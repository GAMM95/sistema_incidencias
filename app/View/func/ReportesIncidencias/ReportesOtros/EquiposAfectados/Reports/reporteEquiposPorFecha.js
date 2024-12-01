$(document).ready(function () {
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };
});

// Generación del PDF al hacer clic en el botón Por fecha
$('#reporteEquiposAfectadosFecha').click(function () {
  const fechaInicio = $('#fechaInicioIncidenciasEquipos').val();
  const fechaFin = $('#fechaFinIncidenciasEquipos').val();

  console.log('Fecha Inicio:', fechaInicio);
  console.log('Fecha Fin:', fechaFin);

  // Verificar si los campos son validos
  if (!validarCamposEquiposMasAfectadosFecha()) {
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
    url: 'ajax/ReportesIncidencias/ReportesOtros/EquiposAfectados/getReporteEquipoMasAfectadoFecha.php',
    method: 'GET',
    data: {
      fechaInicioIncidenciasEquipos: fechaInicio,
      fechaFinIncidenciasEquipos: fechaFin
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
        toastr.warning('No se encontraron datos para el rango de fecha ingresado.', 'Advertencia');
        return;
      }

      try {
        if (data.length > 0) {
          const { jsPDF } = window.jspdf;
          const doc = new jsPDF('portrait');
          const logoUrl = './public/assets/escudo.png';

          // Funcion para agregar encabezado
          function addHeader(doc) {
            doc.setFontSize(9);
            doc.setFont('helvetica', 'normal');

            const fechaImpresion = new Date().toLocaleDateString();
            const headerText2 = 'Subgerencia de Informática y Sistemas';
            const reportTitle = ' EQUIPOS CON MÁS INCIDENCIAS';

            const pageWidth = doc.internal.pageSize.width;
            const marginX = 10;
            const marginY = 5;
            const logoWidth = 25;
            const logoHeight = 25;

            doc.addImage(logoUrl, 'PNG', marginX, marginY, logoWidth, logoHeight);

            // TITULO CENTRAL DEL DOCUMENTO
            doc.setFont('helvetica', 'bold');
            doc.setFontSize(15);
            const titleWidth = doc.getTextWidth(reportTitle);
            const titleX = (pageWidth - titleWidth) / 2;
            const titleY = 15;
            doc.text(reportTitle, titleX, titleY);
            doc.setLineWidth(0.5); // Ancho de subrayado
            doc.line(titleX, titleY + 1, titleX + titleWidth, titleY + 1); // ubicacion del subrayado del titulo

            // Fecha de impresion 
            doc.setFontSize(8);
            doc.setFont('helvetica', 'normal');
            const fechaText = `Fecha de impresión: ${fechaImpresion}`;
            const headerText2Width = doc.getTextWidth(headerText2);
            const fechaTextWidth = doc.getTextWidth(fechaText);
            const headerText2X = pageWidth - marginX - headerText2Width;
            const fechaTextX = pageWidth - marginX - fechaTextWidth;
            // Ajustar las posiciones Y para mover los textos hacia arriba
            const headerText2Y = marginY + 8; // Mover más cerca de la parte superior
            const fechaTextY = headerText2Y + 4; // Espaciado entre los dos textos

            doc.text(headerText2, headerText2X, headerText2Y);
            doc.text(fechaText, fechaTextX, fechaTextY);
          }

          addHeader(doc);


          // Subtitulos de fechas
          const fechaInicioText = 'Fecha de Inicio:';
          const fechaFinText = 'Fecha de Fin:';

          // Obtener las fechas en formato original
          const fechaInicioOriginal = $('#fechaInicioIncidenciasEquipos').val();
          const fechaFinOriginal = $('#fechaFinIncidenciasEquipos').val();

          // Función para formatear la fecha a dd/mm/aaaa
          function formatearFecha(fecha) {
            const partes = fecha.split('-'); // Suponiendo que las fechas están en formato aaaa-mm-dd
            return `${partes[2]}/${partes[1]}/${partes[0]}`; // Retorna dd/mm/aaaa
          }

          // Formatear las fechas
          const fechaInicioValue = ` ${formatearFecha(fechaInicioOriginal)}`;
          const fechaFinValue = ` ${formatearFecha(fechaFinOriginal)}`;

          // Configuracion de fuentes
          doc.setFont('helvetica', 'bold');
          doc.setFontSize(10);

          // Calcular el ancho de los textos
          const fechaInicioAncho = doc.getTextWidth(fechaInicioText);
          const fechaInicioValueAncho = doc.getTextWidth(fechaInicioValue);
          const fechaFinAncho = doc.getTextWidth(fechaFinText);
          const fechaFinValueAncho = doc.getTextWidth(fechaFinValue);

          const spacing = 10; //espacio entre los dos textos

          // Calcular el ancho total de los textos más el espaciado
          const totalWidth = fechaInicioAncho + fechaInicioValueAncho + spacing + fechaFinAncho + fechaFinValueAncho;

          // Ancho de la página
          const pageWidth = doc.internal.pageSize.width;

          // Calcular la posición inicial en X para centrar los textos
          const startX = (pageWidth - totalWidth) / 2;

          const titleY = 25; // La misma posición Y para ambos textos

          // Dibujar el texto "Fecha de Inicio" y su valor
          doc.text(fechaInicioText, startX, titleY);
          doc.setFont('helvetica', 'normal');
          doc.text(fechaInicioValue, startX + fechaInicioAncho, titleY);

          // Dibujar el texto "Fecha de Fin" y su valor
          doc.setFont('helvetica', 'bold');
          doc.text(fechaFinText, startX + fechaInicioAncho + fechaInicioValueAncho + spacing, titleY);
          doc.setFont('helvetica', 'normal');
          doc.text(fechaFinValue, startX + fechaInicioAncho + fechaInicioValueAncho + spacing + fechaFinAncho, titleY);

          // Inicializar el contador
          let item = 1;

          // Lista de incidencias por codigo patrimonial
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

          function addFooter(doc, pageNumber, totalPages) {
            doc.setFontSize(8);
            doc.setFont('helvetica', 'italic');
            const footerY = 285;
            doc.setLineWidth(0.5);
            doc.line(10, footerY - 5, doc.internal.pageSize.width - 10, footerY - 5);

            const footerText = 'Sistema de Gestión de Incidencias';
            const pageInfo = `Página ${pageNumber} de ${totalPages}`;
            const pageWidth = doc.internal.pageSize.width;

            doc.text(footerText, 10, footerY);
            doc.text(pageInfo, pageWidth - 10 - doc.getTextWidth(pageInfo), footerY);
          }

          const totalPages = doc.internal.getNumberOfPages();
          for (let i = 1; i <= totalPages; i++) {
            doc.setPage(i);
            addFooter(doc, i, totalPages);
          }

          // Mostrar mensaje de exito de pdf generado
          toastr.success('Reporte de los equipos m&aacute;s afectados por fecha generado.', 'Mensaje');
          // Retrasar la apertura del PDF y limpiar el campo de entrada
          setTimeout(() => {
            window.open(doc.output('bloburl'));
          }, 2000);
        } else {
          toastr.warning('No se ha encontrado equipos con m&aacute;s incidencias para las fechas seleccionadas.', 'Advertencia');
        }
      } catch (error) {
        toastr.error('Hubo un error al generar reporte.', 'Mensaje de error');
        console.error('Error al generar el PDF:', error.message);
      }
    },
    error: function (xhr, status, error) {
      toastr.error('Hubo un error al obtener equipos con m&aacute;s incidencias.', 'Mensaje de error');
      console.error('Error al realizar la solicitud AJAX:', error);
    }
  });


  function validarCamposEquiposMasAfectadosFecha() {
    var valido = false;
    var mensajeError = '';

    var fechaInicioSeleccionada = ($('#fechaInicioIncidenciasEquipos').val() !== null && $('#fechaInicioIncidenciasEquipos').val().trim() !== '');
    var fechaFinSeleccionada = ($('#fechaFinIncidenciasEquipos').val() !== null && $('#fechaFinIncidenciasEquipos').val().trim() !== '');

    // Verificar si al menos un campo está lleno
    if (fechaInicioSeleccionada || fechaFinSeleccionada) {
      valido = true;
    } else {
      mensajeError = 'Debe ingresar las fechas para generar reporte.';
    }

    if (!valido) {
      toastr.warning(mensajeError.trim());
    }

    return valido;
  }


  function validarFechasEquiposMasAfectadosFecha() {
    // Obtener valores de los campos de fecha
    const fechaInicio = new Date($('#fechaInicioIncidenciasEquipos').val());
    const fechaFin = new Date($('#fechaFinIncidenciasEquipos').val());

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
  $('#fechaInicioIncidenciasEquipos, #fechaFinIncidenciasEquipos').on('change', function () {
    validarFechasEquiposMasAfectadosFecha();
  });
});
