<?php
$auth = SwpmAuth::get_instance();
$setting = SwpmSettings::get_instance();
$password_reset_url = $setting->get_value('reset-page-url');
$join_url = $setting->get_value('join-us-page-url');
// Filter that allows changing of the default value of the username label on login form.
$label_username_or_email = __( 'Username or Email', 'simple-membership' );
$swpm_username_label = apply_filters('swpm_login_form_set_username_label', $label_username_or_email);

$display_password_toggle = $setting->get_value('password-visibility-login-form');
if ( empty( $display_password_toggle ) ){
    $display_password_toggle = false;
}
else{
    $display_password_toggle = true;
}
?>
<div class="swpm-login-widget-form">
    <form id="swpm-login-form" name="swpm-login-form" method="post" action="">
        <div class="swpm-login-form-inner">
            <div class="swpm-username-label">
                <label for="swpm_user_name" class="swpm-label"><?php echo SwpmUtils::_($swpm_username_label) ?></label>
            </div>
            <div class="swpm-username-input">
                <input type="text" class="swpm-text-field swpm-username-field" id="swpm_user_name" value="" size="25" name="swpm_user_name" />
            </div>
            <div class="swpm-password-label">
                <label for="swpm_password" class="swpm-label"><?php echo SwpmUtils::_('Password') ?></label>
            </div>
            <div class="swpm-password-input">                
                <input type="password" class="swpm-text-field swpm-password-field" id="swpm_password" value="" size="25" name="swpm_password" />                
            </div>
            <?php if( $display_password_toggle ){ ?>
                <div class="swpm-password-input-visibility">                                        
                    <span class="swpm-password-toggle-checkbox"><input type="checkbox" name="swpm-password-toggle-checkbox" id="swpm-password-toggle-checkbox" data-state="password-hidden" > </span>
                    <label for="swpm-password-toggle-checkbox" class="swpm-password-toggle-checkbox-label">
                        <span class="swpm-password-toggle-label"> <?php echo SwpmUtils::_('Show password') ?></span>
                    </label>
                </div>
            <?php } ?>
            <div class="swpm-remember-me">
                <span class="swpm-remember-checkbox"><input type="checkbox" name="rememberme" id="swpm-rememberme"></span>
                <label for="swpm-rememberme" class="swpm-rememberme-label">
                    <span class="swpm-rember-label"> <?php echo SwpmUtils::_('Remember Me') ?></span>
                </label>
            </div>

            <div class="swpm-before-login-submit-section"><?php echo apply_filters('swpm_before_login_form_submit_button', ''); ?></div>

            <div class="swpm-login-submit">
                <input type="submit" class="swpm-login-form-submit" name="swpm-login" value="<?php echo SwpmUtils::_('Log In') ?>"/>
            </div>
            <div class="swpm-forgot-pass-link">
                <a id="forgot_pass" class="swpm-login-form-pw-reset-link"  href="<?php echo $password_reset_url; ?>"><?php echo SwpmUtils::_('Forgot Password?') ?></a>
            </div>
            <div class="swpm-join-us-link">
                <a id="register" class="swpm-login-form-register-link" href="<?php echo $join_url; ?>"><?php echo SwpmUtils::_('Join Us') ?></a>
            </div>
            <div class="swpm-login-action-msg">
                <span class="swpm-login-widget-action-msg"><?php echo apply_filters( 'swpm_login_form_action_msg', $auth->get_message() ); ?></span>
            </div>
        </div>
    </form>
</div>
