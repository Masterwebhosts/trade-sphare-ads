jQuery(document).ready(function ($) {

	$('.tsa-toggle-form').on('click', function (event) {
		event.preventDefault();

		$('#tsa-add-zone').slideToggle(200);
	});

	$('.tsa-delete-zone').on('click', function (event) {

		var confirmed = window.confirm(
			'Are you sure you want to delete this ad zone?'
		);

		if (!confirmed) {
			event.preventDefault();
		}
	});

});