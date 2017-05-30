var registrarunidad = function (txtDescripcion,txtId) {
	var options = {
		type: 'POST',
		url: '../../registrar',
		data: {'txtDescripcion': txtDescripcion,
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
						window.location.href = "../../unidad";
					});
				}
			}
		}
	};
	$.ajax(options);
};

$(document).on('click', '#btnguardar', function (event) {
	this.disabled = true;
	event.preventDefault();
	var txtDescripcion = $('#txtDescripcion').val();
	var txtId = $('#txtId').val();
	
	

	registrarunidad(txtDescripcion, txtId);
	//this.disabled=false;

});

function eliminar(id,vigencia){

var options = {
		type: 'POST',
		url: 'eliminar',
		data: {
			'txtId': id,
			'txtVigencia':vigencia,
		},
		dataType: 'json',
		
		success: function (response) {
			(response.error == 0) ?
					bootbox.alert(response.msj, function () {
						window.location.href = "unidad";
					}) :
					bootbox.alert(response.msj);
				$("#btnguardar").prop('disabled', false);
		}
	};
	$.ajax(options);
	
}
