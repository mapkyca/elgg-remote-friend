<?php

$mf2_user = $vars['mf2'];

$properties = $mf2_user['properties'];
//if (isset($mf2_user['properties']['author'])) // If there's an author, then use that
     //$properties =$mf2_user['properties']['author'][0];

$name = $properties['name'][0];
$urls = array_unique($properties['url']);
$photo =  $properties['photo'][0];

$email =  $properties['email'][0];
if (strpos('mailto:', 'mailto:')!==false) $email = substr($email, 7); // Sanitise email

$nickname =  $properties['nickname'][0];

?>
<div class="elgg-item elgg-image-block following-user">

	<div class="elgg-image">
	    <div class="elgg-avatar elgg-avatar-small"><a><img class="u-photo" src="<?= $photo ?>" /></a>
	    </div>
	</div>
	<div class="elgg-body">
	    <h3><?= $name; ?></h3>
	    <div class="e-content entry-content">
		<?php
		foreach ($urls as $url) {
		    ?>
    		<input type="hidden" name="urls[]" value="<?= $url; ?>" />
		    <?php
		}
		?>
		<div class="control-group">
		    <label class="control-label" for="inputName">Name</label>

		    <div class="controls">
			<input id="inputName" type="text" class="span4" name="name" required
			       value="<?= htmlspecialchars($name) ?>">
		    </div>
		</div>
		
		<div class="control-group">
		    <label class="control-label" for="inputNickname">Nickname</label>

		    <div class="controls">
			<input id="inputNickname" type="text" class="span4" name="nickname" required placeholder="Handle (for your reference)"
			       value="<?= htmlspecialchars($nickname) ?>">
		    </div>
		</div>
		
		<div class="control-group">
		    <label class="control-label" for="inputEmail">Email</label>

		    <div class="controls">
			<input id="inputName" type="email" class="span4" name="email" required
			       value="<?= htmlspecialchars($email) ?>">
		    </div>
		</div>
		
		<div class="control-group">
		    <label class="control-label" for="inputUrl">Profile URL</label>
		    
		    <div class="controls">
			<?php if (count($urls)>1) { ?>
			<select name="uuid">
			    <?php
				foreach($urls as $url) {
				    ?>
			    <option><?=$url;?></option>
			    <?php
				}
			    ?>
			</select>
			<?php } else { ?>
			    <a href="<?= $url; ?>" target="_blank"><?= $url; ?></a>
			    <input type="hidden" name="uuid" value="<?= $url; ?>" />
			<?php } ?>
		    </div>

		</div>
		
		<div class="control-group">
		    <div class="controls">
			<input type="submit" class="btn" value="Add as friend..." />
		    </div>
		</div>
	    </div>
	    <div class="footer">

	    </div>

	</div>

    </div>