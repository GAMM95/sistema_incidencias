<div class="pcoded-main-container mt-5">
  <div class="pcoded-content">
    <!-- Miga de pan -->
    <div class="page-header">
      <div class="page-block">
        <div class="row align-items-center">
          <div class="col-md-12">
            <div class="page-header-title">
              <h1 class="text-2xl font-bold mb-2">Registro de categor&iacute;as</h1>
            </div>
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href=""><i class="feather icon-server"></i></a></li>
              <li class="breadcrumb-item"><a href="#">Mantenedor</a></li>
              <li class="breadcrumb-item"><a href="">Categor&iacute;as</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin de miga de pan -->

    <!-- Conteneder principal para el formulario y la tabla -->
    <div class="flex space-x-4">
      <!-- Formulario de registro de categoria -->
      <div class="flex flex-col w-1/3">
        <form id="formCategoria" action="modulo-categoria.php?action=registrar" method="POST" class="card table-card bg-white shadow-md p-6 w-full text-xs">
          <input type="hidden" id="form-action" name="action" value="registrar">
          <!-- Codigo de categoria -->
          <div class="flex justify-center -mx-2 mb-5 hidden">
            <div class="flex items-center mb-4">
              <div class="flex items-center">
                <label for="codCategoria" class="block font-bold mb-1 mr-3 text-lime-500">C&oacute;digo de categor&iacute;a:</label>
                <input type="text" id="codCategoria" name="codCategoria" class="w-20 border border-gray-200 bg-gray-100 rounded-md p-2 text-xs text-center" readonly>
              </div>
            </div>
          </div>

          <!-- Nombre de categoria -->
          <div class="flex flex-wrap -mx-2">
            <div class="w-full px-2 mb-3">
              <label for="nombreCategoria" class="block mb-1 font-bold text-xs">Nombre de categor&iacute;a:</label>
              <input type="text" id="nombreCategoria" name="nombreCategoria" class="border p-2 w-full text-xs rounded-md" pattern="[A-Za-z\s]+" placeholder="Ingrese nueva categor&iacute;a">
            </div>
          </div>

          <!-- Botones del fomulario -->
          <div class="flex justify-center space-x-4">
            <button type="submit" id="guardar-categoria" class="bn btn-primary text-xs text-white font-bold py-2 px-3 rounded-md disabled:cursor-not-allowed"><i class="feather mr-2 icon-save"></i>Guardar</button>
            <button type="button" id="editar-categoria" class="bn btn-info text-xs text-white font-bold py-2 px-3 rounded-md disabled:cursor-not-allowed" disabled><i class="feather mr-2 icon-edit"></i>Editar</button>
            <button type="button" id="nuevo-registro" class="bn btn-secondary text-xs text-white font-bold py-2 px-3 rounded-md"> <i class="feather mr-2 icon-plus-square"></i>Limpiar</button>
          </div>
          <!-- Fin de botones del formulario -->
        </form>
      </div>
      <!-- Fin de formulario de registro -->

      <!-- Tabla de categorias -->
      <div class="w-2/3">
        <div class="relative max-h-[800px] overflow-x-hidden shadow-md sm:rounded-lg">
          <table id="tablaCategorias" class="w-full text-xs text-left rtl:text-right text-gray-500 cursor-pointer bg-white">
            <!-- Encabezado de la tabla -->
            <thead class="sticky top-0 text-xs text-gray-70 uppercase bg-lime-300">
              <tr>
                <th scope="col" class="px-10 py-2 w-1/6 hidden">N&deg;</th>
                <th scope="col" class="px-6 py-2 w-5/6 text-center">Categor&iacute;a</th>
                <th scope="col" class="px-6 py-2 text-center">Acci&oacute;n</th>
              </tr>
            </thead>
            <!-- Fin de encabezado -->

            <!-- Encabezado de la tabla -->
            <tbody>
              <?php if (!empty($resultado)): ?>
                <?php foreach ($resultado as $categoria) : ?>
                  <?php
                  $estado = htmlspecialchars($categoria['EST_codigo']);
                  $isActive = ($estado === '1');
                  $codigoCategoria = htmlspecialchars($categoria['CAT_codigo']);
                  // Aplicar clase de texto rojo si el ARE_estado es 2
                  $categoriaInactiva = ($estado == 2) ? 'text-red-600' : 'text-gray-900';
                  ?>
                  <tr class='second-table hover:bg-green-100 hover:scale-[101%] transition-all border-b' data-id="<?= $codigoCategoria; ?>">
                    <th scope='row' class='px-6 py-2 font-medium text-gray-900 whitespace-nowrap hidden'><?= $codigoCategoria; ?></th>
                    <td class='px-6 py-2 w-2/3 <?= $categoriaInactiva; ?>'><?= $categoria['CAT_nombre']; ?></td>
                    <td class="px-6 py-2 text-center">
                      <div class="custom-control custom-switch cursor-pointer">
                        <input type="checkbox" class="custom-control-input switch-categoria" id="customswitch<?= $codigoCategoria; ?>" data-id="<?= $codigoCategoria; ?>" <?= $isActive ? 'checked' : ''; ?>>
                        <label class="custom-control-label" for="customswitch<?= $codigoCategoria; ?>"><?= $isActive ? 'Activo' : 'Inactivo'; ?></label>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="10" class="text-center py-3">No se han registrado nuevas categor&iacute;as.
                </tr>
              <?php endif; ?>
            </tbody>
            <!-- Fin del cuerpo de la tabla -->
          </table>
        </div>
      </div>
      <!-- Fin de la tabla de categorias -->
    </div>
  </div>
</div>
<script src="https://cdn.tailwindcss.com"></script>