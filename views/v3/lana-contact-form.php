<?php
defined( 'ABSPATH' ) or die();
?>
<div class="lana-contact-form-feedback">
	<?php
	lana_contact_form_get_infos();
	lana_contact_form_get_errors();
	?>
</div>
<form id="lana-contact-form" method="post" class="form-horizontal" role="form">
	<?php wp_nonce_field( 'send', 'lana_contact_form_nonce_field' ); ?>

    <div class="form-group">
        <label for="lana-contact-name" class="col-sm-3 control-label">
			<?php _e( 'Name', 'lana-contact-form' ); ?>
        </label>

        <div class="col-sm-6">
            <input type="text" name="lana_contact[name]" id="lana-contact-name" class="form-control"
                   value="<?php echo esc_attr( lana_contact_form_get_field_value( 'name' ) ); ?>" required/>
        </div>
    </div>

    <div class="form-group">
        <label for="lana-contact-email" class="col-sm-3 control-label">
			<?php _e( 'Email', 'lana-contact-form' ); ?>
        </label>

        <div class="col-sm-6">
            <input type="email" name="lana_contact[email]" id="lana-contact-email" class="form-control"
                   value="<?php echo esc_attr( lana_contact_form_get_field_value( 'email' ) ); ?>" required/>
        </div>
    </div>

    <div class="form-group">
        <label for="lana-contact-message" class="col-sm-3 control-label">
			<?php _e( 'Message', 'lana-contact-form' ); ?>
        </label>

        <div class="col-sm-6">
			<textarea name="lana_contact[message]" id="lana-contact-message" class="form-control" rows="10"
                      required><?php echo esc_textarea( lana_contact_form_get_field_value( 'message' ) ); ?></textarea>
        </div>
    </div>

    <div class="form-group">
        <label for="lana-contact-captcha" class="col-sm-3 text-right">
            <img src="data:image/png;base64,<?php echo esc_attr( lana_contact_form_get_base64_captcha() ); ?>"
                 class="pull-right"/>
        </label>

        <div class="col-sm-6">
            <input type="number" name="lana_contact[captcha]" id="lana-contact-captcha" class="form-control"
                   size="2" min="0" max="20" required/>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-6">
            <div class="form-check">
                <input type="checkbox" name="lana_contact[privacy_policy]" id="lana-contact-privacy-policy"
                       class="form-check-input" required/>
                <label class="form-check-label" for="lana-contact-privacy-policy">
					<?php echo sprintf( __( 'I have read and agree to the %s', 'lana-contact-form' ), lana_contact_form_get_privacy_policy_text() ); ?>
                </label>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-6">
            <button type="submit" name="lana_contact_submit" class="btn btn-primary">
				<?php _e( 'Submit', 'lana-contact-form' ); ?>
            </button>
        </div>
    </div>
</form>