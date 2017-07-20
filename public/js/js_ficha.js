$(document).data('tipoDisp', 2);
$(document).ready(function () {
	$(".actionBar").hide();
	$(".stepContainer").hide();
});

var bDispositivo = function (tipoDisp, id, inp) {
	$(inp).autocomplete({
		source: '../../../../dispositivo/dispositivo/buscardispositivo?tipo=' + tipoDisp + '',
		select: function (event, ui) {
			$(id).val(ui.item.value);
			$(this).val(ui.item.label)
			if (inp == "#txtEquipo") {
				especificos(ui.item.value);
			}
			if (ui.item.label == "TELEFONO" || ui.item.label == "TELEFONO CELULAR") {
				$("#divDatosNuevo").hide();
				$("#divTelefono").show();
				if (ui.item.label == "TELEFONO") {
					$("#FECHAAD").show();
					$("#FECHAREN").show();
					$("#IMEI").hide();
					$("#tblIMEI").hide();
					$("#tblFAdquisicion").show();
					$("#tblFRenovacion").show();
				} else if (ui.item.label == "TELEFONO CELULAR") {
					$("#IMEI").show();
					$("#FECHAAD").show();
					$("#FECHAREN").show();
					$("#tblIMEI").show();
					$("#tblFAdquisicion").show();
					$("#tblFRenovacion").show();
				} 
			} else {
				$("#divDatosNuevo").show();
				$("#divTelefono").hide();
					$("#IMEI").hide();
					$("#FECHAAD").hide();
					$("#FECHAREN").hide();
					$("#tblIMEI").hide();
					$("#tblFAdquisicion").hide();
					$("#tblFRenovacion").hide();
			}
			return false;
		},
		autoFocus: false,
		open: function (event, ui) {
			$(id).val('');
		},
		focus: function (event, ui) {
			return false;
		}
	});
}

//$(document).on('change', '#cmbTipo', function (event) {
//	event.preventDefault();
//	$('#txtDispositivo').val("");
//	$('#txtIdDis').val("");
//	var tipoDisp = $(this).val();
//	if (tipoDisp == "DI") {
//		$("#tabla_modelo").show(1000);
//		$("#tabla_marca").show(1000);
//		$("#marca_modelo").show();
//		$("#hrT").show();
//
//	} else {
//		$("#tabla_modelo").hide();
//		$("#tabla_marca").hide();
//		$("#marca_modelo").hide();
//		$("#hrT").hide();
//		$("#tabla_modelo tbody tr").remove();
//		$("#tabla_marca tbody tr").remove();
//	}
//
//
//	bDispositivo(tipoDisp);
//});

$("#txtSoftCL").focus(function (event) {
	var tipo = 1;
	$("#txtSoftCL").autocomplete({
		source: '../../../../dispositivo/dispositivo/bSofDisp?tipo=' + tipo + '',
		select: function (event, ui) {
			$('#txtIdSofCl').val(ui.item.value);
			$(this).val(ui.item.label);
			$("#tipoSoft").val(ui.item.tipo);
			return false;
		},
		autoFocus: false,
		open: function (event, ui) {
			$("#txtIdSofCl").val('');
		},
		focus: function (event, ui) {
			return false;
		}
	});
});

$("#txtMaMo").focus(function () {
	var id_disp = $('#txtIdDis').val();
	if ($('#txtIdDis').val() == '') {
		bootbox.alert('<b style="color: #F44336;" >Debe seleccionar el dispositivo</b>');
	} else {
		$("#txtMaMo").autocomplete({

			source: '../../../../dispositivo/dispositivo/bMaMoxDisp?id_disp=' + id_disp + '',
			select: function (event, ui) {
				$('#txtIdDisMaMo').val(ui.item.value);
				$(this).val(ui.item.label)
				return false;
			},
			autoFocus: false,
			open: function (event, ui) {
				$("#txtIdDisMaMo").val('');
			},
			focus: function (event, ui) {
				return false;
			}
		});
	}
});


$("#txtUnidadOrganica").focus(function () {

	$("#txtUnidadOrganica").autocomplete({
		minLength: 0,
		source: '../../../../unidad/unidad/buscarUnidadCmb',
		dataType: "json",
		select: function (event, ui) {
			$('#txtIdUnidadOrganica').val(ui.item.value);
			$(this).val(ui.item.label)
			return false;
		},

		open: function (event, ui) {
			$("#txtIdUnidadOrganica").val('');
		},
		focus: function (event, ui) {
			$(this).val(ui.item.label);
			return false;
		}

	});

});

$("#txtRespFuncionario").focus(function () {

	$("#txtRespFuncionario").autocomplete({
		minLength: 0,
		source: '../../../../personal/personal/buscarFuncionario',
		dataType: "json",
		select: function (event, ui) {
			$('#txtIdRespFuncionario').val(ui.item.value);
			$(this).val(ui.item.label);
			$("#txtRespPatrimonio").val(ui.item.respatrimonial.split("\\")[1]);
			$("#txtIdRespPatrimonio").val(ui.item.respatrimonial.split("\\")[0]);
			$("#txtAreaServ").val(ui.item.area);
			$("#txtIdAreaServ").val(ui.item.id_area);
			$("#txtIdUnidadOrganica").val(ui.item.id_uni_org);
			$("#txtUnidadOrganica").val(ui.item.unidad_organica);
			return false;
		},

		open: function (event, ui) {
			$("#txtIdUnidadOrganica").val('');
		},
		focus: function (event, ui) {
			$(this).val(ui.item.label);
			return false;
		}

	});

});


$("#txtUnidadOrganica").keydown(function () {

	$("#txtUnidadOrganica").autocomplete({
		minLength: 0,
		source: '../../../../unidad/unidad/buscarUnidadCmb',
		dataType: "json",
		select: function (event, ui) {
			$('#txtIdUnidadOrganica').val(ui.item.value);
			$(this).val(ui.item.label)
			return false;
		},

		open: function (event, ui) {
			$("#txtIdUnidadOrganica").val('');
		},
		focus: function (event, ui) {
			$(this).val(ui.item.label);
			return false;
		}

	});

});

$("#txtUnidadOrganica").keyup(function () {

	$("#txtUnidadOrganica").autocomplete({
		minLength: 0,
		source: '../../../../unidad/unidad/buscarUnidadCmb',
		dataType: "json",
		select: function (event, ui) {
			$('#txtIdUnidadOrganica').val(ui.item.value);
			$(this).val(ui.item.label)
			return false;
		},

		open: function (event, ui) {
			$("#txtIdUnidadOrganica").val('');
		},
		focus: function (event, ui) {
			$(this).val(ui.item.label);
			return false;
		}


	}).data("ui-autocomplete")._renderItem = function (ul, item) {
		return $("<li>")
				.data("ui-autocomplete-item", item)
				.append("<a>" + item.label + "</a>")
				.appendTo(ul);
	};

});


$("#txtUnidadOrganica").on("click", function () {

	$("#txtUnidadOrganica").autocomplete({
		minLength: 0,
		source: '../../../../unidad/unidad/buscarUnidadCmb',
		dataType: "json",
		select: function (event, ui) {
			$('#txtIdUnidadOrganica').val(ui.item.value);
			$(this).val(ui.item.label)
			return false;
		},

		open: function (event, ui) {
			$("#txtIdUnidadOrganica").val('');
		},
		focus: function (event, ui) {
			$(this).val(ui.item.label);
			return false;
		}


	}).data("ui-autocomplete")._renderItem = function (ul, item) {
		return $("<li>")
				.data("ui-autocomplete-item", item)
				.append("<a>" + item.label + "</a>")
				.appendTo(ul);
	};

});

$("#txtAreaServ").focus(function () {
	$("#txtAreaServ").autocomplete({
		source: '../../../../area/area/buscarAreaCmb',
		select: function (event, ui) {
			$('#txtIdUnidadOrganica').val(ui.item.idunidadeorg);
			$('#txtIdAreaServ').val(ui.item.value);
			$('#txtUnidadOrganica').val(ui.item.unidadorganica);
			$(this).val(ui.item.label)
			return false;
		},
		autoFocus: false,
		open: function (event, ui) {
			$("#txtIdUnidadOrganica").val('');
			$("#txtIdAreaServ").val('');
			$('#txtUnidadOrganica').val('');
		},
		focus: function (event, ui) {
			return false;
		}
	});

});


$("#txtFichaMaMo").focus(function () {
	var id_disp = $('#txtIdEquipo').val();
	if ($('#txtIdEquipo').val() == '') {
		bootbox.alert('<b style="color: #F44336;" >Debe seleccionar el dispositivo</b>');
	} else {
		$("#txtFichaMaMo").autocomplete({
			source: '../../../../dispositivo/dispositivo/bMaMoxDisp?id_disp=' + id_disp + '',
			select: function (event, ui) {
				$('#txtFichaIdDis').val(ui.item.value);
				$(this).val(ui.item.label)
				return false;
			},
			autoFocus: false,
			open: function (event, ui) {
				$("#txtFichaIdDis").val('');
			},
			focus: function (event, ui) {
				return false;
			}
		});
	}
});

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

$(document).on('click', '#btnAnadirDisp', function (event) {
	event.preventDefault();
	var txtIdDisMaMo = $('#txtIdDisMaMo').val();
	var dispositivo = $('#txtDispositivo').val();
	$("#txtDispositivo").focus();
	var txtId = $('#txtIdDis').val();
	var itemMaMo = $('#txtMaMo').val();
	var txtSerie = $('#txtSerieMaMo').val();
	var chk = $('input:checkbox[id=chkOpOtros]:checked').val();
	if (chk == 1) {
		chk = 'SI';
	} else {
		chk = 'NO';
	}
	if (dispositivo == '' && txtIdDisMaMo == '') {
		bootbox.alert('<b style="color: #F44336;" >Debe seleccionar una Dispositivo , Marca y un Modelo</b>');
		$("#txtDispositivo").focus();
	} else {
		if (dispositivo == '') {
			bootbox.alert('<b style="color: #F44336;" >Debe seleccionar una Dispositivo</b>');
			$("#txtDispositivo").focus();
		} else if (txtIdDisMaMo == '') {
			bootbox.alert('<b style="color: #F44336;" >Debe seleccionar una Marca y modelo</b>');
			$("#txtDispositivo").focus();
		} else {
			if (txtId > 0 && txtIdDisMaMo >= 0) {
				verificar(txtIdDisMaMo, dispositivo, itemMaMo, txtSerie, chk);
				$("#txtDispositivo").focus();
				//test();
				$('#txtIdDis').val('');
				$('#txtDispositivo').val('');
				$('#txtMaMo').val('');
				$('#txtSerieMaMo').val('');
				$('#txtIdDisMaMo').val('');
			}
		}
	}


});

$(document).on('click', '#btnAnadirDocumento', function (event) {
	event.preventDefault();
	var txtId = $('#cmbDocumento').val();
	var documento = $('#txtDocumento').val();
	var fecha = $('#txtFechaDoc').val();
	$("#txtDocumento").focus();
	if (documento == '') {
		bootbox.alert('<b style="color: #F44336;" >Debe Ingresar el Numero del Documento</b>');
		$("#txtDocumento").focus();
	} else {
		if (txtId > 0) {
			verificarDocumento(txtId, documento, fecha);
			$("#txtDocumento").focus();
			$('#txtDocumento').val('');
		}
	}
});

var verificarDocumento = function (txtId, documento, fecha) {
	var campo1;
	$("#tabla_adquisicion tbody tr").each(function (index) {
		$(this).children("td").each(function (index2) {
			switch (index2) {
				case 1:
					campo1 = $(this).text();
					break;
			}
		})
	});
	if (campo1 === txtId) {
		bootbox.alert('<b style="color: #F44336;" >El tipo de documento ya ha sido ingresado</b>');
	} else {
		$("#tabla_adquisicion > tbody").append(
				'<tr data-id=' + txtId + ' ><td>#</td><td hidden>' + txtId + '</td><td>' + $("#cmbDocumento option:selected").text() + '</td><td>' + documento + '</td><td>' + fecha + '</td><td><button type="button" class="btn btn-danger btn-xs removerM"><i class="fa fa-fw fa-close"></i></button></td></tr>');
	}
	;
}


$(document).on('click', '#btnAnadirRam', function (event) {
	event.preventDefault();
	var idRam = $('#txtIdMR').val();
	var dispositivo = $('#txtMR').val();
	if (dispositivo == '' && idRam == '') {
		bootbox.alert('<b style="color: #F44336;" >Debe seleccionar la Memoria Ram</b>');
	} else {
		insertarRam(idRam, dispositivo);
		$("#txtMR").focus();
		$('#txtMR').val('');
		$('#txtIdMR').val('');
	}


});

$(document).on('click', '#btnAnadirUser', function (event) {
	event.preventDefault();
	var tipo = $('#cmbTipoUser').val();
	var user = $('#txtUsuario').val();
	var pass = $('#txtPass').val();
	if (user == '' && pass == '') {
		$("#txtUsuario").focus();
		bootbox.alert('<b style="color: #F44336;" >Debe ingresar el Usuario y Contraseña.</b>');
	} else {
		if (user == '') {
			$("#txtUsuario").focus();
			bootbox.alert('<b style="color: #F44336;" >Debe ingresar el Usuario.</b>');
		} else if (pass == '') {
			$("#txtUsuario").focus();
			bootbox.alert('<b style="color: #F44336;" >Debe ingresar la Contraseña.</b>');
		} else {
			insertarUser(tipo, user, pass);
			$("#txtUsuario").focus();
			$('#txtUsuario').val('');
			$('#txtPass').val('');
		}
	}

});



var insertarRam = function (idRam, dispositivo) {
	var campo1;
	$("#tabla_ram tbody tr").each(function (index) {
		$(this).children("td").each(function (index2) {
			switch (index2) {
				case 1:
					campo1 = $(this).text();
					break;
			}
		})
	});
	if (campo1 === idRam) {
		bootbox.alert('<b style="color: #F44336;" >La Ram ya ha sido ingresada</b>');

	} else {
		$("#tabla_ram > tbody").append(
				'<tr data-id=' + idRam + ' ><td>#</td><td hidden>' + idRam + '</td><td>' + dispositivo + '</td></td><td><button type="button" class="btn btn-danger btn-xs removerM"><i class="fa fa-fw fa-close"></i></button></td></tr>');
	}
	;
}

var insertarUser = function (tipo, user, pass) {
	var campo1;
	$("#tabla_user tbody tr").each(function (index) {
		$(this).children("td").each(function (index2) {
			switch (index2) {
				case 1:
					campo1 = $(this).text();

					break;
			}
		})
	});
	if (campo1 === user) {
		bootbox.alert('<b style="color: #F44336;" >El Usuario ya ha sido ingresado</b>');

	} else {
		$("#tabla_user > tbody").append(
				'<tr data-id=' + user + '><td>#</td><td hidden>' + user + '</td><td hidden>' + tipo + '</td><td hidden>' + pass + '</td><td>' + (tipo === 'A' ? "Administrador" : "Usuario") + '</td><td>' + user + '</td><td>*****</td></td><td><button type="button" class="btn btn-danger btn-xs removerM"><i class="fa fa-fw fa-close"></i></button></td></tr>');
	}
	;
}

$(document).on('click', '#btnAnadirSoft', function (event) {
	if ($('#txtIdSofCl').val() == '') {
		bootbox.alert('<b style="color: #F44336;" >Debe seleccionar el software</b>');
	} else {
		event.preventDefault();
		var chk = $('input:checkbox[id=chkLicencia]:checked').val();
		if (chk == 1) {
			chk = 'Si';
		} else {
			chk = 'No';
		}
		insertarSoft($('#txtSoftCL').val(), $('#txtIdSofCl').val(), $('#tipoSoft').val(), chk, $("#txtSoftEdicion").val(), $("#txtSoftVersion").val(), $("#txtSoftNroLicencia").val());
		$("#txtSoftCL").focus();
		$('#txtSoftCL').val('');
		$('#txtSoftEdicion').val('');
		$('#txtIdSofCl').val('');
		$('#tipoSoft').val('');
		$('#txtSoftVersion').val('');
		$('#txtSoftNroLicencia').val('');
	}
});


$("#txtMarcaPC").focus(function () {
	$("#txtMarcaPC").autocomplete({
		source: '../../../../dispositivo/dispositivo/bMaxDisp?idDisp=' + $(document).data('tipoDisp') + '',
		select: function (event, ui) {
			$('#txtIdMPc').val(ui.item.value);
			$(this).val(ui.item.label)
			return false;
		},
		autoFocus: false,
		open: function (event, ui) {
			$("#txtIdMPc").val('');
		},
		focus: function (event, ui) {
			return false;
		}
	});
});

$("#txtSO").focus(function () {
	$("#txtSO").autocomplete({
		source: '../../../../dispositivo/dispositivo/bSofDisp?tipo=SO',
		select: function (event, ui) {
			$('#txtIdMPc').val(ui.item.value);
			$(this).val(ui.item.label)
			return false;
		},
		autoFocus: false,
		open: function (event, ui) {
			$("#txtIdMPc").val('');
		},
		focus: function (event, ui) {
			return false;
		}
	});
});
$("#txtEquipo").focus(function () {
	bDispositivo('DI', '#txtIdEquipo', "#txtEquipo");
});

$("#txtDispositivo").focus(function () {
	bDispositivo('DI', '#txtIdDis', '#txtDispositivo');
});

function especificos(dato) {

	if (dato == '2') {
		$("#datosDetalleFicha").show()
		$("#pcCompatible").show();
		$(document).data('tipoDisp', 2);
		$("#divComponentes").hide();
		$("#step-1").show();
		$("#stepEspecifico").show();
		$(".wizard_steps").show();
		$(".actionBar").show();
		$(".stepContainer").show();

		$("#ficha").val("2");
	} else if (dato == '1') {
		$("#datosDetalleFicha").show()
		$('#chkCompatible').prop('checked', false);
		$("#datosPC").show();
		$("#pcCompatible").hide();
		$("#txtIdMPc").val("");
		$("#txtMarcaPC").val("");
		$("#txtSeriePC").val("");
		$(document).data('tipoDisp', 1);
		$("#divComponentes").hide();
		$("#stepEspecifico").show();
		$("#step-1").show();
		$(".stepContainer").show();
		$(".wizard_steps").show();
		$(".actionBar").show();
		$("#txtSeriePC").val("");
		$("#ficha").val("1");
	} else {
		$("#datosDetalleFicha").show();
		$("#step-1").hide();
		$("#stepEspecifico").hide();
		$(".wizard_steps").hide();
		$(".actionBar").hide();
		$(".stepContainer").hide();
		$(".stepContainer").hide();
		$("#divComponentes").show();
		$("#ficha").val("3");
	}
}

$("#txtMI").focus(function () {
	bCI(3, '#txtIdMI', "#txtMI");
});


$("#txtSeriePC").keyup(function () {
	$(this).val() != '' || $(this).val().toString().length > 0 ?
			bSerie($(this).val(), '#lblASeriePc', "#lblNSeriePc") : ($('#lblASeriePc').hide(), $('#lblNSeriePc').hide());
});

$("#txtLicenciaSO").keyup(function () {
	$(this).val() != '' || $(this).val().toString().length > 0 ?
			bSerie($(this).val(), '#lblALicenciaSO', "#lblNLicenciaSO") : ($('#lblALicenciaSO').hide(), $('#lblNLicenciaSO').hide());
});

$("#txtSerieDD").keyup(function () {
	$(this).val() != '' || $(this).val().toString().length > 0 ?
			bSerie($(this).val(), '#lblASerieDD', "#lblNSerieDD") : ($('#lblASerieDD').hide(), $('#lblNSerieDD').hide());
});

$("#txtSerieMain").keyup(function () {
	$(this).val() != '' || $(this).val().toString().length > 0 ?
			bSerie($(this).val(), '#lblASerieMain', "#lblNSerieMain") : ($('#lblASerieMain').hide(), $('#lblNSerieMain').hide());
});
$("#txtSerieRed").keyup(function () {
	$(this).val() != '' || $(this).val().toString().length > 0 ?
			bSerie($(this).val(), '#lblASerieRed', "#lblNSerieRed") : ($('#lblASerieRed').hide(), $('#lblNSerieRed').hide());
});
$("#txtSerieMaMo").keyup(function () {
	var labelA, labelN;
	if ($("#txtFichaIdDis").val().trim().length > 0||$("#txtFichaIdDis").val()>0	) {
		labelA = "#lblASerieMaMo2";
		labelN = "#lblNSerieMaMo2";
	} else {
		labelA = "#lblASerieMaMo";
		labelN = "#lblNSerieMaMo";
	}

	$(this).val() != '' || $(this).val().toString().length > 0 ?
			bSerie($(this).val(), labelA, labelN) : ($(labelA).hide(), $(labelN).hide());
});
$("#txtFichaSerieMaMo").keyup(function () {
	$(this).val() != '' || $(this).val().toString().length > 0 ?
			bSerie($(this).val(), '#lblASerieMaMo2', "#lblNSerieMaMo2") : ($('#lblASerieMaMo2').hide(), $('#lblNSerieMaMo2').hide());
});

$("#txtSoftNroLicencia").keyup(function () {
	$(this).val() != '' || $(this).val().toString().length > 0 ?
			bSerie($(this).val(), '#lblASerieSoft', "#lblNSerieSoft") : ($('#lblASerieSoft').hide(), $('#lblNSerieSoft').hide());
});

function bSerie(ser, lbla, lbln) {
	var options = {
		type: 'POST',
		url: '../../bserie',
		data: {'serie': ser},
		dataType: 'json',
		success: function (response) {
			if (response.msj == 0) {
				$(lbla).show();
				$(lbln).hide();
			} else if (response.msj >= 1) {
				$(lbla).hide();
				$(lbln).show();
			} else {
			}
		}
	};
	$.ajax(options);
}

$("#txtMR").focus(function () {
	bCI(4, '#txtIdMR', '#txtMR');
});
$("#txtDD").focus(function () {
	bCI(5, '#txtIdDD', '#txtDD');
});

$("#txtMain").focus(function () {
	bCI(6, '#txtIdMain', '#txtMain');
});

$("#txtRed").focus(function () {
	bCI(7, '#txtIdRed', '#txtRed');
});
function bCI(idDisp, id, des) {
	$(des).autocomplete({
		source: '../../../../dispositivo/dispositivo/bMaMoxDisp?id_disp=' + idDisp + '',
		select: function (event, ui) {
			$(id).val(ui.item.value);
			$(this).val(ui.item.label)
			return false;
		},
		autoFocus: false,
		open: function (event, ui) {
			$(id).val('');
		},
		focus: function (event, ui) {
			return false;
		}
	});
}


$(document).on('click', '#btnModelo', function (event) {
	event.preventDefault();
	var id_modelo = $('#id_modelo').val();
	var bitem = $('#bModelo').val();
	if (id_modelo == '') {
		bootbox.alert('<b style="color: #F44336;" >Debe seleccionar un Modelo</b>');
	} else {
		$("#tabla_modelo > tbody").append('<tr data-id=' + id_modelo + ' ><td>#</td><td hidden>' + id_modelo + '</td><td>' + bitem + '</td><td><button type="button" class="btn btn-danger btn-xs removerMo"><i class="fa fa-fw fa-close"></i></button></td></tr>');
	}
	$('#id_modelo').val('');
	$('#bModelo').val('');
});
$(document).on('click', '#btnAnadirDAd', function (event) {
	$("#datosAdquisicion").show();
	$("#txtPecosa").focus();

});

$(document).on('click', '#btnAnadirEditDAd', function (event) {
	$("#datosAdquisicion").show();
	$("#txtPecosa").focus();
	docAdquisicion();
});

$(document).on('click', '#btnAnadirDetaFicha', function (event) {
	
especificos($("#txtIdEquipo").val());
});


function datosEspecificos(){
	var options = {
		type: 'POST',
		url: '../../datosEspecificos',
		data: {'id': $("#txtIdficha").val(),
		},
		dataType: 'html',
		success: function (response) {
			$("#datosBDAd").html(response);
		}
	};
	$.ajax(options);
}

function docAdquisicion(){
	var options = {
		type: 'POST',
		url: '../../documentos',
		data: {'id': $("#txtIdficha").val(),
		},
		dataType: 'html',
		success: function (response) {
			$("#datosBDAd").html(response);
		}
	};
	$.ajax(options);
}


$(document).on('click', '#btnAnadirFichaDisp', function (event) {
	event.preventDefault();
	var id = $('#txtFichaIdDis').val();
	var modelo = $('#txtFichaMaMo').val();
	var serie = $('#txtFichaSerieMaMo').val();
	var inventario = $('#txtFichaCodInventario').val();
	var chk = $('input:checkbox[id=chkOpFichaDisp]:checked').val();
	var imei = $('#txtIMEI').val();
	var fechaRenovacion = $('#txtFRCel').val();
	var fechaAdquisicion = $('#txtFACel').val();
	if (chk == 1) {
		chk = 'SI';
	} else {
		chk = 'NO';
	}
	if (id == '') {
		bootbox.alert('<b style="color: #F44336;" >Debe seleccionar Marca y  Modelo</b>');
	} else {
		insertFichaDisp(id, modelo, serie, inventario, chk, imei, fechaRenovacion, fechaAdquisicion);
	}

	$("#txtFichaMaMo").focus();
	$('input:checkbox[id=chkOpFichaDisp]:checked').val()
	$('#txtFichaIdDis').val('');
	$('#lblASerieMaMo').hide();
	$('#lblASerieMaMo2').hide();
	$('#lblNSerieMaMo').hide();
	$('#lblNSerieMaMo2').hide();
	$('#txtFichaMaMo').val('');
	$('#txtFACel').val('');
	$('#txtFRCel').val('');
	$('#txtIMEI').val('');
	$('#txtFichaSerieMaMo').val('');
	$('#txtFichaCodInventario').val('');
});

var insertFichaDisp = function (id, modelo, serie, inventario, chk, imei, fechaRenovacion, fechaAdquisicion) {
	var d = 0;
	var tblIM=0;
	var tblFa=0;
	var tblFr=0;
	$("#txtEquipo").val() == "TELEFONO CELULAR" ? d = 1 :
			$("#txtEquipo").val() == "TELEFONO" ? d = 2 : d = 0;
	if (d == 1) {
		tblIM='<td id="tblIMEI" name="tblIMEI" style="display:block">' + imei + '</td>';;
		tblFa='<td id="tblFAdquisicion" name="tblFAdquisicion" style="display:'+tblFa+'">' + fechaAdquisicion  + '</td>';
		tblFr='<td id="tblFRenovacion" name="tblFRenovacion" >' + fechaRenovacion  + '</td>';
	} else if (d == 2) {
		tblIM='<td id="tblIMEI" name="tblIMEI" style="display:none">null</td>';
		tblFa='<td id="tblFAdquisicion" name="tblFAdquisicion" style="display:'+tblFa+'">' + fechaAdquisicion  + '</td>';
		tblFr='<td id="tblFRenovacion" name="tblFRenovacion" >' + fechaRenovacion  + '</td>';
	} else if (d == 0) {
		tblIM='<td id="tblIMEI" name="tblIMEI" style="display:none">null</td>';
		tblFa='<td id="tblFAdquisicion" name="tblFAdquisicion" style="display:none">null</td>';
		tblFr='<td id="tblFRenovacion" name="tblFRenovacion" style="display:none">null</td>';
	}

	var campo1;
	$("#tabla_ficha_disp tbody tr").each(function (index) {
		$(this).children("td").each(function (index2) {
			switch (index2) {
				case 1:
					campo1 = $(this).text();
					break;
			}
		})
	});
	if (campo1 === id) {
		bootbox.alert('<b style="color: #F44336;" >La Marca y el Modelo para el Mismo Dispositvo, ya ha sido ingresado</b>');
	} else {
		var dato='<tr data-id=' + id + ' >'
				+'<td>#</td>'
				+'<td hidden>' + id + '</td>'
				+'<td>' + modelo +'</td>'
		        +'<td>' + serie + '</td>'
				+'<td>' + inventario + '</td>'
				+ tblIM;

		$("#tabla_ficha_disp > tbody").append(dato+tblFa+tblFr
				//+ '<td id="tblFAdquisicion" name="tblFAdquisicion" style="display:'+tblFa+'">' + fechaAdquisicion + '</td>'
				//+ '<td id="tblFAdquisicion" name="tblFAdquisicion" style="display:'+tblFa+'">' + fechaAdquisicion + '</td>'
//				+ '<td id="tblFRenovacion" name="tblFRenovacion" style="display:'+tblFr+'">'+ fechaRenovacion + '</td>'
				+ '<td>' + chk + '</td>'
				+ '<td><button type="button" class="btn btn-danger btn-xs removerMo"><i class="fa fa-fw fa-close"></i></button></td>'
				+ '</tr>');
	}
	
}

$(document).on('click', '#chkCompatible', function (event) {
	var chk = $('input:checkbox[id=chkCompatible]:checked').val();
	if (chk == 1) {
		//	$("#chkGarantia").prop("checked", "");
		$("#datosPC").hide();
		$("#txtMarcaPC").val("");
		$("#txtIdMPc").val("");
		$("#txtSeriePC").val("");
	} else {
		//	$("#chkGarantia").prop("checked", "checked")
		$("#datosPC").show();
		$("#txtMarcaPC").val("");
		$("#txtIdMPc").val("");
		$("#txtSeriePC").val("");
		//$('input:checkbox[id=chkGarantia]:checked').val();
	}
});


$(document).on('click', '#chkRedIntegrada', function (event) {
	var chk = $('input:checkbox[id=chkRedIntegrada]:checked').val();
	if (chk == 1) {
		$("#integrada").hide();
		$("#txtRed").val("");
		$("#txtIdRed").val("");
		$("#txtSerieRed").val("");
	} else {
		$("#integrada").show();
	}
});


$(document).on('click', '#chkGarantia', function (event) {
	var chk = $('input:checkbox[id=chkGarantia]:checked').val();
	if (chk == 1) {
		$('#divAnioGarantia').show();
		$('#txtAnioGarantia').val("");
	} else {
		$('#divAnioGarantia').hide();
		$('#txtAnioGarantia').val("0");
	}
});


$(document).on('click', '#chkLicencia', function (event) {
	var chk = $('input:checkbox[id=chkLicencia]:checked').val();
	if (chk == 1) {
		$('#divLicencia').show();
		$('#txtSoftNroLicencia').val("");
	} else {
		$('#divLicencia').hide();
		$('#txtSoftNroLicencia').val("");
	}
});

$(document).on('click', '.removerM', function (event) {
	event.preventDefault();
	$(this).parent().parent().remove();
});

$(document).on('click', '.removerMo', function (event) {
	event.preventDefault();
	$(this).parent().parent().remove();
});


var verificar = function (txtIdDisMaMo, dispositivo, itemMaMo, txtSerie, chk) {
	var campo1;
	$("#tabla_marca tbody tr").each(function (index) {
		$(this).children("td").each(function (index2) {
			switch (index2) {
				case 1:
					campo1 = $(this).text();
					break;
			}
		})
	});
	if (campo1 === txtIdDisMaMo) {
		bootbox.alert('<b style="color: #F44336;" >El dispositivo ya ha sido ingresado</b>');
	} else {
		$("#tabla_marca > tbody").append(
				'<tr data-id=' + txtIdDisMaMo + ' ><td>#</td><td hidden>' + txtIdDisMaMo + '</td><td>' + dispositivo + '</td><td>' + itemMaMo + '</td><td>' + txtSerie + '</td><td>SI</td><td><button type="button" class="btn btn-danger btn-xs removerM"><i class="fa fa-fw fa-close"></i></button></td></tr>');
	}
	;
}



var insertarSoft = function (txtSoft, txtId, tipo, chk, edicion, version, nrolicencia) {
	var campo1;
	$("#tabla_soft tbody tr").each(function (index) {
		$(this).children("td").each(function (index2) {
			switch (index2) {
				case 1:
					campo1 = $(this).text();
					break;
			}
		})
	});
	if (campo1 === txtId) {
		bootbox.alert('<b style="color: #F44336;" >EL software ya ha sido ingresado</b>');
	} else {
		var tip = ""
		if (tipo == "SC")
		{
			tip = "Software comercial";
		} else if (tipo == "SM") {
			tip = "Software a medida";
		} else if (tipo == "SL") {
			tip = "Software libre";
		}
		$("#tabla_soft > tbody").append(
				'<tr data-id=' + txtId + ' ><td>#</td><td hidden>' + txtId + '</td><td hidden>' + tipo +
				'</td><td>' + tip + '</td><td>' + txtSoft + '</td><td>' + edicion + '</td><td>' + version +
				'</td><td>' + chk + '</td><td>' + nrolicencia + '</td><td><button type="button" class="btn btn-danger btn-xs removerM"><i class="fa fa-fw fa-close"></i></button></td></tr>');
	}
	;
}


function test()
{
	var tblMarca = {'idMicroprocesador': $("#txtIdMI").val(),
		'descripcion': $("#txtMI").val(),
		'estructura': $('input:radio[id=rbBits]:checked').val(),
	};


	alert(JSON.stringify(tblMarca, null, 4));
}

//function test()
//{
//		var tblMarca = $('#tabla_marca tbody tr').map(function (i, row) {
//		return {'id': row.cells[1].textContent,
//			'descripcion': row.cells[2].textContent,
//			'idModelo': row.cells[3].textContent,
//			'modelo': row.cells[4].textContent,
//		};
//	}).get();
//	
//	//alert(JSON.stringify(tblMarca, null, 4));
//}


$(document).on('click', '#btnguardar', function (event) {
	alert("aqui");
	//this.disabled = true;
	event.preventDefault();
	var txtNroFicha = $('#txtNroFicha').val();
	var txtFecha = $('#txtFecha').val();
	var txtNomPc = $('#txtNomPc').val();
	var txtFechaInstalacion = $('#txtFechaInstalacion').val();
	var txtObservacion = $('#txtObservacion').val();
	var txtIdEquipo = $('#txtIdEquipo').val();
	var ficha = $('#ficha').val();
	var unidad_org=$("#txtIdUnidadOrganica").val()>0?$("#txtIdUnidadOrganica").val():$("#txtIdNroUniOrg").val();

	//  var txtAnioNroFicha = $('#txtAnioNroFicha').val();
	//var txtFechaAdquisicion = $('#txtFechaAdquisicion').val();
//	var txtAnioGarantia = $('#txtAnioGarantia').val();
	//var txtSeriePC = $('#txtSeriePC').val();
	//var txtNroPatrimonio = $('#txtNroPatrimonio').val();
	//var txtIdSO = $('#txtIdSO').val();
	//var txtLicenciaSO = $('#txtLicenciaSO').val();
	var tblDatosEsp = {
		'chkCompatible': $('input:checkbox[id=chkCompatible]:checked').val() == 1 ? true : false,
		'marca': $('#txtIdMPc').val() > 0 ? $('#txtIdMPc').val() : "null",
		'seriePC': $('#txtSeriePC').val(),
		'chkOpOtros': $('input:checkbox[id=chkOpOtros]:checked').val() == 1 ? true : false,
		'chkGarantia': $('input:checkbox[id=chkGarantia]:checked').val() == 1 ? true : false,
		'fechaAdquisicion': $('#txtFechaAdquisicion').val(),
		'anioGarantia': $('#txtAnioGarantia').val() > 0 ? $('#txtAnioGarantia').val() : "null",
		'nroPatrimonio': $('#txtNroPatrimonio').val().trim().length > 0 ? $('#txtNroPatrimonio').val() : "null",

	};

	var tblPersonal = ($("#txtIdRespFuncionario").val().length > 0) ? {
		'unidadOrganica': $("#txtIdUnidadOrganica").val(),
		'areaServ': $("#txtIdAreaServ").val(),
		'resPatrimonio': $("#txtIdRespPatrimonio").val(),
		'resFuncionario': $("#txtIdRespFuncionario").val()
	} : null;


	var tblRed = ($("#txtIPAdd").val().length> 0) ? {
		'id': $("#txtIdRed").val(),
		'descripcion': $("#txtRed").val(),
		'serie': $("#txtSerieRed").val().length > 0 ? $("#txtSerieRed").val() : null,
		'mac': $("#txtMac").val().length > 0 ? $("#txtMac").val() : null,
		'ip': $("#txtIPAdd").val().length > 0 ? $("#txtIPAdd").val() : null,
		'puertaenlace': $("#txtPuertaEnlance").val(),
		'puertos': 0,
		'proxy': $("#txtProxy").val(),
		'integrada': $('input:checkbox[id=chkRedIntegrada]:checked').val()==1?'true':false,
		'red': $('input:checkbox[id=chkConRed]:checked').val()==1?'true':false,
		'internet': $('input:checkbox[id=chkConInternet]:checked').val()==1?'true':false,
	} : null;

	var tblMicroprocesador = ($("#txtIdMI").val() > 0) ? {'idMicroprocesador': $("#txtIdMI").val(),
		'descripcion': $("#txtMI").val(),
		'estructura': $('input:radio[id=rbBits]:checked').val(),
	} : null;
	var tblDiscoDuro = ($("#txtIdDD").val() > 0) ? {'idDiscoDuro': $("#txtIdDD").val(),
		'descripcion': $("#txtDD").val(),
		'serie': $("#txtSerieDD").val().length > 0 ? $("#txtSerieDD").val() : null,
	} : null;
	var tblMainboard = ($("#txtIdMain").val() > 0) ? {'idMainboard': $("#txtIdMain").val(),
		'descripcion': $("#txtMain").val(),
		'serie': $("#txtSerieMain").val().length > 0 ? $("#txtSerieMain").val() : null,
	} : null;

	var tblOtrosComponentes = $('#tabla_marca tbody tr').map(function (i, row) {
		return {'id': row.cells[1].textContent,
			'descripcion': row.cells[2].textContent,
			'modelo': row.cells[3].textContent,
			'serie': row.cells[4].textContent.length > 0 ? row.cells[4].textContent : null,
		};
	}).get();

	var tblRam = $('#tabla_ram tbody tr').map(function (i, row) {
		return {'id': row.cells[1].textContent
		};
	}).get();

	var tblSoftware = $('#tabla_soft tbody tr').map(function (i, row) {
		return {'id': row.cells[1].textContent,
			'tipo': row.cells[2].textContent,
			'descripcion': row.cells[4].textContent,
			'edicion': row.cells[5].textContent,
			'version': row.cells[6].textContent,
			'licenciado': row.cells[7].textContent == "Si" ? true : false,
			'nrolicencia': row.cells[8].textContent.length > 0 ? row.cells[8].textContent : null,
		};
	}).get();

	var tblUser = $('#tabla_user tbody tr').map(function (i, row) {
		return {
			'user': row.cells[1].textContent,
			'tipo': row.cells[2].textContent,
			'pass': row.cells[3].textContent,
		};
	}).get();

	var tblFichaDisp = $('#tabla_ficha_disp tbody tr').map(function (i, row) {
		return {
			'idDispositivo': $("#txtIdEquipo").val(),
			'idDispMarcaModelo': row.cells[1].textContent,
			'serie': row.cells[3].textContent.length > 0 ? row.cells[3].textContent : null,
			'codInventario': row.cells[4].textContent,
			'imei': row.cells[5].textContent.length > 0 ? row.cells[5].textContent : '',
			'fadquisicion': row.cells[6].textContent.length > 0 ? row.cells[6].textContent : null,
			'frenovacion': row.cells[7].textContent.length > 0 ? row.cells[7].textContent : null,
			'operativo': row.cells[8].textContent == "SI" ? 'true': 'false',
		};
	}).get();

	var tblFichaAd = $('#tabla_adquisicion tbody tr').map(function (i, row) {
		return {
			'id_doc_adquisicion': row.cells[1].textContent,
			'nro_doc': row.cells[3].textContent,
			'fecha_doc': row.cells[4].textContent,

		};
	}).get();

	var tblArchivo = ($("#uploadedfile").val().length > 0) ? {

		'ruta': $("#uploadedfile").val(),
	} : null;


	registrar(ficha, txtNroFicha, txtFecha,
			txtIdEquipo, txtNomPc,
			tblOtrosComponentes, tblRam, tblSoftware, tblMicroprocesador, tblDiscoDuro, tblMainboard, tblRed,
			tblUser, tblFichaDisp, tblFichaAd, tblArchivo, tblPersonal, tblDatosEsp, txtFechaInstalacion, txtObservacion,unidad_org);
});


var registrar = function (ficha, txtNroFicha, txtFecha,
		txtIdEquipo, txtNomPc,
		tblOtrosComponentes, tblRam, tblSoftware, tblMicroprocesador, tblDiscoDuro, tblMainboard, tblRed, tblUser,
		tblFichaDisp, tblFichaAd, tblArchivo, tblPersonal, tblDatosEsp, txtFechaInstalacion, txtObservacion,unidad_org) {
	//alert($("#uploadedfile").val());
	//alert(JSON.stringify(tblArchivo, null, 4));
	var options = {
		type: 'POST',
		url: '../../registrar',
		data: {'txtNroFicha': txtNroFicha,
			'ficha': ficha,
			'txtFecha': txtFecha,
			'txtIdEquipo': txtIdEquipo,
			'txtNomPc': txtNomPc,
			'tblOtrosComponentes': tblOtrosComponentes,
			'tblRam': tblRam,
			'tblSoftware': tblSoftware,
			'tblMicroprocesador': tblMicroprocesador,
			'tblDiscoDuro': tblDiscoDuro,
			'tblMainboard': tblMainboard,
			'tblRed': tblRed,
			'tblUser': tblUser,
			'tblFichaDisp': tblFichaDisp,
			'tblFichaAd': tblFichaAd,
			'tblArchivo': tblArchivo,
			'tblPersonal': tblPersonal,
			'tblDatosEsp': tblDatosEsp,
			'fechaInstalacion': txtFechaInstalacion,
			'observacion': txtObservacion,
			'unidad_org': unidad_org,
		},
		dataType: 'json',
		success: function (response) {
			var elemento = response.msj.split(":");
			if (elemento.length > 0) {
				if (elemento[0] == "Error") {
					bootbox.alert(elemento[0] + "" + elemento[1]);
				} else {
					bootbox.alert(response.msj, function () {
						window.location.href = "../../ficha";
					});
				}
			}
		}
	};
	$.ajax(options);
};