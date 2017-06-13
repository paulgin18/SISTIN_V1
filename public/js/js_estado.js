var registrar = function (txtNumero,txtDescripcion, txtId) {
	
	var options = {
		type: 'POST',
		url: '../../registrar',
		data: {'txtNumero':txtNumero,
			'txtDescripcion': txtDescripcion,
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
	alert("s");
	this.disabled = true;
	event.preventDefault();
	
	var txtDescripcion = $('#txtDescripcion').val();
	var txtId = $('#txtId').val();
	var txtNumero =$('#txtNumero').val();
	registrar(txtNumero,txtDescripcion, txtId);
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
						window.location.href = "estado";
					}) :
					bootbox.alert(response.msj);
				$("#btnBorrar").prop('disabled', false);
		}
	};
	$.ajax(options);

}




