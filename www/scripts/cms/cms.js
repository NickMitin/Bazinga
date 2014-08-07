/**
 * Created by xpundel on 01.07.14.
 */
$(document).ready(function () {
	$('.add-new-item').on('click', function () {
		$.ajax({
			url: "./",
			type: "PUT",
			dataType: "json"
		}).done(function (json) {
			document.location.href = document.location.href + json.id + '/';
		});
	});


	//todo: сделать подгрузку кастомных скриптов и стилей - и перенести это туда!!!!!
	$('.add-new-course').on('click', function () {
		$.ajax({
			url: "./course/",
			type: "PUT",
			dataType: "html",
		}).done(function ($tr) {
			$('.courses-list TBODY').append($tr);
		});
	});

	if ($('.list-image-item img').size() > 0)
	{
		$('.list-image-item img').each(function () {
			var $self = $(this);
			$self.load(function () {
				var width = $self.width();
				$self.parents('.list-image-item').css({
					'width': (width + 20)
				});
				$self.parents('.list-image-item').find('input').css({
					'width': (width-12)
				});
			});
		});
	}

});

$(document).on('click', '.list-image-item-remove-text a', function () {
	$(this).parents('.list-image-item').find('.list-image-item-remove-text').hide();
	$(this).parents('.list-image-item').find('.list-image-item-remove-input').val(0);
	$(this).parents('.list-image-item').find('.list-image-item-block').show();
	return false;
});

$(document).on('click', '.list-image-item-remove', function () {
	$(this).parents('.list-image-item').find('.list-image-item-remove-text').show();
	$(this).parents('.list-image-item').find('.list-image-item-remove-input').val(1);
	$(this).parents('.list-image-item').find('.list-image-item-block').hide();
	return false;
});


