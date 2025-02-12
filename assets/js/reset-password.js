//Animationer
window.addEventListener('load', function () {
    navItems = document.querySelectorAll('.nav-menu li');
    formSection = document.querySelector('.form-section');

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

    formSection.style.willChange = 'opacity, transform';
    formSection.style.opacity = 0;
    formSection.style.transform = 'translateY(50px)';

    requestAnimationFrame(function () {
        formSection.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
        formSection.style.opacity = 1;
        formSection.style.transform = 'none';
    });
});