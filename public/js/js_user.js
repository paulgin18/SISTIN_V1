var registraruser = function (txtUsuario,txtPassword,txtIdPersonal,txtIdRol,txtIdEjecutora,txtIdUser) {
	var options = {
		type: 'POST',
		url: '../../registraruser',
		data: {'txtUsuario': txtUsuario,
			'txtPassword': txtPassword,
			'txtIdPersonal': txtIdPersonal,
			'txtIdRol': txtIdRol,
			'txtIdEjecutora': txtIdEjecutora,
			'txtIdUser': txtIdUser,
			
		},
		dataType: 'json',
		success: function (response) {
			alert(txtPassword);
			(response.error == 0) ?
					bootbox.alert(response.msj, function () {
						window.location.href = "../../user";
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
		var txtUsuario = $('#txtUsuario').val();
		var txtPassword = $('#txtPassword').val();
		var txtIdPersonal = $('#id_personal').val();
		var txtIdRol = $('#id_rol').val();
		var txtIdEjecutora = $('#id_unidad_ejecutora').val();
		var txtIdUser = $('#txtIdUser').val();
			
	

		registraruser(txtUsuario, txtPassword,txtIdPersonal,txtIdRol,txtIdEjecutora,txtIdUser);
		this.disabled = false;
	}
});

$("#txtPersonal").focus(function (){
	$("#txtPersonal").autocomplete({
		source: '../../../../personal/personal/buscarpersonal',
		select: function (event, ui) {
			$('#id_personal').val(ui.item.value);
			$(this).val(ui.item.label)
			return false;
		},
		autoFocus: false,
		open: function (event, ui) {
			$("#id_personal").val('');
		},
		focus: function (event, ui) {
			return false;
		}
	});
});

$("#txtRol").focus(function (){
	$("#txtRol").autocomplete({
		source: '../../../../rol/rol/buscarrol',
		select: function (event, ui) {
			$('#id_rol').val(ui.item.value);
			$(this).val(ui.item.label)
			return false;
		},
		autoFocus: false,
		open: function (event, ui) {
			$("#id_rol").val('');
		},
		focus: function (event, ui) {
			return false;
		}
	});
});

$("#txtEjecutora").focus(function (){
	$("#txtEjecutora").autocomplete({
		source: '../../../../ejecutora/ejecutora/buscarejecutora',
		select: function (event, ui) {
			$('#id_unidad_ejecutora').val(ui.item.value);
			$(this).val(ui.item.label)
			return false;
		},
		autoFocus: false,
		open: function (event, ui) {
			$("#id_unidad_ejecutora").val('');
		},
		focus: function (event, ui) {
			return false;
		}
	});
});







$(document).ready(function () {
	$('input').focusout(function () {
		this.value = this.value.toLocaleUpperCase();
	});
	//$('#txtUsuario').valcn(' abcdefghijklmnñopqrstuvwxyzáéiou');
	
	$(".user, .usuario").keyup(function () {
		if ($(this).val() != "") {$(".error").fadeOut();return false;}
	});
  
});

function validar() {
	    $(".error").remove();
	        if ($(".usuario").val() == "") {
		            $(".usuario").focus().after("<span class='error'>Ingrese el usuario</span>");
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
				window.location.href = "user";
			}) :
			bootbox.alert(response.msj);
		}
	};
	$.ajax(options);

}
