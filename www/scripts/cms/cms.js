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
			document.location.href = document.location.href + json.id + '/';
		});
	});


	//todo: сделать подгрузку кастомных скриптов и стилей - и перенести это туда!!!!!
	$('.add-new-course').on('click', function () {
		$.ajax({
			url: "./course/",
			type: "PUT",
			dataType: "html"
		}).done(function ($tr) {
			$('.courses-list TBODY').append($tr);
		});
	});

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



