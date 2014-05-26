<?php

gatekeeper();

elgg_load_library('remotefriends');

$user = elgg_get_logged_in_user_entity();

if ($uuid = get_input('uuid')) {
    
    if (!$new_user = get_user_by_profile_UUID($uuid))
    {
	// No user found, so create it if it's remote
	if (!is_local_uuid($uuid)) {
	    error_log("*** Creating new remote user");

	    $new_user = new ElggUser();
	    $new_user->subtype = 'remote_user';
	    
	    $new_user->username = get_input('nickname');
	    $new_user->name = get_input('name');
	    $new_user->email = get_input('email');
	    $new_user->access_id = ACCESS_PUBLIC;
	    
	    $new_user->salt = generate_random_cleartext_password();
	    $new_user->password = generate_random_cleartext_password(); // Remote users can't log in
	    
	    $new_user->owner_guid = 0; // Users aren't owned by anyone, even if they are admin created.
	    $new_user->container_guid = 0; // Users aren't contained by anyone, even if they are admin created.
	    $new_user->language = get_current_language();
	    
	    $new_user->uuid = $uuid; // Finally, save their actual UUID
            $new_user->website = $uuid;
            
            $new_user->profile_icon = get_input('profile_icon');
            
	    if (!$new_user->save())
		register_error("There was a problem saving the new remote user.");
	}
	else
	    register_error("Sorry, local users can't be created, but you shouldn't see this message!");
	
    } else
	error_log("*** New user found as " . $new_user->uuid);
    
    if ($new_user) {
		    
	error_log("**** Trying a follow");

	if ($user->addFriend($new_user->guid)) {

	    error_log("*** User added to following");

	    system_message("You are now following " . $new_user->name);
	    
	    forward('friends/' . elgg_get_logged_in_user_entity()->username);
	} else
	    register_error('Could not follow user for some reason (probably already following)');
    } else
	register_error('Sorry, that user doesn\'t exist!');
}
else 
    register_error("No UUID, please try that again!");

forward(REFERER);
