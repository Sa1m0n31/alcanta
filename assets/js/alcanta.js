/* Mobile menu */
const mobileMenu = document.querySelector(".mobileMenu");
const mobileMenuChildren = document.querySelectorAll(".mobileMenu > *");

const openMobileMenu = () => {
    mobileMenu.style.transform = "scaleX(1)";
    setTimeout(() => {
        mobileMenuChildren.forEach(item => {
            item.style.opacity = "1";
        });
    }, 500);
}

const closeMobileMenu = () => {
    mobileMenuChildren.forEach(item => {
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
        setTimeout(() => {
            mobileArrowOfCurrentItem.style.transform = "rotate(-90deg)";
            mobileSubmenuOfCurrentItem.style.height = "auto";
            mobileSubmenuOfCurrentItem.style.marginBottom = "30px";
            mobileSubmenuOfCurrentItem.style.marginTop = "15px";

            setTimeout(() => {
                mobileItemsOfSubmenuOfCurrentItem.forEach(item => {
                    item.style.opacity = "1";
                });
            }, 500);
        }, 500);
    }
}

/* Frontpage carousel */
const emblaContainer = document.querySelector(".carousel__content");
const emblaOptions = {
    dragFree: true,
    containScroll: "trimSnaps"
};
const embla = EmblaCarousel(emblaContainer, emblaOptions);

/* Frontpage carousel progress bar */
const progressBar = document.querySelector(".carousel__progressBar");
let progressBarWidth = 0;

embla.on('scroll', () => {
    progressBarWidth = (embla.scrollProgress() * 100) + "%";
    progressBar.style.width = progressBarWidth;
})