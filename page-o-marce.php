<?php
get_header();
?>

<!-- MOBILE LANDING -->
<header class="mobileLanding mobileLanding--collection">
    <img class="mobileLanding__img" src="<?php echo get_field('zdjecie_w_tle'); ?>" alt="alcanta-kolekcja" />
    <header class="collectionHeader">
        <h1 class="collectionName">
           O marce ALCANTA
        </h1>
    </header>
</header>
<main class="pageAboutUs">
    <?php
        echo the_content();
    ?>

    <button class="desktopLanding__btn mobileLanding__btn button--animated pageAboutUs__btn">
        <a class="button__link" href="<?php echo get_page_link(get_page_by_title('Sklep')->ID); ?>">
            Przejdź do sklepu
        </a>
    </button>
</main>

<?php
get_footer();
?>