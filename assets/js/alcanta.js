/* Check if top bar exists */
const topBar = document.querySelector(".topBar");
if(!topBar) {
    document.querySelector(".mobileHeader").style.top = "0";
    document.querySelector(".mobileLanding").style.marginTop = "50px";
}

/* Mobile menu */
const mobileMenu = document.querySelector(".mobileMenu");
const mobileMenuChildren = document.querySelectorAll(".mobileMenu > *");
const mobileBottomMenuChildren = document.querySelectorAll(".mobileMenu__bottomMenu>li");

const openMobileMenu = () => {
    mobileMenu.style.transform = "scaleX(1)";
    setTimeout(() => {
        mobileMenuChildren.forEach(item => {
            item.style.opacity = "1";
        });
        mobileBottomMenuChildren.forEach(item => {
            item.style.opacity = "1";
        })
    }, 500);
}

const closeMobileMenu = () => {
    mobileMenuChildren.forEach(item => {
        item.style.opacity = "0";
    });
    mobileBottomMenuChildren.forEach(item => {
        item.style.opacity = "0";
    });
    setTimeout(() => {
        mobileMenu.style.transform = "scaleX(0)";
    }, 500);
}

/* Mobile menu accordion */
const mobileMenuItems = document.querySelectorAll(".mobileMenu__item");

const mobileMenuAccordion = n => {
    /* Close other items */
    let i;
    let mobileArrowOfHiddenItem, mobileSubmenuOfHiddenItem, mobileItemsOfSubmenuOfHiddenItem;
    for(i=0; i<mobileMenuItems.length; i++) {
        if(i !== n) {
            mobileArrowOfHiddenItem = mobileMenuItems[i].children[1];
            mobileSubmenuOfHiddenItem = mobileMenuItems[i].children[2];
            mobileItemsOfSubmenuOfHiddenItem = Array.prototype.slice.call(mobileSubmenuOfHiddenItem.children);

            mobileItemsOfSubmenuOfHiddenItem.forEach(item => {
                item.style.opacity = "0";
            });

            mobileArrowOfHiddenItem.style.transform = "rotate(180deg)";
            mobileSubmenuOfHiddenItem.style.height = "0";
            mobileSubmenuOfHiddenItem.style.margin = "0";
        }
    }

    /* Open n-th item */
    const mobileArrowOfCurrentItem = mobileMenuItems[n].children[1];
    const mobileSubmenuOfCurrentItem = mobileMenuItems[n].children[2];
    const mobileItemsOfSubmenuOfCurrentItem = Array.prototype.slice.call(mobileSubmenuOfCurrentItem.children);

    if(window.getComputedStyle(mobileMenuItems[n].children[2]).getPropertyValue('height') !== '0px') {
        /* Submenu already opened */
        mobileItemsOfSubmenuOfCurrentItem.forEach(item => {
            item.style.opacity = "0";
        });

        setTimeout(() => {
            mobileArrowOfCurrentItem.style.transform = "rotate(180deg)";
            mobileSubmenuOfCurrentItem.style.height = "0";
            mobileSubmenuOfCurrentItem.style.margin = "0";
        }, 500);
    }
    else {
        /* Submenu closed */
            mobileArrowOfCurrentItem.style.transform = "rotate(-90deg)";
            mobileSubmenuOfCurrentItem.style.height = "auto";
            mobileSubmenuOfCurrentItem.style.marginBottom = "30px";
            mobileSubmenuOfCurrentItem.style.marginTop = "15px";

            mobileItemsOfSubmenuOfCurrentItem.forEach(item => {
                item.style.opacity = "1";
            });
    }
}

/* Popup */
const popup = document.querySelector(".preorderPopupOpen");
if(popup) {
    popup.addEventListener("click", () => {
        setTimeout(() => {
            document.querySelector(".preorderPopup__input").blur();
        }, 500);
    });
}

/* Single product carousel */
const emblaOptions = {
    dragFree: true,
    draggable: true,
    containScroll: "trimSnaps"
};

const singleProductCarousel = document.querySelector(".single-product .related");
let singleProductEmbla;
if(singleProductCarousel) {
    singleProductEmbla = EmblaCarousel(singleProductCarousel, emblaOptions);
}

/* Frontpage carousel */
const emblaContainer = document.querySelector(".carousel__content");

let embla;
if(emblaContainer) {
    embla = EmblaCarousel(emblaContainer, emblaOptions);
}

/* Frontpage carousel progress bar */
const progressBar = document.querySelector(".carousel__progressBar");
let progressBarWidth = 0;

if(embla) {
    embla.on('scroll', () => {
        progressBarWidth = (embla.scrollProgress() * 100) + "%";
        progressBar.style.width = progressBarWidth;
    });
}

/* Sticky count down - check if countdown is over */
const stickyCountdown = document.querySelector(".stickyCountdown");

const checkIfTimerExists = () => {
    if(!document.querySelector(".ycd-simple-countdown-number")) {
        stickyCountdown.style.display = "none";
        clearTimeout(timerTimeout);
    }
}

checkIfTimerExists();

let timerTimeout = setTimeout(() => {
    checkIfTimerExists();
}, 10000);

/* Collection locked - FAQ open and close */
const faqAnswers = document.querySelectorAll(".collectionLocked__faq__answer");
const faqMinuses = document.querySelectorAll(".collectionLocked__faq__minus");
const faqPluses = document.querySelectorAll(".collectionLocked__faq__plus");

const faqToggle = n => {
    const faqToToggle = faqAnswers[n];
    const minusToToggle = faqMinuses[n];
    const plusToToggle = faqPluses[n];

    if(window.getComputedStyle(faqToToggle).getPropertyValue('display') === 'none') {
        faqToToggle.style.display = "block";
        plusToToggle.style.display = "none";
        minusToToggle.style.display = "block";
    }
    else {
        faqToToggle.style.display = "none";
        minusToToggle.style.display = "none";
        plusToToggle.style.display = "block";
    }
}

/* Collection locked - calculate time to presentation */
const MS_PER_DAY = 1000 * 60 * 60 * 24;

const presentationTime = document.querySelector(".presentationTime");
if(presentationTime) {
    const presentationTimeContent = presentationTime.textContent;
    const presentationDateArray = presentationTimeContent.split(":");
    const currentDate = new Date();
    const presentationDate = new Date(parseInt(presentationDateArray[0]),
                                        parseInt(presentationDateArray[1])-1,
                                        parseInt(presentationDateArray[2]),
                                        parseInt(presentationDateArray[3]),
                                        parseInt(presentationDateArray[4]),
                                        0
        );

    const dateDifference = presentationDate - currentDate;
    const daysDifference = Math.floor(dateDifference / MS_PER_DAY);
    let hoursDifference = presentationDate.getHours() - currentDate.getHours();
    let minutesDifference = presentationDate.getMinutes() - currentDate.getMinutes();
    if(minutesDifference < 0) {
           minutesDifference = 60 + minutesDifference;
           hoursDifference -= 1;
           if(hoursDifference < 0) {
               hoursDifference = 24 + hoursDifference;
           }
    }
    else {
        if(hoursDifference < 0) {
            hoursDifference = 24 + hoursDifference;
        }
    }

    const daysDifferenceSpan = document.querySelectorAll(".daysDifference");
    const hoursDifferenceSpan = document.querySelectorAll(".hoursDifference");
    const minutesDifferenceSpan = document.querySelectorAll(".minutesDifference");

    daysDifferenceSpan.forEach(item => {
        item.textContent = daysDifference.toString();
    });
    hoursDifferenceSpan.forEach(item => {
        item.textContent = hoursDifference.toString();
    });
    minutesDifferenceSpan.forEach(item => {
        item.textContent = minutesDifference.toString();
    });
}

/* Added to cart popup */
/* Avoiding invoke add to cart action on button click */
(function ($) {

    $(document).on('click', '.single_add_to_cart_button', function (e) {
        e.preventDefault();

        var $thisbutton = $(this),
            $form = $thisbutton.closest('form.cart'),
            id = $thisbutton.val(),
            product_qty = $form.find('input[name=quantity]').val() || 1,
            product_id = $form.find('input[name=product_id]').val() || id,
            variation_id = $form.find('input[name=variation_id]').val() || 0;

        var data = {
            action: 'woocommerce_ajax_add_to_cart',
            product_id: product_id,
            product_sku: '',
            quantity: product_qty,
            variation_id: variation_id,
        };

        $(document.body).trigger('adding_to_cart', [$thisbutton, data]);

        $.ajax({
            type: 'post',
            url: wc_add_to_cart_params.ajax_url,
            data: data,
            beforeSend: function (response) {
                $thisbutton.removeClass('added').addClass('loading');
            },
            complete: function (response) {
                $thisbutton.addClass('added').removeClass('loading');
            },
            success: function (response) {

                if (response.error && response.product_url) {
                    window.location = response.product_url;
                    return;
                } else {
                    $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);
                    showAddedToCartPopup();
                }
            },
        });

        return false;
    });
})(jQuery);

const addedToCartPopup = document.querySelector(".addedToCartPopup");

const showAddedToCartPopup = () => {
    addedToCartPopup.style.visibility = "visible";
    addedToCartPopup.style.opacity = "1";
    document.querySelector(".addedToCartPopup__meta--size>span").textContent = currentSelectedVariable;
}

const closeAddedToCartPopup = () => {
    addedToCartPopup.style.opacity = "0";
    setTimeout(() => {
        addedToCartPopup.style.visibility = "hidden";
    }, 500);
}

/* Follow current selected variable */
let currentSelectedVariable = "";
const variableButtons = document.querySelectorAll(".variable-items-wrapper>li");

const checkCurrentVariableProduct = () => {
    variableButtons.forEach(item => {
        if(item.getAttribute("aria-checked") === "true") {
            currentSelectedVariable = item.getAttribute("title");
        }
    });
}

if(variableButtons) {
    variableButtons.forEach(item => {
        item.addEventListener("click", () => {
            setTimeout(() => {
                checkCurrentVariableProduct();
            }, 300);
        });
    });

    checkCurrentVariableProduct();
}

/* Change shipping method */
const changeShippingMethod = (name, isInput) => {
    event.preventDefault();

    /* Button to mark */
    let btnToMark, spanToMark, shippingMethod;
    if(isInput) {
        shippingMethod = name.value;
        btnToMark = document.getElementById(`btn_${name.value}`);
        spanToMark = document.getElementById(`span_${name.value}`);
    }
    else {
        btnToMark = name;
        let cutId = name.id.replace("btn_", "");
        shippingMethod = cutId;
        spanToMark = document.getElementById(`span_${cutId}`);
    }

    const shippingForm = document.querySelector(".shipping_address");
    /* Rozwijamy formularz adresu dostawy */
    if(shippingMethod === 'flat_rate:1') {
        shippingForm.style.display = "block";
        shippingForm.style.height = "auto";
    }
    else {
        shippingForm.style.display = "none";
        shippingForm.style.height = "0";
    }


    /* Gasimy buttony */
    const allBtns = document.querySelectorAll(".shippingMethodBtn");
    allBtns.forEach(item => {
        item.style.background = "transparent";
        item.style.border = "1px solid #171a1d";
    });
    const appBtns__content = document.querySelectorAll(".shippingMethodBtn>span");
    appBtns__content.forEach(item => {
       item.style.display = "none";
    });

    /* Przechodzimy po wszystkich buttonach i sprawdzamy czy name pasuje do id */
    btnToMark.style.background = "#d94926";
    btnToMark.style.border = "none";
    spanToMark.style.display = "block";
}

/* Checkout cart */
const checkoutCarousel = document.querySelector(".checkoutCarousel");
const toggleCheckoutCart = () => {
    const checkoutCartHeader = document.querySelector(".checkoutCart__header__h");
    const checkoutCartArrow = document.querySelector(".checkoutCart__header__arrow");
    if(window.getComputedStyle(checkoutCarousel).getPropertyValue('visibility') === 'visible') {
        checkoutCarousel.style.visibility = "hidden";
        checkoutCarousel.style.height = "0";
        checkoutCartArrow.style.transform = "rotate(270deg)";
        checkoutCartHeader.textContent = "Pokaż przedmioty w koszyku";
    }
    else {
        checkoutCarousel.style.visibility = "visible";
        checkoutCarousel.style.height = "auto";
        checkoutCartArrow.style.transform = "rotate(90deg)";
        checkoutCartHeader.textContent = "Ukryj przedmioty w koszyku";
    }
}

/* Single product gallery carousel */
const emblaOptionsSingleProductGallery = {
    containScroll: "trimSnaps"
};

const singleProductGalleryCarousel = document.querySelector(".carousel--singleGallery");
let singleProductGalleryEmbla;
const singleProductGalleryButtons = document.querySelectorAll(".singleGalleryDot");
const singleProductGalleryButtonsInner = document.querySelectorAll(".singleGalleryDotInner");
if(singleProductGalleryCarousel) {
    singleProductGalleryEmbla = EmblaCarousel(singleProductGalleryCarousel, emblaOptionsSingleProductGallery);
    let currentIndex;

    singleProductGalleryEmbla.on('settle', () => {
        /* Turn off all dots */
        currentIndex = singleProductGalleryEmbla.selectedScrollSnap();
        singleProductGalleryButtons.forEach((item, index) => {
            item.style.border = "none";
            singleProductGalleryButtonsInner[index].style.background = "#c8c8c8";
        });

        /* Turn on current dot */
        singleProductGalleryButtons[currentIndex].style.border = "1px solid #171a1a";
        singleProductGalleryButtonsInner[currentIndex].style.background = "#d94926";
    });
}

const singleProductGalleryChangeSlide = (el) => {
    const n = parseInt(el.id.split("-")[1]);
    singleProductGalleryEmbla.scrollTo(n);
}

/* AJAX request to change shipping address */
if(document.querySelector(".changeShippingAddressBtn")) {
    document.querySelector(".changeShippingAddressBtn").addEventListener("click", (event) => {
        event.preventDefault();

        wp.ajax.post( "get_shipping_address", {
            address: sessionStorage.getItem('alcanta-paczkomat')
        } )
            .done(function(response) {

                /* Zmieniamy wartosc pola zawierajacego adres dostawy */
                document.querySelector(".shippingDestinationFlex>strong").textContent = response;

            });
    });
}

/* Open coupon input */
const couponInnerBtn = document.querySelectorAll(".couponInner__btn");
if(couponInnerBtn) {
    couponInnerBtn.forEach(item => {
        item.addEventListener("click", (event) => {
            event.preventDefault();

            const couponInner = document.querySelectorAll(".couponInner");
            couponInner.forEach(item => {
                item.style.display = "flex";
                item.style.marginTop = "40px";
            })
        });
    })
}

/* Change shipping destination */
const changeShippingDestinationBtn = document.querySelector(".changeShippingDestination");
if(changeShippingDestinationBtn) {
    changeShippingDestinationBtn.addEventListener("click", (event) => {
        event.preventDefault();

        if(window.getComputedStyle(document.querySelector(".shipping_address")).getPropertyValue('display') === 'block') {
            /* Current shipping method - Kurier */
            const el = document.querySelector(".shipping__address__href");
            setTimeout(() => {
                el.scrollIntoView({
                    top: -200,
                    behavior: "smooth"
                });
            }, 300);
        }
        else {
            /* Current shipping method = Paczkomaty */
            openModal();
        }
    });
}

/* Toggle before footer dropdown */
const toggleBeforeFooter = (n) => {
    const dropdownToToggle = document.querySelector(`.beforeFooter__list__item:nth-child(${n})>.beforeFooter__dropdown`);
    const arrowToRotate = document.querySelector(`.beforeFooter__list__item:nth-child(${n})>button>.mobileMenu__item__arrow`);

    if(window.getComputedStyle(dropdownToToggle).getPropertyValue('display') === 'none') {
        dropdownToToggle.style.display = "block";
        arrowToRotate.style.transform = "rotate(-90deg)";
    }
    else {
        dropdownToToggle.style.display = "none";
        arrowToRotate.style.transform = "rotate(180deg)";
    }
}

/* Toggle payment methods */
const togglePaymentMethods = () => {
    const paymentMethods = document.querySelector(".beforeFooter__paymentMethods");
    const paymentMethodsBtn = document.querySelector(".beforeFooter__dropdown__paymentBtn");

    if(window.getComputedStyle(paymentMethods).getPropertyValue('display') === 'none') {
        paymentMethods.style.display = "block";
        paymentMethodsBtn.textContent = "Ukryj wszystkie";
    }
    else {
        paymentMethods.style.display = "none";
        paymentMethodsBtn.textContent = "Pokaż wszystkie";
    }
}

/* Change newsletter input placeholders */
const newsletterInputs = document.querySelectorAll(".tnp-email");
if(newsletterInputs) {
    newsletterInputs.forEach(item => {
        item.placeholder = 'Tu wpisz swój adres e-mail';
    })
}