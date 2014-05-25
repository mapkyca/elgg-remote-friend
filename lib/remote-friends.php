<?php

/**
 * When passed an array of MF2 data, recursively find hcard entries.
 * @param array $mf2
 * @param array $out
 */
function findHcard(array $mf2, array &$out) {
    foreach ($mf2 as $item) {
	// Find h-card
	if (in_array('h-card', $item['type']))
	    $out[] = $item;
	if (isset($item['children']))
	    findHcard($item['children'], $out);
    }
}

/**
 * Go through the list of found hcards and remove duplicates (based on unique profile urls)
 * @param array $hcards
 * @return array
 */
function removeDuplicateProfiles(array $hcards) {
    $cards = [];

    foreach ($hcards as $card) {
	$key = serialize($card['properties']['url']);
	if (!isset($cards[$key]))
	    $cards[$key] = $card;
    }

    return $cards;
}
