var registrarejecutora = function (txtDescripcion,txtNumero,txtId) {
	var options = {
		type: 'POST',
		url: '../../registrar',
		data: {'txtDescripcion': txtDescripcion,
			'txtNumero':txtNumero,
			'txtId': txtId,
			
		},
		dataType: 'json',
		success: function (response) {
			var elemento = response.msj.split(":");
				if (elemento.length > 0) {
					bootbox.alert(response.msj, function () {
						window.location.href = "../../ejecutora";
					});
				}
		}
	};
	$.ajax(options);
};

$(document).on('click', '#btnguardar', function (event) {
	this.disabled = true;
	event.preventDefault();
	var txtDescripcion = $('#txtDescripcion').val();
	var txtNumero = $('#txtNumero').val(); 
	var txtId = $('#txtId').val(); 
	registrarejecutora(txtDescripcion, txtNumero,txtId);
	//this.disabled=false;

});


function validar() {
	    $(".error").remove();
	        if ($(".descripcion").val() == "") {
		            $(".descripcion").focus().after("<span class='error'>Ingrese la descripcion</span>");
		            return false;
	        } else if ($(".numero").val() == "") {
		            $(".numero").focus().after("<span class='error'>Ingrese un numero</span>");
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
				window.location.href = "ejecutora";
			}) :
			bootbox.alert(response.msj);
		}
	};
	$.ajax(options);

}
