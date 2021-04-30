<?php
get_header();
?>

<!-- LANDING -->
<main class="mobileLanding">
    <img class="mobileLanding__img" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/preorder-landing.png'; ?>" alt="alcanta-pierwszenstwo-zakupu" />
</main>

<!-- CAROUSEL -->
<section class="carousel">
    <div class="carousel__content swiper-container">
        <div class="carousel__embla swiper-wrapper">
            <a class="carousel__item" href=".">
                <img class="carousel__item__img" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/slider-locked.png'; ?>" alt="carousel-item" />
            </a>

            <a class="carousel__item" href=".">
                <img class="carousel__item__img" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/slider-locked.png'; ?>" alt="carousel-item" />
            </a>

            <a class="carousel__item" href=".">
                <img class="carousel__item__img" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/slider-locked.png'; ?>" alt="carousel-item" />
            </a>

            <a class="carousel__item" href=".">
                <img class="carousel__item__img" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/slider-locked.png'; ?>" alt="carousel-item" />
            </a>
        </div>
    </div>

    <span class="carousel__progressBarContainer">
            <span class="carousel__progressBar"></span>
        </span>

    <button class="mobileLanding__btn button--gofry button--animated button--animated--black">
        <a class="button__link" href=".">
            Kolekcja GOFRY >
        </a>
    </button>
</section>

<!-- BASIC COLLECTION -->
<section class="frontpageBasicCollection">
    <img class="frontpageBasicCollection__logo" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/alcanta-logo-elegant.png'; ?>" alt="alcanta-logo" />

    <img class="frontpageBasicCollection__img" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/girl.png'; ?>" alt="girl" />

    <button class="moreInfoBtn moreInfoBtn--basicCollection button--animated">
        <a class="button__link" href=".">
            Kolekcja basic >
        </a>
    </button>
</section>


<?php
get_footer();
?>
