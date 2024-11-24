$(document).ready(function () {
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };
});

$('#reporteEventosTotalesFiltro').click(function () {
  const usuario = $("#personaEventosTotales").val();
  const fechaInicio = $('#fechaInicioEventosTotales').val();
  const fechaFin = $('#fechaFinEventosTotales').val();

  console.log('Usuario:', usuario);
  console.log('Fecha Inicio:', fechaInicio);
  console.log('Fecha Fin:', fechaFin);


  if (!validarCamposEventosTotalesFiltro()) {
    return;
  }

  // Realizar una solicitud AJAX para obtener los datos de la incidencia
  $.ajax({
    url: 'ajax/getReporteEventosTotalesFiltro.php',
    method: 'GET',
    data: { personaEventosTotales: usuario, fechaInicioEventosTotales: fechaInicio, fechaFinEventosTotales: fechaFin },
    dataType: 'json',
    success: function (data) {
      console.log("Datos recibidos:", data);

      if (data.error) {
        toastr.error('Error en la solicitud: ' + data.error);
        return;
      }

      // Obtener la cantidad de registros
      const totalRecords = data.length;

      try {
        if (data.length > 0) {
          const { jsPDF } = window.jspdf;
          const doc = new jsPDF('landscape');

          const logoUrl = './public/assets/escudo.png';

          function addHeader(doc, totalRecords) {
            doc.setFontSize(9);
            doc.setFont('helvetica', 'normal');

            const fechaImpresion = new Date().toLocaleDateString();
            const headerText2 = 'Subgerencia de Informática y Sistemas';
            const reportTitle = 'REPORTE FILTRADO DE EVENTOS TOTALES';

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

            // Agregar subtítulo cantidad de registros
            const subtitleText = `Cantidad de registros: ${totalRecords}`;
            doc.setFontSize(11);
            const subtitleWidth = doc.getTextWidth(subtitleText);
            const subtitleX = (pageWidth - subtitleWidth) / 2;
            const subtitleY = titleY + 15; // Ajuste de posición debajo del título
            doc.text(subtitleText, subtitleX, subtitleY);

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

          addHeader(doc, totalRecords);

          // Subtitulos de fechas
          const fechaInicioText = 'Fecha de Inicio:';
          const fechaFinText = 'Fecha Fin:';
          const usuarioSeleccionadoText = 'Usuario:';

          // Obtener las fechas en formato original
          let fechaInicioOriginal = $('#fechaInicioEventosTotales').val();
          let fechaFinOriginal = $('#fechaFinEventosTotales').val();
          let usuarioSeleccionadoOriginal = $('#personaEventosTotales').val();

          // Función para formatear la fecha a dd/mm/aaaa
          function formatearFecha(fecha) {
            if (!fecha) return '-'; // Si la fecha está vacía, retorna '-'
            const partes = fecha.split('-'); // Suponiendo que las fechas están en formato aaaa-mm-dd
            return `${partes[2]}/${partes[1]}/${partes[0]}`; // Retorna dd/mm/aaaa
          }

          // Verificar si los valores están vacíos y asignar '-'
          const fechaInicioValue = fechaInicioOriginal ? ` ${formatearFecha(fechaInicioOriginal)}` : ' -';
          const fechaFinValue = fechaFinOriginal ? ` ${formatearFecha(fechaFinOriginal)}` : ' -';
          const usuarioSeleccionadoValue = usuarioSeleccionadoOriginal || ' -'; // Si el usuario está vacío, asigna '-'

          // Configuracion de fuentes
          doc.setFont('helvetica', 'bold');
          doc.setFontSize(11);

          // Calcular el ancho de los textos
          const fechaInicioAncho = doc.getTextWidth(fechaInicioText);
          const fechaInicioValueAncho = doc.getTextWidth(fechaInicioValue);
          const fechaFinAncho = doc.getTextWidth(fechaFinText);
          const fechaFinValueAncho = doc.getTextWidth(fechaFinValue);

          const spacing = 15; //espacio entre los dos textos

          // Calcular el ancho total de los textos más el espaciado
          const totalWidth = fechaInicioAncho + fechaInicioValueAncho + spacing + fechaFinAncho + fechaFinValueAncho;

          // Ancho de la página
          const pageWidth = doc.internal.pageSize.width;

          // Calcular la posición inicial en X para centrar los textos
          const startX = (pageWidth - totalWidth) / 2;

          const titleY = 23; // La misma posición Y para ambos textos

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
              fillColor: [9, 4, 6],
              textColor: [255, 255, 255],
              fontStyle: 'bold',
              halign: 'center'
            },
            columnStyles: {
              0: { cellWidth: 8 }, // Ancho para la columna item
              1: { cellWidth: 30 }, // Ancho para la columna fecha y hora
              2: { cellWidth: 35 }, // Ancho para la columna evento
              3: { cellWidth: 25 }, // Ancho para la columna tabla afectada
              4: { cellWidth: 20 }, // Ancho para la columna rol del usuario
              5: { cellWidth: 25 }, // Ancho para la columna username
              6: { cellWidth: 38 }, // Ancho para la columna nombre del usuario
              7: { cellWidth: 42 }, // Ancho para la columna area del usuario
              8: { cellWidth: 30 }, // Ancho para la columna ip del equipo
              9: { cellWidth: 35 }, // Ancho para la columna nombre del equipo
            }
          });

          function addFooter(doc, pageNumber, totalPages) {
            doc.setFontSize(8);
            doc.setFont('helvetica', 'italic');
            const footerY = 200;
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
          toastr.success('Reporte filtrado de eventos generado.', 'Mensaje');
          // Retrasar la apertura del PDF y limpiar el campo de entrada
          setTimeout(() => {
            window.open(doc.output('bloburl'));
            $('#fechaInicioEventosTotales').val('');
            $('#fechaFinEventosTotales').val('');
            $('#personaEventosTotales').val('');
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


  
  function validarCamposEventosTotalesFiltro() {
    var valido = false;
    var mensajeError = '';

    var faltaUsuario = ($('#personaEventosTotales').val() !== null && $('#personaEventosTotales').val().trim() !== '');
    var fechaInicioSeleccionada = ($('#fechaInicioEventosTotales').val() !== null && $('#fechaInicioEventosTotales').val().trim() !== '');
    var fechaFinSeleccionada = ($('#fechaFinEventosTotales').val() !== null && $('#fechaFinEventosTotales').val().trim() !== '');

    // Verificar si al menos un campo está lleno
    if (faltaUsuario && fechaInicioSeleccionada || fechaFinSeleccionada) {
      mensajeError = 'Debe completar al menos un campo para realizar la b&uacute;squeda.';
      valido = true;
    } else if(faltaUsuario) {
      mensajeError = 'Debe seleccionar un usuario para realizar la b&uacute;squeda.';
      valido = true;
    } else if(fechaInicioSeleccionada || fechaFinSeleccionada) {
      mensajeError = 'Debe ingresar al menos un campo para realizar la b&uacute;squeda.';
      valido = true; 
    }

    if (!valido) {
      toastr.warning(mensajeError.trim(), 'Advertencia');
    }

    return valido;
  }


  // function validarFechasEventosTotalesFiltro() {
  //   // Obtener valores de los campos de fecha
  //   const fechaInicio = new Date($('#fechaInicioEventosTotales').val());
  //   const fechaFin = new Date($('#fechaFinEventosTotales').val());

  //   // Obtener la fecha actual
  //   const fechaHoy = new Date();

  //   // Validar la fecha de inicio y fin
  //   let valido = true;
  //   let mensajeError = '';

  //   // Bloquear fechas posteriores a la fecha actual
  //   if (fechaInicio > fechaHoy) {
  //     mensajeError = 'La fecha de inicio no puede ser posterior a la fecha actual.';
  //     valido = false;
  //   }

  //   if (fechaFin > fechaHoy) {
  //     mensajeError = 'La fecha fin no puede ser posterior a la fecha actual.';
  //     valido = false;
  //   }

  //   // Verificar que la fecha de fin sea posterior a la fecha de inicio
  //   if (fechaInicio && fechaFin && fechaFin < fechaInicio) {
  //     mensajeError = 'La fecha fin debe ser posterior a la fecha de inicio.';
  //     valido = false;
  //   }

  //   // Mostrar mensaje de error con Toastr si la validación falla
  //   if (!valido) {
  //     toastr.warning(mensajeError.trim(), 'Advertencia');
  //   }

  //   return valido;
  // }

  // // Agregar eventos para validar fechas cuando cambien
  // $('#fechaInicioEventosTotales, #fechaFinEventosTotales').on('change', function () {
  //   validarFechasEventosTotalesFiltro();
  // });

});


  // function validarCamposEventosTotalesFiltro() {
  //   var valido = false;
  //   var mensajeError = '';
  
  //   // Verificar si los campos no están vacíos
  //   var fechaInicioSeleccionada = ($('#fechaInicioEventosTotales').val() !== null && $('#fechaInicioEventosTotales').val().trim() !== '');
  //   var fechaFinSeleccionada = ($('#fechaFinEventosTotales').val() !== null && $('#fechaFinEventosTotales').val().trim() !== '');
  //   var usuarioSeleccionado = ($('#personaEventosTotales').val() !== null && $('#personaEventosTotales').val().trim() !== '');
  
  //   // Verificar si al menos uno de los campos tiene datos
  //   if (fechaInicioSeleccionada || fechaFinSeleccionada || usuarioSeleccionado) {
  //     valido = true;
  //   } else {
  //     mensajeError = 'Debe ingresar al menos un campo para generar el reporte (Fecha Inicio, Fecha Fin o Usuario).';
  //   }
  
  //   if (!valido) {
  //     toastr.warning(mensajeError.trim(), 'Advertencia');
  //   }
  
  //   return valido;
  // }
