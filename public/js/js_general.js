$(document).on('click', '.btnBorrar', function (event) {
	event.preventDefault();
	var cod = "";
	$(this).parents("tr").find("#idCod").each(function () {
		cod += $(this).html();
	});
	bootbox.confirm({
		title: "¿Desea Eliminar, el registro?",
		message: "Al eliminar el registro, solo se desactivara para su uso",
		buttons: {
			cancel: {
				label: '<i class="fa fa-times"></i> Cancel'
			},
			confirm: {
				label: '<i class="fa fa-check"></i> Confirm'
			}
		},
		callback: function (result) {
			if (result == true) {
				eliminar(cod, false);
			} else {
				console.log('No se ha eliminado.');
			}
		}
	});
});

$(document).on('click', '.btnActivar', function (event) {
event.preventDefault();
		var cod = "";
		$(this).parents("tr").find("#idCod").each(function(){ cod += $(this).html(); });
		bootbox.confirm({
		title: "¿Desea Activar, el registro?",
				message: "Al Activar el registro, se mostrara en las demas opciones.",
				buttons: {
				cancel: {
				label: '<i class="fa fa-times"></i> Cancel'
				},
						confirm: {
						label: '<i class="fa fa-check"></i> Confirm'
						}
				},
				callback: function (result) {
				if (result == true){
				eliminar(cod, true);
				} else{
				console.log('No se ha Activado.');
				}
				}
		});
});