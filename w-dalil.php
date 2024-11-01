<?php
/**
Plugin Name: FW - DALIL
Plugin URI: http://destination-ist.com/
Text Domain: w-dalil
Description: Dalil is a plugin to build like a Phone Directory and we can say it is a little more ,for Emails addresses and websites,Ability to users add their own directory .
searching categories and much more features included
Author: Ghiath Alkhaled
Version: 2.0
*/


define('MAX_UPLOAD_SIZE', 1000000);
define('TYPE_WHITELIST', serialize(array(
  'image/jpeg',
  'image/png',
  'image/gif'
  )));
define( 'DALILDIR' , plugin_dir_path(  __FILE__ ) );
define( 'DALILURL' , plugin_dir_url(  __FILE__ ) );
define( 'DALIL_INCLUDES' , plugin_dir_path(  __FILE__ ).'includes/' );


add_action( 'plugins_loaded', 'w_dalil_textdomain' );
function w_dalil_textdomain() {
  load_plugin_textdomain( 'w_dalil', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
}

require_once(DALIL_INCLUDES . 'w-dalil-functions.php');
require_once(DALIL_INCLUDES . 'w-dalil-shortcodes.php');
require_once(DALIL_INCLUDES . 'w-dalil-metabox.php');
require_once(DALIL_INCLUDES . 'w-dalil-init.php');







?>
