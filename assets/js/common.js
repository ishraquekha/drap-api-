function reqAjax(url, formulario)
{
	$('button[type="submit"]').attr('disabled', true);
	
	var tmp = url.split(".");
	
	var operacion = "";
	if (tmp[1] == "agregar") operacion = "agregado";
	else if (tmp[1] == "modificar") operacion = "modificado";

	$.ajax({
		type: "POST",
		url: "pages/" + url + ".php",
		data: formulario,
		complete: function(obj, rst)
		{
			if (rst == "success")
			{
				$("html, body").animate({ scrollTop: 0 }, "slow");
				if (obj.responseText != "")
				{
					$('button[type="submit"]').prop('disabled', false);
					$("#resultado_txt").html(obj.responseText);
				}
				else
				{
					$("#resultado_txt").html("Registro " + operacion + " correctamente.");
					window.setTimeout(function () {
						location.href = "index.php?cat=" + tmp[0];
					}, 2000);
				}
			}
			else
			{
				$('button[type="submit"]').prop('disabled', false);
				$("#resultado_txt").html(obj.responseText);
			}
			$("#resultado").show();
		}
	});
}

function reqGetAjax(urlParam, csrf)
{
	$.ajax({
		type: "GET",
		url: urlParam + "&csrf=" + csrf,
		complete: function(obj, rst)
		{
			if (rst == "success")
			{
				if (obj.responseText != "")
				{
					$("#resultado_txt").html(obj.responseText);
				}
				else
				{
					$("#resultado_txt").html("Registro eliminado correctamente.");
					window.setTimeout(function () {
						location.reload();
					}, 2000);
				}
			}
			else
			{
				$("#resultado_txt").html(obj.responseText);
			}
			$("#resultado").show();
		}
	});
}

function reqCmbAjax(clase, codigo, nombre, clave, valor, csrf)
{
	urlParam = "clase=" + clase + "&codigo=" + codigo + "&nombre=" + nombre + "&clave=" + clave + "&valor=" + valor;
	$.ajax({
		type: "GET",
		url: "cmb.php?" + urlParam + "&csrf=" + csrf,
		complete: function(obj, rst)
		{
			if (rst == "success")
			{
				$("#capa_" + clase).html(obj.responseText);
			}
		}
	});
}
/*
var configFirebase = {
	apiKey: "AIzaSyC3QzdWapUljaoVcSOBltZ45GRZ1a7fcew",
	authDomain: "dooit-198718.firebaseapp.com",
	databaseURL: "https://dooit-198718.firebaseio.com",
	projectId: "dooit-198718",
	storageBucket: "dooit-817891",
	messagingSenderId: "423931574509"
};
*/