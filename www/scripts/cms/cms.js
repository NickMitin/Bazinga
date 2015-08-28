/**
 * Created by xpundel on 01.07.14.
 */

var listEditor = {};

$(document).ready(function () {
	$('.add-new-item').on('click', function () {
		$.ajax({
			url: "./",
			type: "PUT",
			dataType: "json"
		}).done(function (json) {
			var addUrl;
			if ($('.add-new-item').data('edit-button')) {
				addUrl = $('.add-new-item').data('edit-button') + '/';
			} else {
				addUrl = '';
			}
			document.location.href = location.pathname + json.id + '/' + addUrl;
		});
	});


	$('.add-new-subform-item').on('click', function () {
		$.ajax({
			url: './' + $(this).data('object-name') + '/',
			type: 'PUT',
			dataType: 'html'
		}).done(function ($tr) {
			$('.courses-list TBODY').append($tr);
		});
		return false;
	});


	$('.cms-page .module-content .cms-module-list table td button.status ').on('click', function () {
		var tr = $(this).closest('tr')
		$.ajax({
			url: "./" + tr.data('id') + "/",
			data: {status: true},
			type: "POST"
		}).done(function () {
			tr.toggleClass('inactive');
		});
	});

	$('.cms-page .module-content .cms-module-list table td button.delete ').on('click', function () {
		if (confirm('Удалить?')) {
			var tr = $(this).closest('tr')
			$.ajax({
				url: "./" + tr.data('id') + "/",
				type: "DELETE"
			}).done(function () {
				tr.remove();
			});
		}
	});
	$('tr[data-row-for-field="type"][data-row-for-object="user"] select').on('change', function () {
		if ($(this).val() == 100) {
			$('tr[data-row-for-field="acl"][data-row-for-object="user"]').hide();
		} else {
			$('tr[data-row-for-field="acl"][data-row-for-object="user"]').show();
		}
	}).trigger('change');


	$('.cms-page .two-column-layout .nav-panel .filter input').on('keyup', function () {
		var filterText = $(this).val();
		simpleStorage.set('objectsFilter', filterText);

		if (filterText.length > 1) {
			$('.cms-page .two-column-layout .nav-panel').addClass('has-filter');
		} else {
			$('.cms-page .two-column-layout .nav-panel').removeClass('has-filter');
		}

		$('.cms-page .two-column-layout .nav-panel li').removeClass('filtered')
		$('.cms-page .two-column-layout .nav-panel li[data-name]').each(function () {
			if ($(this).data('name')) {
				$(this).find('>a:not(.fa)').html($(this).data('name'));
				if ($(this).data('name').toString().toLowerCase().indexOf(filterText.toLowerCase()) > -1) {
					$(this).parents('li').add($(this)).addClass('filtered');

					if (filterText.length > 1) {
						var reg = new RegExp(filterText, 'gi');
						$(this).find('>a:not(.fa)').html($(this).data('name').replace(reg, function (str) {
							return '<span>' + str + '</span>'
						}));
					}

				}
			}
		})
	})

	if ($('.cms-page .two-column-layout').length) {
		$('.cms-page .two-column-layout .nav-panel .filter input').val(simpleStorage.get('objectsFilter')).trigger('keyup');

		if ($('.cms-page .two-column-layout .nav-panel li.active').length) {
			$('.cms-page .two-column-layout .nav-panel')[0].scrollTop = $('.cms-page .two-column-layout .nav-panel li.active').offset().top - 200;
		}

		$(document).on('scroll', function () {
			if ($(document).scrollTop() >= $('.cms-page .two-column-layout .main-panel').offset().top) {
				$('.cms-page .two-column-layout .nav-panel').css('position', 'fixed').css('top', 0);
			} else {
				$('.cms-page .two-column-layout .nav-panel').css('position', 'absolute').css('top', '');
			}
		});
	}

	if ($('#SaveForm').data('form-saved')) {
		setTimeout(function () {
			$.growl.notice({
				location: 'br',
				message: "",
				size: "small",
				title: $('#SaveForm').data('form-saved')
			});
		}, 500);
	}
});


$(document).on('click', 'input[name="clone-map"]', function () {
	$.ajax({
		url: "./",
		type: "POST",
		data: {ajaxCms: 'cloneObject'},
		dataType: "json"
	}).done(function (data) {
		var url = document.location.href.replace(/\/?$/, '').replace(/\d+$/, data.id) + '/';
		document.location.href = url;
	});
	return false;
});



