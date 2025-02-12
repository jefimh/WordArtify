window.addEventListener('load', function () {
    const heroText = document.querySelector('.hero-text');
    heroText.classList.add('fade-in');

    const navItems = document.querySelectorAll('.nav-menu li');

    navItems.forEach(function (item, index) {
        item.style.willChange = 'opacity, transform';
        item.style.opacity = 0;
        item.style.transform = `translateY(-${(index + 1) * 20}px)`;

        requestAnimationFrame(function () {
            item.style.transition = 'opacity 0.7s ease-out, transform 0.7s ease-out';
            item.style.opacity = 1;
            item.style.transform = 'none';
        });
    });
});

const ctaButton = document.querySelector('.cta-button');
ctaButton.addEventListener('mouseenter', function () {
    TweenMax.to(ctaButton, 0.3, {
        scale: 1.05
    });
});

ctaButton.addEventListener('mouseleave', function () {
    TweenMax.to(ctaButton, 0.3, {
        scale: 1
    });
});