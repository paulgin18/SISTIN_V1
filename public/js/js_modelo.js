var registrar = function (txtDescripcion, txtCapacidad, txtId) {
	var options = {
		type: 'POST',
		url: '../../registrar',
		data: {'txtDescripcion': txtDescripcion,
			'txtCapacidad': txtCapacidad,
			'txtId': txtId,
		},
		dataType: 'json',
		success: function (response) {
			(response.error == 0) ?
					bootbox.alert(response.msj, function () {
						window.location.href = "../../modelo";
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
		var txtCapacidad = $('#txtCapacidad').val();
		var txtId = $('#txtId').val();
		registrar(txtDescripcion, txtCapacidad, txtId);
		this.disabled = false;
	}
});

$(document).ready(function () {
	$('input').focusout(function () {
		this.value = this.value.toLocaleUpperCase();
	});

	$(".cantidad, .descripcion").keyup(function () {
		if ($(this).val() != "") {
			$(".error").fadeOut();
			return false;
		}
	});
  
});


function validar() {
	    $(".error").remove();
	        if ($(".descripcion").val() == "") {
		            $(".descripcion").focus().after("<span class='error'>Ingrese el modelo</span>");
		            return false;
	        } else if ($(".anio").val() == "") {
		            $(".cantidad").focus().after("<span class='error'>Ingrese un Cantidad</span>");
		            return false;
	        } else {
		return true;
	}
}

var eliminar = function ($cod, $vigencia) {
	var options = {
		type: 'POST',
		url: 'eliminar',
		data: {'cod': $cod, 'vigencia': $vigencia,
		},
		dataType: 'json',
		success: function (response) {
			(response.error == 0) ?
					bootbox.alert(response.msj, function () {
						window.location.href = "modelo";
					}) :
					bootbox.alert(response.msj);
		}
	};
	$.ajax(options);

}
