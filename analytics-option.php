<?php 
//SVN: $Id: analytics-option.php 118331 2009-05-16 17:26:36Z imthiaz $
$accountProfiles = array();
if(!empty($this->username) && !empty($this->password)){
	if(empty($this->profile)){
		$profileMessage = __(" Please select your analytics profile.");
	}
	$accountProfiles = $this->getProfiles();
}
$accountProfiles = array(""=>array('title'=>__("Choose your profile"))) + $accountProfiles;
if ( !empty($_POST ) ) { 
?>
<div id="message" class="updated"><p><strong><?php echo __('Options saved.') . $profileMessage ;?></strong></p></div>
<?php } ?>
<div class="wrap">
<h2><?php _e('Google Analytics Reports Plugin Options'); ?></h2>

<form action="" method="post">
<h3><label for="key"><?php _e('Login Email ID'); ?></label></h3>
<p><input type="text" name="wp-analytics-login-email" value="<?php echo get_option('wp-analytics-login-email'); ?>" /></p>

<h3><label for="key"><?php _e('Login Password'); ?></label></h3>
<p><input type="password" name="wp-analytics-login-password" value="<?php echo get_option('wp-analytics-login-password'); ?>" /></p>

<h3><label for="key"><?php _e('Analytics Profile'); ?></label></h3>
<p><select name="wp-analytics-profile">
<?php if(!empty($accountProfiles)){
	foreach ($accountProfiles as $profileId => $profileData){
?>
<option value="<?php echo $profileId;?>" <?php if($this->profile==$profileId):?> selected="selected" <?php endif;?>><?php echo $profileData['title']?></option>
<?php }
}
?>
</select>
<p class="submit"><input type="submit" name="submit" value="<?php _e('Update options &raquo;'); ?>" /></p>
</form>
<h3><?php _e('Donations'); ?></h3>
<p><?php _e('If you really like the plugin and if you want to help you can contribute some dollars.');?></p>
<p>
<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=5331475" title="PayPal - The safer, easier way to pay online!">
<img alt="PayPal - The safer, easier way to pay online!" border="0" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif">
</a>
</p>
</div>
