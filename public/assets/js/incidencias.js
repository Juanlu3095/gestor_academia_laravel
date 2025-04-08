function eliminarModal(data) // El parámetro debe estar en el botón eliminar 
    {
        let id = data.getAttribute('data-eliminarid');
        let titulo = data.getAttribute('data-eliminartitulo');
        var form = $("#eliminar-form"); // Identificamos el formulario por su id. Podemos usar form.prop('action')
        var datos = form.serialize();  // Serializamos sus datos: method y _token, éste último nos lo pide para el delete

        document.getElementById('nombreincidencia').innerHTML = titulo;
        confirmar = $("#confirmarEliminar");

        // Se ejecuta cuando hacemos click para confirmar el delete. off('click') elimina todos los eventos de click
        confirmar.off('click').on('click', function() {
            $.ajax({
                type: 'DELETE',
                url: '/incidencias/' + id,
                data: datos,

                success: function(response) {
                    alert('Incidencia eliminada.')
                    $('#eliminarModal').modal('hide')
                        
                    // Se recargan correctamente los datos de la tabla al eliminar un registro
                    $( "#tabla" ).load( "/incidencias #tabla" );

                },
                error: function (error) {
                    alert(`${error.responseJSON.message} Código del error: ${error.status}.`)
                }

            })
        })

    }