<?php /**
 * Template version: 3.0.0
 * Template zone: admin
 *
 * -= 3.0.0 =-
 * - Updated markup for new master-skin
 *
 * -= 1.0.0 =-
 * - Initial version
 */ ?>

<?php
/*  Copyright 2013 Foobar Studio (contact@foobar.studio)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/
?>

<?php /** @var $address array */ ?>
<?php /** @var $address_id string */ ?>
<?php /** @var $address_class string */ ?>
<?php /** @var $address_label string */ ?>
<?php /** @var $address_actions array */ ?>
<?php /** @var $extra_scripts string */ ?>
<?php /** @var $excluded_fields array */ ?>

<div class="cuar-address cuar-js-address cuar-<?php echo $address_class; ?>" data-address-id="<?php echo $address_id; ?>">
    <?php wp_nonce_field('cuar_' . $address_id, 'cuar_nonce'); ?>

    <div class="cuar-progress cuar-js-progress" style="display: none;">
        <span class="indeterminate"></span>
    </div>

    <?php if ( !empty($address_actions)) : ?>
        <div class="cuar-address-actions cuar-js-address-actions">
            <?php foreach ($address_actions as $action => $desc) : ?>
                <a href="#" class="button cuar-action cuar-js-action cuar-js-<?php echo esc_attr($action); ?>" title="<?php echo esc_attr($desc['tooltip']); ?>"><?php
                    echo $desc['label'];
                    ?></a>&nbsp;
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (!in_array('name', $excluded_fields)) : ?>
    <div class="form-group cuar-js-address-field-container cuar-js-address-name">
        <label for="<?php echo $address_id; ?>_name" class="control-label"><?php _e('Name', 'cuar'); ?></label>

        <input type="text" name="cuar_options[<?php echo $address_id; ?>][name]" id="<?php echo $address_id; ?>_name" value="<?php echo esc_attr($address['name']); ?>" placeholder="<?php esc_attr_e('Name', 'cuar'); ?>" class="regular-text cuar-js-address-field"/>
    </div>
    <?php endif; ?>
    <?php if (!in_array('company', $excluded_fields)) : ?>
    <div class="form-group cuar-js-address-field-container cuar-js-address-company">
        <label for="<?php echo $address_id; ?>_company" class="control-label"><?php _e('Company', 'cuar'); ?></label>

        <input type="text" name="cuar_options[<?php echo $address_id; ?>][company]" id="<?php echo $address_id; ?>_company" value="<?php echo esc_attr($address['company']); ?>" placeholder="<?php esc_attr_e('Company', 'cuar'); ?>" class="regular-text cuar-js-address-field"/>
    </div>
    <?php endif; ?>
    <?php if (!in_array('vat_number', $excluded_fields)) : ?>
    <div class="form-group cuar-js-address-field-container cuar-js-address-vat-number">
        <label for="<?php echo $address_id; ?>_vat_id" class="control-label"><?php _e('VAT Number', 'cuar'); ?></label>

        <input type="text" name="cuar_options[<?php echo $address_id; ?>][vat_number]" id="<?php echo $address_id; ?>_vat_number" value="<?php echo esc_attr($address['vat_number']); ?>" placeholder="<?php esc_attr_e('VAT Number', 'cuar'); ?>" class="regular-text cuar-js-address-field"/>
    </div>
    <?php endif; ?>
    <?php if (!in_array('logo_url', $excluded_fields)) : ?>
    <div class="form-group cuar-js-address-field-container cuar-js-address-logo-url">
        <label for="<?php echo $address_id; ?>_logo_url" class="control-label"><?php _e('Logo', 'cuar'); ?></label>

        <div class="cuar-js-upload-control">
            <input type="text" name="cuar_options[<?php echo $address_id; ?>][logo_url]" id="<?php echo $address_id; ?>_logo_url" value="<?php echo esc_attr($address['logo_url']); ?>" placeholder="<?php esc_attr_e('Logo URL', 'cuar'); ?>" class="regular-text cuar-js-address-field cuar-upload-input"/>
            <span>&nbsp;<input type="button" class="cuar-upload-button button-secondary" value="<?php _e( 'Upload Logo', 'cuar' ); ?>"/></span>
        </div>
    </div>
    <?php endif; ?>
    <?php if (!in_array('line1', $excluded_fields)) : ?>
    <div class="form-group cuar-js-address-field-container cuar-js-address-line1">
        <label for="<?php echo $address_id; ?>_line1" class="control-label"><?php _e('Street address', 'cuar'); ?></label>

        <input type="text" name="cuar_options[<?php echo $address_id; ?>][line1]" id="<?php echo $address_id; ?>_line1" value="<?php echo esc_attr($address['line1']); ?>" placeholder="<?php esc_attr_e('Street address, line 1', 'cuar'); ?>" class="regular-text cuar-js-address-field"/>
    </div>
    <?php endif; ?>
    <?php if (!in_array('line2', $excluded_fields)) : ?>
    <div class="form-group cuar-js-address-field-container cuar-js-address-line2">
        <input type="text" name="cuar_options[<?php echo $address_id; ?>][line2]" id="<?php echo $address_id; ?>_line2" value="<?php echo esc_attr($address['line2']); ?>" placeholder="<?php esc_attr_e('Street address, line 2', 'cuar'); ?>" class="regular-text cuar-js-address-field"/>
    </div>
    <?php endif; ?>
    <?php if (!in_array('zip', $excluded_fields)) : ?>
    <div class="form-group cuar-js-address-field-container cuar-js-address-zip">
        <label for="<?php echo $address_id; ?>_zip" class="control-label"><?php _e('Zip/Postal code', 'cuar'); ?></label>

        <input type="text" name="cuar_options[<?php echo $address_id; ?>][zip]" id="<?php echo $address_id; ?>_zip" value="<?php echo esc_attr($address['zip']); ?>" placeholder="<?php esc_attr_e('Zip/Postal code', 'cuar'); ?>" class="regular-text cuar-js-address-field"/>
    </div>
    <?php endif; ?>
    <?php if (!in_array('city', $excluded_fields)) : ?>
    <div class="form-group cuar-js-address-field-container cuar-js-address-city">
        <label for="<?php echo $address_id; ?>_city" class="control-label"><?php _e('City', 'cuar'); ?></label>

        <input type="text" name="cuar_options[<?php echo $address_id; ?>][city]" id="<?php echo $address_id; ?>_city" value="<?php echo esc_attr($address['city']); ?>" placeholder="<?php esc_attr_e('City', 'cuar'); ?>" class="regular-text cuar-js-address-field"/>
    </div>
    <?php endif; ?>
    <div class="cuar-js-country-state-inputs">
        <?php if (!in_array('country', $excluded_fields)) : ?>
        <div class="form-group cuar-js-address-field-container cuar-js-address-country">
            <label for="<?php echo $address_id; ?>_country" class="control-label"><?php _e('Country', 'cuar'); ?></label>

            <select name="cuar_options[<?php echo $address_id; ?>][country]" id="<?php echo $address_id; ?>_country" class="regular-text cuar-js-address-field">
                <?php foreach (CUAR_CountryHelper::getCountries() as $code => $label) : ?>
                    <option value="<?php echo esc_attr($code); ?>" <?php selected($address['country'], $code); ?>><?php echo $label; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php endif; ?>
        <?php if (!in_array('state', $excluded_fields)) : ?>
        <?php $country_states = CUAR_CountryHelper::getStates($address['country']); ?>
        <div class="form-group cuar-js-address-field-container cuar-js-address-state" <?php if (empty($country_states)) echo 'style="display: none;"'; ?>>
            <label for="<?php echo $address_id; ?>_state" class="control-label"><?php _e('State/Province', 'cuar'); ?></label>

            <select name="cuar_options[<?php echo $address_id; ?>][state]" id="<?php echo $address_id; ?>_state" class="regular-text cuar-js-address-field">
                <?php foreach ($country_states as $code => $label) : ?>
                    <option value="<?php echo esc_attr($code); ?>" <?php selected($address['state'], $code); ?>><?php echo $label; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php endif; ?>
    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('.cuar-<?php echo $address_class; ?>').addressManager();
        $('.cuar-<?php echo $address_class; ?> .cuar-js-country-state-inputs').bindCountryStateInputs({
            select2: {
                allowClear: true,
                placeholder: "",
                width: '25em'
            }
        });
        $(".cuar-<?php echo $address_class; ?> .cuar-js-upload-control").mediaInputControl();
        <?php echo $extra_scripts; ?>
    });
</script>
