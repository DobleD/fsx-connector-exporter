<?php
/*
   $Id: class-fsxExporterART.php v 1.6.8 - RedondoWS $
   FSx-Connector Exporter
   (c) 2016-2017 RWS (www.redondows.com / www.factusol-woocommerce.es)
   -----------------------------------------------------------------------------------------

   WooCommerce
   https://www.woocommerce.com
   ---------------------------------------------------------------------------------------*/

class fsxExporterART
{
	static $version = '1.6.9';
	static $version_date = '2017-06-09';

	public $show_products;
	public $show_products_from;
	public $show_products_to;
	public $output_type;
	public $screen_output;

	public $extruder;

	//Some settings
	static $options_default = array (
	);
	public $options = array();

	// Page Current
	public $fsxe_page = 'fsxeart';

	//Init the class
	public function __construct() {	

		global $wpdb;
		
		// activation hook
		// register_activation_hook( _FSXE_PATH_._FSXE_FILE_, array($this, 'install' ));		
		
		// deactivation hook
		// register_deactivation_hook( _FSXE_PATH_._FSXE_FILE_, array($this, 'uninstall' ));


		// Load our Products Class
		add_action('plugins_loaded', array($this, 'load_dependencies'));
		// Load translation files
		add_action('plugins_loaded', array($this, 'load_textdomain'));
		//Add admin menu item
		add_action('admin_menu', array($this, 'add_admin_menu_item'));
		//Process
		add_action('admin_init', array($this, 'fsxe_art_page_process'));

		// Defaults - Options saved by the user
		// update_option( 'FSXE_OPTIONS', '' );
		$this->options = get_option('FSXE_OPTIONS');
		if (!$this->options) {
			$this->options=array();
			$this->options['FSXE_FSOL_VER']='2017';
			$this->options['FSOL_IMPUESTO_DIRECTO_TIPO_1'] = '';
			$this->options['FSOL_IMPUESTO_DIRECTO_TIPO_2'] = '-1';
			$this->options['FSOL_IMPUESTO_DIRECTO_TIPO_3'] = '-1';
			$this->options['FSOL_IMPUESTO_DIRECTO_TIPO_4'] = '-1';
			$this->options['FIDT_1_PERCENT'] = '21.0';
			$this->options['FIDT_2_PERCENT'] = '10.0';
			$this->options['FIDT_3_PERCENT'] = '4.0';
			$this->options['FIDT_4_PERCENT'] = '0.0';
			$this->options['FSOL_TCACFG'] = '';
			$this->options['FSOL_TCACFG_TAX'] = '0';
			$this->options['FSOL_AUSCFG'] = '';
			$this->options['FSXE_OUTPUT'] = 'csv';
			$this->options['FSXE_HEADER']='0';
			$this->options['FSXE_EXTRA_RIGHT']='0';
			$this->options['FSXE_EXTRA_LEFT']='0';
			$this->options['FSXE_ART_CODE']='sku';
			$this->options['FSXE_TRUNCATE_STR']='0';
			$this->options['FSXE_VERBOSE']='1';
			/* $this->options[''] = ''; */
			$this->options['FSXE_FILTER']='all';
			$this->options['FSXE_FILTER_FROM']='';
			$this->options['FSXE_FILTER_TO']  ='';
		}

		// FSx-Connector found?
 		$this->options['FSXE_FSX_FOUND'] = 
 			in_array( 'FSx-Connector/FSx-Connector.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ?
			'1' :
			'0' ;
		
		if ($this->options['FSXE_FSX_FOUND']>0) {
			// Load FSx values
			$this->options['FSOL_IMPUESTO_DIRECTO_TIPO_1'] = get_option('FSOL_IMPUESTO_DIRECTO_TIPO_1', $this->options['FSOL_IMPUESTO_DIRECTO_TIPO_1']);
			$this->options['FSOL_IMPUESTO_DIRECTO_TIPO_2'] = get_option('FSOL_IMPUESTO_DIRECTO_TIPO_2', $this->options['FSOL_IMPUESTO_DIRECTO_TIPO_2']);
			$this->options['FSOL_IMPUESTO_DIRECTO_TIPO_3'] = get_option('FSOL_IMPUESTO_DIRECTO_TIPO_3', $this->options['FSOL_IMPUESTO_DIRECTO_TIPO_3']);
			$this->options['FSOL_IMPUESTO_DIRECTO_TIPO_4'] = get_option('FSOL_IMPUESTO_DIRECTO_TIPO_4', $this->options['FSOL_IMPUESTO_DIRECTO_TIPO_4']);
			$this->options['FIDT_1_PERCENT'] = get_option('FSOL_PIV1CFG', $this->options['FIDT_1_PERCENT']);
			$this->options['FIDT_2_PERCENT'] = get_option('FSOL_PIV2CFG', $this->options['FIDT_2_PERCENT']);
			$this->options['FIDT_3_PERCENT'] = get_option('FSOL_PIV3CFG', $this->options['FIDT_3_PERCENT']);
		//	$this->options['FIDT_4_PERCENT'] = '0.0';
			$this->options['FSOL_TCACFG']      = get_option('FSOL_TCACFG', $this->options['FSOL_TCACFG']);
			$this->options['FSOL_TCACFG_DESC'] = '';
			$this->options['FSOL_TCACFG_TAX']  = '0';
			$this->options['FSOL_AUSCFG']      = get_option('FSOL_AUSCFG', $this->options['FSOL_AUSCFG']);
			$this->options['FSOL_AUSCFG_DESC']  = '';

			if ( ($configuration = $wpdb->query("show tables like 'F_CFG'")) )
			{
				$tar = $wpdb->get_row("select DESTAR, IINTAR from F_TAR where CODTAR = ".intval($this->options['FSOL_TCACFG']), ARRAY_A);
				$this->options['FSOL_TCACFG_DESC'] = $tar['DESTAR'];
				$this->options['FSOL_TCACFG_TAX']  = $tar['IINTAR'];

				$this->options['FSOL_AUSCFG_DESC'] = 
					$wpdb->get_var("select NOMALM from F_ALM where CODALM = ".intval($this->options['FSOL_AUSCFG']));
			}
		}
	}

	//Load our Products Class
	public function load_dependencies() {
		// Nothing, so far.
		wp_register_style( 'fsxe_woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin.css', array(), WC_VERSION );
		wp_enqueue_style( 'fsxe_woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin.css', array(), WC_VERSION );

		$suffix = '.min';
		wp_register_script( 'fsxe_jquery-tiptip', WC()->plugin_url() . '/assets/js/jquery-tiptip/jquery.tipTip' . $suffix . '.js', array( 'jquery' ), WC_VERSION, true );
		wp_enqueue_script( 'fsxe_jquery-tiptip' );

		wp_register_script( 'fsxe_woocommerce_admin', WC()->plugin_url() . '/assets/js/admin/woocommerce_admin' . $suffix . '.js', array( 'jquery' ), WC_VERSION );
		wp_enqueue_script( 'fsxe_woocommerce_admin' );

	}

	//Load translation files
	public function load_textdomain() {
		// Some work needed here...
		$domain='FSx-Exporter';
		if ($loaded = load_plugin_textdomain($domain, false, trailingslashit(WP_LANG_DIR))) {
			return $loaded;
		} else {
			return load_plugin_textdomain($domain, false, dirname(plugin_basename( __FILE__ )).'/../languages/');
		}
	}

	//Check capabilities
	public function check_capabilities() {
		//Maybe a bit redundant
		return (current_user_can('manage_options') || current_user_can('manage_woocommerce') || current_user_can('view_woocommerce_reports'));
	}

	//Add admin menu item
	public function add_admin_menu_item() {
		 if ($this->check_capabilities()) {
		 	
 		 	// FSx-Connector found?
 		 	if ( in_array( 'FSx-Connector/FSx-Connector.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) 
 		 		$m = 'fsx_connector';
 		 	else
 		 		$m = 'woocommerce';
 
 	 		add_submenu_page( $m, 
 		 		_x('FSx-Exporter', 'admin page title', 'FSx-Exporter'), 
 		 		_x('FSx-Exporter', 'admin menu item' , 'FSx-Exporter'), 
 		 		'view_woocommerce_reports', 
 		 		'fsxeart', 
 		 		array($this, 'fsxe_art_page')
 	 		);
		 }
	}

	//Admin screen
	public function fsxe_art_page() {

		require_once( _FSXE_PATH_.'schnitzel/fsxe_art_page.php' );

	}

	//Admin screen - export
	public function fsxe_art_page_process() {
		global $plugin_page;
		if ($plugin_page==$this->fsxe_page && $this->check_capabilities()) {
			if (   isset($_POST['fsxe_art_button']) 
				|| isset($_POST['fsxe_art_button_STO']) 
				|| isset($_POST['fsxe_art_button_LTA']) ) {

				update_option( 'FSXE_OPTIONS', array_merge($this->options, $_POST) );
				$this->options=get_option('FSXE_OPTIONS');
			
				$name = 'ART';
				if      ( isset($_POST['fsxe_art_button_STO']) ) $name = 'STO';
				else if ( isset($_POST['fsxe_art_button_LTA']) ) $name = 'LTA';

				$this->show_products=(isset($_POST['FSXE_FILTER']) ? 
						trim($_POST['FSXE_FILTER']) : $this->options['FSXE_FILTER']);

				$this->show_products_from=(isset($_POST['FSXE_FILTER_FROM']) ? 
						trim($_POST['FSXE_FILTER_FROM']) : $this->options['FSXE_FILTER_FROM']);

				$this->show_products_to=(isset($_POST['FSXE_FILTER_TO']) ? 
						trim($_POST['FSXE_FILTER_TO']) : $this->options['FSXE_FILTER_TO']);

				$this->output_type=(isset($_POST['FSXE_OUTPUT']) ? 
						trim($_POST['FSXE_OUTPUT']) : $this->options['FSXE_OUTPUT']);

				// Mambo here!
				if (  file_exists( _FSXE_PATH_ . 'classes/class-extruder'.$name.'-'.$this->options['FSXE_FSOL_VER'].'.php' ) )
					require_once ( _FSXE_PATH_ . 'classes/class-extruder'.$name.'-'.$this->options['FSXE_FSOL_VER'].'.php' );
				else 
					{
					$this->screen_output = 'No se ha encontrado el Exportador de '.$name.' para FactuSOL '.$this->options['FSXE_FSOL_VER']; 
					return ;
					}

				$extruder = 'Extruder'.$name.'_'.$this->options['FSXE_FSOL_VER'];
				$this->extruder = new $extruder( $this->options );

				$this->make_export();
			}
		}
	}

	// Lets get dirty, at last!
	public function make_export() {
		$output_array    = array();

		//Options
		
		switch($this->output_type) {
			case 'csv':
				//Correct headers
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename='.$this->extruder->fileName.'.csv');
				//Create a file pointer connected to the output stream
				$output = fopen('php://output', 'w');
				break;
			default:
				//Nothing really
				//Init the output array and add column headers
				$output_array[0]    = $this->extruder->getColumnRange( );
				break;
		}

		if ($this->options['FSXE_HEADER']>0) {
			$cols = $this->extruder->getColumnRange( );
			$i=0;
			foreach ($cols as $col) {
				$t=$this->extruder->getColumnSchema($col);
				$output_array[0][$i] = $t['name'];
				$i++;
			}
		}

		// Get Products
		if ( version_compare( WC_VERSION, '3.0', '>=' ) ) {
			$products = wc_get_products( array(
				'status' 		=> 'publish',
				'limit'			=> -1, //This is not a very good idea
				'orderby'		=> 'title',
				'order'			=> 'ASC',
			) );
		} else {
			$args = array(
				'post_type'			=> 'product',
				'post_status' 		=> 'publish',
				'posts_per_page' 	=> -1, //This is not a very good idea
				'orderby'			=> 'title',
				'order'				=> 'ASC',
			);
			$loop = new WP_Query($args);
			$products = array();
			while ( $loop->have_posts() ) : $loop->the_post();
				global $product;
				$products[] = $product;
			endwhile;
		}

		foreach($products as $product) {
			$id = version_compare( WC_VERSION, '3.0', '>=' ) ? $product->get_id() : $product->id;

			// Lazy gorrino filter ;)
			if ( $this->options['FSXE_FILTER_FROM'] && ($id < $this->options['FSXE_FILTER_FROM']) ) continue;
			if ( $this->options['FSXE_FILTER_TO']   && ($id > $this->options['FSXE_FILTER_TO']  ) ) continue;
/*
			if(    ($this->get_value( 'id', $product, null )[0]!=1137) 
				&& ($this->get_value( 'id', $product, null )[0]!=1139)  ) continue;
*/
			//if ( !version_compare( WC_VERSION, '3.0', '>=' ) ) $product = new WC_Product($product->ID); 
			//It's already a product, although we're using WP_Query to get them...
			$product_type = version_compare( WC_VERSION, '3.0', '>=' ) ? $product->get_type() : $product->product_type;

			if ( $product_type == 'variable' ) {
				$variations = $product->get_available_variations();

				foreach ( $variations as $temp ) {
					$variation = new WC_Product_Variation( $temp['variation_id'] );

					if ( $this->show_products=='all' || ($this->show_products=='managed' && $variation->managing_stock()) ) {
							$output_array[] = $this->extruder->process( $product, $variation );
					}		
				}
			} else {
				if ( $this->show_products=='all' || ($this->show_products=='managed' && $product->managing_stock()) ) {
					$output_array[] = $this->extruder->process( $product );
				}
			}
		}

		//Output
		switch( $this->output_type ) {
			case 'csv':
				//CSV'it
				foreach( $output_array as $i=> $temp ) {
					$output_array[$i] = implode(',', $temp);
				}
				fwrite( $output, chr(255).chr(254).iconv("UTF-8", "UTF-16LE//IGNORE", implode("\n", $output_array) ) );
				die();
				break;
			case 'screen':
				ob_start();
				?>
				<p><b><?php echo count($output_array)-1; ?> <?php _e('products', 'FSx-Exporter'); ?></b></p>
				<table class="widefat">
					<thead>
						<tr>
							<?php
							foreach($output_array[0] as $value) {
								?>
								<th scope="col"><?php echo $value; ?></th>
								<?php
							}
							?>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach($output_array as $key => $values) {
							if ($key>0) {
								?>
								<tr class="<?php echo ($key%2==0 ? '' : 'alternate'); ?>">
								<?php
								foreach($values as $value) {
									?>
									<td class="column-columnname"><?php echo $value; ?></td>
									<?php
								}
								?>
								</tr>
								<?php
							}
						}
						?>
					</tbody>
				</table>
				<?php
				$this->screen_output = ob_get_clean();
				break;
		}
	}

} // That'a all, folks!
