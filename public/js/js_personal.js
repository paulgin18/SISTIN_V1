var registrar = function (txtId,txtNombre, txtApellidos,txtDNI,id_area,responsable) {
	alert(responsable);
	var options = {
		type: 'POST',
		url: '../../registrar',
		data: {'txtId':txtId,
			'txtNombre': txtNombre,
			'txtApellidos': txtApellidos,
			'txtDNI':txtDNI,
			'id_area':id_area,
			'responsable':responsable==1?true:false,
		},
		dataType: 'json',
		success: function (response) {
			(response.error == 0) ?
					bootbox.alert(response.msj, function () {
						window.location.href = "../../personal";
					})
					: bootbox.alert(response.msj);
			$("#btnguardar").prop('disabled', false);

		}
	};
	$.ajax(options);
};

/*
$(document).submit(function (event) {
	var val = validar();
	if (val == true) {
		$("#btnguardar").prop('disabled', true);
		event.preventDefault();
		event.preventDefault();
		var txtDescripcion = $('#txtDescripcion').val();
		var txtId = $('#txtId').val();
		registrar(txtDescripcion, txtId);
		this.disabled = false;
	}
});
*/

$(document).on('click', '#btnguardar', function (event) {
	this.disabled = true;
	event.preventDefault();
	var txtId = $('#txtId').val(); 
	var txtNombre = $('#txtNombre').val();
	var txtApellidos = $('#txtApellidos').val();
	var txtDNI = $('#txtDNI').val();
	var id_area = $('#id_area').val();
	var responsable= $('input:checkbox[id=chkResponsable]:checked').val()

	
	
	registrar(txtId,txtNombre, txtApellidos,txtDNI,id_area,responsable);
	//this.disabled=false;

});


function validar() {
	    $(".error").remove();
	        if ($(".descripcion").val() == "") {
		            $(".descripcion").focus().after("<span class='error'>Ingrese la descripcion.</span>");
		            return false;
	        }
	return true;
}


$("#txtArea").focus(function (){
	$("#txtArea").autocomplete({
		source: '../../../../area/area/buscararea',
		select: function (event, ui) {
			$('#id_area').val(ui.item.value);
			$(this).val(ui.item.label)
			return false;
		},
		autoFocus: false,
		open: function (event, ui) {
			$("#id_area").val('');
		},
		focus: function (event, ui) {
			return false;
		}
	});
});


var eliminar = function ($cod, $vigencia) {
	var options = {
		type: 'POST',
		url: 'eliminar',
		data: {'cod': $cod, 'vigencia': $vigencia,
		},
		dataType: 'json',
		success: function (response) {
			(response.error == 0) ? bootbox.alert(response.msj, function () {
				window.location.href = "personal";
			}) :
					bootbox.alert(response.msj);
		}
		, error: function () {
			this.disabled = true;
		}
	};
	$.ajax(options);
}
