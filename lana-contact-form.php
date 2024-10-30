<?php
/**
 * Plugin Name: Lana Contact Form
 * Plugin URI: https://lana.codes/product/lana-contact-form/
 * Description: Easy to use contact form with captcha.
 * Version: 1.4.0
 * Author: Lana Codes
 * Author URI: https://lana.codes/
 * Text Domain: lana-contact-form
 * Domain Path: /languages
 */

defined( 'ABSPATH' ) or die();
define( 'LANA_CONTACT_FORM_VERSION', '1.4.0' );
define( 'LANA_CONTACT_FORM_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'LANA_CONTACT_FORM_DIR_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Lana Contact Form
 * Modifiable constants
 */
if ( ! defined( 'LANA_CONTACT_FORM_DEFAULT_BOOTSTRAP_VERSION' ) ) {
	define( 'LANA_CONTACT_FORM_DEFAULT_BOOTSTRAP_VERSION', 3 );
}

/**
 * Language
 * load
 */
load_plugin_textdomain( 'lana-contact-form', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

/**
 * Lana Contact Form
 * get bootstrap version
 * @return bool|int
 */
function lana_contact_form_get_bootstrap_version() {

	if ( wp_style_is( 'bootstrap', 'registered' ) ) {
		$wp_styles = wp_styles();

		list( $version ) = explode( '.', $wp_styles->registered['bootstrap']->ver );

		if ( version_compare( $version, '4', '=' ) ) {
			return 4;
		}

		if ( version_compare( $version, '3', '=' ) ) {
			return 3;
		}
	}

	if ( defined( 'LANA_CONTACT_FORM_DEFAULT_BOOTSTRAP_VERSION' ) ) {
		return LANA_CONTACT_FORM_DEFAULT_BOOTSTRAP_VERSION;
	}

	return false;
}

/**
 * Lana Contact Form
 * session start
 */
function lana_contact_form_register_session() {
	global $post;

	/** check not admin page */
	if ( is_admin() ) {
		return;
	}

	/** check post */
	if ( ! is_a( $post, 'WP_Post' ) ) {
		return;
	}

	/** check shortcode */
	$has_shortcode = has_shortcode( $post->post_content, 'lana_contact_form' );

	/** check widget */
	$has_widget = is_active_widget( false, false, 'lana_contact_form' );

	/** check shortcode and widget */
	if ( ! in_array( true, array( $has_shortcode, $has_widget ) ) ) {
		return;
	}

	/** session start */
	if ( ! session_id() ) {
		session_start();
	}
}

add_action( 'wp', 'lana_contact_form_register_session' );

/**
 * Styles
 * load in plugin
 */
function lana_contact_form_bootstrap_styles() {

	if ( ! wp_style_is( 'bootstrap' ) ) {

		wp_register_style( 'lana-contact-form', LANA_CONTACT_FORM_DIR_URL . '/assets/css/lana-contact-form.css', array(), LANA_CONTACT_FORM_VERSION );
		wp_enqueue_style( 'lana-contact-form' );
	}
}

add_action( 'wp_enqueue_scripts', 'lana_contact_form_bootstrap_styles', 1001 );

/**
 * Lana Contact Form
 * form post handle
 */
function lana_contact_form_post_handle() {
	global $lana_contact_form;

	if ( ! isset( $_POST['lana_contact_submit'] ) ) {
		return;
	}

	error_reporting( 0 );
	lana_contact_form_register_session();

	/**
	 * Fields
	 */
	$fields = array(
		'name'           => sanitize_text_field( wp_unslash( $_POST['lana_contact']['name'] ) ),
		'email'          => sanitize_email( $_POST['lana_contact']['email'] ),
		'message'        => sanitize_textarea_field( wp_unslash( $_POST['lana_contact']['message'] ) ),
		'captcha'        => absint( $_POST['lana_contact']['captcha'] ),
		'privacy_policy' => boolval( $_POST['lana_contact']['privacy_policy'] ),
	);

	/**
	 * Errors
	 * @var WP_Error $errors
	 */
	$errors = new WP_Error();

	/**
	 * Infos
	 */
	$infos = array();

	/**
	 * Validate
	 * Nonce field
	 */
	if ( empty( $_POST['lana_contact_form_nonce_field'] ) ) {
		$errors->add( 'nonce_field_empty', __( 'The nonce field is empty.', 'lana-contact-form' ) );
	}

	if ( ! wp_verify_nonce( $_POST['lana_contact_form_nonce_field'], 'send' ) ) {
		$errors->add( 'nonce_field_incorrect', __( 'That nonce field was incorrect.', 'lana-contact-form' ) );
	}

	/**
	 * Validate
	 * Name
	 */
	if ( empty( $fields['name'] ) ) {
		$errors->add( 'name_field_empty', __( 'Please enter your name.', 'lana-contact-form' ) );
	}

	/**
	 * Validate
	 * Email
	 */
	if ( empty( $fields['email'] ) ) {
		$errors->add( 'email_field_empty', __( 'Please enter your email.', 'lana-contact-form' ) );
	}

	if ( filter_var( $fields['email'], FILTER_VALIDATE_EMAIL ) == false ) {
		$errors->add( 'email_field_not_valid', __( 'Please enter valid email address.', 'lana-contact-form' ) );
	}

	/**
	 * Validate
	 * Message
	 */
	if ( empty( $fields['message'] ) ) {
		$errors->add( 'message_field_empty', __( 'Please enter a message.', 'lana-contact-form' ) );
	}

	/**
	 * Validate
	 * captcha
	 */
	if ( empty( $fields['captcha'] ) ) {
		$errors->add( 'captcha_field_empty', __( 'Please enter a captcha.', 'lana-contact-form' ) );
	}

	if ( $fields['captcha'] != $_SESSION['lana_contact_form']['captcha'] ) {
		$errors->add( 'captcha_field_incorrect', __( 'That captcha was incorrect. Try again.', 'lana-contact-form' ) );
	}

	/**
	 * Validate
	 * privacy policy
	 */
	if ( ! $fields['privacy_policy'] ) {
		$errors->add( 'privacy_policy_field_not_accepted', __( 'Please read and accept the privacy policy.', 'lana-contact-form' ) );
	}

	/** unset captcha */
	unset( $fields['captcha'] );

	/** set global lana contact form */
	$lana_contact_form = array(
		'fields' => $fields,
		'errors' => $errors,
	);

	/**
	 * Validate
	 * errors
	 */
	if ( ! empty( $errors->get_error_codes() ) ) {
		return;
	}

	/** email to admin */
	$email_to = get_option( 'admin_email' );

	/** set subject */
	$subject = sprintf( __( '[%s] Contact from %s', 'lana-contact-form' ), get_bloginfo( 'name' ), $fields['name'] );

	/** get message from email template */
	ob_start();
	lana_contact_form_include_view( 'email-template.php' );
	$message = ob_get_clean();

	/** add wp_mail filters */
	add_filter( 'wp_mail_content_type', 'lana_contact_form_wp_mail_content_type', 10, 0 );
	add_filter( 'wp_mail_from', 'lana_contact_form_wp_mail_from', 10, 0 );
	add_filter( 'wp_mail_from_name', 'lana_contact_form_wp_mail_from_name', 10, 0 );

	/**
	 * Send
	 * filters
	 */
	$email_to = apply_filters( 'lana_contact_form_send_email_to', $email_to );
	$subject  = apply_filters( 'lana_contact_form_send_subject', $subject );
	$message  = apply_filters( 'lana_contact_form_send_message', $message );

	/**
	 * Send
	 * contact form email to admin
	 */
	wp_mail( $email_to, $subject, $message );

	/** remove wp_mail filters */
	remove_filter( 'wp_mail_content_type', 'lana_contact_form_wp_mail_content_type', 10 );
	remove_filter( 'wp_mail_from', 'lana_contact_form_wp_mail_from', 10 );
	remove_filter( 'wp_mail_from_name', 'lana_contact_form_wp_mail_from_name', 10 );

	/** set fields */
	$fields = array(
		'name'    => '',
		'email'   => '',
		'message' => '',
	);

	/** add infos */
	$infos[] = __( 'Your email was sent.', 'lana-contact-form' );

	/** set global lana contact form */
	$lana_contact_form = array(
		'fields' => $fields,
		'infos'  => $infos,
	);
}

add_action( 'wp_head', 'lana_contact_form_post_handle' );

/**
 * Lana Contact Form
 * text/html email content type
 * @return string
 */
function lana_contact_form_wp_mail_content_type() {
	return 'text/html';
}

/**
 * Lana Contact Form
 * from
 * @return string
 */
function lana_contact_form_wp_mail_from() {
	global $lana_contact_form;

	return $lana_contact_form['fields']['email'];
}

/**
 * Lana Contact Form
 * from name
 * @return string
 */
function lana_contact_form_wp_mail_from_name() {
	global $lana_contact_form;

	return $lana_contact_form['fields']['name'];
}

/**
 * Lana Contact Form
 * get info from lana_contact infos
 */
function lana_contact_form_get_infos() {
	global $lana_contact_form;

	if ( ! isset( $lana_contact_form['infos'] ) ) {
		return;
	}

	/** @var array $infos */
	$infos = $lana_contact_form['infos'];

	if ( ! empty( $infos ) ) :
		foreach ( $infos as $info ) :
			?>
            <div class="alert alert-info alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong><?php _e( 'Info', 'lana-contact-form' ); ?>!</strong>
				<?php echo $info; ?>
            </div>
		<?php
		endforeach;
	endif;
}

/**
 * Lana Contact Form
 * get error from lana_contact errors
 */
function lana_contact_form_get_errors() {
	global $lana_contact_form;

	if ( ! isset( $lana_contact_form['errors'] ) ) {
		return;
	}

	if ( ! is_a( $lana_contact_form['errors'], 'WP_Error' ) ) {
		return;
	}

	/** @var WP_Error $errors */
	$errors = $lana_contact_form['errors'];

	if ( ! empty( $errors->get_error_codes() ) ) :
		foreach ( $errors->get_error_messages() as $error_message ) :
			?>
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong><?php _e( 'Error', 'lana-contact-form' ); ?>!</strong>
				<?php echo $error_message; ?>
            </div>
		<?php
		endforeach;
	endif;
}

/**
 * Lana Contact Form
 * get captcha
 * @return string
 */
function lana_contact_form_get_captcha() {

	error_reporting( 0 );
	@lana_contact_form_register_session();

	$image = imagecreatetruecolor( 70, 30 );
	imagesavealpha( $image, true );
	imagealphablending( $image, false );
	$transparent = imagecolorallocatealpha( $image, 255, 255, 255, 127 );
	$black       = imagecolorallocate( $image, 0, 0, 0 );
	$num1        = rand( 1, 9 );
	$num2        = rand( 1, 9 );
	$str         = $num1 . ' + ' . $num2 . ' = ';
	$font        = dirname( __FILE__ ) . '/assets/fonts/bebas.ttf';

	imagefill( $image, 0, 0, $transparent );
	imagettftext( $image, 18, 0, 0, 24, $black, $font, $str );

	ob_start();
	imagepng( $image );
	$image_data = ob_get_clean();
	imagedestroy( $image );

	$_SESSION['lana_contact_form']['captcha'] = $num1 + $num2;

	return $image_data;
}

/**
 * Lana Contact Form
 * get privacy policy url
 * @return bool|string
 */
function lana_contact_form_get_privacy_policy_url() {

	$privacy_policy_url = false;

	/** check wp privacy policy */
	if ( function_exists( 'get_privacy_policy_url' ) ) {
		$privacy_policy_url = get_privacy_policy_url();
	}

	return apply_filters( 'lana_contact_form_privacy_policy_url', $privacy_policy_url );
}

/**
 * Lana Contact Form
 * get privacy policy text
 * @return bool|string
 */
function lana_contact_form_get_privacy_policy_text() {

	/** privacy policy text */
	$lana_contact_form_privacy_policy_text = __( 'Privacy Policy', 'lana-contact-form' );

	/** get privacy policy url */
	if ( $lana_contact_form_privacy_policy_url = lana_contact_form_get_privacy_policy_url() ) {
		$lana_contact_form_privacy_policy_text = sprintf( '<a href="%s" target="_blank">%s</a>', $lana_contact_form_privacy_policy_url, $lana_contact_form_privacy_policy_text );
	}

	return apply_filters( 'lana_contact_form_privacy_policy_text', $lana_contact_form_privacy_policy_text );
}

/**
 * Lana Contact Form
 * get base64 encoded captcha
 * @return string
 */
function lana_contact_form_get_base64_captcha() {
	return base64_encode( lana_contact_form_get_captcha() );
}

/**
 * Lana Contact Form
 * get field value
 *
 * @param $field
 *
 * @return mixed|null
 */
function lana_contact_form_get_field_value( $field ) {
	global $lana_contact_form;

	/** check field */
	if ( ! isset( $lana_contact_form['fields'][ $field ] ) ) {
		return null;
	}

	return $lana_contact_form['fields'][ $field ];
}

/**
 * Lana Contact Form
 * include view
 *
 * @param $view_name
 */
function lana_contact_form_include_view( $view_name ) {

	$bootstrap_version = lana_contact_form_get_bootstrap_version();

	/** add theme views */
	$view_files = array(
		get_stylesheet_directory() . '/lana-contact-form/' . $view_name,
		get_template_directory() . '/lana-contact-form/' . $view_name,
	);

	/** add bootstrap view */
	if ( $bootstrap_version ) {
		$view_files[] = dirname( __FILE__ ) . '/views/v' . $bootstrap_version . '/' . $view_name;
	}

	/** add default view */
	$view_files[] = dirname( __FILE__ ) . '/views/' . $view_name;

	/** filter views */
	$view_files = apply_filters( 'lana_contact_form_view_files', $view_files, $view_name );

	$view_to_inculde = false;

	foreach ( $view_files as $view_file ) {
		if ( file_exists( $view_file ) ) {
			$view_to_inculde = $view_file;
			break;
		}
	}

	if ( $view_to_inculde ) {
		/** @noinspection PhpIncludeInspection */
		include $view_to_inculde;

		return;
	}

	wp_die( sprintf( __( 'Error! Failed to find a view path for the specified view: %s', 'lana-contact-form' ), $view_name ) );
}

/**
 * Lana Contact Form
 * include html
 * @return string
 */
function lana_contact_form() {

	ob_start();
	lana_contact_form_include_view( 'lana-contact-form.php' );
	$output = ob_get_clean();

	return $output;
}

/**
 * Lana Contact Form Shortcode
 * @return string
 */
function lana_contact_form_shortcode() {
	return lana_contact_form();
}

add_shortcode( 'lana_contact_form', 'lana_contact_form_shortcode' );

/**
 * Init Widget
 */
add_action( 'widgets_init', function () {
	include_once LANA_CONTACT_FORM_DIR_PATH . '/includes/class-lana-contact-form-widget.php';
	register_widget( 'Lana_Contact_Form_Widget' );
} );