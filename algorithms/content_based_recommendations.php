<?php
// Simple content-based recommender using tag overlap + cosine similarity.
// Works with a small tag list per item; now includes DB helpers for this project.

function tagVector(array $tags): array {
    $vector = [];
    foreach ($tags as $tag) {
        $key = strtolower(trim($tag));
        if ($key === '') {
            continue;
        }
        $vector[$key] = ($vector[$key] ?? 0) + 1;
    }
    return $vector;
}

function cosineSimilarity(array $a, array $b): float {
    $dot = 0;
    $normA = 0;
    $normB = 0;
    $keys = array_unique(array_merge(array_keys($a), array_keys($b)));
    foreach ($keys as $key) {
        $va = $a[$key] ?? 0;
        $vb = $b[$key] ?? 0;
        $dot += $va * $vb;
        $normA += $va * $va;
        $normB += $vb * $vb;
    }
    if ($normA == 0 || $normB == 0) {
        return 0.0;
    }
    return $dot / (sqrt($normA) * sqrt($normB));
}

function buildTagsFromFoodRow(array $row): array {
    $tags = [];
    if (!empty($row['category_name'])) {
        $tags[] = strtolower($row['category_name']);
    }
    $price = isset($row['price']) ? (float)$row['price'] : 0;
    if ($price > 0) {
        $tags[] = $price < 200 ? 'budget' : ($price < 400 ? 'midrange' : 'premium');
    }
    $nameTokens = preg_split('/[^a-zA-Z]+/', strtolower($row['name'] ?? ''), -1, PREG_SPLIT_NO_EMPTY);
    foreach ($nameTokens as $token) {
        if (strlen($token) >= 4) {
            $tags[] = $token;
        }
    }
    return $tags;
}

function loadFoodItemsWithTags(mysqli $conn): array {
    $items = [];
    $sql = "
        SELECT fi.food_id, fi.name, fi.description, fi.price, fi.image, fc.category_name
        FROM fooditem AS fi
        LEFT JOIN foodcategory AS fc ON fc.category_id = fi.category_id
    ";
    if ($result = $conn->query($sql)) {
        while ($row = $result->fetch_assoc()) {
            $items[(int)$row['food_id']] = [
                'id' => (int)$row['food_id'],
                'name' => $row['name'],
                'description' => $row['description'],
                'price' => $row['price'],
                'image' => $row['image'],
                'tags' => buildTagsFromFoodRow($row),
            ];
        }
    }
    return $items;
}

function fetchRecentFoodIdsForCustomer(mysqli $conn, int $customerId, int $limit = 10): array {
    $sql = "
        SELECT DISTINCT od.food_id
        FROM orders AS o
        JOIN orderdetails AS od ON od.order_id = o.order_id
        WHERE o.cid = ?
        ORDER BY o.order_date DESC
        LIMIT ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $customerId, $limit);
    $stmt->execute();
    $res = $stmt->get_result();
    $ids = [];
    while ($row = $res->fetch_assoc()) {
        $ids[] = (int)$row['food_id'];
    }
    return $ids;
}

function buildUserProfile(array $items, array $recentIds): array {
    $aggregate = [];
    foreach ($recentIds as $id) {
        if (!isset($items[$id])) {
            continue;
        }
        $vector = tagVector($items[$id]['tags']);
        foreach ($vector as $tag => $weight) {
            $aggregate[$tag] = ($aggregate[$tag] ?? 0) + $weight;
        }
    }
    return $aggregate;
}

function recommendItems(array $items, array $recentIds, int $limit = 5): array {
    $userProfile = buildUserProfile($items, $recentIds);
    $recommendations = [];
    foreach ($items as $id => $item) {
        if (in_array($id, $recentIds, true)) {
            continue; // skip already seen
        }
        $similarity = cosineSimilarity($userProfile, tagVector($item['tags']));
        $recommendations[] = [
            'id' => $id,
            'name' => $item['name'],
            'price' => $item['price'] ?? null,
            'image' => $item['image'] ?? null,
            'description' => $item['description'] ?? '',
            'score' => round($similarity, 4),
        ];
    }
    usort($recommendations, fn($a, $b) => $b['score'] <=> $a['score']);
    return array_slice($recommendations, 0, $limit);
}

function recommendForCustomer(mysqli $conn, int $customerId, int $limit = 5): array {
    $items = loadFoodItemsWithTags($conn);
    $recentIds = fetchRecentFoodIdsForCustomer($conn, $customerId, 10);
    if (empty($items) || empty($recentIds)) {
        return [];
    }
    return recommendItems($items, $recentIds, $limit);
}
?>
