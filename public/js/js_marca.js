var registrarmarca = function (txtDescripcion,txtId) {
	var options = {
		type: 'POST',
		url: '../../registrarmarca',
		data: {'txtDescripcion': txtDescripcion,
			'txtId': txtId,
		},
		dataType: 'json',
		success: function (response) {
			(response.error == 0) ?
					bootbox.alert(response.msj, function () {
						window.location.href = "../../listadomarcas";
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
		var txtId = $('#txtId').val();
		registrarmarca(txtDescripcion,txtId);
		this.disabled = false;
	}
});

$(document).ready(function () {
	$('input').focusout(function () {
		this.value = this.value.toLocaleUpperCase();
	});
	$('#txtDescripcion').valcn(' abcdefghijklmnñopqrstuvwxyzáéiou');
	
	$(".marca, .descripcion").keyup(function () {
		if ($(this).val() != "") {$(".error").fadeOut();return false;}
	});
  
});

function validar() {
	    $(".error").remove();
	        if ($(".descripcion").val() == "") {
		            $(".descripcion").focus().after("<span class='error'>Ingrese la descripcion</span>");
		            return false;
	        } else if ($(".marca").val() == "") {
		            $(".marca").focus().after("<span class='error'>Ingrese una marca</span>");
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
				window.location.href = "listadomarcas";
			}) :
			bootbox.alert(response.msj);
		}
	};
	$.ajax(options);

}
