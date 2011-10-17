$(document).ready(function(){
	Date.firstDayOfWeek = 0;
	$('#form_date__date')
		.datePicker({createButton:false})
		.bind('focus', clickDateField)
		.bind('click', clickDateField)
		.bind(
			'dpClosed',
			function(event, selected)
			{
				$('*').unbind('focus.datePicker');
			}
		);
	$('#form_date__date').dpSetOffset(26, 52);
});

function clickDateField(event, message)
{
	if (message == $.dpConst.DP_INTERNAL_FOCUS) {
		return true;
	}
	var dp = this;
	var $dp = $(this);
	$dp.dpDisplay();
	$('*').bind(
		'focus.datePicker',
		function(event)
		{
			var $focused = $(this);
			if (!$focused.is('.dp-applied'))
			{
				if ($focused.parents('#dp-popup').length == 0 && this != dp && !($.browser.msie && this == document.body)) {
					$('*').unbind('focus.datePicker');
					$dp.dpClose();
				}
			}
		}
	);
	return false;
}		