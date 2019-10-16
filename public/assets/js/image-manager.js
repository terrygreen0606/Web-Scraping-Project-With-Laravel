window.uploadRequests = [];

$(document).ready(function () {

    /**
     * 
     * Begin show image browser 
     * 
     */
    $(document).on('click', "#choose-upload-image", function (event) {

        event.preventDefault();
        $('#upload_image_input').click();

    });


    /**
     * 
     * Begin image selected event
     * 
     */
    $(document).on('change', '#upload_image_input', function (event) {

        event.preventDefault();

        var fileInput = this,
            files = fileInput.files;

        
        // Show image preview and start uploader
        $.each(files, function (index, file) {

            showImagePreview(file);

        });

        // Reset image input
        $(fileInput).val('');

    });


    /**
     * 
     * Cancel upload image
     * 
     */
    $(document).on('click', '.cancel-upload', function (event) {

        event.preventDefault();

        var $btn = $(this),
            $image = $btn.parents('.uploaded-image:first'),
            imageId = $image.data('id'),
            $progressbar = $image.find('.progress');
        
        window.uploadRequests[imageId].abort();

        $progressbar.progress({
            percent: 0,
        });

        setTimeout(() => {
            $image.remove();
        }, 1000);

    });


    /**
     * 
     * Show remove image confirmation modal
     * 
     */
    $(document).on('click', '.show-remove-image-modal',function (event) {
        
        var $btn = $(this),
            $imageCard = $btn.parents('.card:first'),
            $modal = $('#remove-image-modal'),
            $imagePreview = $modal.find('.remove-image-preview'),
            $modalImageId = $modal.find('[name="image_id"]'),
            imageSrc = $imageCard.find('img:first').attr('src'),
            imageId = $imageCard.attr('data-id');
        
        $imagePreview.attr('src', imageSrc);
        $modalImageId.val(imageId);


        $modal
            .modal({
                closable: false,
                onApprove: function () {

                    var $modal = $(this),
                        imageId = $modal.find('[name="image_id"]').val(),
                        userId = $modal.find('[name="user_id"]').val(),
                        serviceUrl = $modal.attr('action'),
                        $approveBtn = $modal.find('.button.positive');

                    $.ajax({
                        url: serviceUrl,
                        method: 'POST',
                        timeout: 120000,
                        data: {
                            '_token': $('meta[name="csrf-token"]').attr('content'),
                            'image_id': imageId,
                            'user_id': userId
                        },
                        beforeSend: function () {
                            $approveBtn.addClass('loading disabled');
                        },
                        success: function (response) {

                            // begin init message box
                            $message = $modal.find('div.message:first');
                            $content = $message.find('.content:first');

                            $message.addClass('hidden');
                            $content.html('');
                            // end init message box

                            // begin set messages status
                            if (response.success == true) {

                                $message
                                    .addClass('positive')
                                    .removeClass('negative');

                                setTimeout(() => {
                                    $modal.modal('hide');
                                }, 2000);

                                reloadImageList();

                            } else if (response.success == false) {

                                $message
                                    .addClass('negative')
                                    .removeClass('positive');

                                $approveBtn.removeClass('disabled');

                                if (response.data.action == 'reloadPage') {
                                    window.showCSRFModal();
                                }

                            }
                            // end set messages status


                            // begin show messages
                            if (response.messages.length > 0) {

                                $.each(response.messages, function (index, value) {
                                    $content.html(value);
                                });

                                $message.removeClass('hidden');

                            }
                            // end show messages

                        },
                        complete: function () {
                            $approveBtn.removeClass('loading');
                        }
                    });

                    // prevent continue
                    return false;

                },
                onShow: function () {

                    window.modal = 'remove-image-modal';

                },
                onHidden: function () {

                    var $approveBtn = $modal.find('.button.positive'),
                        $message = $modal.find('div.message:first'),
                        $content = $message.find('.content:first');

                    $approveBtn.removeClass('disabled');
                    $message.addClass('hidden');
                    $content.html('');

                }
            })
            .modal('show');


    });


    /** Show edit image form  */
    $(document).on('click', '.show-edit-image-modal', function (event) {

        event.preventDefault();

        var $btn = $(this),
            $card = $btn.parents('.card:first'),
            $modal = $('#edit-image-modal'),
            image_id = $card.attr('data-id');
        
        $modal.modal({
                onShow: function () {

                    window.modal = 'edit-image-modal';

                },
            })
            .modal('show');
        
        console.log(image_id);

    })





    /**
     * 
     * Show image preview
     * 
     */
    function showImagePreview(file) {
        
        var reader = new FileReader();

        reader.onload = (event) => {

            var $imagesContainer = $('#uploaded-images'),
                imageDom,
                fileSource = event.target.result;

            // Append image into image list
            imageDom = createImageProgressbar(file.name, fileSource);
            $(imageDom).appendTo($imagesContainer);
            
            // Satrt to upload image
            uploadImage(imageDom, file);

        }

        reader.readAsDataURL(file);

    }


    /**
     * 
     * create image progress bar from template
     * 
     */
    function createImageProgressbar(name, source) {
        
        var progressbarId = randomString(32),
            imageTemplate = '<div class="uploaded-image" data-id="' + progressbarId + '">\
                                <img class="ui bottom aligned tiny image" src="' + source + '">\
                                <div class="details">\
                                    <h5 class="title">' + name + '</h5>\
                                    <div class="progress-bar-cancel">\
                                        <div class="ui tiny progress indicating" data-total="100">\
                                            <div class="bar"></div>\
                                        </div>\
                                        <button class="ui button icon cancel-upload">\
                                            <i class="icon times circle outline"></i>\
                                        </button>\
                                    </div>\
                                </div>\
                                <p class="ui pointing red basic label hidden error-message"></p>\
                                <div class="ui divider"></div>\
                            </div>';
        
        return imageTemplate;

    }


    /**
     * Upload image on the server
     * 
     * @param Objecdt imageDom 
     * @param File file
     */
    function uploadImage(imageDom, file) {
        
        var formData = new FormData(),
            $imageDom = $(imageDom),
            imageId = $imageDom.attr('data-id'),
            $fileInput = $('#upload_image_input'),
            userId = $fileInput.attr('data-user-id'),
            ajaxUrl = $fileInput.attr('data-url'),
            token = $('meta[name="csrf-token"]').attr('content');
            
        formData.append('image', file);
        formData.append('user_id', userId);
        formData.append('_token', token);


        window.uploadRequests[imageId] = $.ajax({

            xhr: () => {

                var xhr = $.ajaxSettings.xhr();

                xhr.upload.onprogress = (event) => {

                    if (event.lengthComputable) {

                        var currentProgress = (event.loaded / event.total) * 100,
                            $image = $('.uploaded-image[data-id="' + imageId + '"]'),
                            $progressbar = $image.find('.progress');

                        $progressbar.progress({
                                                percent: currentProgress
                                            });
                                            
                        if (currentProgress == 100) {

                            $image.find('.cancel-upload').remove();

                        }

                    }

                };

                return xhr;

            },
            url: ajaxUrl,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            timeout: 30000,
            success: function (response) {

                var $image = $('.uploaded-image[data-id="' + imageId + '"]'),
                    $progressbar = $image.find('.progress'),
                    $message = $image.find('.error-message');
                
                if (response.success == false) {

                    $progressbar.progress('set error');
                    $message
                        .removeClass('hidden')
                        .text(response.message[0]);

                }
                
            },
            error: function (error) {

                var $image = $('.uploaded-image[data-id="' + imageId + '"]'),
                    $progressbar = $image.find('.progress'),
                    $message = $image.find('.error-message');

                if (error.statusText != "abort") {

                    $progressbar.progress('set error');
                    $message.text('Upload has been failed').removeClass('hidden');

                }

            },
            complete: function () {
                delete window.uploadRequests[imageId];
            }
        });

    }


    /**
     * Generate random string
     * 
     * @param Integer length
     */
    function randomString(length) {

        var result = '',
            characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',
            charactersLength = characters.length;
        
        for (var i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }

        return result;

    }


    /**
     * Update pagination
     * 
     * @param Integer pageItems
     * @param Integer total
     */
    function updatePagination(currentPage ,pageItems, total) {

        var $pagination = $('#images-pagination');

        $pagination.pagination({
            currentPage: currentPage,
            items: total,
            itemsOnPage: pageItems,
            onPageClick: function (pageNumber) {

                loadImages(false, pageNumber);

            }
        });

    }


    /**
     * 
     * Load images
     * 
     */
    function loadImages(refreshPaginateion, pageNumber = 0) {
        
        var $container = $('#image-list-segment'),
            count = $container.attr('data-count'),
            $loading = $container.find('.loading'),
            url = $container.attr('data-url'),
            userId = $container.attr('data-user-id'),
            token = $('meta[name="csrf-token"]').attr('content'),
            $message = $('#image-not-found-message');
        
        
        if (pageNumber != 0) {
            pageNumber--;
        }

             
        $.ajax({
            url: url,
            method: 'POST',
            data: {
                count: count,
                page: pageNumber,
                user_id: userId,
                _token: token
            },
            beforeSend: function () {
                $loading.addClass('active');
            },
            success: function (response) {

                if (response.success == true) {
                    
                    // Update container state
                    $container.attr({
                        'data-last': response.data.lastPage,
                        'data-page': ++pageNumber,
                        'data-total': response.data.total
                    });


                    // Update pagination
                    if (refreshPaginateion == true) {
                        updatePagination(pageNumber, count, response.data.total);
                    }


                    // Set not found message status 
                    if (response.data.total == 0) {
                        $message.removeClass('hidden');
                    } else {
                        $message.addClass('hidden');
                    }
                    

                    // Append new images to container
                    updateImageList(response.data.images);

                } else {

                    if (response.data.action == 'reloadPage') {
                        window.showCSRFModal();
                    }

                }

            },
            complete: function () {
                $loading.removeClass('active');
            }
        })

    }


    /**
     * UpdateImageList
     * 
     * @param Array images
     */
    function updateImageList(images) {
        
        var $imageList = $('#image-list'),
            template = '<div class="column">\
                            <div class="ui card" data-id="{imageId}">\
                                <div class="image"><img src="{image}"></div>\
                                <div class="content">\
                                    <a class="header">{title}</a>\
                                    <div class="meta">\
                                        <span class="date category">{category}</span>\
                                    </div>\
                                    <div class="description">{description}</div>\
                                </div>\
                                <div class="extra content">\
                                    <div class="ui two buttons">\
                                        <div class="ui teal button show-edit-image-modal">Edit</div>\
                                        <div class="ui red button show-remove-image-modal">Remove</div>\
                                    </div>\
                                </div>\
                            </div>\
                        </div>';
        
        $imageList.html('');

        $.each(images, function (index, image) {

            var imageTempate = template,
                title = image.title == null ? 'Title' : image.title,
                description = image.description == null ? 'Description' : image.description,
                category = image.category == null ? 'Category' : image.category;
            

            imageTempate = imageTempate.replace('{imageId}', image.uuid);
            imageTempate = imageTempate.replace('{image}', image.url);
            imageTempate = imageTempate.replace('{title}', title);
            imageTempate = imageTempate.replace('{description}', description);
            imageTempate = imageTempate.replace('{category}', category);

            $(imageTempate).appendTo($imageList);

        });

    }

    
    /**
     * Reload image list
     * 
     * 
     */
    function reloadImageList() {
        
        if ($('#image-list').length > 0) {

            var location = document.location,
                hash = location.hash,
                page = 0;

            if (hash.indexOf('page-') > 0) {
                page = hash.replace('#page-', '');
            }

            loadImages(true, page);

        }

    }


    /***** Init image list */
    reloadImageList();


});