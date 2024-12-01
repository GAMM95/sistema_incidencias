$(document).ready(function () {
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };
});

// Generación del PDF al hacer clic en el botón Por fecha
$('#reporteAreaMasIncidencias').click(function () {

  // Realizar una solicitud AJAX para obtener los datos del cierre de incidencia
  $.ajax({
    url: 'ajax/ReportesIncidencias/ReportesOtros/AreasAfectadas/getReporteTotalAreaMasAfectada.php',
    method: 'GET',
    dataType: 'json',
    success: function (data) {
      console.log("Datos recibidos:", data);
      if (data.error) {
        toastr.warning('No se encontraron datos para generar el reporte.', 'Advertencia');
        return;
      }

      try {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('portrait');
        const logoUrl = './public/assets/escudo.png';

        // Funcion para agregar encabezado
        function addHeader(doc, totalRecords) {
          doc.setFontSize(9);
          doc.setFont('helvetica', 'normal');
          const fechaImpresion = new Date().toLocaleDateString();
          const headerText2 = 'Subgerencia de Informática y Sistemas';
          const reportTitle = 'REPORTE TOTAL ÁREAS AFECTADAS';

          const pageWidth = doc.internal.pageSize.width;
          const marginX = 10;
          const marginY = 5;
          const logoWidth = 25;
          const logoHeight = 25;

          // Agregar logo
          doc.addImage(logoUrl, 'PNG', marginX, marginY, logoWidth, logoHeight);

          // Titulo principal
          doc.setFont('helvetica', 'bold');
          doc.setFontSize(12);
          const titleWidth = doc.getTextWidth(reportTitle);
          const titleX = (pageWidth - titleWidth) / 2;
          const titleY = 20;
          doc.text(reportTitle, titleX, titleY);
          doc.setLineWidth(0.5);
          doc.line(titleX, titleY + 1, titleX + titleWidth, titleY + 1);

          // Subtítulo: cantidad de registros
          const subtitleText = `Cantidad de registros: ${totalRecords}`;
          doc.setFontSize(11);
          const subtitleWidth = doc.getTextWidth(subtitleText);
          const subtitleX = (pageWidth - subtitleWidth) / 2;
          doc.text(subtitleText, subtitleX, marginY + 25);

          // Fecha de impresion 
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
        // addHeader(doc);
        addHeader(doc, data.length);

        // Funcion para agregar el pie de pagina
        function addFooter(doc, pageNumber, totalPages) {
          doc.setFontSize(8);
          doc.setFont('helvetica', 'italic');
          const footerY = 285;
          doc.setLineWidth(0.5);
          doc.line(10, footerY - 5, doc.internal.pageSize.width - 10, footerY - 5);

          const footerText = 'Sistema de Gestión de Incidencias';
          const pageInfo = `Página ${pageNumber} de ${totalPages}`;
          doc.text(footerText, 10, footerY);
          doc.text(pageInfo, doc.internal.pageSize.width - 10 - doc.getTextWidth(pageInfo), footerY);
        }

        // Funcion para agregar la tablz de datos
        function addTable(doc) {
          let item = 1;
          doc.autoTable({
            startY: 35, // Altura de la tabla respecto a la parte superior
            margin: { left: 10 },
            head: [['N°', 'ÁREA', 'CANTIDAD']],
            body: data.map(reporte => [
              item++,
              reporte.areaMasIncidencia,
              reporte.cantidadIncidencias,
            ]),
            styles: {
              fontSize: 10,
              cellPadding: 3,
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
              0: { cellWidth: 30 }, // Ancho para la columna item
              1: { cellWidth: 100 }, // Ancho para la columna area
              2: { cellWidth: 60 }, // Ancho para la columna cantidad
            }
          });
        }

        // Agregar encabezado, dato de la categoriaSeleccionada, tabla y pie de página

        addTable(doc);

        // Agregar pie de página en todas las páginas
        const totalPages = doc.internal.getNumberOfPages();
        for (let i = 1; i <= totalPages; i++) {
          doc.setPage(i);
          addFooter(doc, i, totalPages);
        }
        // Mostrar mensaje de exito de pdf generado
        toastr.success('Reporte total de las &aacute;reas con m&aacute;s incidencias generado.', 'Mensaje');

        // Retrasar la apertura del PDF y limpiar el campo de entrada
        setTimeout(() => {
          window.open(doc.output('bloburl'));
        }, 2000);
      } catch (error) {
        toastr.error('Hubo un error al generar reporte.', 'Mensaje de error');
        console.error('Error al generar el PDF:', error.message);
      }
    },
    error: function (xhr, status, error) {
      toastr.error('Hubo un error al obtener &aacute;reas con m&aacute;s incidencias.', 'Mensaje de error');
      console.error('Error al realizar la solicitud AJAX:', error);
    }
  });
});
