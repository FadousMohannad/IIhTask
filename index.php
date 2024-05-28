<?php

function parseDate($dateStr) {
    return DateTime::createFromFormat('d/m/Y', $dateStr);
}

function calculatePrices($searchStart, $searchEnd, $priceIntervals) {
    $searchStart = parseDate($searchStart);
    $searchEnd = parseDate($searchEnd);

    $dayPrices = [];

    foreach ($priceIntervals as $interval) {
        $startDate = parseDate($interval['start_date']);
        $endDate = parseDate($interval['end_date']);
        $intervalPrice = $interval['price'];

        $overlapStart = max($startDate, $searchStart);
        $overlapEnd = min($endDate, $searchEnd);

        $overlapDays = max(0, intval(($overlapEnd->getTimestamp() - $overlapStart->getTimestamp()) / (24 * 60 * 60))) + 1;

        $currentDate = $overlapStart;
        for ($i = 0; $i < $overlapDays; $i++) {
            $dayString = $currentDate->format('d/m/Y');
            $dayPrices[$dayString] = $intervalPrice;
            $currentDate->modify('+1 day');
        }
    }

    $totalPrice = array_sum($dayPrices);

    return $totalPrice;
}


function main() {
    $priceIntervals = [
        ['start_date' => '05/01/2024', 'end_date' => '15/01/2024', 'price' => 5],
        ['start_date' => '01/01/2024', 'end_date' => '11/01/2024', 'price' => 3],
        ['start_date' => '03/01/2024', 'end_date' => '13/01/2024', 'price' => 10],
        ['start_date' => '02/01/2024', 'end_date' => '11/01/2024', 'price' => 4],
    ];

    $searchStart = '01/01/2024';
    $searchEnd = '15/01/2024';

    $totalPrice = calculatePrices($searchStart, $searchEnd, $priceIntervals);


    echo "\nTotal price: $" . $totalPrice . "\n";
}

main();

?>
