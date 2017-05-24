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
	
	alert('descripcion '+txtDescripcion);
	alert('id '+txtId);

	registrarunidad(txtDescripcion, txtId);
	//this.disabled=false;

});

function eliminar(id){
var options = {
		type: 'POST',
		url: '../../eliminar',
		data: {
			'txtId': id,
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
	
}