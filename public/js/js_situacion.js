var registrarsituacion = function (txtDescripcion,txtIdSituacion, txtIdDispositivo) {
	var options = {
		type: 'POST',
		url: '../../registrarsituacion',
		data: {'txtDescripcion': txtDescripcion,
			'txtIdSituacion': txtIdSituacion,
			'txtIdDispositivo': txtIdDispositivo,
		},
		dataType: 'json',
		success: function (response) {
			(response.error == 0) ?
					bootbox.alert(response.msj, function () {
						window.location.href = "../../listadosituaciones";
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


$("#txtDispositivo").focus(function (){
	$("#txtDispositivo").autocomplete({
		source: '../../../../dispositivo/dispositivo/buscarDispositivo?tipo=DI',

		select: function (event, ui) {
			$('#txtIdDispositivo').val(ui.item.value);

			$(this).val(ui.item.label)
			return false;
		},
		autoFocus: false,
		open: function (event, ui) {
			$("#txtIdDispositivo").val('');
		},
		focus: function (event, ui) {
			return false;
		}
	});
});




$(document).submit(function (event) {
	var val = validar();
	if (val == true) {
		$("#btnguardar").prop('disabled', true);
		event.preventDefault();
		var txtDescripcion = $('#txtDescripcion').val();
		var txtIdSituacion = $('#txtIdSituacion').val();
		var txtIdDispositivo = $('#txtIdDispositivo').val();
	
		

		registrarsituacion(txtDescripcion, txtIdSituacion, txtIdDispositivo);
		this.disabled = false;
	}
});

$(document).ready(function () {
	$('input').focusout(function () {
		this.value = this.value.toLocaleUpperCase();
	});
	$('#txtDescripcion').valcn(' abcdefghijklmnñopqrstuvwxyzáéiou');
	
	
	$(".descripcion").keyup(function () {
		if ($(this).val() != "") {$(".error").fadeOut();return false;}
	});
  
});

function validar() {
	    $(".error").remove();
	        if ($(".descripcion").val() == "") {
		            $(".descripcion").focus().after("<span class='error'>Ingrese la descripcion</span>");
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
				window.location.href = "listadosituaciones";
			}) :
			bootbox.alert(response.msj);
		}
	};
	$.ajax(options);

}
