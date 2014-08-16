
var VisualEditor = function ($self) {
	var self = this;

	self.vars = {
		instance: null,
		downInEditor: false,
		counter: 1,
		maxWidth: 520,
		lastFocusedElement: null,
		uploadQueue: {},
		image_upload_url: '',
		afterUploads: function(){}
	};

	self.CKEDITOR = CKEDITOR;

	self.selectors = {
		button: '.add_photo_btn'
	};

	self.dom = {
		$menu : null,
		$imageInserter : null
	};

	self.actions = {
		remove: function($widget){
			$.each(self.vars.instance.widgets.instances, function(ind, widget){
				if (widget.name == 'inTextImage' && widget.element.$ === $widget[0]){
					self.vars.instance.widgets.destroy(widget);
					//widget.destroy();
					$self.parent().find(widget.element.$).remove();
				}
			});
		},
		rotateLeft: function($widget){
			var degree = parseInt($widget.attr('data-degree')),
				source = $widget.attr('data-source'),
				id = $widget.attr('data-id');

			$widget.find('img').attr('src','');

			if (degree < 270){
				degree += 90;
			}else{
				degree = 0;
			}

			self.insertPhoto(source, id, degree);
		},
		rotateRight: function($widget){
			var degree = parseInt($widget.attr('data-degree')),
				source = $widget.attr('data-source'),
				id = $widget.attr('data-id');

			$widget.find('img').hide();

			if (degree > 0){
				degree -= 90;
			}else{
				degree = 270;
			}

			self.insertPhoto(source, id, degree);
		}
	};

	self.templates = {
		inTextImage: '<div class="inTextImage" data-id="%id%">' +
			'<div class="controls">' +
			'<div class="control" data-action="remove" title="Удалить"></div>' +
			'<div class="control" data-action="rotateRight" title="Повернуть"></div>' +
			'<div class="control" data-action="rotateLeft" title="Повернуть"></div>' +
			'</div>' +
			'<imgg></imgg>' +
			/*'<div class="image_description">' +
			'<input data-inWidget="1" class="description" placeholder="Описание фотографии" />' +
			'<input data-inWidget="1" class="author" placeholder="Кто фотографировал?" />' +
			'</div>' +*/
			'</div><div data-role="afterWidget"></div>'
	};

	self.init = function(){

		self.dom.$menu = $(
			'<div class="editor_menu">' +
				'<ul>' +
				'<li data-action="bold"></li>' +
				'<li data-action="italic"></li>' +
				'<li data-action="underline"></li>' +
				'<li class="separator"></li>' +
				'<li data-action="link"></li>' +
				'<li class="separator"></li>' +
				'<li data-action="justifyleft"></li>' +
				'<li data-action="justifycenter"></li>' +
				'<li data-action="justifyright"></li>' +
				'<li class="separator"></li>' +
				'<li data-action="font_size" data-parameter="small"></li>' +
				'<li data-action="font_size" data-parameter="middle"></li>' +
				'<li data-action="font_size" data-parameter="large"></li>' +
				'<li class="separator"></li>' +
				'<li data-action="blockquote"></li>' +
				'</ul>' +
				'<div class="tail"></div>' +
				'</div>'
		);
		$self.parent().append(self.dom.$menu);

		self.dom.$imageInserter = $(
			'<div class="media_elements_adder">' +
				'<div class="add_photo_btn body" title="Добавьте фотографию к посту">' +
				'<input class="fileupload inText" type="file" name="image" data-url="' + self.vars.image_upload_url + '" multiple>' +
				'</div>' +
				'<div class="label">Вставить изображение</div>' +
				'</div>'
		);
		$self.parent().append(self.dom.$imageInserter);

		if(!self.CKEDITOR.plugins.get('font_size')){
			self.CKEDITOR.plugins.add( 'font_size', {
				init: function( editor ) {
					var styles = {
						small: new self.CKEDITOR.style({
							styles: {
								'font-size': '10px',
								'line-height': '140%'
							}
						}),
						middle: new self.CKEDITOR.style({
							styles: {
								'font-size': '14px',
								'line-height': '140%'
							}
						}),
						large: new self.CKEDITOR.style({
							styles: {
								'font-size': '18px',
								'line-height': '140%'
							}
						})
					};

					editor.addCommand( 'font_size', {
						exec : function( editor, parameter) {
							$.each(styles, function(name, style){
								if (style.checkActive(editor.elementPath(), editor)){
									editor.removeStyle( style );
								}
							});
							editor.applyStyle(styles[parameter]);
						}
					});

					editor.on('change', function(){
						if (!self.vars.instance.getSelection().getSelectedText() ||
							!self.vars.instance.getSelection().getSelectedText().length){
							self.dom.$menu.hide();
						}
					});
				}
			});
		}

		self.CKEDITOR.inline($self.attr('id'), {
			entities: true,
			autoParagraph: true,
			enterMode: 1,
			toolbarStartupExpanded: false,
			removePlugins: 'toolbar,contextmenu,liststyle,tabletools',
			extraPlugins: 'font_size',
			forcePasteAsPlainText: true,
			allowedContent: {
				"ol p pre ul i b a strong br u em blockquote span": {
					attributes: ['href','title'],
					styles: ['text-align','font-weight','font-style']
				},
				"img" : {
					attributes: ['src','alt','width','height'],
					styles: ['width','height']
				},
				"div": {
					attributes: ['class','data-*','title'],
					classes: ['inTextImage','controls','control','image_description']
				},
				"input": {
					attributes: ['placeholder'],
					classes: ['description','author'],
					match: function( element ) {
						return element.attributes[ 'data-inwidget' ] == "1";
					}
				},
				"imgg" : {}
			}
		}).on('instanceReady', self.onReady);

		self.dom.$menu.find('li').on('click', function(e){
			self.vars.instance.focus();
			self.vars.instance.execCommand($self.parent().find(this).attr('data-action'), $self.parent().find(this).attr('data-parameter'));
			return false;
		});

		$(document).on('click', function(){
			if (self.vars.instance.focusManager.currentActive == null ||
				!self.vars.instance.focusManager.currentActive.hasFocus ||
				!self.vars.instance.getSelection().getSelectedText() ||
				!self.vars.instance.getSelection().getSelectedText().length){
				self.dom.$menu.hide();
			}
		});

		$(document).on('mousedown', function(){
			self.vars.downInEditor = false;
		});

		$(document).on('mouseup', function(){
			if (!self.vars.downInEditor){
				return false;
			}

			var sel = self.vars.instance.getSelection();
			var txt = sel.getSelectedText();

			window['sel'] = sel;

			if (txt && txt.trim().length){
				var range = sel.getRanges()[0],
					rightWall = $self.parent().find(range.root.$).offset().left + $self.parent().find(range.root.$).width(),
					leftWall = $self.parent().find(range.root.$).offset().left;

				var selectionCoordinates = self.getSelectionCoords();

				sel.selectRanges([range]);

				var startPosition = selectionCoordinates.start,
					endPosition = selectionCoordinates.end;

				if (Math.abs(startPosition.left - startPosition.right) < 2){
					startPosition.top += startPosition.height + 2;
					startPosition.left = endPosition.left + 0;
					startPosition.right = endPosition.right + 0;
				}

				if (endPosition.right < startPosition.left){
					endPosition.right = startPosition.right + 0;
				}

				var menuPosition = {
						top: startPosition.top - 45,
						left: startPosition.left - 170
					},
					rangeWidth = endPosition.right - startPosition.left;

				rangeWidth = rangeWidth < 0 ? 0 : rangeWidth;

				var tailMargin = 158;
				var oldLeft = menuPosition.left + 0;

				if (startPosition.left < endPosition.right && startPosition.left < rightWall){
					menuPosition.left += (endPosition.right - startPosition.left) / 2;
				}

				if (menuPosition.left + 340 > rightWall){
					menuPosition.left = rightWall - 340;
				}

				if (menuPosition.left < leftWall){
					menuPosition.left = leftWall;
				}

				tailMargin += (oldLeft - menuPosition.left) + rangeWidth / 2;
				tailMargin = (tailMargin < 4)? 4 : tailMargin;

				self.dom.$menu.css(menuPosition).show();
				self.dom.$menu.find('.tail').css('margin-left', tailMargin);
			}
		});
	};

	self.initUploader = function(){
		$self.parent().find('.fileupload').fileupload({
			dataType: 'json',
			add: function(e, data){
				self.insertContainer(data, this);
			}
		});

		$(document).on('mousedown', '.fileupload.inText', function(e){
			self.vars.lastFocusedElement = document.activeElement;
		});

		$(document).on('click', '.inTextImage .image_description input', function(e){
			$self.parent().find(this).focus();
		});

		$(document).on('click', '.inTextImage > .controls > .control', function(){
			var $widget = $self.parent().find(this).closest('.inTextImage');
			self.actions[$self.parent().find(this).attr('data-action')]($widget);
		});
	};

	self.insertPhoto = function(source, id, degree){
		var img = new Image(),
			element = $self.parent().find('.inTextImage[data-id="' + id + '"]');

		element.attr('data-source', source);
		element.attr('data-degree', degree);
		img.onload = function(){
			var ratio = img.width / img.height,
				width = (img.width > self.vars.maxWidth) ? self.vars.maxWidth : img.width;
			element.css({
				width: width,
				height: width / ratio
			});
			element.find('imgg,img').replaceWith(img).show();

			if (self.queueIsEmpty()){
				self.vars.afterUploads();
			}
		};
		img.src = '/netcat/rusplt_image_get.php?source=' + encodeURI(source) + '&degree=' + degree;
	};

	self.insertContainer = function(data, caller){
		var inTextImage = $self.parent().find(caller).hasClass('inText'),
			id = ++self.vars.counter;

		self.vars.uploadQueue[id] = true;

		if (self.vars.lastFocusedElement && $self.parent().find(self.vars.lastFocusedElement.parentElement).hasClass('image_description')){
			var range = self.vars.instance.createRange();
			range.moveToElementEditablePosition( self.vars.instance.editable(), true ); // bar.^</p>
			self.vars.instance.getSelection().selectRanges( [ range ] );
		}

		function focusAfter(){
			var elementCollection = $self.parent().find(self.vars.instance.document.$).find('div[data-role="afterWidget"]');
			var nodeArray = [];
			if (elementCollection.length){
				for (var i = 0; i < elementCollection.length; ++i) {
					nodeArray[i] = new self.CKEDITOR.dom.element( elementCollection[ i ] );
				}
				var rangeObjForSelection = new self.CKEDITOR.dom.range( self.vars.instance.document );
				rangeObjForSelection.selectNodeContents( nodeArray[ nodeArray.length - 1 ] );
				self.vars.instance.getSelection().selectRanges( [ rangeObjForSelection ] );
			}
			//elementCollection.remove();
		}

		if (inTextImage){
			$self.parent().find(self.vars.instance.document.$).find('div[data-role="afterWidget"]').remove();

			var textData = $self.parent().find(self.vars.instance.getData()).text().trim();

			if (textData == ""){
				self.vars.instance['setData'](self.vars.instance.getData() + self.fillPlaceholders(self.templates.inTextImage, {
					id: id
				}), focusAfter);
			}else{
				self.vars.instance['insertHtml'](self.fillPlaceholders(self.templates.inTextImage, {
					id: id
				}), focusAfter)
			}
		}

		var jqXHR = data.submit()
			.success(function(data){
				if (data == false){

					alert('Неверный формат файла (поддерживаются: jpg/jpeg/png)');

				}else if (data.result == "ok"){
					var source = data.filepath;

					if (inTextImage){
						self.insertPhoto(source, id, 0);
					}else{
						$self.parent().find('.add_photo_btn.head').closest('.upload_area').hide();
						$self.parent().find('.photo_container.head').html('<img src="' + source + '" />').show();
						$self.parent().find('input[name="f_post_previewImage"]').val(source);
					}
				}else{

					alert('Невозможно загрузить изображение');

				}

				delete self.vars.uploadQueue[id];
			});
	};

	self.queueIsEmpty = function(){
		return (JSON.stringify(self.vars.uploadQueue) == '{}');
	};

	self.onReady = function(event){
		self.vars.instance = jQuery.extend(true, {}, event.editor);

		$self.parent().find(self.vars.instance.container.$).on('mousedown', function(e){
			self.vars.instance.getSelection().removeAllRanges();
			self.vars.downInEditor = true;
			e.stopPropagation();
		});

		$self.parent().find(self.vars.instance.container.$).on('mouseup', function(e){
			e.originalEvent.returnValue = false;
		});

		self.vars.instance.widgets.add( 'inTextImage', {
			upcast: function( element ) {
				// Defines which elements will become widgets.
				if ( element.hasClass( 'inTextImage' ) )
					return true;
			},
			init: function() {

			},
			draggable: false,
			nestedEditable: false,
			contentEditable: false
		} );



		$(document).on('scroll', function(){
			var editor = self.vars.instance.container.$,
				editorTop = $(editor).offset().top,
				editorLeft = $(editor).offset().left + $(editor).width() + 20;

			var $button = $self.parent().find('.media_elements_adder');


			$button.css({
				left: editorLeft
			});

			$button.css({
				top: editorTop,
				bottom: 'auto'
			});

		});
		$(document).trigger('scroll');

	};

	self.getSelectionCoords = function() {
		var sel = document.selection, range, rect, rect2, rectArr;
		var x = 0, y = 0, x2 = 0, y2 = 0;
		if (sel) {
			if (sel.type != "Control") {
				range = sel.createRange();
				range.collapse(true);
				x = range.boundingLeft;
				y = range.boundingTop;
			}
		} else if (window.getSelection) {
			sel = window.getSelection();
			if (sel.rangeCount) {
				range = sel.getRangeAt(0).cloneRange();
				if (range.getClientRects) {

					rectArr = range.getClientRects();

					$.each(rectArr, function(ind, r){
						if (rect === undefined && r.left != r.right){
							rect = $.extend({}, r);
						}
						if (ind == rectArr.length - 1){
							rect2 = $.extend({}, r);
						}
					});
				}
				// Fall back to inserting a temporary element
				if (rect === undefined && rect2 === undefined) {
					var span = document.createElement("span");
					if (span.getClientRects) {
						// Ensure span has dimensions and position by
						// adding a zero-width space character
						span.appendChild( document.createTextNode("\u200b") );
						range.insertNode(span);
						rectArr = span.getClientRects();

						$.each(rectArr, function(ind, r){
							if (rect === undefined && r.left != r.right){
								rect = $.extend({}, r);
							}
							if (ind == rectArr.length - 1){
								rect2 = $.extend({}, r);
							}
						});

						var spanParent = span.parentNode;
						spanParent.removeChild(span);

						// Glue any broken text nodes back together
						spanParent.normalize();
					}
				}

				var scrollTop = $(window).scrollTop();
				if (rect === undefined){
					rect = {
						top: 0,
						bottom: 0,
						left: 0,
						right: 0
					};
				}
				if (rect2 === undefined){
					rect2 = {
						top: 0,
						bottom: 0,
						left: 0,
						right: 0
					};
				}
				rect.top += scrollTop;
				rect.bottom += scrollTop;
				rect2.top += scrollTop;
				rect2.bottom += scrollTop;
			}
		}
		return { start: rect, end: rect2 };
	};

	self.fillPlaceholders = function(str, placeholders){
		$.each(placeholders, function(placeholder, value){
			var reg = new RegExp('%' + placeholder + '%', 'g');
			str = str.replace(reg, value);
		});
		return str;
	};

};
$(function () {
	$('.wsRedactor textarea').each(function () {
		var id = $(this).attr('id');
		listEditor[id] = new VisualEditor($(this));
		listEditor[id].init();
		listEditor[id].initUploader();
	});
});