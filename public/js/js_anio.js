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
			(response.error == 0) ?
					bootbox.alert(response.msj, function () {
						window.location.href = "../../listadoanios";
					}) :
					bootbox.alert(response.msj);
				$("#btnguardar").prop('disabled', false);
		}
		, error: function () {
			this.disabled = true;
		}
	};
	$.ajax(options);
};

$(document).submit(function (event) {
	var val = validar();
	if (val == true) {
		$("#btnguardar").prop('disabled', true);
		event.preventDefault();
		var txtDescripcion = $('#txtDescripcion').val();
		var txtAnio = $('#txtAnio').val();
		var txtId = $('#txtId').val();
		registraranio(txtDescripcion, txtAnio, txtId);
		this.disabled = false;
	}
});

$(document).ready(function () {
	$('input').focusout(function () {
		this.value = this.value.toLocaleUpperCase();
	});
	$('#txtDescripcion').valcn(' abcdefghijklmnñopqrstuvwxyzáéiou');
	$('#txtAnio').valcn('0123456789');
	
	$(".anio, .descripcion").keyup(function () {
		if ($(this).val() != "") {$(".error").fadeOut();return false;}
	});
  
});

function validar() {
	    $(".error").remove();
	        if ($(".descripcion").val() == "") {
		            $(".descripcion").focus().after("<span class='error'>Ingrese su nombre</span>");
		            return false;
	        } else if ($(".anio").val() == "") {
		            $(".anio").focus().after("<span class='error'>Ingrese un año</span>");
		            return false;
	        } else {
		return true;
	}
}

var eliminar = function ($cod){
	var options = {
		type: 'POST',
		url: 'eliminar',
		data: {'cod': $cod,
		},
		dataType: 'json',
		success: function (response) {
		(response.error == 0) ?
			bootbox.alert(response.msj, function () {
				window.location.href = "listadoanios";
			}) :
			bootbox.alert(response.msj);
		}
	};
	$.ajax(options);

}
