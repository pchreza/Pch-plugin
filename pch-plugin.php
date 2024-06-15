<?php
/**
 * Plugin Name:  pch-plugins
 * Plugin URI: https://disweb.ir/
 * Description: تغییر لوگوی قسمت ورود به سایت ، تغییر فونت پیش خوان ، غیر فعال کردن دسترسی ویرایش افزونه ها و تم ها  برای جلوگیری از هک شدن  مسدود کردن چک کردن آپدیت و دسترسی به لینک های خارجی
 * Version: 1.2
 * Author: Reza_pch	
 * Author URI: https://disweb.ir/
 */


function font_pish() {
    echo "<style>
        li#menu-posts-wp_automatic,li#menu-tools,li#menu-posts-elementor_library,.error,div#lrm_auto_trigger{
            display:none;
        }
    </style>";
}
add_action( 'admin_head', 'font_pish' );



//غر فعالکردن ویرایش پلاگین و قالب 
function disable_theme_plugin_editing() {
    if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
        define( 'DISALLOW_FILE_EDIT', true );
    }
}
add_action( 'init', 'disable_theme_plugin_editing' );


// function load_custom_login_style() {
//     $custom_logo_id = get_theme_mod('custom_logo');
//     $custom_logo_url = wp_get_attachment_image_src($custom_logo_id, 'full');

//     if ($custom_logo_url) {
//         $logo_path = $custom_logo_url[0];
//         echo "<style> #login h1 a{ background-image: url('$logo_path'); background-repeat: no-repeat; background-position: center; background-size: contain; }  </style>";
//     }
// }
// add_action('login_enqueue_scripts', 'load_custom_login_style');
 


// // نمایش محصولات ناموجود در انتهای لیست 
// add_filter( 'woocommerce_get_catalog_ordering_args', 'sort_by_stock', 9999 );
// function sort_by_stock( $args ) {
// $args['orderby'] = 'meta_value';
// $args['order'] = 'ASC';
// $args['meta_key'] = '_stock_status';
// return $args;
// }
// function custom_sort_by_stock($query) {
//     if ( !is_admin() && $query->is_main_query() ) {
//         if ( is_shop()  ) {
//             $query->set( 'orderby', 'meta_value_num' );
//             $query->set( 'order', 'ACE' );
//             $query->set( 'meta_key', '_stock_status' );
//         }
//     }
// }



/**
* Sorting out of stock WooCommerce products - Order product collections by stock status, in-stock products first.
*/
// class Vebeet_Orderby_Stock_Status {
//     public function __construct() {
//         // Check if WooCommerce is active
//         if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
//             add_filter('posts_clauses', array($this, 'order_by_stock_status'), 2000);
//         }
//     }
//     public function order_by_stock_status($posts_clauses) {
//         global $wpdb;
//         // only change query on WooCommerce loops
//         if (is_woocommerce() && (is_shop() || is_product_category() || is_product_tag())) {
//             $posts_clauses['join'] .= " INNER JOIN $wpdb->postmeta istockstatus ON ($wpdb->posts.ID = istockstatus.post_id) ";
//             $posts_clauses['orderby'] = " istockstatus.meta_value ASC, " . $posts_clauses['orderby'];
//             $posts_clauses['where'] = " AND istockstatus.meta_key = '_stock_status' AND istockstatus.meta_value <> '' " . $posts_clauses['where'];
//         }
//         return $posts_clauses;
//     }
// }
// new Vebeet_Orderby_Stock_Status;
// /**
// * END - Order product collections by stock status, instock products first.
// */



// غیرفعال کردن ویرایشگر گوتنبرگ
add_filter('use_block_editor_for_post', '__return_false', 10);

// فعال کردن ویرایشگر کلاسیک
function enable_classic_editor_automatically($post_type) {
    $post_types = array('post', 'page'); // نوع پست‌هایی که می‌خواهید از ویرایشگر کلاسیک استفاده شود
    if (in_array($post_type, $post_types)) {
        $user = wp_get_current_user();
        if (!empty($user->ID) && !empty($user->roles) && is_array($user->roles) && in_array('administrator', $user->roles)) {
            return false; // اجازه استفاده از ویرایشگر کلاسیک برای مدیران
        }
    }
    return true;
}
add_filter('gutenberg_can_edit_post_type', 'enable_classic_editor_automatically');


// add_filter( 'the_title', 'shorten_woo_product_title', 10, 2 );
// function shorten_woo_product_title( $title, $id ) {
// if (get_post_type( $id ) === 'product' ) {
//   return wp_trim_words( $title, 10, '...' ); // شما میتوانید عدد دلخواه خود را به جای 4 جایگذاری کنید.
// } else {
// return $title;
// }
// }







 
//  function block_external_links() {
//     define('WP_HTTP_BLOCK_EXTERNAL', true);
//     define('WP_ACCESSIBLE_HOSTS', '*.zarinpal.com, *.persianscript.ir, *.woosupport.ir, *.ippanel.com, ippanel.com, *.torob.com , *.extractor.torob.com');


// //*.wordpress.com, *.wordpress.org, 
// }
// add_action('init', 'block_external_links');
# تغییر برنامه بر اساس درخواست جدید. 



// // فانکشن برای به روزرسانی محصولات بدون قیمت به صورت خودکار
// function update_products_stock_status() {
//     // دریافت تمام محصولات بدون قیمت
//     $args = array(
//         'post_type'      => 'product',
//         'posts_per_page' => -1,
//         'meta_query'     => array(
//             array(
//                 'key'     => '_price',
//                 'value'   => '',
//                 'compare' => '=',
//             ),
//         ),
//     );

//     $products = new WP_Query($args);

//     if ($products->have_posts()) {
//         while ($products->have_posts()) {
//             $products->the_post();
            
//             // به روزرسانی موجودی به صفر
//             update_post_meta(get_the_ID(), '_stock', 0);
//             update_post_meta(get_the_ID(), '_stock_status', 'outofstock');
//         }
//         wp_reset_postdata();
//     }
// }

// // اجرای فانکشن به صورت دوره‌ای یا در هنگام فعال‌سازی افزونه
// add_action('init', 'update_products_stock_status');

// افزودن یک هوک برای اجرا قبل از ارسال درخواست
// add_action('http_request_args', 'block_requests_to_specific_domains', 10, 2);

// function block_requests_to_specific_domains($args, $url) {
//     $blocked_domains = array(
//         'intellywp.com',
//         'rankmath.com',
//         'api.crocoblock.com',
//          '*.rankmath.com',
//                  '*.crocoblock.com',
//                   'perfmatters.io',
//                   '*.optimizingmatters.com',
//                     'feeds.feedburner.com',
//                      '*.feedburner.com',
//     );

//     // بررسی آدرس درخواست
//     foreach ($blocked_domains as $domain) {
//         if (strpos($url, $domain) !== false) {
//             // اگر آدرس دامنه مسدود شده باشد، ارسال درخواست نکن
//             $args['blocking'] = false;
//             break;
//         }
//     }

//     return $args;
// }
// //remove error zlib output compression
// remove_action( 'shutdown', 'wp_ob_end_flush_all', 1 );
// add_action( 'shutdown', function() {
//   while ( @ob_end_flush() );
// } );



// // /////
// add_shortcode('my_custom_orders','my_custom_orders_shortcode');
// function my_custom_orders_shortcode(){
//     ob_start();
//     $order_count = 4; // تعداد سفارش‌های مورد نظر را تعیین کنید
//     wc_get_template('myaccount/my-orders.php', array('order_count' => $order_count));
//     return ob_get_clean();
// }



///shokrino
defined( 'ABSPATH' ) || exit;

$plugin_data = get_file_data(__FILE__, array('Version' => 'Version'), false);
$plugin_data_name = get_file_data(__FILE__, array('Plugin Name' => 'Plugin Name'), false);
$plugin_version = $plugin_data['Version'];
$plugin_name = $plugin_data_name['Plugin Name'];
define('ADMNL_NAME', $plugin_name);
define('ADMNL_VERSION', $plugin_version);
define('ADMNL_PATH' , WP_CONTENT_DIR.'/plugins/pch-plugin');
define('ADMNL_URL' , plugin_dir_url( __DIR__ ).'pch-plugin');
define('ADMNL_INC' , ADMNL_PATH.'/inc');
define('ADMNL_LIB' , ADMNL_PATH.'/inc/lib');
define('ADMNL_TPL' , ADMNL_PATH.'/inc/templates');
define('ADMNL_ASSETS' , ADMNL_URL.'/assets');
define('ADMNL_FONTS' , ADMNL_ASSETS.'/fonts');
define('ADMNL_TEXTDOMAIN' , 'admino-l');

/////////////////////////////////////////////////////////////////////////////////////////////////

require_once 'inc/sdo-framework/sdo-framework.php';
require_once 'inc/sdo-config.php';

function adminol_load_textdomain() {
    load_plugin_textdomain( 'admino-l', false, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'init', 'adminol_load_textdomain' );

function adminol_font_dashboard() {
    wp_enqueue_style('general_admin_panel_font', ADMNL_ASSETS . '/css/admin-font-general.css');
    wp_enqueue_style('custom_admin_panel_font', ADMNL_ASSETS . '/css/font-shabnam.css');
}

function adminol_adminbar_css() {
    if (strpos(implode(' ', get_body_class()), 'admin-bar') !== false) {
        wp_enqueue_style('custom_admin_panel_font', ADMNL_ASSETS . '/css/font-shabnam.css', array('admin-bar'));
    }
}

function adminol_style_dashboard() {
    wp_enqueue_style('custom_admin_panel_style', ADMNL_ASSETS . '/css/style1.css');
}

function adminol_style_dashboard_head() {
    $admin_style1_main_color = admnl_options('admin_color');
    ?>
    <style>
    li#toplevel_page_admnl_option_settings {
    display: none;
}
        :root  {
            --var-pch-plugin-color-main: <?php echo $admin_style1_main_color; ?> !important;
        }
    </style>
    <?php
}

function adminol_style_login() {
    if (admnl_options('login_style') == "off") { ?>
        <style>
            body.login #backtoblog {
                display: none !important;
            }
        </style>
    <?php }
    $logo_option_admino = admnl_options('admin_logo');
    if (!empty($logo_option_admino)) { ?>
        <style>
            #login h1 a, .login h1 a {
                background-image: url(<?php echo $logo_option_admino; ?>);
                background-repeat: no-repeat;
                background-size: contain;
                width: 100%;
                box-shadow: none !important;
            }
        </style>
    <?php } else { ?>
        <style>
            #login > h1 {
                display: none !important;
            }
        </style>
    <?php }
    $disable_signup_lostpassword = admnl_options('disable_signup_lostpassword') !== "off" ? true : false;
    $disable_backtoblog = admnl_options('disable_backtoblog') !== "off" ? true : false;
    $background_login_picture = admnl_options('admin_bg');
    wp_enqueue_style('login-page-general-styles', ADMNL_ASSETS . '/css/login-page-general.css');
    ?>
    <style>
        #login {
            margin: auto !important;
        }
    </style>
    <?php if (!$disable_signup_lostpassword) { ?>
        <style>
            body.login #nav {
                display: none !important;
            }
        </style>
    <?php } if (!$disable_backtoblog) { ?>
        <style>
            body.login #backtoblog {
                display: none !important;
            }
        </style>
    <?php }
    if (!empty($login_other_color)) { ?>
        <style>
            .wp-core-ui .button-secondary .dashicons:before,
            #wp-submit:hover, .wp-core-ui .button-primary:focus, .wp-core-ui .button-primary.active,
            .wp-core-ui .button-primary.active:focus, .wp-core-ui .button-primary:active {
                color: <?php echo $login_other_color;?> !important;
                background: white !important;
            }
            .wp-core-ui .button-primary {
                background: <?php echo $login_other_color;?> !important;
            }
            .login #login_error, .login .message, .login .success {
                border-color: <?php echo $login_other_color;?> !important;
            }
            #user_login:focus, #user_email:focus, #user_pass:focus, .input:focus, .password-input:focus, .login input[type="text"]:focus, input:focus {
                border: 2px solid <?php echo $login_other_color;?> !important;
            }
        </style>
    <?php } if (!empty($login_box_bg_color)) { ?>
        <style>
            #login, #loginform {
                background: <?php echo $login_box_bg_color;?> !important;
            }
        </style>
    <?php } if (!empty($login_text_color)) { ?>
        <style>
            .message-wp-login-admino, .login form, .login #backtoblog, .login #nav, .login #login_error, .login .message, .login .success, #nav a, #backtoblog a {
                color: <?php echo $login_text_color;?> !important;
            }
        </style>
    <?php } if (!empty($background_login_picture)) { ?>
        <style>
            body.login {
                background-image: url(<?php echo $background_login_picture;?>) !important;
            }
        </style>
    <?php }
}

function admnl_login_headerurl() {
    return home_url();
}

function admnl_login_headertext() {
    return '';
}

function admnl_login_body_class($classes) {
    $classes[] = 'admino-login-page-plugin';
    return $classes;
}

function admnl_login_display_language_dropdown() {
    if (admnl_options('disable_wp_language_switcher') !== "off") {
        return true;
    } else {
        return false;
    }
}

function admnl_enable_login_autofocus() {
    return false;
}

if (admnl_options('persian_font') !== "off") {
    add_action('wp_enqueue_scripts', 'adminol_adminbar_css');
    add_action('admin_enqueue_scripts', 'adminol_font_dashboard');
    add_action('login_enqueue_scripts', 'adminol_font_dashboard');
}

if (admnl_options('admin_style') !== "off") {
    add_action('admin_enqueue_scripts', 'adminol_style_dashboard');
    add_action('admin_head', 'adminol_style_dashboard_head');
}

if (admnl_options('login_style') !== "off") {
    add_action('login_enqueue_scripts', 'adminol_style_login');
    add_action('login_head', 'adminol_style_dashboard_head');
    add_filter('login_headerurl', 'admnl_login_headerurl');
    add_filter('login_headertext', 'admnl_login_headertext');
    add_filter('login_body_class', 'admnl_login_body_class');
    add_filter('login_display_language_dropdown', 'admnl_login_display_language_dropdown');
    add_filter('enable_login_autofocus', 'admnl_enable_login_autofocus');
}




