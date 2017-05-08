$(document).ready(function () {

	var tipoDisp = $('#cmbTipo').val();
	bMarca();
	bModelo();
	bDispositivo(tipoDisp);

});



var bDispositivo = function (tipoDisp) {
	$("#txtDispositivo").autocomplete({
		source: '../../buscardispositivo?tipo=' + tipoDisp + '',
		select: function (event, ui) {
			$('#txtIdDis').val(ui.item.value);
			$(this).val(ui.item.label)
			return false;
		},
		autoFocus: false,
		open: function (event, ui) {
			$("#txtIdDis").val('');
		},
		focus: function (event, ui) {
			return false;
		}
	});
}

$(document).on('change', '#cmbTipo', function (event) {
	event.preventDefault();
	$('#txtDispositivo').val("");
	$('#txtIdDis').val("");
	var tipoDisp = $(this).val();
	if (tipoDisp == "DI") {
		$("#tabla_modelo").show(1000);
		$("#tabla_marca").show(1000);
		$("#marca_modelo").show();
		$("#hrT").show();

	} else {
		$("#tabla_modelo").hide();
		$("#tabla_marca").hide();
		$("#marca_modelo").hide();
		$("#hrT").hide();
		$("#tabla_modelo tbody tr").remove();
		$("#tabla_marca tbody tr").remove();
	}


	bDispositivo(tipoDisp);
});

var bMarca = function () {
	$("#bMarca").autocomplete({
		source: '../../buscarmarca',
		select: function (event, ui) {
			$('#id_marca').val(ui.item.value);
			$(this).val(ui.item.label)
			return false;
		},
		autoFocus: false,
		open: function (event, ui) {
			$("#id_marca").val('');
		},
		focus: function (event, ui) {
			return false;
		}
	});
}


var bModelo = function () {
	$("#bModelo").autocomplete({
		source: '../../buscarmodelo',
		select: function (event, ui) {
			$('#id_modelo').val(ui.item.value);
			$(this).val(ui.item.label)
			return false;
		},
		autoFocus: false,
		open: function (event, ui) {
			$("#id_modelo").val('');
		},
		focus: function (event, ui) {
			return false;
		}
	});
}

$(document).on('click', '#btnMarca', function (event) {
	event.preventDefault();
	var id_marca = $('#id_marca').val();
	var id_modelo = $('#id_modelo').val();
	var bitem = $('#bMarca').val();
	var bitem2 = $('#bModelo').val();
	if (id_marca == '' && id_modelo == '') {
		bootbox.alert('<b style="color: #F44336;" >Debe seleccionar una Marca y un Modelo</b>');
	} else {
		if (id_modelo == '') {
			bootbox.alert('<b style="color: #F44336;" >Debe seleccionar una Modelo</b>');
		}else  if (id_marca == '') {
			bootbox.alert('<b style="color: #F44336;" >Debe seleccionar una Marca</b>');
		}else {
			if (id_marca >0 && id_modelo >=0) {
			$("#tabla_marca > tbody").append(
					'<tr data-id=' + id_marca + ' ><td>#</td><td hidden>' + id_marca + '</td><td>' + bitem + '</td><td hidden>' + id_modelo + '</td><td>' + bitem2 + '</td><td><button type="button" class="btn btn-danger btn-xs removerM"><i class="fa fa-fw fa-close"></i></button></td></tr>');
		}
		}
			
	}
	$('#id_marca').val('');
	$('#id_modelo').val('');
	$('#bMarca').val('');
	$('#bModelo').val('');
});

$(document).on('click', '#btnModelo', function (event) {
	event.preventDefault();
	var id_modelo = $('#id_modelo').val();
	var bitem = $('#bModelo').val();
	if (id_modelo == '') {
		bootbox.alert('<b style="color: #F44336;" >Debe seleccionar un Model</b>');
	} else {
		$("#tabla_modelo > tbody").append('<tr data-id=' + id_modelo + ' ><td>#</td><td hidden>' + id_modelo + '</td><td>' + bitem + '</td><td><button type="button" class="btn btn-danger btn-xs removerMo"><i class="fa fa-fw fa-close"></i></button></td></tr>');
	}
	$('#id_modelo').val('');
	$('#bModelo').val('');
});

$(document).on('click', '.removerM', function (event) {
	event.preventDefault();
	$(this).parent().parent().remove();
});

$(document).on('click', '.removerMo', function (event) {
	event.preventDefault();
	$(this).parent().parent().remove();
});

var registrar = function (cmbTipo, txtDescripcion, txtIdDis, rbtFichaTecnica, items_marca) {
	var options = {
		type: 'POST',
		url: '../../registrar',
		data: {'txtDescripcion': txtDescripcion,
			'cmbTipo': cmbTipo,
			'rbtFicha': rbtFichaTecnica,
			'items_marca': items_marca,
			'txtIdDis': txtIdDis,
		},
		dataType: 'json',
		success: function (response) {
			var elemento = response.msj.split(":");
			if (elemento.length > 0) {
				if (elemento[0] == "Error") {
					bootbox.alert(elemento[0] + "" + elemento[1]);
				} else {
					bootbox.alert(response.msj, function () {
						window.location.href = "../../dispositivo";
					});
				}
			}
		}
	};
	$.ajax(options);
};

$(document).on('click', '#btnguardar', function (event) {
	this.disabled = true;
	event.preventDefault();
	var cmbTipo = $('#cmbTipo').val();
	var txtDescripcion = $('#txtDispositivo').val();
	var txtIdDis = $('#txtIdDis').val();
	var rbtFichaTecnica = $('input:radio[id=rbtFicha]:checked').val();
	//var txtId = $('#txtId').val();
	var items_marca = $('#tabla_marca tbody tr').map(function (i, row) {
		return {'id': row.cells[1].textContent,
			'descripcion': row.cells[2].textContent,
			'idModelo': row.cells[3].textContent,
			'modelo': row.cells[4].textContent,
		};
	}).get();


	registrar(cmbTipo, txtDescripcion, txtIdDis, rbtFichaTecnica, items_marca);
});