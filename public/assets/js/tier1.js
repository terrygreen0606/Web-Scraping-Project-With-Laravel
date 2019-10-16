
$(document).ready(function(){

    window.ItemsOnPage = 5;

    // begin search function

    $(document).on('keyup', '#search', function(){

        var filter =  $("#search").val().toLowerCase();

        $("#tier1 tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(filter) > -1)
        });
    })

    // end search function

    // begin 301Url check, uncheck function

    $(document).on('click', '#radio-enable', function(event) {

        $("#emUrl").prop("disabled", false);
        $("#targetUrl").prop("disabled", false);
        $("#emUrl").val("");
        $("#targetUrl").val("");
    });

    $(document).on('click', '#radio-disable', function(event) {

        $("#emUrl").prop("disabled", true);
        $("#targetUrl").prop("disabled", true);
        $("#emUrl").val("");
        $("#targetUrl").val($("#tier1Link").val());

    });

    $(document).on('keyup', '#tier1Link', function(){

        if( $("#radio-disable").prop("checked")){
            $("#targetUrl").val($("#tier1Link").val());
        }

    })

    // End 301Url check, uncheck function


    // Begin 301Url check, uncheck (Edit function)
    $(document).on('click', '#radio-enableEdit', function(event) {

        $("#emUrlEdit").prop("disabled", false);
        $("#targetUrlEdit").prop("disabled", false);
        $("#emUrl").val("");
        $("#targetUrl").val("");
    });

    $(document).on('click', '#radio-disableEdit', function(event) {

        $("#emUrlEdit").prop("disabled", true);
        $("#targetUrlEdit").prop("disabled", true);
        $("#emUrl").val("");
        $("#targetUrlEdit").val($("#tier1LinkEdit").val());

    });

    $(document).on('keyup', '#tier1LinkEdit', function(){

        if( $("#radio-disableEdit").prop("checked")){
            $("#targetUrlEdit").val($("#tier1LinkEdit").val());
        }

    })
    // End 301Url check, uncheck (Edit function)


    /***** Begin add a new Tier1 form *****/
    $('#form-add-Tier1').on('submit', function (event) {

        event.preventDefault();
        console.log("clicked");
        var is301Url = $("#radio-enable").prop('checked');
        $.ajax({
            url: $(this).attr('action'),
            method: 'post',
            timeout: 20000,
            data: {
                _token: $(this).find('[name="_token"]').val(),
                client_id: $(this).find('[name="clientId"]').val(),
                provider_id: $(this).find('[name="providerId"]').val(),
                tier1_link: $(this).find('[name="tier1Link"]').val(),
                emUrl: is301Url ? $(this).find('[name="emUrl"]').val() : "null",
                anchor_text: $(this).find('[name="anchorText"]').val(),
                target_url: is301Url ? $(this).find('[name="targetUrl"]').val() : $(this).find('[name="tier1Link"]').val()
            },
            beforeSend: function () {

                $(this).addClass('loading');

            },
            complete: function () {

                $(this).removeClass('loading');

            },
            success: function (response) {

                // Begin process response
                if (response.success == true) {

                    var tier1 = response.data.tier1,

                        rowTemplate = '<tr class="tier1Item" data-tier1-id="{id}">\
                                            <td class="counterCell"></td>\
                                            <td>{client_id}</td>\
                                            <td>{provider_id}</td>\
                                            <td>{tier1_link}</td>\
                                            <td>{301_url}</td>\
                                            <td>{anchor_text}</td>\
                                            <td>{target_url}</td>\
                                            <td>\
                                                <div class="right floated content">\
                                                    <div class="ui tiny icon button teal show-edit-tier1-modal" data-toggle="modal" data-target="#editTier1">\
                                                        <i class="edit icon"></i>\
                                                    </div>\
                                                    <div class="ui tiny icon button red show-delete-tier1-modal" data-toggle="modal" data-target="#delete-tier1-modal">\
                                                        <i class="trash alternate outline icon"></i>\
                                                    </div>\
                                                </div>\
                                            </td>\
                                        </tr>',
                        $list = $('#tier1 tbody');

                    rowTemplate = rowTemplate.replace(/{id}/g, tier1.id);
                    rowTemplate = rowTemplate.replace('{client_id}', tier1.client_id);
                    rowTemplate = rowTemplate.replace('{provider_id}', tier1.provider_id);
                    rowTemplate = rowTemplate.replace('{tier1_link}', tier1.tier1_link);
                    rowTemplate = rowTemplate.replace('{301_url}', tier1.emUrl);
                    rowTemplate = rowTemplate.replace('{anchor_text}', tier1.anchor_text);
                    rowTemplate = rowTemplate.replace('{target_url}', tier1.target_url);

                    $list.append(rowTemplate);

                    $("#tier1Modal").modal("hide");

                    $("#tier1Link").val("");
                    $("#emUrl").val("");
                    $("#anchorText").val("");
                    $("#targetUrl").val("");


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

    });

    /***** End add a new Tier1 form *****/


     /***** Begin delete Tier1 *****/
     $(document).on('click', '.show-delete-tier1-modal', function (event) {

        event.preventDefault();

        var $modal = $('#delete-tier1-modal');
            $btn = $(this);
            $item = $btn.parents('.tier1Item');
            tier1Id = $item.attr('data-tier1-id');
            $message = $modal.find('.message');
            $('#form-remove-Tier1').on('submit', function (event) {

                event.preventDefault();

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'post',
                    timeout: 20000,
                    data: {
                        _token: $(this).find('[name="_token"]').val(),
                        id: tier1Id,
                    },
                    beforeSend: function () {

                        $(this).addClass('loading');

                    },
                    complete: function () {

                        $(this).removeClass('loading');

                    },
                    success: function (response) {

                        // Begin process response
                        if (response.success == true) {

                            $item.remove();


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
                        $("#delete-tier1-modal").modal("hide");
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
            });
    })
    /***** End delete Tier1 *****/


    /***** Begin edit Tier1 *****/
    $(document).on('click', '.show-edit-tier1-modal', function (event) {

        event.preventDefault();

        var $modal = $('#editTier1');
            $btn = $(this);
            $item = $btn.parents('.tier1Item');
            tier1Id = $item.attr('data-tier1-id');
            $message = $modal.find('.message');
            var $tier1Row = [];
            for( var $i = 0; $i< 6; $i++) {
                $tier1Row[$i] = $item.children("td:nth-child("+($i+2)+")").text();
            }


            $("#clientIdEdit").val($tier1Row[0]);
            $("#providerIdEdit").val($tier1Row[1]);
            $("#tier1LinkEdit").val($tier1Row[2]);
            $("#emUrlEdit").val($tier1Row[3]);
            $("#anchorTextEdit").val($tier1Row[4]);
            $("#targetUrlEdit").val($tier1Row[5]);

            $('#formEditTier1').on('submit', function (event) {
                event.preventDefault();

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'post',
                    timeout: 20000,
                    data: {
                        id: tier1Id,
                        client_id: $("#clientIdEdit").val(),
                        provider_id: $("#providerIdEdit").val(),
                        tier1_link: $("#tier1LinkEdit").val(),
                        emUrl: $("#emUrlEdit").val(),
                        anchor_text: $("#anchorTextEdit").val(),
                        target_url: $("#targetUrlEdit").val(),
                        _token: $(this).find('[name="_token"]').val()
                    },
                    beforeSend: function () {

                        $(this).addClass('loading');

                    },
                    complete: function () {

                        $(this).removeClass('loading');

                    },
                    success: function (response) {

                        // Begin process response
                        if (response.success == true) {

                            $item.attr('data-tier1-id', response.data.tier1_id);
                            $item.children('td:nth-child(2)').text(response.data.client_id);
                            $item.children('td:nth-child(3)').text(response.data.provider_id);
                            $item.children('td:nth-child(4)').text(response.data.tier1_link);
                            $item.children('td:nth-child(5)').text(response.data.emUrl);
                            $item.children('td:nth-child(6)').text(response.data.anchor_text);
                            $item.children('td:nth-child(7)').text(response.data.target_url);


                            $("#editTier1").modal("hide");

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
                        $("#editTier1").modal("hide");
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
            });
    })
    /***** End edit Tier1 *****/


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

})
