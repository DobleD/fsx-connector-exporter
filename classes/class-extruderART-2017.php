<?php
/*
   $Id: class-extruderART-2017.php v 1.5.6 - RedondoWS $
   FSx-Connector Exporter
   (c) 2016-2017 RWS (www.redondows.com / www.factusol-woocommerce.es)
   -----------------------------------------------------------------------------------------

   WooCommerce
   https://www.woocommerce.com
   ---------------------------------------------------------------------------------------*/

require_once ( _FSXE_PATH_ . 'classes/class-extruder.php' );

class ExtruderART_2017 extends Extruder {

	public $fileName   = 'ART';
	public $lastColumn = 'AW';



	/* ********************************************************************************************* */

	public function columnA( $onlySchema = false) {
		$template = array (
					  'column' => 'A',
					  'name'   => 'Código',
					  'size'   => '13',
					  'type'   => 'A',
					  'notes'  => 'Campo índice, no puede haber duplicados',
					 );
		if ( $onlySchema ) return $template;

		$value = $this->columnCODART();

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnB( $onlySchema = false) {
		$template = array (
					  'column' => 'B',
					  'name'   => 'Código de barras',
					  'size'   => '13',
					  'type'   => 'A',
					  'notes'  => '',
					 );
		if ( $onlySchema ) return $template;

		$value = '';
		// To Do: search $this->product_meta / $this->variation_meta for the value

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnC( $onlySchema = false) {
		$template = array (
					  'column' => 'C',
					  'name'   => 'Código equivalente',
					  'size'   => '18',
					  'type'   => 'A',
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
					  'name'   => 'Código corto',
					  'size'   => '5',
					  'type'   => 'N',
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
					  'name'   => 'Familia',
					  'size'   => '3',
					  'type'   => 'A',
					  'notes'  => '',
					 );
		if ( $onlySchema ) return $template;

		$terms = get_the_terms( $this->column1(), 'product_cat' );
		$value = '';
		if ( $terms ) {
			foreach ( $terms as $term ) {
				$value .= trim($term->name) .', ';		// WP_Term Object
			}
		}
		$value = trim($value, ', ');

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnF( $onlySchema = false) {
		$template = array (
					  'column' => 'F',
					  'name'   => 'Descripción',
					  'size'   => '50',
					  'type'   => 'A',
					  'notes'  => '',
					 );
		if ( $onlySchema ) return $template;

		$value = $this->product->get_title();
		if ($this->variation) $value .= '. '.$this->variation->get_formatted_variation_attributes( true );

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnG( $onlySchema = false) {
		$template = array (
					  'column' => 'G',
					  'name'   => 'Descrip. Etiquetas',
					  'size'   => '30',
					  'type'   => 'A',
					  'notes'  => '',
					 );
		if ( $onlySchema ) return $template;

		$value = '';

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnH( $onlySchema = false) {
		$template = array (
					  'column' => 'H',
					  'name'   => 'Descrip. Ticket',
					  'size'   => '20',
					  'type'   => 'A',
					  'notes'  => '',
					 );
		if ( $onlySchema ) return $template;

		$value = '';

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnI( $onlySchema = false) {
		$template = array (
					  'column' => 'I',
					  'name'   => 'Proveedor habitual',
					  'size'   => '5',
					  'type'   => 'N',
					  'notes'  => '',
					 );
		if ( $onlySchema ) return $template;

		$value = '';

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnJ( $onlySchema = false) {
		$template = array (
					  'column' => 'J',
					  'name'   => 'Tipo de IVA',
					  'size'   => '1',
					  'type'   => 'N',
					  'notes'  => 'Valores 0 al 3 según tabla en configuración',
					 );
		if ( $onlySchema ) return $template;

		$value = -1;
		$val = $this->product->get_tax_class();

		for ($i = 1; $i <= 4 ; $i++) {
		    if ( $this->extra_data['FSOL_IMPUESTO_DIRECTO_TIPO_'.$i] == $val ) {
		    	$value = $i - 1;
		        break;
		    }
		}

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnK( $onlySchema = false) {
		$template = array (
					  'column' => 'K',
					  'name'   => 'Precio de costo',
					  'size'   => '12-2',
					  'type'   => 'ND',
					  'notes'  => '',
					 );
		if ( $onlySchema ) return $template;

		$value = '';

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnL( $onlySchema = false) {
		$template = array (
					  'column' => 'L',
					  'name'   => 'Descuento 1',
					  'size'   => '2-2',
					  'type'   => 'ND',
					  'notes'  => '',
					 );
		if ( $onlySchema ) return $template;

		$value = '';

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnM( $onlySchema = false) {
		$template = array (
					  'column' => 'M',
					  'name'   => 'Descuento 2',
					  'size'   => '2-2',
					  'type'   => 'ND',
					  'notes'  => '',
					 );
		if ( $onlySchema ) return $template;

		$value = '';

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnN( $onlySchema = false) {
		$template = array (
					  'column' => 'N',
					  'name'   => 'Descuento 3',
					  'size'   => '2-2',
					  'type'   => 'ND',
					  'notes'  => '',
					 );
		if ( $onlySchema ) return $template;

		$value = '';

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnO( $onlySchema = false) {
		$template = array (
					  'column' => 'O',
					  'name'   => 'Fecha de alta',
					  'size'   => 'Fecha',
					  'type'   => 'F',
					  'notes'  => '',
					 );
		if ( $onlySchema ) return $template;

		$value = '';

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnP( $onlySchema = false) {
		$template = array (
					  'column' => 'P',
					  'name'   => 'Máximo dto. Aplicable',
					  'size'   => '3-2',
					  'type'   => 'ND',
					  'notes'  => '',
					 );
		if ( $onlySchema ) return $template;

		$value = '';

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnQ( $onlySchema = false) {
		$template = array (
					  'column' => 'Q',
					  'name'   => 'Ubicación',
					  'size'   => '30',
					  'type'   => 'A',
					  'notes'  => '',
					 );
		if ( $onlySchema ) return $template;

		$value = '';

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnR( $onlySchema = false) {
		$template = array (
					  'column' => 'R',
					  'name'   => 'Unidades en línea',
					  'size'   => '3',
					  'type'   => 'N',
					  'notes'  => '',
					 );
		if ( $onlySchema ) return $template;

		$value = '';

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnS( $onlySchema = false) {
		$template = array (
					  'column' => 'S',
					  'name'   => 'Unidades por bulto',
					  'size'   => '10-2',
					  'type'   => 'ND',
					  'notes'  => '',
					 );
		if ( $onlySchema ) return $template;

		$value = '';

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnT( $onlySchema = false) {
		$template = array (
					  'column' => 'T',
					  'name'   => 'Dimensiones',
					  'size'   => '30',
					  'type'   => 'A',
					  'notes'  => '',
					 );
		if ( $onlySchema ) return $template;

		$value = $this->product->get_length().'x'.$this->product->get_width().'x'.$this->product->get_height();
		if (strlen($value)<3) $value = '';

		if ( $this->variation ) {
			$val = $this->variation->get_length().'x'.$this->variation->get_width().'x'.$this->variation->get_height();
			if (strlen($val)<3) $val = '';
			if ($val) $value = $val;
		}

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnU( $onlySchema = false) {
		$template = array (
					  'column' => 'U',
					  'name'   => 'Mensaje emergente',
					  'size'   => '50',
					  'type'   => 'A',
					  'notes'  => '',
					 );
		if ( $onlySchema ) return $template;

		$value = '';

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnV( $onlySchema = false) {
		$template = array (
					  'column' => 'V',
					  'name'   => 'Observaciones',
					  'size'   => '120',
					  'type'   => 'A',
					  'notes'  => '',
					 );
		if ( $onlySchema ) return $template;

		$value = '';

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnW( $onlySchema = false) {
		$template = array (
					  'column' => 'W',
					  'name'   => 'No utilizar',
					  'size'   => '1',
					  'type'   => 'N',
					  'notes'  => '0=No, 1=Si',
					 );
		if ( $onlySchema ) return $template;

		$value = 0;

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnX( $onlySchema = false) {
		$template = array (
					  'column' => 'X',
					  'name'   => 'No imprimir',
					  'size'   => '1',
					  'type'   => 'N',
					  'notes'  => '0=No, 1=Si',
					 );
		if ( $onlySchema ) return $template;

		$value = 0;

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnY( $onlySchema = false) {
		$template = array (
					  'column' => 'Y',
					  'name'   => 'Artículo compuesto',
					  'size'   => '1',
					  'type'   => 'N',
					  'notes'  => '0=No, 1=Si',
					 );
		if ( $onlySchema ) return $template;

		$value = 0;

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnZ( $onlySchema = false) {
		$template = array (
					  'column' => 'Z',
					  'name'   => 'Campo programable 1',
					  'size'   => '25',
					  'type'   => 'A',
					  'notes'  => '',
					 );
		if ( $onlySchema ) return $template;

		$value = '';

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnAA( $onlySchema = false) {
		$template = array (
					  'column' => 'AA',
					  'name'   => 'Campo programable 2',
					  'size'   => '25',
					  'type'   => 'A',
					  'notes'  => '',
					 );
		if ( $onlySchema ) return $template;

		$value = '';

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnAB( $onlySchema = false) {
		$template = array (
					  'column' => 'AB',
					  'name'   => 'Campo programable 3',
					  'size'   => '25',
					  'type'   => 'A',
					  'notes'  => '',
					 );
		if ( $onlySchema ) return $template;

		$value = '';

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnAC( $onlySchema = false) {
		$template = array (
					  'column' => 'AC',
					  'name'   => 'Referencia del proveedor',
					  'size'   => '30',
					  'type'   => 'A',
					  'notes'  => '',
					 );
		if ( $onlySchema ) return $template;

		$value = '';

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnAD( $onlySchema = false) {
		$template = array (
					  'column' => 'AD',
					  'name'   => 'Descripción larga',
					  'size'   => '65000',
					  'type'   => 'A',
					  'notes'  => '',
					 );
		if ( $onlySchema ) return $template;

		$value = strip_tags($this->product->post->post_excerpt)."\n\n".strip_tags($this->product->post->post_content);

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnAE( $onlySchema = false) {
		$template = array (
					  'column' => 'AE',
					  'name'   => 'Importe de portes por unidad',
					  'size'   => '12-3',
					  'type'   => 'ND',
					  'notes'  => '',
					 );
		if ( $onlySchema ) return $template;

		$value = '';

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnAF( $onlySchema = false) {
		$template = array (
					  'column' => 'AF',
					  'name'   => 'Cuenta ventas',
					  'size'   => '10',
					  'type'   => 'A',
					  'notes'  => '',
					 );
		if ( $onlySchema ) return $template;

		$value = '';

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnAG( $onlySchema = false) {
		$template = array (
					  'column' => 'AG',
					  'name'   => 'Cuenta compras',
					  'size'   => '10',
					  'type'   => 'A',
					  'notes'  => '',
					 );
		if ( $onlySchema ) return $template;

		$value = '';

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnAH( $onlySchema = false) {
		$template = array (
					  'column' => 'AH',
					  'name'   => 'Cantidad por defecto en las salidas',
					  'size'   => '3-2',
					  'type'   => 'ND',
					  'notes'  => '',
					 );
		if ( $onlySchema ) return $template;

		$value = '';

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnAI( $onlySchema = false) {
		$template = array (
					  'column' => 'AI',
					  'name'   => 'Imagen',
					  'size'   => '255',
					  'type'   => 'A',
					  'notes'  => '',
					 );
		if ( $onlySchema ) return $template;

		$value = '';

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnAJ( $onlySchema = false) {
		$template = array (
					  'column' => 'AJ',
					  'name'   => 'Subir a Internet',
					  'size'   => '1',
					  'type'   => 'N',
					  'notes'  => '0=No, 1=Sí',
					 );
		if ( $onlySchema ) return $template;

		$value = 1;

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnAK( $onlySchema = false) {
		$template = array (
					  'column' => 'AK',
					  'name'   => 'Descripción web',
					  'size'   => '65000',
					  'type'   => 'A',
					  'notes'  => '',
					 );
		if ( $onlySchema ) return $template;

		$value = $this->product->post->post_excerpt."\n<--**-->\n".$this->product->post->post_content;
		$value = trim($value, "\n<--**-->\n");

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnAL( $onlySchema = false) {
		$template = array (
					  'column' => 'AL',
					  'name'   => 'Mensaje emergente web',
					  'size'   => '255',
					  'type'   => 'A',
					  'notes'  => '',
					 );
		if ( $onlySchema ) return $template;

		$value = '';

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnAM( $onlySchema = false) {
		$template = array (
					  'column' => 'AM',
					  'name'   => 'Control del stock',
					  'size'   => '1',
					  'type'   => 'N',
					  'notes'  => '0=No, 1=Sí',
					 );
		if ( $onlySchema ) return $template;

		$value = $this->product->managing_stock() ? 1 : 0 ;

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnAN( $onlySchema = false) {
		$template = array (
					  'column' => 'AN',
					  'name'   => 'Imagen web',
					  'size'   => '100',
					  'type'   => 'A',
					  'notes'  => '',
					 );
		if ( $onlySchema ) return $template;

		$value = '';

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnAO( $onlySchema = false) {
		$template = array (
					  'column' => 'AO',
					  'name'   => 'Control stock web',
					  'size'   => '1',
					  'type'   => 'N',
					  'notes'  => '0=Artículo no disponible, 1=Consultar Disponibilidad, 2=Artículo disponible, 3=Mostrar el último stockaje',
					 );
		if ( $onlySchema ) return $template;

		$value = 2;

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnAP( $onlySchema = false) {
		$template = array (
					  'column' => 'AP',
					  'name'   => 'Última modificación',
					  'size'   => 'Fecha',
					  'type'   => 'F',
					  'notes'  => '',
					 );
		if ( $onlySchema ) return $template;

		$value = '';

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnAQ( $onlySchema = false) {
		$template = array (
					  'column' => 'AQ',
					  'name'   => 'Peso',
					  'size'   => '12-2',
					  'type'   => 'ND',
					  'notes'  => '',
					 );
		if ( $onlySchema ) return $template;

		$value = $this->product->get_weight();
		if ( $this->variation && $this->variation->get_weight() ) $value = $this->variation->get_weight();

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnAR( $onlySchema = false) {
		$template = array (
					  'column' => 'AR',
					  'name'   => 'Fabricante',
					  'size'   => '5',
					  'type'   => 'N',
					  'notes'  => 'Código de fabricante para el artículo',
					 );
		if ( $onlySchema ) return $template;

		$value = '';

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnAS( $onlySchema = false) {
		$template = array (
					  'column' => 'AS',
					  'name'   => 'Articulo concatenado',
					  'size'   => '13',
					  'type'   => 'A',
					  'notes'  => 'Código del articulo concatenado',
					 );
		if ( $onlySchema ) return $template;

		$value = '';

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnAT( $onlySchema = false) {
		$template = array (
					  'column' => 'AT',
					  'name'   => 'Garantía',
					  'size'   => '50',
					  'type'   => 'A',
					  'notes'  => '',
					 );
		if ( $onlySchema ) return $template;

		$value = '';

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnAU( $onlySchema = false) {
		$template = array (
					  'column' => 'AU',
					  'name'   => 'Unidad de medida',
					  'size'   => '5',
					  'type'   => 'N',
					  'notes'  => '',
					 );
		if ( $onlySchema ) return $template;

		$value = '';

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnAV( $onlySchema = false) {
		$template = array (
					  'column' => 'AV',
					  'name'   => 'Exportar a MovilSOL / N!PREVENTA',
					  'size'   => '1',
					  'type'   => 'N',
					  'notes'  => '0 = No, 1 = Sí',
					 );
		if ( $onlySchema ) return $template;

		$value = 1;

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */

	public function columnAW( $onlySchema = false) {
		$template = array (
					  'column' => 'AW',
					  'name'   => 'Desligar del código del artículo en las ventas',
					  'size'   => '1',
					  'type'   => 'N',
					  'notes'  => '0 = No, 1 = Sí',
					 );
		if ( $onlySchema ) return $template;

		$value = 0;

		return $this->formatColumn( $value, $template['size'], $template['type'] );
	}

	/* ********************************************************************************************* */


}	// class ExtruderART ENDS