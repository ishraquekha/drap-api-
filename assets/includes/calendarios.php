<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">
<script src="js/plugins/datapicker/bootstrap-datepicker.js"></script>
<script>
$('.calendario').datepicker({
	format: "dd-mm-yyyy",
	todayBtn: false,
	todayHighlight: true,
	keyboardNavigation: false,
	forceParse: false,
	calendarWeeks: false,
	autoclose: true,
	language: "es",
	weekStart: 1
});
</script>