
var registrar = function (txtDescripcion,txtId_uni_ejec,txtId) {
	
	var options = {
		type: 'POST',
		url: '../../registrar',
		data: {
			'txtDescripcion': txtDescripcion,
			'txtId_uni_ejec':txtId_uni_ejec,
			//'chkVigencia': chkVigencia,
			'txtId': txtId,
		},
		
		dataType: 'json',
		success: function (response) {
			var elemento = response.msj.split(":");
			if (elemento.length > 0) {
		
					bootbox.alert(response.msj, function () {
						window.location.href = "../../area";
					});
				
			}
		}
	};

	$.ajax(options);
};



$(document).on('click', '#btnguardar', function (event) {
	this.disabled = true;
	event.preventDefault();
	
	var txtId = $('#txtId').val();

	var txtDescripcion = $('#txtDescripcion').val();

	var txtId_uni_ejec =$('#txtId_uni_ejec').val();


	registrar(txtDescripcion,txtId_uni_ejec, txtId);
	//this.disabled=false;
	

});

function validar() {
	    $(".error").remove();
	        if ($(".descripcion").val() == "") {
		            $(".descripcion").focus().after("<span class='error'>Ingrese la descripcion</span>");
		            return false;
	        } else if ($(".anio").val() == "") {
		            $(".anio").focus().after("<span class='error'>Ingrese un año</span>");
		            return false;
	        } else {
		return true;
	}
}



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
						window.location.href = "area";
					}) :
					bootbox.alert(response.msj);
				$("#btnBorrar").prop('disabled', false);
		}
	};
	$.ajax(options);
		//alert('id eliminar area'+txtId);
	//alert('txt vigencia '+txtVigencia);

}

/*

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

*/
