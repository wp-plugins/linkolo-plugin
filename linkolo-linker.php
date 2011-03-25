<?php

/*
Plugin Name: Linkolo Linker
Description: Plugin pozwala na szybką integrację systemu Linkolo.pl z blogiem opartym na Wordpress. Linkolo.pl jest systemem pozwalającym właścicielom stron zarabiać na publikacji linków w treści artykułów na ich stronach.
Author: Artur Pleskot
Version: 1.2.1
Author URI: http://seopower.pl/
*/

$spwr_file_name_label = "spwr_file_name";
add_filter("the_content", 'swpr_show_links', 9);
add_action('admin_menu', 'linkolo_admin');

function swpr_show_links($content) {

	global $spwr_file_name_label;

	$spwr_file = get_option($spwr_file_name_label);

	if (is_singular() && is_file($spwr_file)) {
		
		require_once $spwr_file;
		return spwrPrintArticle($content);
		
	} else return $content;
	
}

function linkolo_admin() {

	add_options_page('Linkolo Linker Options', 'Linkolo Linker', 'manage_options', 'my-unique-identifier', 'linkolo_linker_options');

}

function linkolo_linker_options() {

	global $spwr_file_name_label;

	$path_array = explode("/", dirname(__FILE__));
	$count = count($path_array);
	for	($i=array_search('wp-content', $path_array); $i <= $count; $i++) unset($path_array[$i]);
	
	$dirname = implode("/", $path_array);
		
    if ($dh = opendir($dirname)) {
        while (($file = readdir($dh)) !== false) {
        	
        	if (preg_match("/^cl_[0-9a-z]{16}\.php$/", $file)) $suggested_file = $file;
        	if (preg_match("/^cl_[0-9a-z]{16}$/", $file)) $suggested_folder = $file;
        	
        }
        closedir($dh);
    }
	
	if (!current_user_can('manage_options'))  {
		wp_die( __('Niestety. Nie masz odpowiednich uprawnień do edycji tej strony.') );
	}
	  
	
	
	if (!empty($_POST[$spwr_file_name_label])) {
	 	$spwr_file_name_val = $_POST[$spwr_file_name_label];
		update_option($spwr_file_name_label, $spwr_file_name_val);
?>
<div class="updated"><p><strong><?php _e('Zmiany zostały zapisane', 'linkolo_linker_options' ); ?></strong></p></div>
<?php

 	} else {
 		
 		$spwr_file_name_val = get_option($spwr_file_name_label);
 		
 	}

  
?>

<div class="wrap">
<h2><?php _e('Linkolo Linker Options', 'linkolo_linker_options') ?></h2>
<?php if (!empty($suggested_file)) : ?>
<p>
	Plugin Linkolo linker działa poprawnie z Wordpress 2.9.x<br />
	<hr />
	W folderze<br /><br />
	<strong><?php echo $dirname; ?></strong><br /><br />
	Znaleźliśmy plik<br /><br />
	<strong><?php echo $suggested_file; ?></strong>.<br /><br />
	Prawdopodobnie jest to twój plik instalacyjny. <br /><br />
	Pamiętaj też, że folder z plikami danych musi mieć ustawione odpowiednie uprawnienia do zapisu (np. chmod 777).
</p>
<?php endif; ?>
<form name="linkolo-form" method="post" action="">
<?php if (!empty($suggested_file)) : ?>
<input type="hidden" name="suggested_file" value="<?php echo $dirname.'/'.$suggested_file; ?>">
<?php endif; ?>
<p><?php _e("Ścieżka dostępu do pliku instalacyjnego Linkolo :", 'linkolo_linker_options'); ?> 
<input type="text" name="<?php echo $spwr_file_name_label; ?>" value="<?php echo $spwr_file_name_val; ?>" size="100">
</p><hr />

<p class="submit">
<?php if (!empty($suggested_file)) : ?>
<input type="button" onclick="insertfile(this.form)" class="button-primary" value="Użyj sugerowanego pliku" />
<?php endif; ?>
<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
</p>

</form>
</div>
<script type="text/javascript">
	function insertfile(form) {
		
		form.<?php echo $spwr_file_name_label; ?>.value = form.suggested_file.value;
		form.submit();
		
	}
</script>
	
<?php 
	
}

?>