var registrarred = function (txtDescripcion,txtId,cmbUnidad) {
	var options = {
		type: 'POST',
		url: '../../registrar',
		data: {'txtDescripcion': txtDescripcion,
			'txtId': txtId,
			'cmbUnidad':cmbUnidad,
		},
		dataType: 'json',
		success: function (response) {
			var elemento = response.msj.split(":");
				if (elemento.length > 0) {
					bootbox.alert(response.msj, function () {
						window.location.href = "../../red";
					});
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
	var cmbUnidad = $('#cmbUnidad').val(); 
	
	registrarred(txtDescripcion, txtId,cmbUnidad);
	//this.disabled=false;

});

function eliminar(id, vigencia){

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
						window.location.href = "red";
					}) :
					bootbox.alert(response.msj);
				$("#btnguardar").prop('disabled', false);
		}
	};
	$.ajax(options);
	
}