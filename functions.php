<?php
/**
 * Theme Functions
 *
 * @package Betheme
 * @author Muffin group
 * @link https://muffingroup.com
 */

define('MFN_THEME_VERSION', '21.7.9');

// theme related filters

add_filter('widget_text', 'do_shortcode');

add_filter('the_excerpt', 'shortcode_unautop');
add_filter('the_excerpt', 'do_shortcode');

/**
 * White Label
 * IMPORTANT: We recommend the use of Child Theme to change this
 */

defined('WHITE_LABEL') or define('WHITE_LABEL', false);

/**
 * textdomain
 */

load_theme_textdomain('betheme', get_template_directory() .'/languages'); // frontend
load_theme_textdomain('mfn-opts', get_template_directory() .'/languages'); // admin panel

/**
 * theme options
 */

require_once(get_theme_file_path('/muffin-options/theme-options.php'));

/**
 * theme functions
 */

$theme_disable = mfn_opts_get('theme-disable');

require_once(get_theme_file_path('/functions/theme-functions.php'));
require_once(get_theme_file_path('/functions/theme-head.php'));

// menu

require_once(get_theme_file_path('/functions/theme-menu.php'));
if (! isset($theme_disable['mega-menu'])) {
	require_once(get_theme_file_path('/functions/theme-mega-menu.php'));

}

// builder

require_once(get_theme_file_path('/functions/builder/class-mfn-builder.php'));

// post types

$post_types_disable = mfn_opts_get('post-type-disable');

require_once(get_theme_file_path('/functions/post-types/class-mfn-post-type.php'));

if (! isset($post_types_disable['client'])) {
	require_once(get_theme_file_path('/functions/post-types/class-mfn-post-type-client.php'));
}
if (! isset($post_types_disable['offer'])) {
	require_once(get_theme_file_path('/functions/post-types/class-mfn-post-type-offer.php'));
}
if (! isset($post_types_disable['portfolio'])) {
	require_once(get_theme_file_path('/functions/post-types/class-mfn-post-type-portfolio.php'));
}
if (! isset($post_types_disable['slide'])) {
	require_once(get_theme_file_path('/functions/post-types/class-mfn-post-type-slide.php'));
}
if (! isset($post_types_disable['testimonial'])) {
	require_once(get_theme_file_path('/functions/post-types/class-mfn-post-type-testimonial.php'));
}

if (! isset($post_types_disable['layout'])) {
	require_once(get_theme_file_path('/functions/post-types/class-mfn-post-type-layout.php'));
}
if (! isset($post_types_disable['template'])) {
	require_once(get_theme_file_path('/functions/post-types/class-mfn-post-type-template.php'));
}

require_once(get_theme_file_path('/functions/post-types/class-mfn-post-type-page.php'));
require_once(get_theme_file_path('/functions/post-types/class-mfn-post-type-post.php'));

// includes

require_once(get_theme_file_path('/includes/content-post.php'));
require_once(get_theme_file_path('/includes/content-portfolio.php'));

// shortcodes

require_once(get_theme_file_path('/functions/theme-shortcodes.php'));

// hooks

require_once(get_theme_file_path('/functions/theme-hooks.php'));

// sidebars

require_once(get_theme_file_path('/functions/theme-sidebars.php'));

// widgets

require_once(get_theme_file_path('/functions/widgets/class-mfn-widgets.php'));

// TinyMCE

require_once(get_theme_file_path('/functions/tinymce/tinymce.php'));

// plugins

require_once(get_theme_file_path('/functions/class-mfn-love.php'));
require_once(get_theme_file_path('/functions/plugins/visual-composer.php'));
require_once(get_theme_file_path('/functions/plugins/elementor/class-mfn-elementor.php'));

// WooCommerce functions

if (function_exists('is_woocommerce')) {
	require_once(get_theme_file_path('/functions/theme-woocommerce.php'));
}

// dashboard

if (is_admin()) {
	require_once(get_theme_file_path('/functions/admin/class-mfn-api.php'));
	require_once(get_theme_file_path('/functions/admin/class-mfn-helper.php'));
	require_once(get_theme_file_path('/functions/admin/class-mfn-update.php'));

	require_once(get_theme_file_path('/functions/admin/class-mfn-dashboard.php'));
	$mfn_dashboard = new Mfn_Dashboard();

	if (! isset($theme_disable['demo-data'])) {
		require_once(get_theme_file_path('/functions/importer/class-mfn-importer.php'));
	}

	require_once(get_theme_file_path('/functions/admin/tgm/class-mfn-tgmpa.php'));

	if (! mfn_is_hosted()) {
		require_once(get_theme_file_path('/functions/admin/class-mfn-status.php'));
	}

	require_once(get_theme_file_path('/functions/admin/class-mfn-support.php'));
	require_once(get_theme_file_path('/functions/admin/class-mfn-changelog.php'));
}

/**
 * @deprecated 21.0.5
 * Below constants are deprecated and will be removed soon
 * Please check if you use these constants in your Child Theme
 */

define('THEME_DIR', get_template_directory());
define('THEME_URI', get_template_directory_uri());

define('THEME_NAME', 'betheme');
define('THEME_VERSION', MFN_THEME_VERSION);

define('LIBS_DIR', get_template_directory() .'/functions');
define('LIBS_URI', get_template_directory() .'/functions');

// customize part
function wooc_extra_register_fields() {?>
       <p class="form-row form-row-first">
       <label for="country"><?php _e( 'stát/země', 'woocommerce' ); ?><span class="required">*</span></label>
	   	<select type="text"
			       id="country "
			       name="country"
				   onchange="(country.value == 'CZ')?mfcr(ico.value):mfsk(skdic.value)"
			       value="<?php echo esc_attr_e( $_POST['country'] ); ?>"
			       class="input"
			       placeholder="<?php _e( 'country', 'woocommerce' ); ?>"
			>
			<option <?php selected( $_POST['country'], 'CZ' ); ?>>CZ</option>
			<option <?php selected( $_POST['country'], 'SK' ); ?>>SK</option>
			</select>
       </p>
       <p class="form-row form-row-last">
       <label for="ico"><span id="icolabel">IČO</span><span class="required">*</span></label>
       <input type="text" class="input-text" style="<?php if($_POST['country'] == 'SK') {echo 'display:none;';} else {echo 'display:block;';} ?>" onkeyup="mfcr(ico.value)" placeholder="IČO" name="ico" id="ico" value="<?php if ( ! empty( $_POST['ico'] ) ) esc_attr_e( $_POST['ico'] ); ?>" />
	   <span id="skico" style="<?php if($_POST['country'] == 'SK') {echo 'display:flex;';} else {echo 'display:none;';} ?>color:red;font-weight: bold;">Zvolte prosím variantu vaší registrace</span>
       <input type="hidden" name="icoStatus" id="icoStatus" value="<?php if ( !empty( $_POST['icoStatus'] ) ) esc_attr_e( $_POST['icoStatus'] ); ?>" />

       <input type="hidden" name="billing_company" id="billing_company" value="<?php esc_attr_e( $_POST['billing_company'] ); ?>" />
	   <input type="hidden" name="billing_address_1" id="billing_address_1" value="<?php esc_attr_e( $_POST['billing_address_1'] ); ?>" />
	   <input type="hidden" name="billing_city" id="billing_city" value="<?php esc_attr_e( $_POST['billing_city'] ); ?>" />
	   <input type="hidden" name="billing_postcode" id="billing_postcode" value="<?php esc_attr_e( $_POST['billing_postcode'] ); ?>" />
	   <input type="hidden" name="billing_country" id="billing_country" value="<?php esc_attr_e( $_POST['billing_country'] ); ?>" />
	   <input type="hidden" name="billing_state" id="billing_state" value="<?php esc_attr_e( $_POST['billing_state'] ); ?>" />
       </p>
	
	   <p class="form-row form-row-wide" name="skdiclbl" id="skdiclbl" style="<?php if($_POST['country'] == 'SK') {echo 'display:block;';} else {echo 'display:none;';} ?>">
       <label for="skdic"><?php _e( 'Jste-li registrovaný plátce DPH zadejte prosím Vaše DIČ:', 'woocommerce' ); ?><span class="required">*</span></label>
	   <span style="display: flex;">
		<input type="text" class="input-text" onkeyup="mfsk(skdic.value)" placeholder="DIČ" name="skdic" id="skdic" value="<?php if ( !empty( $_POST['skdic'] ) ) esc_attr_e( $_POST['skdic'] ); ?>" />
		<span style="color:blue;font-weight: bold;margin-left: 15px;margin-top: 10px;white-space: nowrap;">Vyplňte pouze pokud jste plátce DPH</span>
	   </span>
       </p>

	   <p class="form-row form-row-wide" name="skicolink" id="skicolink" style="<?php if($_POST['country'] == 'SK') {echo 'display:block;';} else {echo 'display:none;';} ?>margin-top: 15px;color:blue;font-weight: bold;">
		Pro registraci na IČ, <a style="margin-top: 15px;color:red;font-weight: bold;" href="https://www.diagbox.cz/registrace-sk">prosím klikněte >>> ZDE <<<</a>
	   </p>

       <div class="clear"></div>
       <?php
 }
 add_action( 'woocommerce_register_form_start', 'wooc_extra_register_fields' );

 function wooc_extra_register_fields_end() {
    echo '<div id="personal_approval_field">';
			woocommerce_form_field( 'personal_approval', array(
				'type'      => 'checkbox',
				'required'  => 'required',
				'class'     => array('input-checkbox'),
				'label'     => __('<a href="https://www.app.cz/care/zasady-ochrany-soukromi/" target="_blank" >Souhlas se zpracováním osobních údajů</a>'),
			),  WC()->checkout->get_value( 'personal_approval' ) );
	echo '</div>';
	/*echo '<div id="tac_approval_field">';
		woocommerce_form_field( 'tac_approval', array(
			'type'      => 'checkbox',
			'required'  => 'required',
			'class'     => array('input-checkbox'),
			'label'     => __('<a href="https://www.app.cz/care/obchodni-podminky/" target="_blank" >Souhlas s obchodními podmínkami</a>'),
		),  WC()->checkout->get_value( 'tac_approval' ) );
	echo '</div>';
	*/
 }
 add_action( 'woocommerce_register_form', 'wooc_extra_register_fields_end' );

 function wooc_validate_extra_register_fields( $username, $email, $validation_errors ) {
	  global $wpdb;

      if ( isset( $_POST['ico'] ) && empty( $_POST['ico'] ) && $_POST['country'] == "CZ" ) {
          $validation_errors->add( 'ico_error', __( '<strong>Error</strong>: IČO je požadováno!', 'woocommerce' ) );
      }
      if ( isset( $_POST['skdic'] ) && empty( $_POST['skdic'] ) && $_POST['country'] == "SK" ) {
             $validation_errors->add( 'ico_error', __( '<strong>Error</strong>: DIČ je požadováno!', 'woocommerce' ) );
      }
      if ( isset( $_POST['country'] ) && empty( $_POST['country'] ) ) {
             $validation_errors->add( 'country_error', __( '<strong>Error</strong>: Country je požadováno!.', 'woocommerce' ) );
      }
	  if (strlen($_POST['ico']) > 0 && $_POST['country'] == "CZ" && $_POST['icoStatus'] == 'Ok' ) {
		  $result = $wpdb->get_var("SELECT user_id FROM wp_usermeta WHERE meta_key = 'ico' AND meta_value =  '".sanitize_text_field($_POST['ico'])."'");
	      if(!empty($result) && $result!=0) {
			$validation_errors->add('ico_error','<strong>Error</strong>: IČO je již registrováno, můžete využít funkci <a href ="https://www.diagbox.cz/muj-ucet/lost-password/">ztráta hesla</a>');
    	  }
	  }
	  if (strlen($_POST['skdic']) > 0 && $_POST['country'] == "SK" && $_POST['icoStatus'] == 'Ok' ) {
		  $result = $wpdb->get_var("SELECT user_id FROM wp_usermeta WHERE meta_key = 'dic' AND meta_value =  '".sanitize_text_field($_POST['skdic'])."'");
	      if(!empty($result) && $result!=0) {
			$validation_errors->add('ico_error','<strong>Error</strong>: DIČ je již registrován, můžete využít funkci <a href ="https://www.diagbox.cz/muj-ucet/lost-password/">ztráta hesla</a>');
    	  }
	  }

	  if (strlen($_POST['ico']) > 0 && $_POST['country'] == "CZ" && $_POST['icoStatus'] != 'Ok' ) {
		$validation_errors->add('ico_error',__( '<strong>Error</strong>: Bylo zadáno chybné IČO', 'woocommerce' ));
	  }

	  if (strlen($_POST['skdic']) > 0 && $_POST['country'] == "SK" && $_POST['icoStatus'] != 'Ok' ) {
		$validation_errors->add('ico_error',__( '<strong>Error</strong>: Bylo zadáno chybné DIČ', 'woocommerce' ));
	  }

	  if (!$_POST['personal_approval']) {
		$validation_errors->add('personal_approval_error',__( '<strong>Error</strong>: Souhlas se zpracování Vašich osobních údajů je povinný!', 'woocommerce' ));
	  }
	/*  if (!$_POST['tac_approval']) {
		$validation_errors->add('tac_approval_error',__( '<strong>Error</strong>: Souhlas s obchodními podmínkami je povinný!', 'woocommerce' ));
	  }*/
      return $validation_errors;
}
add_action( 'woocommerce_register_post', 'wooc_validate_extra_register_fields', 10, 3 );

function wooc_save_extra_register_fields( $customer_id ) {
    if ( isset( $_POST['ico']) && $_POST['country'] == "CZ" ) {
            update_user_meta( $customer_id, 'ico', sanitize_text_field( $_POST['ico'] ) );
    }
    if ( isset( $_POST['skdic']) && $_POST['country'] == "SK" ) {
            update_user_meta( $customer_id, 'dic', sanitize_text_field( $_POST['skdic'] ) );
    }
    if ( isset( $_POST['country'] ) ) {
            update_user_meta( $customer_id, 'country', sanitize_text_field( $_POST['country'] ) );
    }
	if ( isset( $_POST['billing_company'] ) ) {
            update_user_meta( $customer_id, 'billing_company', sanitize_text_field( $_POST['billing_company'] ) );
    }
	if ( isset( $_POST['billing_address_1'] ) ) {
            update_user_meta( $customer_id, 'billing_address_1', sanitize_text_field( $_POST['billing_address_1'] ) );
    }
	if ( isset( $_POST['billing_city'] ) ) {
            update_user_meta( $customer_id, 'billing_city', sanitize_text_field( $_POST['billing_city'] ) );
    }
	if ( isset( $_POST['billing_postcode'] ) ) {
            update_user_meta( $customer_id, 'billing_postcode', sanitize_text_field( $_POST['billing_postcode'] ) );
    }
	if ( isset( $_POST['billing_country'] ) ) {
            update_user_meta( $customer_id, 'billing_country', sanitize_text_field( $_POST['billing_country'] ) );
    }
	if ( isset( $_POST['billing_state'] ) ) {
            update_user_meta( $customer_id, 'billing_state', sanitize_text_field( $_POST['billing_state'] ) );
    }
}



function custom_registration_redirect($customer_id) {
    wp_logout();
    wp_redirect( 'https://www.diagnostika-delphi.cz/dekujeme-za-registraci/' );
}
add_action('woocommerce_registration_redirect', 'custom_registration_redirect');


function add_ico_to_edit_account_form() {
    $user = wp_get_current_user();
    ?>
        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="ico"><?php if (esc_attr($user->country) =="CZ"){_e( 'IČO', 'woocommerce' );} else {_e( 'DIČ', 'woocommerce' );} ?></label>
        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="ico" id="ico" value="<?php if (esc_attr($user->country) =="CZ"){echo esc_attr( $user->ico );} else {echo esc_attr( $user->dic );} ?>" readonly="readonly" />
    </p>
    <?php
}
add_action( 'woocommerce_edit_account_form', 'add_ico_to_edit_account_form' );


//MUJ UCET