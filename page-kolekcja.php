<?php
get_header();
?>

<!-- LANDING -->
<header class="mobileLanding mobileLanding--collection">
    <img class="mobileLanding__img" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/preorder-landing.png'; ?>" alt="alcanta-pierwszenstwo-zakupu" />
    <header class="collectionHeader">
        <h2 class="collection">
            Collection
        </h2>
        <h1 class="collectionName">
            Gofry
        </h1>
        <h3 class="collectionSubtitle">
            It means idziemy smażyć
        </h3>
    </header>
</header>

<!-- SHOP ITEMS -->
<main class="collectionItems">
    <?php echo do_shortcode('[product_category category="kolekcja-1" columns="2"]'); ?>

    <img class="collection__alcantaLogo" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/alcanta-logo-collection.png'; ?>" alt="alcanta" />
</main>



<?php
get_footer();
?>
