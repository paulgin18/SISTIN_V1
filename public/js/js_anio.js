var registraranio = function (txtDescripcion, txtAnio, txtId) {
	var options = {
		type: 'POST',
		url: '../../registraranio',
		data: {'txtDescripcion': txtDescripcion,
			'txtAnio': txtAnio,
			'txtId': txtId,
		},
		dataType: 'json',
		success: function (response) {
			var elemento = response.msj.split(":");
			if (elemento.length > 0) {
				if (elemento[0] == "Error") {
					bootbox.alert(elemento[0] + "" + elemento[1]);
				} else {
					bootbox.alert(response.msj, function () {
						window.location.href = "../../listadoanios";
					});
				}
			}
		}
	};
	$.ajax(options);
};

//$(document).on('click', '#btnguardar', function (event) {
//	this.disabled = true;
//	event.preventDefault();
//	var txtDescripcion = $('#txtDescripcion').val();
//	var txtAnio = $('#txtAnio').val();
//	var txtId = $('#txtId').val();
//	registraranio(txtDescripcion, txtAnio, txtId);
//	//this.disabled=false;
//
//});

$(function () {
	$('input').focusout(function () {
		this.value = this.value.toLocaleUpperCase();
	});
});