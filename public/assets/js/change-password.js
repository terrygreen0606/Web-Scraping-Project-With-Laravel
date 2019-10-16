$(document).ready(function () {

    $('#form-user-change-password').submit(function (event) {
        event.preventDefault();
    });

    $('#form-user-change-password').validate({
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
            old_password: {
                required: true,
                minlength: 8,
                maxlength: 64
            },
            password: {
                required: true,
                minlength: 8,
                maxlength: 64
            },
            password_confirmation: {
                required: true,
                equalTo: "#password"
            }
        },
        messages: {
            old_password: {
                required: "Required",
                minlength: "Mininum length is 8",
                maxlength: "Maxinum length is 64"
            },
            password: {
                required: "Required",
                minlength: "Mininum length is 8",
                maxlength: "Maxinum length is 64"
            },
            password_confirmation: {
                required: "Required",
                equalTo: "Passowrds mismatches"
            }
        },
        submitHandler: function (form) {

            $.ajax({
                url: $(form).attr('action'),
                method: 'post',
                timeout: 20000,
                data: {
                    _token:         $(form).find('[name="_token"]').val(),
                    user_id:        $(form).find('[name="user_id"]').val(),
                    old_password:   $(form).find('[name="old_password"]').val(),
                    password:       $(form).find('[name="password"]').val(),
                    password_confirmation: $(form).find('[name="password_confirmation"]').val(),
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
                        
                        if (response.data.action == 'reloadPage') {
                            window.showCSRFModal();
                        }

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