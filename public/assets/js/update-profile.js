$(document).ready(function () {

    $('#form-user-profile').submit(function (event) {
        event.preventDefault();
    });

    $('#form-user-profile').validate({
        errorPlacement: function (label, element) {
            $(element).parents('.field:first').append(label);
        },
        highlight: function (element) {
            $(element).parents('.field:first').addClass('error');
        },
        unhighlight: function (element) {
            $(element).parents('.field:first').removeClass('error');
        },
        rules: {
            name: {
                required: true,
            },
            family: {
                required: true,
            }
        },
        messages: {
            name: {
                required: "Required",
            },
            family: {
                required: "Required",
            }
        },
        submitHandler: function (form) {

            $.ajax({
                url: $(form).attr('action'),
                method: 'post',
                timeout: 20000,
                data: {
                    _token:     $(form).find('[name="_token"]').val(),
                    user_id:    $(form).find('[name="user_id"]').val(),
                    name:       $(form).find('[name="name"]').val(),
                    family:     $(form).find('[name="family"]').val(),
                },
                beforeSend: function () {
                    $(form).addClass('loading');
                },
                success: function (response) {

                    // begin init message box
                    $message = $(form).find('.ui.message:first');
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

                        if (response.data.action == 'reloadPage') {
                            window.showCSRFModal();
                        }

                    }
                    // end take action form response

                },
                complete: function () {
                    $(form).removeClass('loading');
                }
            });

        }
    });

});