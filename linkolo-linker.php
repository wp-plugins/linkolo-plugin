<?php

/*
  Plugin Name: Linkolo Linker
  Description: Plugin allows for quick system integration Linkolo.pl with a blog based on Wordpress. Linkolo.pl is a system that allows the owners of websites make money on the links in the content of the publication of articles on their sites.
  Plugin pozwala na szybką integrację systemu Linkolo.pl z blogiem opartym na Wordpress. Linkolo.pl jest systemem pozwalającym właścicielom stron zarabiać na publikacji linków w treści artykułów na ich stronach.
  Author: Artur Pleskot
  Version: 2.3.2
  Author URI: http://seopower.pl/
 */

define('SPWR_PLUGIN_DIR', dirname(__FILE__) . '/');
define('SPWR_FILE_NAME_LABEL', 'spwr_file_name');

add_filter("the_content", 'swpr_show_links', 9);
add_action('admin_menu', 'linkolo_admin');
load_plugin_textdomain('linkolo-linker', false, basename(dirname(__FILE__)) . '/languages');

require_once SPWR_PLUGIN_DIR . 'functions.php';
require_once SPWR_PLUGIN_DIR . 'widget.php';
add_action('widgets_init', create_function('', 'register_widget("Linkolo_widget");'));

register_activation_hook(__FILE__, 'linkolo_activate');

?>