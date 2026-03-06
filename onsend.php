<?php
/**
 * Plugin Name:       OnSend (Community Fork)
 * Description:       Send messaging notifications to your customers through OnSend.
 * Version:           1.1.1
 * Requires at least: 6.9
 * Requires PHP:      8.0
 * Author:            OnPay Solutions
 * Author URI:        https://onsend.io/
 * Maintainer:        Your GitHub Username
 * License:           GNU General Public License v3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       onsend
 */

if ( !defined( 'ABSPATH' ) ) exit;

if ( class_exists( 'OnSend' ) ) return;

define( 'ONSEND_FILE', __FILE__ );
define( 'ONSEND_URL', plugin_dir_url( ONSEND_FILE ) );
define( 'ONSEND_PATH', plugin_dir_path( ONSEND_FILE ) );
define( 'ONSEND_BASENAME', plugin_basename( ONSEND_FILE ) );
define( 'ONSEND_VERSION', '1.0.0' );

// Plugin core class
require( ONSEND_PATH . 'includes/class-onsend.php' );
