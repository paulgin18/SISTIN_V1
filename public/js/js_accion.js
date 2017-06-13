

var registrar = function (cmbTipo,txtDescripcion, txtId) {
	
	
	var options = {
		type: 'POST',
		url: '../../registrar',
		data: {'cmbTipo':cmbTipo,
			'txtDescripcion': txtDescripcion,
			
			'txtId': txtId,
		},
		dataType: 'json',
		success: function (response) {
			var elemento = response.msj.split(":");
			if (elemento.length > 0) {
		
					bootbox.alert(response.msj, function () {
						window.location.href = "../../accion";
					});
				
			}
		}
	};
	$.ajax(options);
};


$(document).on('click', '#btnguardar', function (event) {

this.disabled = true;

	event.preventDefault();
	var cmbTipo =$('#cmbTipo').val();
	var txtDescripcion = $('#txtDescripcion').val();
	var txtId = $('#txtId').val();
	registrar(cmbTipo,txtDescripcion, txtId);


	//registrar(txtTipo,txtDescripcion,txtId);	
	//this.disabled=false;

});

$(document).ready(function () {
	$('input').focusout(function () {
		this.value = this.value.toLocaleUpperCase();
	});
	$('#txtDescripcion').valcn(' abcdefghijklmnñopqrstuvwxyzáéiou');
	$('#txtNumero').valcn('0123456789');
	
	$(".numero, .descripcion").keyup(function () {
		if ($(this).val() != "") {$(".error").fadeOut();return false;}
	});
  
});



var eliminar = function ($cod,$vigencia){
	alert($cod);
	var options = {
		type: 'POST',
		url: 'eliminar',
		data: {'cod': $cod,'vigencia':$vigencia,
		},
		dataType: 'json',
		success: function (response) {
		(response.error == 0) ?
			bootbox.alert(response.msj, function () {
				window.location.href = "accion";
			}) :
			bootbox.alert(response.msj);
		}
	};
	$.ajax(options);

}









