<?php
/**
 * Plugin Name: eSSIF-Lab-ContactForm7
 * Plugin URI: https://github.com/LSVH/hbo-ict-inno-s2-tno
 * Description: The purpose of the eSSIF-Lab is to specify, develop and validate technological and non-technological means that support people, businesses and governments to think about, design and operate their (information) processes and (electronically) conduct business transactions with one another.
 * Version: 1.0
 * Author: Duur Klop, Luuk van Houdt, Ruben Sikkens, Ufuk Altinçöp en Weis Mateen
 */

add_action( 'wpcf7_init', 'custom_add_form_tag_clock' );

function custom_add_form_tag_clock() {
    wpcf7_add_form_tag( 'clock', 'custom_clock_form_tag_handler' ); // "clock" is the type of the form-tag
}

function custom_clock_form_tag_handler( $tag ) {
    return "<input type=\"submit\" value=\"Verzenden\" class=\"wpcf7-form-control wpcf7-submit\">";
}