<?php

namespace LSVH\WordPress\FixContentLinks\Controllers;

defined('ABSPATH') or die();

use LSVH\WordPress\FixContentLinks\Extendables\CoreAbstract;
use LSVH\WordPress\FixContentLinks\Models\FieldManager;

class Admin extends CoreAbstract
{
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

            wp_redirect(add_query_arg('settings-updated', 'true',  wp_get_referer()));
            exit;
        }
    }

    public function admin_notice() {
        $notice = $this->get_admin_notice(get_current_user_id());
        if (!empty($notice) && is_array($notice)) {
            $status = array_key_exists('status', $notice) ? $notice['status'] : 'success';
            $message = array_key_exists('message', $notice) ? $notice['message'] : '';
            print '<div class="notice notice-'.$status.' is-dismissible">'.$message.'</div>';
        }
    }

    public function save_option_type($value) {
        if (!empty($value) && $value === 'permanently') {
            $status = 'warning';
            $message = '<p>' . __('No found to update.', $this->get_domain()) . '</p>';

            $query = new \WP_Query([
                'post_type' => 'any',
                'posts_per_page' => -1,
            ]);

            $replacements = get_transient($this->get_domain() . '_' . get_current_user_id() . '_replacements');

            if ($query->have_posts()) {
                $message = '';
                foreach ($query->get_posts() as $post) {
                    if (preg_match('/src(?:\s|)=(?:\s|)"[^"]*"/mi', $post->post_content)) {
                        $post->post_content = $this->notAdmin->fix_post_content($post->post_content);
                        $count = is_array($replacements) && array_key_exists($post->ID, $replacements) ? $replacements[$post->ID] : 0;
                        $result = sprintf(__(wp_update_post($post) === $post->ID ?
                            'Successfully updated %s links' : 'Failed to update', $this->get_domain()), $count);

                        $text = $post->post_title . ' (#' . $post->ID . ')';
                        $link = get_permalink($post->ID);
                        $anchor_text = __('view page', $this->get_domain());
                        $anchor = '<a href="' . $link . '" target="_blank">' . $anchor_text . '</a>';
                        $message .= '<li>' . $result . ' ' . __('at', $this->get_domain()) . ' ' . $text . ', ' . $anchor . '.</li>';
                    }
                }

                if (!empty($message)) {
                    $status = 'success';
                    $message = '<p>'.__('The following posts were updated:', $this->get_domain()).'</p>'
                        .'<ul class="ul-disc">'.$message.'</ul>';
                }
            }

            $this->set_admin_notice(get_current_user_id(), $message, $status);

            return $this->get_option_default(self::FIELD_TYPE);
        } else {
            return $value;
        }
    }

    private function load_fields() {
        $domain = $this->get_domain();

        $this->fieldManager->add([
            'id' => self::FIELD_TYPE,
            'type' => 'radio',
            'name' => $this->get_domain().'['.self::FIELD_TYPE.']',
            'label' => __('Select how to fix the links', $domain),
            'value' => $this->get_option(self::FIELD_TYPE),
            'options' => [
                $this->get_option_default(self::FIELD_TYPE) =>
                    __('Convert the links to temporary.', $domain),
                'permanently' => __('Modify the links permanently in the database.', $domain),
                'disable' => __('Disable all functionality of this plugin.', $domain),
            ],
        ]);

        $this->fieldManager->add([
            'id' => self::FIELD_PATH,
            'type' => 'text',
            'name' => $this->get_domain().'['.self::FIELD_PATH.']',
            'label' => __('What path are the links prefixed with?', $domain),
            'value' => $this->get_option(self::FIELD_PATH),
        ]);

        $this->fieldManager->add([
            'id' => self::FIELD_EXCLUDE,
            'type' => 'textarea',
            'name' => $this->get_domain().'['.self::FIELD_EXCLUDE.']',
            'label' => __('Exclude fixing links when the link contains text from one of the lines below.', $domain),
            'value' => $this->get_option(self::FIELD_EXCLUDE),
        ]);
    }

    private function set_admin_notice($id, $message, $status = 'success') {
        set_transient($this->get_domain() . '_' . $id . '_admin_notice', [
            'message' => $message,
            'status' => $status
        ], 30);
    }

    private function get_admin_notice($id) {
        $transient = get_transient( $this->get_domain() . '_' . $id . '_admin_notice');
        if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] && $transient ) {
            delete_transient( $this->get_domain() . '_' . $id . '_admin_notice');
        }
        return $transient;
    }
}