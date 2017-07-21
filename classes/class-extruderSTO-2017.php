<?php
/*
   $Id: class-extruderSTO-2017.php v 1.5.6 - RedondoWS $
   FSx-Connector Exporter
   (c) 2016-2017 RWS (www.redondows.com / www.factusol-woocommerce.es)
   -----------------------------------------------------------------------------------------

   WooCommerce
   https://www.woocommerce.com
   ---------------------------------------------------------------------------------------*/

require_once ( _FSXE_PATH_ . 'classes/class-extruder.php' );

class ExtruderSTO_2017 extends Extruder {

	public $fileName   = 'STO';
	public $lastColumn = 'F';


	public function extendColumnRange( $columnRange ) {	

		if ( $this->extra_data['FSXE_EXTRA_LEFT'] > 0 ) 
			$columnRange = array_merge(array( '1', '0' ), $columnRange);

		return $columnRange;
		
	}


	/* ********************************************************************************************* */

	public function columnA( $onlySchema = false) {
		$template = array (
					  'column' => 'A',
					  'name'   => 'Código de artículo',
					  'size'   => '13',
					  'type'   => 'A',
					  'notes'  => 'Campos índice, no puede haber duplicados',
					 );
		if ( $onlySchema ) return $template;

		$value = $this->columnCODART();

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnB( $onlySchema = false) {
		$template = array (
					  'column' => 'B',
					  'name'   => 'Código de Almacén',
					  'size'   => '3',
					  'type'   => 'A',
					  'notes'  => '',
					 );
		if ( $onlySchema ) return $template;

		$value = $this->extra_data['FSOL_AUSCFG'];

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnC( $onlySchema = false) {
		$template = array (
					  'column' => 'C',
					  'name'   => 'Stock mínimo',
					  'size'   => '12-2',
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
					  'name'   => 'Stock máximo',
					  'size'   => '12-2',
					  'type'   => 'ND',
					  'notes'  => '',
					 );
		if ( $onlySchema ) return $template;

		$value = '';

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnE( $onlySchema = false) {
		$template = array (
					  'column' => 'E',
					  'name'   => 'Stock actual',
					  'size'   => '12-2',
					  'type'   => 'ND',
					  'notes'  => '',
					 );
		if ( $onlySchema ) return $template;

		$value = $this->columnStock();

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnF( $onlySchema = false) {
		$template = array (
					  'column' => 'F',
					  'name'   => 'Stock disponible',
					  'size'   => '12-2',
					  'type'   => 'ND',
					  'notes'  => '',
					 );
		if ( $onlySchema ) return $template;

		$value = '';

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */


}	// class ExtruderART ENDS