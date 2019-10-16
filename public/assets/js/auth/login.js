$(document).ready(function () {

    $('#form-user-login').submit(function (event) {
        event.preventDefault();
    });

    $('#form-user-login').validate({
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
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
            }
        },
        messages: {
            email: {
                required: "Required",
                email: "Please enter a valid email"
            },
            password: {
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
                    email:      $(form).find('[name="email"]').val(),
                    password:   $(form).find('[name="password"]').val(),
                    remember:   $(form).find('[name="remember"]').prop('checked'),
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