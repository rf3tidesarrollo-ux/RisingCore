$(document).ready(function() {
    $('.prueba').select2({
        minimumResultsForSearch: Infinity // Desactiva la búsqueda
      //minimumResultsForSearch: 0 //Reactiva la busqueda
    });

    $('.prueba2').select2({
        minimumResultsForSearch: 0 //Reactiva la busqueda
    });
});

$("select").on("select2:open", function(event) {
    $('input.select2-search__field').attr('placeholder', 'Buscar...');
});


