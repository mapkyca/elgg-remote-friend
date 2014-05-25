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

/**
 * Return either a local or remote user based on it's UUID
 */
function get_user_by_profile_UUID($url) {
    $user = false;
    
    // Try and user by profile if local
    if (is_local_uuid($url)) {
	
	if (preg_match("/".str_replace('/','\/', $CONFIG->wwwroot)."profile\/([0-9a-zA-Z]+)\/?/", $url, $match))
		$username = $match[1];
	
	$user = get_user_by_username($username);
    }
    
    // otherwise see if the profile ID has been attached to a user
    if (!$user) {
	if ($entities = elgg_get_entities_from_metadata([
	    'type' => 'user',
	    'metadata_name_value_pairs' => ['name' => 'uuid', 'value' => $url]
	])) {
	    error_log("*** Found a user $url as remote");
	    $user = $entities[0];
	}
    }
    
    return $user;
}

/**
 * Return whether the uuid is a local address.
 */
function is_local_uuid($uuid) {
    if (($uuid_parse = parse_url($uuid)) && ($url_parse = parse_url(elgg_get_site_url()))) {
	if ($uuid_parse['host'] == $url_parse['host']) {
	    return true;
	}
    }

    return false;
}