// Permite obtener las personas del select cuando cambiamos el rol
$(document).on('change', '#rol', function(event) {
    let rol = $("#rol").val();
    let partialUrl = ''
    
    if(rol == 'Profesor') {
        partialUrl = 'profesores'
    } else if (rol == 'Alumno') {
        partialUrl = 'alumnos'
    }

    fetch(`/${partialUrl}/lista`, {
        method: 'GET'
    })
    .then(respuesta => {
        if (respuesta.ok) {
            return respuesta.json()
        }
        throw new Error("Error " + respuesta.status + " al llamar al backend: " + respuesta.statusText);
    })
    .then (respuesta => {
        let defaultOption = '<option selected disabled>Elija la persona afectada por la incidencia</option>'

        $('#persona').empty() // Vaciamos las option dentro del select con id persona
        $('#persona').append(defaultOption); // Añadimos la opción por defecto

        respuesta.forEach(persona => {
            let options = '<option value="' + persona.id + '">' + persona.nombre + ' ' +  persona.apellidos + '</option>'

        $('#persona').append(options); // Añadimos las personas (alumno o profesor) que nos vienen con el fetch
        })
    })
    .catch (error => {
        console.error(error)
    })
});