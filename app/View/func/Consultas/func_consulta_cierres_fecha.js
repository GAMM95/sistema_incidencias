$(document).ready(function () {
  // Configuración de Toastr
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };

  function nuevaConsulta() {
    // limpiar los campos de fecha
    $('#fechaInicio_incidencias_cerradas, #fechaFin_incidencias_cerradas').val('');

    // Realiza una llamada AJAX para obtener los registros para el rango de fechas
    $.ajax({
      url: "reportes.php?action=consultarCerradas",
      type: 'GET',
      success: function (response) {
        console.log("Resultados: ", response);
        // Limpiar el contenido actual de la tabla
        $('#tablaIncidenciasCerradas tbody').empty();
        // Actualizar el contenido de la tabla con la respuesta
        $('#tablaIncidenciasCerradas tbody').html(response);
      },
      error: function (error) {
        console.error("Error al obtener registros: ", error);
      }
    })
  }

  // Evento para nueva consulta
  $('#limpiarCampos_incidencias_cerradas').on('click', nuevaConsulta);

  $('#formConsultarIncidenciasCerradas').submit(function (event) {
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
      url: "reportes.php?action=consultarCerradas",
      type: 'GET',
      data: dataObject,
      success: function (response) {
        console.log("Resultados filtrados:", response); // Depuración
        // Limpia el contenido actual de la tabla antes de agregar nuevos datos
        $('#tablaIncidenciasCerradas tbody').empty();
        // Actualiza el contenido de la tabla con la respuesta
        $('#tablaIncidenciasCerradas tbody').html(response);
      },
      error: function (xhr, status, error) {
        console.error('Error en la consulta AJAX:', error);
      }
    });

    function validarCampos() {
      var valido = false;
      var mensajeError = '';

      var fechaInicioSeleccionada = ($('#fechaInicio_incidencias_cerradas').val() !== null && $('#fechaInicio_incidencias_cerradas').val().trim() !== '');
      var fechaFinSeleccionada = ($('#fechaFin_incidencias_cerradas').val() !== null && $('#fechaFin_incidencias_cerradas').val().trim() !== '');

      // Verificar si al menos un campo está lleno
      if (fechaInicioSeleccionada || fechaFinSeleccionada) {
        valido = true;
      } else {
        mensajeError = 'Debe ingresar el rango de fechas para filtrar las incidencias.';
      }

      if (!valido) {
        toastr.warning(mensajeError.trim(), 'Advertencia');
      }

      return valido;
    }
  });

  function validarFechas() {
    // Obtener valores de los campos de fecha
    const fechaInicio = new Date($('#fechaInicio_incidencias_cerradas').val());
    const fechaFin = new Date($('#fechaFin_incidencias_cerradas').val());

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
  $('#fechaInicio_incidencias_cerradas, #fechaFin_incidencias_cerradas').on('change', function () {
    validarFechasCerradas();
  });


  // Seleccionar los elementos de los campos de fecha y el botón de reporte
  const fechaInicio = document.getElementById("fechaInicio_incidencias_cerradas");
  const fechaFin = document.getElementById("fechaFin_incidencias_cerradas");
  const reporteButton = document.getElementById("reportes-cierres-fechas");
  const limpiarCamposButton = document.getElementById("limpiarCampos_incidencias_cerradas");

  // Función que valida si ambos campos de fecha están completos
  function validarFechasCerradas() {
    if (fechaInicio.value !== "" && fechaFin.value !== "") {
      // Si ambos campos tienen valor, habilitar el botón de reporte
      reporteButton.disabled = false;
      reporteButton.classList.remove("bg-gray-400", "cursor-not-allowed");
      reporteButton.classList.add("bg-blue-500", "hover:bg-blue-600", "cursor-pointer");
    } else {
      // Si falta una o ambas fechas, deshabilitar el botón de reporte
      reporteButton.disabled = true;
      reporteButton.classList.remove("bg-blue-500", "hover:bg-blue-600", "cursor-pointer");
      reporteButton.classList.add("bg-gray-400", "cursor-not-allowed");
    }
  }

  // Escuchar los cambios en los campos de fecha
  fechaInicio.addEventListener("input", validarFechas);
  fechaFin.addEventListener("input", validarFechas);

  // Deshabilitar el botón de reporte al cargar la página
  window.onload = function () {
    reporteButton.disabled = true;
    reporteButton.classList.add("bg-gray-400", "cursor-not-allowed");
  };

  // Función que se ejecuta cuando se hace clic en el botón "Nueva Consulta"
  limpiarCamposButton.addEventListener("click", function () {
    // Deshabilitar el botón de reporte y aplicar clases de deshabilitado
    reporteButton.disabled = true;
    reporteButton.classList.remove("bg-blue-500", "hover:bg-blue-600", "cursor-pointer");
    reporteButton.classList.add("bg-gray-400", "cursor-not-allowed");

    // Limpiar los campos de fecha (opcional)
    fechaInicio.value = "";
    fechaFin.value = "";
  });

});

