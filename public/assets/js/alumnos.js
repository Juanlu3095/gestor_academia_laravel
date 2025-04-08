// Al abrir modal de editar alumno
function verModal(data) {
    let id = data.getAttribute('data-editar');

    $.ajax({
        type: 'GET',
        url: '/alumnos/' + id,

        success: function (response) {
            
            $("#editar-id").val(response[0].id);
            $("#editar-nombre").val(response[0].nombre);
            $("#editar-apellidos").val(response[0].apellidos);
            $("#editar-email").val(response[0].email);
            $("#editar-dni").val(response[0].dni); 
            
            //$("#editar-form").attr('action', nuevaURL); // Solo crea /alumnos/6 MAL muy mal ChatGPT
        }
    })
}

// Al confirmar la edición del alumno
function editarModal() {
    var form =$("#editar-form"); //Identificamos el formulario por su id. Podemos usar form.prop('action')
    var datos = form.serialize();  //Serializamos sus datos: method, _token y values de los input
    // var datosArray = form.serializeArray(); // Devolvería los datos en array

    $.ajax({
        type: 'PUT',
        url: '/alumnos/' + $("#editar-id").val(), // con val() obtenemos el value del input
        data: datos,

        success: function (response) {
            if(response) {
                
                // Esto sólo edita el primer registro y modifica éste al intentar actualizar cualquier otro registro
                /* $("#table-nombre").text(response[0].nombre); // con text() obtenemos el valor del <td>
                $("#table-apellidos").text(response[0].apellidos);
                $("#table-email").text(response[0].email);
                $("#table-dni").text(response[0].dni);  */
                
                // Ésta es la forma correcta, se recarga sólo la tabla con los datos actualizados
                $( "#tabla" ).load( "/alumnos #tabla" );

                alert('Datos del alumno actualizados.')
                $('#editarModal').modal('hide')
            }
        },
        error: function (error) {
            alert(`${error.responseJSON.message} Código del error: ${error.status}`)
        }
    })
}

// Al abrir modal para eliminar
function eliminarModal(data) // El parámetro debe estar en el botón eliminar 
{
    let id = data.getAttribute('data-eliminarid');
    let nombre = data.getAttribute('data-eliminarnombre');
    var form = $("#eliminar-form"); // Identificamos el formulario por su id. Podemos usar form.prop('action')
    var datos = form.serialize();  // Serializamos sus datos: method y _token, éste último nos lo pide para el delete

    document.getElementById('nombrealumno').innerHTML = nombre;
    confirmar = $("#confirmarEliminar");

    // Se ejecuta cuando hacemos click para confirmar el delete
    confirmar.off('click').on('click', function() {
        $.ajax({
            type: 'DELETE',
            url: '/alumnos/' + id,
            data: datos,

            success: function(response) {
                alert('Alumno eliminado.')
                $('#eliminarModal').modal('hide')
                
                // Se recargan correctamente los datos de la tabla al eliminar un registro
                $( "#tabla" ).load( "/alumnos #tabla" );
                $(".paginacion").load("/profesores .paginacion")

            },
            error: function (error) {
                alert(`${error.responseJSON.message} Código del error: ${error.status}.`)
            }

        })
    })

}