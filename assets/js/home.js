var popupContainer = document.querySelector('.popup-container');
var cardId;
var isPostLiked = false;

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

function displayPostContent(element) {
    popupContainer.style.display = 'block';
    toggleBodyScroll();

    var imageSource = element.querySelector('img').src;
    var prompt = element.querySelector('p').textContent;
    var postedBy = element.querySelector('h4').textContent;
    cardId = element.id;
    document.getElementById("popupImage").src = imageSource;
    document.getElementById("popupPrompt").textContent = prompt;
    document.getElementById("postedBy").textContent = postedBy;

    $.post("../ajax/get_likes.php", { postId: cardId }, function (result) {
        $(".like-count").html(result.amountOfLikes);

        if (result.isLiked) {
            $(".fa-thumbs-up").addClass("liked");
        }
    }, "json");

    $.post("../ajax/get_dislikes.php", { postId: cardId }, function (result) {
        $(".dislike-count").html(result.amountOfDislikes);

        if (result.isDisliked) {
            $(".fa-thumbs-down").addClass("disliked");
        }
    }, "json");

    $.post("../ajax/get_post_title.php", { postId: cardId }, function (result) {
        $(".post-title").html(result);
    });

    $.post("../ajax/get_comments.php", { postId: cardId }, function (result) {
        $(".comment-list").append(result);
    });
}

function saveImage() {
    var imgSrc = document.getElementById('popupImage').src;
    var prompt = document.getElementById("popupPrompt").textContent;

    $.post("../ajax/save_image.php", { postId: cardId, imgSrc: imgSrc, prompt: prompt }, function (data) {
        console.log(data);
        showInfoMessage(data);
    });
}

function showInfoMessage(message) {
    var infoMessage = document.querySelector('.info-message');
    infoMessage.textContent = message;
    infoMessage.style.display = 'block';

    setTimeout(function () {
        infoMessage.style.display = 'none';
    }, 3000); // Visa meddelandet i tre sekunder. 
}

function toggleBodyScroll() {
    document.body.classList.toggle('no-scroll');
}


function addComment() {
    var comment = document.getElementById("commentText").value;

    $.post("../ajax/add_comment.php", { postId: cardId, comment: comment }, function (result) {
        $(".comment-list").append(result);
        document.getElementById("commentText").value = "";
    });
}

function deleteComment(id) {
    comment = document.getElementById(id);
    console.log(id);
    comment.remove();

    $.post("../ajax/remove_comment.php", { commentId: id })
}

function resetComments() {
    $(".comment-list").empty();
}

function handleLiking() {
    $.post("../ajax/like.php", { postId: cardId }, function (response) {
        var amountOfLikes = $(".like-count");
        var amountOfDislikes = $(".dislike-count");

        var likeCount = response.likeCount;
        var dislikeCount = response.dislikeCount;
        var isPostLiked = response.isLiked;

        amountOfLikes.text(likeCount);
        amountOfDislikes.text(dislikeCount);

        if (isPostLiked) {
            $(".fa-thumbs-down").removeClass("disliked");
            $(".fa-thumbs-up").addClass("liked");
        }
        else {
            $(".fa-thumbs-down").removeClass("disliked");
            $(".fa-thumbs-up").removeClass("liked");
        }

    }, "json");
}

function handleDisliking() {
    $.post("../ajax/dislike.php", { postId: cardId }, function (response) {
        var amountOfLikes = $(".like-count");
        var amountOfDislikes = $(".dislike-count");

        var likeCount = response.likeCount;
        var dislikeCount = response.dislikeCount;

        amountOfLikes.text(likeCount);
        amountOfDislikes.text(dislikeCount);

        if (response.isDisliked) {
            $(".fa-thumbs-down").addClass("disliked");
            $(".fa-thumbs-up").removeClass("liked");
        }
        else {
            $(".fa-thumbs-down").removeClass("disliked");
            $(".fa-thumbs-up").removeClass("liked");
        }
    }, "json");
}

function closePopup() {
    popupContainer.style.display = 'none';
    toggleBodyScroll();
}

function deleteCard(event, element) {
    //Hindrar från onclick="displayPostContent" att exekveras när vi trycker på "x-mark" ikonen. 
    event.stopPropagation();

    //Hämta parent div med klassen .card
    while (element && !element.classList.contains('card')) {
        element = element.parentElement;
    }

    if (element) {
        parentId = element.id;
        element.remove();
    }

    // $.post("../ajax/remove_post.php", { postId: parentId })

    $.post("../ajax/remove_post.php", { postId: parentId })
        .done(function (data) {
            console.log("Post removal successful. Response:", data);
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.error("Error while removing post:", textStatus, errorThrown);
        });
}


$(document).ready(function () {
    $("#searchBar").keyup(function () {
        var search = document.getElementById("searchBar").value;

        $.post("../ajax/search.php", { search: search }, function (result) {
            $('.posts-container .card').remove();
            $(".posts-container").append(result);
        });
    });
});