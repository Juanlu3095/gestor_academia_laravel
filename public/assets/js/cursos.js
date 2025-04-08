/**
* @param {string} id Contains course id
* It allows to set a cookie 'last_course' with course's id which was tried to update
*/
function guardarUltimoCursoConsultado(id) {
    let date = new Date(Date.now() + 86400e3); // Fecha de hoy + 1 día
    date = date.toUTCString();
    document.cookie = `last_course=${id}; expires=${date}; samesite=strict`;
}