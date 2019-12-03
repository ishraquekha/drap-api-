<link href="../assets/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
<script src="../assets/js/plugins/dataTables/datatables.min.js?v=<?=time()?>"></script>


<script>
	$(document).ready(function(){
		$('#lst_tbl').DataTable({
			"bLengthChange" : false,
			"pageLength": <?=(isset($pageLength) ? $pageLength : "10")?>,
			dom: '<"html5buttons"B><"top"f>rt<"bottom"ilp><"clear">',
			"pagingType": "full_numbers",
			buttons: [
				{ extend: 'copy', title: 'Copiar' },
				{extend: 'excel', title: 'ExampleFile'},
			],
			"language": {
				"url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
			},
		});
	});
</script>
