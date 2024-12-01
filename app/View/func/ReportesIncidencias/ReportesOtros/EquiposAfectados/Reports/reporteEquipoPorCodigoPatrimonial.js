$(document).ready(function () {
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };
});

// Generación del PDF al hacer clic en el botón Por fecha
$('#reporteEquiposAfectadosCodigoPatrimonial').click(function () {
  const equipo = $("#codigoEquipo").val();

  console.log('Equipo:', equipo);

  // Verificar si los campos son validos
  if (!validarCamposEquiposMasAfectadosCodPatrimonial()) {
    return; // Detiene el envío si los campos fechas no son válidos
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

  // Realizar una solicitud AJAX para obtener los datos del cierre de incidencia
  $.ajax({
    url: 'ajax/ReportesIncidencias/ReportesOtros/EquiposAfectados/getReporteEquipoMasAfectadoCodPatrimonial.php',
    method: 'GET',
    data: {
      codigoEquipo: equipo
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
        toastr.warning('No se encontraron datos para c&oacute;digo patrimonial ingresado.', 'Advertencia');
        return;
      }

      try {
        if (data.length > 0) {
          const { jsPDF } = window.jspdf;
          const doc = new jsPDF('portrait');
          const logoUrl = './public/assets/escudo.png';

          // Funcion para agregar encabezado
          function addHeader(doc, totalRecords) {
            const pageWidth = doc.internal.pageSize.width;
            const marginX = 10;
            const marginY = 5;
            const logoWidth = 25;
            const logoHeight = 25;
            const reportTitle = 'EQUIPO AFECTADO';
            const headerText2 = 'Subgerencia de Informática y Sistemas';
            const fechaImpresion = new Date().toLocaleDateString();

            // Agregar logo
            doc.addImage(logoUrl, 'PNG', marginX, marginY, logoWidth, logoHeight);

            // Titulo principal
            doc.setFont('helvetica', 'bold');
            doc.setFontSize(13);
            const titleWidth = doc.getTextWidth(reportTitle);
            const titleX = (pageWidth - titleWidth) / 2;
            doc.text(reportTitle, titleX, marginY + 10);
            doc.setLineWidth(0.5); // Ancho de subrayado
            doc.line(titleX, marginY + 11, titleX + titleWidth, marginY + 11);

            // Subtítulo: cantidad de registros
            const subtitleText = `Cantidad de registros: ${totalRecords}`;
            doc.setFontSize(11);
            const subtitleWidth = doc.getTextWidth(subtitleText);
            const subtitleX = (pageWidth - subtitleWidth) / 2;
            doc.text(subtitleText, subtitleX, marginY + 17);

            // Fecha de impresion 
            doc.setFontSize(8);
            const headerText2Width = doc.getTextWidth(headerText2);
            const fechaText = `Fecha de impresión: ${fechaImpresion}`;
            const fechaTextWidth = doc.getTextWidth(fechaText);
            doc.text(headerText2, pageWidth - marginX - headerText2Width, marginY + logoHeight / 2);
            doc.text(fechaText, pageWidth - marginX - fechaTextWidth, marginY + logoHeight / 2 + 5);
          }

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

          // Función para agregar el nombre de la equipo
          function addEquipo(doc) {
            const pageWidth = doc.internal.pageSize.width;
            const labels = {
              equipo: 'Código patrimonial: '
            };

            // Obtener valores
            const codigoPatrimonial = $('#codigoEquipo').val() || '-';

            // Dibujar datos de la equipo
            doc.setFont('helvetica', 'bold');
            doc.setFontSize(11);

            // Equipo
            const equipoLabelWidth = doc.getTextWidth(labels.equipo);
            const equipoValueWidth = doc.getTextWidth(` ${codigoPatrimonial}`);
            const totalEquipoWidth = equipoLabelWidth + equipoValueWidth;
            const startXEquipo = (pageWidth - totalEquipoWidth) / 2;
            const titleYEquipo = 29;
            doc.text(labels.equipo, startXEquipo, titleYEquipo);
            doc.setFont('helvetica', 'normal');
            doc.text(` ${codigoPatrimonial}`, startXEquipo + equipoLabelWidth, titleYEquipo);
          }


          // Funcion para agregar la tablz de datos
          function addTable(doc) {
            let item = 1;
            doc.autoTable({
              startY: 35, // Altura de la tabla respecto a la parte superior
              margin: { left: 10 },
              head: [['N°', 'CÓDIGO PATRIMONIAL', 'NOMBRE DE BIEN', 'ÁREA', 'CANTIDAD']],
              body: data.map(reporte => [
                item++,
                reporte.codigoPatrimonial,
                reporte.nombreBien,
                reporte.nombreArea,
                reporte.cantidadIncidencias
              ]),
              styles: {
                fontSize: 9.5,
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
                0: { cellWidth: 15 }, // Ancho para la columna item
                1: { cellWidth: 30 }, // Ancho para la columna codigo patrimonial
                2: { cellWidth: 60 }, // Ancho para la columna nombre bien
                3: { cellWidth: 60 }, // Ancho para la columna nombre area
                4: { cellWidth: 25 } // Ancho para la columna cantidad
              }
            });
          }

          // Agregar encabezado, dato de la categoriaSeleccionada, tabla y pie de página
          addHeader(doc, totalRecords);
          addEquipo(doc);
          addTable(doc);

          // Agregar pie de página en todas las páginas
          const totalPages = doc.internal.getNumberOfPages();
          for (let i = 1; i <= totalPages; i++) {
            doc.setPage(i);
            addFooter(doc, i, totalPages);
          }
          // Mostrar mensaje de exito de pdf generado
          toastr.success('Reporte de equipos afectados por c&oacute;digo patrimonial generado.', 'Mensaje');

          // Retrasar la apertura del PDF y limpiar el campo de entrada
          setTimeout(() => {
            window.open(doc.output('bloburl'));
          }, 2000);
        } else {
          toastr.warning('No se ha encontrado incidencias para el c&oacute;digo patrimonial ingresado.', 'Advertencia');
        }
      } catch (error) {
        toastr.error('Hubo un error al generar reporte.', 'Mensaje de error');
        console.error('Error al generar el PDF:', error.message);
      }
    },
    error: function (xhr, status, error) {
      toastr.error('Hubo un error al obtener incidencias para el c&oacute;digo patrimonial ingresado.', 'Mensaje de error');
      console.error('Error al realizar la solicitud AJAX:', error);
    }
  });
});

// Funcion para validar que el campo categoria tenga un valor
function validarCamposEquiposMasAfectadosCodPatrimonial() {
  var valido = false;
  var mensajeError = '';

  var faltaCodigo = ($('#codigoEquipo').val() !== null && $('#codigoEquipo').val().trim() !== '');

  // Verificar si al menos un campo está lleno
  if (faltaCodigo) {
    valido = true;
  } else {
    mensajeError = 'Debe ingresar c&oacute;digo patrimonial para generar el reporte.';
  }

  if (!valido) {
    toastr.warning(mensajeError.trim());
  }

  return valido;
}