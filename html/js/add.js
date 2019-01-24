jQuery(document).ready(function($) {
	
	$('.jobs-list__response-btn').on('click', function(event) {
		var sJobName = $(this).closest('.jobs-list__city-response').siblings('.jobs-list__name').html(),
			sJobCity = $(this).siblings('.jobs-list__city').html();
		$('.alert_up__window--jobs .job-name').html('&laquo;'+sJobName+'&raquo;');
		$('.alert_up__window--jobs .job-city').html(sJobCity);
	});

});