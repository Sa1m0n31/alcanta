<?php
/**
 * Storefront engine room
 *
 * @package storefront
 */

/**
 * Assign the Storefront version to a var
 */
$theme              = wp_get_theme( 'storefront' );
$storefront_version = $theme['Version'];

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 980; /* pixels */
}

$storefront = (object) array(
	'version'    => $storefront_version,

	/**
	 * Initialize all the things.
	 */
	'main'       => require 'inc/class-storefront.php',
	'customizer' => require 'inc/customizer/class-storefront-customizer.php',
);

require 'inc/storefront-functions.php';
require 'inc/storefront-template-hooks.php';
require 'inc/storefront-template-functions.php';
require 'inc/wordpress-shims.php';

if ( class_exists( 'Jetpack' ) ) {
	$storefront->jetpack = require 'inc/jetpack/class-storefront-jetpack.php';
}

if ( storefront_is_woocommerce_activated() ) {
	$storefront->woocommerce            = require 'inc/woocommerce/class-storefront-woocommerce.php';
	$storefront->woocommerce_customizer = require 'inc/woocommerce/class-storefront-woocommerce-customizer.php';

	require 'inc/woocommerce/class-storefront-woocommerce-adjacent-products.php';

	require 'inc/woocommerce/storefront-woocommerce-template-hooks.php';
	require 'inc/woocommerce/storefront-woocommerce-template-functions.php';
	require 'inc/woocommerce/storefront-woocommerce-functions.php';
}

if ( is_admin() ) {
	$storefront->admin = require 'inc/admin/class-storefront-admin.php';

	require 'inc/admin/class-storefront-plugin-install.php';
}

/**
 * NUX
 * Only load if wp version is 4.7.3 or above because of this issue;
 * https://core.trac.wordpress.org/ticket/39610?cversion=1&cnum_hist=2
 */
if ( version_compare( get_bloginfo( 'version' ), '4.7.3', '>=' ) && ( is_admin() || is_customize_preview() ) ) {
	require 'inc/nux/class-storefront-nux-admin.php';
	require 'inc/nux/class-storefront-nux-guided-tour.php';
	require 'inc/nux/class-storefront-nux-starter-content.php';
}

/* CHILD THEME - ALCANTA */

/* Alcanta enqueue scripts */
function alcanta_enqueue_script() {
    wp_enqueue_script('main-js', get_stylesheet_directory_uri() . '/assets/js/alcanta.js', array('embla', 'jquery'), 1.2, true);

    wp_enqueue_script('embla', '//unpkg.com/embla-carousel/embla-carousel.umd.js', array(), 1.0, true);
    wp_enqueue_script('jquery', get_stylesheet_directory_uri() . '/assets/js/jquery.js', array(), 1.0, true);

    wp_enqueue_style( 'bootstrap', '//stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css');

    wp_enqueue_style('bootstrap-style', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css', null, 1.0, true);
    wp_enqueue_style('bootstrap-style');
    wp_register_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js', null, 1.0, true);
    wp_enqueue_script('bootstrap');
    wp_register_script('bootstrap-bundle', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js', null, 1.0, true);
    wp_enqueue_script('bootstrap-bundle');
}

add_action('wp_enqueue_scripts', 'alcanta_enqueue_script');

/* Header */
function remove_header_actions() {
    remove_all_actions('storefront_before_header');
    remove_all_actions('storefront_before_content');
    remove_all_actions('storefront_content_top');
}
add_action('wp_head', 'remove_header_actions');

function alcanta_content_top() {
    ?>

    <!-- TOP BAR -->
    <?php
        $topBarText = get_field('top_bar', 403);
        if($topBarText) {
            ?>

            <aside class="topBar">
                <h4 class="topBar__header">
                    <?php echo $topBarText; ?>
                </h4>
            </aside>

                <?php
        }
    ?>

    <!-- HEADER -->
    <header class="mobileHeader d-flex d-md-none align-items-center">
        <a class="mobileHeader__logoWrapper" href="<?php echo home_url(); ?>">
            <img class="mobileHeader__logo" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/alcanta-logo.png'; ?>" alt="alcanta-logo" />
        </a>

        <div class="mobileHeader__right d-flex">
            <a class="mobileHeader__btn" href="<?php echo wc_get_cart_url(); ?>">
                <img class="mobileHeader__btn__img" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/cart.svg'; ?>" alt="koszyk" />
                <span class="mobileHeader__btn__text">Koszyk</span>
                <span class="mobileHeader__cartCount">
                    <?php echo WC()->cart->get_cart_contents_count(); ?>
                </span>
            </a>
            <button class="mobileHeader__btn" onclick="openMobileMenu()">
                <img class="mobileHeader__btn__img" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/menu.svg'; ?>" alt="menu" />
                <span class="mobileHeader__btn__text">Menu</span>
            </button>
        </div>
    </header>

    <menu class="mobileMenu">
        <div class="mobileMenu__top d-flex justify-content-between">
            <img class="mobileMenu__logo" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/alcanta-logo.png'; ?>" alt="alcanta-logo" />

            <button class="mobileMenu__closeBtn" onclick="closeMobileMenu()">
                <img class="mobileMenu__closeBtn__img" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/close.png'; ?>" alt="wyjdz" />
            </button>
        </div>

            <?php
            $items = wp_get_nav_menu_items( 'Menu 1' );
            $i = 0;
            if( $items ) {
                ?>
        <ul class="mobileMenu__menu">

        <?php
                foreach( $items as $index => $item ) {
                    if( $item->menu_item_parent == 0 ) {
                        ?>
                        <li class="mobileMenu__item" onclick="mobileMenuAccordion(<?php echo $i; ?>)">
                            <a class="mobileMenu__item__link" href="<?php echo $item->url; ?>">
                                <?php echo $item->post_title; ?>
                            </a>
                        <img class="mobileMenu__item__arrow" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/arrow.svg'; ?>" alt="strzalka" />


                        <ul class="mobileMenu__submenu">
                        <?php
                        foreach($items as $indexSub => $itemSub) {
                            if($itemSub->menu_item_parent == $item->ID) {
                                ?>

                                        <li class="mobileSubmenu__item">
                                            <a class="mobileMenu__item__link" href="<?php echo $itemSub->url; ?>">
                                                <?php echo $itemSub->post_title; ?>
                                            </a>
                                        </li>

                                    <?php
                            }
                        }
                        ?>
                        </ul>
                        </li>

                            <?php

                        $i++;
                    }
                }
                ?>
        </ul>
            <?php
            }
            ?>

        <ul class="mobileMenu__bottomMenu">
            <li class="mobileBottomMenu__item">
                Marka Alcanta >
            </li>
            <li class="mobileBottomMenu__item">
                <span class="mobileBottomMenu__item__discount">
                    -10%
                </span>
                Newsletter >
            </li>
            <li class="mobileBottomMenu__item">
                Polityka zwrotów >
            </li>
            <li class="mobileBottomMenu__item">
                Pomoc >
            </li>
        </ul>
    </menu>

<?php
}

add_action('storefront_header', 'alcanta_content_top', 13);

/* Homepage */
function alcanta_homepage() {
    ?>

    <!-- MOBILE LANDING -->
    <main class="mobileLanding">
        <img class="mobileLanding__img" src="<?php echo get_field('zdjecie_glowne', 410); ?>" alt="alcanta-pierwszenstwo-zakupu" />

        <div class="mobileLanding__content">
            <h1 class="mobileLanding__header">
                WAITLIST
            </h1>
            <h2 class="mobileLanding__subheader">
                Zdobądź pierszeństwo zakupu
            </h2>
            <button class="mobileLanding__btn button--animated button--animated--black preorderPopupOpen">
                    <span class="button__link">
                        Zapisuję się >
                    </span>
            </button>
        </div>
    </main>

<!-- CAROUSEL -->
    <section class="carousel">
        <div class="carousel__content swiper-container">
            <div class="carousel__embla swiper-wrapper">

                <?php
                    $carousel_options = array(
                            'post_type' => 'homepage_carousel'
                    );
                    $carousel_query = new WP_Query($carousel_options);
                    if($carousel_query->have_posts()) {
                        while($carousel_query->have_posts()) {
                            $carousel_query->the_post();
                            ?>

                            <a class="carousel__item" href="<?php echo get_field('link'); ?>">
                                <img class="carousel__item__img" src="<?php echo get_field('zdjecie'); ?>" alt="carousel-item" />
                            </a>



                <?php
                        }
                    }
                ?>
            </div>
        </div>

        <span class="carousel__progressBarContainer">
            <span class="carousel__progressBar"></span>
        </span>

        <button class="moreInfoBtn button--animated">
            <a class="button__link" href="<?php echo get_page_link(get_page_by_title('Preorder')->ID); ?>">
                Więcej informacji >
            </a>
        </button>
    </section>

    <!-- BASIC COLLECTION -->
    <section class="frontpageBasicCollection">
        <img class="frontpageBasicCollection__logo" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/alcanta-logo-elegant.png'; ?>" alt="alcanta-logo" />

        <img class="frontpageBasicCollection__img" src="<?php echo get_field('zdjecie_kolekcji_basic', 410); ?>" alt="girl" />

        <button class="moreInfoBtn moreInfoBtn--basicCollection button--animated">
            <a class="button__link" href=".">
                Kolekcja basic >
            </a>
        </button>
    </section>

    <?php
}

add_action('storefront_homepage', 'alcanta_homepage', 11);

/* Footer */
function alcanta_remove_footer_actions() {
    remove_all_actions('storefront_before_footer');
    remove_all_actions('storefront_after_footer');
}
add_action('wp_head', 'alcanta_remove_footer_actions');

function alcanta_footer() {
    ?>

    <!-- BEFORE FOOTER -->
    <section class="beforeFooter">
        <ul class="beforeFooter__list">
            <li class="beforeFooter__list__item">
                Dostawa i zwroty
                <img class="mobileMenu__item__arrow" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/arrow.svg'; ?>" alt="strzalka" />
            </li>
            <li class="beforeFooter__list__item">
                Newsletter
                <img class="mobileMenu__item__arrow" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/arrow.svg'; ?>" alt="strzalka" />
            </li>
            <li class="beforeFooter__list__item">
                Marka Alcanta
                <img class="mobileMenu__item__arrow" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/arrow.svg'; ?>" alt="strzalka" />
            </li>
            <li class="beforeFooter__list__item">
                Pomoc online
                <img class="mobileMenu__item__arrow" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/arrow.svg'; ?>" alt="strzalka" />
            </li>

        </ul>
    </section>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="footer__socialMediaIcons d-flex">
            <a class="footer__socialMediaIcons__link" href="https://facebook.com">
                <img class="footer__socialMediaImg" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/facebook.svg'; ?>" alt="facebook" />
            </a>

            <a class="footer__socialMediaIcons__link" href="https://facebook.com">
                <img class="footer__socialMediaImg" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/instagram.svg'; ?>" alt="instagram" />
            </a>

            <a class="footer__socialMediaIcons__link" href="https://facebook.com">
                <img class="footer__socialMediaImg" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/messenger.svg'; ?>" alt="messenger" />
            </a>
        </div>

        <h6 class="footer__caption">&copy; ALCANTA WEAR | All rights reserved</h6>
    </footer>

    <!-- Sticky countdown at the bottom of the page -->
    <div class="stickyCountdown">
        <?php echo do_shortcode('[ycd_countdown id=399]'); ?>
        <h3 class="stickyCountdown__header">
            Gofry - preorder
        </h3>
        <button class="stickyCountdown__btn">
            <img class="stickyCountdown__btn__img" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/right-arrow.png'; ?>" alt="right-arrow" />
        </button>
    </div>

<?php
}

add_action('storefront_footer', 'alcanta_footer');

function alcanta_single_post_before() {
    ?>

    <!-- FAQ -->
    <section class="collectionLocked__faq">

        <ul class="collectionLocked__faq__list">
            <li class="collectionLocked__faq__list__item">
                <label class="collectionLocked__faq__question">
                    <span>Opis</span>
                    <button class="collectionLocked__faq__btn" onclick="faqToggle(0)">
                    <span class="collectionLocked__faq__plus">
                        +
                    </span>
                        <span class="collectionLocked__faq__minus">
                        -
                    </span>
                    </button>
                </label>
                <span class="collectionLocked__faq__answer">
                <?php echo the_content(); ?>
            </span>
            </li>
            <li class="collectionLocked__faq__list__item">
                <label class="collectionLocked__faq__question">
                    Kiedy dostanę produkty Alcanta?
                    <button class="collectionLocked__faq__btn" onclick="faqToggle(1)">
                    <span class="collectionLocked__faq__plus">
                        +
                    </span>
                        <span class="collectionLocked__faq__minus">
                        -
                    </span>
                    </button>
                </label>
                <span class="collectionLocked__faq__answer">
                <?php echo get_field('kiedy_dostane_produkty_alcanta'); ?>
            </span>
            </li>
            <li class="collectionLocked__faq__list__item">
                <label class="collectionLocked__faq__question">
                    Dostawa i płatność
                    <button class="collectionLocked__faq__btn" onclick="faqToggle(2)">
                    <span class="collectionLocked__faq__plus">
                        +
                    </span>
                        <span class="collectionLocked__faq__minus">
                        -
                    </span>
                    </button>
                </label>
                <span class="collectionLocked__faq__answer">
                <?php echo get_field('dostawa_i_platnosc'); ?>
            </span>
            </li>
            <li class="collectionLocked__faq__list__item">
                <label class="collectionLocked__faq__question">
                    Skład i konserwacja
                    <button class="collectionLocked__faq__btn" onclick="faqToggle(3)">
                    <span class="collectionLocked__faq__plus">
                        +
                    </span>
                        <span class="collectionLocked__faq__minus">
                        -
                    </span>
                    </button>
                </label>
                <span class="collectionLocked__faq__answer">
                <?php echo get_field('sklad_i_konserwacja'); ?>
            </span>
            </li>
        </ul>
    </section>

<?php
}

add_action("woocommerce_after_single_product_summary", "alcanta_single_post_before");

function alcanta_after_single_product() {
    ?>

    <!-- CAROUSEL -->
    <section class="carousel carousel--single">
        <div class="carousel__content swiper-container">
            <div class="carousel__embla swiper-wrapper">

                <?php
                $product = new WC_Product(get_the_ID());
                $upsells = $product->get_upsells();
                if (!$upsells)
                    return;

                $meta_query = WC()->query->get_meta_query();

                $args = array(
                    'post_type' => 'product',
                    'ignore_sticky_posts' => 1,
                    'no_found_rows' => 1,
                    'posts_per_page' => 4,
                    'post__in' => $upsells,
                    'post__not_in' => array($product->id),
                    'meta_query' => $meta_query
                );

                $carousel_query = new WP_Query($args);

                if($carousel_query->have_posts()) {
                    while($carousel_query->have_posts()) {
                        $carousel_query->the_post();
                        ?>

                        <a class="carousel__item" href="<?php echo the_permalink(); ?>">
                            <img class="carousel__item__img" src="<?php echo the_post_thumbnail_url(); ?>" alt="carousel-item" />
                        </a>



                        <?php
                    }
                }
                ?>
            </div>
        </div>

        <span class="carousel__progressBarContainer">
            <span class="carousel__progressBar"></span>
        </span>
    </section>

<?php
}

add_action('woocommerce_after_single_product', 'alcanta_after_single_product');

/* Added to cart popup */
function alcanta_added_to_cart_popup() {
    ?>

    <div class="addedToCartPopup">
        <button class="addedToCartPopup__closeBtn" onclick="closeAddedToCartPopup()">
            &times;
        </button>

        <?php
        $product_id = get_the_ID();
        $product = wc_get_product($product_id);
        $price = $product->get_price();
        ?>

        <img class="checkedImg" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/checked.svg'; ?>" alt="dodano-do-koszyka" />
        <h2 class="addedToCartPopup__header">
            Udało Ci się dodać produkt do koszyka
        </h2>

        <img class="addedToCartPopup__productImg" src="<?php echo get_the_post_thumbnail_url(); ?>" alt="<?php echo the_title(); ?>" />

        <div class="addedToCartPopup__flex">
            <h3 class="addedToCartPopup__meta">
                <?php echo the_title(); ?>
            </h3>
            <h3 class="addedToCartPopup__meta">
                <?php echo $price; ?> PLN
            </h3>
        </div>
        <div class="addedToCartPopup__flex">
            <h3 class="addedToCartPopup__meta addedToCartPopup__meta--size">
                Rozmiar: <span></span>
            </h3>
        </div>

        <button class="mobileLanding__btn button--popup button--animated button--animated--black">
                    <a class="button__link" href="<?php echo wc_get_cart_url(); ?>">
                        Przejdź do zamówienia >
                    </a>
        </button>

        <button class="addedToCartPopup__continueBtn" onclick="closeAddedToCartPopup()">
            Kontynuuj zakupy
        </button>

    </div>


<?php
}

add_action('woocommerce_share', 'alcanta_added_to_cart_popup');

// Add homepage carousel post type
function alcanta_add_homepage_carousel_post_type() {
    $supports = array(
        'title'
    );

    $labels = array(
        'name' => 'Karuzela główna'
    );

    $args = array(
        'labels'               => $labels,
        'supports'             => $supports,
        'public'               => true,
        'capability_type'      => 'post',
        'has_archive'          => true,
        'menu_position'        => 30,
        'menu_icon'            => 'dashicons-desktop'
    );

    register_post_type("homepage_carousel", $args);
}

add_action("init", "alcanta_add_homepage_carousel_post_type");

// Add collection post type
function alcanta_add_collection_post_type() {
    $supports = array(
        'title'
    );

    $labels = array(
        'name' => 'Kolekcje'
    );

    $args = array(
        'labels'               => $labels,
        'supports'             => $supports,
        'public'               => true,
        'capability_type'      => 'post',
        'has_archive'          => true,
        'menu_position'        => 30,
        'menu_icon'            => 'dashicons-universal-access-alt'
    );

    register_post_type("collection", $args);
}

add_action("init", "alcanta_add_collection_post_type");

// Add collection locked post type
function alcanta_add_collection_locked_post_type() {
    $supports = array(
        'title'
    );

    $labels = array(
        'name' => 'Kolekcje przedpremierowe'
    );

    $args = array(
        'labels'               => $labels,
        'supports'             => $supports,
        'public'               => true,
        'capability_type'      => 'post',
        'has_archive'          => true,
        'menu_position'        => 30,
        'menu_icon'            => 'dashicons-welcome-view-site'
    );

    register_post_type("collection_locked", $args);
}

add_action("init", "alcanta_add_collection_locked_post_type");


/* AJAX add to cart */
add_action('wp_ajax_woocommerce_ajax_add_to_cart', 'woocommerce_ajax_add_to_cart');
add_action('wp_ajax_nopriv_woocommerce_ajax_add_to_cart', 'woocommerce_ajax_add_to_cart');

function woocommerce_ajax_add_to_cart() {

    $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
    $quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount($_POST['quantity']);
    $variation_id = absint($_POST['variation_id']);
    $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
    $product_status = get_post_status($product_id);

    if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity, $variation_id) && 'publish' === $product_status) {

        do_action('woocommerce_ajax_added_to_cart', $product_id);

        if ('yes' === get_option('woocommerce_cart_redirect_after_add')) {
            wc_add_to_cart_message(array($product_id => $quantity), true);
        }

        WC_AJAX :: get_refreshed_fragments();
    } else {
        $data = array(
            'error' => true,
            'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id));

        echo wp_send_json($data);
    }

    wp_die();
}

/* Cart - before button */
function alcanta_cart_totals_after_order_total() {
    ?>

    <img class="guaranteeImg" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/gwarancja-zwrotu.png'; ?>" alt="gwarancja" />

    <h3 class="guaranteeText">
        <img class="guaranteeText__img" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/lock.svg'; ?>" alt="lock" />
        Bezpieczny system płatności
    </h3>

<?php
}

add_action('woocommerce_cart_totals_after_order_total', 'alcanta_cart_totals_after_order_total');

function alcanta_cart_contents() {
    ?>
<div class="cart__afterCoupon">
    <h3 class="cart__info">
        <img class="cart__info__img" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/information.svg'; ?>" alt="info" />
        <span>Nie zwlekaj z zakupem, dodanie artykułów do koszyka nie oznacza ich rezerwacji.</span>
    </h3>
</div>

<?php
}

add_action('woocommerce_cart_coupon', 'alcanta_cart_contents');