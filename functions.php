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
    wp_enqueue_script( 'wp-util' ); // Option 1: Manually enqueue the wp-util library.
    wp_enqueue_script('main-js', get_stylesheet_directory_uri() . '/assets/js/alcanta.js', array('embla', 'jquery', 'wp-util'), 1.2, true);
    wp_enqueue_script( 'geowidget', 'https://geowidget.easypack24.net/js/sdk-for-javascript.js', null, null, true );

    wp_enqueue_script('embla', '//unpkg.com/embla-carousel/embla-carousel.umd.js', array(), 1.0, true);
    wp_enqueue_script('jquery', get_stylesheet_directory_uri() . '/assets/js/jquery.js', array(), 1.0, true);

    wp_enqueue_style( 'bootstrap', '//stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css');
    wp_enqueue_style('desktop', get_stylesheet_directory_uri() . '/desktop.css', array(), 1.0);

    wp_enqueue_style('bootstrap-style', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css', null, 1.0, true);
    wp_enqueue_style('bootstrap-style');
    wp_register_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js', null, 1.0, true);
    wp_enqueue_script('bootstrap');
    wp_register_script('bootstrap-bundle', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js', null, 1.0, true);
    wp_enqueue_script('bootstrap-bundle');
}

add_action('wp_enqueue_scripts', 'alcanta_enqueue_script');

add_action('wp_ajax_nopriv_get_shipping_address', 'alcanta_get_shipping_address');
add_action('wp_ajax_get_shipping_address', 'alcanta_get_shipping_address');

function alcanta_get_shipping_address() {
    wp_send_json_success(WC()->customer->get_shipping_address() . ' ' . WC()->customer->get_shipping_postcode() . ' ' . WC()->customer->get_shipping_city());
}

/* Add InPost Geowidget */
add_action('wp_head', 'inpost_script_javascript', 9);
function inpost_script_javascript()
{
    /* sprawdzamy czy jesteśmy na stronie z zamówieniem */
    if (is_checkout()) {
        ?>
        <script src="https://geowidget.easypack24.net/js/sdk-for-javascript.js"></script>
        <script type="text/javascript">
            function createOutput(e) {
                /*
                  pobieramy id naszego pola które ustawialiśmy w formularzu
                  i wstawiamy do pola nasz adres który otrzymujemy od inpost
                */
                const t = e.address,
                    o = t.line1 + ", " + t.line2 + ", " + e.name;
                sessionStorage.setItem('alcanta-paczkomat', o);
            };
            /*
              w miejscu uruchamiamy funkcję która otwiera popup,
              a także wywołujemy funkcję zamykająca popup
              na oficjalnej stornie inpost jest metoda do zamknięcia
              okna ale jest zbugowana
              a za pomocą tej funkcji createOutput wstawiamy dane
              do pola inpost
            */
            function openModal() {
                easyPack.modalMap(function(e, n) {
                    document.getElementById("widget-modal").addEventListener("click", closeModalPopup), createOutput(e)
                }, {
                    width: 500,
                    height: 600
                })

                const fullAddress = sessionStorage.getItem('alcanta-paczkomat');

                changeShippingAddress(fullAddress);

                document.querySelector(".shippingDestinationFlex>strong").textContent = sessionStorage.getItem('alcanta-paczkomat');
            };
            // funkcja chowająca popup
            function closeModalPopup() {
                var e = document.getElementById("widget-modal");
                e.parentNode.style.display = "none";
                e.removeEventListener("click", closeModalPopup)

                const fullAddress = sessionStorage.getItem('alcanta-paczkomat');

                changeShippingAddress(fullAddress);

                /* Zmieniamy wartosc pola zawierajacego adres dostawy */
                document.querySelector(".shippingDestinationFlex>strong").textContent = sessionStorage.getItem('alcanta-paczkomat');
            }

            window.easyPackAsyncInit = function() {
                easyPack.init({
                    defaultLocale: "pl",
                    mapType: "osm",
                    searchType: "osm",
                    points: {
                        types: ["parcel_locker"]
                    },
                    map: {
                        initialTypes: ["parcel_locker"]
                    }
                })
            };

            // kliknięcie w pole inpost wywołuje uruchomienie funkcji openModal()
            window.addEventListener("DOMContentLoaded", function() {
                document.querySelector("label[for=shipping_method_0_flat_rate2]").addEventListener("click", function() {
                    openModal();
                })
            });
        </script>
        <link rel="stylesheet" href="https://geowidget.easypack24.net/css/easypack.css" />
    <?php }
}

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
        $topBarText = get_field('top_bar_-_tekst_1', 403);
        if($topBarText) {
            ?>

            <aside class="topBar">
                <div class="topBar__header">
                    <?php
                    // Loop through all texts
                    for($i=1; $i<6; $i++) {
                        $field = get_field('top_bar_-_tekst_' . $i, 403);
                        if($field) {
                        ?>
                            <h5 class="topBar__header__h">
                                <a class="topBar__header__h__link" href="<?php echo get_field('top_bar_-_link_' . $i, 403); ?>">
                                    <?php echo $field; ?>
                                </a>
                            </h5>

                            <?php
                        }
                    }
                    ?>
                </div>
            </aside>

                <?php
        }
    ?>

    <!-- HEADER DESKTOP -->
    <header class="desktopHeader d-none d-md-flex">
        <a class="desktopHeader__logo" href="<?php echo home_url(); ?>">
            <img class="desktopHeader__logo__img" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/alcanta-logo-elegant-small.png'; ?>" alt="alcanta-logo" />
        </a>
        <a class="desktopHeader__btn desktopHeader__cartBtn" href="<?php echo wc_get_cart_url(); ?>">
            <img class="mobileHeader__btn__img" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/cart.svg'; ?>" alt="koszyk" />
            <span class="mobileHeader__btn__text">Koszyk</span>
            <span class="mobileHeader__cartCount" id="cartCount">
                    <?php echo WC()->cart->get_cart_contents_count(); ?>
            </span>
        </a>
    </header>

    <!-- HEADER -->
    <header class="mobileHeader d-flex d-md-none align-items-center">
        <a class="mobileHeader__logoWrapper" href="<?php echo home_url(); ?>">
            <img class="mobileHeader__logo" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/alcanta-logo.png'; ?>" alt="alcanta-logo" />
        </a>

        <div class="mobileHeader__right d-flex">
            <a class="mobileHeader__btn" href="<?php echo wc_get_cart_url(); ?>">
                <img class="mobileHeader__btn__img" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/cart.svg'; ?>" alt="koszyk" />
                <span class="mobileHeader__btn__text">Koszyk</span>
                <span class="mobileHeader__cartCount" id="cartCount2">
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
                        <li class="mobileMenu__item">
                            <p class="mobileMenu__item__link" onclick="mobileMenuAccordion(<?php echo $i; ?>)">
                                <?php echo $item->post_title; ?>
                            </p>
                            <a href="<?php echo $item->url; ?>">
                                <img class="mobileMenu__item__arrow" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/arrow.svg'; ?>" alt="strzalka" />
                            </a>

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
                <a href="<?php echo get_page_link(get_page_by_title('O marce')->ID); ?>">
                    Marka Alcanta
                </a>
            </li>
            <li class="mobileBottomMenu__item" onclick="window.scrollTo(0,document.body.scrollHeight); closeMobileMenu(); toggleBeforeFooter(2)">
                <span class="mobileBottomMenu__item__discount">
                    -10%
                </span>
                Newsletter
            </li>
            <li class="mobileBottomMenu__item" onclick="window.scrollTo(0,document.body.scrollHeight); closeMobileMenu(); toggleBeforeFooter(4)">
                Polityka zwrotów
            </li>
            <li class="mobileBottomMenu__item" onclick="window.scrollTo(0,document.body.scrollHeight); closeMobileMenu(); toggleBeforeFooter(4)">
                Pomoc
            </li>
        </ul>
    </menu>

<?php
}

function menu_set_dropdown( $sorted_menu_items, $args ) {
    $last_top = 0;
    foreach ( $sorted_menu_items as $key => $obj ) {
        // it is a top lv item?
        if ( 0 == $obj->menu_item_parent ) {
            // set the key of the parent
            $last_top = $key;
        } else {
            $sorted_menu_items[$last_top]->classes['dropdownItem'] = 'dropdownItem';
        }
    }
    return $sorted_menu_items;
}
add_filter( 'wp_nav_menu_objects', 'menu_set_dropdown', 10, 2 );

add_action('storefront_header', 'alcanta_content_top', 13);

/* Homepage */
function alcanta_homepage() {
    ?>

    <!-- DESKTOP LANDING -->
    <main class="desktopLanding d-none d-md-block">
        <img class="desktopLanding__img" src="<?php echo get_field('zdjecie_glowne_-_desktop', 410); ?>" alt="alcanta" />
        <section class="desktopLanding__content">
            <h1 class="desktopLanding__header">
                <?php echo get_field('landing_desktop_-_header', 410); ?>
            </h1>
            <h2 class="desktopLanding__subheader">
                <?php echo get_field('landing_desktop_-_subheader', 410); ?>
            </h2>
            <button class="desktopLanding__btn mobileLanding__btn button--animated button--animated--black">
                    <a class="button__link" href="<?php echo get_field('landing_desktop_-_link_buttona', 410); ?>">
                        <?php echo get_field('landing_desktop_-_tekst_buttona', 410); ?>
                    </a>
            </button>
        </section>
    </main>

    <!-- MOBILE LANDING -->
    <main class="mobileLanding d-md-none">
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

    <!-- DESKTOP SLOGAN -->
    <section class="desktopSlogan d-none d-lg-flex">
        <h2 class="desktopSlogan__header">
            Każde zamówienie to niezapomiane <span class="desktopSlogan__border">doświadczenie</span>
        </h2>
        <img class="desktopSlogan__box" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/box.svg'; ?>" alt="pudelko" />
    </section>

<!-- CAROUSEL -->
    <section class="carousel">
        <div class="carousel__content swiper-container">
            <div class="carousel__embla swiper-wrapper">

                <?php
                function isHomepageCarousel($arr) {
                    for($i=0; $i<sizeof($arr); $i++) {
                        if($arr[$i] == "Strona główna") return true;
                    }
                    return false;
                }
                    $carousel_options = array(
                            'post_type' => 'homepage_carousel'
                    );
                    $carousel_query = new WP_Query($carousel_options);
                    if($carousel_query->have_posts()) {
                        while($carousel_query->have_posts()) {
                            $carousel_query->the_post();
                            if(isHomepageCarousel(get_field('miejsce'))) {
                            ?>

                            <a class="carousel__item" href="<?php echo get_field('link'); ?>">
                                <img class="carousel__item__img" src="<?php echo get_field('zdjecie'); ?>" alt="carousel-item" />
                            </a>



                <?php
                        }
                        }
                    }
                ?>
            </div>
        </div>

        <span class="carousel__progressBarContainer">
            <span class="carousel__progressBar"></span>
        </span>

        <button class="moreInfoBtn button--animated d-lg-none">
            <a class="button__link" href="<?php echo get_field('link_do_buttona_1', 410); ?>">
                <?php echo get_field('tekst_buttona_1', 410); ?>
            </a>
        </button>
    </section>

    <!-- BASIC COLLECTION DESKTOP -->
    <section class="frontpageBasicCollectionDesktop d-none d-lg-flex">
        <h2 class="basic__h2">
            Kolekcja Basic
        </h2>
        <h3 class="basic__h3">
            Sprawdź najpopularniejsze produkty Alcanta
        </h3>
        <h4 class="basic__h4">
            Dowiedz się więcej
        </h4>

        <a class="basicCollection__desktopBtn"  href="https://skylo-test1.pl/collection/basic">
            Kolekcja BASIC
            <img class="basicCollection__desktopBtn__img" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/arrow-left.png'; ?>" alt="strzalka" />
        </a>
    </section>

    <!-- BASIC COLLECTION MOBILE -->
    <section class="frontpageBasicCollection d-lg-none">
        <img class="frontpageBasicCollection__logo" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/alcanta-logo-elegant.png'; ?>" alt="alcanta-logo" />

        <img class="frontpageBasicCollection__img" src="<?php echo get_field('zdjecie_kolekcji_basic', 410); ?>" alt="girl" />

        <button class="moreInfoBtn moreInfoBtn--basicCollection button--animated">
            <a class="button__link" href="<?php echo get_field('link_do_buttona_2', 410); ?>">
                <?php echo get_field('tekst_buttona_2', 410); ?>
            </a>
        </button>
    </section>

    <!-- NEWSLETTER DESKTOP -->
    <section class="newsletterDesktop d-none d-lg-block">
        <h2 class="newsletterDesktop__header">
            Newsletter
        </h2>
        <h3 class="newsletterDesktop__h3">
            Dołącz do społeczności z wyczuciem stylu
        </h3>
        <h4 class="newsletterDesktop__h4">
            Zdobądź -10% na pierwsze zakupy, specjalne promocje i informacje
        </h4>

        <div class="newsletterDesktop__form">
            <?php
            echo do_shortcode('[newsletter_form list="1"]');
            ?>
        </div>
    </section>

    <!-- ABOUT DESKTOP -->
    <section class="aboutDesktop d-none d-lg-flex">
        <div class="aboutDesktop__left">

        </div>
        <div class="aboutDesktop__right">
            <img class="aboutDesktop__img" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/alcanta-logo-elegant-big.png'; ?>" alt="alcanta-logo" />
        </div>
        <div class="aboutDesktop__left">
            <?php echo get_field('desktop_-_tekst_o_marce', 410); ?>
        </div>
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
    <section class="beforeFooter d-lg-none">
        <ul class="beforeFooter__list">
            <li class="beforeFooter__list__item">
                <button class="beforeFooter__list__item__btn" onclick="toggleBeforeFooter(1)">
                    Dostawa i zwroty
                    <img class="mobileMenu__item__arrow" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/arrow.svg'; ?>" alt="strzalka" />
                </button>
                <div class="beforeFooter__dropdown">
                    <p class="beforeFooter__dropdown__text">
                        W naszym sklepie realizujemy zwroty <b>do 30 dni</b> zgodnie z naszą polityką zwrotów.
                    </p>

                    <p class="beforeFooter__dropdown__text">
                        <span class="beforeFooter__dropdown__span">Formy dostawy:</span>
                        <span class="beforeFooter__dropdown__span">- kurier InPost</span>
                        <span class="beforeFooter__dropdown__span">- paczkomaty InPost</span>
                    </p>

                    <p class="beforeFooter__dropdown__text">
                        Wysyłka:<br/>
                        Twoje zamówienie starannie pakujemy i wysyłamy do 48 h od momentu zaksięgowania wpłaty
                    </p>

                    <p class="beforeFooter__dropdown__text">
                        Dostępne metody płatności <button class="beforeFooter__dropdown__paymentBtn" onclick="togglePaymentMethods()">Pokaż wszystkie</button>
                    </p>

                    <div class="beforeFooter__paymentMethods">
                        <img class="beforeFooter__paymentMethods__img" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/metody-platnosci.png'; ?>" alt="metody-platnosci" />
                    </div>
                </div>
            </li>
            <li class="beforeFooter__list__item">
                <button class="beforeFooter__list__item__btn" onclick="toggleBeforeFooter(2)">
                    Newsletter
                    <img class="mobileMenu__item__arrow" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/arrow.svg'; ?>" alt="strzalka" />
                </button>
                <div class="beforeFooter__dropdown">
                    <p class="beforeFooter__dropdown__text">
                        Chcesz zgarnąć -50% na pierwsze zakupy i być na bieżąco z naszymi nowościami? Zapisz się do newslettera!
                    </p>
                    <?php
                    echo do_shortcode('[newsletter_form list="1"]');
                    ?>
                </div>
            </li>
            <li class="beforeFooter__list__item">
                <a class="beforeFooter__list__item__btn" href="<?php echo get_page_link(get_page_by_title('O marce')->ID); ?>">
                    Marka Alcanta
                    <img class="mobileMenu__item__arrow" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/arrow.svg'; ?>" alt="strzalka" />
                </a>
            </li>
            <li class="beforeFooter__list__item">
                <button class="beforeFooter__list__item__btn" onclick="toggleBeforeFooter(4)">
                    Pomoc online
                    <img class="mobileMenu__item__arrow" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/arrow.svg'; ?>" alt="strzalka" />
                </button>
                <div class="beforeFooter__dropdown">
                    <p class="beforeFooter__dropdown__text">
                        Odszukaj odpowiedzi na swoje pytania lub uzyskaj pomoc kontaktując się z nami.
                    </p>

                    <button class="beforeFooter__questionBtn popmake-681">
                        Tabele rozmiarowe >
                    </button>
                    <button class="beforeFooter__questionBtn popmake-517">
                        Regulamin >
                    </button>
                    <button class="beforeFooter__questionBtn popmake-683">
                        Polityka prywatności >
                    </button>
                    <button class="beforeFooter__questionBtn popmake-682">
                        Czas dostawy >
                    </button>

                    <p class="beforeFooter__dropdown__text">
                        Kontakt (odpowiadamy pon.-pt. 8:00-16:00)
                    </p>

                    <a class="beforeFooter__mailLink" href="mailto:kontakt@alcanta.pl">
                        <img class="beforeFooter__mailImg" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/email.svg'; ?>" alt="mail" />
                        <span>kontakt@alcanta.pl</span>
                    </a>

                </div>
            </li>

        </ul>
    </section>

    <!-- FOOTER DESKTOP -->
    <footer class="footerDesktop d-none d-lg-flex">
        <img class="footerDesktop__logo" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/alcanta-logo-elegant.png'; ?>" alt="alcanta-logo" />

        <menu class="footerDesktop__menu">
            <?php
            wp_nav_menu( array(
                'menu' => 'Footer menu'
            ) );
            ?>
        </menu>

        <h6 class="footer__caption">&copy; ALCANTA WEAR | All rights reserved</h6>
    </footer>

    <!-- FOOTER MOBILE -->
    <footer class="footer d-lg-none">
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
    <a class="stickyCountdown d-md-none" href="<?php echo get_field('link_do_sticky_countdown_buttona', 410); ?>">
        <?php echo do_shortcode('[ycd_countdown id=399]'); ?>
        <h3 class="stickyCountdown__header">
            <?php echo get_field('tekst_do_sticky_countdown_buttona', 410); ?>
        </h3>
        <button class="stickyCountdown__btn">
            <img class="stickyCountdown__btn__img" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/right-arrow.png'; ?>" alt="right-arrow" />
        </button>
    </a>

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
        <div class="carousel__content swiper-container carousel--single">
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


add_action('woocommerce_after_cart', 'alcanta_after_cart');

/* After cart carousel */
function alcanta_after_cart() {
    ?>
        <section class="afterCart d-desktop">
            <h2 class="afterCart__header">
                Zobacz jeszcze
            </h2>
            <section class="thankYou__carousel thankYou__carousel--afterCart">
                <div class="carousel__content swiper-container">
                    <div class="carousel__embla swiper-wrapper">

                        <?php
                        function isCartCarousel($arr) {
                            for($i=0; $i<sizeof($arr); $i++) {
                                if($arr[$i] == "Koszyk (wersja desktop)") return true;
                            }
                            return false;
                        }

                        $carousel_options = array(
                            'post_type' => 'homepage_carousel'
                        );
                        $carousel_query = new WP_Query($carousel_options);
                        if($carousel_query->have_posts()) {
                            while($carousel_query->have_posts()) {
                                $carousel_query->the_post();
                                if(isCartCarousel(get_field('miejsce'))) {
                                ?>

                                <a class="carousel__item" href="<?php echo get_field('link'); ?>">
                                    <img class="carousel__item__img" src="<?php echo get_field('zdjecie'); ?>" alt="carousel-item" />
                                </a>



                                <?php
                            }
                            }
                        }
                        ?>
                    </div>
                </div>

                <span class="carousel__progressBarContainer">
            <span class="carousel__progressBar"></span>
        </span>

            </section>

        </section>
        <?php
}

/* Added to cart popup */
function alcanta_added_to_cart_popup() {
    ?>

    <div class="addedToCartPopupWrapper">
        <main class="addedToCartPopup">
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
                <h3 class="addedToCartPopup__meta addedToCartPopup--price">

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
        </main>
    </div>


<?php
}

add_action( 'woocommerce_before_add_to_cart_quantity', 'func_option_valgt' );

function func_option_valgt() {
    global $product;

    if ( $product->is_type('variable') ) {
        $variations_data =[]; // Initializing

        // Loop through variations data
        foreach($product->get_available_variations() as $variation ) {
            // Set for each variation ID the corresponding price in the data array (to be used in jQuery)
            $variations_data[$variation['variation_id']] = $variation['display_price'];
        }
        ?>
        <script>
            jQuery(function($) {
                var jsonData = <?php echo json_encode($variations_data); ?>,
                    inputVID = 'input.variation_id';

                $('input').change( function(){
                    if( '' != $(inputVID).val() ) {
                        var vid      = $(inputVID).val(), // VARIATION ID
                            length   = $('#cfwc-title-field').val(), // LENGTH
                            diameter = $('#diameter').val(),  // DIAMETER
                            vprice   = ''; // Initilizing

                        // Loop through variation IDs / Prices pairs
                        $.each( jsonData, function( index, price ) {
                            if( index == $(inputVID).val() ) {
                                vprice = price; // The right variation price
                                sessionStorage.setItem('alcanta-current-price', vprice);
                                document.querySelector(".single-product div.product p.price").textContent = vprice + " PLN";
                            }
                        });

                    }
                });
            });
        </script>
        <?php
    }
}

//add_filter( 'woocommerce_variation_option_price', 'display_price_in_variation_option_name' );

function display_price_in_variation_option_name( $term ) {
    global $wpdb, $product;

    if ( empty( $term ) ) return $term;
    if ( empty( $product->id ) ) return $term;

    $id = $product->get_id();

    $result = $wpdb->get_col( "SELECT slug FROM {$wpdb->prefix}terms WHERE name = '$term'" );

    $term_slug = ( !empty( $result ) ) ? $result[0] : $term;

    $query = "SELECT postmeta.post_id AS product_id
                FROM {$wpdb->prefix}postmeta AS postmeta
                    LEFT JOIN {$wpdb->prefix}posts AS products ON ( products.ID = postmeta.post_id )
                WHERE postmeta.meta_key LIKE 'attribute_%'
                    AND postmeta.meta_value = '$term_slug'
                    AND products.post_parent = $id";

    $variation_id = $wpdb->get_col( $query );

    $parent = wp_get_post_parent_id( $variation_id[0] );

    if ( $parent > 0 ) {
        $_product = new WC_Product_Variation( $variation_id[0] );
        return $term . ' (' . wp_kses( woocommerce_price( $_product->get_price() ), array() ) . ')';
    }
    return $term;

}

add_action('woocommerce_share', 'alcanta_added_to_cart_popup');

// Add homepage carousel post type
function alcanta_add_homepage_carousel_post_type() {
    $supports = array(
        'title'
    );

    $labels = array(
        'name' => 'Karuzele'
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



<?php
}

add_action('woocommerce_cart_totals_after_order_total', 'alcanta_cart_totals_after_order_total');

function alcanta_cart_contents() {
    ?>


<?php
}

add_action('woocommerce_cart_coupon', 'alcanta_cart_contents');

/* Remove checkout fields */
function wc_remove_checkout_fields( $fields ) {

    // Billing fields
    unset( $fields['billing']['billing_phone'] );
    unset( $fields['billing']['billing_state'] );


    // Order fields
    unset( $fields['order']['order_comments'] );

    return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'wc_remove_checkout_fields' );


/* AJAX cart count */
add_filter( 'woocommerce_add_to_cart_fragments', 'wc_refresh_mini_cart_count');
function wc_refresh_mini_cart_count($fragments){
    ob_start();
    $items_count = WC()->cart->get_cart_contents_count();
    ?>
    <span class="mobileHeader__cartCount" id="cartCount">
             <?php echo $items_count; ?>
    </span>
    <span class="mobileHeader__cartCount" id="cartCount2">
             <?php echo $items_count; ?>
    </span>
    <?php
    $fragments['#cartCount'] = ob_get_clean();
    $fragments['#cartCount2'] = ob_get_clean();
    return $fragments;
}
