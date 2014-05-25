<?php

/**
 * Provides remote friending support to elgg, using MF2 discovery, in a way similar
 * to Known.
 *
 * @licence GNU Public License version 2
 * @link https://github.com/mapkyca/elgg-remote-friends
 * @link http://www.marcus-povey.co.uk
 * @link http://microformats.org/wiki/microformats-2
 * @author Marcus Povey <marcus@marcus-povey.co.uk>
 */
elgg_register_event_handler('init', 'system', function() {

    // Load those files
    require_once(dirname(__FILE__) . '/vendor/url/src/webignition/Url/Url.php');
    require_once(dirname(__FILE__) . '/vendor/absolute-url-deriver/src/webignition/AbsoluteUrlDeriver/AbsoluteUrlDeriver.php');
    require_once(dirname(__FILE__) . '/vendor/php-mf2/Mf2/Parser.php');

    // Register libraries
    elgg_register_library('remotefriends', dirname(__FILE__) . '/lib/remote-friends.php');

    // Add friend action
    elgg_register_action('remotefriends/addfriend', dirname(__FILE__) . '/actions/addfriend.php');

    // Register CSS
    elgg_register_css('remotefriends.css', elgg_get_simplecache_url('css', 'remotefriends'));

    // Register bookmark page handler
    elgg_register_page_handler('remotefriends', function($page) {

	if (isset($page[0])) {

	    elgg_load_library('remotefriends');
	    elgg_load_css('remotefriends.css');

	    switch ($page[0]) {

		// Find available friends
		case 'findfriends' :

		    gatekeeper();

		    $user = elgg_get_logged_in_user_entity();
		    $url = get_input('u');

		    if ($content = file_get_contents($url)) {

			$parser = new \Mf2\Parser($content, $u);

			if ($return = $parser->parse()) {

			    if (isset($return['items'])) {
				$body = '';
				$hcard = [];

				findHcard($return['items'], $hcard);
				$hcard = removeDuplicateProfiles($hcard);

				if (!count($hcard))
				    throw new Exception("Sorry, could not find any users on that page, perhaps they need to mark up their profile in <a href=\"http://microformats.org/wiki/microformats-2\">Microformats</a>?"); // TODO: Add a manual way to add the user

				foreach ($hcard as $card)
				    $body .= elgg_view_form('remotefriends/addfriend', [], ['mf2' => $card]);

				echo elgg_view_page('Friends found', elgg_view_layout('content', array(
				    'content' => $body,
				    'title' => 'Friends found',
				    'filter' => ''
				)));
			    } else {
				register_error("Sorry, there was a problem parsing the page!");
				forward();
			    }
			} else {
			    register_error("Sorry, $url could not be retrieved!");
			    forward();
			}
		    } else {
			register_error("Sorry, $url could not be retrieved!");
			forward();
		    }

		    break;
	    }
	}

	return true;
    });
});
