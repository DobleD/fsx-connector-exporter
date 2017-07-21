<?php
/*
	Plugin Name: FSx-Connector Exporter
	Description: Product Exporter to FactuSOL for WooCommerce.
	Plugin URI: http://fsxtutorial.factusol-woocommerce.es/index.php?tab=fsxexp
	
	Version: 1.6.9
	
	Copyright: (c) RedondoWS
	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
	
	Author: RedondoWS
	Author URI: http://www.RedondoWS.com

	Text Domain: FSx-Exporter
	Domain Path: /languages
*/


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Load project definitions
require_once( plugin_dir_path( __FILE__ ).'TabFsx.php' );

// include the main class
require_once _FSXE_PATH_ . 'classes/class-fsxExporterART.php';

/**
 * Check if WooCommerce is active
 **/
if (!function_exists('fsx_active_nw_plugins'))
{
	// Get active network plugins - From Novalnet Payment Gateway
	function fsx_active_nw_plugins() {
		if (!is_multisite())
			return false;
		$wse_activePlugins = (get_site_option('active_sitewide_plugins')) ? array_keys(get_site_option('active_sitewide_plugins')) : array();
		return $wse_activePlugins;
	}
}

if (    in_array('woocommerce/woocommerce.php', (array) get_option('active_plugins')) 
	 || in_array('woocommerce/woocommerce.php', (array) fsx_active_nw_plugins())) {

	if (is_admin()) {
		// Do the Mambo!
		$fsx_exporter = new fsxExporterART();
	}

}	
