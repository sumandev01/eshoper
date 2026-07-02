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

if (!function_exists('clean_html')) {
    function clean_html($html)
    {
        if (empty($html)) return $html;
        $allowed_tags = '<p><a><b><i><strong><em><ul><li><ol><br><img><h1><h2><h3><h4><h5><h6><span><div><table><tr><td><th><tbody><thead><tfoot><hr><blockquote>';
        return strip_tags($html, $allowed_tags);
    }
}
