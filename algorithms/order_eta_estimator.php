<?php
// Prep + travel ETA helper. Gives a simple minute estimate you can show on order confirmation.

function haversineDistanceKm(float $lat1, float $lon1, float $lat2, float $lon2): float {
    $earthRadius = 6371; // km
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat / 2) ** 2 +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;
    $c = 2 * asin(min(1, sqrt($a)));
    return $earthRadius * $c;
}

function estimateEtaMinutes(array $orderItems, array $restaurantCoords, array $customerCoords, float $avgSpeedKmh = 25.0, int $handoffBuffer = 5): int {
    $longestPrep = 0;
    foreach ($orderItems as $item) {
        // Each item: ['name' => 'Burger', 'prep_minutes' => 12, 'quantity' => 2]
        $prepPerItem = isset($item['prep_minutes']) ? (int)$item['prep_minutes'] : 0;
        $longestPrep = max($longestPrep, $prepPerItem);
    }

    $distanceKm = haversineDistanceKm(
        $restaurantCoords['lat'],
        $restaurantCoords['lon'],
        $customerCoords['lat'],
        $customerCoords['lon']
    );

    $travelMinutes = ($avgSpeedKmh > 0) ? ($distanceKm / $avgSpeedKmh) * 60 : 0;
    $eta = $longestPrep + $handoffBuffer + $travelMinutes;

    return (int)ceil($eta);
}

// Demo: plug in your own numbers or wrap this in an API response.
/*
$orderItems = [
    ['name' => 'Burger', 'prep_minutes' => 12, 'quantity' => 1],
    ['name' => 'Fries',  'prep_minutes' => 6,  'quantity' => 1],
];
$restaurantCoords = ['lat' => 40.7128, 'lon' => -74.0060];
$customerCoords   = ['lat' => 40.7306, 'lon' => -73.9352];

$etaMinutes = estimateEtaMinutes($orderItems, $restaurantCoords, $customerCoords);
echo 'Estimated ETA: ' . $etaMinutes . ' minutes';
*/
?>
