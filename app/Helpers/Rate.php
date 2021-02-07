<?php

function calculateRateAverage($rates)
{
    $count = count($rates);

    if(!$count){
        return 'Not rated yet';
    }

    $thirtyPercent = round($count / 3);
    $sum = 0;

    for ($i = 0; $i < $count; $i++) {
        $tmpRate = $rates[$i]['rate'];

        if($i < $thirtyPercent){
            $tmpRate *= 2;
        }

        $sum += $tmpRate;
    }

    return $sum / $count;
}

