function objectPropertyOf(obj, propertyValue) {
	var result = '';

	for (var propertyName in obj) {
		if (obj[propertyName] == propertyValue) {
			result = propertyName;
			break;
		}
	}

	return result;
}


function arrayCompare(arrayA, arrayB) {
	// if the other array is a falsy value, return
	if ((!arrayA) || (!arrayB))
		return false;

	// compare lengths - can save a lot of time
	if (arrayA.length != arrayB.length)
		return false;

	for (var i = 0; i < arrayA.length; i++) {
		// Check if we have nested arrays
		if (arrayA[i] instanceof Array && arrayB[i] instanceof Array) {
			// recurse into the nested arrays
			if (!arrayCompare(arrayA[i], arrayB[i]))
				return false;
		}
		else if (arrayA[i] != arrayB[i]) {
			// Warning - two different object instances will never be equal: {x:20} != {x:20}
			return false;
		}
	}

	return true;
}


function objectCompare(objectA, objectB) {
	// if the other object is a falsy value, return
	if ((!objectA) || (!objectB))
		return false;

	// compare lengths - can save a lot of time
	if (Object.keys(objectA).length != Object.keys(objectB).length)
		return false;

	for (var i in objectA) {
		// Check if we have nested objects
		if (objectB.hasOwnProperty(i)) {
			if (objectA[i] instanceof Object && objectB[i] instanceof Object) {
				// recurse into the nested objects
				if (!objectCompare(objectA[i], objectB[i]))
					return false;
			}
			else if (objectA[i] != objectB[i]) {
				// Warning - two different object instances will never be equal: {x:20} != {x:20}
				return false;
			}
		}
		else {
			return false;
		}
	}

	return true;
}


$.renderTemplate = function (templateName, data) {
	var fullTemplate = document.getElementById(templateName);
	if (fullTemplate != null) {
		var template = fullTemplate.innerHTML.replace('<!--', '').replace('-->', '');
		if (data) {
			for (var i in data) {
				var pattern = new RegExp('{\\$' + i + '}', 'g');
				template = template.replace(pattern, data[i]);
			}
		}
		return template;
	}
	else {
		console.error('template "' + templateName + '" does not exist');
		return '';
	}
};


$.fn.hasAttr = function (attrName) {
	var attr = $(this).attr(attrName);

	return ((typeof attr !== 'undefined') && (attr !== false));
};


$.fn.getPatternIndex = function () {
	var index = -1;

	if ($(this).hasAttr('id')) {
		var matches = $(this).attr('id').match(/patternIndex_(\d+)/);
		if (matches && matches[1].length) {
			index = parseInt(matches[1]);
		}
	}

	return index;
}


var routerSettings = {}, routes = [], routeIndexes = {}, lastRouteIndex = 0;
abstractClasses = [], fieldTypes = [],
	formAttachedAttrName = 'data-formAttached', defaultAbstractClass = 'bmHTMLPage', debugLogHandlerPrototype = false;


function updateItemListPosition(routeIndex) {
	if (routeIndex >= 0) {
		if ((routerSettings.hasOwnProperty('features')) &&
			(routerSettings.features.hasOwnProperty('liveRouteSorting')) &&
			(routerSettings.features.liveRouteSorting === true)) {
			// find the 'sorted' position for new one element
			var $currentItem = $('#jsRouter #patternIndex_' + routeIndex);
			if ($currentItem.length) {
				var routePattern = routeIndexes[routeIndex], insertBeforeIndex;
				for (var rIndex in routeIndexes) {
					if (routePattern < routeIndexes[rIndex]) {
						insertBeforeIndex = rIndex;
						break;
					}
				}

				if (insertBeforeIndex >= 0) {
					var $insertBeforeItem = $('#jsRouter #patternIndex_' + insertBeforeIndex);
					if ($insertBeforeItem.length) {
						// move DOM node
						$insertBeforeItem.before($currentItem.detach());

						scrollToPatternAtIndex(routeIndex);
					}
				}
			}
		}
	}
}


function updateAllSelectedOptionsToCurrentState(itemBlock) {
	if (itemBlock.length) {
		itemBlock.find('select option[data-current]').each(function () {
			$(this).removeAttr('data-current');
		});

		itemBlock.find('select option[data-selected]').each(function () {
			$(this).attr('data-current', '');
		});
	}
}


function scrollToPatternAtIndex(patternIndex) {
	if (patternIndex >= 0) {
		var $itemBlock = $('#jsRouter #patternIndex_' + patternIndex),
			$itemGroup = $itemBlock.closest('.jsItemGroupBlock');

		if ($itemGroup.length) {
			if (!($itemGroup.find('.jsToggleSpoilerBlock').first().hasClass('adminSection-parametersList-spoilerOpen'))) {
				$itemGroup.find('.jsToggleSpoilerClickArea').first().trigger('click');
			}
		}

		if (!($itemBlock.find('.jsToggleSpoilerBlock').hasClass('adminSection-parametersList-spoilerOpen'))) {
			$itemBlock.find('.jsToggleSpoilerClickArea').trigger('click');
		}


		var animationTime = 0;
		if ((routerSettings.hasOwnProperty('features')) &&
			(routerSettings.features.hasOwnProperty('allowAnimation')) &&
			(routerSettings.features.allowAnimation === true)) {
			animationTime = 300;
		}

		$('html, body').animate(
			{
				scrollTop: $itemBlock.offset().top
			},
			animationTime
		);
	}
}


function clonePatternParametersToForm(cloneFromRouteIndex, cloneToRouteIndex) {
	var ok = false;

	if ((cloneFromRouteIndex >= 0) && (cloneToRouteIndex >= 0)) {
		if (routeIndexes.hasOwnProperty(cloneFromRouteIndex)) {
			var cloneParameters = routes[routeIndexes[cloneFromRouteIndex]].parameters || {},
				$itemBlock = $('#patternIndex_' + cloneToRouteIndex),
				$formParametersTable = $itemBlock.find('.jsRouterParametersTable');

			if ($itemBlock.length && $formParametersTable.length) {
				// clear
				$formParametersTable.html('');

				if (Object.keys(cloneParameters).length > 0) {
					var routerParameters = '', selectOptionsFieldTypes = $('#jsSelectOptionsFieldTypes').html();

					for (var key in cloneParameters) {
						routerParameters += $.renderTemplate(
							'jsPatternParameterTemplate',
							{
								'routeParameterName': key,
								'fieldType': cloneParameters[key],
								'selectOptionsFieldTypes': selectOptionsFieldTypes
							}
						);
					}

					if (routerParameters != '') {
						$formParametersTable.html(routerParameters);

						// set fieldType select at proper value (routes[routePattern].parameters[key])
						for (key in cloneParameters) {
							setFieldTypeSelectorForParameterNameAndRoutePattern(routeIndexes[cloneToRouteIndex], key, cloneParameters[key]);
						}

						$formParametersTable.find('input[name="routeParameterName"]').each(function () {
							$(this).attr('data-savedValue', '');
						});

						$itemBlock.find('.jsSaveRouteAction').show();
					}
				}
			}
		}
	}

	return ok;
}


function getComplexRoutes() {
	var complexRoutesTmp = {}, complexRoutes = [];

	for (var routeIndex in routes) {
		if (!complexRoutesTmp.hasOwnProperty(routes[routeIndex].route)) {
			complexRoutesTmp[routes[routeIndex].route] = [];
		}

		complexRoutesTmp[routes[routeIndex].route].push(routeIndex);
	}

	for (var routePath in complexRoutesTmp) {
		if (complexRoutesTmp[routePath].length >= 2) {
			complexRoutes.push(routePath);
		}
	}

	return complexRoutes;
}


function getCurrentFormParameters(itemBlock) {
	var routeParameters = {};

	itemBlock.find('.jsRouterParametersTable tr').each(function () {
		var routeParameterName = $.trim($(this).find('input[name="routeParameterName"]').val()),
			routeParameterType = $.trim($(this).find('select[name="routeParameterType"]').val());

		if ((routeParameterName != '') && (routeParameterType >= 0)) {
			routeParameters[routeParameterName] = parseInt(routeParameterType);
		}
	});

	return routeParameters;
}


function checkParametersOnDuplicates(itemBlock) {
	var hasDuplicates = false, routeParameters = {};


	itemBlock.find('.jsRouterParametersTable tr').each(function () {
		var $inputParameterName = $(this).find('input[name="routeParameterName"]'),
			routeParameterName = $.trim($inputParameterName.val());

		if (routeParameterName != '') {
			if (!routeParameters.hasOwnProperty(routeParameterName)) {
				routeParameters[routeParameterName] = $inputParameterName;  // save a link to parameter input
				$inputParameterName.removeClass('errorField');
			}
			else {
				// duplicates
				hasDuplicates = true;

				routeParameters[routeParameterName].addClass('errorField');
				$inputParameterName.addClass('errorField');
			}
		}
	});


	return hasDuplicates;
}


function saveRoute(routePattern, successFunction) {
	var ajaxURL = '/admin/router/rp/savePattern/',
		errorText = 'Невозможно сохранить паттерн "' + routePattern + '".';
	;


	var dataObject = {
		'pattern': routePattern,
		'route': routes[routePattern].route,
		'class': routes[routePattern].class,
		'parentClass': abstractClasses[routes[routePattern].parentClass]
	};

	var arrayIndex = 0;
	for (var patternParameterName in routes[routePattern].parameters) {
		var parameterName = 'parameterName[' + arrayIndex + ']',
			parameterType = 'parameterType[' + arrayIndex + ']';

		dataObject[parameterName] = patternParameterName;
		dataObject[parameterType] = routes[routePattern].parameters[patternParameterName];

		arrayIndex++;
	}

	$.ajax(
		{
			url: ajaxURL,
			type: 'post',
			async: false,
			dataType: 'json',
			data: dataObject,
			success: function (data) {
				if (data.result == 'ok') {
					if (successFunction) {
						successFunction();
					}
				}
				else {
					alert(errorText);
					console.error(errorText);
				}
			},
			error: function (data) {
				alert(errorText);
				console.error(errorText);
			}
		});
}


function deleteRoute(routePattern, successFunction) {
	if (isNewPatternName(routePattern)) {
		if (successFunction) {
			successFunction();
		}
	}
	else {
		var ajaxURL = '/admin/router/rp/deletePattern/',
			errorText = 'Невозможно удалить паттерн "' + routePattern + '".';

		$.ajax(
			{
				url: ajaxURL,
				type: 'post',
				async: false,
				dataType: 'json',
				data: {
					'pattern': routePattern
				},
				success: function (data) {
					if (data.result == 'ok') {
						if (successFunction) {
							successFunction();
						}
					}
					else {
						alert(errorText);
						console.error(errorText);
					}
				},
				error: function (data) {
					alert(errorText);
					console.error(errorText);
				}
			});
	}
}


function routeFormHasChanges(routeIndex) {
	var result = false,
		routeIndex = parseInt(routeIndex);

	$itemBlock = $('#jsRouter #patternIndex_' + routeIndex);
	if ($itemBlock.length) {
		$itemBlock.find('input,select').each(function () {
			var currentValue = $.trim($(this).val()),
				savedValue = $(this).attr('data-savedValue');

			if (currentValue != savedValue) {
				result = true;
				return false;  // break;
			}
		});
	}

	if (!result && $itemBlock.hasAttr(formAttachedAttrName)) {
		// check route parameters
		var currentParameters = getCurrentFormParameters($itemBlock),
			savedParameters = (routes[routeIndexes[routeIndex]].hasOwnProperty('parameters')) ?
				routes[routeIndexes[routeIndex]].parameters : {};

		result = (!objectCompare(currentParameters, savedParameters));
	}

	return result;
}


function isNewPatternName(patternName) {
	var matches = patternName.match(/newPattern_(\d+)/);
	return (matches && matches[1].length);
}


function setAbstractClassSelectorForRoutePattern(routePattern, abstractClass) {
	var patternIndex = objectPropertyOf(routeIndexes, routePattern);
	if (patternIndex >= 0) {
		var $select = $('#patternIndex_' + patternIndex + ' .jsFormBlock select[name="routeBaseClassName"]');
		if ($select.length) {
			$select.find('option').each(function () {
				$(this).removeAttr('data-selected');
			});
			var $selectOption = $select.find('option[value="' + abstractClass + '"]');
			if ($selectOption.length) {
				$selectOption.prop('selected', true);
				$selectOption.attr('data-selected', '');

				if ($select.find('option[data-current]').length == 0) {
					$selectOption.attr('data-current', '');
				}
			}
			else {
				var alertText = 'Использован неизвестный абстрактный класс, нужно выбрать один из допустимых!';
				console.log(alertText);
				alert(alertText);
			}
		}
	}
	else {
		console.log('Unknown patternIndex for pattern "' + routePattern + '"');
	}
}


function setFieldTypeSelectorForParameterNameAndRoutePattern(routePattern, parameterName, fieldType) {
	var patternIndex = objectPropertyOf(routeIndexes, routePattern);
	if (patternIndex >= 0) {
		var $parameterNameInput = $('#patternIndex_' + patternIndex + ' .jsFormBlock input[name="routeParameterName"][value="' + parameterName + '"]');
		if ($parameterNameInput.length) {
			var $select = $parameterNameInput.closest('tr').find('select[name="routeParameterType"]');
			if ($select.length) {
				$select.find('option').each(function () {
					$(this).removeAttr('data-selected');
				});
				var $selectOption = $select.find('option[value="' + fieldType + '"]');
				if ($selectOption.length) {
					$selectOption.prop('selected', true);
					$selectOption.attr('data-selected', '');

					if ($select.find('option[data-current]').length == 0) {
						$selectOption.attr('data-current', '');
					}
				}
				else {
					var alertText = 'Использован неизвестный тип данных, нужно выбрать один из допустимых!';
					console.log(alertText);
					alert(alertText);
				}
			}
		}
	}
	else {
		console.log('Unknown patternIndex for pattern "' + routePattern + '"');
	}
}


function setDefaultFieldTypeSelectorForParameterNameAndPatternIndex(patternIndex) {
	if (patternIndex >= 0) {
		var fieldType = 1; // Текст
		var $select = $('#patternIndex_' + patternIndex + ' .jsFormBlock select[name="routeParameterType"]').last();
		if ($select.length) {
			$select.find('option').each(function () {
				$(this).removeAttr('data-selected');
			});
			var $selectOption = $select.find('option[value="' + fieldType + '"]');
			if ($selectOption.length) {
				$selectOption.prop('selected', true);
				$selectOption.attr('data-selected', '');

				if ($select.find('option[data-current]').length == 0) {
					$selectOption.attr('data-current', '');
				}
			}
			else {
				var alertText = 'Использован неизвестный тип данных, нужно выбрать один из допустимых!';
				console.log(alertText);
				alert(alertText);
			}
		}
	}
}


function routePropertyAlreadyExists(propertyName, propertyValue, inputBlock, useNonsavedParameters) {
	var useNonsavedParameters = (useNonsavedParameters === true) ? true : false,
		propertyValue = $.trim(propertyValue),
		propertyAlreadyExists = false,
		propertyValueRouteIndex = -1,
		$blockRoute = $(inputBlock).closest('.jsItemBlock'),
		blockRouteIndex = $blockRoute.attr('data-patternIndex'),
		parametersHintBlockHTML = '';

	if (propertyName != '') {
		for (var routeKey in routes) {
			if (propertyValue == routes[routeKey][propertyName]) {
				propertyValueRouteIndex = objectPropertyOf(routeIndexes, routeKey);

				if (blockRouteIndex != propertyValueRouteIndex) {
					propertyAlreadyExists = true;
					break;
				}
			}
		}

		if ($(inputBlock).length) {
			var $inputHintBlock = $(inputBlock).siblings().filter('.jsInputHintBlock');

			if (propertyAlreadyExists) {
				$inputHintBlock.attr('data-patternIndex', propertyValueRouteIndex);

				$(inputBlock).addClass('warningField');
				$inputHintBlock.show();


				var patternsList = getComplexPatternsHintListWithMixedParameters(routeIndexes[blockRouteIndex], propertyAlreadyExists, useNonsavedParameters);
				if (patternsList != '') {
					parametersHintBlockHTML = $.renderTemplate('jsParametersHintBlockTemplate', {'patternsList': patternsList });
				}
			}
			else {
				$inputHintBlock.removeAttr('data-patternIndex');

				$(inputBlock).removeClass('warningField');
				$inputHintBlock.hide();
			}

			$blockRoute.find('.jsParametersHintContainer').html(parametersHintBlockHTML);
		}
	}
}


function routeAlreadyExists(routePattern, inputBlock) {
	var routePattern = $.trim(routePattern);

	if ($(inputBlock).length) {
		var $inputHintBlock = $(inputBlock).siblings().filter('.jsInputHintBlock'),
			routeIndex = objectPropertyOf(routeIndexes, routePattern),
			blockRouteIndex = $(inputBlock).closest('.jsItemBlock').attr('data-patternIndex');

		if ((routeIndex != blockRouteIndex) && routes.hasOwnProperty(routePattern)) {
			if (routeIndex >= 0) {
				$inputHintBlock.attr('data-patternIndex', routeIndex);
			}

			$(inputBlock).addClass('errorField');
			$inputHintBlock.show();
		}
		else {
			$inputHintBlock.removeAttr('data-patternIndex');

			$(inputBlock).removeClass('errorField');
			$inputHintBlock.hide();
		}
	}
}


function buildRoutes() {
	routes = $.parseJSON($('#jsRoutesJSON').html() || {});
	abstractClasses = $.parseJSON($('#jsAbstractClassesJSON').html() || []);
	fieldTypes = $.parseJSON($('#jsFieldTypesJSON').html() || {});
	routerSettings = $.parseJSON($('#jsSettingsJSON').html() || {});


	var index = 0;
	for (var routeKey in routes) {
		routeIndexes[index] = routeKey;
		index++;
	}
	lastRouteIndex = index - 1;


	if ((abstractClasses.length > 0) && (Object.keys(fieldTypes).length > 0) && (Object.keys(routes).length > 0)) {
		var key, routeGroup = {}, routeGroupItemsCount = {}, groups = {},
			selectOptionsAbstractClasses = '', selectOptionsFieldTypes = '';

		for (key in abstractClasses) {
			selectOptionsAbstractClasses += $.renderTemplate(
				'jsSelectOptionTemplate',
				{
					'name': abstractClasses[key],
					'value': key
				}
			)
		}
		$('#jsSelectOptionsAbstractClasses').html(selectOptionsAbstractClasses);


		for (key in fieldTypes) {
			selectOptionsFieldTypes += $.renderTemplate(
				'jsSelectOptionTemplate',
				{
					'name': fieldTypes[key],
					'value': key
				}
			)
		}
		$('#jsSelectOptionsFieldTypes').html(selectOptionsFieldTypes);


		var minItemsCountInGroup = 2;
		if ((routerSettings.hasOwnProperty('main')) &&
			(routerSettings.main.hasOwnProperty('minItemsPerGroup')) &&
			(routerSettings.main.minItemsPerGroup >= 2)) {
			minItemsCountInGroup = routerSettings.main.minItemsPerGroup;
		}


		for (key in routes) {
			var routeParts = key.split('/');

			if (routeParts.length > 2) {
				if (!(routeParts[1] in routeGroupItemsCount)) {
					routeGroupItemsCount[routeParts[1]] = 0;
				}

				routeGroup[key] = routeParts[1];
				routeGroupItemsCount[routeParts[1]]++;

				if ((!(routeParts[1] in groups)) && (routeGroupItemsCount[routeParts[1]] >= minItemsCountInGroup)) {
					groups[routeParts[1]] = [routeParts[0], routeParts[1], '...'].join('/');
				}
			}
			else {
				routeGroup[key] = '';
			}
		}

		for (key in routeGroup) {
			if (!(routeGroup[key] in groups)) {
				routeGroup[key] = '';
			}
		}

		var spoilerItemHTML = '';
		for (key in routeGroup) {
			spoilerItemHTML = $.renderTemplate(
				'jsSpoilerItemTemplate',
				{
					'patternIndex': objectPropertyOf(routeIndexes, key),
					'pattern': key,
					'newItemClass': ''
				}
			);


			if (routeGroup[key] == '') {
				$('#jsRouter').append(spoilerItemHTML);
			}
			else {
				if ($('#jsRouter div[data-group="' + routeGroup[key] + '"]').length == 0) {
					$('#jsRouter').append($.renderTemplate(
						'jsSpoilerItemGroupTemplate',
						{
							'group': routeGroup[key],
							'pattern': groups[routeGroup[key]]
						}
					));
				}

				$('#jsRouter div[data-group="' + routeGroup[key] + '"] .jsToggleGroupSpoilerContent').append(spoilerItemHTML);
			}
		}
	}


	$('#jsSearchButton').attr('disabled', true);
	$('#jsSearchResetButton').attr('disabled', true);


	if ($('#jsSearchRouteInput').val() != '') {
		$('#jsSearchButton').removeAttr('disabled');
	}
}


function hideGroups() {
	if ((routerSettings.hasOwnProperty('main')) &&
		(routerSettings.main.hasOwnProperty('hiddenGroups')) &&
		($.isArray(routerSettings.main.hiddenGroups)) &&
		(routerSettings.main.hiddenGroups.length > 0)) {
		for (var hiddenGroupIndex in routerSettings.main.hiddenGroups) {
			$('#jsRouter .jsItemGroupBlock[data-group="' + routerSettings.main.hiddenGroups[hiddenGroupIndex] + '"]').hide();
		}
	}
}


function getComplexPatternsHintListWithMixedParameters(routePattern, patternAlreadyExists, useNonsavedParameters) {
	var patternsList = '',
		patternAlreadyExists = (patternAlreadyExists === true) ? true : false,
		useNonsavedParameters = (useNonsavedParameters === true) ? true : false;

	if (routePattern != '') {
		var complexRoutes = [], routeObject, patternIndex = objectPropertyOf(routeIndexes, routePattern);

		if (useNonsavedParameters || isNewPatternName(routePattern)) {
			if (patternIndex >= 0) {
				var $patternBlock = $('#jsRouter #patternIndex_' + patternIndex);
				if ($patternBlock.length) {
					routeObject = {
						'route': $.trim($patternBlock.find('input[name="routePath"]').val()),
						'class': $.trim($patternBlock.find('input[name="routeClass"]').val()),
						'parameters': getCurrentFormParameters($patternBlock)
					};
				}
			}
		}
		else {
			complexRoutes = getComplexRoutes();
			routeObject = routes[routePattern];

			if (!routeObject.hasOwnProperty('parameters')) {
				routeObject.parameters = {};  // for proper objectCompare
			}
		}


		if (patternAlreadyExists || (complexRoutes.indexOf(routeObject.route) >= 0)) {
			// find all complex pattern at certain route
			for (var routeIndex in routes) {
				if ((routes[routeIndex].route == routeObject.route) && (routeIndex != routePattern)) {
					var routeIndexObject = routes[routeIndex];

					if (!routeIndexObject.hasOwnProperty('parameters')) {
						routeIndexObject.parameters = {};  // for proper objectCompare
					}

					if (!objectCompare(routeIndexObject.parameters, routeObject.parameters)) {
						if (patternsList != '') {
							patternsList += ', ';
						}

						patternsList += $.renderTemplate(
							'jsComplexPatternParametersHintBlockTemplate',
							{
								'pattern': routeIndex,
								'patternIndex': objectPropertyOf(routeIndexes, routeIndex)
							}
						);
					}
				}
			}
		}
	}


	return patternsList;
}


function toggleItemForm(itemBlock) {
	var $formBlock = itemBlock.find('.jsFormBlock');
	if ($formBlock.length) {
		var routeIndex = itemBlock.getPatternIndex();
		var routePattern = routeIndexes[routeIndex] ? routeIndexes[routeIndex] : '';

		if (routePattern != '') {
			if (itemBlock.hasAttr(formAttachedAttrName)) {
				if (routeFormHasChanges(routeIndex)) {
					if (confirm('Форма была изменена, сохранить?')) {
						// save form
						itemBlock.find('.jsSaveRouteAction').trigger('click');
					}
					else {
						var savedValue = $itemBlock.find('input[name="route"]').attr('data-savedValue');
						$itemBlock.find('.jsToggleSpoilerClickArea').text(savedValue);
						$itemBlock.find('.jsSaveRouteAction').hide();
					}
				}

				// remove form
				$formBlock.html('');
				itemBlock.removeAttr(formAttachedAttrName);
			}
			else {
				// append form
				if (routes[routePattern] && Object.keys(routes[routePattern]).length > 0) {
					var routerParameters = '';

					if (routes[routePattern].parameters && (Object.keys(routes[routePattern].parameters).length > 0)) {
						var selectOptionsFieldTypes = $('#jsSelectOptionsFieldTypes').html();

						for (key in routes[routePattern].parameters) {
							routerParameters += $.renderTemplate(
								'jsPatternParameterTemplate',
								{
									'routeParameterName': key,
									'fieldType': routes[routePattern].parameters[key],
									'selectOptionsFieldTypes': selectOptionsFieldTypes
								}
							);
						}
					}

					var patternsList = '', newPatternFlag = isNewPatternName(routePattern);

					if (!newPatternFlag) {
						patternsList = getComplexPatternsHintListWithMixedParameters(routePattern);
					}

					var parametersHintBlockHTML = ((patternsList != '') ?
						$.renderTemplate('jsParametersHintBlockTemplate', {'patternsList': patternsList }) : '');

					$formBlock.html($.renderTemplate(
						'jsPatternFormTemplate',
						{
							'routePattern': (newPatternFlag ? '' : routePattern),
							'routePath': routes[routePattern].route,
							'routeClass': routes[routePattern].class,
							'routeBaseClass': routes[routePattern].parentClass,
							'selectOptionsAbstractClasses': $('#jsSelectOptionsAbstractClasses').html(),
							'routerParameters': routerParameters,
							'hintBlock': $.renderTemplate('jsPatternFormHintBlockTemplate'),
							'inputHintBlock': $.renderTemplate('jsPatternInputHintBlockTemplate'),
							'parametersHintBlock': parametersHintBlockHTML
						}
					));

					if (patternsList != '') {
						// enable save
						var $saveRouteBlock = $formBlock.closest('.jsItemBlock').find('.jsSaveRouteAction');
						if ($saveRouteBlock.length) {
							$saveRouteBlock.addClass('jsSaveAction');
							$saveRouteBlock.show();
						}
					}

					// set abstractClass select at proper value (@todo: send this value from server in 'routes')
					setAbstractClassSelectorForRoutePattern(routePattern, routes[routePattern].parentClass);

					// set fieldType select at proper value (routes[routePattern].parameters[key])
					for (key in routes[routePattern].parameters) {
						setFieldTypeSelectorForParameterNameAndRoutePattern(routePattern, key, routes[routePattern].parameters[key]);
					}

					itemBlock.attr(formAttachedAttrName, '');

					var $inputRoute = $formBlock.find('input[name="route"]');
					if ($inputRoute.length && ($.trim($inputRoute.val()) != '')) {
						routeAlreadyExists(routePattern, $inputRoute);
					}

					var $inputRoutePath = $formBlock.find('input[name="routePath"]');
					if ($inputRoutePath.length && ($.trim($inputRoutePath.val()) != '')) {
						routePropertyAlreadyExists('route', routes[routePattern].route, $inputRoutePath);
					}

					var $inputRouteClass = $formBlock.find('input[name="routeClass"]');
					if ($inputRouteClass.length && ($.trim($inputRouteClass.val()) != '')) {
						routePropertyAlreadyExists('class', routes[routePattern].class, $inputRouteClass);
					}

					checkParametersOnDuplicates(itemBlock);
				}
			}
		}
	}
}


function showRoutesByURL(url) {
	// reset all pattern blocks to visible state BEFORE search
	$('#jsRouter .jsItemBlock').show();
	$('#jsRouter .jsItemGroupBlock').each(function () {
		$(this).show();
	});

	url = $.trim(url);
	if (url != '') {
		var key, matches, routeFound = false;
		for (key in routes) {
			var keyPattern = key, keyFirstChar = key.charAt(0), keyLastChar = key.charAt(key.length - 1);
			if ((keyFirstChar == '~') && (keyLastChar == '~')) {
				// tilda chars
				keyPattern = key.substring(1, key.length - 1);
			}

			matches = url.match(keyPattern);
			if (matches && matches.length) {
				var patternIndex = objectPropertyOf(routeIndexes, key);
				if (patternIndex >= 0) {
					var $foundPatternBlock = $('#jsRouter #patternIndex_' + patternIndex);
					if ($foundPatternBlock.length) {
						var $patternGroupBlock = $foundPatternBlock.closest('.jsItemGroupBlock');
						if ($patternGroupBlock.length) {
							var $spoilerBlock = $patternGroupBlock.find('.jsToggleSpoilerBlock').first();
							if ($spoilerBlock.length) {
								if ($spoilerBlock.hasClass('adminSection-parametersList-spoilerClosed')) {
									$spoilerBlock.removeClass('adminSection-parametersList-spoilerClosed');
									$spoilerBlock.addClass('adminSection-parametersList-spoilerOpen');
									$spoilerBlock.find('.jsToggleSpoilerContent').first().toggle();
								}
							}
						}

						$('#jsRouter .jsItemGroupBlock').each(function () {
							if (!$(this).is($patternGroupBlock)) {
								$(this).hide();
							}
						});

						routeFound = true;
						console.log(patternIndex);

						$('#jsRouter .jsItemBlock').hide();
						$foundPatternBlock.show();
						break;
					}
				}
			}
		}

		if (!routeFound) {
			$('#jsSearchRouteInput').removeClass('adminSection-searchBlockInput-hasResults');
			$('#jsSearchRouteInput').addClass('adminSection-searchBlockInput-noResults');

			console.log('Nothing was found like "' + url + '".');
		}
		else {
			$('#jsSearchRouteInput').removeClass('adminSection-searchBlockInput-noResults');
			$('#jsSearchRouteInput').addClass('adminSection-searchBlockInput-hasResults');
		}

		$('#jsSearchButton').attr('disabled', true);
		$('#jsSearchResetButton').removeAttr('disabled');
	}
}


function attachRoutingActionsHandlers() {
	$('#jsActionsBlock').on('click', '.jsAction', function (event) {
		if (debugLogHandlerPrototype) console.log("$('#jsActionsBlock').on('click', '.jsAction', function(event) { ... }");

		// add new route
		if ($(this).hasClass('jsAddNewRouteAction')) {
			var abstractClassKey = parseInt(abstractClasses.indexOf(defaultAbstractClass));
			if (!(abstractClassKey >= 0)) {
				abstractClassKey = '';
			}

			var newIndex = lastRouteIndex + 1,
				fakeIndexPattern = 'newPattern_' + newIndex;

			routes[fakeIndexPattern] = {
				'class': '',
				'parentClass': abstractClassKey,
				'route': ''
			};
			routeIndexes[newIndex] = fakeIndexPattern;
			lastRouteIndex++;

			var newItemBlockHTML = $.renderTemplate(
				'jsSpoilerItemTemplate',
				{
					'patternIndex': newIndex,
					'pattern': '',
					'newItemClass': 'jsNewItemBlock adminSection-parametersList-NewItemBlock'
				}
			);

			$('#jsRouter').find('.jsItemBlock, .jsItemGroupBlock').first().before(newItemBlockHTML);

			$('#jsRouter').find('.jsItemBlock').first().find('.jsToggleSpoilerClickArea').trigger('click');
		}


		//save route
		else if ($(this).hasClass('jsSaveRouteAction')) {
			var $itemBlock = $(this).closest('.jsItemBlock');
			if ($itemBlock.length) {
				var $inputRoutePattern = $itemBlock.find('input[name="route"]');

				var routeSaved = false,
					oldRoutePattern = $inputRoutePattern.attr('data-savedValue'),
					oldRouteIndex = objectPropertyOf(routeIndexes, oldRoutePattern),
					newRoutePattern = $.trim($inputRoutePattern.val());

				if (oldRouteIndex == '') {
					// new route pattern
					var dataIndex = parseInt($itemBlock.attr('data-patternIndex'));
					if (dataIndex >= 0) {
						var patternKey = 'newPattern_' + dataIndex;
						if (routes.hasOwnProperty(patternKey)) {
							oldRouteIndex = dataIndex;
							oldRoutePattern = patternKey;
						}
					}
				}

				if (newRoutePattern != '') {
					var updateRoute = function (pattern) {
						var routeParameters = getCurrentFormParameters($itemBlock);

						routes[pattern] = {
							'class': $.trim($itemBlock.find('input[name="routeClass"]').val()),
							'parentClass': $.trim($itemBlock.find('select[name="routeBaseClassName"]').val()),
							'route': $.trim($itemBlock.find('input[name="routePath"]').val())
						};

						if (Object.keys(routeParameters).length) {
							routes[pattern]['parameters'] = routeParameters;
						}
					}


					var updateComplexRouteParameters = function (pattern) {
						var patternParameters = routes[pattern].parameters || {},
							complexRoutes = getComplexRoutes(), routeIndex = objectPropertyOf(routeIndexes, pattern),
							patternFormsWereBeingDiscarded = [];

						if (complexRoutes.indexOf(routes[pattern].route) >= 0) {
							for (var routePattern in routes) {
								if (routes[pattern].route == routes[routePattern].route) {
									if (routePattern != pattern) {
										if (!objectCompare(routes[routePattern].parameters, patternParameters)) {
											routes[routePattern].parameters = patternParameters;
											saveRoute(routePattern);
										}

										// close form if it happens to be opened
										var currentRouteIndex = objectPropertyOf(routeIndexes, routePattern);
										if (currentRouteIndex >= 0) {
											var $itemBlock = $('#jsRouter #patternIndex_' + currentRouteIndex);
											if ($itemBlock.length) {
												if ($itemBlock.hasAttr(formAttachedAttrName)) {
													// remove form and DISCARD all unchanged values in forms
													$itemBlock.find('.jsFormBlock').html('');
													$itemBlock.removeAttr(formAttachedAttrName);
													$itemBlock.removeClass('jsNewItemBlock adminSection-parametersList-NewItemBlock');

													patternFormsWereBeingDiscarded.push(routePattern);
												}
											}
										}
									}
								}
							}
						}

						if (patternFormsWereBeingDiscarded.length) {
							var warningText = 'Изменения в следующих паттернах НЕ были сохранены: ' + patternFormsWereBeingDiscarded.join(', ');
							console.log(warningText);
							alert(warningText);
						}
					}

					if (newRoutePattern != oldRoutePattern) {
						updateRoute(newRoutePattern);

						var newIndex = lastRouteIndex + 1;
						routeIndexes[newIndex] = newRoutePattern;
						lastRouteIndex++;

						var newRouteIndex = objectPropertyOf(routeIndexes, newRoutePattern);

						saveRoute(newRoutePattern, function () {
							if (newRouteIndex >= 0) {
								var $oldItemBlock = $('#jsRouter #patternIndex_' + oldRouteIndex);
								if ($oldItemBlock.length) {
									$oldItemBlock.find('input,select').each(function () {
										$(this).attr('data-savedValue', ($.trim($(this).val())));
									});

									updateAllSelectedOptionsToCurrentState($oldItemBlock);

									$oldItemBlock.attr('id', 'patternIndex_' + newRouteIndex);
									$oldItemBlock.attr('data-patternIndex', newRouteIndex);
									$oldItemBlock.removeClass('jsNewItemBlock adminSection-parametersList-NewItemBlock');
								}
							}

							updateComplexRouteParameters(newRoutePattern);

							routeSaved = true;
						});

						deleteRoute(oldRoutePattern, function () {
							if (oldRouteIndex >= 0) {
								delete routeIndexes[oldRouteIndex];
								delete routes[oldRoutePattern];
							}
						});

						updateItemListPosition(newRouteIndex);
					}
					else {
						updateRoute(oldRoutePattern);

						saveRoute(oldRoutePattern, function () {
							if (oldRouteIndex >= 0) {
								var $oldItemBlock = $('#jsRouter #patternIndex_' + oldRouteIndex);
								if ($oldItemBlock.length) {
									$oldItemBlock.find('input,select').each(function () {
										$(this).attr('data-savedValue', ($.trim($(this).val())));
									});

									updateAllSelectedOptionsToCurrentState($oldItemBlock);

									$oldItemBlock.removeClass('jsNewItemBlock adminSection-parametersList-NewItemBlock');

									updateComplexRouteParameters(oldRoutePattern);

									routeSaved = true;
								}
							}
						});
					}

					if (routeSaved) {
						$itemBlock.find('.jsSaveRouteAction').hide();
						$itemBlock.find('.jsFormBlock .jsParametersHintContainer').html('');
					}
				}
			}
		}


		// remove route
		else if ($(this).hasClass('jsRemoveRouteAction')) {
			var $itemBlock = $(this).closest('.jsItemBlock');
			if ($itemBlock.length) {
				var routeIndex, successFunction = function () {
					if (routeIndex >= 0) {
						var pattern = routeIndexes[routeIndex];
						delete routeIndexes[routeIndex];
						delete routes[pattern];
					}

					$itemBlock.remove();
				}

				routeIndex = parseInt($itemBlock.attr('data-patternIndex'));

				if ($itemBlock.hasClass('jsNewItemBlock')) {
					successFunction();
				}
				else {
					if (routeIndex >= 0) {
						deleteRoute(routeIndexes[routeIndex], successFunction);
					}
				}
			}
		}


		// add route parameter
		else if ($(this).hasClass('jsAddParameterAction')) {
			var $paramTable = $(this).closest('tr').siblings().find('.jsRouterParametersTable');
			if ($paramTable.length) {
				var selectOptionsFieldTypes = $('#jsSelectOptionsFieldTypes').html();

				var newParamBlock = $.renderTemplate(
					'jsPatternParameterTemplate',
					{
						'routeParameterName': '',
						'fieldType': 1,  // default value
						'selectOptionsFieldTypes': selectOptionsFieldTypes
					}
				);

				($paramTable.find('tr').length == 0) ?
					$paramTable.html(newParamBlock) :
					$paramTable.find('tr').last().after(newParamBlock);

				var patternIndex = parseInt($(this).closest('.jsItemBlock').attr('data-patternIndex'));
				if (patternIndex >= 0) {
					setDefaultFieldTypeSelectorForParameterNameAndPatternIndex(patternIndex);
				}

				$paramTable.find('tr').last().find('input[name="routeParameterName"]').focus();
			}
		}


		// remove route parameter
		else if ($(this).hasClass('jsRemoveParameterAction')) {
			var $itemBlock = $(this).closest('.jsItemBlock');
			if ($itemBlock.length) {
				var patternIndex = parseInt($itemBlock.attr('data-patternIndex'));
				if (patternIndex >= 0) {
					var $inputRoutePath = $itemBlock.find('input[name="routePath"]');
					if ($inputRoutePath.length) {
						$(this).closest('tr').remove();

						routePropertyAlreadyExists('route', routes[routeIndexes[patternIndex]].route, $inputRoutePath, true);
					}

					$itemBlock.find('.jsSaveRouteAction').show();
				}
			}
		}
	});
}


function attachSearchHandlers() {
	$('#jsSearchRouteInput').on('keyup', function (event) {
		if (debugLogHandlerPrototype) console.log("$('#jsSearchRouteInput').on('keyup', function(event) { ... }");

		if (event.keyCode == 13) {
			$('#jsSearchButton').trigger('click');
		}
	});


	$('#jsSearchRouteInput').on('input propertychange', function () {
		if (debugLogHandlerPrototype) console.log("$('#jsSearchRouteInput').on('input propertychange', function() { ... }");

		$(this).removeClass('adminSection-searchBlockInput-hasResults adminSection-searchBlockInput-noResults');
		//$('#jsSearchResetButton').attr('disabled', true);

		if ($(this).val() == '') {
			$('#jsSearchButton').attr('disabled', true);
		}
		else {
			$('#jsSearchButton').removeAttr('disabled');

			// remove leading 'http' from URL
			var matches = this.value.match(/^http:\/\/([^\/]{1,})(.*)$/);
			if (matches && (matches[2] != '')) {
				$(this).val(matches[2]);
			}
		}
	})


	$('#jsSearchButton').on('click', function () {
		if (debugLogHandlerPrototype) console.log("$('#jsSearchButton').on('click', function() { ... }");

		showRoutesByURL($('#jsSearchRouteInput').val());
	});


	$('#jsSearchResetButton').on('click', function () {
		if (debugLogHandlerPrototype) console.log("$('#jsSearchResetButton').on('click', function() { ... }");

		showRoutesByURL('');

		$('#jsSearchRouteInput')
			.removeClass('adminSection-searchBlockInput-hasResults adminSection-searchBlockInput-noResults')
			.val('').focus();

		$(this).attr('disabled', true);
	});
}


function attachRouteFormHandlers() {
	var $jsRouterBlock = $('#jsRouter');


	$jsRouterBlock.on('input propertychange', '.jsItemBlock input[name="route"]', function () {
		if (debugLogHandlerPrototype) console.log("$('#jsRouter').on('input propertychange', '.jsItemBlock input[name=\"route\"]', function() { ... }");

		var currentValue = $.trim($(this).val()),
			savedValue = $(this).attr('data-savedValue');

		$(this).closest('.jsToggleSpoilerBlock').find('.jsToggleSpoilerClickArea').html(currentValue);

		if ((currentValue != '') && (currentValue != savedValue)) {
			routeAlreadyExists(currentValue, this);
		}
	});


	$jsRouterBlock.on('input propertychange', '.jsItemBlock input[name="routePath"]', function () {
		if (debugLogHandlerPrototype) console.log("$('#jsRouter').on('input propertychange', '.jsItemBlock input[name=\"routePath\"]', function() { ... }");

		var currentValue = $.trim($(this).val()),
			savedValue = $(this).attr('data-savedValue');

		if (currentValue != '') {
			routePropertyAlreadyExists('route', currentValue, this);
		}
	});


	$jsRouterBlock.on('input propertychange', '.jsItemBlock input[name="routeClass"]', function () {
		if (debugLogHandlerPrototype) console.log("$('#jsRouter').on('input propertychange', '.jsItemBlock input[name=\"routeClass\"]', function() { ... }");

		var currentValue = $.trim($(this).val()),
			savedValue = $(this).attr('data-savedValue');

		if (currentValue != '') {
			routePropertyAlreadyExists('class', currentValue, this);
		}
	});
}


function attachEventHandlers() {
	var $jsRouterBlock = $('#jsRouter');


	$jsRouterBlock.on('click', '.jsToggleSpoilerClickArea', function (event) {
		if (debugLogHandlerPrototype) console.log("$('#jsRouter').on('click', '.jsToggleSpoilerClickArea', function(event) { ... }");

		var $itemBlock = $(this).closest('.jsItemBlock');
		if ($itemBlock.length) {
			toggleItemForm($itemBlock);
		}

		var $spoilerBlock = $(this).closest('.jsToggleSpoilerBlock');
		if ($spoilerBlock.length) {
			$spoilerBlock.toggleClass('adminSection-parametersList-spoilerClosed');
			$spoilerBlock.toggleClass('adminSection-parametersList-spoilerOpen');
			$spoilerBlock.find('.jsToggleSpoilerContent').first().toggle();
		}
	});


	$jsRouterBlock.on('click', '.jsRemoveAction, .jsSaveAction', function (event) {
		if (debugLogHandlerPrototype) console.log("$('#jsRouter').on('click', '.jsRemoveAction, .jsSaveAction', function(event) { ... }");

		var confirmText = (($(this).hasClass('jsRemoveAction')) ?
			'Точно удалить?' :
			'Точно сохранить именно этот набор параметров в других связных паттернах? Также все несохраненные изменения в таких связных паттернах будут сброшены на их последние сохраненные значения.');

		if (!confirm(confirmText)) {
			event.stopImmediatePropagation();
		}
	});


	attachRoutingActionsHandlers();


	$jsRouterBlock.on('change', 'select', function (event) {
		if (debugLogHandlerPrototype) console.log("$('#jsRouter').on('change', 'select', function(event) { ... }");

		$(this).find('option:selected').attr('data-selected', '');
		$(this).find('option:not(:selected)').removeAttr('data-selected');
	});


	$jsRouterBlock.on('focus blur', 'select', function (event) {
		if (debugLogHandlerPrototype) console.log("$('#jsRouter').on('focus blur', 'select', function(event) { ... }");

		var select, selectName = $(this).attr('name');

		switch (selectName) {
			case 'routeBaseClassName' :
				select = $(this).siblings('.jsSelectHintBlock');
				break;

			case 'routeParameterType' :
				select = $(this).closest('table').closest('tr').prev().find('.jsSelectHintBlock');
				break;
		}

		if (select.length) {
			(event.handleObj.origType == 'focus') ? select.show() : select.hide();
		}
	});


	attachSearchHandlers();


	$jsRouterBlock.on('keyup', 'input', function (event) {
		if (debugLogHandlerPrototype) console.log("$('#jsRouter').on('keyup', 'input', function(event) { ... }");

		if ($.trim($(this).val()) == '') {
			// right arrow
			if (event.keyCode == 39) {
				var valueToShow = '', savedValue = $(this).attr('data-savedValue') || '';
				if (savedValue != '') {
					valueToShow = savedValue;
				}
				else {
					var helperValue = $(this).attr('data-helperValue') || '';
					if (helperValue != '') {
						valueToShow = helperValue;
					}
				}

				if (valueToShow != '') {
					$(this).val(valueToShow);
					$(this).trigger('propertychange');  // must be
				}
			}
		}
	});


	attachRouteFormHandlers();


	/**
	 * @todo do refactor last function's code below -----------------------------------------------------------------------------------
	 */

	$('#jsRouter').on('click', '.jsInputHintClickArea, .jsComplexPatternParametersClickArea', function () {
		if (debugLogHandlerPrototype) console.log("$('#jsRouter').on('click', '.jsInputHintClickArea, .jsComplexPatternParametersClickArea', function() { ... }");

		var routeIndex = -1;

		if ($(this).hasClass('jsInputHintClickArea')) {
			routeIndex = parseInt($(this).closest('.jsInputHintBlock').attr('data-patternIndex'));
		}
		else if ($(this).hasClass('jsComplexPatternParametersClickArea')) {
			routeIndex = parseInt($(this).attr('data-patternIndex'));
		}

		scrollToPatternAtIndex(routeIndex);
	});


	$('#jsRouter').on('click', '.jsCloneComplexPatternParametersClickArea', function () {
		if (debugLogHandlerPrototype) console.log("$('#jsRouter').on('click', '.jsCloneComplexPatternParametersClickArea', function() { ... }");

		var cloneFromRouteIndex = parseInt($(this).attr('data-patternIndex')),
			cloneToRouteIndex = parseInt($(this).closest('.jsItemBlock').attr('data-patternIndex'));

		clonePatternParametersToForm(cloneFromRouteIndex, cloneToRouteIndex);
	});


	$('#jsRouter').on('input propertychange change', '.jsItemBlock input, .jsItemBlock select', function () {
		if (debugLogHandlerPrototype) console.log("$('#jsRouter').on('input propertychange change', '.jsItemBlock input, .jsItemBlock select', function() { ... }");

		var $itemBlock = $(this).closest('.jsItemBlock');
		if ($itemBlock.length) {
			var inputName = $(this).attr('name');
			if ((inputName == 'routeParameterName') && (inputName == 'routeParameterType')) {
				checkParametersOnDuplicates($itemBlock);

				var $routePathInput = $itemBlock.find('input[name="routePath"]');
				if ($routePathInput.length) {
					routePropertyAlreadyExists('route', $routePathInput.val(), $routePathInput, true);
				}
			}
			//else
			{
				var $saveRouteBlock = $itemBlock.find('.jsSaveRouteAction'),
					routeIndex = $itemBlock.attr('data-patternIndex'),
					hideSaveButton = true;

				if (routeFormHasChanges(routeIndex)) {
					var inputPatternCurrentValue = $.trim($itemBlock.find('input[name="route"]').val()),
						inputPatternFilled = (inputPatternCurrentValue.length > 0),
						inputFilePathFilled = ($.trim($itemBlock.find('input[name="routePath"]').val()).length > 0),
						inputClassFilled = ($.trim($itemBlock.find('input[name="routeClass"]').val()).length > 0),
						patternValueAllowed = true;


					var $inputRoutePath = $itemBlock.find('input[name="routePath"]'), inputRoutePathValue = $.trim($inputRoutePath.val());
					if ($inputRoutePath.length && (inputRoutePathValue != '')) {
						routePropertyAlreadyExists('route', inputRoutePathValue, $inputRoutePath);
					}


					if (inputPatternFilled) {
						if ($itemBlock.hasClass('jsNewItemBlock') && routes.hasOwnProperty(inputPatternCurrentValue)) {
							patternValueAllowed = false;
						}
					}

					if (inputPatternFilled && inputFilePathFilled && inputClassFilled && patternValueAllowed) {
						$saveRouteBlock.show();
						hideSaveButton = false;
					}
				}

				if (hideSaveButton) {
					$saveRouteBlock.hide();
				}
			}
		}
	});


	/*  // @ todo  to removing ...

	 $('#jsRouter').on('input propertychange change', '.jsRouterParametersTable input[name="routeParameterName"], .jsRouterParametersTable select[name="routeParameterType"]', function()
	 {
	 if (debugLogHandlerPrototype) console.log("$('#jsRouter').on('input propertychange change', '.jsRouterParametersTable input[name=\"routeParameterName\"], .jsRouterParametersTable select[name=\"routeParameterType\"]', function() { ... }");

	 var $itemBlock = $(this).closest('.jsItemBlock');
	 if ($itemBlock.length)
	 {
	 checkParametersOnDuplicates($itemBlock);


	 var $routePathInput = $itemBlock.find('input[name="routePath"]');
	 if ($routePathInput.length)
	 {
	 routePropertyAlreadyExists('route', $routePathInput.val(), $routePathInput, true);
	 }
	 }
	 });
	 */


	$(window).on('beforeunload', function () {
		if (debugLogHandlerPrototype) console.log("$(window).on('beforeunload', function() { ... }");

		var formsHasChanges = false;

		for (var routeIndex in routeIndexes) {
			if (routeFormHasChanges(routeIndex)) {
				formsHasChanges = true;
				break;
			}
		}

		if (formsHasChanges) {
			return 'Есть несохраненные данные!';
		}
	});
}


$(document).ready(function () {
	console.time('buildRoutes');
	buildRoutes();
	console.timeEnd('buildRoutes');

	hideGroups();
	attachEventHandlers();
});
