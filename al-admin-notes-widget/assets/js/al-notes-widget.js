jQuery(document).ready(function ($) {
    var $boxNotes = $('#al_notes'); // widget id. automatically added
    var $boxTitle = $('h2 span', $boxNotes).text();

    // Clean up note field and change widget title to default value
    $('.clear', $boxNotes).click(function () {
        $('textarea', $boxNotes).text('');
        $('h2 span', $boxNotes)
            .text('Field is cleaned. Save the result!')
            .css('color', 'orangered');
    });

    // Sending the form
    $('form', $boxNotes).submit(function (e) {
        e.preventDefault();

        $nonce_security = $('#jq-nonce-security', $boxNotes).val();

        // Animation
        $boxNotes.animate({opacity: 0.5}, 300);

        // Ajax request
        var $request = $.post(
            ajaxurl,
            {
                action: 'al_notes',
                al_note_content: $('textarea', $boxNotes).val(),
                nonce_security: $nonce_security
            }
        );

        // Processing successful request
        $request.done(function ($response) {
            var $title = $('h2 span', $boxNotes).text($response.data.message);
            if ($response.success) {
                $title.css('color', 'green');
            } else {
                $title.css('color', 'orangered');
            }
        });

        // Processing failed request
        $request.fail(function () {
            $('h2 span', $boxNotes)
                .text('An unexpected error!')
                .css('color', 'red');
        });

        // Processing in both cases
        $request.always(function () {
            $boxNotes.animate(
                {opacity: 1},
                300,
                '',
                function () {
                    setTimeout(function () {
                        $('h2 span', $boxNotes)
                            .text($boxTitle)
                            .attr('style', '');
                    }, 2000);
                }
            );
        });

    });
});