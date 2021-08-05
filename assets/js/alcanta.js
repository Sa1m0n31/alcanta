/* Remove default shipping method */
const shippingMethodInput = document.querySelector("li>.shipping_method");
if(shippingMethodInput) {
    shippingMethodInput.removeAttribute("checked");
}

/* TY page after newsletter or waitlist */
const tyMain =  document.querySelector(".thankYou__main");
if(tyMain) {
    const isWaitlist = localStorage.getItem('alcanta-waitlist');
    if(isWaitlist) {
        const tyHeader = document.querySelector(".thankYou__header");
        const tySubheader = document.querySelector(".thankYou__subheader");
        const tyText = document.querySelector(".thankYou__text");

        tyHeader.textContent = "Gratulujemy!"
        tySubheader.textContent = "Zapis na waitlistę zakończony powodzeniem.";
        tyText.style.visibility = "hidden";
        tyText.style.height = "0";
        tyText.style.margin = "30px";
        localStorage.removeItem("alcanta-waitlist");
    }
}

const waitlistSubmit = document.querySelector(".pum .tnp-submit");
const newsletterSubmit = document.querySelector(".beforeFooter__submitBtn");

if(waitlistSubmit) {
    waitlistSubmit.addEventListener("click", () => {
        localStorage.setItem('alcanta-waitlist', 'waitlist');
    });
}

if(newsletterSubmit) {
    newsletterSubmit.addEventListener("click", () => {
        localStorage.removeItem("alcanta-waitlist");
    });
}

/* Single product gallery */
const galleryItems = document.querySelectorAll(".desktopGallery>div>.woocommerce-product-gallery__image>a>img");
const galleryMain = document.querySelector(".woocommerce-product-gallery__wrapper>div>a>img");

if(galleryItems) {
    galleryItems.forEach(item => {
        item.addEventListener("click", () => {
            const imageSrc = item.getAttribute("src");
            const mainSrc = galleryMain.getAttribute("srcset");

            galleryMain.setAttribute("srcset", imageSrc);
            galleryMain.setAttribute("src", imageSrc);

            item.setAttribute("srcset", mainSrc);
            item.setAttribute("src", mainSrc);

            const magnifierEffectImg = document.querySelector(".zoomImg");

            console.log(magnifierEffectImg);

            magnifierEffectImg.setAttribute("srcset", imageSrc);
        });
    });
}

/* Shipping and payment */
const checkoutHeader1 = document.querySelector(".col-1>.checkoutHeader");
const shippingMethods = document.querySelector("#shipping_method");
const shippingDestination = document.querySelector(".woocommerce-shipping-destination");
const checkoutSection1Btn = document.querySelector(".button--skipToPayment");
const shippingFields = document.querySelector(".woocommerce-shipping-fields");
const checkoutSection1 = [checkoutHeader1, shippingMethods, shippingDestination, shippingFields, checkoutSection1Btn];

const checkoutPayment = document.querySelectorAll(".paymentWrapper");
const checkoutFields = document.querySelector(".woocommerce-billing-fields");
const checkoutSection2Btn = document.querySelector(".button--payment");
const checkoutSection2 = [checkoutPayment[0], checkoutPayment[1], checkoutFields, checkoutSection2Btn];

const checkoutSectionHeader1 = document.querySelector(".checkoutHeader--first");
const checkoutSectionHeader2 = document.querySelector(".checkoutHeader--second");

const test = document.querySelectorAll(".paymentWrapper>.paymentWrapper");

const paymentNextSection = () => {
    console.log(test);
    /* Skip to 2nd section */
    checkoutSection1.forEach(item => {
        item.style.display = "none";
    });
    checkoutSection2.forEach(item => {
        console.log(item);
        item.style.display = "block";
    });

    checkoutSectionHeader1.classList.remove("checkoutHeader--active");
    checkoutSectionHeader2.classList.add("checkoutHeader--active");
    window.scrollTo({
        top: 0,
        behavior: "smooth"
    });
}

const paymentFirstSection = () => {
    /* Back to 1st section */
    checkoutSection2.forEach(item => {
        item.style.display = "none";
    });
    checkoutSection1.forEach((item, index) => {
        if(index === 0) {
            item.style.display = "flex";
        }
        else {
            item.style.display = "block";
        }
    });

    checkoutSectionHeader1.classList.add("checkoutHeader--active");
    checkoutSectionHeader2.classList.remove("checkoutHeader--active");
    window.scrollTo({
        top: 0,
        behavior: "smooth"
    });
}

const btnSkipToPayment = document.querySelector(".button--skipToPayment");
if(btnSkipToPayment) {
    if(!sessionStorage.getItem('shipping-section')) {

    }
}

/* AJAX update cart count */
const addToCartButton = document.querySelector(".single_add_to_cart_button");
if(addToCartButton) {
    addToCartButton.addEventListener("click", (e) => {
        const cartCount = document.querySelector("#cartCount2");
        let total = parseInt(cartCount.textContent);
        total++;
        cartCount.textContent = total.toString();
    });
}

/* Check if top bar exists */
const topBar = document.querySelector(".topBar");
if(!topBar) {
    document.querySelector(".mobileHeader").style.top = "0";
    document.querySelector(".mobileLanding").style.marginTop = "50px";
}
else {
    /* If top bar exists - add animation */

}

/* Check if countdown button exists */
const countdownBtn = document.querySelector(".stickyCountdown");
if(countdownBtn) {
    if(window.getComputedStyle(countdownBtn).getPropertyValue("display") !== "none") {
        document.querySelector(".footer").style.marginBottom = "60px";
    }
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
            mobileArrowOfHiddenItem = mobileMenuItems[i].children[1].children[0];
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
    const mobileArrowOfCurrentItem = mobileMenuItems[n].children[1].children[0];
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
            mobileArrowOfCurrentItem.style.transform = "rotate(90deg)";
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

const changeShippingAddress = (fullAddress) => {

    console.log(fullAddress);
    const address = fullAddress.split(",")[0];
    const postalCode = fullAddress.match(/\d{2}-\d{3}/i)[0];
    let city;

    if(fullAddress.split(",")[1]) {
        city = fullAddress.split(",")[1].replace(postalCode + " ", "");
    }
    else {
        city = fullAddress.split(",")[2].replace(postalCode + " ", "");
    }

    console.log(city);
    console.log(postalCode);

    const shippingAddressInput = document.getElementById("shipping_address_1");
    const shippingPostalCodeInput = document.getElementById("shipping_postcode");
    const shippingCityInput = document.getElementById("shipping_city");

    shippingAddressInput.setAttribute("value", address);
    shippingPostalCodeInput.setAttribute("value", postalCode);
    shippingCityInput.setAttribute("value", city);
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
const addedToCartPopupWrapper = document.querySelector(".addedToCartPopupWrapper");

const showAddedToCartPopup = () => {
    addedToCartPopupWrapper.style.opacity = "1";
    addedToCartPopupWrapper.style.zIndex = "11";
    addedToCartPopup.style.visibility = "visible";
    addedToCartPopup.style.opacity = "12";
    document.querySelector(".addedToCartPopup__meta--size>span").textContent = currentSelectedVariable;
    document.querySelector(".addedToCartPopup .addedToCartPopup--price").textContent = sessionStorage.getItem('alcanta-current-price') + ' PLN';

}

const closeAddedToCartPopup = () => {
    addedToCartPopupWrapper.style.opacity = "0";
    addedToCartPopupWrapper.style.zIndex = "-1";
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
    //event.preventDefault();

    console.log(name);

    const kurierMethod = document.querySelector("#shipping_method_0_flat_rate1");
    const inpostMethod = document.querySelector("#shipping_method_0_flat_rate2");

    if(name.value === "flat_rate:2") {
        /* InPost */
        inpostMethod.setAttribute("checked", "checked");
        kurierMethod.removeAttribute("checked");
    }
    else {
        /* Kurier */
        kurierMethod.setAttribute("checked", "checked");
        inpostMethod.removeAttribute("checked");
    }

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
            const couponArrow = document.querySelectorAll(".couponInner__arrow");

            if(sessionStorage.getItem('alcanta-coupon-open')) {
                couponInner.forEach(item => {
                   item.style.display = "none";
                   item.style.marginTop = "0";
                });
                couponArrow.forEach(item => {
                   item.style.transform = "rotate(-90deg)";
                });
                sessionStorage.removeItem('alcanta-coupon-open');
            }
            else {
                sessionStorage.setItem('alcanta-coupon-open', 'T');
                couponInner.forEach(item => {
                    item.style.display = "flex";
                    item.style.marginTop = "40px";
                });
                couponArrow.forEach(item => {
                   item.style.transform = "rotate(-270deg)";
                });
            }
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
        arrowToRotate.style.transform = "rotate(90deg)";
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

/* Add class to buttons */
const returnToShopButtons = document.querySelectorAll(".return-to-shop");
returnToShopButtons.forEach(item => {
   item.classList.add("button--animated");
   item.style.position = "relative";
});

/* Add class to newsletter submit */
const newsletterSubmitBtn = document.querySelector(".beforeFooter .tnp-submit");
if(newsletterSubmitBtn) {
    newsletterSubmitBtn.classList.add("beforeFooter__submitBtn");
    newsletterSubmitBtn.classList.add("mobileLanding__btn");
    newsletterSubmitBtn.classList.add("button--animated");
    newsletterSubmitBtn.classList.add("button--animated--black");
}

/* Helper function */
const isInArray = (el, arr) => {
    return arr.filter(item => {
        return item === el;
    }).length > 0;
}

/* Size filter */
const sizeFilter = (n) => {
    /* Toggle border color */
    if(n) {
        if(sessionStorage.getItem('alcanta-filters-off')) {
            sessionStorage.removeItem('alcanta-filters-off');

            const sizes = [
               'alcanta-rozmiar-xs',
                'alcanta-rozmiar-s',
                'alcanta-rozmiar-m',
                'alcanta-rozmiar-l',
                'alcanta-rozmiar-xl'
            ];

            /* Unfilter */
            sizes.forEach((item, index) => {
               sessionStorage.removeItem(item);
            });
        }

        const circleToToggle = document.querySelector(`.collectionItems__circle:nth-of-type(${n})`);
        let isClicked;
        let key;
        switch(n) {
            case 1:
                key = 'alcanta-rozmiar-xs';
                break;
            case 2:
                key = 'alcanta-rozmiar-s';
                break;
            case 3:
                key = 'alcanta-rozmiar-m';
                break;
            case 4:
                key = 'alcanta-rozmiar-l';
                break;
            case 5:
                key = 'alcanta-rozmiar-xl';
                break;
            default:
                break;
        }
        isClicked = sessionStorage.getItem(key);

        if(!isClicked) {
            circleToToggle.style.border = "2px solid rgb(217,73,38)";
            sessionStorage.setItem(key, "T");
        }
        else {
            sessionStorage.removeItem(key);
            circleToToggle.style.border = "1px solid #a3a3a3";
        }
    }

    /* Check current visible sizes */
    let visibleSizes = [];
    if(sessionStorage.getItem('alcanta-rozmiar-xs')) visibleSizes.push('alcanta-rozmiar-xs');
    if(sessionStorage.getItem('alcanta-rozmiar-s')) visibleSizes.push('alcanta-rozmiar-s');
    if(sessionStorage.getItem('alcanta-rozmiar-m')) visibleSizes.push('alcanta-rozmiar-m');
    if(sessionStorage.getItem('alcanta-rozmiar-l')) visibleSizes.push('alcanta-rozmiar-l');
    if(sessionStorage.getItem('alcanta-rozmiar-xl')) visibleSizes.push('alcanta-rozmiar-xl');

    /* Toggle products */
    const allProducts = document.querySelectorAll(".products>.product");
    allProducts.forEach(item => {
        const elementClasses = item.classList;
        let isProductVisible = false;
        elementClasses.forEach(classItem => {
            if(isInArray(classItem.toLowerCase(), visibleSizes)) {
                isProductVisible = true;
                return 0;
            }
        });

        if(!isProductVisible) {
            item.style.opacity = "0";
            setTimeout(() => {
                item.style.display = "none";
            }, 500);
        }
        else {
            item.style.opacity = "1";
            item.style.display = "block";
        }
    });
}


if(document.querySelector(`.collectionItems__circle:first-of-type`)) {
    /* First load - set all sizes checked */
    sessionStorage.setItem('alcanta-rozmiar-xs', 'T');
    sessionStorage.setItem('alcanta-rozmiar-s', 'T');
    sessionStorage.setItem('alcanta-rozmiar-m', 'T');
    sessionStorage.setItem('alcanta-rozmiar-l', 'T');
    sessionStorage.setItem('alcanta-rozmiar-xl', 'T');
    sessionStorage.setItem('alcanta-filters-off', 'T');

    // document.querySelectorAll(".collectionItems__circle").forEach(item => {
    //    item.style.border = "2px solid rgb(217,73,38)";
    // });
}

/* Add animations classes to buttons */
const newsletterBtn = document.querySelector(".newsletterDesktop>.newsletterDesktop__form>.tnp-subscription>form>.tnp-field-button");
if(newsletterBtn) {
    newsletterBtn.classList.add("desktopLanding__btn");
    newsletterBtn.classList.add("mobileLanding__btn");
    newsletterBtn.classList.add("button--animated");
    newsletterBtn.classList.add("button--animated--black");
}

const addToCartBtn = document.querySelector(".single_add_to_cart_button");
if(addToCartBtn) {
    addToCartBtn.classList.add("desktopLanding__btn");
    addToCartBtn.classList.add("mobileLanding__btn");
    addToCartBtn.classList.add("button--animated");
}

const checkoutBtn = document.querySelector(".woocommerce-checkout .place-order .button");
if(checkoutBtn) {
    checkoutBtn.classList.add("desktopLanding__btn");
    checkoutBtn.classList.add("mobileLanding__btn");
    checkoutBtn.classList.add("button--animated");
}