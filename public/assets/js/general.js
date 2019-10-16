$(document).ready(function () {

    // Begin message close button event
    $('.ui.message .close')
        .on('click', function () {
            $(this)
                .closest('.message')
                .transition('fade');
        });


    $('.ui.accordion').accordion();
    $('.ui.dropdown').dropdown()
    // End message close button event




    // Begin convert check box input to toggle button
    $('.ui.checkbox').checkbox();

    // Begin sidebar
    $('.toggle-sidebar').on('click', function (event) {

        var $sidebar = $('.sidebar'),
            newSidebarStatus = '';


        if ($sidebar.hasClass('expanded') == true) {
            newSidebarStatus = 'collapsed';
        } else {
            newSidebarStatus = 'expanded';
        }

        $sidebar.toggleClass('very thin icon expanded');

        $.ajax({
            url: document.location.protocol + '//' + document.location.hostname + '/setSidebar',
            method: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'user_id': $('meta[name="user"]').attr('content'),
                'status': newSidebarStatus
            },
            success: function (response) {

                if (response.success == false) {

                    if (response.data.action == 'reloadPage') {
                        window.showCSRFModal();
                    }

                }

            }
        });

    });
    // End sidebar


    $('.top-user-menu')
        .popup({
            inline: true,
            position: "bottom right"
        });


    // Begin CSRF modal
    window.modal = '';
    window.showCSRFModal = function () {

        var $currentModal = [],
            $csrfModal = $('#csrf-modal');


        // Begin to hide the active modal
        if (window.modal != '') {

            $currentModal = $('#' + window.modal);

            if ($currentModal.length > 0) {
                $currentModal.modal('hide');
            }

        }
        // End to hide the active modal


        $csrfModal.modal({
                closable: false,
                onApprove: function () {

                    document.location.reload();

                }
            })
            .modal('show');

    }
    // End CSRF modal


});
