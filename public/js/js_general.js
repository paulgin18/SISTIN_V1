$(document).on('click', '.btnBorrar', function (event) {
    event.preventDefault();
	var cod="";
	$(this).parents("tr").find("#idCod").each(function(){ cod+=$(this).html();});
	eliminar(cod);
});