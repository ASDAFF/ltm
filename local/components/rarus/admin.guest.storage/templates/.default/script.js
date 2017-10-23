/**
 * Created by dmitry on 28.05.2016.
 */
$(document).ready(function () {

	//сворачивание разворачивание строк таблицы
	$('.table tbody').on('dblclick', 'tr', function () {
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


	$('.table tbody').on('click', 'a.in-working', function (e) {
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

	$(document).on('submit', 'form[name=inworking]', function(){BX.showWait();});


	$('.table tbody').on('click', 'a.to-delete', function (e) {
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

	$(document).on('submit', 'form[name=todelete]', function(){BX.showWait();});
});