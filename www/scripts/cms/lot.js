/**
 * Created by xpundel on 01.07.14.
 */


$(document).ready(function () {

	$('SELECT[name="cms-form-item-relation[lotType]"]').on('change', function () {
		onLotTypeChanged($(this).val());
	});
	onLotTypeChanged($('SELECT[name="cms-form-item-relation[lotType]"]').val());

	$('input[name="cms-form-item[priceNew]"], input[name="cms-form-item[priceOld]"]').on('change blur input', function () {
		var oldPrice = parseInt($('input[name="cms-form-item[priceOld]"]').val()) || 0,
			newPrice = parseInt($('input[name="cms-form-item[priceNew]"]').val()) || 0,
			discount = oldPrice - newPrice;
		if (discount < 0) discount = 0;
		$('.discount').html("–" + discount + " руб.")
	});

	$('input[name="cms-form-item[freePlan]"]').on('change', function () {
		disableRooms($(this).is(':checked'));
	});
	disableRooms($('input[name="cms-form-item[freePlan]"]').is(':checked'));

	function disableRooms(checked) {
		var $roomsRow = $('tr[data-row-for-field="rooms"')
		if (checked) {
			$roomsRow.hide();
		}
		else {
			$roomsRow.show();
		}
	}

	function onLotTypeChanged(val) {
		var fieldsToHide = ['rooms', 'freePlan', 'square', 'maxFloors', 'material', 'finishing'];
		var fieldsToHide2 = ['withContract'];
		if (val == 5) {
			fieldsToHide.map(function (item) {
				$('tr[data-row-for-field="' + item + '"]').hide();
			})
			fieldsToHide2.map(function (item) {
				$('tr[data-row-for-field="' + item + '"]').show();
			})
		} else {
			fieldsToHide.map(function (item) {
				$('tr[data-row-for-field="' + item + '"]').show();
			});
			if (val == 2) {
				$('tr[data-row-for-field="freePlan"]').hide();
				$('tr[data-row-for-field="category"]').show();
			} else {
				$('tr[data-row-for-field="category"]').hide();
			}
			fieldsToHide2.map(function (item) {
				$('tr[data-row-for-field="' + item + '"]').hide();
			});
		}
	}

});


