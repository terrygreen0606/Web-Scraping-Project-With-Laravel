$(document).ready(function () {

    window.ItemsOnPage = 5;


    /***** Begin add a new client form *****/
    $('#form-add-client').on('submit', function (event) {
        event.preventDefault();
    });

    $('#form-add-client').validate({
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
            client_name: {
                required: true,
                minlength: 3,
                maxlength: 64
            }
        },
        messages: {
            client_name: {
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
                    client_name: $(form).find('[name="client_name"]').val(),
                },
                beforeSend: function () {

                    $(form).addClass('loading');

                },
                complete: function () {

                    $(form).removeClass('loading');

                },
                success: function (response) {

                    // Begin process response
                    if (response.success == true) {

                        var client = response.data.client,
                            rowTemplate = '<div class="item" data-client-id="{clientID}" data-client-name="{clientName}">\
                                                <div class="right floated content">\
                                                    <div class="ui tiny icon button teal show-edit-client-modal">\
                                                        <i class="edit icon"></i>\
                                                    </div>\
                                                    <div class="ui tiny icon button red show-delete-client-modal">\
                                                        <i class="trash alternate outline icon"></i>\
                                                    </div>\
                                                </div>\
                                                <div class="content middle aligned">{clientName}</div>\
                                            </div>',
                            $list = $('#client-list');

                        rowTemplate = rowTemplate.replace('{clientID}', client.uuid);
                        rowTemplate = rowTemplate.replace(/{clientName}/g, client.title);

                        $list.append(rowTemplate);
                        updatePagination();
                        form.reset();

                    } else if (response.success == false) {

                        if (response.data.action == 'reloadPage') {

                            window.showCSRFModal();
                            return false;

                        }


                        // begin show messages
                        if (response.messages.length > 0) {

                            var $modal = $('#message-modal');

                            $modal.find('.content').text(response.messages[0]);
                            $modal.modal('show');

                        }
                        // end show messages

                    }
                    // End  process response

                },
                error: function (error) {

                    var $modal = $('#message-modal');

                    if (error.statusText == 'timeout') {
                        $modal.find('.content').text('The server does not respond');
                    } else {
                        $modal.find('.content').text('There was a problem, please try agian');
                    }

                    $modal.modal('show');

                }
            });

        }
    });
    /***** End add a new client form *****/


    /***** Begin edit client *****/
    $(document).on('click', '.show-edit-client-modal', function (event) {

        event.preventDefault();

        var modalId = 'edit-client-modal',
            $modal =  $('#edit-client-modal'),
            $btn = $(this),
            $item = $btn.parents('.item:first'),
            clientId = $item.attr('data-client-id'),
            clientName = $item.attr('data-client-name'),
            action = $modal.attr('action');
            $message = $modal.find('.message');
            $messageContent = $message.find('.content');

        $modal
            .modal({
                closable: false,
                onHidden: function(){

                    window.modal = '';

                    $messageContent
                        .text('');

                    $message
                        .addClass('hidden')
                        .removeClass('yellow');
                },
                onShow: function(){

                    $("#edit_client_name").val(clientName);
                    window.modal = modalId;

                },
                onApprove: function () {

                    $.ajax({
                        url: action,
                        method: 'POST',
                        data: {
                            client_id: clientId,
                            client_name: $("#edit_client_name").val(),
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function () {

                            $modal
                                .find('.button')
                                .addClass('disabled loading');

                        },
                        complete: function () {

                            $modal
                                .find('.button')
                                .removeClass('disabled loading');

                        },
                        success: function (response) {

                            if (response.success == true) {
                                $item.attr('data-client-name', response.data.client_name);
                                $item.children('div:nth-child(2)').text(response.data.client_name);

                                $modal.modal('hide');

                            } else {

                                if (response.data.action == 'reloadPage') {
                                    window.showCSRFModal();
                                    return false;
                                }

                                // Begin show messages
                                if (response.messages.length > 0) {

                                    $messageContent
                                        .text(response.messages[0]);

                                    $message
                                        .removeClass('hidden')
                                        .addClass('yellow');

                                }
                                // End show messages

                            }

                        }
                    });

                    // prevent close
                    return false;

                }
            })
            .modal('show');
    });
    /***** End edit client *****/


    /***** Begin delete client *****/
    $(document).on('click', '.show-delete-client-modal', function (event) {

        event.preventDefault();

        var modalId = 'delete-client-modal',
            $modal = $('#delete-client-modal'),
            $btn = $(this),
            $item = $btn.parents('.item:first'),
            clientId = $item.attr('data-client-id'),
            action = $modal.attr('action'),
            $message = $modal.find('.message'),
            $messageContent = $message.find('.content');

        $modal
            .modal({
                closable: false,
                onHidden: function () {

                    $modal.removeAttr('data-user-id');
                    window.modal = '';

                    $messageContent
                        .text('');

                    $message
                        .addClass('hidden')
                        .removeClass('yellow');

                },
                onShow: function () {

                    $modal.attr('data-user-id', clientId);
                    window.modal = modalId;

                },
                onApprove: function () {

                    $.ajax({
                        url: action,
                        method: 'POST',
                        data: {
                            client_id: clientId,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function () {

                            $modal
                                .find('.button')
                                .addClass('disabled loading');

                        },
                        complete: function () {

                            $modal
                                .find('.button')
                                .removeClass('disabled loading');

                        },
                        success: function (response) {

                            if (response.success == true) {

                                $item.remove();
                                updatePagination();
                                $modal.modal('hide');

                            } else {

                                if (response.data.action == 'reloadPage') {
                                    window.showCSRFModal();
                                    return false;
                                }


                                // Begin show messages
                                if (response.messages.length > 0) {

                                    $messageContent
                                        .text(response.messages[0]);

                                    $message
                                        .removeClass('hidden')
                                        .addClass('yellow');

                                }
                                // End show messages

                            }

                        }
                    });


                    // prevent close
                    return false;

                }
            })
            .modal('show');
    })
    /***** End delete client *****/


    /***** Begin initiate message mosal *****/
    $('#message-modal').modal({
        closable: false,
        onHidden: function () {

            var $modal = $('#message-modal'),
                $message = $modal.find('.content');

            $message.text('');

        }
    });
    /***** End initiate message mosal *****/


    /**
     * Setup pagination
     *
     * @return Void
     */
    function setupPagination() {

        var $pagination = $('#pagination'),
            $list = $('#client-list'),
            $items = $list.children('.item');

        $pagination.pagination({
            items: $items.length,
            itemsOnPage: window.ItemsOnPage,
            cssStyle: 'light-theme',
            onPageClick: function (pageNumber) {
                updateItemsOnPaginationChange(pageNumber);
            },
            onInit: function () {
                updateItemsOnPaginationChange(1);
            }
        });

    }


    /**
     * Update pagination
     *
     * @return Void
     */
    function updatePagination() {

        var $pagination = $('#pagination'),
            $list = $('#client-list'),
            $items = $list.children('.item'),
            currentPage,
            pageCount;

        $pagination.pagination('updateItems', $items.length);

        currentPage = $pagination.pagination('getCurrentPage');
        pageCount = $pagination.pagination('getPagesCount');


        // Set active page when page count is reduced
        if (currentPage > pageCount) {

            currentPage = pageCount;
            $pagination.pagination('selectPage', currentPage);

        }


        updateItemsOnPaginationChange(currentPage);

    }


    /**
     * Show items on pagination update
     *
     * @return Void
     */
    function updateItemsOnPaginationChange(currentPage) {

        var startIndex = (currentPage - 1) * window.ItemsOnPage,
            endIndex = currentPage * window.ItemsOnPage,
            $list = $('#client-list'),
            $allItems = $list.children('.item'),
            $itmesToShow = $list.children('.item').slice(startIndex, endIndex);

        $allItems.addClass('hidden');
        $itmesToShow.removeClass('hidden');

    }

    // Start app
    setupPagination();

});
