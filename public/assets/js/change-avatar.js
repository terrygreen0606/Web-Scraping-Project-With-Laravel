$(document).ready(function () {
    
    var cropper,
        $stage = null;

    initCropStage();

    /***** Begin avatar image select *****/
    $(document).on('change', '#avatar-file', function () {

        var $fileInput = $(this),
            file = $(this)[0].files[0],
            reader = new FileReader();

        $fileInput.val('');

        $stage.data('avatar-changed', true);
        $stage.data('file', file);
        $('.change-avatar-place-holder').remove();
        $('.change-avatar.image').removeClass('hidden');
        $('.submit-avatar').removeClass('disabled');

        reader.onload = function (event) {
            cropper.replace(event.target.result, false);
        }

        reader.readAsDataURL(file);

    });
    
    $(document).on('click', '.select-avatart-picture', function (event) {
       
        event.preventDefault();
        $('#avatar-file').click();

    });
    /***** End avatar image select *****/


    /**
     * Init the cropping stage
     * 
     * @return Void
     */
    function initCropStage() {

        $stage = $('#cropper-stage');

        $stage.cropper({
            aspectRatio: 1,
            viewMode: 3,
            zoomable: false,
            toggleDragModeOnDblclick: false,
        });

        cropper = $stage.data('cropper');
        
    }


    /***** Begin ajax *****/
    $('#form-user-change-avatar').submit(function (event) {
        event.preventDefault();
    });

    $('#form-user-change-avatar').validate({
        submitHandler: function (form) {

            var cropData = cropper.getData(true),
                cropCoordinate = [],
                formData = new FormData();

            
            // Begin provide data
            cropCoordinate['x'] = cropData.x;
            cropCoordinate['y'] = cropData.y;
            cropCoordinate['w'] = cropData.width;
            cropCoordinate['h'] = cropData.height;
            
            formData.append('_token', $(form).find('[name="_token"]').val());
            formData.append('user_id', $(form).find('[name="user_id"]').val());
            formData.append('image', $stage.data('file'));
            formData.append('crop[x]', cropData.x);
            formData.append('crop[y]', cropData.y);
            formData.append('crop[w]', cropData.width);
            formData.append('crop[h]', cropData.height);
            // End provide data


            $.ajax({
                url: $(form).attr('action'),
                method: 'post',
                timeout: 120000,
                processData: false,
                contentType: false,
                data: formData,
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
                    if (response.success == true) {

                        if (response.data.imageUrl != '') {
                            
                            $('.user-avatar img').attr('src', response.data.imageUrl + '?' + Math.random(100000));
                            $('.avatar.image').attr('src', response.data.imageUrl + '?' + Math.random(100000));
                            $('.submit-avatar').addClass('disabled');
                            cropper.destroy();

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
    /***** End ajax *****/

});