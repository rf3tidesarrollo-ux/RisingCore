$(document).ready(function() {
    $('.prueba').select2();
});

$("select").on("select2:open", function(event) {
    $('input.select2-search__field').attr('placeholder', 'Buscar...');
});
