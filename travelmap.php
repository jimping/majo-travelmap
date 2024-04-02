<?php
/**
 * Plugin Name:     Travelmap Connector
 * Plugin URI:      https://splintnet.de
 * Description:     Majo
 * Author:          splintnet
 * Author URI:      https://splintnet.de
 * Text Domain:     travelmap
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Travelmap
 */

// Your code starts here.

define('TRAVELMAP_GOOGLE_MAPS_API_KEY', 'AIzaSyBMeRMnBG6oMZv0Hl_9VZI5QXN4_O65rCk');

require('post-types/trips.php');
require('post-types/campingsites.php');


require('rest/trips.php');
require('rest/campingsites.php');
require('rest/wp-posts.php');
require('rest/posts.php');


require('taxonomies/locations.php');

// load languages
add_action('plugins_loaded', 'travelmap_load_textdomain');
function travelmap_load_textdomain()
{
    load_plugin_textdomain('travelmap', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
