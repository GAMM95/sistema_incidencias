$(document).ready(function () {
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };

  // Funcion para realizar la consulta sin filtros
  function nuevaConsultaEquipos() {
    // limpiar los campos fechas 
    $('#fechaInicioIncidenciasEquipo').val('');
    $('#fechaFinIncidenciasEquipo').val('');
    $('#codigoPatrimonialEquipo').val('');
    $('#tipoBienEquipo').val('');

    // Realizar la solicitud AJAX para obtener todos los registros (sin filtros)
    $.ajax({
      url: 'reportes.php?action=consultarIncidenciasEquipos',
      type: 'GET',
      dataType: 'html', // Esperamos HTML para renderizar la tabla
      success: function (response) {
        console.log("Resultados de nueva consulta: ", response);
        // Limpia el contenido actual de la tabla antes de agregar nuevos datos
        $('#tablaIncidenciasEquipos tbody').empty();
        // Actualiza el contenido de la tabla con la respuesta
        $('#tablaIncidenciasEquipos tbody').html(response);
      },
      error: function (xhr, status, error) {
        console.error('Error en la consulta AJAX:', error);
      }
    });
  }


  // Evento para el botón de limpiar campos
  $('#limpiarCamposIncidenciasEquipo').on('click', nuevaConsultaEquipos);

  // Validación y envío del formulario
  $('#formIncidenciasEquipos').submit(function (event) {
    event.preventDefault(); // Evita el envío del formulario por defecto

    // Verifica si los campos y las fechas son válidos
    if (!validarCamposEquipos() || !validarFechasEquipos()) {
      return; // Detiene el envío si los campos o las fechas no son válidos
    }

    var formData = $(this).serializeArray(); // Recopila los datos del formulario
    var dataObject = {}; // Crea un objeto para los datos del formulario
    console.log(dataObject);

    // Recorre los datos del formulario y llena el objeto con los valores
    formData.forEach(function (item) {
      if (item.value.trim() !== '') {
        dataObject[item.name] = item.value;
      }
    });

    // Realiza la solicitud AJAX
    $.ajax({
      url: 'reportes.php?action=consultarIncidenciasEquipos',
      type: 'GET',
      data: dataObject,
      success: function (response) {
        console.log("Resultados filtrados:", response);
        $('#tablaIncidenciasEquipos tbody').empty(); // Limpia el contenido actual de la tabla antes de agregar nuevos datos
        $('#tablaIncidenciasEquipos tbody').html(response); // Actualiza el contenido de la tabla con la respuesta
      },
      error: function (xhr, status, error) {
        console.error('Error en la consulta AJAX:', error);
      }
    });

    // Función para validar los campos fechas
    function validarCamposEquipos() {
      var valido = false;
      var mensajeError = '';

      var faltaCodigoPatrimonial = ($('#codigoPatrimonialEquipo').val() !== null && $('#codigoPatrimonialEquipo').val().trim() !== '');
      var fechaInicioSeleccionada = ($('#fechaInicioIncidenciasEquipo').val() !== null && $('#fechaInicioIncidenciasEquipo').val().trim() !== '');
      var fechaFinSeleccionada = ($('#fechaFinIncidenciasEquipo').val() !== null && $('#fechaFinIncidenciasEquipo').val().trim() !== '');


      // Verificar si al menos un campo está lleno
      if (faltaCodigoPatrimonial || fechaInicioSeleccionada || fechaFinSeleccionada) {
        valido = true;
      } else {
        mensajeError = 'Debe completar al menos un campo para filtrar la tabla.';
      }

      if (!valido) {
        toastr.warning(mensajeError.trim(), 'Advertencia');
      }

      return valido;
    }
  });

  // Función para validar fechas
  function validarFechasEquipos() {
    const fechaInicio = new Date($('#fechaInicioIncidenciasEquipo').val());
    const fechaFin = new Date($('#fechaFinIncidenciasEquipo').val());
    const fechaHoy = new Date();
    fechaHoy.setHours(0, 0, 0, 0); // Ajustar la hora para comparar solo las fechas

    let valido = true;
    let mensajeError = ''; // Asegurarse de que mensajeError sea una cadena vacía

    if (fechaInicio > fechaHoy) {
      mensajeError = 'La fecha de inicio no puede ser posterior a la fecha actual.';
      valido = false;
    }

    if (fechaFin > fechaHoy) {
      mensajeError = 'La fecha fin no puede ser posterior a la fecha actual.';
      valido = false;
    }

    if (fechaInicio && fechaFin && fechaInicio > fechaFin) {
      mensajeError = 'La fecha fin debe ser posterior a la fecha de inicio.';
      valido = false;
    }

    if (!valido) {
      toastr.warning(mensajeError.trim(), 'Advertencia'); // Ahora mensajeError siempre será una cadena
    }

    return valido;
  }

  // Agregar eventos para validar fechas cuando cambien
  $('#fechaInicioIncidenciasEquipo, #fechaFinIncidenciasEquipo').on('change', function () {
    validarFechasEquipos();
  });

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
          data: { codigoEquipo: codigoBusqueda }, // Enviar el código para buscar
          success: function (response) {
            // Verificar si el tipo de bien fue encontrado en la respuesta
            if (response.tipo_bien) {
              lastValidResult = response.tipo_bien; // Guardar el resultado válido
              $('#tipoBienEquipo').val(lastValidResult);  // Mostrar el tipo de bien en el campo readonly
            } else {
              $('#tipoBienEquipo').val('No encontrado'); // Mostrar mensaje si no se encuentra el tipo
            }
          },
          error: function () {
            $('#tipoBienEquipo').val('Error al buscar'); // Mostrar mensaje de error en caso de fallo
          }
        });
      } else if (codigo.length === 0) {
        // Si el código está vacío, limpiar el valor de tipoBien
        $('#tipoBienEquipo').val('');
        lastValidResult = ''; // Limpiar el último resultado válido
      } else {
        // Si el código tiene menos de 8 dígitos, mantener el último resultado válido
        $('#tipoBienEquipo').val(lastValidResult);
      }
    }

    // Evento para cuando el valor del campo de código cambia
    $('#codigoPatrimonialEquipo').on('input', function () {
      var codigo = $(this).val().replace(/[^0-9]/g, ''); // Filtrar para que solo se permitan dígitos
      buscarTipoBien(codigo); // Llamar a la función de búsqueda
    });
  });
});


