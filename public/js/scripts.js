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

	/*
	 * Set active tab in user settings on redirect
	 */
	var hash = $('#active_id').val();
	if(hash)
	{
		var obj = $('.nav-tabs a[href="' + hash + '"]');
        obj.tab('show');
    }
    else
    {
    	$('.nav-tabs a[href="#summary"]').tab('show');
    }

    /*
     * Dropdown on hover
     */
	$(function() {
		$('.dropdown').hover(function() {
			$('ul.dropdown-menu', this).stop(true, true).slideDown('fast');
			$(this).delay(200).addClass('open');
		},
		function() {
			$('ul.dropdown-menu', this).stop(true, true).slideUp('fast');
			$(this).delay(200).removeClass('open');
		});
	});

	/*
	 * Follow link when account's clicked
	 */
	$('#accounts').click(function() {
		window.location = $('#accounts').attr('href');
	});

});