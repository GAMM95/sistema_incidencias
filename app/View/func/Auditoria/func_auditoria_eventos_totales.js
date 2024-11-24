$(document).ready(function () {
  // Configuración de Toastr
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };

  // SETEO DE COMBO usuario
  $.ajax({
    url: 'ajax/getPersonaAuditoria.php',
    type: 'GET',
    dataType: 'json',
    success: function (data) {
      var select = $('#personaEventosTotales');
      select.empty();
      select.append('<option value="" selected disabled>Seleccione un usuario</option>');
      $.each(data, function (index, value) {
        select.append('<option value="' + value.USU_codigo + '">' + value.persona + '</option>');
      });
    },
    error: function (error) {
      console.error(error);
    }
  });

  // BUSCADOR PARA EL COMBO AREA 
  $('#personaEventosTotales').select2({
    allowClear: true,
    width: '100%',
    dropdownCssClass: 'text-xs', // Use Tailwind CSS class
    language: {
      noResults: function () {
        return "No se encontraron resultados";
      }
    }
  });

  // Evento para limpiar los campos y renderizar la tabla
  function nuevaConsultaEventosTotales() {
    // Limpiar los campos de fecha y el input de persona (resetea el formulario)
    $('#fechaInicioEventosTotales').val('');
    $('#fechaFinEventosTotales').val('');
    $('#personaEventosTotales').val(null).trigger('change');  // Reset del select2 con trigger

    // Realizar la solicitud AJAX para obtener todos los registros (sin filtros)
    $.ajax({
      url: 'auditoria.php?action=consultarEventosTotales', // No pasamos filtros en esta consulta
      type: 'GET',
      dataType: 'html', // Esperamos HTML para renderizar la tabla
      success: function (response) {
        console.log("Resultados de nueva consulta (sin filtros):", response);
        // Limpia el contenido actual de la tabla antes de agregar nuevos datos
        $('#tablaEventosTotales tbody').empty();
        // Actualiza el contenido de la tabla con la respuesta
        $('#tablaEventosTotales tbody').html(response);
      },
      error: function (xhr, status, error) {
        console.error('Error en la consulta AJAX:', error);
      }
    });
  }

  $('#limpiarCamposEventosTotales').on('click', nuevaConsultaEventosTotales);

  $('#formEventosTotales').submit(function (event) {
    event.preventDefault(); // Evita el envío del formulario por defecto

    // Verifica si los campos y las fechas son válidos
    if (!validarCamposEventosTotales() || !validarFechasEventosTotales()) {
      return; // Detiene el envío si los campos o las fechas no son válidos
    }

    var formData = $(this).serializeArray(); // Recopila los datos del formulario
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
      url: 'auditoria.php?action=consultarEventosTotales',
      type: 'GET',
      data: dataObject,
      success: function (response) {
        console.log("Resultados filtrados:", response); // Depuración
        // Limpia el contenido actual de la tabla antes de agregar nuevos datos
        $('#tablaEventosTotales tbody').empty();
        // Actualiza el contenido de la tabla con la respuesta
        $('#tablaEventosTotales tbody').html(response);
      },
      error: function (xhr, status, error) {
        console.error('Error en la consulta AJAX:', error);
      }
    });

    function validarCamposEventosTotales() {
      var valido = false;
      var mensajeError = '';

      var faltaUsuario = ($('#personaEventosTotales').val() !== null && $('#personaEventosTotales').val().trim() !== '');
      var fechaInicioSeleccionada = ($('#fechaInicioEventosTotales').val() !== null && $('#fechaInicioEventosTotales').val().trim() !== '');
      var fechaFinSeleccionada = ($('#fechaFinEventosTotales').val() !== null && $('#fechaFinEventosTotales').val().trim() !== '');

      // Verificar si al menos un campo está lleno
      if (faltaUsuario || fechaInicioSeleccionada || fechaFinSeleccionada) {
        valido = true;
      } else {
        mensajeError = 'Debe completar al menos un campo para realizar la b&uacute;squeda.';
      }

      if (!valido) {
        toastr.warning(mensajeError.trim(), 'Advertencia');
      }

      return valido;
    }
  });

  function validarFechasEventosTotales() {
    // Obtener valores de los campos de fecha
    const fechaInicio = new Date($('#fechaInicioEventosTotales').val());
    const fechaFin = new Date($('#fechaFinEventosTotales').val());

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
      mensajeError = 'La fecha fin no puede ser posterior a la fecha actual.';
      valido = false;
    }

    // Verificar que la fecha de fin sea posterior a la fecha de inicio
    if (fechaInicio && fechaFin && fechaFin < fechaInicio) {
      mensajeError = 'La fecha fin debe ser posterior a la fecha de inicio.';
      valido = false;
    }

    // Mostrar mensaje de error con Toastr si la validación falla
    if (!valido) {
      toastr.warning(mensajeError.trim(), 'Advertencia');
    }

    return valido;
  }

  // Agregar eventos para validar fechas cuando cambien
  $('#fechaInicioEventosTotales, #fechaFinEventosTotales, #personaEventosTotales').on('change', function () {
    validarCamposEventosTotalesFiltro(); // Llama a la validación de fechas y de la persona seleccionada
  });
});

// Seleccionar los elementos de los campos de fecha, persona y el botón de reporte
const personaSeleccionada = document.getElementById("personaEventosTotales");
const fechaInicio = document.getElementById("fechaInicioEventosTotales");
const fechaFin = document.getElementById("fechaFinEventosTotales");
const reporteButton = document.getElementById("reporteEventosTotalesFiltro");
const limpiarCamposButton = document.getElementById("limpiarCamposEventosTotales");

// Función que valida si al menos un campo (persona, fecha inicio o fecha fin) tiene valor
function validarCamposEventosTotalesFiltro() {
  if (personaSeleccionada.value !== "" || fechaInicio.value !== "" || fechaFin.value !== "") {
    // Si hay valor en alguno de los campos, habilitar el botón de reporte
    reporteButton.disabled = false;
    reporteButton.classList.remove("bg-gray-300", "cursor-not-allowed");
    reporteButton.classList.add("bg-blue-500", "hover:bg-blue-600", "cursor-pointer");
  } else {
    // Si no hay valor en ningún campo, deshabilitar el botón de reporte
    reporteButton.disabled = true;
    reporteButton.classList.remove("bg-blue-500", "hover:bg-blue-600", "cursor-pointer");
    reporteButton.classList.add("bg-gray-300", "cursor-not-allowed");
  }
}

// Escuchar los cambios en los campos de fecha y persona
fechaInicio.addEventListener("input", validarCamposEventosTotalesFiltro);
fechaFin.addEventListener("input", validarCamposEventosTotalesFiltro);
personaSeleccionada.addEventListener("change", validarCamposEventosTotalesFiltro);

// Deshabilitar el botón de reporte al cargar la página
window.onload = function () {
  reporteButton.disabled = true;
  reporteButton.classList.add("bg-gray-300", "cursor-not-allowed");
};

// Función que se ejecuta cuando se hace clic en el botón "Nueva Consulta"
limpiarCamposButton.addEventListener("click", function () {
  // Deshabilitar el botón de reporte y aplicar clases de deshabilitado
  reporteButton.disabled = true;
  reporteButton.classList.remove("bg-blue-500", "hover:bg-blue-600", "cursor-pointer");
  reporteButton.classList.add("bg-gray-300", "cursor-not-allowed");

  // Limpiar los campos de fecha y persona (opcional)
  fechaInicio.value = "";
  fechaFin.value = "";
  personaSeleccionada.value = "";
});
