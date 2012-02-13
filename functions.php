<?php
  
function swpr_show_links($content) {

	$spwr_file = get_option(SPWR_FILE_NAME_LABEL);

	if (is_singular() && is_file($spwr_file)) {
		
		require_once $spwr_file;
		return spwrPrintArticle($content);
		
	} else return $content;
	
}

function linkolo_trigger_error($message, $errno) {

    if(isset($_GET['action']) && $_GET['action'] == 'error_scrape') {

        echo '<strong>' . $message . '</strong>';

        exit;
        
    } else {

        trigger_error($message, $errno);
        
    }
}

function linkolo_admin() {

	add_options_page('Linkolo Linker Options', 'Linkolo Linker', 'manage_options', 'my-unique-identifier', 'linkolo_linker_options');

}

function linkolo_activate() {

    $path_array = explode("/", dirname(__FILE__));
    $count = count($path_array);
    for ($i = array_search('wp-content', $path_array); $i <= $count; $i++)
        unset($path_array[$i]);

    $dirname = implode("/", $path_array);
    if (empty($dirname)) $dirname='/';
    
    if (($dh = opendir($dirname))) {
        while (($file = readdir($dh)) !== false) {

            if (preg_match("/^cl_[0-9a-z]{16}\.php$/", $file))
                $suggested_file = $file;
        }
        closedir($dh);
    }

    if (empty($suggested_file)) linkolo_trigger_error(_e('We could not find scripts Linkolo on your server. Properly upload our files to the root of Wordpress, and try to re-activate the plugin'), E_USER_ERROR);
    update_option(SPWR_FILE_NAME_LABEL, $dirname.'/'.$suggested_file);

    
}

function linkolo_linker_options() {

	$path_array = explode("/", dirname(__FILE__));
	$count = count($path_array);
	for	($i=array_search('wp-content', $path_array); $i <= $count; $i++) unset($path_array[$i]);
	
	$dirname = implode("/", $path_array);
		
    if (($dh = opendir($dirname))) {
        while (($file = readdir($dh)) !== false) {
        	
        	if (preg_match("/^cl_[0-9a-z]{16}\.php$/", $file)) $suggested_file = $file;
        	if (preg_match("/^cl_[0-9a-z]{16}$/", $file)) $suggested_folder = $file;
        	
        }
        closedir($dh);
    }
	
	if (!current_user_can('manage_options'))  {
		wp_die( __('Sorry. You don\'t have privileges to edit this page.', 'linkolo-linker') );
	}
	  
	
	
	if (!empty($_POST[SPWR_FILE_NAME_LABEL])) {
	 	$spwr_file_name_val = $_POST[SPWR_FILE_NAME_LABEL];
		update_option(SPWR_FILE_NAME_LABEL, $spwr_file_name_val);
?>
<div class="updated"><p><strong><?php _e('Changes are saved', 'linkolo-linker' ); ?></strong></p></div>
<?php

 	} else {
 		
 		$spwr_file_name_val = get_option(SPWR_FILE_NAME_LABEL);
 		
 	}

  
?>

<div class="wrap">
<h2><?php _e('Linkolo Linker Options', 'linkolo-linker') ?></h2>
<?php if (!empty($suggested_file)) : ?>
<p>
	<?php _e('In folder', 'linkolo-linker'); ?><br /><br />
	<strong><?php echo $dirname; ?></strong><br /><br />
	<?php _e('we found file', 'linkolo-linker'); ?><br /><br />
	<strong><?php echo $suggested_file; ?></strong>.<br /><br />
	<?php _e('This is probably your setup file', 'linkolo-linker'); ?>. <br /><br />
	<?php _e('Remember the folder with data files must have write permissions (eg chmod 777)', 'linkolo-linker'); ?>.
</p>
<?php endif; ?>
<form name="linkolo-form" method="post" action="">
<?php if (!empty($suggested_file)) : ?>
<input type="hidden" name="suggested_file" value="<?php echo $dirname.'/'.$suggested_file; ?>">
<?php endif; ?>
<p><?php _e("Path to Linkolo installation files :", 'linkolo-linker'); ?> 
<input type="text" name="<?php echo SPWR_FILE_NAME_LABEL; ?>" value="<?php echo $spwr_file_name_val; ?>" size="100">
</p><hr />

<p class="submit">
<?php if (!empty($suggested_file)) : ?>
<input type="button" onclick="insertfile(this.form)" class="button-primary" value="<?php _e('Use suggested file', 'linkolo-linker'); ?>" />
<?php endif; ?>
<input type="submit" name="Submit" class="button-primary" value="<?php _e('Save Changes', 'linkolo-linker') ?>" />
</p>

</form>
</div>
<script type="text/javascript">
	function insertfile(form) {
		
		form.<?php echo SPWR_FILE_NAME_LABEL; ?>.value = form.suggested_file.value;
		form.submit();
		
	}
</script>
	
<?php 
	
}
