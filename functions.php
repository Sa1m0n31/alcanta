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
    wp_enqueue_script('main-js', get_stylesheet_directory_uri() . '/assets/js/alcanta.js', array('embla'), 1.2, true);

    wp_enqueue_script('embla', '//unpkg.com/embla-carousel/embla-carousel.umd.js', null, null, true);
    //wp_enqueue_script('glider-js', get_stylesheet_directory_uri() . '/assets/js/glider.min.js', array(), 1.0,  true);

    wp_enqueue_style( 'bootstrap', '//stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css');
    wp_enqueue_style('glider', get_stylesheet_directory_uri() . '/assets/css/alcanta/glider.min.css', array(), 1.0);

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

    <!-- HEADER -->
    <header class="mobileHeader d-flex d-md-none align-items-center">
        <a class="mobileHeader__logoWrapper" href=".">
            <img class="mobileHeader__logo" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/alcanta-logo.png'; ?>" alt="alcanta-logo" />
        </a>

        <div class="mobileHeader__right d-flex">
            <button class="mobileHeader__btn">
                <img class="mobileHeader__btn__img" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/cart.svg'; ?>" alt="koszyk" />
                <span class="mobileHeader__btn__text">Koszyk</span>
            </button>
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
                <img class="mobileMenu__closeBtn__img" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/cancel.svg'; ?>" alt="wyjdz" />
            </button>
        </div>

        <ul class="mobileMenu__menu">
            <li class="mobileMenu__item" onclick="mobileMenuAccordion(0)">
                Sklep
                <img class="mobileMenu__item__arrow" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/arrow.svg'; ?>" alt="strzalka" />

                <ul class="mobileMenu__submenu">
                    <li class="mobileSubmenu__item">
                        Bluzki
                    </li>
                    <li class="mobileSubmenu__item">
                        Spodnie
                    </li>
                    <li class="mobileSubmenu__item">
                        Kurtyki
                    </li>
                </ul>
            </li>
            <li class="mobileMenu__item" onclick="mobileMenuAccordion(1)">
                Kolekcja basic
                <img class="mobileMenu__item__arrow" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/arrow.svg'; ?>" alt="strzalka" />

                <ul class="mobileMenu__submenu">
                    <li class="mobileSubmenu__item">
                        Bluzki
                    </li>
                    <li class="mobileSubmenu__item">
                        Spodnie
                    </li>
                    <li class="mobileSubmenu__item">
                        Kurtyki
                    </li>
                </ul>

            </li>
            <li class="mobileMenu__item" onclick="mobileMenuAccordion(2)">
                Looked collection
                <img class="mobileMenu__item__arrow" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/arrow.svg'; ?>" alt="strzalka" />

                <ul class="mobileMenu__submenu">
                    <li class="mobileSubmenu__item">
                        Bluzki
                    </li>
                    <li class="mobileSubmenu__item">
                        Spodnie
                    </li>
                    <li class="mobileSubmenu__item">
                        Kurtyki
                    </li>
                </ul>

            </li>
            <li class="mobileMenu__item" onclick="mobileMenuAccordion(3)">
                Kolekcje
                <img class="mobileMenu__item__arrow" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/arrow.svg'; ?>" alt="strzalka" />

                <ul class="mobileMenu__submenu">
                    <li class="mobileSubmenu__item">
                        Bluzki
                    </li>
                    <li class="mobileSubmenu__item">
                        Spodnie
                    </li>
                    <li class="mobileSubmenu__item">
                        Kurtyki
                    </li>
                </ul>
            </li>
        </ul>

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
        <img class="mobileLanding__img" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/landing.png'; ?>" alt="alcanta-pierwszenstwo-zakupu" />

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
        <div class="carousel__content">
            <div class="carousel__embla">
                <a class="carousel__item" href=".">
                    <img class="carousel__item__img" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/slider-looked.png'; ?>" alt="carousel-item" />
                </a>

                <a class="carousel__item" href=".">
                    <img class="carousel__item__img" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/slider-looked.png'; ?>" alt="carousel-item" />
                </a>

                <a class="carousel__item" href=".">
                    <img class="carousel__item__img" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/slider-looked.png'; ?>" alt="carousel-item" />
                </a>

                <a class="carousel__item" href=".">
                    <img class="carousel__item__img" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/slider-looked.png'; ?>" alt="carousel-item" />
                </a>
            </div>
        </div>

        <span class="carousel__progressBarContainer">
            <span class="carousel__progressBar"></span>
        </span>

        <button class="moreInfoBtn button--animated">
            <a class="button__link" href=".">
                Więcej informacji >
            </a>
        </button>
    </section>

    <!-- BASIC COLLECTION -->
    <section class="frontpageBasicCollection">
        <img class="frontpageBasicCollection__logo" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/alcanta-logo-elegant.png'; ?>" alt="alcanta-logo" />

        <img class="frontpageBasicCollection__img" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/girl.png'; ?>" alt="girl" />

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

<?php
}

add_action('storefront_footer', 'alcanta_footer');