
var registrar = function (txtRango_inicial,txtRango_final,txtId) {
	
	var options = {
		type: 'POST',
		url: '../../registrar',
		data: {
			'txtRango_inicial': txtRango_inicial,
			'txtRango_final':txtRango_final,
			//'chkVigencia': chkVigencia,
			'txtId': txtId,
		},
		dataType: 'json',
		success: function (response) {
			var elemento = response.msj.split(":");
			if (elemento.length > 0) {
		
					bootbox.alert(response.msj, function () {
						window.location.href = "../../rango_ip";
					});
				
			}
		}
	};
	$.ajax(options);
};



$(document).on('click', '#btnguardar', function (event) {
	this.disabled = true;
	event.preventDefault();
	
	var txtRango_inicial = $('#txtRango_inicial').val();
	var txtRango_final = $('#txtRango_final').val();

	alert('id eliminar area'+txtDescripcion)

	
	/*
	if(chkVigencia==1){
		chkVigencia=true;
	}else{
		chkVigencia=false;
	}
	

	*/
	var txtId = $('#txtId').val();
	


	registrar(txtRango_inicial,txtRango_final,txtId);
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
						window.location.href = "rango_ip";
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
