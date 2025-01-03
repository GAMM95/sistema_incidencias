$(document).ready(function () {
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };
});

// Generación del PDF al hacer clic en el botón "Usuario y fechas"
$('#reporteIncidenciasAreaFecha').click(function () {
  const area = $("#areaIncidencia").val();
  const fechaInicio = $('#fechaInicioIncidenciasArea').val();
  const fechaFin = $('#fechaFinIncidenciasArea').val();

  console.log('Área:', area);
  console.log('Fecha Inicio:', fechaInicio);
  console.log('Fecha Fin:', fechaFin);

  // Verificar si los campos son validos
  if (!validarCamposIncidenciasAreaFecha()) {
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
    url: 'ajax/ReportesIncidencias/ReportesAreas/getReportePorAreaFecha.php',
    method: 'GET',
    data: {
      areaIncidencia: area,
      fechaInicioIncidenciasArea: fechaInicio,
      fechaFinIncidenciasArea: fechaFin
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
            const reportTitle = 'REPORTE DE INCIDENCIAS POR ÁREA Y FECHAS';
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

          // Función para agregar datos del area y fechas
          function addAreaAndDates(doc) {
            const pageWidth = doc.internal.pageSize.width;
            const labels = {
              fechaInicio: 'Fecha de Inicio: ',
              fechaFin: 'Fecha Fin: ',
              area: 'Área:'
            };

            // Obtener valores
            const areaNombre = $('#nombreArea').val() || '-';
            const fechaInicioOriginal = $('#fechaInicioIncidenciasArea').val();
            const fechaFinOriginal = $('#fechaFinIncidenciasArea').val();

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

            // Dibujar datos de area
            doc.setFont('helvetica', 'bold');
            doc.setFontSize(11);

            // Area
            const areaLabelWidth = doc.getTextWidth(labels.area);
            const areaValueWidth = doc.getTextWidth(` ${areaNombre}`);
            const totalAreaWidth = areaLabelWidth + areaValueWidth;
            const startXArea = (pageWidth - totalAreaWidth) / 2;
            const titleYArea = 26;
            doc.text(labels.area, startXArea, titleYArea);
            doc.setFont('helvetica', 'normal');
            doc.text(` ${areaNombre}`, startXArea + areaLabelWidth, titleYArea);

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
              startY: 35,
              margin: { left: 4, right: 10 },
              head: [['N°', 'INCIDENCIA', 'ÁREA SOLICITANTE', 'FECHA INC.', 'ASUNTO', 'DOCUMENTO', 'EQUIPO', 'NOMBRE DEL BIEN', 'PRIORIDAD', 'CONDICIÓN', 'ESTADO']],
              body: data.map(reporte => [
                item++,
                reporte.INC_numero_formato,
                reporte.ARE_nombre,
                reporte.fechaIncidenciaFormateada,
                reporte.INC_asunto,
                reporte.INC_documento,
                reporte.INC_codigoPatrimonial,
                reporte.BIE_nombre,
                reporte.PRI_nombre,
                reporte.CON_descripcion,
                reporte.Estado,
              ]),
              styles: {
                fontSize: 7,
                cellPadding: 2,
                halign: 'center',
                valign: 'middle'
              },
              headStyles: {
                fillColor: [9, 4, 6],
                textColor: [255, 255, 255],
                fontStyle: 'bold',
                halign: 'center'
              },
              columnStyles: {
                0: { cellWidth: 8 }, // Ancho para la columna item
                1: { cellWidth: 22 }, // Ancho para la columna Número de incidencia
                2: { cellWidth: 35 }, // Ancho para la columna area solicitante
                3: { cellWidth: 35 }, // Ancho para la columna asunto
                4: { cellWidth: 25 }, // Ancho para la columna equipo
                5: { cellWidth: 33 }, // Ancho para la columna nombre del bien
                6: { cellWidth: 30 }, // Ancho para la columna fecha de asignación
                7: { cellWidth: 30 }, // Ancho para la columna fecha de finalización
                8: { cellWidth: 22 }, // Ancho para la columna usario asignado
                9: { cellWidth: 25 }, // Ancho para la columna tiempo de mantenimiento
                10: { cellWidth: 25 }, // Ancho para la columna estado
              }
            });
          }

          // Agregar encabezado, datos de usuario y fechas, tabla y pie de página
          addHeader(doc, totalRecords);
          addAreaAndDates(doc);
          addTable(doc);

          // Agregar pie de página en todas las páginas
          const totalPages = doc.internal.getNumberOfPages();
          for (let i = 1; i <= totalPages; i++) {
            doc.setPage(i);
            addFooter(doc, i, totalPages);
          }

        // Establecer las propiedades del documento
        doc.setProperties({
          title: "Reporte por áreas seleccionadas y fechas.pdf"
        });

          // Mostrar mensaje de éxito
          toastr.success('Reporte de incidencias por &aacute;rea y fechas generado.', 'Mensaje');

          // Abrir PDF después de una pequeña pausa
          setTimeout(() => {
            window.open(doc.output('bloburl'), '_blank');
          }, 2000);
        } else {
          toastr.warning('No se ha encontrado incidencias para los campos ingresados.', 'Advertencia');
        }
      } catch (error) {
        toastr.error('Hubo un error al generar reporte.', 'Mensaje de error');
        console.error('Error al generar el PDF:', error.message);
      }
    },
    error: function (xhr, status, error) {
      toastr.error('Hubo un error al obtener datos de las incidencias cerradas.', 'Mensaje de error');
      console.error('Error al realizar la solicitud AJAX:', error);
    }
  });
});

function validarCamposIncidenciasAreaFecha() {
  var valido = false;
  var mensajeError = '';

  // Verificar si los campos no están vacíos
  var fechaInicioSeleccionada = ($('#fechaInicioIncidenciasArea').val() !== null && $('#fechaInicioIncidenciasArea').val().trim() !== '');
  var fechaFinSeleccionada = ($('#fechaFinIncidenciasArea').val() !== null && $('#fechaFinIncidenciasArea').val().trim() !== '');
  var areaSeleccionada = ($('#areaIncidencia').val() !== null && $('#areaIncidencia').val().trim() !== '');

  // Verificar si al menos uno de los campos tiene datos
  if (fechaInicioSeleccionada && fechaFinSeleccionada && areaSeleccionada) {
    valido = true;
  } else {
    mensajeError = 'Debe seleccionar un &aacute;rea e ingresar el rango de fechas para generar el reporte.';
  }

  if (!valido) {
    toastr.warning(mensajeError.trim(), 'Advertencia');
  }

  return valido;
}
