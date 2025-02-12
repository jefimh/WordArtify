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

function handlePasswordVisibility() {
    var passwordInputElement = document.getElementById("password");
    var confirmPasswordInputElement = document.getElementById("passwordConfirmation");
    var eye = document.getElementById("eye");

    if (passwordInputElement.type === "password") {
        passwordInputElement.type = "text";
        confirmPasswordInputElement.type = "text";
        eye.classList.remove("fa-sharp", "fa-solid", "fa-eye");
        eye.classList.add("fa-solid", "fa-eye-slash");
    }
    else {
        confirmPasswordInputElement.type = "password";
        passwordInputElement.type = "password";
        eye.classList.remove("fa-solid", "fa-eye-slash");
        eye.classList.add("fa-sharp", "fa-solid", "fa-eye");
    }
}