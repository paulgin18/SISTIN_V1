

var registrar = function (txtNumero,txtDescripcion, chkVigencia, txtId) {
	
	var options = {
		type: 'POST',
		url: '../../registrar',
		data: {'txtNumero':txtNumero,
			'txtDescripcion': txtDescripcion,
			'chkVigencia': chkVigencia,
			'txtId': txtId,
		},
		dataType: 'json',
		success: function (response) {
			var elemento = response.msj.split(":");
			if (elemento.length > 0) {
		
					bootbox.alert(response.msj, function () {
						window.location.href = "../../estado";
					});
				
			}
		}
	};
	$.ajax(options);
};



$(document).on('click', '#btnguardar', function (event) {
	this.disabled = true;
	event.preventDefault();
	var txtNumero =$('#txtNumero').val();
	var txtDescripcion = $('#txtDescripcion').val();
	var chkVigencia = $('input:checkbox[name=chkVigencia]:checked').val();
	var txtId = $('#txtId').val();
	
	if(chkVigencia==1){
		chkVigencia=true;
	}else{
		chkVigencia=false;
	}
	
	registrar(txtNumero,txtDescripcion, chkVigencia, txtId);
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
						window.location.href = "estado";
					}) :
					bootbox.alert(response.msj);
				$("#btnBorrar").prop('disabled', false);
		}
	};
	$.ajax(options);
	
}



var eliminar = function ($id,$vigencia){
	var options = {
		type: 'POST',
		url: 'eliminar',
		data: {'id_estado': $id,'vigencia':$vigencia,
		},
		dataType: 'json',
		success: function (response) {
		(response.error == 0) ?
			bootbox.alert(response.msj, function () {
				window.location.href = "Seastado";
			}) :
			bootbox.alert(response.msj);
		}
	};
	$.ajax(options);




