(() => {
    'use strict';

    const ATTR_SLIDER = 'data-testimonials-slider';
    const ATTR_TRACK = 'data-testimonials-track';
    const ATTR_SLIDE = 'data-testimonials-slide';
    const ATTR_PREV = 'data-testimonials-prev';
    const ATTR_NEXT = 'data-testimonials-next';
    const CLASS_ACTIVE = 'mai-testimonials__slide--active';
    const AUTOPLAY_INTERVAL = 5000;

    function initSlider(slider) {
        const track = slider.querySelector(`[${ATTR_TRACK}]`);
        if (!track) return;

        const slides = Array.from(track.querySelectorAll(`[${ATTR_SLIDE}]`));
        if (slides.length < 2) return;

        let current = 0;
        let timer = null;

        function goTo(index) {
            slides[current].classList.remove(CLASS_ACTIVE);
            slides[current].setAttribute('aria-hidden', 'true');
            current = (index + slides.length) % slides.length;
            slides[current].classList.add(CLASS_ACTIVE);
            slides[current].setAttribute('aria-hidden', 'false');
        }

        function startAutoplay() {
            timer = setInterval(() => goTo(current + 1), AUTOPLAY_INTERVAL);
        }

        function stopAutoplay() {
            clearInterval(timer);
        }

        const prevBtn = slider.querySelector(`[${ATTR_PREV}]`);
        const nextBtn = slider.querySelector(`[${ATTR_NEXT}]`);

        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                stopAutoplay();
                goTo(current - 1);
                startAutoplay();
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                stopAutoplay();
                goTo(current + 1);
                startAutoplay();
            });
        }

        slider.addEventListener('mouseenter', stopAutoplay);
        slider.addEventListener('mouseleave', startAutoplay);
        slider.addEventListener('focusin', stopAutoplay);
        slider.addEventListener('focusout', startAutoplay);

        startAutoplay();
    }

    document.querySelectorAll(`[${ATTR_SLIDER}]`).forEach(initSlider);
})();
