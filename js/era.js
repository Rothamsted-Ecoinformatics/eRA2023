//function that has to do with the display of images
function myFunction() {
    // Get the snackbar DIV
    var x = document.getElementById("snackbar");

    // Add the "show" class to DIV
    x.className = "show";

    // After 4 seconds, remove the show class from DIV
    setTimeout(function () {
        x.className = x.className.replace("show", "");
    }, 4000);
}

$(document).ready(function () {
    var $lightbox = $('#lightbox');

    $('[data-target="#lightbox"]').on('click', function (event) {
        var $img = $(this).find('img'),
            src = $img.attr('src'),
            alt = $img.attr('alt'),
            css = {
                'maxWidth': $(window).width() - 100,
                'maxHeight': $(window).height() - 100
            };

        $lightbox.find('.close').addClass('hidden');
        $lightbox.find('img').attr('src', src);
        $lightbox.find('img').attr('alt', alt);
        $lightbox.find('img').css(css);
    });

    $lightbox.on('shown.bs.modal', function (e) {
        var $img = $lightbox.find('img');

        $lightbox.find('.modal-dialog').css({
            'width': $img.width()
        });
        $lightbox.find('.close').removeClass('hidden');
    });

    $('#mapid').on('shown.bs.collapse', function (e) {
        map.invalidateSize(true);
    })


    let selectedTab = window.location.hash;

    $('.nav-link[href="' + selectedTab + '"]').trigger('click');
    // $('.nav-item[href="' + selectedTab + '"]' ).trigger('click');
    //$('.nav.active').trigger('click');
    //location.reload();
    //$('.nav-link[href="' + selectedTab + '"]' ).parent().addClass('active');

    $('.copyToClipboard').click(function () {

        let copyText = $(this).attr('address');
        let x = document.getElementById("div1");
        navigator.clipboard.writeText(copyText);

        // Show a div for a bit of feedback 

        x.style.display = 'inline';
        // remove it after 3 seconds
        setTimeout(function () {
            x.style.display = 'none';
        }, 3000);
    })

});