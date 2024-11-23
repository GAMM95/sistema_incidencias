<div class="pcoded-main-container mt-5">
  <div class="pcoded-content">
    <?php
    global $incidenciaRegistrada;
    ?>

    <!-- Miga de pan -->
    <div class="page-header">
      <div class="page-block">
        <div class="row align-items-center">
          <div class="col-md-12">
            <div class="page-header-title">
              <h1 class="text-2xl font-bold mb-2">Registro de incidencias</h1>
            </div>
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href=""><i class="feather icon-edit"></i></a></li>
              <li class="breadcrumb-item"><a href="#">Registros</a></li>
              <li class="breadcrumb-item"><a href="">Incidencias</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin de miga de pan -->

    <!-- Formulario de registro -->
    <form id="formIncidencia" action="registro-incidencia.php?action=registrar" method="POST" class="card table-card  bg-white shadow-md p-6 w-full text-xs mb-2">
      <input type="hidden" id="form-action" name="action" value="registrar">

      <!-- Fila oculta del numero de incidencia -->
      <div class="flex items-center mb-4 hidden">
        <label for="numero_incidencia" class="block font-bold mb-1 mr-1 text-lime-500">N&deg; Incidencia:</label>
        <input type="text" id="numero_incidencia" name="numero_incidencia" class="w-20 border border-gray-200 bg-gray-100 rounded-md p-2 text-xs" readonly>
      </div>

      <!-- Primera fila del formulario -->
      <div class="flex flex-wrap -mx-2">
        <!-- CATEGORIA SELECCIONADA -->
        <div class="w-full sm:w-1/3 px-2 mb-2">
          <label for="categoria" class="block font-bold mb-1">Categor&iacute;a: *</label>
          <select id="cbo_categoria" name="categoria" class="border p-2 w-full text-xs cursor-pointer">
          </select>
        </div>

        <!-- AREA DE LA INCIDENCIA -->
        <div class="w-full sm:w-1/3 px-2 mb-2">
          <label for="area" class="block font-bold mb-1">&Aacute;rea: *</label>
          <select id="cbo_area" name="area" class="border p-2 w-full text-xs cursor-pointer">
          </select>
        </div>

        <!-- FECHA DE LA INCIDENCIA -->
        <div class="w-full sm:w-1/6 px-2 mb-2 hidden">
          <label for="fecha_incidencia" class="block mb-1 font-bold text-xs">Fecha:</label>
          <input type="date" id="fecha_incidencia" name="fecha_incidencia" class="border border-gray-200 bg-gray-100 p-2 w-full text-xs" value="<?php echo date('Y-m-d'); ?>" readonly>
        </div>

        <!-- HORA DE LA INCIDENCIA -->
        <div class="w-full md:w-1/6 px-2 mb-2 hidden">
          <label for="hora" class="block font-bold mb-1">Hora:</label>
          <?php
          // Establecer la zona horaria deseada
          date_default_timezone_set('America/Lima');
          $fecha_actual = date('Y-m-d');
          $hora_actual = date('H:i:s');
          ?>
          <input type="text" id="hora" name="hora" class="border border-gray-200 bg-gray-100 p-2 w-full text-xs" value="<?php echo $hora_actual; ?>" readonly>
        </div>

        <!-- USUARIO QUE ABRE LA INCIDENCIA -->
        <div class="w-full md:w-1/6 px-2 mb-2 hidden">
          <label for="usuarioDisplay" class="block font-bold mb-1">Usuario:</label>
          <input type="text" id="usuarioDisplay" name="usuarioDisplay" class="border border-gray-200 bg-gray-100 p-2 w-full text-xs" value="<?php echo $_SESSION['usuario']; ?>" readonly>
        </div>
        <div class="w-full md:w-1/6 px-2 mb-2 hidden">
          <label for="usuario" class="block font-bold mb-1">Usuario:</label>
          <input type="text" id="usuario" name="usuario" class="border border-gray-200 bg-gray-100 p-2 w-full text-xs" value="<?php echo $_SESSION['codigoUsuario']; ?>" readonly>
        </div>

        <div class="mb-2 sm:w-1/3 flex items-center gap-4 pl-2 pr-2">
          <!-- CODIGO PATROMONIAL -->
          <div class="flex-grow sm:w-1/3">
            <!-- <div class="w-full sm:w-1/3 px-2 mb-2"> -->
            <label for="codigoPatrimonial" class="block mb-1 font-bold text-xs">C&oacute;d. Patrimonial:</label>
            <input type="text" id="codigoPatrimonial" name="codigoPatrimonial"
              class="border p-2 w-full text-xs rounded-md" maxlength="12"
              pattern="\d{1,12}" oninput="this.value = this.value.replace(/[^0-9]/g, '');"
              title="Ingrese solo dígitos"
              placeholder="Ingrese código">
          </div>

          <!-- TIPO DE BIEN -->
          <div class="flex-grow sm:w-2/3">
            <!-- <div class="w-full sm:w-2/3 px-2 mb-2"> -->
            <label for="tipoBien" class="block mb-1 font-bold text-center text-xs">Nombre del bien:</label>
            <input type="text" id="tipoBien" name="tipoBien" class="border p-2 w-full text-center text-xs rounded-md" disabled readonly>
          </div>
        </div>
      </div>

      <!-- SEGUNDA FILA DEL FORMULARIO -->
      <div class="flex flex-wrap -mx-2">

        <!-- ASUNTO DE LA INCIDENCIA -->
        <div class="w-full sm:w-2/3 px-2 mb-2">
          <label for="asunto" class="block mb-1 font-bold text-xs">Asunto: *</label>
          <input type="text" id="asunto" name="asunto" class="border p-2 w-full text-xs rounded-md" placeholder="Ingrese asunto">
        </div>

        <!-- DOCUMENTO DE LA INCIDENCIA -->
        <div class="w-full sm:w-1/3 px-2 mb-2">
          <label for="documento" class="block mb-1 font-bold text-xs">Documento: *</label>
          <input type="text" id="documento" name="documento" class="border p-2 w-full text-xs rounded-md" placeholder="Ingrese documento" oninput="uppercaseInput(this)">
          <script>
            function uppercaseInput(element) {
              element.value = element.value.toUpperCase();
            }
          </script>
        </div>
      </div>

      <!-- TERCERA FILA DEL FORMULARIO -->
      <div class="flex flex-wrap -mx-2">
        <!-- DESCRIPCION DE LA INCIDENCIA -->
        <div class="w-full md:w-1/1 px-2 mb-2">
          <label for="descripcion" class="block mb-1 font-bold text-xs">Descripci&oacute;n:</label>
          <input type="text" id="descripcion" name="descripcion" class="border p-2 w-full text-xs mb-3 rounded-md" placeholder="Ingrese descripci&oacute;n (opcional)">
        </div>
      </div>
      <!-- RECOPILACION DE VALORES DE CADA INPUT Y COMBOBOX     -->
      <script>
        // Asignación de valores predefinidos al cargar la página
        document.getElementById('numero_incidencia').value = '<?php echo $incidenciaRegistrada ? $incidenciaRegistrada['INC_incidencia'] : $fecha_actual; ?>';
        document.getElementById('fecha_incidencia').value = '<?php echo $incidenciaRegistrada ? $incidenciaRegistrada['INC_fecha'] : $fecha_actual; ?>';
        document.getElementById('hora').value = '<?php echo $incidenciaRegistrada ? $incidenciaRegistrada['INC_hora'] : $hora_actual; ?>';
        document.getElementById('cbo_area').value = '<?php echo $incidenciaRegistrada ? $incidenciaRegistrada['ARE_codigo'] : ''; ?>';
        document.getElementById('codigo_patrimonial').value = '<?php echo $incidenciaRegistrada ? $incidenciaRegistrada['INC_codigo_patrimonial'] : ''; ?>';
        document.getElementById('cbo_categoria').value = '<?php echo $incidenciaRegistrada ? $incidenciaRegistrada['CAT_codigo'] : ''; ?>';
        document.getElementById('asunto').value = '<?php echo $incidenciaRegistrada ? $incidenciaRegistrada['INC_asunto'] : ''; ?>';
        document.getElementById('documento').value = '<?php echo $incidenciaRegistrada ? $incidenciaRegistrada['INC_documento'] : ''; ?>';
        document.getElementById('descripcion').value = '<?php echo $incidenciaRegistrada ? $incidenciaRegistrada['INC_descripcion'] : ''; ?>';
      </script>

      <!-- Botones del formulario -->
      <div class="flex justify-center space-x-4">
        <button type="submit" id="guardar-incidencia" class="bn btn-primary text-xs text-white font-bold py-2 px-3 rounded-md disabled:bg-gray-300 disabled:cursor-not-allowed"><i class="feather mr-2 icon-save"></i>Registrar</button>
        <button type="button" id="editar-incidencia" class="bn btn-info text-xs text-white font-bold py-2 px-3 rounded-md disabled:bg-gray-300 disabled:cursor-not-allowed" disabled><i class="feather mr-2 icon-edit"></i>Actualizar</button>
        <button type="button" id="nuevo-registro" class="bn btn-secondary text-xs text-white font-bold py-2 px-3 rounded-md"> <i class="feather mr-2 icon-plus-square"></i>Limpiar</button>
        <!-- <button type="button" id="imprimir-incidencia" class="bn btn-warning text-xs text-white font-bold py-2 px-3 rounded-md"> <i class="feather mr-2 icon-printer"></i>Imprimir </button> -->
        <button type="button" data-toggle="modal" data-target="#modalBuscarIncidencia" id="buscar-incidencia" class="bn bg-orange-500 hover:bg-orange-600 text-xs text-white font-bold py-2 px-3 rounded-md">
          <i class="feather mr-2 icon-search"></i>Buscar incidencia</button>
      </div>

      <!-- Modal Buscar Incidencia -->
      <div class="modal fade" id="modalBuscarIncidencia" tabindex="-1" role="dialog" aria-labelledby="modalBuscarIncidenciaLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
          <div class="modal-content">
            <!-- <div class="modal-header">
              <h5 class="modal-title text-xl font-bold" id="modalBuscarIncidenciaLabel">Buscar Incidencia</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div> -->
            <div class="modal-body">
              <form>
                <div class="justify-center">
                  <label for="nueroIncidencia" class="block font-bold text-xs text-center text-gray-800 mb-2">N&deg; de Incidencia</label>
                  <!-- Campo de texto con conversión a mayúsculas -->
                  <input type="text" id="numeroIncidencia" name="numeroIncidencia"
                    class="border text-center border-gray-200 p-2 w-64 text-xs rounded-md"
                    placeholder="Ingrese N&deg; de incidencia"
                    oninput="uppercaseInput(this)" autofocus>
                  <script>
                    function uppercaseInput(element) {
                      element.value = element.value.toUpperCase();
                    }
                  </script>

                </div>
              </form>
            </div>
            <div class="modal-footer justify-center">
              <button type="button" id="imprimir-detalle-incidencia" class="bn btn-warning text-xs text-white font-bold py-2 px-3 rounded-md"> <i class="feather mr-2 icon-printer"></i>Generar detalle </button>
            </div>
          </div>
        </div>
      </div>
      <!-- Fin Modal Buscar Incidencia -->

    </form>
    <!-- Fin de Formulario -->

    <!-- Paginacion de la tabla -->
    <div id="noIncidencias" class="flex justify-between items-center mb-2">
      <h1 class="text-xl text-gray-400">Nuevas incidencias</h1>
      <!-- Busqueda de termino -->
      <div class="flex justify-between items-center">
        <input type="text" id="termino" class="px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300 text-xs" placeholder="Buscar incidencia..." oninput="filtrarTablaIncidencias()" />
      </div>

    </div>
    <!-- Fin de la paginacion -->

    <!-- TABLA DE INCIDENCIAS REGISTRADAS -->
    <div class="relative max-h-[300px] overflow-x-hidden shadow-md sm:rounded-lg">
      <div class="max-w-full overflow-hidden rounded-lg">
        <table id="tablaListarIncidencias" class="w-full text-xs text-left rtl:text-right text-gray-500 cursor-pointer bg-white">
          <!-- Encabezado de la tabla -->
          <thead class="text-xs text-gray-700 uppercase bg-blue-300">
            <tr>
              <th scope="col" class="px-6 py-2 hidden">N&deg;</th>
              <th scope="col" class="px-6 py-2 text-center">N&deg; Incidencia</th>
              <th scope="col" class="px-4 py-2 text-center">Fecha de entrada</th>
              <th scope="col" class="px-1 py-2 text-center">C&oacute;d. Patrimonial</th> <!-- Ajuste aquí -->
              <th scope="col" class="px-10 py-2 text-center">Asunto</th>
              <th scope="col" class="px-3 py-2 text-center">Documento</th>
              <th scope="col" class="px-6 py-2 text-center">Categor&iacute;a</th>
              <th scope="col" class="px-6 py-2 text-center">&Aacute;rea</th>
              <th scope="col" class="px-6 py-2 hidden">descripcion</th>
              <th scope="col" class="px-6 py-2 hidden">Estado</th>
              <th scope="col" class="px-6 py-2 hidden">Estado</th>
              <th scope="col" class="px-6 py-2 hidden">Usuario</th>
              <th scope="col" class="px-6 py-2 text-center">Acci&oacute;n</th>
            </tr>
          </thead>
          <!-- Fin de ecabezado de la tabla -->

          <!-- Cuerpo de la tabla -->
          <tbody>
            <?php if (!empty($resultado)) : ?>
              <?php foreach ($resultado as $incidencia) : ?>
                <tr class='second-table hover:bg-green-100 hover:scale-[101%] transition-all border-b' data-id="<?= $incidencia['INC_numero']; ?>">
                  <th scope='row' class='px-6 py-3 font-medium text-gray-900 whitespace-nowrap hidden'> <?= $incidencia['INC_numero']; ?></th>
                  <td class='px-6 py-3 text-center'><?= $incidencia['INC_numero_formato']; ?></td>
                  <td class='px-4 py-3 text-center'><?= $incidencia['fechaIncidenciaFormateada']; ?></td>
                  <td class='px-1 py-3 text-center'><?= $incidencia['INC_codigoPatrimonial']; ?></td> <!-- Ajuste aquí -->
                  <td class='px-10 py-3 text-center'><?= $incidencia['INC_asunto']; ?></td>
                  <td class='px-3 py-3 text-center'><?= $incidencia['INC_documento']; ?></td>
                  <td class='px-6 py-3 text-center'><?= $incidencia['CAT_nombre']; ?></td>
                  <td class='px-6 py-3 text-center'><?= $incidencia['ARE_nombre']; ?></td>
                  <td class='px-6 py-3 hidden'><?= $incidencia['INC_descripcion']; ?></td>
                  <td class='px-6 py-3 text-center hidden'><?= $incidencia['ESTADO']; ?></td>
                  <td class="px-3 py-3 text-center hidden">
                    <?php
                    $estadoDescripcion = htmlspecialchars($incidencia['ESTADO']);
                    $badgeClass = '';
                    switch ($estadoDescripcion) {
                      case 'ABIERTO':
                        $badgeClass = 'badge-light-danger';
                        break;
                      case 'RECEPCIONADO':
                        $badgeClass = 'badge-light-success';
                        break;
                      case 'CERRADO':
                        $badgeClass = 'badge-light-primary';
                        break;
                      default:
                        $badgeClass = 'badge-light-secondary';
                        break;
                    }
                    ?>
                    <label class="badge <?= $badgeClass ?>"><?= $estadoDescripcion ?></label>
                  </td>
                  <td class='px-6 py-3 hidden'><?= $incidencia['Usuario']; ?></td>
                  <td class="px-6 py-3 text-center align-middle flex space-x-2"> <!-- Columna de Acción con botones -->
                    <!-- Botón de Imprimir detalla de incidencia -->
                    <button type="button" id="imprimir-incidencia" class="bn btn-warning text-xs text-white font-bold py-2 px-3 rounded-md flex items-center justify-center" title="Imprimir detalle de incidencia">
                      <i class="feather icon-printer"></i>
                    </button>

                    <!-- Botón de Eliminar -->
                    <button type="button" class="eliminar-incidencia bn btn-danger text-xs text-white font-bold py-2 px-3 rounded-md flex items-center justify-center" title="Eliminar incidencia">
                      <i class="feather icon-trash-2"></i>
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="8" class="text-center py-3">No se han registrado nuevas incidencias</td>
              </tr>
            <?php endif; ?>
          </tbody>
          <!-- Fin de cuerpo de la tabla -->
        </table>
      </div>
    </div>
    <!-- Fin de tabla de incidencias registradas -->

  </div>
</div>
<script src="https://cdn.tailwindcss.com"></script>