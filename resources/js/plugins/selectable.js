$(document).on('click', '.row-select', function(e) {
    toggleRow($(this));
    toggleButtons();
});

$(document).on('click', '.all-row-select', function() {
    if ($(this).is(':checked')) {
        $('.row-select').prop('checked', true);
        $('.row-select').parents('tr').addClass('selected');
    } else {
        $('.row-select').prop('checked', false);
        $('.row-select').parents('tr').removeClass('selected');
    }

    toggleButtons();
});

function toggleRow(checkbox)
{
    if ($(checkbox).is(':checked')) {
        $(checkbox).parents('tr').addClass('selected');
    } else {
        $(checkbox).parents('tr').removeClass('selected');
    }
}

function toggleButtons()
{
    let quantity = $('.row-select:checked').length;

    // Add/remove disabled class from select button(s).
    if (quantity > 0) {
        $('.btn-select').removeClass('disabled');
    } else {
        $('.btn-select').addClass('disabled');
    }
}

$(document).on('click', '.btn-select-item', function(e) {
    e.preventDefault();

    let ids = '?selected-items=';

    $('.row-select:checked').each(function() {
        ids += $(this).val() + ',';
    });

    ids = ids.substr(0, ids.length - 1);

    window.location.href = $(this).attr('href') + ids;
});


/*$(document).ready(function () {*/
/*    $('.row-all-checkbox').click(function () {
        if ($(this).is(':checked')) {
            $('.row-checkbox').attr('checked', true);
            $('.row-checkbox').parents('tr').addClass('selected');
        } else {
            $('.row-checkbox').attr('checked', false);
            $('.row-checkbox').parents('tr').removeClass('selected');
        }
    });*/

/*    $('.row-select').click(function () {
        if ($('.row-select:checked').length > 0) {
            $('.btn-select').removeClass('disabled');
        } else {
            $('.btn-select').addClass('disabled');
        }

        if ($(this).is(':checked')) {
            $(this).parents('tr').addClass('selected');
        } else {
            $(this).parents('tr').removeClass('selected');
        }
    });*/
/*
});*/
