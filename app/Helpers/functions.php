<?php

function formatBDT($amount)
{
    $amount = (int)$amount;

    $lastThree = substr($amount, -3);

    $restUnits = substr($amount, 0, -3);

    if ($restUnits != '') {
        $restUnits = preg_replace("/\B(?=(\d{2})+(?!\d))/", ",", $restUnits);
        return $restUnits . "," . $lastThree;
    }

    return $lastThree;
}
