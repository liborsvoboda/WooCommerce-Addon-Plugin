<?php
/*
Plugin Name: IČO Registration Fields
Plugin URI:
Description:
Version: 0.1
Author: Libor Svoboda
Author URI:
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

/**
 * Front end registration
 */
function add_custom_script(){
    wp_enqueue_script('my-custom-script-handle', './js/custom_script.js',array('jquery'), '1', true);
}
add_action('wp_enqueue_style','add_custom_script');

function add_ico_field() {

	$ico = ! empty( $_POST['ico'] ) ? intval( $_POST['ico'] ) : '';
	$country = ! empty( $_POST['country'] ) ? esc_attr( $_POST['country'] ) : '';
	$icoStatus = ! empty( $_POST['icoStatus'] ) ? esc_attr($_POST['icoStatus']) : '';


	?>
	<p>
		<label for="country"><?php esc_html_e( 'stát/země', 'crf' ) ?><br/>
			<select type="text"
			       id="country "
			       name="country"
				   onchange="(country.value == 'CZ')?mfcr(ico.value):mfsk(skdic.value)"
			       value="<?php echo esc_attr( $country ); ?>"
			       class="input"
			       placeholder="Country"
			>
			<option <?php selected( $_POST['country'], 'CZ' ); ?>>CZ</option>
			<option <?php selected( $_POST['country'], 'SK' ); ?>>SK</option>
			</select>
		</label>
	</p>
	<p>
		<label for="ico"><span id="icolabel">IČO</span><br/>
			<input type="text"
			       id="ico"
			       name="ico"
			       value="<?php echo esc_attr( $ico ); ?>"
			       class="input"
			       onkeyup="mfcr(ico.value)"
			       placeholder="Ičo"
				   style="<?php if($_POST['country'] == 'SK') {echo 'display:none;';} else {echo 'display:block;';} ?>"
			/>
			<span id="skico" style="<?php if($_POST['country'] == 'SK') {echo 'display:flex;';} else {echo 'display:none;';} ?>color:red;font-weight: bold;">Zvolte prosím variantu vaší registrace</span>
			<input type="hidden" id="icoStatus" name="icoStatus" value="<?php echo esc_attr( $icoStatus ); ?>" />
		</label>
	</p>
	<p class="form-row form-row-wide" name="skdiclbl" id="skdiclbl" style="<?php if($_POST['country'] == 'SK') {echo 'display:block;';} else {echo 'display:none;';} ?>">
		<label for="skdic" style="color:#444;"><?php _e( 'Jste-li registrovaný plátce DPH zadejte prosím Vaše DIČ:', 'woocommerce' ); ?></label>
		<span style="display: flex;">
		<input type="text" class="input-text" onkeyup="mfsk(skdic.value)" placeholder="DIČ" name="skdic" id="skdic" value="<?php if ( !empty( $_POST['skdic'] ) ) esc_attr_e( $_POST['skdic'] ); ?>" />
		</span>
    </p>

	<p class="form-row form-row-wide" name="skicolink" id="skicolink" style="<?php if($_POST['country'] == 'SK') {echo 'display:block;';} else {echo 'display:none;';} ?>margin-bottom: 15px;color:blue;font-weight: bold;">
		Pro registraci na IČ, <a style="margin-top: 15px;color:red;font-weight: bold;" href="https://www.diagbox.cz/registrace-sk">klikněte >>> ZDE <<<</a>
	</p>
	<?php
}
add_action( 'register_form', 'add_ico_field' );

function crf_registration_errors( $errors, $sanitized_user_login, $user_email ) {
	global $wpdb;
	if (strlen($_POST['ico']) == 0 && $_POST['country'] == "CZ") {
		$errors->add('ico_error','<strong>Error</strong>: pole IČO je povinné');
	}
	if (strlen($_POST['skdic']) == 0 && $_POST['country'] == "SK") {
		$errors->add('dic_error','<strong>Error</strong>: pole DIČ je povinné');
	}

	if (strlen($_POST['ico']) > 0 && $_POST['country'] == "CZ" && $_POST['icoStatus'] == 'Ok' ) {
	  $result = $wpdb->get_var("SELECT user_id FROM wp_usermeta WHERE meta_key = 'ico' AND meta_value =  '".intval($_POST['ico'])."'");
	  if(!empty($result) && $result!=0) {
		$validation_errors->add('ico_error','<strong>Error</strong>: IČO je již registrováno, můžete využít funkci <a href ="https://www.diagbox.cz/muj-ucet/lost-password/">ztráta hesla</a>');
      }
	}

	if (strlen($_POST['skdic']) > 0 && $_POST['country'] == "SK" && $_POST['icoStatus'] == 'Ok' ) {
	  $result = $wpdb->get_var("SELECT user_id FROM wp_usermeta WHERE meta_key = 'dic' AND meta_value =  '".intval($_POST['skdic'])."'");
	  if(!empty($result) && $result!=0) {
		$validation_errors->add('ico_error','<strong>Error</strong>: DIČ je již registrován, můžete využít funkci <a href ="https://www.diagbox.cz/muj-ucet/lost-password/">ztráta hesla</a>');
      }
	}

	if (strlen($_POST['ico']) > 0 && $_POST['country'] == "CZ" && $_POST['icoStatus'] != 'Ok' ) {
		$errors->add('ico_error','<strong>Error</strong>: Bylo zadáno chybné IČO');
	}

	if (strlen($_POST['skdic']) > 0 && $_POST['country'] == "SK" && $_POST['icoStatus'] != 'Ok' ) {
		$errors->add('ico_error','<strong>Error</strong>: Bylo zadáno chybné DIČ');
	}

	if (!$_POST['personal_approval']) {
		$errors->add('personal_approval_error','<strong>Error</strong>: Souhlas se zpracování Vašich osobních údajů je povinný!');
	}
	/*if (!$_POST['tac_approval']) {
		$errors->add('tac_approval_error','<strong>Error</strong>: Souhlas s obchodními podmínkami je povinný!');
	}*/
	return $errors;
}
add_filter( 'registration_errors', 'crf_registration_errors', 10, 3 );

function crf_user_register( $user_id ) {
	if ( !empty($_POST['ico']) && $_POST['country'] == "CZ" ) {
		update_user_meta( $user_id, 'ico', intval( $_POST['ico'] ) );
		update_user_meta( $user_id, 'country', $_POST['country']);
	}

	if ( !empty( $_POST['skdic']) && $_POST['country'] == "SK" ) {
        update_user_meta( $user_id, 'dic', intval( $_POST['skdic'] ) );
		update_user_meta( $user_id, 'country', $_POST['country']);
    }
}
add_action( 'user_register', 'crf_user_register' );

// Function to check starting char of a string
function startsWith($haystack, $needle){
    return $needle === '' || strpos($haystack, $needle) === 0;
}

//remove username field
add_action('login_head', function(){
?>
    <style>
        #registerform > p:first-child{
            display:none;
        }
    </style>

    <script type="text/javascript" src="<?php echo site_url('/wp-includes/js/jquery/jquery.js'); ?>"></script>
    <script type="text/javascript">
        jQuery(document).ready(function($){
            $('#registerform > p:first-child').css('display', 'none');
        });
    </script>
<?php
});

//Remove error for username, only show error for email only.
add_filter('registration_errors', function($wp_error, $sanitized_user_login, $user_email){
    if(isset($wp_error->errors['empty_username'])){
        unset($wp_error->errors['empty_username']);
    }

    if(isset($wp_error->errors['username_exists'])){
        unset($wp_error->errors['username_exists']);
    }
    return $wp_error;
}, 10, 3);

add_action('login_form_register', function(){
    if(isset($_POST['user_login']) && isset($_POST['user_email']) && !empty($_POST['user_email'])){
        $_POST['user_login'] = $_POST['user_email'];
    }
});

// Custom function to display the Billing Address form to registration page
function zk_add_billing_form_to_registration(){
    global $woocommerce;
    $checkout = $woocommerce->checkout();
	foreach ( $checkout->get_checkout_fields( 'billing' ) as $key => $field ) : 
	 if($key!='billing_email' && $key!='billing_address_2' && $key!='billing_phone'){ 
            $field['placeholder'] = $field['label'];	
            $field['class'] = array('hideElement');	
            woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
          } 
	endforeach; 
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
	echo '</div>';*/
}
add_action('register_form','zk_add_billing_form_to_registration');


// Custom function to save Usermeta or Billing Address of registered user
function zk_save_billing_address($user_id){
    global $woocommerce;
    $address = $_POST;
    foreach ($address as $key => $field){
        if(startsWith($key,'billing_')){
            // Condition to add firstname and last name to user meta table
            if($key == 'billing_first_name' || $key == 'billing_last_name'){
                $new_key = explode('billing_',$key);
                update_user_meta( $user_id, $new_key[1], $_POST[$key] );
            }
            update_user_meta( $user_id, $key, $_POST[$key] );
        }
    }
}
add_action('user_register','zk_save_billing_address');

/*function cz_user_approve( $user_id ) { 
	$roles = get_userdata( $user_id )->roles; 
	if ($_POST['country'] == "CZ" && 'pending' == pw_new_user_approve()->get_user_status( $user_id ) && in_array( 'user', $roles ) ) { 
		pw_new_user_approve()->update_user_status( $user_id, 'approve' ); 
	} 
} 
add_action( 'user_register', 'cz_user_approve', 999, 1 );
*/

//editing form
function edit_ico_field($user_id) {
	?>
	 <table class="form-table">
	<tr>
       <th><label for="contact"><?php if(esc_attr( get_the_author_meta( 'country', $user_id->ID )) == "CZ") {echo "IČO";} else {echo "DIČ";}?></label></th>
	    <td><input type="text" class="input-text form-control" name="ico" id="ico" value="<?php if(esc_attr( get_the_author_meta( 'country', $user_id->ID )) == "CZ") {echo esc_attr( get_the_author_meta( 'ico', $user_id->ID ));} else {echo esc_attr( get_the_author_meta( 'dic', $user_id->ID ));}?>" readonly="readonly" /></td>
	</tr>
    </table>
	<?php
}
function edit_user_ico_field($user_id) {
	?>
	 <table class="form-table">
	<tr>
       <th><label for="contact"><?php if(esc_attr( get_the_author_meta( 'country', $user_id->ID )) == "CZ") {echo "IČO";} else {echo "DIČ";}?></label></th>
	   <td><input type="text" class="input-text form-control" name="ico" id="ico" value="<?php if(esc_attr( get_the_author_meta( 'country', $user_id->ID )) == "CZ") {echo esc_attr( get_the_author_meta( 'ico', $user_id->ID ));} else {echo esc_attr( get_the_author_meta( 'dic', $user_id->ID ));}?>" /></td>
	</tr>
    </table>
	<?php
}
add_action( 'show_user_profile', 'edit_ico_field', 10, 1 ); 
add_action( 'edit_user_profile', 'edit_user_ico_field', 10, 1 ); 

function update_extra_profile_fields($user_id) {
	if (! empty( $_POST['ico'])){
		if(esc_attr( get_the_author_meta( 'country', $user_id->ID )) == "CZ") {update_user_meta($user_id, 'ico', $_POST['ico']);}
		if(esc_attr( get_the_author_meta( 'country', $user_id->ID )) == "SK") {update_user_meta($user_id, 'dic', $_POST['ico']);}
	}
}
add_action('edit_user_profile_update', 'update_extra_profile_fields');
add_action('personal_options_update', 'update_extra_profile_fields');


//WPaction=register
?>