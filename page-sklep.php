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
                    echo '
 
<li>';
                    echo '<a href="'.get_term_link($category).'" >';
                    echo $category->name;
                    echo '</a>';
                    echo '</li>';
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
    <?php echo do_shortcode('[product_category category="' . get_field('kategoria_produktu_do_wyswietlenia') . '" columns="2"]'); ?>

    <img class="collection__alcantaLogo" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/alcanta-logo-collection.png'; ?>" alt="alcanta" />
</main>



<?php
get_footer();
?>
