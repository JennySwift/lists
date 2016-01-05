<?php

/**
 * Merge two array together, passing the second array through array filter to remove null values
 * @param array $base
 * @param array $newItems
 * @return array
 */
function array_compare(array $base, array $newItems)
{
    return array_merge($base, array_filter($newItems, 'removeFalseKeepZero'));
}

/**
 * For array_filter(), when I don't want values that are 0 to be removed
 * @param $value
 * @return bool
 */
function removeFalseKeepZero($value)
{
    return $value || is_numeric($value);
}
