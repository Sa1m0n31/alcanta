<?php
get_header();
?>

<!-- LANDING -->
<header class="mobileLanding mobileLanding--collectionLocked">
    <img class="mobileLanding__img" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/collection-locked-header.png'; ?>" alt="alcanta-pierwszenstwo-zakupu" />
    <header class="collectionLocked__counter">
        <h2 class="collectionLocked__counter__header">
            Premiera - <?php echo get_field('data_premiery'); ?>
        </h2>
        <div class="collectionLocked__counter__buttonsContainer">
            <div class="collectionLocked__counter__countdown">
                <?php
                    $date = get_field('data_premiery');
                    $year = substr($date, 0, 4);
                    $month = substr($date, 5, 2);
                    $day = substr($date, 8, 2);
                    $hours = substr($date, 17, 2);
                    $minutes = substr($date, 20, 2);
                    ?>
                <span class="presentationTime">
                    <?php
                        echo $year . ":" . $month . ":" . $day . ":" . $hours . ":" . $minutes;
                    ?>
                </span>
                <span class="differenceTime">
                    <span class="daysDifference"></span> d :
                    <span class="hoursDifference"></span> h :
                    <span class="minutesDifference"></span> m
                </span>
            </div>

            <button class="collectionLocked__counter__waitlistBtn button--animated button--animated--black preorderPopupOpen">
                <span class="button__link">
                    WAITLIST
                    <img class="collectionLocked__btn__img" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/right-arrow.png'; ?>" alt="waitlist" />
                </span>
            </button>
        </div>
        <h3 class="collectionLocked__counter__caption">
            Kolekcja w dropie - mocno limitowana
        </h3>
    </header>
</header>

<!-- COLLECTION INFO -->
<main class="collectionLocked__desc">
    <h4 class="collectionLocked__desc__header">
        O kolekcji
    </h4>
    <div class="collectionLocked__desc__text">
        <?php echo get_field('o_kolekcji'); ?>
    </div>

    <img class="collectionLocked__img collectionLocked__img--marginBottom" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/locked-collection-1.png'; ?>" alt="<?php echo the_title(); ?>" />

    <h4 class="collectionLocked__desc__header">
        Motyw
    </h4>
    <div class="collectionLocked__desc__text">
        <?php echo get_field('motyw'); ?>
    </div>
</main>

<!-- COLLECTION CAROUSEL -->
<section class="carousel lockedCollection__carousel">
    <h3 class="lockedCollection__carousel__headerBefore">
        Locked collection
        <img class="lockedCollection__lockIcon" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/lock.svg'; ?>" alt="lock" />
    </h3>
    <h4 class="lockedCollection__carousel__subheaderBefore">
        Premiera już wkrótce
    </h4>


    <div class="carousel__content swiper-container">
        <div class="carousel__embla swiper-wrapper">

            <?php
                if(get_field('zdjecie_kolekcji_1')) {
                    ?>
                    <a class="carousel__item" href="<?php echo get_field('link_do_zdjecia_kolekcji_1'); ?>">
                        <img class="carousel__item__img" src="<?php echo get_field('zdjecie_kolekcji_1'); ?>" alt="carousel-item" />
                    </a>

                    <?php
                }
            ?>
            <?php
            if(get_field('zdjecie_kolekcji_2')) {
                ?>
                <a class="carousel__item" href="<?php echo get_field('link_do_zdjecia_kolekcji_2'); ?>">
                    <img class="carousel__item__img" src="<?php echo get_field('zdjecie_kolekcji_2'); ?>" alt="carousel-item" />
                </a>

                <?php
            }
            ?>
            <?php
            if(get_field('zdjecie_kolekcji_3')) {
                ?>
                <a class="carousel__item" href="<?php echo get_field('link_do_zdjecia_kolekcji_3'); ?>">
                    <img class="carousel__item__img" src="<?php echo get_field('zdjecie_kolekcji_3'); ?>" alt="carousel-item" />
                </a>

                <?php
            }
            ?>
            <?php
            if(get_field('zdjecie_kolekcji_4')) {
                ?>
                <a class="carousel__item" href="<?php echo get_field('link_do_zdjecia_kolekcji_4'); ?>">
                    <img class="carousel__item__img" src="<?php echo get_field('zdjecie_kolekcji_4'); ?>" alt="carousel-item" />
                </a>

                <?php
            }
            ?>

        </div>
    </div>

    <span class="carousel__progressBarContainer">
        <span class="carousel__progressBar"></span>
    </span>

    <h1 class="mobileLanding__header mobileLanding__header--lockedCollection">
        WAITLIST
    </h1>
    <h2 class="mobileLanding__subheader">
        Zdobądź pierszeństwo zakupu
    </h2>
    <button class="mobileLanding__btn button--animated button--animated--black preorderPopupOpen mobileLanding__btn--lockedCollection">
        <span class="button__link">
            Zapisuję się >
        </span>
    </button>
</section>

<!-- FAQ -->
<section class="collectionLocked__faq">
    <h3 class="collectionLocked__faq__header">
        Q&A
    </h3>

    <ul class="collectionLocked__faq__list">
        <li class="collectionLocked__faq__list__item">
            <label class="collectionLocked__faq__question">
                <span><?php echo get_field('pytanie_1'); ?></span>
                <button class="collectionLocked__faq__btn" onclick="faqToggle(0)">
                    <span class="collectionLocked__faq__plus">
                        +
                    </span>
                    <span class="collectionLocked__faq__minus">
                        -
                    </span>
                </button>
            </label>
            <span class="collectionLocked__faq__answer">
                <?php echo get_field('odpowiedz_1'); ?>
            </span>
        </li>
        <li class="collectionLocked__faq__list__item">
            <label class="collectionLocked__faq__question">
                <?php echo get_field('pytanie_2'); ?>
                <button class="collectionLocked__faq__btn" onclick="faqToggle(1)">
                    <span class="collectionLocked__faq__plus">
                        +
                    </span>
                    <span class="collectionLocked__faq__minus">
                        -
                    </span>
                </button>
            </label>
            <span class="collectionLocked__faq__answer">
                <?php echo get_field('odpowiedz_2'); ?>
            </span>
        </li>
        <li class="collectionLocked__faq__list__item">
            <label class="collectionLocked__faq__question">
                <?php echo get_field('pytanie_3'); ?>
                <button class="collectionLocked__faq__btn" onclick="faqToggle(2)">
                    <span class="collectionLocked__faq__plus">
                        +
                    </span>
                    <span class="collectionLocked__faq__minus">
                        -
                    </span>
                </button>
            </label>
            <span class="collectionLocked__faq__answer">
                <?php echo get_field('odpowiedz_3'); ?>
            </span>
        </li>
        <li class="collectionLocked__faq__list__item">
            <label class="collectionLocked__faq__question">
                <?php echo get_field('pytanie_4'); ?>
                <button class="collectionLocked__faq__btn" onclick="faqToggle(3)">
                    <span class="collectionLocked__faq__plus">
                        +
                    </span>
                    <span class="collectionLocked__faq__minus">
                        -
                    </span>
                </button>
            </label>
            <span class="collectionLocked__faq__answer">
                <?php echo get_field('odpowiedz_4'); ?>
            </span>
        </li>
        <li class="collectionLocked__faq__list__item">
            <label class="collectionLocked__faq__question"">
                <?php echo get_field('pytanie_5'); ?>
                <button class="collectionLocked__faq__btn" onclick="faqToggle(4)">
                    <span class="collectionLocked__faq__plus">
                        +
                    </span>
                    <span class="collectionLocked__faq__minus">
                        -
                    </span>
                </button>
            </label>
            <span class="collectionLocked__faq__answer">
                <?php echo get_field('odpowiedz_5'); ?>
            </span>
        </li>
    </ul>
</section>

<!-- WAITLIST -->
<section class="collectionLocked__waitlist">
    <h2 class="collectionLocked__waitlist__header">
        Zapisz się na waitlistę
    </h2>
    <h3 class="collectionLocked__waitlist__subheader">
        i otrzymaj wcześniejszy dostęp zakupu
    </h3>

    <h2 class="collectionLocked__counter__header">
        Premiera - <?php echo get_field('data_premiery'); ?>
    </h2>
    <div class="collectionLocked__counter__buttonsContainer">
        <div class="collectionLocked__counter__countdown">
            <span class="differenceTime">
                    <span class="daysDifference"></span> d :
                    <span class="hoursDifference"></span> h :
                    <span class="minutesDifference"></span> m
                </span>
        </div>

        <button class="collectionLocked__counter__waitlistBtn button--animated button--animated--black preorderPopupOpen">
                <span class="button__link">
                    WAITLIST
                    <img class="collectionLocked__btn__img" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/right-arrow.png'; ?>" alt="waitlist" />
                </span>
        </button>
    </div>
    <h3 class="collectionLocked__counter__caption">
        Kolekcja w dropie - mocno limitowana
    </h3>
</section>


<!-- BOTTOM IMAGE -->
<img class="collectionLocked__img" src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/images/alcanta/locked-collection-2.png'; ?>" alt="<?php echo the_title(); ?>" />


<?php
get_footer();
?>
