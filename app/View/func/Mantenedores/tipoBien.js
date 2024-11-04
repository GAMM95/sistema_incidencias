$(document).ready(function () {
  var lastValidResult = ''; // Variable para almacenar el último resultado válido

  // Función para buscar el tipo de bien en el servidor
  function buscarTipoBien(codigo) {
    // Limitar el código a los primeros 12 dígitos y obtener los primeros 8 dígitos para búsqueda
    var codigoLimite = codigo.substring(0, 12);
    var codigoBusqueda = codigoLimite.substring(0, 8);

    if (codigoBusqueda.length === 8) {
      $.ajax({
        url: 'ajax/getTipoBien.php',
        type: 'GET',
        data: { codigo_patrimonial: codigoBusqueda },
        success: function (response) {
          if (response.tipo_bien) {
            lastValidResult = response.tipo_bien; // Guardar el resultado válido
            $('#tipoBien').val(lastValidResult);
          } else {
            $('#tipoBien').val('No encontrado');
          }
        },
        error: function () {
          $('#tipoBien').val('Error al buscar');
        }
      });
    } else if (codigo.length === 0) {
      // Si el código está vacío, borrar el valor de tipoBien
      $('#tipoBien').val('');
      lastValidResult = ''; // Limpiar el último resultado válido
    } else {
      // No cambiar el valor si el código es menor de 8 dígitos, solo mantener el último resultado válido
      $('#tipoBien').val(lastValidResult);
    }
  }

  // Llamar a la función cuando el campo de código patrimonial cambia
  $('#codigoPatrimonial').on('input', function () {
    var codigo = $(this).val().replace(/[^0-9]/g, ''); // Filtrar solo dígitos
    buscarTipoBien(codigo);
  });
});
