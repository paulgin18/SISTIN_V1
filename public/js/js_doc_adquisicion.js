var registrar = function (txtDescripcion, txtAbreviatura, txtId) {
	var options = {
		type: 'POST',
		url: '../../registrar',
		data: {'txtDescripcion': txtDescripcion,
			'txtAbreviatura': txtAbreviatura,
			'txtId': txtId,
		},
		dataType: 'json',
		success: function (response) {
			(response.error == 0) ?
					bootbox.alert(response.msj, function () {
						window.location.href = "../../docadquisicion";
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
		var txtAbreviatura = $('#txtAbreviatura').val();
		var txtId = $('#txtId').val();
		registrar(txtDescripcion, txtAbreviatura, txtId);
		this.disabled = false;
	}
});

$(document).ready(function () {
	$('input').focusout(function () {
		this.value = this.value.toLocaleUpperCase();
	});
	//$('#txtDescripcion').valcn('abcdefghijklmnñopqrstuvwxyzáéiou');
	$('#txtAbreviatura').valcn('abcdefghijklmnñopqrstuvwxyzáéiou');
	$(".abreviatura, .descripcion").keyup(function () {
		if ($(this).val() != "") {$(".error").fadeOut();return false;}
	});
  
});

function validar() {
	    $(".error").remove();
	        if ($(".descripcion").val() == "") {
		            $(".descripcion").focus().after("<span class='error'>Ingrese el nombre del documento</span>");
		            return false;
	        } else if ($(".abreviatura").val() == "") {
		            $(".abreviatura").focus().after("<span class='error'>Ingrese una Abreviatura</span>");
		            return false;
	        } else {
		return true;
	}
}

var eliminar = function ($cod,$vigencia){
	var options = {
		type: 'POST',
		url: 'eliminar',
		data: {'cod': $cod,'vigencia':$vigencia,
		},
		dataType: 'json',
		success: function (response) {
		(response.error == 0) ?
			bootbox.alert(response.msj, function () {
				window.location.href = "docadquisicion";
			}) :
			bootbox.alert(response.msj);
		}
	};
	$.ajax(options);

}
