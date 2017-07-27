$(function() {
	  $('#cmbUnidadOrganica').on('change', function(){
		var val = $(this).find("option:selected")[0].getAttribute('value');
		var val1 = $(this).find("option:selected")[0].getAttribute('value1');
		if(val && val1){
			$('#id_unidad_organica').val(val);
			$('#id_unidad_organica_conex').val(val1);
		}
	  });
});

var registrar = function (txtId_Area,txtDescripcion,id_uni_org,cnx) {
	
	var options = {
		type: 'POST',
		url: '../../registrar',
		data: {
			'txtId_Area':txtId_Area,
			'txtDescripcion': txtDescripcion,
			'id_uni_org':id_uni_org,
			'cnx':cnx,
			//'chkVigencia': chkVigencia,
		
		},
		dataType: 'json',
		success: function (response) {
			(response.error == 0) ?
					bootbox.alert(response.msj, function () {
						window.location.href = "../../area";
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

$("#txtUnidad").focus(function (){
	$("#txtUnidad").autocomplete({
		source: '../../../../unidad/unidad/buscarunidadCmb',
		select: function (event, ui) {
			$('#id_uni_org').val(ui.item.value);
			$(this).val(ui.item.label)
			return false;
		},
		autoFocus: false,
		open: function (event, ui) {
			$("#id_area").val('ui.item.value)');
		},
		focus: function (event, ui) {
			return false;
		}
	});
});



$(document).on('click', '#btnguardar', function (event) {
	this.disabled = true;
	event.preventDefault();
	var txtId = $('#txtId').val();
    var txtDescripcion = $('#txtDescripcion').val();
    var id_uni_org =$('#id_unidad_organica').val();
    var cnx =$('#id_unidad_organica_conex').val();

	registrar(txtId,txtDescripcion,id_uni_org,cnx);

});

function validar() {
	    $(".error").remove();
	        if ($(".descripcion").val() == "") {
		            $(".descripcion").focus().after("<span class='error'>Ingrese la descripcion</span>");
		            return false;
	        } else if ($(".anio").val() == "") {
		            $(".anio").focus().after("<span class='error'>Ingrese un año</span>");
		            return false;
	        } else {
		return true;
	}
}



function eliminar(id, vigencia){

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
						window.location.href = "area";
					}) :
					bootbox.alert(response.msj);
				$("#btnBorrar").prop('disabled', false);
		}
	};
	$.ajax(options);
		//alert('id eliminar area'+txtId);
	//alert('txt vigencia '+txtVigencia);

}

/*

var eliminar = function ($id,$vigencia){
	var options = {
		type: 'POST',
		url: 'eliminar',
		data: {'id_estado': $id,'vigencia':$vigencia,
		},
		dataType: 'json',
		success: function (response) {
		(response.error == 0) ?
			bootbox.alert(response.msj, function () {
				window.location.href = "Seastado";
			}) :
			bootbox.alert(response.msj);
		}
	};
	$.ajax(options);

*/
