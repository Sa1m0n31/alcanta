<?php
get_header();
?>

<!-- MOBILE LANDING -->
<header class="mobileLanding mobileLanding--collection">
    <img class="mobileLanding__img" src="<?php echo get_field('zdjecie_kolekcji'); ?>" alt="alcanta-kolekcja" />
    <header class="collectionHeader">
        <h2 class="collection">
            Collection
        </h2>
        <h1 class="collectionName">
            <?php echo the_title(); ?>
        </h1>
        <h3 class="collectionSubtitle">
            <?php echo get_field('podtytul'); ?>
        </h3>
    </header>
</header>

<!-- SHOP ITEMS -->
<main class="collectionItems">
    <section class="collectionItems__filters d-none d-md-block">
        <section class="collectionItems__filters__section">
            <h3 class="collectionItems__filters__header">
                Kategoria
            </h3>
                <?php
                $orderby = 'name';
                $order = 'asc';
                $hide_empty = false;
                $cat_args = array(
                    'orderby'    => $orderby,
                    'order'      => $order,
                    'hide_empty' => $hide_empty,
                );

                $product_categories = get_terms( 'product_cat', $cat_args );

                if( !empty($product_categories) ){
                    echo '
 
<ul class="colectionItems__categoryList">';
                    foreach ($product_categories as $key => $category) {
                        if($category->name != 'Bez kategorii') {
                            echo '
 
<li>';
                            echo '<a href="'.get_term_link($category).'" >';
                            echo $category->name;
                            echo '</a>';
                            echo '</li>';
                        }
                    }
                    echo '</ul>
 
 
';
                }
                ?>
        </section>
        <section class="collectionItems__filters__section">
            <h3 class="collectionItems__filters__header">
                Rozmiar
            </h3>
            <div class="collectionItems__sizes">
                <button class="collectionItems__circle">XS</button>
                <button class="collectionItems__circle">S</button>
                <button class="collectionItems__circle">M</button>
                <button class="collectionItems__circle">L</button>
                <button class="collectionItems__circle">XL</button>
            </div>
        </section>
    </section>

    <?php
        $args = array(
                'post_type' => 'product',
                'posts_per_page' => -1
        );

        $query = new WP_Query($args);
        $collection = get_the_title();

        if($query->have_posts()) {
            ?>
    <ul class="products columns-2">
        <?php
            while($query->have_posts()) {
                $query->the_post();
                if(get_field('kolekcja') == $collection) {
                ?>
        <li class="product type-product post-527 status-publish first instock product_cat-bluzy has-post-thumbnail shipping-taxable purchasable product-type-variable">
            <a href="<?php echo the_permalink(); ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
                <?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $query->post->ID ), 'single-post-thumbnail' );?>
                <img width="324" height="486" src="<?php echo $image[0]; ?>" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt="" loading="lazy">
                <h2 class="woocommerce-loop-product__title"><?php echo the_title(); ?></h2>
                <span class="price">
                <?php $price = get_post_meta( get_the_ID(), '_price', true ); ?>
                <p><?php echo wc_price( $price ); ?></p>
                </span>
            </a>
            <a href="<?php echo the_permalink(); ?>" data-quantity="1" class="button product_type_variable add_to_cart_button" data-product_id="527" data-product_sku="" aria-label="Wybierz opcje dla „Bluza”" rel="nofollow">Wybierz opcje</a>
        </li>

    <?php
            }
            }
       ?>
    </ul>
       <?php
        }
    ?>



<!--    --><?php //echo do_shortcode('[products columns="2"]'); ?>

    <img class="collection__alcantaLogo" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/alcanta-logo-collection.png'; ?>" alt="alcanta" />
</main>



<?php
get_footer();
?>
