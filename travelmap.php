<?php
/**
 * Plugin Name:     TravelMap Connector
 * Plugin URI:      https://splintnet.de
 * Description:     Connector for TravelMap
 * Author:          splintnet
 * Author URI:      https://splintnet.de
 * Text Domain:     travelmap
 * Domain Path:     /languages
 * Version:         3.0.0
 *
 * @package         Travelmap
 */

// Your code starts here.

// load languages
add_action('plugins_loaded', 'travelmap_load_textdomain');
function travelmap_load_textdomain()
{
    load_plugin_textdomain('travelmap', false, dirname(plugin_basename(__FILE__)) . '/languages');
}


require('libs/rest.php');
require('libs/config.php');


require('post-types/trips.php');
require('post-types/campingsites.php');


require('rest/trips.php');
require('rest/campingsites.php');
require('rest/wp-posts.php');
require('rest/posts.php');


require('taxonomies/locations.php');
