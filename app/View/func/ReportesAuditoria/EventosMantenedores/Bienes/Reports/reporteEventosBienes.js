$(document).ready(function () {
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };

  // Generacion del PDF al hacer clic en botón de "Totales"
  $('#reporteEventosBienesTotales').click(function () {
    // Realizar la solicitud AJAX para obtener los datos
    $.ajax({
      url: 'ajax/ReportesAuditoria/EventosMantenedores/Bienes/getReporteAuditoriaBienesTotales.php',
      method: 'GET',
      dataType: 'json',
      success: function (data) {
        console.log(data);
        if (data.length === 0) {
          toastr.warning('No se encontraron datos para generar el reporte.', 'Advertencia');
          return;
        }

        try {
          const { jsPDF } = window.jspdf;
          const doc = new jsPDF('landscape'); // Orientación horizontal

          const logoUrl = './public/assets/escudo.png';

          function addHeader(doc, totalRecords) {
            doc.setFontSize(9);
            doc.setFont('helvetica', 'normal');

            const fechaImpresion = new Date().toLocaleDateString();
            const headerText2 = 'Subgerencia de Informática y Sistemas';
            const reportTitle = 'REPORTE DE EVENTOS DE BIENES';

            const pageWidth = doc.internal.pageSize.width;
            const marginX = 10;
            const marginY = 5;
            const logoWidth = 25;
            const logoHeight = 25;

            doc.addImage(logoUrl, 'PNG', marginX, marginY, logoWidth, logoHeight);

            doc.setFont('helvetica', 'bold');
            doc.setFontSize(16);
            const titleWidth = doc.getTextWidth(reportTitle);
            const titleX = (pageWidth - titleWidth) / 2;
            const titleY = 20;
            doc.text(reportTitle, titleX, titleY);
            doc.setLineWidth(0.5);
            doc.line(titleX, titleY + 1, titleX + titleWidth, titleY + 1);

            // Agregar subtítulo cantidad de registros
            const subtitleText = `Cantidad de registros: ${totalRecords}`;
            doc.setFontSize(12);
            const subtitleWidth = doc.getTextWidth(subtitleText);
            const subtitleX = (pageWidth - subtitleWidth) / 2;
            const subtitleY = titleY + 8; // Ajuste de posición debajo del título
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

          // addHeader(doc);
          addHeader(doc, data.length);

          const titleY = 45;
          doc.setFont('helvetica', 'bold');
          doc.setFontSize(12);
          doc.text('Detalle de la Incidencia:', 20, titleY);

          let item = 1; // Contador para item
          doc.autoTable({
            startY: 35,
            margin: { left: 8, right: 10 },
            head: [['N°', 'FECHA Y HORA', 'USUARIO DE EVENTO', 'OPERACIÓN', 'REFERENCIA', 'IP', 'NOMBRE DEL EQUIPO']],
            body: data.map(reporte => [
              item++,
              reporte.fechaFormateada,
              reporte.UsuarioEvento,
              reporte.AUD_operacion,
              reporte.referencia,
              reporte.AUD_ip,
              reporte.AUD_nombreEquipo
            ]),
            styles: {
              fontSize: 8,
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
              0: { cellWidth: 10 }, // Ancho para la columna item
              1: { cellWidth: 40 }, // Ancho para la columna fecha y hora
              2: { cellWidth: 40 }, // Ancho para la columna nombre del usuario de evento
              3: { cellWidth: 40 }, // Ancho para la columna operacion
              4: { cellWidth: 50 }, // Ancho para la columna referencia
              5: { cellWidth: 52 }, // Ancho para la columna ip del equipo
              6: { cellWidth: 50 }, // Ancho para la columna nombre del equipo
            }
          });

          function addFooter(doc, pageNumber, totalPages) {
            doc.setFontSize(8);
            doc.setFont('helvetica', 'italic');
            const footerY = 200; // Ajuste la posición del pie de página en la orientación horizontal
            doc.setLineWidth(0.5);
            doc.line(10, footerY - 5, doc.internal.pageSize.width - 10, footerY - 5);

            const footerText = 'Sistema de Gestión de Incidencias';
            const pageInfo = `Página ${pageNumber} de ${totalPages}`;
            const pageWidth = doc.internal.pageSize.width;

            doc.text(footerText, 10, footerY);
            doc.text(pageInfo, pageWidth - 10 - doc.getTextWidth(pageInfo), footerY);
          }

          // Pie de pagina
          const totalPages = doc.internal.getNumberOfPages();
          for (let i = 1; i <= totalPages; i++) {
            doc.setPage(i);
            addFooter(doc, i, totalPages);
          }
          // Establecer las propiedades del documento PDF
          doc.setProperties({
            title: 'Reporte total de eventos de bienes.pdf',
          });
          // Mostrar mensaje de exito de pdf generado
          toastr.success('Reporte total de eventos de bienes generado.', 'Mensaje');
          // Retrasar la apertura del PDF y limpiar el campo de entrada
          setTimeout(() => {
            window.open(doc.output('bloburl'), '_blank');
          }, 2000);
        } catch (error) {
          toastr.error('Hubo un error al generar el reporte total eventos de bienes.', 'Mensaje de error');
          console.error('Error al generar el PDF:', error.message);
        }
      },
      error: function (xhr, status, error) {
        toastr.error('Hubo un error al obtener el reporte total de eventos de bienes.', 'Mensaje de error');
        console.error('Error en AJAX:', xhr.responseText, 'Status:', status, 'Error:', error);
      }
    });
  });
});
