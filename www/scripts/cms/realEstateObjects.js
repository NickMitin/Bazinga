/**
 * Created by xpundel on 01.07.14.
 */


$(document).ready(function () {

	onObjectTypeChanged($('SELECT[name="cms-form-item-relation[objectType]"]:first').val());
	initializeMap();

	$('SELECT[name="cms-form-item-relation[objectType]"]').on('change', function () {
		onObjectTypeChanged($(this).val());
	});


	$('.object-forms-container').find('INPUT:not(:file), SELECT').on('change blur', function () {
		if ($(this).is(':checkbox')) {
			$('.object-forms-container').find('[name="' + $(this).attr('name') + '"]').prop('checked', $(this).prop('checked'));
		} else {
			$('.object-forms-container').find('[name="' + $(this).attr('name') + '"]').val($(this).val());
		}
	});

	$('input[name="cms-form-item[gk]"]').on('change', function() {
		if($(this).is(':checked')) {
			$('tr[data-row-for-field="releaseDateQuart"], tr[data-row-for-field="releaseDateYear"]').hide();
		} else {
			$('tr[data-row-for-field="releaseDateQuart"], tr[data-row-for-field="releaseDateYear"]').show();
		}
	}).trigger('change');

	function onObjectTypeChanged(val) {
		$('.form-for-type-' + val).show().siblings().hide();
		initializeMap();
	}

	function initializeMap() {
		var lat = parseFloat($('[name="cms-form-item[lat]"]').val()) || 55.75648626598077;
		var lng = parseFloat($('[name="cms-form-item[lng]"]').val()) || 37.623796463012695;

		$('[name="cms-form-item[lat]"]').val(lat)
		$('[name="cms-form-item[lng]"]').val(lng)

		var mapOptions = {
			scrollwheel: false,
			center: new google.maps.LatLng(lat, lng),
			zoom: 11,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};


		$('.googleMap').each(function () {
			var map = new google.maps.Map($(this)[0], mapOptions);

			var marker = new google.maps.Marker({
				position: new google.maps.LatLng(lat, lng),
			});
			marker.setMap(map);

			google.maps.event.addListener(map, "click", function (event) {
				// populate yor box/field with lat, lng
				$('[name="cms-form-item[lat]"]').val(event.latLng.lat())
				$('[name="cms-form-item[lng]"]').val(event.latLng.lng())
				marker.setPosition(event.latLng);
			});
		})


	}

});


