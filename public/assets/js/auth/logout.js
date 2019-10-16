$(document).ready(function () {

    $('.button.logout').on('click', function (event) {

        var $btn = $(this),
            url = $btn.data('logout-url') || null,
            scrfToken = $('[name="csrf-token"]').attr('content');

        $.ajax({
            url     :url,
            method: 'POST',
            data: {
                _token: scrfToken
            },
            success: function (response) {

                // begin init message box
                $message = $('.ui.message.logout:first');
                $messageList = $message.find('.list:first');

                $message.addClass('hidden');

                $messageList.html('');
                // end init message box


                // begin set messages status
                if (response.success == true) {

                    $message
                        .addClass('positive')
                        .removeClass('negative');

                } else if (response.success == false) {

                    $message
                        .addClass('negative')
                        .removeClass('positive');

                }
                // end set messages status


                // begin show messages
                if (response.messages.length > 0) {

                    $.each(response.messages, function (index, value) {
                        $messageList.append('<li>' + value + '</li>');
                    });

                    $message.removeClass('hidden')

                }
                // end show messages


                // begin take action form response
                if (response.data.action != undefined) {

                    if (response.data.action == 'redirect') {
                        document.location.href = response.data.url;
                    }

                }
                    // end take action form response
                
            }
        })

    });

});