$(document).data('tipoDisp', 2);

var bDispositivo = function (tipoDisp, id, inp) {
		
	$(inp).autocomplete({
		source: '../../../../dispositivo/dispositivo/buscardispositivo?tipo=' + tipoDisp + '',
		select: function (event, ui) {
			$(id).val(ui.item.value);
			$(this).val(ui.item.label)
			if (inp == "#txtEquipo") {
				especificos(ui.item.value);
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
	var txtId = $('#txtIdDis').val();
	var itemMaMo = $('#txtMaMo').val();
	var txtSerie = $('#txtSerieMaMo').val();
	var chk = $('input:checkbox[id=chkOpOtros]:checked').val();
	if (chk == 1) {
		chk = 'Si';
	} else {
		chk = 'No';
	}
	if (dispositivo == '' && txtIdDisMaMo == '') {
		bootbox.alert('<b style="color: #F44336;" >Debe seleccionar una Dispositivo , Marca y un Modelo</b>');
	} else {
		if (dispositivo == '') {
			bootbox.alert('<b style="color: #F44336;" >Debe seleccionar una Dispositivo</b>');
		} else if (txtIdDisMaMo == '') {
			bootbox.alert('<b style="color: #F44336;" >Debe seleccionar una Marca y modelo</b>');
		} else {
			if (txtId > 0 && txtIdDisMaMo >= 0) {
				verificar(txtIdDisMaMo, dispositivo, itemMaMo, txtSerie, chk);
				//test();
			}
		}
	}
	
	$('#txtIdDis').val('');
	$('#txtDispositivo').val('');
	$('#txtMaMo').val('');
	$('#txtSerieMaMo').val('');
	$('#txtIdDisMaMo').val('');
});

$(document).on('click', '#btnAnadirRam', function (event) {
	event.preventDefault();
	var idRam = $('#txtIdMR').val();
	var dispositivo = $('#txtMR').val();
	if (dispositivo == '' && idRam == '') {
		bootbox.alert('<b style="color: #F44336;" >Debe seleccionar la Memoria Ram</b>');
	} else {
		insertarRam(idRam, dispositivo);
	}
	$('#txtMR').val('');
	$('#txtIdMR').val('');
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
		insertarSoft($('#txtSoftCL').val(), $('#txtIdSofCl').val(), $('#tipoSoft').val(), chk, $("#txtSoftEdicion").val(), $("#txtSoftVersion").val(),$("#txtSoftNroLicencia").val());
	}
		$('#txtSoftCL').val('');
		$('#txtSoftEdicion').val('');
		$('#txtIdSofCl').val('');
		$('#tipoSoft').val('');
		$('#txtSoftVersion').val('');
		$('#txtSoftNroLicencia').val('');
	
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
		$("#pcCompatible").show();
		$("#txtMarcaPC").val("");
		$("#txtIdMPc").val("");
		$("#txtSeriePC").val("");
		$(document).data('tipoDisp', 2);
	} else if (dato == '1') {
		$('#chkCompatible').prop('checked', false);
		$("#datosPC").show();
		$("#pcCompatible").hide();
		$("#txtIdMPc").val("");
		$("#txtMarcaPC").val("");
		$("#txtSeriePC").val("");
		$(document).data('tipoDisp', 1);
	}
}

$("#txtMI").focus(function () {
	bCI(3, '#txtIdMI', "#txtMI");
});


$("#txtSeriePC").keyup(function () {
	$(this).val() != '' && $(this).val().length > 0 ?
			bSerie($(this).val(), '#lblASeriePc', "#lblNSeriePc") : '';
});

$("#txtLicenciaSO").keyup(function () {
	bSerie($(this).val(), '#lblALicenciaSO', "#lblNLicenciaSO");
});

$("#txtSerieDD").keyup(function () {
	bSerie($(this).val(), '#lblASerieDD', "#lblNSerieDD");
});

$("#txtSerieMain").keyup(function () {
	bSerie($(this).val(), '#lblASerieMain', "#lblNSerieMain");
});
$("#txtSerieRed").keyup(function () {
	bSerie($(this).val(), '#lblASerieRed', "#lblNSerieRed");
});
$("#txtSerieMaMo").keyup(function () {
	bSerie($(this).val(), '#lblASerieMaMo', "#lblNSerieMaMo");
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
		bootbox.alert('<b style="color: #F44336;" >Debe seleccionar un Model</b>');
	} else {
		$("#tabla_modelo > tbody").append('<tr data-id=' + id_modelo + ' ><td>#</td><td hidden>' + id_modelo + '</td><td>' + bitem + '</td><td><button type="button" class="btn btn-danger btn-xs removerMo"><i class="fa fa-fw fa-close"></i></button></td></tr>');
	}
	$('#id_modelo').val('');
	$('#bModelo').val('');
});

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
				'<tr data-id=' + txtIdDisMaMo + ' ><td>#</td><td hidden>' + txtIdDisMaMo + '</td><td>' + dispositivo + '</td><td>' + itemMaMo + '</td><td>' + txtSerie + '</td><td>' + chk + '</td><td><button type="button" class="btn btn-danger btn-xs removerM"><i class="fa fa-fw fa-close"></i></button></td></tr>');
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
		'</td><td>' + tip + '</td><td>' + txtSoft + '</td><td>' + edicion + '</td><td>' + version+ 
		'</td><td>' + chk+ '</td><td>' + nrolicencia + '</td><td><button type="button" class="btn btn-danger btn-xs removerM"><i class="fa fa-fw fa-close"></i></button></td></tr>');
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
	//this.disabled = true;
	event.preventDefault();
	var txtFecha = $('#txtFecha').val();
	var txtNroFicha = $('#txtNroFicha').val();
	var txtAnioNroFicha = $('#txtAnioNroFicha').val();
	var txtUnidadOrganica = $('#txtUnidadOrganica').val();
	var txtAreaServ = $('#txtAreaServ').val();
	var txtRespPatrimonio = $('#txtRespPatrimonio').val();
	var txtRespFuncionario = $('#txtRespFuncionario').val();
	var txtIdEquipo = $('#txtIdEquipo').val();
	var txtNomPc = $('#txtNomPc').val();
	var txtFechaAdquisicion = $('#txtFechaAdquisicion').val();
	var txtAnioGarantia = $('#txtAnioGarantia').val();
	var txtSeriePC = $('#txtSeriePC').val();
	var txtNroPatrimonio = $('#txtNroPatrimonio').val();
	var txtIdSO = $('#txtIdSO').val();
	var txtLicenciaSO = $('#txtLicenciaSO').val();
	var chkCompatible = $('input:checkbox[id=chkCompatible]:checked').val();
	var chkOpOtros = $('input:checkbox[id=chkOpOtros]:checked').val();
	var chkGarantia = $('input:checkbox[id=chkGarantia]:checked').val();
	var tblRed = ($("#txtIPAdd").val().length >0)?{'id': $("#txtIdRed").val(),
			'descripcion': $("#txtRed").val(),
			'serie': $("#txtSerieRed").val().length>0?$("#txtSerieRed"):null,
			'mac': $("#txtMac").val().length>0?$("#txtMac").val():null,
			'ip': $("#txtIPAdd").val().length>0?$("#txtIPAdd").val():null,
			'puertaEnlace': $("#txtPuertaEnly").val(),
			'puertos': 0,
			'proxy': $("#txtProxy").val(),
			'integrada':  $('input:checkbox[id=chkRedIntegrada]:checked').val(),
			'red':  $('input:checkbox[id=chkConRed]:checked').val(),
			'internet':  $('input:checkbox[id=chkConInternet]:checked').val(),
		}:null;
		
	var tblMicroprocesador =($("#txtIdMI").val()>0)? {'idMicroprocesador': $("#txtIdMI").val(),
			'descripcion': $("#txtMI").val(),
			'estructura': $('input:radio[id=rbBits]:checked').val(),
		}:null;
	var tblDiscoDuro = ($("#txtIdDD").val()>0)?{'idDiscoDuro': $("#txtIdDD").val(),
			'descripcion': $("#txtDD").val(),
			'serie': $("#txtSerieDD").val().length>0?$("#txtSerieDD").val():null,
		}:null;
	var tblMainboard = ($("#txtIdMain").val()>0)?{'idMainboard': $("#txtIdMain").val(),
			'descripcion': $("#txtMain").val(),
			'serie': $("#txtSerieMain").val().length>0?$("#txtSerieMain").val():null,
		}:null;
		
	var tblOtrosComponentes = $('#tabla_marca tbody tr').map(function (i, row) {
		return {'id': row.cells[1].textContent,
			'descripcion': row.cells[2].textContent,
			'modelo': row.cells[3].textContent,
			'serie': row.cells[4].textContent.length>0?row.cells[4].textContent:null,
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
			'licenciado': row.cells[7].textContent=="Si"?true:false,
			'nrolicencia': row.cells[8].textContent.length>0?row.cells[8].textContent:null,
		};
	}).get();
	
	registrar(txtNroFicha, txtFecha, txtAnioNroFicha, txtUnidadOrganica,txtAreaServ,txtRespPatrimonio,
	txtRespFuncionario,txtIdEquipo,txtNomPc, txtFechaAdquisicion,txtAnioGarantia,
	txtSeriePC,txtNroPatrimonio,txtIdSO,txtLicenciaSO,chkCompatible,chkOpOtros, chkGarantia,
	tblOtrosComponentes,tblRam,tblSoftware,tblMicroprocesador,tblDiscoDuro,tblMainboard,tblRed);
});


var registrar = function (txtNroFicha, txtFecha, txtAnioNroFicha, txtUnidadOrganica,txtAreaServ,txtRespPatrimonio,
	txtRespFuncionario,txtIdEquipo,txtNomPc, txtFechaAdquisicion,txtAnioGarantia,
	txtSeriePC,txtNroPatrimonio,txtIdSO,txtLicenciaSO,chkCompatible,chkOpOtros, chkGarantia,
	tblOtrosComponentes,tblRam,tblSoftware,tblMicroprocesador,tblDiscoDuro,tblMainboard,tblRed) {
	var options = {
		type: 'POST',
		url: '../../registrar',
		data: {'txtNroFicha':txtNroFicha,
			'txtFecha':txtFecha, 
			'txtAnioNroFicha':txtAnioNroFicha,
			'txtUnidadOrganica':txtUnidadOrganica,
			'txtAreaServ':txtAreaServ,
			'txtRespPatrimonio':txtRespPatrimonio,
			'txtRespFuncionario':txtRespFuncionario,
			'txtIdEquipo':txtIdEquipo,
			'txtNomPc':txtNomPc,
			'txtFechaAdquisicion':txtFechaAdquisicion,
			'txtAnioGarantia':txtAnioGarantia,
			'txtSeriePC':txtSeriePC,
			'txtNroPatrimonio':txtNroPatrimonio,
			'txtIdSO':txtIdSO,
			'txtLicenciaSO':txtLicenciaSO,
			'chkCompatible':chkCompatible,
			'chkOpOtros':chkOpOtros,
			'chkGarantia':chkGarantia,
			'tblOtrosComponentes':tblOtrosComponentes,
			'tblRam':tblRam,
			'tblSoftware':tblSoftware,
			'tblMicroprocesador':tblMicroprocesador,
			'tblDiscoDuro':tblDiscoDuro,
			'tblMainboard':tblMainboard,
			'tblRed':tblRed,
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