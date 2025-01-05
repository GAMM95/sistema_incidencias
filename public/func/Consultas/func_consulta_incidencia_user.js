$(document).ready(function () {
  // Configuración de Toastr
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };

  // SETEO DE COMBO ESTADO
  $.ajax({
    url: 'ajax/getEstadoConsulta.php',
    type: 'POST',
    dataType: 'json',
    success: function (data) {
      var select = $('#estado');
      select.empty();
      select.append('<option value="" selected disabled>Seleccione un estado</option>');
      $.each(data, function (index, value) {
        select.append('<option value="' + value.EST_codigo + '">' + value.EST_descripcion + '</option>');
      });
    },
    error: function (error) {
      console.error(error);
    }
  });

  // BUSCADOR PARA EL COMBO AREA Y ESTADO
  $('#estado').select2({
    allowClear: true,
    width: '100%',
    dropdownCssClass: 'text-xs',
    language: {
      noResults: function () {
        return "No se encontraron resultados";
      }
    }
  });

  // Evento para nueva consulta
  function nuevaConsultaUsuario() {
    const form = document.getElementById('formConsultarIncidenciaUser');
    form.reset();
    window.location.reload();
  }


  // Evento para nueva consulta
  $('#limpiarCamposUserArea').on('click', nuevaConsultaUsuario);

  $('#formConsultarIncidenciaUser').submit(function (event) {
    event.preventDefault(); // Evita el envío del formulario por defecto

    // Verifica si los campos y las fechas son válidos
    if (!validarCampos() || !validarFechas()) {
      return; // Detiene el envío si los campos o las fechas no son válidos
    }

    var formData = $(this).serializeArray(); // Recopila los datos del formulario<
    var dataObject = {}; // Crea un objeto para los datos del formulario
    console.log(dataObject);
    // Recorre los datos del formulario
    formData.forEach(function (item) {
      // Solo agrega los parámetros al objeto si tienen valor
      if (item.value.trim() !== '') {
        dataObject[item.name] = item.value;
      }
    });

    // Realiza la solicitud AJAX
    $.ajax({
      url: 'consultar-incidencia-user.php?action=consultar_usuario',
      type: 'GET',
      data: dataObject,
      success: function (response) {
        console.log("Resultados filtrados:", response); // Depuración
        // Limpia el contenido actual de la tabla antes de agregar nuevos datos
        $('#tablaIncidenciasUser tbody').empty();
        // Actualiza el contenido de la tabla con la respuesta
        $('#tablaIncidenciasUser tbody').html(response);
      },
      error: function (xhr, status, error) {
        console.error('Error en la consulta AJAX:', error);
      }
    });

    function validarCampos() {
      var valido = false;
      var mensajeError = '';

      var codigoPatrimonial = ($('#codigoPatrimonial').val() !== null && $('#codigoPatrimonial').val().trim() !== '');
      var estadoSeleccionado = ($('#estado').val() !== null && $('#estado').val().trim() !== '');
      var fechaInicioSeleccionada = ($('#fechaInicio').val() !== null && $('#fechaInicio').val().trim() !== '');
      var fechaFinSeleccionada = ($('#fechaFin').val() !== null && $('#fechaFin').val().trim() !== '');

      // Verificar si al menos un campo está lleno
      if (codigoPatrimonial || estadoSeleccionado || fechaInicioSeleccionada || fechaFinSeleccionada) {
        valido = true;
      } else {
        mensajeError = 'Debe completar al menos un campo para realizar la búsqueda.';
      }

      if (!valido) {
        toastr.warning(mensajeError.trim(), 'Advertencia');
      }

      return valido;
    }
  });

  function validarFechas() {
    // Obtener valores de los campos de fecha
    const fechaInicio = new Date($('#fechaInicio').val());
    const fechaFin = new Date($('#fechaFin').val());

    // Obtener la fecha actual
    const fechaHoy = new Date();

    // Validar la fecha de inicio y fin
    let valido = true;
    let mensajeError = '';

    // Bloquear fechas posteriores a la fecha actual
    if (fechaInicio > fechaHoy) {
      mensajeError = 'La fecha de inicio no puede ser posterior a la fecha actual.';
      valido = false;
    }

    if (fechaFin > fechaHoy) {
      mensajeError = 'La fecha de fin no puede ser posterior a la fecha actual.';
      valido = false;
    }

    // Verificar que la fecha de fin sea posterior a la fecha de inicio
    if (fechaInicio && fechaFin && fechaFin < fechaInicio) {
      mensajeError = 'La fecha de fin debe ser posterior a la fecha de inicio.';
      valido = false;
    }

    // Mostrar mensaje de error con Toastr si la validación falla
    if (!valido) {
      toastr.warning(mensajeError.trim(), 'Advertencia');
    }

    return valido;
  }

  // Agregar eventos para validar fechas cuando cambien
  $('#fechaInicio, #fechaFin').on('change', function () {
    validarFechas();
  });
});

// Funcion para buscar el tipo de bien en el servidor
$(document).ready(function () {
  var lastValidResult = ''; // Almacena el último resultado válido

  // Función para buscar el tipo de bien en el servidor
  function buscarTipoBien(codigo) {
    // Limitar el código a los primeros 12 dígitos y obtener los primeros 8 dígitos para la búsqueda
    var codigoLimite = codigo.substring(0, 12); // Solo considerar los primeros 12 dígitos
    var codigoBusqueda = codigoLimite.substring(0, 8); // Extraer los primeros 8 dígitos

    if (codigoBusqueda.length === 8) {
      $.ajax({
        url: 'ajax/getTipoBien.php', // Ruta del archivo PHP que obtiene el tipo de bien
        type: 'GET',
        data: { codigoPatrimonial: codigoBusqueda }, // Enviar el código para buscar
        success: function (response) {
          // Verificar si el tipo de bien fue encontrado en la respuesta
          if (response.tipo_bien) {
            lastValidResult = response.tipo_bien; // Guardar el resultado válido
            $('#tipoBien').val(lastValidResult);  // Mostrar el tipo de bien en el campo readonly
          } else {
            $('#tipoBien').val('No encontrado'); // Mostrar mensaje si no se encuentra el tipo
          }
        },
        error: function () {
          $('#tipoBien').val('Error al buscar'); // Mostrar mensaje de error en caso de fallo
        }
      });
    } else if (codigo.length === 0) {
      // Si el código está vacío, limpiar el valor de tipoBien
      $('#tipoBien').val('');
      lastValidResult = ''; // Limpiar el último resultado válido
    } else {
      // Si el código tiene menos de 8 dígitos, mantener el último resultado válido
      $('#tipoBien').val(lastValidResult);
    }
  }

  // Evento para cuando el valor del campo de código cambia
  $('#codigoPatrimonial').on('input', function () {
    var codigo = $(this).val().replace(/[^0-9]/g, ''); // Filtrar para que solo se permitan dígitos
    buscarTipoBien(codigo); // Llamar a la función de búsqueda
  });
});
