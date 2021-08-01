<?php
/**
 * Checkout billing information form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-billing.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 * @global WC_Checkout $checkout
 */

defined( 'ABSPATH' ) || exit;

$formatted_destination    = isset( $formatted_destination ) ? $formatted_destination : WC()->countries->get_formatted_address( $package['destination'], ', ' );

?>

<!-- TOP BAR -->
<header class="checkoutTopBar">
    <a class="checkoutTopBar__back" href="<?php echo wc_get_cart_url(); ?>">
        <img class="checkoutTopBar__back__img" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/arrow-left.png'; ?>" alt="wroc" />
        Powrót
    </a>

    <img class="checkoutTopBar__logo" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/alcanta-logo-checkout.png'; ?>" alt="alcanta" />
</header>

<!-- CART -->
<section class="checkoutCart">
    <div class="checkoutCart__header" onclick="toggleCheckoutCart()">
        <div class="checkoutCart__header__cartIconWrapper">
            <span class="mobileHeader__cartCount mobileHeader__cartCount--checkout">
                    <?php echo WC()->cart->get_cart_contents_count(); ?>
        </span>
            <img class="checkoutCart__header__icon" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/cart.svg'; ?>" alt="koszyk" />
        </div>
        <h2 class="checkoutCart__header__h">
            Pokaż przedmioty w koszyku
        </h2>
        <img class="checkoutCart__header__arrow" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/arrow.svg'; ?>" alt="zwin-rozwin" />
    </div>
</section>

<!-- CART CAROUSEL -->
<section class="checkoutCarousel">
    <div class="carousel__content swiper-container">
        <div class="carousel__embla swiper-wrapper">

            <?php
            global $woocommerce;
            $items = $woocommerce->cart->get_cart();
            foreach($items as $item => $values) {
                $_product =  wc_get_product( $values['data']->get_id() );
                $getProductDetail = wc_get_product( $values['product_id'] );
                ?>

                <a class="carousel__item" href="<?php echo get_permalink($values['product_id']); ?>">
                    <img class="carousel__item__img" src="<?php echo get_the_post_thumbnail_url($values['product_id']); ?>" alt="carousel-item" />
                    <h4 class="carousel__item__header">
                        <?php echo get_the_title($values['product_id']); ?>
                    </h4>
                    <h5 class="carousel__item__subheader">
                        <!-- TODO -->
                        Rozmiar:
                        <?php
                        $variation = new WC_Product_Variation($values['variation_id']);
                        $variationName = implode(" / ", $variation->get_variation_attributes());
                        echo $variationName;
                        ?>
                    </h5>
                </a>

            <?php
            }
            ?>
        </div>
    </div>
    <span class="carousel__progressBarContainer">
            <span class="carousel__progressBar"></span>
    </span>
</section>


<!-- WYSYLKA -->
<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
    <!-- CUSTOM CODE -->
    <h2 class="checkoutHeader">
            <span class="checkoutHeader__count">
                1
            </span>
        <span class="checkoutHeader__text">
                Wybierz dostawę
            </span>
    </h2>

<!--    --><?php //do_action( 'woocommerce_review_order_before_shipping' ); ?>

    <?php wc_cart_totals_shipping_html(); ?>

    <?php do_action( 'woocommerce_review_order_after_shipping' ); ?>

<?php endif; ?>

<!-- SHIPPING FORM -->

<div class="woocommerce-shipping-fields">
    <?php if ( true === WC()->cart->needs_shipping_address() ) : ?>

        <h3 id="ship-to-different-address">
            <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
                <input id="ship-to-different-address-checkbox" type="checkbox" name="ship_to_different_address" value="1" checked /> <span><?php esc_html_e( 'Ship to a different address?', 'woocommerce' ); ?></span>
            </label>
        </h3>

        <div class="shipping_address">
            <span class="shipping__address__href"></span>

            <?php do_action( 'woocommerce_before_checkout_shipping_form', $checkout ); ?>

            <div class="woocommerce-shipping-fields__field-wrapper">
                <?php
                $fields = $checkout->get_checkout_fields( 'shipping' );

                foreach ( $fields as $key => $field ) {
                    woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
                }
                ?>
            </div>

            <button class="mobileLanding__btn changeShippingAddressBtn button--animated button--animated--black">
                    <span class="button__link">
                        Zmień adres dostawy
                    </span>
            </button>

            <?php do_action( 'woocommerce_after_checkout_shipping_form', $checkout ); ?>

        </div>

    <?php endif; ?>
</div>
<div class="woocommerce-additional-fields">
    <?php do_action( 'woocommerce_before_order_notes', $checkout ); ?>

    <?php if ( apply_filters( 'woocommerce_enable_order_notes_field', 'yes' === get_option( 'woocommerce_enable_order_comments', 'yes' ) ) ) : ?>

        <?php if ( ! WC()->cart->needs_shipping() || wc_ship_to_billing_address_only() ) : ?>

            <h3><?php esc_html_e( 'Additional information', 'woocommerce' ); ?></h3>

        <?php endif; ?>

        <div class="woocommerce-additional-fields__field-wrapper">
            <?php foreach ( $checkout->get_checkout_fields( 'order' ) as $key => $field ) : ?>
                <?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>

    <?php do_action( 'woocommerce_after_order_notes', $checkout ); ?>
</div>



<!-- END SHIPPING FORM -->

<?php wc_get_template(' checkout/form-shipping.php'); ?>

<!-- PLATNOSCI -->
<?php wc_get_template( 'checkout/payment.php' ); ?>

<!-- FORMULARZ -->
<div class="woocommerce-billing-fields">
	<?php if ( wc_ship_to_billing_address_only() && WC()->cart->needs_shipping() ) : ?>

		<h3><?php esc_html_e( 'Billing &amp; Shipping', 'woocommerce' ); ?></h3>

	<?php else : ?>

		<h3><?php esc_html_e( 'Billing details', 'woocommerce' ); ?></h3>

	<?php endif; ?>

	<?php do_action( 'woocommerce_before_checkout_billing_form', $checkout ); ?>

	<div class="woocommerce-billing-fields__field-wrapper">
		<?php
		$fields = $checkout->get_checkout_fields( 'billing' );

		foreach ( $fields as $key => $field ) {
			woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
		}
		?>
	</div>

    <!-- BOX CLONE -->
    <div class="form-row place-order d-none d-md-block">
        <noscript>
            <?php
            /* translators: $1 and $2 opening and closing emphasis tags respectively */
            printf( esc_html__( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the %1$sUpdate Totals%2$s button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce' ), '<em>', '</em>' );
            ?>
            <br/><button type="submit" class="button alt" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e( 'Update totals', 'woocommerce' ); ?>"><?php esc_html_e( 'Update totals', 'woocommerce' ); ?></button>
        </noscript>

        <?php wc_get_template('checkout/review-order.php'); ?>

        <?php do_action( 'woocommerce_review_order_before_submit' ); ?>

        <?php echo apply_filters( 'woocommerce_order_button_html', '<button type="submit" class="button alt" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( 'Kupuję i płacę' ) . '" data-value="' . esc_attr( 'Kupuję i płacę' ) . '">' . esc_html( 'Kupuję i płacę' ) . '</button>' ); // @codingStandardsIgnoreLine ?>

        <?php wc_get_template( 'checkout/terms.php' ); ?>

        <?php do_action( 'woocommerce_review_order_after_submit' ); ?>

        <?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>
    </div>


    <?php do_action( 'woocommerce_after_checkout_billing_form', $checkout ); ?>
</div>

<?php if ( ! is_user_logged_in() && $checkout->is_registration_enabled() ) : ?>
	<div class="woocommerce-account-fields">
		<?php if ( ! $checkout->is_registration_required() ) : ?>

			<p class="form-row form-row-wide create-account">
				<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
					<input class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" id="createaccount" <?php checked( ( true === $checkout->get_value( 'createaccount' ) || ( true === apply_filters( 'woocommerce_create_account_default_checked', false ) ) ), true ); ?> type="checkbox" name="createaccount" value="1" /> <span><?php esc_html_e( 'Create an account?', 'woocommerce' ); ?></span>
				</label>
			</p>

		<?php endif; ?>

		<?php do_action( 'woocommerce_before_checkout_registration_form', $checkout ); ?>

		<?php if ( $checkout->get_checkout_fields( 'account' ) ) : ?>

			<div class="create-account">
				<?php foreach ( $checkout->get_checkout_fields( 'account' ) as $key => $field ) : ?>
					<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
				<?php endforeach; ?>
				<div class="clear"></div>
			</div>

		<?php endif; ?>

		<?php do_action( 'woocommerce_after_checkout_registration_form', $checkout ); ?>
	</div>
<?php endif; ?>

<div class="form-row place-order">
    <noscript>
        <?php
        /* translators: $1 and $2 opening and closing emphasis tags respectively */
        printf( esc_html__( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the %1$sUpdate Totals%2$s button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce' ), '<em>', '</em>' );
        ?>
        <br/><button type="submit" class="button alt" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e( 'Update totals', 'woocommerce' ); ?>"><?php esc_html_e( 'Update totals', 'woocommerce' ); ?></button>
    </noscript>

    <?php wc_get_template('checkout/review-order.php'); ?>

    <?php do_action( 'woocommerce_review_order_before_submit' ); ?>

    <?php echo apply_filters( 'woocommerce_order_button_html', '<button type="submit" class="button alt desktopLanding__btn mobileLanding__btn button--animated" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( 'Kupuję i płacę' ) . '" data-value="' . esc_attr( 'Kupuję i płacę' ) . '">' . esc_html( 'Kupuję i płacę' ) . '</button>' ); // @codingStandardsIgnoreLine ?>

    <?php wc_get_template( 'checkout/terms.php' ); ?>

    <?php do_action( 'woocommerce_review_order_after_submit' ); ?>

    <?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>
</div>

