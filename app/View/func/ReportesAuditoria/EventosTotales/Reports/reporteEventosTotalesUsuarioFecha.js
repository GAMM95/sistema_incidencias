$(document).ready(function () {
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };
});

// Generación del PDF al hacer clic en el botón "Usuario y fechas"
$('#reporteEventosTotalesUsuarioFecha').click(function () {
  const usuario = $("#personaEventosTotales").val();
  const fechaInicio = $('#fechaInicioEventosTotales').val();
  const fechaFin = $('#fechaFinEventosTotales').val();

  console.log('Usuario:', usuario);
  console.log('Fecha Inicio:', fechaInicio);
  console.log('Fecha Fin:', fechaFin);

  // Verificar si los campos son validos
  if (!validarCamposEventosTotalesUsuarioFecha()) {
    return;
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

  // Realizar una solicitud AJAX para obtener los datos de la incidencia
  $.ajax({
    url: 'ajax/ReportesAuditoria/EventosTotales/getReporteEventosTotalesUsuarioFecha.php',
    method: 'GET',
    data: {
      personaEventosTotales: usuario,
      fechaInicioEventosTotales: fechaInicio,
      fechaFinEventosTotales: fechaFin
    },
    dataType: 'json',
    success: function (data) {
      console.log("Datos recibidos:", data);

      if (data.error) {
        toastr.error('Error en la solicitud: ' + data.error);
        return;
      }

      // Obtener la cantidad de registros
      const totalRecords = data.length;
      // Verificar si hay registros
      if (totalRecords === 0) {
        toastr.warning('No se encontraron datos para los campos ingresados.', 'Advertencia');
        return;
      }

      try {
        if (data.length > 0) {
          const { jsPDF } = window.jspdf;
          const doc = new jsPDF('landscape');
          const logoUrl = './public/assets/escudo.png';

          // Función para agregar encabezado
          function addHeader(doc, totalRecords) {
            const pageWidth = doc.internal.pageSize.width;
            const marginX = 10;
            const marginY = 5;
            const logoWidth = 25;
            const logoHeight = 25;
            const reportTitle = 'REPORTE DE EVENTOS POR USUARIO Y FECHAS';
            const headerText2 = 'Subgerencia de Informática y Sistemas';
            const fechaImpresion = new Date().toLocaleDateString();

            // Agregar logo
            doc.addImage(logoUrl, 'PNG', marginX, marginY, logoWidth, logoHeight);

            // Título principal
            doc.setFont('helvetica', 'bold');
            doc.setFontSize(15);
            const titleWidth = doc.getTextWidth(reportTitle);
            const titleX = (pageWidth - titleWidth) / 2;
            doc.text(reportTitle, titleX, marginY + 10);
            doc.setLineWidth(0.5);
            doc.line(titleX, marginY + 11, titleX + titleWidth, marginY + 11);

            // Subtítulo: cantidad de registros
            const subtitleText = `Cantidad de registros: ${totalRecords}`;
            doc.setFontSize(11);
            const subtitleWidth = doc.getTextWidth(subtitleText);
            const subtitleX = (pageWidth - subtitleWidth) / 2;
            doc.text(subtitleText, subtitleX, marginY + 16);

            // Fecha y texto derecho
            doc.setFontSize(8);
            const headerText2Width = doc.getTextWidth(headerText2);
            const fechaText = `Fecha de impresión: ${fechaImpresion}`;
            const fechaTextWidth = doc.getTextWidth(fechaText);
            doc.text(headerText2, pageWidth - marginX - headerText2Width, marginY + logoHeight / 2);
            doc.text(fechaText, pageWidth - marginX - fechaTextWidth, marginY + logoHeight / 2 + 5);
          }

          // Función para agregar pie de página
          function addFooter(doc, pageNumber, totalPages) {
            doc.setFontSize(8);
            doc.setFont('helvetica', 'italic');
            const footerY = 200;
            doc.setLineWidth(0.5);
            doc.line(10, footerY - 5, doc.internal.pageSize.width - 10, footerY - 5);

            const footerText = 'Sistema de Gestión de Incidencias';
            const pageInfo = `Página ${pageNumber} de ${totalPages}`;
            doc.text(footerText, 10, footerY);
            doc.text(pageInfo, doc.internal.pageSize.width - 10 - doc.getTextWidth(pageInfo), footerY);
          }

          // Función para agregar datos del usuario y fechas
          function addUserAndDates(doc) {
            const pageWidth = doc.internal.pageSize.width;
            const labels = {
              fechaInicio: 'Fecha de Inicio: ',
              fechaFin: 'Fecha Fin: ',
              usuario: 'Usuario:'
            };

            // Obtener valores
            const usuarioNombre = $('#nombreUsuarioEventosTotales').val() || '-';
            const fechaInicioOriginal = $('#fechaInicioEventosTotales').val();
            const fechaFinOriginal = $('#fechaFinEventosTotales').val();

            // Función para formatear la fecha
            function formatearFecha(fecha) {
              if (!fecha) return ' - ';
              const [yyyy, mm, dd] = fecha.split('-');
              return `${dd}/${mm}/${yyyy}`;
            }

            // Fechas formateadas
            const fechas = {
              inicio: formatearFecha(fechaInicioOriginal),
              fin: formatearFecha(fechaFinOriginal)
            };

            // Dibujar datos de usuario
            doc.setFont('helvetica', 'bold');
            doc.setFontSize(11);

            // Usuario
            const usuarioLabelWidth = doc.getTextWidth(labels.usuario);
            const usuarioValueWidth = doc.getTextWidth(` ${usuarioNombre}`);
            const totalUsuarioWidth = usuarioLabelWidth + usuarioValueWidth;
            const startXUsuario = (pageWidth - totalUsuarioWidth) / 2;
            const titleYUsuario = 26;
            doc.text(labels.usuario, startXUsuario, titleYUsuario);
            doc.setFont('helvetica', 'normal');
            doc.text(` ${usuarioNombre}`, startXUsuario + usuarioLabelWidth, titleYUsuario);

            // Fechas
            const fechaInicioWidth = doc.getTextWidth(labels.fechaInicio);
            const fechaFinWidth = doc.getTextWidth(labels.fechaFin);
            const fechaInicioValueWidth = doc.getTextWidth(` ${fechas.inicio}`);
            const fechaFinValueWidth = doc.getTextWidth(` ${fechas.fin}`);
            const spacing = 15;
            const totalWidthFechas = fechaInicioWidth + fechaInicioValueWidth + spacing + fechaFinWidth + fechaFinValueWidth;
            const startXFechas = (pageWidth - totalWidthFechas) / 2;
            const titleYFechas = 32;

            doc.setFont('helvetica', 'bold');
            doc.text(labels.fechaInicio, startXFechas, titleYFechas);
            doc.setFont('helvetica', 'normal');
            doc.text(` ${fechas.inicio}`, startXFechas + fechaInicioWidth, titleYFechas);

            doc.setFont('helvetica', 'bold');
            doc.text(labels.fechaFin, startXFechas + fechaInicioWidth + fechaInicioValueWidth + spacing, titleYFechas);
            doc.setFont('helvetica', 'normal');
            doc.text(` ${fechas.fin}`, startXFechas + fechaInicioWidth + fechaInicioValueWidth + spacing + fechaFinWidth, titleYFechas);
          }

          // Función para agregar tabla de datos
          function addTable(doc) {
            let item = 1;
            doc.autoTable({
              startY: 36,
              margin: { left: 4 },
              head: [['N°', 'FECHA Y HORA', 'EVENTO', 'TABLA', 'ROL', 'USUARIO', 'NOMBRE COMPLETO', 'ÁREA', 'IP', 'NOMBRE DEL EQUIPO']],
              body: data.map(reporte => [
                item++,
                reporte.fechaFormateada,
                reporte.AUD_operacion,
                reporte.AUD_tabla,
                reporte.ROL_nombre,
                reporte.USU_nombre,
                reporte.NombreCompleto,
                reporte.ARE_nombre,
                reporte.AUD_ip,
                reporte.AUD_nombreEquipo
              ]),
              styles: {
                fontSize: 7,
                cellPadding: 2,
                halign: 'center',
                valign: 'middle'
              },
              headStyles: {
                fillColor: [44, 62, 80],
                textColor: [255, 255, 255],
                fontStyle: 'bold',
                halign: 'center'
              },
              columnStyles: {
                0: { cellWidth: 8 },
                1: { cellWidth: 30 },
                2: { cellWidth: 35 },
                3: { cellWidth: 25 },
                4: { cellWidth: 20 },
                5: { cellWidth: 25 },
                6: { cellWidth: 38 },
                7: { cellWidth: 42 },
                8: { cellWidth: 30 },
                9: { cellWidth: 35 }
              }
            });
          }

          // Agregar encabezado, datos de usuario y fechas, tabla y pie de página
          addHeader(doc, totalRecords);
          addUserAndDates(doc);
          addTable(doc);

          // Agregar pie de página en todas las páginas
          const totalPages = doc.internal.getNumberOfPages();
          for (let i = 1; i <= totalPages; i++) {
            doc.setPage(i);
            addFooter(doc, i, totalPages);
          }

          // Mostrar mensaje de éxito
          toastr.success('Reporte de eventos por usuario y fechas generado.', 'Mensaje');

          // Abrir PDF después de una pequeña pausa
          setTimeout(() => {
            window.open(doc.output('bloburl'));
          }, 2000);
        } else {
          toastr.warning('No se ha encontrado eventos para los campos ingresados.', 'Advertencia');
        }
      } catch (error) {
        toastr.error('Hubo un error al generar reporte.', 'Mensaje de error');
        console.error('Error al generar el PDF:', error.message);
      }
    },
    error: function (xhr, status, error) {
      toastr.error('Hubo un error al obtener datos de los eventos.', 'Mensaje de error');
      console.error('Error al realizar la solicitud AJAX:', error);
    }
  });
});

function validarCamposEventosTotalesUsuarioFecha() {
  var valido = false;
  var mensajeError = '';

  // Verificar si los campos no están vacíos
  var fechaInicioSeleccionada = ($('#fechaInicioEventosTotales').val() !== null && $('#fechaInicioEventosTotales').val().trim() !== '');
  var fechaFinSeleccionada = ($('#fechaFinEventosTotales').val() !== null && $('#fechaFinEventosTotales').val().trim() !== '');
  var usuarioSeleccionado = ($('#personaEventosTotales').val() !== null && $('#personaEventosTotales').val().trim() !== '');

  // Verificar si al menos uno de los campos tiene datos
  if (fechaInicioSeleccionada && fechaFinSeleccionada && usuarioSeleccionado) {
    valido = true;
  } else {
    mensajeError = 'Debe seleccionar un usuario e ingresar el rango de fechas para generar el reporte.';
  }

  if (!valido) {
    toastr.warning(mensajeError.trim(), 'Advertencia');
  }

  return valido;
}
