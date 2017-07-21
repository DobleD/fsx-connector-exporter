<?php
/*
   $Id: class-extruderLTA-2017.php v 1.5.6 - RedondoWS $
   FSx-Connector Exporter
   (c) 2016-2017 RWS (www.redondows.com / www.factusol-woocommerce.es)
   -----------------------------------------------------------------------------------------

   WooCommerce
   https://www.woocommerce.com
   ---------------------------------------------------------------------------------------*/

require_once ( _FSXE_PATH_ . 'classes/class-extruder.php' );

class ExtruderLTA_2017 extends Extruder {

	public $fileName   = 'LTA';
	public $lastColumn = 'D';


	public function extendColumnRange( $columnRange ) {	

		if ( $this->extra_data['FSXE_EXTRA_LEFT'] > 0 ) 
			$columnRange = array_merge(array( '1', '0' ), $columnRange);

		return $columnRange;
		
	}


	/* ********************************************************************************************* */

	public function columnA( $onlySchema = false) {
		$template = array (
					  'column' => 'A',
					  'name'   => 'Código de Tarifa',
					  'size'   => '2',
					  'type'   => 'N',
					  'notes'  => 'Campos índice, no puede haber duplicados',
					 );
		if ( $onlySchema ) return $template;

		$value = $this->extra_data['FSOL_TCACFG'];

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnB( $onlySchema = false) {
		$template = array (
					  'column' => 'B',
					  'name'   => 'Artículo',
					  'size'   => '13',
					  'type'   => 'A',
					  'notes'  => '',
					 );
		if ( $onlySchema ) return $template;

		$value = $this->columnCODART();

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnC( $onlySchema = false) {
		$template = array (
					  'column' => 'C',
					  'name'   => 'Margen',
					  'size'   => '3-2',
					  'type'   => 'ND',
					  'notes'  => '',
					 );
		if ( $onlySchema ) return $template;

		$value = '';

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnD( $onlySchema = false) {
		$template = array (
					  'column' => 'D',
					  'name'   => 'Precio',
					  'size'   => '12-2',
					  'type'   => 'ND',
					  'notes'  => '',
					 );
		if ( $onlySchema ) return $template;

		$value = $this->columnPrecio();

		return $value;
	}

	/* ********************************************************************************************* */


}	// class ExtruderART ENDS