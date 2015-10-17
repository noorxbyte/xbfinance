/*
 * Custom Scripts
 */

$(document).ready(function() {

	/*
	 * Date time picker
	 */
	$('#dpicker').datepicker({dateFormat: 'dd/mm/yy'});

	// set the current date if empty
	if (!$('#dpicker').val())
	{
		var d = new Date();
		var currentDate = d.getDate() + "/" + (d.getMonth() + 1) + "/" + d.getFullYear();

		$('#dpicker').val(currentDate);
	}

	/*
	 * Slide up flash message after 3 seconds
	 */
	$('#flash_message').delay(3000).slideUp(300);

	/*
	 * Confirm delete and submit delete form
	 */
	$('.btn_del').click(function (event) {
		event.preventDefault();

		if (confirm($('#del_msg').val()) == true)
		{
			// update action
			var url = $(this).attr('href');
			$('#del_form').attr('action', url);

			// submit form
			$('#del_form').submit();
		}
	});

});