$(document).ready(function () {
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };
});

// Generación del PDF al hacer clic en el botón "Area"
$('#reporteIncidenciasArea').click(function () {
  const area = $("#areaSeleccionada").val();

  console.log('Area:', area);

  // Verificar si los campos son validos
  if (!validarCamposIncidenciasArea()) {
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
    url: 'ajax/ReportesIncidencias/ReportesAreas/getReportePorArea.php',
    method: 'GET',
    data: {
      areaSeleccionada: area
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
        toastr.warning('No se encontraron datos para el &aacute;rea seleccionada.', 'Advertencia');
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
            const reportTitle = 'REPORTE TOTAL DE INCIDENCIAS POR ÁREA';
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
            doc.text(subtitleText, subtitleX, marginY + 17);

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

          // Función para agregar datos del area
          function addUser(doc) {
            const pageWidth = doc.internal.pageSize.width;
            const labels = {
              area: 'Área: '
            };

            // Obtener valores
            const areaNombre = $('#nombreAreaSeleccionada').val() || '-';

            // Dibujar datos de area
            doc.setFont('helvetica', 'bold');
            doc.setFontSize(11);

            // Area
            const areaLabelWidth = doc.getTextWidth(labels.area);
            const areaValueWidth = doc.getTextWidth(` ${areaNombre}`);
            const totalAreaWidth = areaLabelWidth + areaValueWidth;
            const startXArea = (pageWidth - totalAreaWidth) / 2;
            const titleYArea = 29;
            doc.text(labels.area, startXArea, titleYArea);
            doc.setFont('helvetica', 'normal');
            doc.text(` ${areaNombre}`, startXArea + areaLabelWidth, titleYArea);
          }

          // Función para agregar tabla de datos
          function addTable(doc) {
            let item = 1;
            doc.autoTable({
              startY: 35,
              margin: { left: 4, right: 10 },
              head: [['N°', 'INCIDENCIA', 'FECHA INC', 'CATEGORÍA', 'ASUNTO', 'DOCUMENTO', 'CÓD PATRIMONIAL', 'PRIORIDAD', 'CONDICIÓN', 'ESTADO']],
              body: data.map(reporte => [
                item++,
                reporte.INC_numero_formato,
                reporte.fechaIncidenciaFormateada,
                reporte.CAT_nombre,
                reporte.INC_asunto,
                reporte.INC_documento,
                reporte.INC_codigoPatrimonial,
                reporte.PRI_nombre,
                reporte.CON_descripcion,
                reporte.ESTADO
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
                1: { cellWidth: 25 }, // Ancho para la columna numero formato incidencia
                2: { cellWidth: 18 }, // Ancho para la columna fecha de incidencia
                3: { cellWidth: 45 }, // Ancho para la columna categoria
                4: { cellWidth: 45 }, // Ancho para la columna asunto
                5: { cellWidth: 40 }, // Ancho para la columna documento
                6: { cellWidth: 30 }, // Ancho para la columna coigo patrimonial
                7: { cellWidth: 25 }, // Ancho para la columna prioridad
                8: { cellWidth: 25 }, // Ancho para la columna condicion
                9: { cellWidth: 25 } // Ancho para la columna estado
              }
            });
          }

          // Agregar encabezado, datos de area, tabla y pie de página
          addHeader(doc, totalRecords);
          addUser(doc);
          addTable(doc);

          // Agregar pie de página en todas las páginas
          const totalPages = doc.internal.getNumberOfPages();
          for (let i = 1; i <= totalPages; i++) {
            doc.setPage(i);
            addFooter(doc, i, totalPages);
          }

          // Mostrar mensaje de éxito
          toastr.success('Reporte de incidencias por &aacute;rea generado.', 'Mensaje');

          // Abrir PDF después de una pequeña pausa
          setTimeout(() => {
            window.open(doc.output('bloburl'));
          }, 2000);
        } else {
          toastr.warning('No se ha encontrado incidencias por &aacute;rea.', 'Advertencia');
        }
      } catch (error) {
        toastr.error('Hubo un error al generar reporte.', 'Mensaje de error');
        console.error('Error al generar el PDF:', error.message);
      }
    },
    error: function (xhr, status, error) {
      toastr.error('Hubo un error al obtener el reporte de las incidencias por &aacute;rea.', 'Mensaje de error');
      console.error('Error al realizar la solicitud AJAX:', error);
    }
  });
});

// Funcion para validar que el campo area tenga un valor
function validarCamposIncidenciasArea() {
  var valido = false;
  var mensajeError = '';

  var faltaArea = ($('#areaSeleccionada').val() !== null && $('#areaSeleccionada').val().trim() !== '');

  // Verificar si al menos un campo está lleno
  if (faltaArea) {
    valido = true;
  } else {
    mensajeError = 'Debe seleccionar un &aacute;rea para generar reporte.';
  }

  if (!valido) {
    toastr.warning(mensajeError.trim(), 'Advertencia');
  }

  return valido;
}

