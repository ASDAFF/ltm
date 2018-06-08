/**
 * Created by dmitry on 28.05.2016.
 */

$(document).ready(function () {

    //сворачивание разворачивание строк таблицы
    $(document).on('dblclick', 'tr', function () {
        $(this).toggleClass('full');
    });

    $(document).on('change', '.storage-popup .checkbox li input[type=checkbox], .storage-popup .radio li input[type=radio]', function () {
        var cb = $(this),
            checked = cb.prop('checked'),
            label = cb.closest('label');

        if (cb.attr('type') == 'radio') {
            cb.closest('.radio').find('label.active').each(function (index, element) {
                $(element).removeClass('active');
            });
        }


        if (checked) {
            label.addClass('active');
        }
        else {
            label.removeClass('active');
        }
    });


    $(document).on('click', 'a.in-working', function (e) {
        e.preventDefault();

        var userID = $(this).data('id');

        $.fancybox({
            'padding': 0,
            'margin': 0,
            'autoSize': true,
            'autoDimensions': false,
            'scrolling': 'no',
            'type': 'ajax',
            'href': window.location.pathname + '?popup=Y&ID=' + userID
        });
    });

    $(document).on('submit', 'form[name=inworking]', function (ev) {
        ev.preventDefault();
        BX.showWait();
        var datastring = $(this).serialize();
        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: datastring,
            success: function(data) {
                $('.storage').html($(data).html());
                $.fancybox.close();
                BX.closeWait();
            },
            error: function() {
                alert('error handing here');
                $.fancybox().close();
                BX.closeWait();
            }
        });
    });


    $(document).on('click', 'a.to-delete', function (e) {
        e.preventDefault();

        var userID = $(this).data('id');

        $.fancybox({
            'padding': 0,
            'margin': 0,
            'autoSize': true,
            'autoDimensions': false,
            'scrolling': 'no',
            'type': 'ajax',
            'href': window.location.pathname + '?popup=Y&action=delete&ID=' + userID
        });
    });

    $(document).on('submit', 'form[name=todelete]', function (ev) {
        ev.preventDefault();
        BX.showWait();
        var datastring = $(this).serialize();
        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: datastring,
            success: function(data) {
                $('.storage').html($(data).html());
                $.fancybox.close();
                BX.closeWait();
            },
            error: function() {
                alert('error handing here');
                $.fancybox().close();
                BX.closeWait();
            }
        });
    });

    $(document).on('click', 'button[name=toDelete]', function (ev) {
        ev.preventDefault();
        var checked = $('.storage input[name=checkToDeleted]:checked');
        if(checked.length){
            var values = [];
            checked.each(function (index, item) {
                values.push($(item).val());
            })
            values = values.join(',');
            $.fancybox({
                'padding': 0,
                'margin': 0,
                'autoSize': true,
                'autoDimensions': false,
                'scrolling': 'no',
                'type': 'ajax',
                'href': window.location.pathname + '?popup=Y&action=deleteMass&items=' + values,
            });
        }else{
            alert('No cheсked checkbox');
        }
    });

    $(document).on('submit', 'form[name=todeletemass]', function (ev) {
        ev.preventDefault();
        BX.showWait();
        var datastring = $(this).serialize();
        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: datastring,
            success: function(data) {
                $('.storage').html($(data).html());
                $.fancybox.close();
                BX.closeWait();
            },
            error: function() {
                alert('error handing here');
                $.fancybox().close();
                BX.closeWait();
            }
        });
    });
});