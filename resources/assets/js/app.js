/**
 * Created by antoine on 08/10/15.
 */
(function ($) {
    $.getScript('/assets/js/en_js.min.js', function (data, textStatus, jqxhr) {

        var $fade = $('<div id="fade">');
        var $alert = $(".alert");
        $fade.css({
            'display': 'none',
            'background-color': '#ccc',
            'position': 'fixed',
            'left': 0,
            'top': 0,
            'height': '100%',
            'width': '100%',
            'opacity': 0.8,
            'zIndex': 999
        }).appendTo('body');

        $(".btn-delete").on('click', function (e) {
            $fade.fadeIn();
            $alert.fadeIn();
            var $message = $("<small>" + lang.en.delete + "</small>");
            var $title = $alert.find('h1');
            var $form = $(this).parent('form');
            $message.appendTo($title);

            $('a', $alert).on('click', function (e) {
                if ($(this).data('response') == 'yes') {
                    $form.submit();
                }
                $fade.fadeOut();
                $alert.fadeOut(function () {
                    $message.empty();
                });
                e.preventDefault();
            });
            e.preventDefault();
        });
        var $css = {
            'color': 'red'
        };
        if ($('.flash-message') != null) {
            $('.flash-message').css($css).fadeOut(1600, function () {
                $(this).empty();
            });
        }
    });
    // menu responsive
    var $cross = $('<span class="cross">X</span>');
    var $nav__sidebar = $("#nav__sidebar");

    $("#header__icon").on('click', function (e) {
        e.preventDefault();
        $nav__sidebar.toggleClass('nav__visible');
        //$nav__sidebar.toggle('slow');
    });


})(jQuery);