// Al abrir modal de editar alumno
function verModal(data) {
    let id = data.getAttribute('data-editar');

    let propiedades = ['email', 'dni', 'nombre', 'apellidos']

    // Limpia los errores anteriores en los div debajo de los input de la ventana modal
    propiedades.forEach(propiedad => {
        $(`#error-${propiedad}`).empty(); // empty todo lo que haya dentro del div, contenido y tags
    });

    fetch(`/profesores/${id}`)
        .then(response => response.json())
            .then(datos => {
                $("#editar-id").val(datos[0].id);
                $("#editar-nombre").val(datos[0].nombre);
                $("#editar-apellidos").val(datos[0].apellidos);
                $("#editar-email").val(datos[0].email);
                $("#editar-dni").val(datos[0].dni);
            })
        .catch(error => {
            console.error(error)
        })
}

// Al confirmar la edición del alumno dándole al botón
function editarModal() {
    var form = $("#editar-form"); //Identificamos el formulario por su id. Podemos usar form.prop('action')
    var datos = form.serialize();  //Serializamos sus datos: method, _token y values de los input
    var datosArray = form.serializeArray(); // Devolvería los datos en array, _token y _method incluidos
    
    fetch('/profesores/' + $("#editar-id").val(), {
        method: 'PUT',
        headers: {
            'Content-type': 'application/json', // Lo que se envía desde JS
            'Accept': 'application/json', // Lo que se recibe como respuesta del fetch
            'X-CSRF-TOKEN': datosArray.filter(element => element.name == '_token')[0].value // necesitamos pasarle el token para POST, PUT y DELETE
        },
        body: JSON.stringify({
            // FILTER vs FIND para buscar elementos en un array
            // Filter devuelve un array (todos lo elementos que cumplen la condición => element.name == 'nombre')
            // Find devuelve un único objeto (el primero que cumple la condición)
            
            nombre: datosArray.filter(element => element.name == 'nombre')[0].value, 
            apellidos: datosArray.filter(element => element.name == 'apellidos')[0].value,
            email: datosArray.find(element => element.name == 'email').value,
            dni: datosArray.find(element => element.name == 'dni').value
        })
    })
    .then(async respuesta => {
        if (respuesta.ok) {
            return respuesta.text()
        }
        let errorData = await respuesta.json(); // Obtiene errores del Form Request de los messages si los hay
        let propiedades = ['email', 'dni', 'nombre', 'apellidos']

        // Limpia los errores anteriores
        propiedades.forEach(propiedad => {
            $(`#error-${propiedad}`).empty(); // empty todo lo que haya dentro del div, contenido y tags
        });

        // Ejecuta el bucle en el que pinta cada error obtenido en su correspondiente div
        for(let propiedad in errorData.errors) {            
            for(i=0; i < errorData.errors[propiedad].length; i++) {
                if(propiedades.includes(propiedad)) {
                    let error = '<p class="text-danger">' + errorData.errors[propiedad][i] + '</p>'
                    $(`#error-${propiedad}`).append(error)
                }
            }
        }
        throw new Error("Error " + respuesta.status + " al llamar al backend: " + respuesta.statusText);
    })
    .then(datos => {
        $( "#tabla" ).load( "/profesores #tabla" )
        alert(datos)
        $('#editarModal').modal('hide')
    })
    .catch(error => {
        console.error(error)
        alert(`${error.responseJSON.message} Código del error: ${error.status}`)
    })
}

// Al abrir modal para eliminar
function eliminarModal(data) // El parámetro debe estar en el botón eliminar 
{
    let id = data.getAttribute('data-eliminarid');
    let nombre = data.getAttribute('data-eliminarnombre');
    var form = $("#eliminar-form"); // Identificamos el formulario por su id. Podemos usar form.prop('action')
    var datos = form.serializeArray();  // Serializamos sus datos: method y _token, éste último nos lo pide para el delete

    document.getElementById('nombreprofesor').innerHTML = nombre;
    confirmar = $("#confirmarEliminar");

    // Se ejecuta cuando hacemos click para confirmar el delete. off() para eliminar eventos anteriores
    confirmar.off('click').on('click', function() {
        fetch(`/profesores/${id}`, {
            method: 'DELETE',
            headers: {
                // Otra forma de seleccionar el valor del token del form. Si no especificamos la id del formulario,
                // seleccionará el primer formulario que encuentre con el input indicado del html
                'X-CSRF-TOKEN': document.querySelector('#eliminar-form input[name=_token]').value
            }
        })
        .then(respuesta => {
            if (respuesta.ok) {
                return respuesta.json()
            }
            throw new Error("Error " + respuesta.status + " al llamar al backend: " + respuesta.statusText);
        })
        .then (respuesta => {
            alert('Profesor eliminado.')
            $('#eliminarModal').modal('hide')
                    
            // Se recargan correctamente los datos de la tabla al eliminar un registro
            $( "#tabla" ).load( "/profesores #tabla" );
            $(".paginacion").load("/profesores .paginacion")
        })
        .catch (error => {
            console.error(error)
        })
    })
}