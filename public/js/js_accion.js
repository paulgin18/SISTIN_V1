

var registrar = function (txtTipo,txtDescripcion, txtId) {
	
	
	var options = {
		type: 'POST',
		url: '../../registrar',
		data: {'txtTipo':txtTipo,
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
	var txtTipo =$('#txtTipo').val();
	var txtDescripcion = $('#txtDescripcion').val();

	var txtId = $('#txtId').val();
	
	
	registrar(txtTipo,txtDescripcion, txtId);
	//registrar(txtTipo,txtDescripcion,txtId);
	
	//this.disabled=false;

});






