<div class="pcoded-main-container mt-5">
  <div class="pcoded-content">

    <?php
    global $AreaRegistrada;
    ?>
    <!-- Miga de pan -->
    <div class="page-header">
      <div class="page-block">
        <div class="row align-items-center">
          <div class="col-md-12">
            <div class="page-header-title">
              <h1 class="text-2xl font-bold mb-2">Registro de &aacute;reas</h1>
            </div>
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href=""><i class="feather icon-server"></i></a></li>
              <li class="breadcrumb-item"><a href="#">Mantenedor</a></li>
              <li class="breadcrumb-item"><a href="">&Aacute;reas</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin de miga de pan -->

    <!-- Contenedor principal para el fomulario y la tabla -->
    <div class="flex space-x-4">

      <!-- Inicio de formulario  -->
      <div class="flex flex-col w-1/3">
        <form id="formArea" action="modulo-area.php?action=registrar" method="POST" class="card table-card bg-white shadow-md p-6 w-full text-xs">
          <input type="hidden" id="form-action" name="action" value="registrar">

          <!-- PRIMERA FILA  -->
          <div class="flex justify-center -mx-2 mb-5 hidden">
            <div class="flex items-center mb-4">
              <div class="flex items-center">
                <label for="codArea" class="block font-bold mb-1 mr-3 text-lime-500">C&oacute;digo de &aacute;rea:</label>
                <input type="text" id="codArea" name="codArea" class="w-20 border border-gray-200 bg-gray-100 rounded-md p-2 text-xs text-center" readonly>
              </div>
            </div>
          </div>

          <!-- SEGUNDA FILA -->
          <div class="flex flex-wrap -mx-2">
            <div class="w-full px-2 mb-3">
              <label for="nombreArea" class="block mb-1 font-bold text-xs">Nombre &aacute;rea:</label>
              <input type="text" id="nombreArea" name="nombreArea" class="border p-2 w-full text-xs rounded-md" pattern="[A-Za-z]+" placeholder="Ingrese nueva &aacute;rea">
            </div>
          </div>

          <!-- BOTONES -->
          <div class="flex justify-center space-x-4">
            <button type="submit" id="guardar-area" class="bn btn-primary text-xs text-white font-bold py-2 px-3 rounded-md disabled:cursor-not-allowed"><i class="feather mr-2 icon-save"></i>Guardar</button>
            <button type="button" id="editar-area" class="bn btn-info text-xs text-white font-bold py-2 px-3 rounded-md disabled:cursor-not-allowed" disabled><i class="feather mr-2 icon-edit"></i>Editar</button>
            <button type="button" id="nuevo-registro" class="bn btn-secondary text-xs text-white font-bold py-2 px-3 rounded-md"> <i class="feather mr-2 icon-plus-square"></i>Limpiar</button>
          </div>
        </form>
      </div>
      <!-- Fin de formulario -->

      <!-- Inicio de Busqueda y Tabla -->
      <div class="w-2/3">
        <!-- Inicio de Buscador -->
        <div class="flex justify-between items-center mt-2">
          <input type="text" id="termino" class="px-4 py-2 w-full border rounded-md focus:outline-none focus:ring-2 focus:ring-lime-300 text-xs" placeholder="Buscar &aacute;rea..." oninput="filtrarTablaAreas()" />
        </div>
        <!-- Fin de Buscador -->

        <!-- Tabla de areas -->
        <div class="relative max-h-[550px] mt-2 overflow-x-hidden shadow-md sm:rounded-lg">
          <table id="tablaAreas" class="w-full text-xs text-left rtl:text-right text-gray-500 cursor-pointer bg-white">
            <!-- ENCABEZADO DE LA TABLA -->
            <thead class="sticky top-0 text-xs text-gray-70 uppercase bg-lime-300">
              <tr>
                <th scope="col" class="px-10 py-2 w-1/6 hidden">N&deg;</th>
                <th scope="col" class="px-6 py-2 w-2/3 text-center">&Aacute;rea</th>
                <th scope="col" class="px-6 py-2 w-1/6 text-center">Estado</th>
              </tr>
            </thead>
            <!-- Fin de encabezado -->

            <!-- Cuerpo de la tabla -->
            <tbody>
              <?php if (!empty($resultado)) : ?>
                <?php foreach ($resultado as $area) : ?>
                  <?php
                  $estado = htmlspecialchars($area['EST_codigo']);
                  $isActive = ($estado === '1');
                  $codigoArea = htmlspecialchars($area['ARE_codigo']); 
                  $areaInactiva = ($estado == 2) ? 'text-red-600' : 'text-gray-900';
                  ?>
                  <tr class="hover:bg-green-100 hover:scale-[101%] transition-all hover:cursor-pointer border-b" data-id="<?= $codigoArea; ?>">
                    <th scope="row" class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap hidden"><?= $codigoArea; ?></th>
                    <td class="px-6 py-2 text-center <?= $areaInactiva; ?>"><?= htmlspecialchars($area['ARE_nombre']); ?></td>
                    <td class="px-6 py-2 text-center">
                      <div class="custom-control custom-switch cursor-pointer">
                        <input type="checkbox" class="custom-control-input switch-area" id="customswitch<?= $codigoArea; ?>" data-id="<?= $codigoArea; ?>" <?= $isActive ? 'checked' : ''; ?>>
                        <label class="custom-control-label" for="customswitch<?= $codigoArea; ?>"><?= $isActive ? 'Activo' : 'Inactivo'; ?></label>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="3" class="text-center py-3">No se han registrado nuevas &aacute;reas</td>
                </tr>
              <?php endif; ?>
            </tbody>

            <!-- Fin del cuerpo de la tabla -->
          </table>
        </div>

        <!-- Fin de tabla de areas -->
      </div>
      <!-- Fin de Busqueda y Tabla -->
    </div>
  </div>
</div>
<script src="https://cdn.tailwindcss.com"></script>


<script>

</script>