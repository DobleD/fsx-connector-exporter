<?php
/*
   $Id: fsx_art_page.php v 1.6.9 - RedondoWS $
   FSx-Connector Exporter
   (c) 2016-2017 RWS (www.redondows.com / www.factusol-woocommerce.es)
   -----------------------------------------------------------------------------------------

   WooCommerce
   https://www.woocommerce.com
   ---------------------------------------------------------------------------------------*/

		$show_products_options = array(
			array(
				'value'	=>	'all',
				'label'	=>	__('All products', 'FSx-Exporter'),
			),
			array(
				'value'	=>	'managed',
				'label'	=>	__('Products with managed stock', 'FSx-Exporter'),
			),
		);
		$output_options = array(
			array(
				'value'	=>	'csv',
				'label'	=>	__('CSV file', 'FSx-Exporter'),
			),
			array(
				'value'	=>	'screen',
				'label'	=>	__('HTML table on screen', 'FSx-Exporter'),
			),
		);

		$plugins_url = plugins_url();
		
		?>

<!-- link rel='stylesheet' id='woocommerce_admin_styles-css'  href='< ?php echo $plugins_url; ?>/woocommerce/assets/css/admin.css' type='text/css' media='all' />
<script type='text/javascript' src='< ?php echo $plugins_url; ?>/woocommerce/assets/js/jquery-tiptip/jquery.tipTip.min.js'></script>
<script type='text/javascript' src='< ?php echo $plugins_url; ?>/woocommerce/assets/js/admin/woocommerce_admin.min.js'></script -->

		<div class="wrap">
			<h2><?php _ex('FSx-Connector Exporter :: Product Exporter to FactuSOL&trade; for WooCommerce', 'admin page title', 'FSx-Exporter'); ?></h2>
			<p><?php // _e('', 'FSx-Exporter'); ?>
				FSx-Connector Exporter genera un fichero CSV con los Productos y Variaciones de WooCommerce según el formato definido en el Documento de FactuSOL "<a href="<?php echo _FSXE_URL_ ; ?>documents/FACTUSOL_Importacion_Excel_Calc.pdf" target="_blank">Instrucciones para la importación de datos desde OpenOffice.org Calc o Microsoft Office Excel</a>", en el apartado correspondiente a "Artículos", "Stock" y "Tarifas". El fichero CSV puede guardarse como ART.xls (o ART.ods) y luego importarlo a FactuSOL.  
				<br /><br /><a class="page-title-action" href="http://fsxtutorial.factusol-woocommerce.es/index.php?tab=fsxexp" target="_blank"> Ayuda </a>
			</p>
			<?php
			//WPML
			if (function_exists('icl_object_id')) {
				?>
				<p><?php _e('WPML users: You can export the report on a different language by changing it on this page top bar.', 'FSx-Exporter'); ?></p>
				<?php
			}
			?>
			<form method="post" id="woocoomerce-stock-export-form" action="">
				<!-- ?php submit_button(__('Ayuda', 'FSx-Exporter'), 'primary', 'fsxe_art_help_button'); ? -->
				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row" class="titledesc">Versión FactuSOL</th>
							<td>
									<div>
<?php 
		$directory = _FSXE_PATH_ . 'classes';

		$files = array();
		if ($handle = opendir( $directory ))
		{
				while (false !== ($file = readdir($handle)))
						if ($file != '.' AND $file != '..') {
							if ( false === strpos ( $file , 'class-extruderART-') ) continue;
							$files[] = str_replace("class-extruderART-", "", str_replace(".php", "", $file));
						}
				closedir($handle);
		}
		if (empty($files))
		{
			//
		}
		natcasesort($files);

		$tax_classes_options = array();

		foreach($files as $file) $tax_classes_options[$file] = $file;

		$debug = $this->options['FSXE_FSOL_VER'];
		$opt = '';
		foreach ( $tax_classes_options as $key => $value ) {
			$opt .= '<option '. fsx_selected( esc_attr( $key ) ,$debug).' value="' . esc_attr( $key ) . '">' . $value . '</option>';
		}

		echo '<select id="FSXE_FSOL_VER" name="FSXE_FSOL_VER">
		      	<option value="-1">-- Seleccione --</option>
		      	'.$opt.'
		      </select> ' . wc_help_tip('La Versión de FactuSOL'); ?> 
									</div>
							</td>
						</tr>

						<tr>
							<th scope="row" class="titledesc">Impuestos Directos FactuSOL
								<?php if ($this->options['FSXE_FSX_FOUND']>0) { ?> 
								<br />
								<span style="font-size: 12px; font-weight: normal;border-left: 3px solid #ffba00;padding-left: 6px;">(Tomado de FSx-Connector)</span>
								<?php } ?> 
							</th>
							<td>
								<p class="flip_1" style="font-size: 12px; display: none;"><a>Mostrar / Ocultar</a></p>
								<div class="panel_1">
									<div>
								<label for="FSOL_IMPUESTO_DIRECTO_TIPO_1">IVA Tipo 1: </label>
<?php 
		$tax_classes_options = array();

		$tax_classes = WC_Tax::get_tax_classes();

		if ( $tax_classes )
			foreach ( $tax_classes as $class ) {
				$tax_classes_options[ sanitize_title( $class ) ] = esc_html( $class );
			}

		$debug = $this->options['FSOL_IMPUESTO_DIRECTO_TIPO_1'];
		$opt = '';
		foreach ( $tax_classes_options as $key => $value ) {
			$opt .= '<option '. fsx_selected( esc_attr( $key ) ,$debug).' value="' . esc_attr( $key ) . '">' . $value . '</option>';
		}

		echo '<select id="FSOL_IMPUESTO_DIRECTO_TIPO_1" name="FSOL_IMPUESTO_DIRECTO_TIPO_1">
		      	<option value="-1">-- Seleccione --</option>
		      	<option '. fsx_selected( esc_attr( '' ) ,$debug).' value="">'.__( 'Standard', 'woocommerce' ).'</option>
		      	'.$opt.'
		      </select>'
?>
								 &nbsp; &nbsp; -> Porcentaje: <input type="text" id="FIDT_1_PERCENT" name="FIDT_1_PERCENT" size="5" value="<?php echo esc_attr( $this->options['FIDT_1_PERCENT']); ?>"/>% 	 <?php echo wc_help_tip('Introduzca los porcentajes de los Impuestos Directos que ha definido en FactuSOL y sus equivalentes en WooCommerce'); ?>

									</div>

									<div>
								<label for="FSOL_IMPUESTO_DIRECTO_TIPO_2">IVA Tipo 2: </label>
<?php 
		$debug = $this->options['FSOL_IMPUESTO_DIRECTO_TIPO_2'];
		$opt = '';
		foreach ( $tax_classes_options as $key => $value ) {
			$opt .= '<option '. fsx_selected( esc_attr( $key ) ,$debug).' value="' . esc_attr( $key ) . '">' . $value . '</option>';
		}

		echo '<select id="FSOL_IMPUESTO_DIRECTO_TIPO_2" name="FSOL_IMPUESTO_DIRECTO_TIPO_2">
		      	<option value="-1">-- Seleccione --</option>
		      	<option '. fsx_selected( esc_attr( '' ) ,$debug).' value="">'.__( 'Standard', 'woocommerce' ).'</option>
		      	'.$opt.'
		      </select>'
?>
								 &nbsp; &nbsp; -> Porcentaje: <input type="text" id="FIDT_2_PERCENT" name="FIDT_2_PERCENT" size="5" value="<?php echo esc_attr( $this->options['FIDT_2_PERCENT']); ?>"/>%				
									</div>

									<div>
								<label for="FSOL_IMPUESTO_DIRECTO_TIPO_3">IVA Tipo 3: </label>
<?php 
		$debug = $this->options['FSOL_IMPUESTO_DIRECTO_TIPO_3'];
		$opt = '';
		foreach ( $tax_classes_options as $key => $value ) {
			$opt .= '<option '. fsx_selected( esc_attr( $key ) ,$debug).' value="' . esc_attr( $key ) . '">' . $value . '</option>';
		}

		echo '<select id="FSOL_IMPUESTO_DIRECTO_TIPO_3" name="FSOL_IMPUESTO_DIRECTO_TIPO_3">
		      	<option value="-1">-- Seleccione --</option>
		      	<option '. fsx_selected( esc_attr( '' ) ,$debug).' value="">'.__( 'Standard', 'woocommerce' ).'</option>
		      	'.$opt.'
		      </select>'
?>
								 &nbsp; &nbsp; -> Porcentaje: <input type="text" id="FIDT_3_PERCENT" name="FIDT_3_PERCENT" size="5" value="<?php echo esc_attr( $this->options['FIDT_3_PERCENT']); ?>"/>%				
									</div>

									<div>
								<label for="FSOL_IMPUESTO_DIRECTO_TIPO_4">IVA Tipo 4: </label>
<?php 
		$debug = $this->options['FSOL_IMPUESTO_DIRECTO_TIPO_4'];
		$opt = '';
		foreach ( $tax_classes_options as $key => $value ) {
			$opt .= '<option '. fsx_selected( esc_attr( $key ) ,$debug).' value="' . esc_attr( $key ) . '">' . $value . '</option>';
		}

		echo '<select id="FSOL_IMPUESTO_DIRECTO_TIPO_4" name="FSOL_IMPUESTO_DIRECTO_TIPO_4">
		      	<option value="-1">-- Seleccione --</option>
		      	<option '. fsx_selected( esc_attr( '' ) ,$debug).' value="">'.__( 'Standard', 'woocommerce' ).'</option>
		      	'.$opt.'
		      </select>'
?>
								 &nbsp; &nbsp; -> Porcentaje: <input type="text" id="FIDT_4_PERCENT" name="FIDT_4_PERCENT" size="5" value="<?php echo esc_attr( $this->options['FIDT_4_PERCENT']); ?>"  readonly="readonly" />%	 <?php echo wc_help_tip('Exento'); ?>
									</div>
								</div>
							</td>
						</tr>


						<tr>
							<th scope="row" class="titledesc">Tarifa FactuSOL
								<?php if ($this->options['FSXE_FSX_FOUND']>0) { ?> 
								<br />
								<span style="font-size: 12px; font-weight: normal;border-left: 3px solid #ffba00;padding-left: 6px;">(Tomado de FSx-Connector)</span>
								<?php } ?> 
							</th>
							<td>
								<p class="flip_2" style="font-size: 12px; display: none;"><a>Mostrar / Ocultar</a></p>
								<div class="panel_2">
									<div>
								<label for="FSOL_TCACFG">Código de Tarifa: </label>
								<input type="text" id="FSOL_TCACFG" name="FSOL_TCACFG" size="5" value="<?php echo esc_attr( $this->options['FSOL_TCACFG']); ?>"/> <?php echo $this->options['FSOL_TCACFG_DESC'] . ' ' . wc_help_tip('El Código de Tarifa debe existir en FactuSOL'); ?>
									</div>
									<div>
								<label for="FSOL_TCACFG_TAX">El Precio de esta Tarifa incluye el Impuesto: </label>
								<select id="FSOL_TCACFG_TAX" name="FSOL_TCACFG_TAX">
								  	<option <?php echo fsx_selected("1",$this->options['FSOL_TCACFG_TAX']); ?> value="1">Sí</option>
							      	<option <?php echo fsx_selected("0",$this->options['FSOL_TCACFG_TAX']); ?> value="0">No</option>
							    </select> <?php echo wc_help_tip('Según se haya definido la Tarifa en FactuSOL; el Precio mostrado incluye o no el Impuesto'); ?>
									</div>
								</div>
							</td>
						</tr>


						<tr>
							<th scope="row" class="titledesc">Almacén FactuSOL
								<?php if ($this->options['FSXE_FSX_FOUND']>0) { ?> 
								<br />
								<span style="font-size: 12px; font-weight: normal;border-left: 3px solid #ffba00;padding-left: 6px;">(Tomado de FSx-Connector)</span>
								<?php } ?> 
							</th>
							<td>
								<p class="flip_3" style="font-size: 12px; display: none;"><a>Mostrar / Ocultar</a></p>
								<div class="panel_3">
									<div>
								<label for="FSOL_AUSCFG">Código de Almacén: </label>
								<input type="text" id="FSOL_AUSCFG" name="FSOL_AUSCFG" size="5" value="<?php echo esc_attr( $this->options['FSOL_AUSCFG']); ?>"/> <?php echo $this->options['FSOL_AUSCFG_DESC'] . ' ' . wc_help_tip('El Código de Almacén debe existir en FactuSOL'); ?>
									</div>
								</div>
							</td>
						</tr>


						<tr>
							<th scope="row" class="titledesc">Filtro de Productos</th>
							<td>
									<div>
								<label for="FSXE_FILTER_FROM">Desde ID: </label>
								<input type="text" id="FSXE_FILTER_FROM" name="FSXE_FILTER_FROM" size="5" value="<?php echo esc_attr( $this->options['FSXE_FILTER_FROM']); ?>"/> <?php echo wc_help_tip('En blanco significa el menor ID'); ?>
									</div>
									<div>
								<label for="FSXE_FILTER_TO">Hasta ID: </label>
								<input type="text" id="FSXE_FILTER_TO" name="FSXE_FILTER_TO" size="5" value="<?php echo esc_attr( $this->options['FSXE_FILTER_TO']); ?>"/> <?php echo wc_help_tip('En blanco significa el mayor ID'); ?>
									</div>
							</td>
						</tr>

<!-- -->

						<!-- tr>
							<th scope="row" class="titledesc">< ?php _e('Products to return', 'FSx-Exporter'); ?></th>
							<td>
								<select name="FSXE_FILTER">
									< ?php
									foreach($show_products_options as $option) {
										?>
										<option value="< ?php echo $option['value']; ?>"< ?php if ($this->options['FSXE_FILTER']==$option['value']) echo ' selected="selected"'; ?>>< ?php echo $option['label']; ?></option>
										< ?php
									}
									?>
								</select>
							</td>
						</tr -->
						<input type="hidden" id="FSXE_FILTER" name="FSXE_FILTER" size="5" value="<?php echo esc_attr( $this->options['FSXE_FILTER']); ?>"/>

						<tr>
							<th scope="row" class="titledesc"><?php _e('Output', 'FSx-Exporter'); ?></th>
							<td>
									<div>
								<select name="FSXE_OUTPUT">
									<?php
									foreach($output_options as $option) {
										?>
										<option value="<?php echo $option['value']; ?>"<?php if ($this->options['FSXE_OUTPUT']==$option['value']) echo ' selected="selected"'; ?>><?php echo $option['label']; ?></option>
										<?php
									}
									?>
								</select>
									</div>
									<div>
								<label for="FSXE_HEADER">Incluir cabeceras de los campos: </label>
								<select id="FSXE_HEADER" name="FSXE_HEADER">
								  	<option <?php echo fsx_selected("1",$this->options['FSXE_HEADER']); ?> value="1">Sí</option>
							      	<option <?php echo fsx_selected("0",$this->options['FSXE_HEADER']); ?> value="0">No</option>
							    </select> <?php echo wc_help_tip('La primera fila será el Nombre de los Campos (Columnas)'); ?>
									</div>
									<div>
								<label for="FSXE_EXTRA_RIGHT">Añadir Columnas a la derecha: </label>
								<select id="FSXE_EXTRA_RIGHT" name="FSXE_EXTRA_RIGHT">
								  	<option <?php echo fsx_selected("1",$this->options['FSXE_EXTRA_RIGHT']); ?> value="1">Sí</option>
							      	<option <?php echo fsx_selected("0",$this->options['FSXE_EXTRA_RIGHT']); ?> value="0">No</option>
							    </select> <?php echo wc_help_tip('Se añaden dos Columnas a la derecha con el Precio (para LTA.xls) y el Stock (para STO.xls)'); ?>
									</div>
									<div>
								<label for="FSXE_EXTRA_LEFT">Añadir Columnas a la izquierda: </label>
								<select id="FSXE_EXTRA_LEFT" name="FSXE_EXTRA_LEFT">
								  	<option <?php echo fsx_selected("1",$this->options['FSXE_EXTRA_LEFT']); ?> value="1">Sí</option>
							      	<option <?php echo fsx_selected("0",$this->options['FSXE_EXTRA_LEFT']); ?> value="0">No</option>
							    </select> <?php echo wc_help_tip('Se añaden dos Columnas a la izquierda con el ID del Producto y el ID de la Variación'); ?>
									</div>
									<div>
								<label for="FSXE_ART_CODE">Código de Artículo: </label>
<?php 
		$tax_classes_options = array('' => 'Vacío', 'sku' => 'SKU', 'id' => 'ID' );

		$debug = $this->options['FSXE_ART_CODE'];
		$opt = '';
		foreach ( $tax_classes_options as $key => $value ) {
			$opt .= '<option '. fsx_selected( esc_attr( $key ) ,$debug).' value="' . esc_attr( $key ) . '">' . $value . '</option>';
		}

		echo '<select id="FSXE_ART_CODE" name="FSXE_ART_CODE">
		      	'.$opt.'
		      </select> ' . wc_help_tip('La Columna correspondiente al Código de Artículo se rellenará con este valor'); ?> 
									</div>
									<div>
								<label for="FSXE_TRUNCATE_STR">Truncar cadenas demasiado largas: </label>
								<select id="FSXE_TRUNCATE_STR" name="FSXE_TRUNCATE_STR">
								  	<option <?php echo fsx_selected("1",$this->options['FSXE_TRUNCATE_STR']); ?> value="1">Sí</option>
							      	<option <?php echo fsx_selected("0",$this->options['FSXE_TRUNCATE_STR']); ?> value="0">No</option>
							    </select> <?php echo wc_help_tip('Si un campo alfanumérico excede la longitud permitida, se truncará'); ?>
									</div>
									<div>
								<label for="FSXE_VERBOSE">Mostrar Errores en campos: </label>
								<select id="FSXE_VERBOSE" name="FSXE_VERBOSE">
								  	<option <?php echo fsx_selected("1",$this->options['FSXE_VERBOSE']); ?> value="1">Sí</option>
							      	<option <?php echo fsx_selected("0",$this->options['FSXE_VERBOSE']); ?> value="0">No</option>
							    </select> <?php echo wc_help_tip('Error si el valor recuperado de WooCommerce no se ajusta a la definición del campo en FactuSOL'); ?>
									</div>
							</td>
						</tr>
					</tbody>
				</table>
				<p class="submit">
				<?php submit_button('Exportar Productos', 'primary', 'fsxe_art_button'    , false); ?> &nbsp; 
				<?php submit_button('Exportar Stock'    , 'primary', 'fsxe_art_button_STO', false); ?> &nbsp; 
				<?php submit_button('Exportar Tarifa'   , 'primary', 'fsxe_art_button_LTA', false); ?>
				</p>
			</form>
			<?php
			if ($this->options['FSXE_OUTPUT']=='screen' && isset($this->screen_output)) {
				?>
				<hr/>
				<?php
				echo $this->screen_output;
			}
			?>
		</div>

<script type="text/javascript">
	// https://crunchify.com/jquery-very-simple-showhide-panel-on-mouse-click-event/
	jQuery(document).ready(function() {
<?php if ($this->options['FSXE_FSX_FOUND']>0) { ?>
		jQuery(".panel_1").slideToggle("slow");
		jQuery(".panel_2").slideToggle("slow");
		jQuery(".panel_3").slideToggle("slow");

		jQuery(".flip_1").slideToggle("slow");
		jQuery(".flip_2").slideToggle("slow");
		jQuery(".flip_3").slideToggle("slow");

		jQuery(".flip_1").click(function() {
			jQuery(".panel_1").slideToggle("slow");
		});
		jQuery(".flip_2").click(function() {
			jQuery(".panel_2").slideToggle("slow");
		});
		jQuery(".flip_3").click(function() {
			jQuery(".panel_3").slideToggle("slow");
		});
<?php } else { ?>
		// jQuery(".flip_1").slideToggle("slow");
		// jQuery(".flip_2").slideToggle("slow");
		// jQuery(".flip_3").slideToggle("slow");
<?php } ?>
	});
</script>

		<?php
		