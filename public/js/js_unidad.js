var registrarunidad = function (txtDescripcion,txtId,txtNumero, txtIdJerarquia) {
	var options = {
		type: 'POST',
		url: '../../registrar',
		data: {'txtDescripcion': txtDescripcion,
			'txtId': txtId,
			'txtNumero':txtNumero,
			'txtIdJerarquia':txtIdJerarquia,
		},
		dataType: 'json',
		success: function (response) {
			var elemento = response.msj.split(":");
			if (elemento.length > 0) {
				if (elemento[0] == "Error") {
					bootbox.alert(elemento[0] + "" + elemento[1]);
				} else {
					bootbox.alert(response.msj, function () {
						window.location.href = "../../unidad";
					});
				}
			}
		}
	};
	$.ajax(options);
};

$("#txtJerarquia").focus(function () {
		$("#txtJerarquia").autocomplete({
			source: '../../buscarUnidadCmb',
			select: function (event, ui) {
				$('#txtIdJerarquia').val(ui.item.value);
				$(this).val(ui.item.label)
				return false;
			},
			autoFocus: false,
			open: function (event, ui) {
				$("#txtIdJerarquia").val('');
			},
			focus: function (event, ui) {
				return false;
			}
		});
});

$(document).on('click', '#btnguardar', function (event) {
	this.disabled = true;
	event.preventDefault();
	var txtDescripcion = $('#txtDescripcion').val();
	var txtNumero = $('#txtNumero').val();
	var txtIdJerarquia = $('#txtIdJerarquia').val();
	var txtId = $('#txtId').val();
	
	

	registrarunidad(txtDescripcion, txtId, txtNumero, txtIdJerarquia);
	//this.disabled=false;

});

function eliminar(id,vigencia){

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
						window.location.href = "unidad";
					}) :
					bootbox.alert(response.msj);
				$("#btnguardar").prop('disabled', false);
		}
	};
	$.ajax(options);
	
}
