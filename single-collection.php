<?php
get_header();
?>

<!-- MOBILE LANDING -->
<header class="mobileLanding mobileLanding--collection">
    <img class="mobileLanding__img d-mobile" src="<?php echo get_field('zdjecie_kolekcji'); ?>" alt="alcanta-kolekcja" />
    <img class="mobileLanding__img d-desktop" src="<?php echo get_field('zdjecie_w_tle'); ?>" alt="alcanta-kolekcja" />
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
 
<li class="collectionItems__categoryList__li">';
                            if(get_category($category)->category_count != 0) {
                                echo '<a href="'.get_term_link($category).'" >';
                                echo $category->name;
                                echo '</a>';
                            }
                            else {
                                echo $category->name;
                            }
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
                <button class="collectionItems__circle" onclick="sizeFilter(1)">XS</button>
                <button class="collectionItems__circle" onclick="sizeFilter(2)">S</button>
                <button class="collectionItems__circle" onclick="sizeFilter(3)">M</button>
                <button class="collectionItems__circle" onclick="sizeFilter(4)">L</button>
                <button class="collectionItems__circle" onclick="sizeFilter(5)">XL</button>
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
                    $image = wp_get_attachment_image_src(get_post_thumbnail_id($query->post->ID), 'single-post-thumbnail');

                    $product_id = get_the_ID();
                    $product = wc_get_product($product_id);
                    $price = $product->get_price_html();

                    $sizes = explode(", ", $product->get_attribute("rozmiar"));
                ?>
                    <li class="product type-product status-publish has-post-thumbnail shipping-taxable purchasable product-type-variable <?php
                    for($i=0; $i<count($sizes); $i++) {
                        echo 'alcanta-rozmiar-' . $sizes[$i] . ' ';
                    }
                    ?>">
                        <a href="<?php echo the_permalink(); ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
                            <figure class="productImageWrapper">
                                <img src="<?php echo $image[0]; ?>" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail productImage-1" alt="" loading="lazy" />
                                <img src="<?php echo get_field('drugie_zdjecie'); ?>" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail d-desktop productImage-2" alt="" loading="lazy" />
                            </figure>
                            <h2 class="woocommerce-loop-product__title">
                                <?php echo the_title(); ?>
                            </h2>
                            <span class="price">
                                <?php echo $price; ?>
                            </span>
                        </a>
                    </li>

    <?php
            }
            }
       ?>
    </ul>
       <?php
        }
    ?>


    <img class="collection__alcantaLogo" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/alcanta-logo-collection.png'; ?>" alt="alcanta" />
</main>



<?php
get_footer();
?>
