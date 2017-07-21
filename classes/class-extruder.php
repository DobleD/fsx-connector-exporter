<?php
/*
   $Id: class-extruder.php v 1.5.6 - RedondoWS $
   FSx-Connector Exporter
   (c) 2016-2017 RWS (www.redondows.com / www.factusol-woocommerce.es)
   -----------------------------------------------------------------------------------------

   WooCommerce
   https://www.woocommerce.com
   ---------------------------------------------------------------------------------------*/

class Extruder {

	public $fileName   = '';
	public $lastColumn = '';

	public $product;
	public $variation;
	public $product_meta;
	public $variation_meta;

	public $extra_data;


	// Init the class
	public function __construct( $extra_data = array() ) {	

		$this->extra_data = $extra_data;
		
	}

	// Class Processor
	public function process( WC_Product $product, WC_Product_Variation $variation = NULL ) {	

		$this->product   = $product;
		$this->variation = $variation;

		$this->product_meta   = version_compare( WC_VERSION, '3.0', '>=' ) ?
								$product->get_meta() :							// get_meta($key);
								get_post_meta( $this->column1() );				// get_post_meta($tpost_id, $key, true);

		if ($variation) 
			$this->variation_meta = version_compare( WC_VERSION, '3.0', '>=' ) ?
								$variation->get_meta() :
								get_post_meta( $this->column0() );
		else
			$this->variation_meta = array();

		$slab = array();

		foreach ( $this->getColumnRange() as $col ) {
			//
			$slab[] = $this->getColumnValue( $col );
		}

		return $slab;
		
	}

	// Class Processor Helper
	public function getColumnRange( ) {	

		$upper_limit = $this->lastColumn;  // Hasta 'AW'

		$ulen = strlen($upper_limit);

		if ( !$ulen || ($ulen>2) ) return array();

		$v=str_split($upper_limit);

		if (isset($v[1]))
			if ( !(($v[0]=='B') || ($v[0]=='A')) ) return array();

		$columnRange = array();
		$limit = $v[0];
		$stub = '';

		if (isset($v[1])) {
			$columnRange = array_merge($columnRange, range('A', 'Z'));
			$stub = 'A';
			$limit = $v[1];
			if ($v[0]=='B') {
				foreach(range('A', 'Z') as $val){
					$columnRange[] = 'A' . $val;
				}
				$stub = 'B';
			}
		}

		foreach(range('A', $limit) as $val){
			$columnRange[] = $stub . $val;
		}

		// Testing
		//	$columnRange = array('A', 'E', 'F', 'J', 'T', 'AD', 'AK', 'AQ');

		return $this->extendColumnRange( $columnRange );
		
	}

	public function extendColumnRange( $columnRange ) {	

		if ( $this->extra_data['FSXE_EXTRA_RIGHT'] > 0 ) 
			$columnRange = array_merge($columnRange, array( 'Precio', 'Stock' ));

		if ( $this->extra_data['FSXE_EXTRA_LEFT'] > 0 ) 
			$columnRange = array_merge(array( '1', '0' ), $columnRange);

		return $columnRange;
		
	}

	// Class Processor Helper 1
	public function getColumnValue( $column = '' ) {	

		// $this->product   = $product;
		$value = false;
		if ( method_exists($this, 'column'.$column) )
			$value = call_user_func( array($this, 'column'.$column) );
		return $value;
		
	}

	// Class Processor Helper 2
	public function getColumnSchema( $column = '' ) {	

		$value = array();
		if ( method_exists($this, 'column'.$column) )
			$value = call_user_func( array($this, 'column'.$column), true );
		return $value;
		
	}

	/*
		10. Las abreviaturas de los tipos de datos son:
		
		- A: Alfanumérico
		- N: Numérico
		- ND: Numérico con decimal :: sólo pueden contener caracteres numéricos, el punto decimal (,), y los signos + o -.
		- F: Fecha; Formato fecha: DD/MM/AAAA
		- Formato hora: HH:MM
	*/
	public function formatColumn( $value, $size, $type ) {	

		$val = trim($value);

		if ( strlen($val) )
		switch ( $type ) {
			case 'A':
				if ( strlen($val) > $size ) 
					if ($this->extra_data['FSXE_TRUNCATE_STR']) 
						$val = substr($val, 0, $size);
					else
						if ($this->extra_data['FSXE_VERBOSE'])
							$val = $val.' es una cadena demasiado grande. Máximo tamaño: '.$size;
					if ($this->extra_data['FSXE_OUTPUT']=='csv') $val = '"'.$val.'"';
				break;
			case 'N':
				$val = intval($val);
				if ( strlen(strval($val)) > $size ) 
					if ($this->extra_data['FSXE_VERBOSE'])
						$val = $val.' es un número demasiado grande. Máximo tamaño: '.$size;
				break;
			case 'ND':
				list($n, $d) = preg_split('/-/', $size);
				$nv = intval($val);
				if ( strlen(strval($nv)) > $n ) {
					if ($this->extra_data['FSXE_VERBOSE'])
						$val = $val.' es un número demasiado grande. Máximo tamaño: '.$size;
				} else {
					$val = number_format(round(floatval($val), $d), $d, ',', '');
				}
				break;
			case 'F':
				// To Do :: Dates not really needed for this extruder...
				break;
			default:
				//Nothing really
				break;
		}

		return $val;
		
	}


	/* ********************************************************************************************* */

	public function column1( $onlySchema = false) {
		$template = array (
					  'column' => '1',
					  'name'   => 'Product ID WooCommerce',
					  'size'   => '20',
					  'type'   => 'N',
					  'notes'  => 'Campo índice, no puede haber duplicados',
					 );
		if ( $onlySchema ) return $template;

		$value = version_compare( WC_VERSION, '3.0', '>=' ) ? $this->product->get_id() : $this->product->id;

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function column0( $onlySchema = false) {
		$template = array (
					  'column' => '0',
					  'name'   => 'Variation ID WooCommerce',
					  'size'   => '20',
					  'type'   => 'N',
					  'notes'  => 'Campo índice, no puede haber duplicados',
					 );
		if ( $onlySchema ) return $template;

		$value = '';

		if ($this->variation) 
			$value = version_compare( WC_VERSION, '3.0', '>=' ) ? 
				$this->variation->get_id() : 
				$this->variation->variation_id;

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnPrecio( $onlySchema = false) {
		$template = array (
					  'column' => 'Precio',
					  'name'   => 'Precio',
					  'size'   => '12-2',
					  'type'   => 'ND',
					  'notes'  => '',
					 );
		if ( $onlySchema ) return $template;

		$val = $this->product->get_regular_price();	// Tax included? Don't know

		if ($this->variation) $val = $this->variation->get_regular_price();

		// Now, calculate Tax Percent
		$r = -1;
		$taxClass = $this->product->get_tax_class();

		for ($i = 1; $i <= 4 ; $i++) {
		    if ( ($this->extra_data['FSOL_IMPUESTO_DIRECTO_TIPO_'.$i]) == $taxClass ) {
		    	$r = $this->extra_data['FIDT_'.$i.'_PERCENT'];
		        break;
		    }
		}

		if ($r>=0)
			if ( get_option('woocommerce_prices_include_tax') == 'yes' ) {
				if ( $this->extra_data['FSOL_TCACFG_TAX'] > 0 ) $value = $val;
				else $value = $val/(1+$r/100.0);
			} else {
				if ( $this->extra_data['FSOL_TCACFG_TAX'] > 0 ) $value = $val*(1+$r/100.0);
				else $value = $val;
			}
		else
			$value = 0;

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnStock( $onlySchema = false) {
		$template = array (
					  'column' => 'Stock',
					  'name'   => 'Stock actual',
					  'size'   => '12-2',
					  'type'   => 'ND',
					  'notes'  => '',
					 );
		if ( $onlySchema ) return $template;

		$value = $this->product->managing_stock() ? $this->product->get_stock_quantity() : 0;

		if ($this->variation) {
			$value = $this->variation->managing_stock() ? $this->variation->get_stock_quantity() : 0;
		}

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnCODART( $onlySchema = false) {
		$template = array (
					  'column' => 'CODART',
					  'name'   => 'Código de Artículo',
					  'size'   => '13',
					  'type'   => 'A',
					  'notes'  => '',
					 );
		if ( $onlySchema ) return $template;

		$value = '';

		if ( $this->extra_data['FSXE_ART_CODE'] == 'sku' ) {
		
				if ($this->variation) $value = $this->variation->get_sku();
				else 				  $value = $this->product->get_sku();
		}

		if ( $this->extra_data['FSXE_ART_CODE'] == 'id' ) {
		
				if ($this->variation) $value = $this->column0();
				else 				  $value = $this->column1();
		}

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */


}	// class ExtruderART ENDS