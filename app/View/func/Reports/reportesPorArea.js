$(document).ready(function () {
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };
});

// Manejar la generación de PDF al hacer clic en el botón
$('#reportes-areas').click(function () {
  const codigoArea = $('#area').val();

  if (!codigoArea) {
    toastr.warning('Seleccione un &aacute;rea para generar el reporte.', 'Advertencia');
    return;
  }

  // Realizar una solicitud AJAX para obtener los datos de la incidencia
  $.ajax({
    url: 'ajax/getReportePorArea.php',
    method: 'GET',
    data: { area: codigoArea },
    success: function (data) {
      if (data.error) {
        toastr.error('Error en la solicitud: ' + data.error);
        return;
      }
      // Obtener la cantidad de registros
      const totalRecords = data.length;

      // Generar el PDF
      generarPDF(data, totalRecords);
      $('#area').val('').trigger('change');
    },
    error: function (xhr, status, error) {
      toastr.error('Hubo un error al obtener los datos del &aacute;rea seleccionada.', 'Mensaje de error');
      console.error('Error al realizar la solicitud AJAX:', error);
    }
  });
});

function generarPDF(data, totalRecords) {
  if (!data || data.length === 0) {
    toastr.warning('No se encontr&oacute; informaci&oacute;n para el &aacute;rea seleccionada.', 'Advertencia');
    return;
  }

  const { jsPDF } = window.jspdf;
  const doc = new jsPDF('landscape');

  // Añadir encabezado
  const logoUrl = './public/assets/escudo.png';
  addHeaderArea(doc, logoUrl, totalRecords);

  const titleY = 23;
  doc.setFont('helvetica', 'bold');
  doc.setFontSize(11);

  const areaText = 'Área:';
  const codigoWidth = doc.getTextWidth(areaText);
  const codigoValue = ` ${$('#nombreArea').val()}`;
  const codigoValueWidth = doc.getTextWidth(codigoValue);

  const pageWidth = doc.internal.pageSize.width;
  const totalWidth = codigoWidth + codigoValueWidth;
  const startX = (pageWidth - totalWidth) / 2;

  // Dibujar el texto "Código Patrimonial" en negrita
  doc.text(areaText, startX, titleY);

  // Cambiar a estilo normal para el valor del código patrimonial
  doc.setFont('helvetica', 'normal');
  doc.text(codigoValue, startX + codigoWidth, titleY);

  // Añadir tabla de datos
  let item = 1;
  doc.autoTable({
    startY: 35,
    margin: { left: 5 },
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

  // Añadir pie de página
  const totalPages = doc.internal.getNumberOfPages();
  for (let i = 1; i <= totalPages; i++) {
    doc.setPage(i);
    addFooter(doc, i, totalPages);
  }

  // Mostrar mensaje de exito de pdf generado
  toastr.success('Reporte de incidencias para el &aacute;rea seleccionada.', 'Mensaje');

  // Retrasar la apertura del PDF y limpiar el campo de entrada
  setTimeout(() => {
    window.open(doc.output('bloburl'));
    $('#area').val(''); // limpiar combo area
  }, 2000);
}

// Encabezado
function addHeaderArea(doc, logoUrl, totalRecords) {
  doc.setFontSize(9);
  doc.setFont('helvetica', 'normal');
  const fechaImpresion = new Date().toLocaleDateString();
  const headerText2 = 'Subgerencia de Informática y Sistemas';
  const reportTitle = 'REPORTE DE INCIDENCIAS POR ÁREA';

  const pageWidth = doc.internal.pageSize.width;
  const marginX = 10;
  const marginY = 5;
  const logoWidth = 25;
  const logoHeight = 25;

  doc.addImage(logoUrl, 'PNG', marginX, marginY, logoWidth, logoHeight);

  doc.setFont('helvetica', 'bold');
  doc.setFontSize(15);
  const titleWidth = doc.getTextWidth(reportTitle);
  const titleX = (pageWidth - titleWidth) / 2;
  const titleY = 15;
  doc.text(reportTitle, titleX, titleY);
  doc.setLineWidth(0.5);
  doc.line(titleX, titleY + 1, titleX + titleWidth, titleY + 1);

  // Agregar subtítulo cantidad de registros
  const subtitleText = `Cantidad de registros: ${totalRecords}`;
  doc.setFontSize(11);
  const subtitleWidth = doc.getTextWidth(subtitleText);
  const subtitleX = (pageWidth - subtitleWidth) / 2;
  const subtitleY = titleY + 15; // Ajuste de posición debajo del título
  doc.text(subtitleText, subtitleX, subtitleY);

  doc.setFontSize(8);
  doc.setFont('helvetica', 'normal');
  const fechaText = `Fecha de impresión: ${fechaImpresion}`;
  const headerText2Width = doc.getTextWidth(headerText2);
  const fechaTextWidth = doc.getTextWidth(fechaText);
  const headerText2X = pageWidth - marginX - headerText2Width;
  const fechaTextX = pageWidth - marginX - fechaTextWidth;
  const headerText2Y = marginY + logoHeight / 2;
  const fechaTextY = headerText2Y + 5;

  doc.text(headerText2, headerText2X, headerText2Y);
  doc.text(fechaText, fechaTextX, fechaTextY);
}
// Fin de encabezado
