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
    <form id="formIncidencia" action="registro-incidencia-user.php?action=registrar" method="POST" class="card table-card  bg-white shadow-md p-6 w-full text-xs mb-2">
      <input type="hidden" id="form-action" name="action" value="registrar">

      <!-- Fila oculta del numero de incidencia -->
      <div class="flex items-center mb-4 hidden">
        <label for="numero_incidencia" class="block font-bold mb-1 mr-1 text-lime-500">N&deg; Incidencia:</label>
        <input type="text" id="numero_incidencia" name="numero_incidencia" class="w-20 border border-gray-200 bg-gray-100 rounded-md p-2 text-xs" readonly>
      </div>

      <!-- Primera fila del formulario -->
      <div class="flex flex-wrap -mx-2">
        <!-- AREA DEL USUARIO -->
        <div class="w-full sm:w-1/6 px-2 mb-2 hidden">
          <label for="codigoArea" class="block mb-1 font-bold text-xs">C&oacute;digo &Aacute;rea:</label>
          <input type="text" id="codigoArea" name="codigoArea" class="border border-gray-200 bg-gray-100 p-2 w-full text-xs" value="<?php echo $_SESSION['codigoArea']; ?>" readonly>
        </div>
        <div class="w-full sm:w-1/2 px-2 mb-4 hidden">
          <label for="area" class="block font-bold mb-1 text-xs">&Aacute;rea:</label>
          <input type="text" id="area" name="area" class="border border-gray-200 bg-gray-100 p-2 w-full text-xs" value="<?php echo $_SESSION['area']; ?>" readonly>
        </div>

        <!-- FECHA DE LA INCIDENCIA -->
        <div class="w-full sm:w-1/6 px-2 mb-2 hidden">
          <label for="fecha_incidencia" class="block mb-1 font-bold text-xs">Fecha:</label>
          <input type="date" id="fecha_incidencia" name="fecha_incidencia" class="border border-gray-200 bg-gray-100 p-2 w-full text-xs" value="<?php echo date('Y-m-d'); ?>" readonly>
        </div>

        <!-- HORA DE LA INCIDENCIA -->
        <div class="w-full md:w-1/6 px-2 mb-2 hidden">
          <label for="hora" class="block font-bold mb-1 text-xs">Hora:</label>
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
          <label for="usuarioDisplay" class="block font-bold mb-1 text-xs">Usuario:</label>
          <input type="text" id="usuarioDisplay" name="usuarioDisplay" class="border border-gray-200 bg-gray-100 p-2 w-full text-xs" value="<?php echo $_SESSION['usuario']; ?>" readonly>
        </div>
        <div class="w-full md:w-1/6 px-2 mb-2 hidden">
          <label for="codigoUsuario" class="block font-bold mb-1 text-xs">C&oacute;digo Usuario:</label>
          <input type="text" id="codigoUsuario" name="codigoUsuario" class="border border-gray-200 bg-gray-100 p-2 w-full text-xs" value="<?php echo $_SESSION['codigoUsuario']; ?>" readonly>
        </div>
      </div>

      <!-- SEGUNDA FILA DEL FORMULARIO -->
      <div class="flex flex-wrap -mx-2">
        <!-- CATEGORIA SELECCIONADA -->
        <div class="w-full sm:w-1/2 px-2 mb-2">
          <label for="categoria" class="block font-bold mb-1">Categor&iacute;a: *</label>
          <select id="cbo_categoria" name="categoria" class="border p-2 w-full text-xs cursor-pointer">
          </select>
        </div>

        <!-- CODIGO PATRIMONIAL -->
        <div class="w-full sm:w-1/4 px-2 mb-2">
          <label for="codigoPatrimonial" class="block mb-1 font-bold text-xs">C&oacute;digo Patrimonial:</label>
          <input type="text" id="codigoPatrimonial" name="codigoPatrimonial"
            class="border p-2 w-full text-xs rounded-md" maxlength="12"
            pattern="\d{1,12}" inputmode="numeric"
            title="Ingrese solo dígitos"
            placeholder="Ingrese código patrimonial">
        </div>

        <!-- TIPO DE BIEN -->
        <div class="w-full sm:w-1/4 px-2 mb-2">
          <label for="tipoBien" class="block mb-1 font-bold text-center text-xs">Nombre del bien:</label>
          <input type="text" id="tipoBien" name="tipoBien" class="border p-2 w-full text-center text-xs rounded-md" placeholder="Nombre del bien" disabled readonly>
        </div>
      </div>

      <!-- TERCERA FILA DEL FORMULARIO -->
      <div class="flex flex-wrap -mx-2">
        <!-- ASUNTO DE LA INCIDENCIA -->
        <div class="w-full sm:w-1/2 px-2 mb-2">
          <label for="asunto" class="block mb-1 font-bold text-xs">Asunto: *</label>
          <input type="text" id="asunto" name="asunto" class="border p-2 w-full text-xs rounded-md" placeholder="Ingrese asunto">
        </div>
        <!-- DOCUMENTO DE LA INCIDENCIA -->
        <div class="w-full sm:w-1/2 px-2 mb-2">
          <label for="documento" class="block mb-1 font-bold text-xs">Documento: *</label>
          <input type="text" id="documento" name="documento" class="border p-2 w-full text-xs rounded-md" placeholder="Ingrese documento" oninput="uppercaseInput(this)">
          <script>
            function uppercaseInput(element) {
              element.value = element.value.toUpperCase();
            }
          </script>
        </div>
      </div>

      <!-- CUARTA FILA DEL FORMULARIO -->
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
        document.getElementById('fecha').value = '<?php echo $incidenciaRegistrada ? $incidenciaRegistrada['INC_fecha'] : $fecha_actual; ?>';
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
      </div>
      <!-- Fin de botones -->
    </form>

    <!-- Paginacion de la tabla -->
    <div id="noIncidencias" class="flex justify-between items-center mb-2">
      <h1 class="text-xl text-gray-400">Lista de incidencias</h1>
      <!-- Busqueda de termino -->
      <div class="flex justify-between items-center">
        <input type="text" id="termino" class="px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300 text-xs" placeholder="Buscar incidencia..." oninput="filtrarTablaIncidencias()" />
      </div>
      <!-- Fin de busqueda -->
    </div>
    <!-- Fin de la paginacion -->

    <!-- Tabla de incidencias registradas -->
    <div class="relative overflow-x-hidden shadow-md sm:rounded-lg">
      <div class="max-w-full overflow-hidden rounded-lg">
        <table id="tablaListarIncidencias" class="w-full text-xs text-left rtl:text-right text-gray-500 cursor-pointer bg-white">
          <!-- Encabezado de la tabla -->
          <thead class="sticky top-0 text-xs text-gray-700 uppercase bg-blue-300">
            <tr>
              <th scope="col" class="px-6 py-2 text-center hidden">N&deg;</th>
              <th scope="col" class="px-6 py-2 text-center">N&deg; Incidencia</th>
              <th scope="col" class="px-6 py-2 text-center">Fecha de entrada</th>
              <th scope="col" class="px-6 py-2 text-center">C&oacute;digo Patrimonial</th>
              <th scope="col" class="px-6 py-2 text-center">Asunto</th>
              <th scope="col" class="px-6 py-2 text-center">Documento</th>
              <th scope="col" class="px-6 py-2 text-center">Categor&iacute;a</th>
              <th scope="col" class="px-6 py-2 text-center hidden">Area</th>
              <th scope="col" class="px-6 py-2 text-center hidden">descripci&oacute;n</th>
              <th scope="col" class="px-6 py-2 text-center hidden">Estado</th>
              <th scope="col" class="px-6 py-2 text-center hidden">Estado</th>
              <th scope="col" class="px-6 py-2 text-center">Usuario</th>
              <th scope="col" class="px-6 py-2 text-center">Acci&oacute;n</th>
            </tr>
          </thead>
          <!-- fin de encabezado -->

          <!-- Cuerpo de la tabla -->
          <tbody>
            <?php if (!empty($resultado)) : ?>
              <?php foreach ($resultado as $incidencia) : ?>
                <tr class='second-table hover:bg-green-100 hover:scale-[101%] transition-all border-b' data-id="<?= $incidencia['INC_numero']; ?>">
                  <th scope='row' class='px-6 py-4 font-medium text-gray-900 whitespace-nowrap hidden'> <?= $incidencia['INC_numero']; ?></th>
                  <td class='px-6 py-2 text-center'><?= $incidencia['INC_numero_formato']; ?></td>
                  <td class='px-6 py-2 text-center'><?= $incidencia['fechaIncidenciaFormateada']; ?></td>
                  <td class='px-6 py-2 text-center'><?= $incidencia['INC_codigoPatrimonial']; ?></td>
                  <td class='px-6 py-2 text-center'><?= $incidencia['INC_asunto']; ?></td>
                  <td class='px-6 py-2 text-center'><?= $incidencia['INC_documento']; ?></td>
                  <td class='px-6 py-2 text-center'><?= $incidencia['CAT_nombre']; ?></td>
                  <td class='px-6 py-2 text-center hidden'><?= $incidencia['ARE_nombre']; ?></td>
                  <td class='px-6 py-2 text-center hidden'><?= $incidencia['INC_descripcion']; ?></td>
                  <td class='px-6 py-2 text-center hidden'><?= $incidencia['ESTADO']; ?></td>
                  <td class="px-3 py-2 text-center hidden">
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
                  <td class='px-6 py-2 text-center'><?= $incidencia['Usuario']; ?></td>
                  <td class="px-6 py-2 justify-center text-center flex space-x-2"> <!-- Columna de Acción con botones -->
                    <!-- Botón de Imprimir detalle de incidencia -->
                    <button type="button" id="imprimir-incidencia" class="bn btn-warning text-xs text-white font-bold py-2 px-3 rounded-md flex items-center justify-center" title="Imprimir detalle de incidencia">
                      <i class="feather icon-printer"></i>
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="8" class="text-center py-3">No se han registrado nuevas incidencias.</td>
              </tr>
            <?php endif; ?>
          </tbody>
          <!-- Fin del cuerpo de tabla -->
        </table>
      </div>
    </div>
    <!-- Fin de tabla de incidencias registradas -->
  </div>
</div>

<script src="https://cdn.tailwindcss.com"></script>