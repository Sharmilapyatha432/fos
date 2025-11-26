<?php
// Load-balanced delivery assignment tailored to this project schema.
// Chooses an approved delivery person with the fewest active deliveries and records the assignment.

function pickDriverByLoad(mysqli $conn): ?array {
    $sql = "
        SELECT
            dp.dpid, 
            dp.fullname,
            COALESCE(SUM(CASE WHEN UPPER(o.delivery_status) IN ('PENDING','SHIPPED') THEN 1 ELSE 0 END), 0) AS active_deliveries,
            COALESCE(MAX(da.assigned_time), '1970-01-01') AS last_assigned_at
        FROM delivery_person AS dp
        LEFT JOIN deliveryassignment AS da ON da.dpid = dp.dpid
        LEFT JOIN orders AS o ON o.order_id = da.order_id
        WHERE dp.status = 'Approved'
        GROUP BY dp.dpid, dp.fullname
        ORDER BY active_deliveries ASC, last_assigned_at ASC
        LIMIT 1
    ";

    $result = $conn->query($sql);
    if (!$result || $result->num_rows === 0) {
        return null;
    }
    return $result->fetch_assoc();
}

/**
 * Attempts to assign an order to the least-loaded approved driver.
 * Returns: ['assigned' => bool, 'driver' => array|null, 'message' => string]
 */
function assignOrderToDriver(mysqli $conn, int $orderId): array {
    $check = $conn->prepare("SELECT dpid FROM deliveryassignment WHERE order_id = ? LIMIT 1");
    $check->bind_param('i', $orderId);
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
        $check->close();
        return [
            'assigned' => true,
            'driver' => null,
            'message' => 'Order already assigned.'
        ];
    }
    $check->close();

    $driver = pickDriverByLoad($conn);
    if (!$driver) {
        return [
            'assigned' => false,
            'driver' => null,
            'message' => 'No approved delivery person is available right now.'
        ];
    }

    $assign = $conn->prepare("
        INSERT INTO deliveryassignment (order_id, dpid, assigned_time)
        VALUES (?, ?, NOW())
    ");
    $assign->bind_param('ii', $orderId, $driver['dpid']);

    if (!$assign->execute()) {
        return [
            'assigned' => false,
            'driver' => $driver,
            'message' => 'Could not save the assignment.'
        ];
    }

    return [
        'assigned' => true,
        'driver' => $driver,
        'message' => ''
    ];
}
?>
