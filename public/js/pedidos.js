var modalbuscador = function(){

	var options = {
		url:'modalbuscador',
		dataType: 'html',
		success: function(response){
			$('#divmodal_buscador').html(response);
			$('#modal_buscador').modal('show');
		}
	};
	$.ajax(options);
};

$(document).on('change', '#indicar_equipo', function(event) {
	event.preventDefault();
	/* Act on the event */
	var indicar = $(this).val();

	if (indicar == 'si') {
		modalbuscador();
	}else{
		$('#info_equipo').hide('slow');
		$('#id_equipo').val('');
	}

});


var buscarunidad = function(unidad){

	var options = {
		type: 'POST',
		url:'buscarunidad',
		data: {
			'unidad' : unidad
		},
		dataType: 'html',
		success: function(response){
			// $('#divcamas').removeAttr('disabled');
			$('#listadounidad').html(response);
			// $('#divcamas').fadeIn("fast");
			// $('#divinfocamas').fadeOut("fast");

			// console.log(response);

		}
	};
	$.ajax(options);
};

$(document).on('click', '#btnbuscar', function(event) {
	event.preventDefault();
	// event.stopPropagation();
	/* Act on the event */

	var bunidad = $('#bunidad').val();

	buscarunidad(bunidad);

});

var cargarinfoequipo = function(id){

	var options = {
		type: 'POST',
		url:'cargarinfoequipo',
		data: {
			'id' : id
		},
		dataType: 'json',
		success: function(response){

			$('#span_equipo').html(response.equi_nombre);
			$('#span_modelo').html(response.equi_modelofabrica);
			$('#span_placa').html(response.equi_placa);
			$('#span_motor').html(response.equi_motor);
			$('#id_equipo').val(response.eqti_id);
			$('#modal_buscador').modal('hide');

			$('#info_equipo').show('slow');

			// $('#divbusqueda').hide('slow');
			// $('#divformato').html(response);
			// var tipo_item= $('#tipo_item').val();
			// bitem(tipo_item);
			// $('#cantidad').TouchSpin({ verticalbuttons: true, buttondown_class: 'btn btn-info', buttonup_class: 'btn btn-info', verticalupclass: 'glyphicon glyphicon-plus', verticaldownclass: 'glyphicon glyphicon-minus' });
			
		}
	};
	$.ajax(options);
};
$(document).on('click', '.seleccion', function(event) {
	event.preventDefault();
	/* Act on the event */
	var id = $(this).data('id');

	cargarinfoequipo(id);
});


var bitem = function (tipo_item) {
	
	$( "#bitem" ).autocomplete({

      source: 'buscaritem?tipo='+tipo_item+'',
      // source: 'index.php?page=triaje&accion=buscarciex&tb_ciex=cod',
      select : function(event, ui) {
        $('#id_item').val(ui.item.value);
		$(this).val(ui.item.label)
		
		return false;
      },
      autoFocus: false,
      open: function( event, ui ) {
		$( "#id_item" ).val('');

      },
      focus: function( event, ui ) {
		return false;     	
      }
    });
}

$(document).ready(function() {
	var tipo_item= $('#tipo_pedido').val();
	bitem(tipo_item);
});

$(document).on('change', '#tipo_pedido', function(event) {
	event.preventDefault();
	/* Act on the event */

	var tipo_item = $(this).val();

	bitem(tipo_item);
});

$(document).on('click', '#btnaniadir', function(event) {
	event.preventDefault();
	/* Act on the event */

	var id_item = $('#id_item').val();
	var bitem = $('#bitem').val();
	var cantidad = $('#cantidad').val();

	if (id_item == '') {
		bootbox.alert('<b style="color: #F44336;" >Debe seleccionar un item valido</b>');
	} else {
	$("#tabla_items > tbody").append('<tr data-id='+id_item+' ><td>#</td><td hidden>'+id_item+'</td><td>'+bitem+'</td><td>'+cantidad+'</td><td><button type="button" class="btn btn-danger btn-xs remover"><i class="fa fa-fw fa-close"></i></button></td></tr>');
	}


	
	$('#id_item').val('');
	$('#bitem').val('');
	$('#cantidad').val(1);

});


$(document).on('click', '.remover', function(event) {
	event.preventDefault();
	/* Act on the event */

	$(this).parent().parent().remove();

});



var registrarpedido = function(tipo_pedido, indicar_equipo, id_equipo, items_detalle, comentario){

	var options = {
		type: 'POST',
		url:'registrarpedido',
		data: {
			'tipo_pedido' : tipo_pedido, 
			'indicar_equipo' : indicar_equipo,
			'id_equipo' : id_equipo,
			'items_detalle' : items_detalle,
			'comentario' : comentario,
		},
		dataType: 'json',
		success: function(response){

			bootbox.alert(response.msj, function () {
				window.location.href = "listadopedidos";
			});
			
		}
	};
	$.ajax(options);
};


$(document).on('click', '#btnguardar', function(event) {
	event.preventDefault();
	/* Act on the event */

	var tipo_pedido = $('#tipo_pedido').val();
	// var indicar_equipo = $('#indicar_equipo :checked').val();
	var indicar_equipo = $('input:radio[id=indicar_equipo]:checked').val();
	var id_equipo = $('#id_equipo').val();

		var items_detalle = $('#tabla_items tbody tr').map(function(i, row) {
			return {
					'id' : row.cells[1].textContent,
					'descripcion' : row.cells[2].textContent,
					'cantidad' : row.cells[3].textContent
					};
			}).get();

	var comentario = $('#comentario').val();

	registrarpedido(tipo_pedido, indicar_equipo, id_equipo, items_detalle, comentario);
	// console.log(indicar_equipo);

});
