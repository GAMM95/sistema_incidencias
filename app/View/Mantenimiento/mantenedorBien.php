<div class="pcoded-main-container mt-5">
  <div class="pcoded-content">
    <!-- Miga de pan -->
    <div class="page-header">
      <div class="page-block">
        <div class="row align-items-center">
          <div class="col-md-12">
            <div class="page-header-title">
              <h1 class="text-2xl font-bold mb-2">Registro de bienes</h1>
            </div>
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href=""><i class="feather icon-server"></i></a></li>
              <li class="breadcrumb-item"><a href="#">Mantenedor</a></li>
              <li class="breadcrumb-item"><a href="">Bienes</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin de miga de pan -->

    <!-- Conteneder principal para el formulario y la tabla -->
    <div class="flex space-x-4">
      <!-- Formulario de registro de bien -->
      <div class="flex flex-col w-1/3">
        <form id="formBienes" action="modulo-bien.php?action=registrar" method="POST" class="card table-card bg-white shadow-md p-6 w-full text-xs">
          <input type="hidden" id="form-action" name="action" value="registrar">
          <!-- Codigo de bien -->
          <div class="flex justify-center -mx-2 mb-5 hidden">
            <div class="flex items-center mb-4">
              <div class="flex items-center">
                <label for="codBien" class="block font-bold mb-1 mr-3 text-lime-500">C&oacute;digo de bien:</label>
                <input type="text" id="codBien" name="codBien" class="w-20 border border-gray-200 bg-gray-100 rounded-md p-2 text-xs text-center" readonly>
              </div>
            </div>
          </div>

          <!-- Codigo identificador -->
          <div class="flex flex-wrap -mx-2">
            <div class="px-2 mb-3 w-1/2">
              <label for="codigoIdentificador" class="block mb-1 font-bold text-xs">C&oacute;digo identificador:</label>
              <input type="text" id="codigoIdentificador" name="codigoIdentificador" class="border p-2 w-full text-xs rounded-md" maxlength="8" pattern="\d{1,12}" inputmode="numeric" title="Ingrese solo dígitos" oninput="this.value = this.value.replace(/[^0-9]/g, '');" placeholder="Ingrese c&oacute;digo identificador">
            </div>
          </div>

          <!-- nombre del tipo de bien -->
          <div class="flex flex-wrap -mx-2">
            <div class="w-full px-2 mb-3">
              <label for="nombreTipoBien" class="block mb-1 font-bold text-xs">Nombre de bien:</label>
              <input type="text" id="nombreTipoBien" name="nombreTipoBien" class="border p-2 w-full text-xs rounded-md" placeholder="Ingrese nuevo tipo de bien">
            </div>
          </div>

          <!-- Botones del formulario -->
          <div class="flex justify-center space-x-4">
            <button type="submit" id="guardar-bien" class="bn btn-primary text-xs text-white font-bold py-2 px-3 rounded-md disabled:cursor-not-allowed"><i class="feather mr-2 icon-save"></i>Guardar</button>
            <button type="button" id="editar-bien" class="bn btn-info text-xs text-white font-bold py-2 px-3 rounded-md disabled:cursor-not-allowed" disabled><i class="feather mr-2 icon-edit"></i>Editar</button>
            <button type="button" id="nuevo-registro" class="bn btn-secondary text-xs text-white font-bold py-2 px-3 rounded-md"> <i class="feather mr-2 icon-plus-square"></i>Limpiar</button>
          </div>
          <!-- Fin de botones del formulario -->
        </form>
        <!-- Inicio de Buscador -->
        <div class="flex justify-between items-center ">
          <input type="text" id="termino" class="px-4 py-2 w-full border rounded-md focus:outline-none focus:ring-2 focus:ring-lime-300 text-xs" placeholder="Buscar tipo de bien..." oninput="filtrarTablaBienes()" />
        </div>
        <!-- Fin de Buscador -->
      </div>
      <!-- Fin de formulario de registro -->

      <!-- Tabla de Bienes -->
      <div class="w-2/3">
        <div class="relative max-h-[800px] overflow-x-hidden shadow-md sm:rounded-lg">
          <table id="tablaListarBienes" class="w-full text-xs text-left rtl:text-right text-gray-500 cursor-pointer bg-white">
            <!-- Encabezado de la tabla -->
            <thead class="sticky top-0 text-xs text-gray-700 uppercase bg-lime-300">
              <tr>
                <th scope="col" class="px-10 py-2 w-1/6 hidden">N&deg;</th>
                <th scope="col" class="px-6 py-2 w-1/6 text-center">C&oacute;digo identificador</th>
                <th scope="col" class="px-6 py-2 w-4/6 text-center">Nombre de bien</th>
                <th scope="col" class="px-6 py-2 text-center">Acci&oacute;n</th>
              </tr>
            </thead>
            <!-- Fin de encabezado -->

            <!-- Cuerpo de la tabla -->
            <tbody>
              <?php if (!empty($resultado)) : ?>
                <?php foreach ($resultado as $bien) : ?>
                  <?php
                  $estado = htmlspecialchars($bien['BIE_estado']);
                  $isActive = ($estado === '1');
                  $codigoBien = htmlspecialchars($bien['BIE_codigo']);

                  // Aplicar clase de texto rojo si el ARE_estado es 2
                  $bienInactivo = ($estado == 2) ? 'text-red-600' : 'text-gray-900';
                  ?>
                  <tr class='second-table hover:bg-green-100 hover:scale-[101%] transition-all border-b'>
                    <th scope='row' class='px-10 py-2 font-medium text-gray-900 whitespace-nowrap hidden'> <?= $codigoBien; ?></th>
                    <td class="px-6 py-2 text-center  <?= $bienInactivo; ?>"> <?= $bien['BIE_codigoIdentificador']; ?></td>
                    <td class="px-6 py-2 <?= $bienInactivo; ?>"> <?= $bien['BIE_nombre']; ?></td>
                    <td class="px-6 py-2 text-center">
                      <div class="custom-control custom-switch cursor-pointer">
                        <input type="checkbox" class="custom-control-input switch-bien" id="customswitch<?= $codigoBien; ?>" data-id="<?= $codigoBien; ?>" <?= $isActive ? 'checked' : ''; ?>>
                        <label class="custom-control-label" for="customswitch<?= $codigoBien; ?>"><?= $isActive ? 'Activo' : 'Inactivo'; ?></label>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="3" class="text-center py-3">No se han registrado nuevos bienes</td>
                </tr>
              <?php endif; ?>
            </tbody>
            <!-- Fin de cuerpo de la tabla -->
          </table>
        </div>
      </div>
      <!-- Fin de la tabla de bienes -->
    </div>
  </div>
</div>
<script src="https://cdn.tailwindcss.com"></script>