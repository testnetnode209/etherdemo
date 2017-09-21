<?php
/**
 * Plugin Name: Ethereum Donation Plugin
 * Plugin URI: https://etherdonation.com
 * Description: Collect more Ethereum donations online with WordPress plugin.
 * Author: alexss23
 * Version: 1.0.0
 * License: GPLv2 or later
 */

function ethereum_donation_activation() {
    $options_array = array(
        'ethereum_account' => '',
        'target' => '_blank'
    );
    if (get_option('ethereum_donation_options') !== false) {
        update_option('ethereum_donation_options', $options_array);
    } else {
        add_option('ethereum_donation_options', $options_array);
    }
}

register_activation_hook(__FILE__, 'ethereum_donation_activation');

function ethereum_donation_link_shortcode() {
    $options = get_option('ethereum_donation_options');

    return '<a href="https://etherdonation.com?to=' . $options['ethereum_account']
              . '" title="Donate Ethereum" target="' . $options['target'] . '">'
              . $options['ethereum_account'] . '</a>';
}

add_shortcode('ethereum_donation_link', 'ethereum_donation_link_shortcode');

add_filter('widget_text', 'do_shortcode');

function ethereum_donation_callback() {
}

function ethereum_donation_account_callback() {
    $options = get_option('ethereum_donation_options');
    echo "<input class='regular-text ltr' name='ethereum_donation_options[ethereum_account]' id='ethereum_account' type='text' value='{$options['ethereum_account']}'/>";
}

function ethereum_donation_target_callback() {
    $options = get_option('ethereum_donation_options');
    $target = array(
        '_blank' => 'Blank',
        '_self' => 'Self'
    );
    ?>
    <select id='target' name='ethereum_donation_options[target]'>
        <?php
        foreach ($target as $key => $label) :
            if ($key == $options['target']) {
                $selected = "selected='selected'";
            } else {
                $selected = '';
            }
            echo "<option {$selected} value='{$key}'>{$label}</option>";
        endforeach;
        ?>
    </select>
    <p class="description"><?php _e('Select "Blank" to open EtherDonation.com in a new tab or select "Self" to open EtherDonation.com in the same tab.', 'ethereum-donation') ?></p>
    <?php
}

function ethereum_donation_settings_and_fields() {

    register_setting(
            'ethereum_donation_options', 'ethereum_donation_options'
    );

    add_settings_section(
            'donate_plugin_main_section', __('Main Settings', 'ethereum-donation'), 'ethereum_donation_callback', __FILE__
    );

    add_settings_field(
            'ethereum_account', __('Ethreum account:', 'ethereum-donation'), 'ethereum_donation_account_callback', __FILE__, 'donate_plugin_main_section', array('label_for' => 'ethereum_account')
    );

    add_settings_field(
            'target', __('Open EtherDonation.com:', 'ethereum-donation'), 'ethereum_donation_target_callback', __FILE__, 'donate_plugin_main_section', array('label_for' => 'target')
    );
}

add_action('admin_init', 'ethereum_donation_settings_and_fields');

function ethereum_donation_options_init() {
    add_options_page(
            __('Ethereum Donation', 'ethereum-donation'), __('Ethereum Donation', 'ethereum-donation'), 'administrator', __FILE__, 'ethereum_donation_options_page'
    );
}

add_action('admin_menu', 'ethereum_donation_options_init');

function ethereum_donation_options_page() {
    ?>
    <div class="wrap">
        <h2><?php _e('Ethereum Donation Settings', 'ethereum-donation') ?></h2>
        <form method="post" action="options.php" enctype="multipart/form-data">
            <?php
            settings_fields('ethereum_donation_options');
            do_settings_sections(__FILE__);
            submit_button();
            ?>
        </form>
    </div>
    <?php
}
