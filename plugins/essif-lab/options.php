<?php
/**
 * @internal never define functions inside callbacks.
 * these functions could be run multiple times; this would result in a fatal error.
 */
const WPORG = 'wporg';
const WPORG_OPTIONS = 'wporg_options';
const WPORG_SECTION_DEVELOPERS = 'wporg_section_developers';
const WPORG_CUSTOM_DATA = 'wporg_custom_data';
const LABEL_FOR = 'label_for';

/**
 * custom option and settings
 */
function wporg_settings_init() {
    // register a new setting for "wporg" page
    register_setting( WPORG, WPORG_OPTIONS);

    // register a new section in the "wporg" page
    add_settings_section(
        WPORG_SECTION_DEVELOPERS,
        __( 'The Matrix has you.', WPORG),
        'wporg_section_developers_cb',
        WPORG
    );

    // register a new field in the "wporg_section_developers" section, inside the "wporg" page
    add_settings_field(
        'wporg_field_pill', // as of WP 4.6 this value is used only internally
        // use $args' label_for to populate the id inside the callback
        __( 'Pill', WPORG),
        'wporg_field_pill_cb',
        WPORG,
        WPORG_SECTION_DEVELOPERS,
        [
            LABEL_FOR => 'wporg_field_pill',
            'class' => 'wporg_row',
            WPORG_CUSTOM_DATA => 'custom',
        ]
    );

    // register a second new field in the "wporg_section_developers" section, inside the "wporg" page
    add_settings_field(
        'wporg_field_home_text', // as of WP 4.6 this value is used only internally
        // use $args' label_for to populate the id inside the callback
        __( 'Home text', WPORG),
        'wporg_field_home_text_cb',
        WPORG,
        WPORG_SECTION_DEVELOPERS,
        [
            LABEL_FOR => 'wporg_field_home_text',
            'class' => 'wporg_row',
            WPORG_CUSTOM_DATA => 'custom',
        ]
    );
}

/**
 * register our wporg_settings_init to the admin_init action hook
 */
add_action( 'admin_init', 'wporg_settings_init' );

/**
 * custom option and settings:
 * callback functions
 */

// developers section cb

// section callbacks can accept an $args parameter, which is an array.
// $args have the following keys defined: title, id, callback.
// the values are defined at the add_settings_section() function.
function wporg_section_developers_cb( $args ) {
    ?>
    <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Follow the white rabbit.', WPORG); ?></p>
    <?php
}

// pill field cb

// field callbacks can accept an $args parameter, which is an array.
// $args is defined at the add_settings_field() function.
// wordpress has magic interaction with the following keys: label_for, class.
// the "label_for" key value is used for the "for" attribute of the <label>.
// the "class" key value is used for the "class" attribute of the <tr> containing the field.
// you can add custom key value pairs to be used inside your callbacks.
function wporg_field_pill_cb( $args ) {
    // get the value of the setting we've registered with register_setting()
    $options = get_option(WPORG_OPTIONS);
    // output the field
    ?>
    <select id="<?php echo esc_attr( $args[LABEL_FOR] ); ?>"
            data-custom="<?php echo esc_attr( $args[WPORG_CUSTOM_DATA] ); ?>"
            name="wporg_options[<?php echo esc_attr( $args[LABEL_FOR] ); ?>]"
    >
        <option value="red" <?php echo isset( $options[ $args[LABEL_FOR] ] ) ? ( selected( $options[ $args[LABEL_FOR] ], 'red', false ) ) : ( '' ); ?>>
            <?php esc_html_e( 'red pill', WPORG); ?>
        </option>
        <option value="blue" <?php echo isset( $options[ $args[LABEL_FOR] ] ) ? ( selected( $options[ $args[LABEL_FOR] ], 'blue', false ) ) : ( '' ); ?>>
            <?php esc_html_e( 'blue pill', WPORG); ?>
        </option>
    </select>
    <p class="description">
        <?php esc_html_e( 'You take the blue pill and the story ends. You wake in your bed and you believe whatever you want to believe.', WPORG); ?>
    </p>
    <p class="description">
        <?php esc_html_e( 'You take the red pill and you stay in Wonderland and I show you how deep the rabbit-hole goes.', WPORG); ?>
    </p>
    <?php
    if($options[ $args[LABEL_FOR]] == 'red'){
        esc_html_e( 'Red', WPORG);
    }
    elseif($options[ $args[LABEL_FOR]] == 'blue'){
        esc_html_e( 'Blue', WPORG);
    }
    ?>
    <?php
}

// home text field cb
function wporg_field_home_text_cb( $args ) {
    // get the value of the setting we've registered with register_setting()
    $options = get_option(WPORG_OPTIONS);
    // output the field
    ?>
    <textarea id="<?php echo esc_attr( $args[LABEL_FOR] ); ?>"
              data-custom="<?php echo esc_attr( $args[WPORG_CUSTOM_DATA] ); ?>"
              name="wporg_options[<?php echo esc_attr( $args[LABEL_FOR] ); ?>]"
              style="min-width: 25%; max-width: 100%"
    ><?php echo esc_attr($options[ $args[LABEL_FOR]]); ?></textarea>
    <br>
    <?php
    esc_html_e($options[ $args[LABEL_FOR]], WPORG);
    ?>
    <?php
}

/**
 * top level menu
 */
function wporg_options_page() {
    // add top level menu page
    add_menu_page(
        'WPOrg',
        'WPOrg Options',
        'manage_options',
        WPORG,
        'wporg_options_page_html'
    );
}

/**
 * register our wporg_options_page to the admin_menu action hook
 */
add_action( 'admin_menu', 'wporg_options_page' );

/**
 * top level menu:
 * callback functions
 */
function wporg_options_page_html() {
    // check user capabilities
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    // add error/update messages

    // check if the user have submitted the settings
    // wordpress will add the "settings-updated" $_GET parameter to the url
    if ( isset( $_GET['settings-updated'] ) ) {
        // add settings saved message with the class of "updated"
        add_settings_error( 'wporg_messages', 'wporg_message', __( 'Settings Saved', WPORG), 'updated' );
    }

    // show error/update messages
    settings_errors( 'wporg_messages' );
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="options.php" method="post">
            <?php
            // output security fields for the registered setting "wporg"
            settings_fields(WPORG);
            // output setting sections and their fields
            // (sections are registered for "wporg", each field is registered to a specific section)
            do_settings_sections(WPORG);
            // output save settings button
            submit_button( 'Save Settings' );
            ?>
        </form>
    </div>
    <?php
}