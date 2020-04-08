<?php

namespace TNO\Essiflab\Application\Controllers;

defined('ABSPATH') or die();

use TNO\EssifLab\Application\Workflows\Constructors\CoreAbstract;

class NotAdmin extends CoreAbstract
{
    public function insert_message($content)
    {
        return $content .= '<p>'.esc_attr($this->get_option(self::FIELD_MESSAGE)).'</p>';
    }
}