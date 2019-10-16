$(document).ready(function () {

    $('#form-add-catetory').submit(function (event) {
        event.preventDefault();
    });


    /**
     * 
     * Add new category event
     * 
     */
    $('#form-add-catetory').validate({
        errorPlacement: function (label, element) {
            // $(element).parents('.field:first').append(label);
        },
        highlight: function (element) {
            $(element).parents('.field:first').addClass('error');
        },
        unhighlight: function (element) {
            $(element).parents('.field:first').removeClass('error');
        },
        rules: {
            category_title: {
                required: true,
                minlength: 3,
                maxlength: 64
            }
        },
        messages: {
            category_title: {
                required: "Required",
                minlength: "Mininum length is 3",
                maxlength: "Maxinum length is 64"
            },
        },
        submitHandler: function (form) {

            $.ajax({
                url: $(form).attr('action'),
                method: 'post',
                timeout: 20000,
                data: {
                    _token: $(form).find('[name="_token"]').val(),
                    user_id: $(form).find('[name="user_id"]').val(),
                    category_title: $(form).find('[name="category_title"]').val(),
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
                        
                        form.reset();

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

                        if (response.data.action == 'addNewCategory') {
                            
                            var category = response.data.category,
                                rowTemplate = '<tr data-id="' + category.uuid + '">\
                                                    <td><span class="category-title">' + category.title + '</span></td>\
                                                    <td width="160px">\
                                                        <div class="ui buttons">\
                                                            <button class="ui teal button show-edit-category-form">Edit</button>\
                                                            <div class="or"></div>\
                                                            <button class="ui red button show-delete-category-modal">Delete</button>\
                                                        </div>\
                                                    </td>\
                                                </tr>',
                                $tableBody = $('.category-list');
                            
                            $tableBody.append(rowTemplate);

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


    /**
     * 
     * Show delete a category confirmation modal
     * 
     */
    $(document).on('click', '.show-delete-category-modal', function (event) {

        event.preventDefault();

        var $btn = $(this),
            $table = $btn.parents('table:first'),
            $row = $btn.parents('tr:first'),
            categoryId = $row.data('id'),
            userId = $table.data('user-id'),
            $modal = $('#delete-category-modal');
        
        
        $modal.data({
            'category-id': categoryId,
            'user-id': userId
        });
        
        $modal
            .modal({
                closable: false,
                onApprove: function () {

                    var $modal = $(this),
                        categoryId = $modal.data('category-id'),
                        userId = $modal.data('user-id'),
                        serviceUrl = $modal.attr('action'),
                        $approveBtn = $modal.find('.button.positive');

                    $.ajax({
                        url: serviceUrl,
                        method: 'POST',
                        timeout: 120000,
                        data: {
                            'category_id': categoryId,
                            '_token': $('meta[name="csrf-token"]').attr('content'),
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

                                $('tbody.category-list tr[data-id="' + categoryId + '"]').remove();

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

                                $message.removeClass('hidden')

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

                    window.modal = 'delete-category-modal';

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


    /**
     * 
     * Show edit category form
     * 
     */
    $(document).on('click', '.show-edit-category-form', function (event) {

        event.preventDefault();

        var $btn = $(this),
            $table = $btn.parents('table:first'),
            $rows  = $table.find('tr'),
            $row = $btn.parents('tr:first'),
            $title = $row.find('span.category-title:first'),
            $categoryButtons = $row.find('.category-buttons:first'),
            $form = $('#form-update-catetory'),
            $formButtons = $('#form-update-catetory-buttons'),
            categoryId = $row.attr('data-id');
        
        
        // reset satus of all rows
        $rows.removeClass('edit-mode');
             
        
        // set current row to edit mode
        $row.addClass('edit-mode');


        // set fields value
        $form.find('[name="category_title"]').val($title.text());
        $form.find('[name="category_id"]').val(categoryId);


        // move form to current row
        $title.after($form);
        $categoryButtons.after($formButtons);

        return false;

    });


    /**
     * 
     * Cancel edit category form
     * 
     */
    $(document).on('click', '.category-cancel-edit', function (event) {

        event.preventDefault();

        var $btn = $(this),
            $row = $btn.parents('tr:first'),
            $form = $('#form-update-catetory'),
            $formButtons = $('#form-update-catetory-buttons'),
            $container = $('#container-form-update-catetory');
        
        $row.removeClass('edit-mode');
        
        $form.appendTo($container);
        $formButtons.appendTo($container);

        return false;

    });



    /**
     * 
     * Update a category
     * 
     */
    $('#form-update-catetory').submit(function (event) {
        event.preventDefault();
    });

    $('#form-update-catetory').validate({
        errorPlacement: function (label, element) {
            // $(element).parents('.field:first').append(label);
        },
        highlight: function (element) {
            $(element).parents('.field:first').addClass('error');
        },
        unhighlight: function (element) {
            $(element).parents('.field:first').removeClass('error');
        },
        rules: {
            category_title: {
                required: true,
                minlength: 3,
                maxlength: 64
            }
        },
        messages: {
            category_title: {
                required: "Required",
                minlength: "Mininum length is 3",
                maxlength: "Maxinum length is 64"
            },
        },
        submitHandler: function (form) {

            $.ajax({
                url: $(form).attr('action'),
                method: 'post',
                timeout: 20000,
                data: {
                    _token: $(form).find('[name="_token"]').val(),
                    user_id: $(form).find('[name="user_id"]').val(),
                    category_title: $(form).find('[name="category_title"]').val(),
                    category_id: $(form).find('[name="category_id"]').val(),
                },
                beforeSend: function () {
                    $(form).addClass('loading');
                },
                success: function (response) {

                    if (response.success == true) {

                        var $form = $(form),
                            $row = $form.parents('tr:first'),
                            category_title = $(form).find('[name="category_title"]').val()
                            $categoryTitle = $row.find('span.category-title'),
                            $formContainer = $('#container-form-update-catetory'),
                            $formButtons = $('#form-update-catetory-buttons');
                        
                        $categoryTitle.text(category_title);

                        $row.removeClass('edit-mode');

                        $form.appendTo($formContainer);
                        $formButtons.appendTo($formContainer);

                        form.reset();
                        
                    } else if (response.success == false) {

                        if (response.data.action == 'reloadPage') {
                            window.showCSRFModal();
                        }

                    }

                },
                complete: function () {
                    $(form).removeClass('loading');
                }
            });

        }
    });


    /**
     * 
     * Trigger edit form submit
     * 
     */
    $(document).on('click', '.category-do-edit', function () {
        $('#form-update-catetory').submit();
    });



});