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