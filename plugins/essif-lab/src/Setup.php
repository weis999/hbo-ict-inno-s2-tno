<?php

namespace LSVH\WordPress\FixContentLinks;

defined('ABSPATH') or die();

use LSVH\WordPress\FixContentLinks\Controllers\Activate;
use LSVH\WordPress\FixContentLinks\Controllers\Admin;
use LSVH\WordPress\FixContentLinks\Controllers\NotAdmin;
use LSVH\WordPress\FixContentLinks\Controllers\Deactivate;
use LSVH\WordPress\FixContentLinks\Extendables\CoreAbstract;
use LSVH\WordPress\FixContentLinks\Traits\Hooks;

class Setup extends CoreAbstract
{
    use Hooks;

    public function __construct(array $plugin_data = [])
    {
        parent::__construct($plugin_data);

        $file = $this->get_path().'index.php';

        register_activation_hook($file, [$this, 'activate']);
        register_deactivation_hook($file, [$this, 'deactivate']);

        if (is_admin()) {
            $this->define_admin_hooks();
        } else {
            $this->define_not_admin_hooks();
        }

        $this->install_hooks();
    }

    public function activate()
    {
        new Activate($this->get_plugin_data());
    }

    public function deactivate()
    {
        new Deactivate($this->get_plugin_data());
    }

    private function define_admin_hooks()
    {
        $component = new Admin($this->get_plugin_data());
        $this->add_action('admin_init', $component, 'init');
        $this->add_action('admin_menu', $component, 'menu');
        $this->add_action('admin_init', $component, 'submit_form');
        $this->add_action('admin_notices', $component, 'admin_notice', 99);
        $this->add_filter($this->get_domain() . '_save_option_' . self::FIELD_TYPE, $component, 'save_option_type');
    }

    private function define_not_admin_hooks() {
        $component = new NotAdmin($this->get_plugin_data());
        if ($this->get_option(self::FIELD_TYPE) === self::get_option_default(self::FIELD_TYPE)) {
            $this->add_action('the_content', $component, 'fix_post_content');
        }
    }
}