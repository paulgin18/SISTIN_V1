var registrar = function (txtDescripcion, txtId) {
	var options = {
		type: 'POST',
		url: '../../registrar',
		data: {'txtDescripcion': txtDescripcion,
			'txtId': txtId,
		},
		dataType: 'json',
		success: function (response) {
			(response.error == 0) ?
					bootbox.alert(response.msj, function () {
						window.location.href = "../../marca";
					})
					: bootbox.alert(response.msj);
			$("#btnguardar").prop('disabled', false);

		}
	};
	$.ajax(options);
};

$(document).on('click', '#btnguardar', function (event) {
	this.disabled = true;
	event.preventDefault();
	var txtDescripcion = $('#txtDescripcion').val();
	var txtId = $('#txtId').val();
	registrar(txtDescripcion, txtId);
});

$(document).ready(function () {
	$('input').focusout(function () {
		this.value = this.value.toLocaleUpperCase();
	});
	$('#txtDescripcion').valcn(' abcdefghijklmnñopqrstuvwxyzáéiou');

	$(".descripcion").keyup(function () {
		if ($(this).val() != "") {
			$(".error").fadeOut();
			return false;
		}
	});
  
});


function validar() {
	    $(".error").remove();
	        if ($(".descripcion").val() == "") {
		            $(".descripcion").focus().after("<span class='error'>Ingrese la descripcion.</span>");
		            return false;
	        }
}

var eliminar = function ($cod, $vigencia) {
	alert($vigencia);
	var options = {
		type: 'POST',
		url: 'eliminar',
		data: {'cod': $cod, 'vigencia': $vigencia,
		},
		dataType: 'json',
		success: function (response) {
			(response.error == 0) ? bootbox.alert(response.msj, function () {
				window.location.href = "marca";
			}) :
					bootbox.alert(response.msj);
		}
	};
	$.ajax(options);

}
