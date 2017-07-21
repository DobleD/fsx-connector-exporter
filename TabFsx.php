<?php
/*
   $Id: TabFsx.php v 1.6.8 - RedondoWS $
   FSx-Connector Exporter
   (c) 2016-2017 RWS (www.redondows.com / www.factusol-woocommerce.es)
   -----------------------------------------------------------------------------------------

   WooCommerce
   https://www.woocommerce.com
   ---------------------------------------------------------------------------------------*/

// Some handy constants
define( '_FSXE_URL_',     plugin_dir_url( __FILE__ ) ); // with trailing slash
define( '_FSXE_PATH_',    plugin_dir_path( __FILE__ )); // with trailing slash
define( '_FSXE_FILE_',   'FSx-Exporter.php');
// Path to the main plugin file: __FILE__ = _FSX_PATH_ . _FSX_FILE_


  // Handy functions


  // Form helpers
if (!function_exists('fsx_selected'))
{
  function fsx_selected( $selected, $current = true ) {
      return selected( $selected, $current, false ) ? 'selected="selected"' : '' ;
  }
}


/************************************************************************/

////
// Miscellaneous functions

if (!function_exists('rws_r'))
{
  function rws_r($data, $flag = false){

    echo '<pre>';print_r($data);echo '</pre>';
    if ( $flag) die();
  }
}


  @include_once ('TabFsx_local.php');

