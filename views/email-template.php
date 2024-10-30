<?php
defined( 'ABSPATH' ) or die();

global $lana_contact_form;
?>

<div class="lana-contact-form-email-template">
    <h3>
		<?php _e( 'Contact Form', 'lana-contact-form' ); ?>
    </h3>
    <div class="contact-form-user">
        <p>
			<?php echo sprintf( __( 'Name: %s', 'lana-contact-form' ), $lana_contact_form['fields']['name'] ); ?>
        </p>
        <p>
			<?php echo sprintf( __( 'Email: %s', 'lana-contact-form' ), $lana_contact_form['fields']['email'] ); ?>
        </p>
    </div>
    <br/>
    <div class="contact-form-message">
		<?php _e( 'Message:', 'lana-contact-form' ); ?>
        <p>
			<?php echo $lana_contact_form['fields']['message']; ?>
        </p>
    </div>
</div>