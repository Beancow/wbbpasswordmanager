<?php

/**
 * Lapa Theme Function
 *
 */
function remove_angle_brackets($message) {
    return preg_replace('/<(https?):\/\/(.*)>/', '$1://$2', $message);
}

add_filter('retrieve_password_message', 'remove_angle_brackets');
add_action( 'after_setup_theme', 'lapa_child_theme_setup' );
add_action( 'wp_enqueue_scripts', 'lapa_child_enqueue_styles', 20);

if( !function_exists('lapa_child_enqueue_styles') ) {
    function lapa_child_enqueue_styles() {
        wp_enqueue_style( 'lapa-child-style',
            get_stylesheet_directory_uri() . '/style.css',
            array( 'lapa-theme' ),
            wp_get_theme()->get('Version')
        );
    }
}

if( !function_exists('lapa_child_theme_setup') ) {
    function lapa_child_theme_setup() {
        load_child_theme_textdomain( 'lapa-child', get_stylesheet_directory() . '/languages' );
    }
}

add_action( 'wp_enqueue_scripts', 'lapa_child_enqueue', 99 );

function lapa_child_enqueue() {
    wp_enqueue_script( 'lapa-child-scripts', get_stylesheet_directory_uri() . '/js/scripts.js' );
}

/** Registers a widget area. */
function dynamika_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'Lesson Sidebar', 'lapa-child' ),
        'id'            => 'lesson-left-sidebar',
        'description'   => __( 'Add widgets here to appear in your sidebar.', 'lapa-child' ),
        'before_widget' => '',
        'after_widget'  => '',
    ) );
}
add_action( 'widgets_init', 'dynamika_widgets_init' );

/* Register Menus */
function register_my_menus() {
    register_nav_menus(
        array(
            'wolf_footer' => __( 'Footer Wolf Menu' ),
        )
    );
}
add_action( 'init', 'register_my_menus' );

/* Allow redirection
even if my theme starts to send output to the browser
================================================== */
add_action( 'init', 'do_output_buffer' );
function do_output_buffer() {
    ob_start();
}

/* Redirect Course */
function redirect_course($url) {
    header("location: " . $url);
}

/* Redirect after login */
add_filter('woocommerce_login_redirect', 'wc_login_redirect');
function wc_login_redirect( $redirect_to ) {
    $redirect_to = '/kursy/masterwolf/';
    return $redirect_to;
}

/* Change Links in Menu Footer */
function add_login_logout_register_menu( $items, $args ) {

    if (is_user_logged_in() && $args->theme_location == 'wolf_footer') {
        $items .= '<li><a href="' . wp_logout_url( home_url() ) . '">Wyloguj</a></li>';
        $items .= '<li><a href="/kursy/masterwolf/">Twój kurs</a></li>';
        $items .= '<li><a href="/moje-konto/">Moje Konto</a></li>';
        $items .= '<li><a href="/regulamin/">Regulamin</a></li>';

    }
    elseif (!is_user_logged_in() && $args->theme_location == 'wolf_footer') {
        $items .= '<li><a href="/moje-konto/"> Zaloguj się</a></li>';
        $items .= '<li><a href="/moje-konto/">Twój kurs</a></li>';
        $items .= '<li><a href="/">Oferta</a></li>';
        $items .= '<li><a href="/regulamin/">Regulamin</a></li>';
    }
    return $items;
}
add_filter( 'wp_nav_menu_items', 'add_login_logout_register_menu', 199, 2 );

/* Add ColorBox to Code */
function colorbox_scripts() {
    wp_enqueue_script( 'lapa-child-colorboxcss', get_stylesheet_directory_uri() . '/js/jquery.colorbox-min.js', array(), '1.0.0', true );
    wp_enqueue_style( 'lapa-child-colorboxjs', get_stylesheet_directory_uri() . '/css/colorbox.css' );
}
add_action( 'wp_enqueue_scripts', 'colorbox_scripts' );

/* Remove JetPack Open Graph Fb */
add_filter( 'jetpack_enable_open_graph', '__return_false' );

/* WooCommerce: The Code Below Removes Checkout Fields */
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

function custom_override_checkout_fields( $fields ) {
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_address_1']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_city']);
    unset($fields['billing']['billing_postcode']);
    unset($fields['billing']['billing_country']);
    unset($fields['billing']['billing_state']);
    return $fields;
}




// Add meta
// Check and register coupon code in a custom session variable
add_action('woocommerce_checkout_process', 'cw_custom_process_checkbox');
function cw_custom_process_checkbox() {

    global $woocommerce;
    if (!$_POST['custom_checkbox2'])
        wc_add_notice( __( 'Musisz potwierdzić, że zapoznałeś się z klauzulą o przetwarzaniu danych.' ), 'error' );

}
/* 18 lat The Wolf */
add_action('woocommerce_review_order_before_submit', 'cw_custom_checkbox_fields', 50);
function cw_custom_checkbox_fields( $checkout ) {
    $product_id = 4300;
    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
        // If Cart has category "download", set $cat_in_cart to true
        if ($cart_item['product_id']==$product_id) {
            $cat_in_cart = true;
            continue;
        }
    }
    if(is_checkout() && $cat_in_cart) {
        woocommerce_form_field( 'custom_checkbox', array(
            'type'          => 'checkbox',
            'label'         => __('Oświadczam, że mam ukończone 18 lat.'),
            'required'  => true,
        ));

    }
    woocommerce_form_field( 'custom_checkbox2', array(
        'type'          => 'checkbox',
        'label'         => __('Zgadzam się z <a target="_blank" href="/klauzula-informacyjna/">Klauzulą o przetwarzaniu danych osobwych.</a>'),
        'required'  => true,
    ));
}
add_action('woocommerce_review_order_before_submit', 'rodo_checkbox_fields', 20);
function rodo_checkbox_fields( $checkout ) {


    woocommerce_form_field( 'pp_checkbox', array(
        'type'          => 'checkbox',
        'label'         => __('Zgadzam się z <a target="_blank" href="/regulamin/">polityką prywatności.</a>'),
        'required'  => true,
    ));


}

/* Wył. automatycznych aktualizacji pluginów */
add_filter( 'auto_update_plugin', '__return_false' );

/* Wył. automatycznych aktualizacji szablonu */
add_filter( 'auto_update_theme', '__return_false' );