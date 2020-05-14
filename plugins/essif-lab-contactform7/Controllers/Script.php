<?php
function wpb_adding_scripts()
{
    wp_register_script('essif_lab_script', plugins_url('../js/essif-lab-script.js', __FILE__), array('jquery'), '1.1', true);
    wp_enqueue_script('essif_lab_script');
}

add_action('wp_enqueue_scripts', 'wpb_adding_scripts');