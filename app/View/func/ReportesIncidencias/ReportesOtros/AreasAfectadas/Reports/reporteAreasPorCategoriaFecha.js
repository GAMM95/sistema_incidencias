$(document).ready(function () {
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };
});

// Generación del PDF al hacer clic en el botón Por fecha
$('#reporteAreaMasIncidenciasCategoriaFecha').click(function () {
  const categoria = $("#categoriaSeleccionada").val();
  const fechaInicio = $('#fechaInicioAreaMasAfectada').val();
  const fechaFin = $('#fechaFinAreaMasAfectada').val();

  console.log('Categoria:', categoria);
  console.log('Fecha Inicio:', fechaInicio);
  console.log('Fecha Fin:', fechaFin);

  // Verificar si los campos son validos
  if (!validarCamposAreasMasAfectadasCategoriaFecha()) {
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
    url: 'ajax/ReportesIncidencias/ReportesOtros/AreasAfectadas/getReporteAreaMasIncidenciaCategoriaFecha.php',
    method: 'GET',
    data: {
      categoriaSeleccionada: categoria,
      fechaInicioAreaMasAfectada: fechaInicio,
      fechaFinAreaMasAfectada: fechaFin
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
          const doc = new jsPDF('portrait');
          const logoUrl = './public/assets/escudo.png';

          // Funcion para agregar encabezado
          function addHeader(doc, totalRecords) {
            const pageWidth = doc.internal.pageSize.width;
            const marginX = 10;
            const marginY = 5;
            const logoWidth = 25;
            const logoHeight = 25;
            const reportTitle = ' ÁREAS CON MÁS INCIDENCIAS';
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

          // Función para agregar el nombre de la categoría y fechas
          function addCategoryAndDates(doc) {
            const pageWidth = doc.internal.pageSize.width;
            const labels = {
              fechaInicio: 'Fecha de Inicio: ',
              fechaFin: 'Fecha Fin: ',
              categoria: 'Categoría:'
            };

            // Obtener valores
            const categoriaNombre = $('#nombreCategoriaSeleccionada').val() || '-';
            const fechaInicioOriginal = $('#fechaInicioAreaMasAfectada').val();
            const fechaFinOriginal = $('#fechaFinAreaMasAfectada').val();

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

            // Dibujar datos de Categoria
            doc.setFont('helvetica', 'bold');
            doc.setFontSize(11);

            // Categoria
            const categoriaLabelWidth = doc.getTextWidth(labels.categoria);
            const categoriaValueWidth = doc.getTextWidth(` ${categoriaNombre}`);
            const totalCategoriaWidth = categoriaLabelWidth + categoriaValueWidth;
            const startXCategoria = (pageWidth - totalCategoriaWidth) / 2;
            const titleYCategoria = 27;
            doc.text(labels.categoria, startXCategoria, titleYCategoria);
            doc.setFont('helvetica', 'normal');
            doc.text(` ${categoriaNombre}`, startXCategoria + categoriaLabelWidth, titleYCategoria);

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

          // Funcion para agregar la tablz de datos
          function addTable(doc) {
            let item = 1;
            doc.autoTable({
              startY: 38, // Altura de la tabla respecto a la parte superior
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
          addHeader(doc, totalRecords);
          addCategoryAndDates(doc);
          addTable(doc);

          // Agregar pie de página en todas las páginas
          const totalPages = doc.internal.getNumberOfPages();
          for (let i = 1; i <= totalPages; i++) {
            doc.setPage(i);
            addFooter(doc, i, totalPages);
          }
          // Establecer las propiedades del documento
          doc.setProperties({
            title: "Reporte de áreas afectadas por categoría y fechas.pdf"
          });
          // Mostrar mensaje de exito de pdf generado
          toastr.success('Reporte de las &aacute;reas con m&aacute;s incidencias por caregor&iacute;a y fechas ingresadas generado.', 'Mensaje');

          // Retrasar la apertura del PDF y limpiar el campo de entrada
          setTimeout(() => {
            window.open(doc.output('bloburl'), '_blank');
          }, 2000);
        } else {
          toastr.warning('No se ha encontrado datos para los campos ingresados.', 'Advertencia');
        }
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

// Funcion para validar que el campo categoria tenga un valor
function validarCamposAreasMasAfectadasCategoriaFecha() {
  var valido = false;
  var mensajeError = '';

  var faltaCategoria = ($('#categoriaSeleccionada').val() !== null && $('#categoriaSeleccionada').val().trim() !== '');
  var fechaInicioSeleccionada = ($('#fechaInicioAreaMasAfectada').val() !== null && $('#fechaInicioAreaMasAfectada').val().trim() !== '');
  var fechaFinSeleccionada = ($('#fechaFinAreaMasAfectada').val() !== null && $('#fechaFinAreaMasAfectada').val().trim() !== '');

  // Verificar si al menos un campo está lleno
  if (faltaCategoria && fechaInicioSeleccionada && fechaFinSeleccionada) {
    valido = true;
  } else {
    mensajeError = 'Debe seleccionar una categor&iacute;a e ingresar el rango de fechas para generar el reporte.';
  }

  if (!valido) {
    toastr.warning(mensajeError.trim());
  }

  return valido;
}