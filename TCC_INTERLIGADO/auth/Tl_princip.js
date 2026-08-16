/* ═══════════════════════════════════════════════════════════
   MEGA STORE — Luxury E-Commerce
   JavaScript: Interactions & Animations
   ═══════════════════════════════════════════════════════════ */

(function () {
    'use strict';

    // ─── DOM References ───
    const header       = document.getElementById('main-header');
    const hamburger    = document.getElementById('hamburger-btn');
    const mobileNav    = document.getElementById('mobile-nav');
    const mobileLinks  = document.querySelectorAll('.mobile-nav__link');
    const nlForm       = document.getElementById('newsletter-form');
    const nlFeedback   = document.getElementById('newsletter-feedback');
    const reveals      = document.querySelectorAll('.reveal');

    // ─── State ───
    let lastScrollY    = 0;
    let headerHidden   = false;
    const scrollThreshold = 80;

    /* ═════════════════════════════════════════════════════════
       1. STICKY HEADER — Hide on scroll down, show on scroll up
       ═════════════════════════════════════════════════════════ */
    function handleScroll() {
        const currentScrollY = window.scrollY;

        // Don't hide if we're near the very top
        if (currentScrollY <= scrollThreshold) {
            if (headerHidden) {
                header.classList.remove('header--hidden');
                headerHidden = false;
            }
            lastScrollY = currentScrollY;
            return;
        }

        // Scrolling DOWN → hide
        if (currentScrollY > lastScrollY && !headerHidden) {
            header.classList.add('header--hidden');
            headerHidden = true;
        }

        // Scrolling UP → show
        if (currentScrollY < lastScrollY && headerHidden) {
            header.classList.remove('header--hidden');
            headerHidden = false;
        }

        lastScrollY = currentScrollY;
    }

    // Throttle scroll handler for performance
    let scrollTicking = false;
    window.addEventListener('scroll', function () {
        if (!scrollTicking) {
            window.requestAnimationFrame(function () {
                handleScroll();
                scrollTicking = false;
            });
            scrollTicking = true;
        }
    }, { passive: true });


    /* ═════════════════════════════════════════════════════════
       2. MOBILE NAVIGATION
       ═════════════════════════════════════════════════════════ */
    if (hamburger && mobileNav) {
        hamburger.addEventListener('click', function () {
            hamburger.classList.toggle('is-active');
            mobileNav.classList.toggle('is-open');
            document.body.style.overflow = mobileNav.classList.contains('is-open') ? 'hidden' : '';
        });

        // Close mobile nav when a link is clicked
        mobileLinks.forEach(function (link) {
            link.addEventListener('click', function () {
                hamburger.classList.remove('is-active');
                mobileNav.classList.remove('is-open');
                document.body.style.overflow = '';
            });
        });
    }


    /* ═════════════════════════════════════════════════════════
       3. REVEAL ON SCROLL (Intersection Observer)
       ═════════════════════════════════════════════════════════ */
    function initRevealObserver() {
        if (!('IntersectionObserver' in window)) {
            // Fallback: show everything immediately
            reveals.forEach(function (el) {
                el.classList.add('is-visible');
            });
            return;
        }

        const observerOptions = {
            root: null,
            rootMargin: '0px 0px -60px 0px',
            threshold: 0.15
        };

        const observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target); // Only animate once
                }
            });
        }, observerOptions);

        reveals.forEach(function (el) {
            observer.observe(el);
        });
    }

    initRevealObserver();


    /* ═════════════════════════════════════════════════════════
       4. NEWSLETTER FORM
       ═════════════════════════════════════════════════════════ */
    if (nlForm) {
        nlForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const emailInput = document.getElementById('newsletter-email');
            const email = emailInput.value.trim();

            if (!email) return;

            // Simple feedback
            nlFeedback.textContent = 'Obrigado! Você receberá nossas novidades em breve.';
            nlFeedback.style.opacity = '1';
            emailInput.value = '';

            // Fade out feedback after 5 seconds
            setTimeout(function () {
                nlFeedback.style.opacity = '0';
                setTimeout(function () {
                    nlFeedback.textContent = '';
                }, 400);
            }, 5000);
        });
    }


    /* ═════════════════════════════════════════════════════════
       5. SMOOTH SCROLL FOR ANCHOR LINKS
       ═════════════════════════════════════════════════════════ */
    document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;

            const target = document.querySelector(targetId);
            if (!target) return;

            e.preventDefault();

            const headerOffset = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--header-h')) || 72;

            const elementPosition = target.getBoundingClientRect().top + window.scrollY;
            const offsetPosition = elementPosition - headerOffset - 20;

            window.scrollTo({
                top: offsetPosition,
                behavior: 'smooth'
            });
        });
    });


    /* ═════════════════════════════════════════════════════════
       6. HERO PARALLAX-LIKE SUBTLE EFFECT
       ═════════════════════════════════════════════════════════ */
    const heroImage = document.getElementById('hero-image');

    if (heroImage) {
        let parallaxTicking = false;

        window.addEventListener('scroll', function () {
            if (!parallaxTicking) {
                window.requestAnimationFrame(function () {
                    const scrolled = window.scrollY;
                    const heroHeight = window.innerHeight;

                    if (scrolled < heroHeight) {
                        const translateY = scrolled * 0.3;
                        heroImage.style.transform = 'translateY(' + translateY + 'px) scale(1)';
                    }
                    parallaxTicking = false;
                });
                parallaxTicking = true;
            }
        }, { passive: true });
    }


    /* ═════════════════════════════════════════════════════════
       7. PRODUCT CARD IMAGE LAZY FADE-IN
       ═════════════════════════════════════════════════════════ */
    function initImageFadeIn() {
        const productImages = document.querySelectorAll('.product-card__image');

        productImages.forEach(function (img) {
            img.style.opacity = '0';
            img.style.transition = 'opacity 0.8s ease';

            if (img.complete) {
                img.style.opacity = '1';
            } else {
                img.addEventListener('load', function () {
                    img.style.opacity = '1';
                });
            }
        });
    }

    initImageFadeIn();

})();