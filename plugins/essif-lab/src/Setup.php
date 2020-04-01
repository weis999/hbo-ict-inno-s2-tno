<?php

namespace TNO\EssifLab;

defined('ABSPATH') or die();

use TNO\EssifLab\Application\Controllers\Activate;
use TNO\EssifLab\Application\Controllers\Admin;
use TNO\EssifLab\Application\Controllers\Deactivate;
use TNO\EssifLab\Application\Controllers\NotAdmin;
use TNO\EssifLab\Application\Workflows\Constructors\CoreAbstract;
use TNO\EssifLab\Application\Workflows\Constructors\Hooks;

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
    }

    private function define_not_admin_hooks() {
        $component = new NotAdmin($this->get_plugin_data());
        $this->add_action('the_content', $component, 'insert_message');
    }
}