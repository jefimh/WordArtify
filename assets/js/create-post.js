var popupContainer = document.querySelector('.popup-container');
var cardId;

//Animationer
window.addEventListener('load', function () {
    heroText = document.querySelector('.hero-text');
    heroText.classList.add('fade-in');

    navItems = document.querySelectorAll('.nav-container li');

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

function uploadImage() {
    var fileInput = document.getElementById("upload-photo");
    var file = fileInput.files[0];

    if (!file) {
        return;
    }

    var formData = new FormData();
    formData.append("image", file);

    $.ajax({
        url: "../../ajax/upload_image.php",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function () {
            location.reload();
        },
        error: function () {
            $("#response").html("Error uploading file.");
        },
    });
}