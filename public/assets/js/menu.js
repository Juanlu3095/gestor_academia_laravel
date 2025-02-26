$(document).ready( function () {

    let url = window.location.href; // Contiene la url de la página actual

    // Comprobamos la url actual con los href de los elementos con clase 'menu-item' de <nav>
    $('.menu-item').each(function () {
        
        // Si la url de la página coincide con el href del elemento con clase 'menu-item', el cual es representado por 'this' por
        // el each, se añade la clase 'active' que le da color al elemento <a>.
        // Destacar que se utiliza '|| $(this).attr('href') + '/' == url' ya que la url de la página de inicio se representa por:
        // http://localhost:8000/ y el href correspondiente no tiene el '/' del final.
        if($(this).attr('href') == url || $(this).attr('href') + '/' == url) {
            $(this).addClass('active')
        }
    })

    // Se pretendía utilizar esta función cuando se hiciese un 'click', pero al hacerlo se recarga la página y se pierden los cambios
    // que hace Jquery al hacer el click.

})