<?php
get_header();
?>

<main class="thankYou__main thankYou__main--desktop d-desktop">
    <img class="thankYou__logo" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/alcanta-logo-checkout.png'; ?>" alt="alcanta" />

    <h1 class="thankYou__header">
        Dziękujemy za dokonanie zakupu
    </h1>
    <h2 class="thankYou__subheader">
        W naszym sklepie
    </h2>

    <p class="thankYou__text">
        Zamówienie zostało przekazane do realizacji. Wkrótce znajdzie się u Ciebie.
    </p>

    <button class="moreInfoBtn button--animated button--thankYou">
        <a class="button__link" href="<?php echo get_home_url(); ?>">
            Przejdź do strony głównej
        </a>
    </button>
</main>

<main class="thankYou__main d-mobile">
    <img class="thankYou__logo" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/alcanta-logo-checkout.png'; ?>" alt="alcanta" />

    <h1 class="thankYou__header">
        Dziękujemy za dokonanie zakupu
    </h1>
    <h2 class="thankYou__subheader">
        W naszym sklepie
    </h2>

    <p class="thankYou__text">
        Zamówienie zostało przekazane do realizacji. Wkrótce znajdzie się u Ciebie.
    </p>

    <button class="moreInfoBtn button--animated button--thankYou">
        <a class="button__link" href="<?php echo get_home_url(); ?>">
            Przejdź do strony głównej
        </a>
    </button>
</main>
<section class="thankYou__carousel">
    <h2 class="thankYou__carouselHeader d-desktop">
        Zobacz jeszcze
    </h2>
    <div class="carousel__content swiper-container">
        <div class="carousel__embla swiper-wrapper">

            <?php
            function isTYCarousel($arr) {
                for($i=0; $i<sizeof($arr); $i++) {
                    if($arr[$i] == "Thank you page") return true;
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
                    if(isTYCarousel(get_field('miejsce'))) {
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

<?php
get_footer();
?>
