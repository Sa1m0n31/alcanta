<?php
/**
 * Shipping Methods Display
 *
 * In 2.1 we show methods per package. This allows for multiple methods per order if so desired.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-shipping.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

$formatted_destination    = isset( $formatted_destination ) ? $formatted_destination : WC()->countries->get_formatted_address( $package['destination'], ', ' );
$has_calculated_shipping  = ! empty( $has_calculated_shipping );
$show_shipping_calculator = ! empty( $show_shipping_calculator );
$calculator_text          = '';
?>
<tr class="woocommerce-shipping-totals shipping">
<!--    <th>--><?php //echo wp_kses_post( $package_name ); ?><!--</th>-->
<!--    <td data-title="--><?php //echo esc_attr( $package_name ); ?><!--">-->
        <?php if ( $available_methods ) : ?>
            <ul id="shipping_method" class="woocommerce-shipping-methods">
                <?php foreach ( $available_methods as $method ) : ?>
                    <li>
                        <?php
                        if ( 1 < count( $available_methods ) ) {
                            printf( '<input onclick="changeShippingMethod(this, true)" type="radio" name="shipping_method[%1$d]" data-index="%1$d" id="shipping_method_%1$d_%2$s" value="%3$s" class="shipping_method" %4$s />', $index, esc_attr( sanitize_title( $method->id ) ), esc_attr( $method->id ), checked( $method->id, $chosen_method, false ) ); // WPCS: XSS ok.
                            ?>
                            <span class="shippingMethod__free">ZA DARMO (dzisiaj)</span>
                            <span class="shippingMethod__deliveryTime">
                                    <img class="shippingMethod__deliveryTime__img" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/time.png'; ?>" alt="czas" />
                                    1 - 4 dni roboczych
</span>
                        <?php
                        } else {
                            printf( '<input onclick="changeShippingMethod(this, true)" type="hidden" name="shipping_method[%1$d]" data-index="%1$d" id="shipping_method_%1$d_%2$s" value="%3$s" class="shipping_method" />', $index, esc_attr( sanitize_title( $method->id ) ), esc_attr( $method->id ) ); // WPCS: XSS ok.
                        ?>
                            <span class="shippingMethod__free">ZA DARMO (dzisiaj)</span>
                            <span class="shippingMethod__deliveryTime">
                                    <img class="shippingMethod__deliveryTime__img" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/time.png'; ?>"  alt="czas" />
                                    1 - 4 dni roboczych
</span>
                        <?php
                        }
                        printf( '<label for="shipping_method_%1$s_%2$s">
%3$s
</label>', $index, esc_attr( sanitize_title( $method->id ) ), wc_cart_totals_shipping_method_label( $method ) ); // WPCS: XSS ok.
                        do_action( 'woocommerce_after_shipping_rate', $method, $index );
                        ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="woocommerce-shipping-destination">
                <div class="shippingDestinationFirstLine">
                    <h2 class="shippingDestinationHeader">
                        Wybrany punkt odbioru
                    </h2>

                    <button class="changeShippingDestination">
                        Zmień
                        <div class="changeShippingDestination__imgWrapper">
                            <img class="changeShippingDestination__img" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/draw.svg'; ?>" alt="zmien-adres-dostawy" />
                        </div>
                    </button>
                </div>

                <div class="shippingDestinationFlex">
                    <img class="shippingDestinationIcon" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/map-locator.svg'; ?>" alt="adres-dostawy" />

                    <?php
                    if ( $formatted_destination ) {
                        // Translators: $s shipping destination.
                        echo '<strong>' . esc_html( $formatted_destination ) . '</strong>';
                        $calculator_text = esc_html__( 'Change address', 'woocommerce' );
                    } else {
                        echo '<strong>' . wp_kses_post( apply_filters( 'woocommerce_shipping_estimate_html', __( 'Shipping options will be updated during checkout.', 'woocommerce' ) ) ) . '</strong>';
                    }
                    ?>
                </div>
            </div>

        <?php
        elseif ( ! $has_calculated_shipping || ! $formatted_destination ) :
            if ( is_cart() && 'no' === get_option( 'woocommerce_enable_shipping_calc' ) ) {
                echo wp_kses_post( apply_filters( 'woocommerce_shipping_not_enabled_on_cart_html', __( 'Shipping costs are calculated during checkout.', 'woocommerce' ) ) );
            } else {
                echo wp_kses_post( apply_filters( 'woocommerce_shipping_may_be_available_html', __( 'Enter your address to view shipping options.', 'woocommerce' ) ) );
            }
        elseif ( ! is_cart() ) :
            echo wp_kses_post( apply_filters( 'woocommerce_no_shipping_available_html', __( 'There are no shipping options available. Please ensure that your address has been entered correctly, or contact us if you need any help.', 'woocommerce' ) ) );
        else :
            // Translators: $s shipping destination.
            echo wp_kses_post( apply_filters( 'woocommerce_cart_no_shipping_available_html', sprintf( esc_html__( 'No shipping options were found for %s.', 'woocommerce' ) . ' ', '<strong>' . esc_html( $formatted_destination ) . '</strong>' ) ) );
            $calculator_text = esc_html__( 'Enter a different address', 'woocommerce' );
        endif;
        ?>

        <?php echo '<p class="woocommerce-shipping-contents"><small>' . esc_html( $package_details ) . '</small></p>'; ?>

        <?php if ( $show_shipping_calculator ) : ?>
            <?php woocommerce_shipping_calculator( $calculator_text ); ?>
        <?php endif; ?>
</tr>
