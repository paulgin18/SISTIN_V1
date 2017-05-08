var registrar = function (txtDescripcion, chkVigencia, txtId) {
	var options = {
		type: 'POST',
		url: '../../registrar',
		data: {'txtDescripcion': txtDescripcion,
			'chkVigencia': chkVigencia,
			'txtId': txtId,
		},
		dataType: 'json',
		success: function (response) {
			var elemento = response.msj.split(":");
			if (elemento.length > 0) {
				if (elemento[0] == "Error") {
					bootbox.alert(elemento[0] + "" + elemento[1]);
				} else {
					bootbox.alert(response.msj, function () {
						window.location.href = "../../marca";
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
	var txtDescripcion = $('#txtDescripcion').val();
	var chkVigencia = $('input:checkbox[name=chkVigencia]:checked').val();
	var txtId = $('#txtId').val();
	alert(chkVigencia);
	if(chkVigencia==1){
		chkVigencia=true;
	}else{
		chkVigencia=false;
	}
	registrar(txtDescripcion, chkVigencia, txtId);
	//this.disabled=false;

});