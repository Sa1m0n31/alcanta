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
    for(i=0; i<4; i++) {
        if(i !== n) {
            mobileArrowOfHiddenItem = mobileMenuItems[i].children[0];
            mobileSubmenuOfHiddenItem = mobileMenuItems[i].children[1];
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
    const mobileArrowOfCurrentItem = mobileMenuItems[n].children[0];
    const mobileSubmenuOfCurrentItem = mobileMenuItems[n].children[1];
    const mobileItemsOfSubmenuOfCurrentItem = Array.prototype.slice.call(mobileSubmenuOfCurrentItem.children);

    if(window.getComputedStyle(mobileMenuItems[n].children[1]).getPropertyValue('height') !== '0px') {
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
        //setTimeout(() => {
            mobileArrowOfCurrentItem.style.transform = "rotate(-90deg)";
            mobileSubmenuOfCurrentItem.style.height = "auto";
            mobileSubmenuOfCurrentItem.style.marginBottom = "30px";
            mobileSubmenuOfCurrentItem.style.marginTop = "15px";

            //setTimeout(() => {
                mobileItemsOfSubmenuOfCurrentItem.forEach(item => {
                    item.style.opacity = "1";
                });
           // }, 500);
       // }, 500);
    }
}

/* Popup */
document.querySelector(".preorderPopupOpen").addEventListener("click", () => {
    setTimeout(() => {
        document.querySelector(".preorderPopup__input").blur();
    }, 500);
    console.log("Listener");
});

/* Frontpage carousel */
// const emblaContainer = document.querySelector(".carousel__content");
// const emblaOptions = {
//     dragFree: true,
//     // containScroll: "trimSnaps",
//     draggable: true,
//     selectedClass: '',
//     slidesToScroll: 4,
//     startIndex: 0
// };
//
// const embla = EmblaCarousel(emblaContainer, emblaOptions);
//
// /* Frontpage carousel progress bar */
// const progressBar = document.querySelector(".carousel__progressBar");
// let progressBarWidth = 0;
//
// embla.on('scroll', () => {
//     progressBarWidth = (embla.scrollProgress() * 100) + "%";
//     progressBar.style.width = progressBarWidth;
// });

/* Frontpage carousel - glider */
// new Glider(document.querySelector('.glider'), {
//     slidesToShow: 1.8,
//     slidesToScroll: 4,
//     draggable: true
// });

// const swiper = new Swiper('.swiper-container', {
//    freeMode: true,
//    slidesPerView: 2
// });

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