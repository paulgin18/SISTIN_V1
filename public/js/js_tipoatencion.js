
var registrar = function (txtNumero,txtDescripcion, chkVigencia, txtId) {
	var options = {
		type: 'POST',
		url: '../../registrar',
		data: {'txtNumero':txtNumero,
			'txtDescripcion': txtDescripcion,
			'chkVigencia': chkVigencia,
			'txtId': txtId,
		},
		dataType: 'json',
		success: function (response) {
			var elemento = response.msj.split(":");
			if (elemento.length > 0) {
		
					bootbox.alert(response.msj, function () {
						window.location.href = "../../tipoatencion";
					});
				
			}
		}
	};
	$.ajax(options);
};

$(document).on('click', '#btnguardar', function (event) {
	this.disabled = true;
	event.preventDefault();
	var txtNumero =$('#txtNumero').val();
	var txtDescripcion = $('#txtDescripcion').val();
	var chkVigencia = $('input:checkbox[name=chkVigencia]:checked').val();
	var txtId = $('#txtId').val();
	
	if(chkVigencia==1){
		chkVigencia=true;
	}else{
		chkVigencia=false;
	}
	registrar(txtNumero,txtDescripcion, chkVigencia, txtId);
	//this.disabled=false;

});






