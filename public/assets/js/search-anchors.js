$(document).ready(function () {

    $('#add-new-source-modal form').on('submit', function (event) {
        event.preventDefault();
    });

    /***** Begin add new source *****/
    $(document).on('click', '#show-add-new-source-modal', function (event) {

        event.preventDefault();

        var $addSouceModal = $('#add-new-source-modal'),
            $notificationModal = $('#enter-target-anchor-notification-modal'),
            $anchorURLInput = $('input#anchor_url'),
            anchorURL = $anchorURLInput.val().trim(),
            urlRegex = /^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/;

        if (urlRegex.test(anchorURL) == false) {

            $notificationModal.modal({
                onHidden: function () {

                    $anchorURLInput.focus();

                }
            }).modal('show');

        } else {

            $addSouceModal
                .modal({
                    closable: false,
                    onHidden: function () {

                        var $sourceInput = $addSouceModal.find('#modal-source-input');

                        $sourceInput.val('');
                        $addSouceModal
                            .find('.field')
                            .removeClass('error')
                            .find('p.error')
                            .remove();

                    },
                    onShow: function () {

                        window.modal = 'add-new-source-modal';

                    },
                    onApprove: function () {

                        var $form = $('#form-send-plain-text-source');

                        $form.submit();
                        return false;

                    }
                })
                .modal('show');

        }

    });


    $('#form-send-plain-text-source').on('submit', function (event) {
        event.preventDefault();
    });


    $('#form-send-plain-text-source').validate({
        errorPlacement: function (error, element) {

            var $parent = $(element).parents('.input:first');
            error.insertAfter($parent);

        },
        highlight: function (element, errorClass) {

            $(element)
                .parents('.field:first')
                .addClass('error');

        },
        unhighlight: function (element, errorClass) {

            $(element)
                .parents('.field:first')
                .removeClass('error');

        },
        rules: {
            modal_source_input: {
                required: true
            }
        },
        messages: {
            modal_source_input: {
                required: "Please put your source hear",
            },
        },
        submitHandler: function (form) {

            var $plainTextInput = $(form).find('[name="modal_source_input"]'),
                $targetAnchorURLInput = $('input#anchor_url'),
                $modal = $('#add-new-source-modal');
                $btnAddSource = $('#add-source-dropdown');

            $.ajax({
                url: $(form).attr('action'),
                method: 'post',
                timeout: 600000,
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'target_anchor': $targetAnchorURLInput.val().trim(),
                    'plain_text': $plainTextInput.val()
                },
                beforeSend: function () {

                    clearSourcesAndResults();

                    // $(form).addClass('loading');
                    // $modal.find('.approve').addClass('loading disabled');
                    // $modal.find('.deny').addClass('disabled');

                    // $modal
                    //     .find('.field')
                    //     .removeClass('error')
                    //     .find('p.error')
                    //     .remove();
                    $modal.modal('hide');
                    $btnAddSource.addClass('loading disabled');

                },
                success: function (response) {

                    var data = response.data,
                        $resultTable = $('#anchor-search-result-table');

                    if (response.success == true) {

                        $resultTable.attr('data-campaign-name', data.campaignName);

                        $.each(data.analyzeResult, function (index, item) {

                            var number = index + 1;
                            // appendToSourceList(item.url, number, item.status.toLowerCase());
                            appendRowToTable('success', item, number);

                        });

                        refreshView();
                        appendToSourceList(data.campaignName);

                        $btnAddSource.removeClass('loading disabled');
                        // $modal.modal('hide');
                        return true;

                    } else {

                        if (response.data.action == 'reloadPage') {
                            window.showCSRFModal();
                        }

                        if (response.data.length == 0 && response.messages.length > 0) {

                            $modal
                                .modal('show')
                                .find('.field')
                                .addClass('error')
                                .append('<p class="error">' + response.messages[0] + '</p>');


                        }
                        $btnAddSource.removeClass('loading disabled');
                    }

                },
                error: function (error) {

                    $btnAddSource.removeClass('loading disabled');

                },
                complete: function () {

                    $(form).removeClass('loading');
                    $modal.find('.approve').removeClass('loading disabled');
                    $modal.find('.deny').removeClass('disabled');
                    $btnAddSource.removeClass('loading disabled');
                }
            });

            return false;

        }
    });
    /***** End add new source *****/


    /***** Begin upload source file *****/
    $(document).on('click', '#show-upload-source-file-modal', function (event) {

        event.preventDefault();

        var $uploadModal = $('#upload-source-file-modal'),
            $notificationModal = $('#enter-target-anchor-notification-modal'),
            $anchorURLInput = $('input#anchor_url'),
            anchorURL = $anchorURLInput.val().trim(),
            urlRegex = /^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/;


        if (urlRegex.test(anchorURL) == false) {

            $notificationModal.modal({
                onHidden: function () {

                    $anchorURLInput.focus();

                }
            }).modal('show');

        } else {

            $uploadModal
                .modal({
                    closable: false,
                    onHidden: function () {

                        var $inputs = $uploadModal.find('.field.error'),
                            $form = $('#form-upload-source-file');

                        $form[0].reset();
                        $inputs.removeClass('error');

                    },
                    onShow: function () {

                        window.modal = 'upload-source-file-modal';

                    },
                    onApprove: function () {

                        var $form = $('#form-upload-source-file');

                        $form.submit();
                        return false;

                    }
                })
                .modal('show');

        }

    });


    $('#form-upload-source-file').on('submit', function (event) {
        event.preventDefault();
    });


    $('#form-upload-source-file').validate({
        errorPlacement: function (error, element) {

            var $parent = $(element).parents('.input:first');
            error.insertAfter($parent);

        },
        highlight: function (element, errorClass) {

            $(element)
                .parents('.field:first')
                .addClass('error');

        },
        unhighlight: function (element, errorClass) {

            $(element)
                .parents('.field:first')
                .removeClass('error');

        },
        rules: {
            source_file: {
                required: true,
                fileExtension: "rtf|txt"
            }
        },
        messages: {
            source_file: {
                required: "Please choose your file",
                fileExtension: "Please choose .RTF or .TXT file"
            },
        },
        submitHandler: function (form) {

            var formData = new FormData(),
                $sourceFile = $(form).find('[name="source_file"]'),
                $targetAnchorURLInput = $('input#anchor_url'),
                $modal = $('#upload-source-file-modal'),
                targetAnchorURL = $targetAnchorURLInput.val().trim();

            formData.append('_token', $(form).find('[name="_token"]').val());
            formData.append('target_anchor', targetAnchorURL);
            formData.append('source_file', $sourceFile[0].files[0]);

            $.ajax({
                url: $(form).attr('action'),
                method: 'post',
                timeout: 300000,
                processData: false,
                contentType: false,
                data: formData,
                beforeSend: function () {

                    clearSourcesAndResults();

                    $(form).addClass('loading');
                    $('#upload-source-file-modal').find('.approve').addClass('loading disabled');
                    $('#upload-source-file-modal').find('.deny').addClass('disabled');

                },
                success: function (response) {

                    var data = response.data;

                    if (response.success == true) {

                        $.each(data.analyzeResult, function (index, item) {

                            var number = index + 1;

                            // appendToSourceList(item.url, number, item.status.toLowerCase());
                            appendRowToTable('success', item, number);

                        });

                        refreshView();
                        appendToSourceList(data.campaignName);
                        $modal.modal('hide');

                        return true;

                    } else {

                        if (response.data.action == 'reloadPage') {

                            window.showCSRFModal();

                        }

                    }


                },
                error: function (error) {



                },
                complete: function () {

                    $(form).removeClass('loading');
                    $('#upload-source-file-modal').find('.approve').removeClass('loading disabled');
                    $('#upload-source-file-modal').find('.deny').removeClass('disabled');

                }
            });

            return false;

        }
    });
    /***** End upload source file *****/


    /***** Begin show page preview */
    $(document).on('click', '.show-anchor-preview', function (event) {

        event.preventDefault();

        var $btn = $(this),
            $modal = $('#page-preview-modal'),
            $iframe = $('#page-preview-iframe'),
            pageContent = $btn.data('pageContent'),
            src = 'data:text/html;base64,' + pageContent;

        $modal
            .modal({
                onShow: function () {

                    $iframe.attr('src', src);
                    $iframe.css({
                        'height': window.innerHeight * 0.7 + 'px'
                    });

                },
                onHidden: function () {
                    $iframe.attr('src', '');
                }
            })
            .modal('show');

    });
    /***** End show page preview */


    /***** Begin clear all sources *****/
    $(document).on('click', '#remove-all-source', function (event) {

        event.preventDefault();

        var $modal = $('#clear-all-sources-modal');

        $modal
            .modal({
                closable: false,
                onApprove: function () {

                    clearSourcesAndResults();
                    refreshView();

                }
            })
            .modal('show');

    });
    /***** End clear all sources *****/


    /***** Begin remove source item *****/
    $(document).on('click', '.remove-source-item', function (event) {

        event.preventDefault();

        var $form = $('#form-search-anchors'),
            $itemList = $('#field-search-anchors-source'),
            $listItems = null,
            $item = $(this).parents('.item:first'),
            number = $item.attr('data-number'),
            $row = $('#anchor-search-result-table').find('tr[data-row="' + number + '"]'),
            $rowExtended = $('#anchor-search-result-table').find('tr[data-main-row="' + number + '"]');

        $row.remove();
        $rowExtended.remove();
        $item.remove();


        // Begin set form status
        $listItems = $itemList.find('.item');

        if ($listItems.length == 0) {
            $form.removeAttr('data-search-status');
        }
        // End set form status


        refreshView();

    });
    /***** End remove source item *****/


    /***** Begin search again *****/
    $(document).on('click', '#restart-search-sources', function (event) {

        event.preventDefault();

        var $btnSubmit = $('#do-search-sources'),
            $resultTable = $('#anchor-search-result-table tbody'),
            $itemsList = $('#field-search-anchors-source'),
            $items = $itemsList.find('.item');

        $items.each(function (index, item) {
            $(item).attr('data-status', 'not-checked');
        });

        $resultTable.html('');
        $btnSubmit.click();

    });
    /***** End search again *****/


    /***** Begin save the CSV file *****/
    $(document).on('click', '#do-save-csv', function () {

        var $btn = $(this),
            $form = $('#form-search-anchors'),
            csvData = [],
            campaignName = '',
            action = $btn.attr('data-action'),
            $table = $('#anchor-search-result-table');


        // Begin prepaire data
        campaignName = $table.attr('data-campaign-name');
        $table.find('[data-row]').each(function (index, row) {

            var $row = $(row),
                rowNum = $row.attr('data-row');

            csvData.push({
                'num': rowNum,
                'url': $row.children('td:eq(1)').text(),
                'status': $row.children('td:eq(2)').text(),
                'found': $row.children('td:eq(3)').text(),
                'anchor_text': $row.children('td:eq(4)').text(),
                'anchor_url': $row.children('td:eq(5)').text(),
                'file_size': $row.children('td:eq(6)').text(),
                'date_checked': $row.children('td:eq(7)').text()
            });


            // Begin process sub rows
            $table.find('[data-main-row=' + rowNum + ']').each(function (index, subRow) {

                var $subRow = $(subRow);

                csvData.push({
                    'num': '',
                    'url': '',
                    'status': '',
                    'found': '',
                    'anchor_text': $subRow.children('td:eq(0)').text(),
                    'anchor_url': $subRow.children('td:eq(1)').text(),
                    'file_size': '',
                    'date_checked': ''
                });

            });
            // End process sub rows

        });
        // End prepaire data


        // Begin send ajax request
        $.ajax({
            url: action,
            method: 'POST',
            data: {
                csv: csvData,
                campaign: campaignName,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {
                $form.addClass('loading');
            },
            success: function (response) {
                var $modal = $('#store-csv-modal');

                if (response.success == true) {

                    $modal.find('.failed').addClass('hidden');
                    $modal.find('.success').removeClass('hidden');
                    $modal.modal('show');

                } else if (response.success == false) {

                    if (response.data.action == 'reloadPage') {

                        window.showCSRFModal();

                    } else {

                        $modal.find('.failed').removeClass('hidden');
                        $modal.find('.success').addClass('hidden');
                        $modal.modal('show');

                    }

                }

            },
            error: function (error) {
                $('#store-csv-modal')
                    .addClass('failed')
                    .removeClass('success')
                    .modal('show');

            },
            complete: function () {

                $form.removeClass('loading');

            }

        })
        // End send ajax request

    });
    /***** End save the CSV file *****/


    /***** Begin search anchor form validation and submit *****/
    $('#form-search-anchors').on('submit', function (event) {
        event.preventDefault();
    });

    $('#form-search-anchors').validate({
        highlight: function (element) {
            $(element).parents('.field:first').addClass('error');
        },
        unhighlight: function (element) {
            $(element).parents('.field:first').removeClass('error');
        },
        rules: {
            anchor_url: {
                required: true,
                // url: true,
            }
        },
        messages: {
            anchor_url: {
                required: "",
                // url: "URL is invalid",
            },
        },
        submitHandler: function (form) {

            startToSearch();

        }
    });
    /***** End search anchor form validation and submit *****/


    /**
     * Clear all sources and results
     *
     * @return Void
     */
    function clearSourcesAndResults() {

        var $list = $('#field-search-anchors-source'),
            $table = $('#anchor-search-result-table tbody'),
            $form = $('#form-search-anchors');

        $list.html('');
        $table.html('');
        $form.removeAttr('data-search-status');

    }


    /**
     * Update list state
     *
     * @return Void
     */
    function refreshView() {

        var $form = $('#form-search-anchors'),
            $list = $('#field-search-anchors-source'),
            $anchorInput = $('#anchor_url').parents('.field:first'),
            $items = $list.find('.item'),
            $tblResult = $('#anchor-search-result-table'),
            $uncheckedItems = $list.find('.item[data-status="not-checked"]'),
            $btnClearList = $('#remove-all-source'),
            $btnSubmit = $('#do-search-sources'),
            $btnAddSource = $('#add-source-dropdown'),
            $btnSearchAgain = $('#restart-search-sources'),
            $btnStoreCSV = $('#do-save-csv'),
            $btnDelSelected = $('#delete-selected');


        if ($form.attr('data-search-status') == undefined) {

            $btnAddSource.addClass('teal');
            $btnAddSource.removeClass('disabled');
            $anchorInput.removeClass('disabled');

            if ($items.length == 0) {

                //$btnClearList.addClass('disabled');
                $btnSubmit.addClass('disabled');
                $btnSearchAgain.addClass('disabled');

                //$btnClearList.removeClass('yellow');
                $btnSubmit.removeClass('green');
                $btnSearchAgain.removeClass('blue');

            } else {

                //$btnClearList.removeClass('disabled');
                //$btnClearList.addClass('yellow');
                $btnSubmit.removeClass('loading');

                if ($uncheckedItems.length > 0) {

                    $btnSubmit.removeClass('disabled');
                    $btnSubmit.addClass('green');

                } else {

                    $btnSubmit.addClass('disabled');
                    $btnSubmit.removeClass('green');

                }

            }

        } else if ($form.attr('data-search-status') == 'inprogress') {

            $anchorInput.addClass('disabled');

            $btnClearList.addClass('disabled');
            $btnClearList.removeClass('yellow');

            $btnSubmit.addClass('disabled loading');
            $btnSubmit.removeClass('green');

            $btnAddSource.addClass('disabled');
            $btnAddSource.removeClass('teal');

            $btnSearchAgain.addClass('disabled');
            $btnSearchAgain.removeClass('blue');

        } else if ($form.attr('data-search-status') == 'completed') {

            $btnSearchAgain.removeClass('disabled');
            $btnSearchAgain.addClass('blue');

            $btnClearList.removeClass('disabled');
            $btnClearList.addClass('yellow');

            $btnSubmit.removeClass('loading');
            $anchorInput.removeClass('disabled');

            $btnAddSource.removeClass('disabled');
            $btnAddSource.addClass('teal');

        }


        // Begin change the Store csv button state
        if ($tblResult.find('[data-row]').length > 0) {

            $btnStoreCSV
                .removeClass('disabled')
                .addClass('blue');
            $btnDelSelected
                .removeClass('disabled')
                .addClass('red');
            $btnClearList
                .removeClass('disabled')
                .addClass('yellow');

        } else {

            $btnStoreCSV
                .addClass('disabled')
                .removeClass('blue');
            $btnDelSelected
                .addClass('disabled')
                .removeClass('red');
            $btnClearList
                .addClass('disabled')
                .removeClass('yellow');

        }
        // End change the Store csv button state


        // Begin set item numbers
        $items.each(function (index, item) {

            var $item = $(item),
                newNumber = index + 1,
                currentNumber = $item.attr('data-number'),
                $row = $tblResult.find('tr[data-row="' + currentNumber + '"]'),
                $rowExtended = $tblResult.find('tr[data-main-row="' + currentNumber + '"]'),
                rowClass;


            // Begin computing rowClass (even / odd)
            if (newNumber % 2 == 1) {
                rowClass = 'row-even';
            } else {
                rowClass = 'row-odd';
            }
            // End computing rowClass (even / odd)


            $item
                .attr('data-number', newNumber)
                .find('.number')
                .text(newNumber);

            $row
                .removeClass('row-odd row-even')
                .addClass(rowClass)
                .attr('data-row', newNumber)
                .find('.number')
                .text(newNumber);

            $rowExtended
                .removeClass('row-odd row-even')
                .addClass(rowClass)
                .attr('data-main-row', newNumber);

        });
        // End set item numbers

    }


    /**
     * Start search anchor in URLs
     *
     *
     */
    function startToSearch() {

        var $form = $('#form-search-anchors'),
            $urlList = $('#field-search-anchors-source'),
            $urlItem = $urlList.find('.item[data-status="not-checked"]:first'),
            link = $urlItem.attr('data-url') || null,
            number = $urlItem.attr('data-number') || null,
            listTopPosition,
            itemTopPosition,
            newScrollPosition,
            currentScrollPosition;


        if ($urlItem.length == 0) {

            $form.attr('data-search-status', 'completed');
            refreshView();
            return false;

        } else {
            $form.attr('data-search-status', 'inprogress');
            refreshView();
        }


        // Begin set list scroll position
        listTopPosition         = $urlList.offset().top;
        itemTopPosition         = $urlItem.offset().top;
        currentScrollPosition   = $urlList[0].scrollTop;
        newScrollPosition       = (itemTopPosition - listTopPosition) + currentScrollPosition - ($urlList.height() - $urlItem.height() - 5);

        $urlList.animate({
                            'scrollTop': newScrollPosition
                        },
                        {
                            duration: 150,
                            queue: false
                        });
        // End set list scroll position

        $.ajax({
            url: $form.attr('action'),
            method: 'post',
            timeout: 45000,
            data: {
                _token: $form.find('[name="_token"]').val(),
                link: link,
                target_Anchor: $form.find('[name="anchor_url"]').val()
            },
            beforeSend: function () {
                $urlItem.attr('data-status', 'inprogress');
            },
            success: function (response) {

                if (response.success == true) {

                    $urlItem.attr('data-status', 'success');
                    appendRowToTable('success', response.data, number);

                } else if (response.success == false) {

                    if (response.data.action == 'reloadPage') {

                        window.showCSRFModal();

                    } else {

                        $urlItem.attr('data-status', 'failed');
                        appendRowToTable('success', response.data, number);

                    }

                }

            },
            error: function (error) {

                var searchResult = {
                    'anchors': [],
                    'checked_at': '',
                    'file_size': 0,
                    'number': number,
                    'page_content': '',
                    'status': error.status,
                    'url': link,
                };

                $urlItem.attr('data-status', 'failed');
                appendRowToTable('failed', searchResult, number);

            },
            complete: function () {

                startToSearch()

            }
        });

    }


    /**
     * Append row to source list
     *
     * @param String url
     * @return Void
     */
    // function appendToSourceList(url, number = 1, status) {
    function appendToSourceList(campaignName) {
        var $sourceList = $('#field-search-anchors-source'),
            template = '<div class="item">\
                            <h5>Campaign Name</h5>\
                            <input type="text" value="{campaignName}" disabled>\
                        </div>';

        template = template.replace(/\{campaignName\}/g, campaignName);
        $sourceList.append(template);
        // var $sourceList = $('#field-search-anchors-source'),
        //     $listItems = null,
        //     duplecated = false,
        //     tempItem = '',
        //     itemTemplate = '<div class="item" data-number="{number}" data-url="{url}" data-status="{status}">\
        //                                     <div class="right floated content">\
        //                                         <div class="ui button mini red labled icon remove-source-item">\
        //                                             <i class="trash alternate outline icon"></i>\
        //                                         </div>\
        //                                     </div>\
        //                                     <div class="content">\
        //                                         <label class="ui orange circular label number">{number}</label>\
        //                                         <span class="status">\
        //                                             <i class="spinner icon spin"></i>\
        //                                             <i class="times circle outline icon"></i>\
        //                                             <i class="check circle outline icon"></i>\
        //                                         </span>\
        //                                         <a href="{url}" target="_blank">{url}</a>\
        //                                     </div>\
        //                                 </div>';

        // // Begin check duplicate url
        // $listItems = $sourceList.find('.item');

        // $listItems.each(function (itemIndex, item) {

        //     var itemUrl = $(item).attr('data-url');

        //     if (itemUrl == url) {
        //         duplecated = true;
        //         return false;
        //     }

        // });
        // // End check duplicate url

        // if (duplecated == false) {

        //     tempItem = itemTemplate;
        //     tempItem = tempItem.replace(/\{url\}/g, url);
        //     tempItem = tempItem.replace(/\{number\}/g, number);


        //     if (status == 'not-checked') {
        //         tempItem = tempItem.replace(/\{status\}/g, 'not-checked');
        //     } else if (status == 'success') {
        //         tempItem = tempItem.replace(/\{status\}/g, 'success');
        //     } else {
        //         tempItem = tempItem.replace(/\{status\}/g, 'failed');
        //     }

        //     $sourceList.append(tempItem);

        // }

    }


    /**
     * Append new search result rows to the result table
     *
     * @param Array data
     * @param String networkStatus
     * @retur Void
     */
    function appendRowToTable (networkStatus, data, number = 1) {

        var $table = $('#anchor-search-result-table tbody'),
            rowTemplate = '',
            extendRowTemplate = '<tr data-main-row="{number}">\
                                    <td class="center aligned collapsing"><span class="show-anchor-preview" data-content="{pageContent}">{anchorText}</span></td>\
                                    <td class="center aligned collapsing">{anchorURL}</td>\
                                </tr>',
            tableRow = '',
            status;

        // Begin row definition
        if (data.anchors.length == 0) {

            rowTemplate = '<tr data-row="{number}">\
                                <td class="center aligned collapsing"><input type="checkbox" class="size"></td>\
                                <td class="center aligned collapsing counterCell"></td>\
                                <td class="left aligned">{url}</td>\
                                <td class="center aligned collapsing">{status}</td>\
                                <td class="center aligned collapsing">{found}</td>\
                                <td class="center aligned collapsing">{anchorText}</td>\
                                <td class="center aligned collapsing">{anchorURL}</td>\
                                <td class="center aligned collapsing">{fileSize}</td>\
                                <td class="center aligned collapsing">{date}</td>\
                            </tr>';

        } else if (data.anchors.length == 1) {

            rowTemplate = '<tr data-row="{number}">\
                                <td class="center aligned collapsing"><input type="checkbox" class="size"></td>\
                                <td class="center aligned collapsing counterCell"></td>\
                                <td class="left aligned">{url}</td>\
                                <td class="center aligned collapsing">{status}</td>\
                                <td class="center aligned collapsing">{found}</td>\
                                <td class="center aligned"><span class="show-anchor-preview" data-content="{pageContent}">{anchorText}</span></td>\
                                <td class="center aligned">{anchorURL}</td>\
                                <td class="center aligned collapsing">{fileSize}</td>\
                                <td class="center aligned collapsing">{date}</td>\
                            </tr>';

        } else {

            rowTemplate = '<tr data-row="{number}">\
                                <td rowspan="' + data.anchors.length + '" class="center aligned collapsing"><input type="checkbox" class="size"></td>\
                                <td rowspan="' + data.anchors.length + '" class="center aligned collapsing counterCell"></td>\
                                <td rowspan="' + data.anchors.length + '" class="left aligned">{url}</td>\
                                <td rowspan="' + data.anchors.length + '" class="center aligned collapsing">{status}</td>\
                                <td rowspan="' + data.anchors.length + '" class="center aligned collapsing">{found}</td>\
                                <td class="center aligned"><span class="show-anchor-preview" data-content="{pageContent}">{anchorText}</span></td>\
                                <td class="center aligned">{anchorURL}</td>\
                                <td rowspan="' + data.anchors.length + '" class="center aligned collapsing">{fileSize}</td>\
                                <td rowspan="' + data.anchors.length + '" class="center aligned collapsing">{date}</td>\
                            </tr>';

        }
        // End row definition


        // Begin set status
        if (networkStatus == 'success') {

            status = data.status;

        } else {

            status = 'Network Error';

        }
        // End set status


        // Begin make row
        if (networkStatus == 'failed') {

            rowTemplate = rowTemplate.replace(/\{number\}/g, number);
            rowTemplate = rowTemplate.replace('{url}', data.url);
            rowTemplate = rowTemplate.replace('{status}', status);
            rowTemplate = rowTemplate.replace('{anchorText}', '');
            rowTemplate = rowTemplate.replace('{anchorURL}', '');
            rowTemplate = rowTemplate.replace('{found}', 'Unknown');
            rowTemplate = rowTemplate.replace('{fileSize}', 'Unknown');
            rowTemplate = rowTemplate.replace('{date}', '');

            tableRow += rowTemplate;

        } else {

            if (data.anchors.length == 0) {

                rowTemplate = rowTemplate.replace(/\{number\}/g, number);
                rowTemplate = rowTemplate.replace('{url}', data.url);
                rowTemplate = rowTemplate.replace('{status}', status);
                rowTemplate = rowTemplate.replace('{anchorText}', '');
                rowTemplate = rowTemplate.replace('{anchorURL}', '');
                rowTemplate = rowTemplate.replace('{found}', 'Not Found');
                rowTemplate = rowTemplate.replace('{fileSize}', data.file_size);
                rowTemplate = rowTemplate.replace('{date}', data.checked_at);

                tableRow += rowTemplate;

            } else {

                $.each(data.anchors, function (index, anchor) {

                    var pageContent = data.page_content,
                        pageSource = '',
                        newAnchorHTML,
                        customJs = '<script type="text/javascript">window.onload = function () {var element = document.getElementById("kass-anchor-highlight"),rect = element.getBoundingClientRect(),scrollTop = window.pageYOffset || document.documentElement.scrollTop;window.scrollTo(null , rect.top + scrollTop - 50);}</script></body>';

                    // Begin prepare page content
                    newAnchorHTML = '<a id="kass-anchor-highlight" style="display: inline-block !important; background: yellow !important; color: #333 !important;" href="' + anchor.url + '">' + anchor.text + '</a>';
                    pageContent = pageContent.replace(anchor.anchor, newAnchorHTML);
                    pageContent = pageContent.replace('</body>', customJs);
                    pageSource = window.btoa(unescape(encodeURIComponent(pageContent)));
                    // End prepare page content


                    if (index == 0) {

                        rowTemplate = rowTemplate.replace(/\{number\}/g, number);
                        rowTemplate = rowTemplate.replace('{url}', data.url);
                        rowTemplate = rowTemplate.replace('{status}', status);
                        rowTemplate = rowTemplate.replace('{anchorText}', anchor['text']);
                        rowTemplate = rowTemplate.replace('{anchorURL}', anchor['url']);
                        rowTemplate = rowTemplate.replace('{found}', 'Found');
                        rowTemplate = rowTemplate.replace('{fileSize}', data.file_size);
                        rowTemplate = rowTemplate.replace('{date}', data.checked_at);
                        rowTemplate = rowTemplate.replace('{pageContent}', pageSource);

                        tableRow += rowTemplate;

                    } else {

                        var extendRow = extendRowTemplate;

                        extendRow = extendRow.replace(/\{number\}/g, number);
                        extendRow = extendRow.replace('{anchorText}', anchor['text']);
                        extendRow = extendRow.replace('{anchorURL}', anchor['url']);
                        extendRow = extendRow.replace('{pageContent}', pageSource);

                        tableRow += extendRow;

                    }

                });

            }

        }
        // End make row

        $table.append(tableRow);


        // Begin remove anchor data-content attribute
        var $showPreviewButtons = $table.find('.show-anchor-preview[data-content]');

        $showPreviewButtons.each(function () {

            var $btn = $(this),
                content = $btn.attr('data-content');

            $btn.data('pageContent', content);
            $btn.removeAttr('data-content');

        })
        // End remove anchor data-content attribute

    }


    /***** Begin add file type validation to jquery.validator *****/
    jQuery.validator.addMethod("fileExtension", function (value, element, param) {

        param = typeof param === "string" ? param.replace(/,/g, '|') : "png|jpe?g|gif";
        return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));

    }, jQuery.format("Please enter a value with a valid extension."));
    /***** End add file type validation to jquery.validator *****/

    $(document).on('click', '#delete-selected', function(){
        var $checked_items =  $("#anchor-search-result-table tr td input:checked");
        $checked_items.parent().parent().remove();

    })

});
