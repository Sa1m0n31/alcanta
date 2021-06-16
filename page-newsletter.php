<?php
get_header();
?>

<section class="thankYou__carousel">
    <div class="carousel__content swiper-container">
        <div class="carousel__embla swiper-wrapper">

            <?php
            $carousel_options = array(
                'post_type' => 'homepage_carousel'
            );
            $carousel_query = new WP_Query($carousel_options);
            if($carousel_query->have_posts()) {
                while($carousel_query->have_posts()) {
                    $carousel_query->the_post();
                    ?>

                    <a class="carousel__item" href="<?php echo get_field('link'); ?>">
                        <img class="carousel__item__img" src="<?php echo get_field('zdjecie'); ?>" alt="carousel-item" />
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

<main class="thankYou__main">
    <img class="thankYou__logo" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/alcanta-logo-checkout.png'; ?>" alt="alcanta" />

    <h1 class="thankYou__header">
        Potwierdzono Twoją subskrypcje
    </h1>
    <h2 class="thankYou__subheader">
        Dziękujemy za zapisanie się do newslettera!
    </h2>

    <p class="thankYou__text">
        Od dziś nie ominą Cię już nowości w naszym sklepie
    </p>

    <button class="moreInfoBtn button--animated button--thankYou">
        <a class="button__link" href="<?php echo get_home_url(); ?>">
            Przejdź do strony głównej
        </a>
    </button>
</main>

<?php
get_footer();
?>
