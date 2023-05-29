<?php

function readableCombination(array $combination)
{
    $combination = str_replace(['[', ']', '"'], '', json_encode($combination));
    $combination = str_replace(',', ' - ', $combination);
    return $combination;
}

function arrayCombination(string $combination)
{
    $combination = str_replace(' ', '', $combination);
    $combination = explode('-', $combination);
    return $combination;
}
