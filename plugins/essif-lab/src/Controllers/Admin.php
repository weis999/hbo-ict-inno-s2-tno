<?php

namespace TNO\EssifLab\Controllers;

defined('ABSPATH') or die();

use TNO\EssifLab\Extendables\CoreAbstract;
use TNO\EssifLab\Models\FieldManager;

class Admin extends CoreAbstract
{
    const SETTINGS_UPDATED = 'settings-updated';
    const STATUS = 'status';
    const MESSAGE = 'message';
    const ADMIN_NOTICE = '_admin_notice';
    private $fieldManager;
    private $notAdmin;

    public function __construct($plugin_data = [])
    {
        parent::__construct($plugin_data);

        $this->notAdmin = new NotAdmin($plugin_data);
        $this->fieldManager = FieldManager::getInstance();
    }

    public function init()
    {
        $domain = $this->get_domain();

        add_settings_section($domain, $this->get_name(), function() use ($domain) {
            echo '<p>'.__('Customize the configuration of the plugin below.', $domain).'</p>';
        }, $this->get_plugin_parent_page());

        $this->load_fields();

        $fields = $this->fieldManager->all();
        if (!empty($fields)) {
            foreach ($fields as $field) {
                add_settings_field($field->get_id(), $field->get_label(), [$field, 'render'], $this->get_plugin_parent_page(), $domain);
            }
        }
    }

    public function menu()
    {
        add_options_page(
            $this->get_name(),
            $this->get_name(),
            'administrator',
            $this->get_domain(),
            [$this, 'page']
        );
    }

    public function page()
    {
        $this->submit_form();
        ?>
        <div class="wrap">
            <form method="post" action="">
                <?php
                settings_fields($this->get_plugin_parent_page());
                do_settings_sections($this->get_plugin_parent_page());
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function submit_form() {
        if (
            !empty($_POST) &&
            is_array($_POST) &&
            array_key_exists('_wpnonce', $_POST) &&
            array_key_exists($this->get_domain(), $_POST) &&
            wp_verify_nonce($_POST['_wpnonce'], $this->get_plugin_parent_page() . '-options')
        ) {
            $options = $_POST[$this->get_domain()];

            // Sanitize submitted options
            array_walk($options, function(&$v, $k) {
                $v = apply_filters($this->get_domain() . '_save_option_' . $k, $this->fieldManager->get($k)->sanitize($v));
            });

            // Validate submitted options
            $options = array_filter($options, function($v, $k) {
                return $this->fieldManager->get($k)->validate($v);
            }, ARRAY_FILTER_USE_BOTH);

            $this->update_options($options);

            if (!empty($options) && empty($this->get_admin_notice(get_current_user_id()))) {
                $this->set_admin_notice(get_current_user_id(), '<p>' .__('Settings saved.') . '</p>');
            }

            wp_redirect(add_query_arg(self::SETTINGS_UPDATED, 'true',  wp_get_referer()));
            exit;
        }
    }

    public function admin_notice() {
        $notice = $this->get_admin_notice(get_current_user_id());
        if (!empty($notice) && is_array($notice)) {
            $status = array_key_exists(self::STATUS, $notice) ? $notice[self::STATUS] : 'success';
            $message = array_key_exists(self::MESSAGE, $notice) ? $notice[self::MESSAGE] : '';
            print '<div class="notice notice-'.$status.' is-dismissible">'.$message.'</div>';
        }
    }

    private function load_fields() {
        $domain = $this->get_domain();

        $this->fieldManager->add([
            'id' => self::FIELD_MESSAGE,
            'type' => 'textarea',
            'name' => $this->get_domain().'['.self::FIELD_MESSAGE.']',
            'label' => __('Define message to be displayed.', $domain),
            'value' => $this->get_option(self::FIELD_MESSAGE),
        ]);
    }

    private function set_admin_notice($id, $message, $status = 'success') {
        set_transient($this->get_domain() . '_' . $id . self::ADMIN_NOTICE, [
            self::MESSAGE => $message,
            self::STATUS => $status
        ], 30);
    }

    private function get_admin_notice($id) {
        $transient = get_transient( $this->get_domain() . '_' . $id . self::ADMIN_NOTICE);
        if ( isset( $_GET[self::SETTINGS_UPDATED] ) && $_GET[self::SETTINGS_UPDATED] && $transient ) {
            delete_transient( $this->get_domain() . '_' . $id . self::ADMIN_NOTICE);
        }
        return $transient;
    }
}