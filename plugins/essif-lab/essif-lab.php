<?php
/**
 * Plugin Name: eSSIF-Lab
 * Plugin URI: https://github.com/LSVH/hbo-ict-inno-s2-tno
 * Description: The purpose of the eSSIF-Lab is to specify, develop and validate technological and non-technological means that support people, businesses and governments to think about, design and operate their (information) processes and (electronically) conduct business transactions with one another.
 * Version: 1.0
 * Author: Duur Klop, Luuk van Houdt, Ruben Sikkens, Ufuk Altinçöp en Weis Mateen
 */

include_once dirname( __FILE__ ) . '/options.php';

add_action( 'the_content', 'my_thank_you_text' );

function my_thank_you_text ( $content ) {
    $options = get_option( 'wporg_options' );
    return $content .= '<p>'.esc_attr($options[ 'wporg_field_home_text']).'</p>';
}