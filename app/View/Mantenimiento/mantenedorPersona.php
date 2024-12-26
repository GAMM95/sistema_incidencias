<div class="pcoded-main-container mt-5">
  <div class="pcoded-content">

    <!-- Miga de pan -->
    <div class="page-header">
      <div class="page-block">
        <div class="row align-items-center">
          <div class="col-md-12">
            <div class="page-header-title">
              <h1 class="text-2xl font-bold mb-2">Registro de personas</h1>
            </div>
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href=""><i class="feather icon-server"></i></a></li>
              <li class="breadcrumb-item"><a href="#">Mantenedor</a></li>
              <li class="breadcrumb-item"><a href="">Personas</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin de miga de pan -->

    <!-- Contenedor principal para el formulario y la tabla -->
    <div class="flex space-x-4">
      <!-- Inicio de Formulario de registro de personas -->
      <div class="flex flex-col w-1/3">
        <form id="formPersona" action="modulo-persona.php?action=registrar" method="POST" class="card table-card bg-white shadow-md p-6 text-xs  flex flex-col mb-2">
          <input type="hidden" id="form-action" name="action" value="registrar">
          <!-- Subtitulo -->
          <h3 class="text-2xl font-plain mb-4 text-xs text-gray-400">Datos personales</h3>

          <!-- Inicio de Card de formulario -->
          <div class="card-body">
            <!-- PRIMERA FILA -->
            <div class="flex justify-center -mx-2 hidden">
              <div class="flex items-center mb-4">
                <div class="flex items-center">
                  <label for="CodPersona" class="block font-bold mb-1 mr-3 text-lime-500">C&oacute;digo de persona:</label>
                  <input type="text" id="CodPersona" name="CodPersona" class="w-20 border border-gray-200 bg-gray-100 rounded-md p-2 text-xs text-center" readonly>
                </div>
              </div>
            </div>

            <!-- DNI DE LA PERSONA -->
            <div class="mb-2 sm:w-1/3">
              <label for="dni" class="block mb-1 font-bold text-xs">DNI: *</label>
              <div class="relative">
                <input type="text" id="dni" name="dni" class="border p-2 w-full text-xs rounded-md pl-10" maxlength="8" pattern="\d{1,8}" autofocus inputmode="numeric" title="Ingrese solo dígitos" oninput="this.value = this.value.replace(/[^0-9]/g, '');" placeholder="Ingrese DNI">
              </div>
            </div>

            <!-- NOMBRES DE LA PERSONA -->
            <div class="mb-2 sm:w-1/2">
              <label for="nombres" class="block mb-1 font-bold text-xs">Nombres: *</label>
              <input type="text" id="nombres" name="nombres" class="border p-2 w-full text-xs rounded-md" title="Ingrese solo letras" placeholder="Ingrese nombres" oninput="capitalizeInput(this)">
            </div>

            <div class="flex flex-wrap -mx-2">
              <!-- APELLIDO PATERNO -->
              <div class="w-full sm:w-1/2 px-2 mb-2">
                <label for="apellidoPaterno" class="block mb-1 font-bold text-xs">Apellido Paterno: *</label>
                <input type="text" id="apellidoPaterno" name="apellidoPaterno" class="border p-2 w-full text-xs rounded-md" placeholder="Ingrese apellido paterno" oninput="capitalizeInput(this)">
              </div>

              <!-- APELLIDO MATERNO -->
              <div class="w-full sm:w-1/2 px-2 mb-2">
                <label for="apellidoMaterno" class="block mb-1 font-bold text-xs">Apellido Materno: *</label>
                <input type="text" id="apellidoMaterno" name="apellidoMaterno" class="border p-2 w-full text-xs rounded-md" placeholder="Ingrese apellido materno" oninput="capitalizeInput(this)">
              </div>
            </div>

            <div class="flex flex-wrap -mx-2">
              <!-- CELULAR -->
              <div class="w-full sm:w-1/3 px-2 mb-2">
                <label for="celular" class="block mb-1 font-bold text-xs">Celular:</label>
                <input type="tel" id="celular" name="celular" class="border p-2 w-full text-xs rounded-md" maxlength="9" pattern="\d{1,9}" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '');" placeholder="Ingrese celular">
              </div>

              <!-- EMAIL -->
              <div class="w-full sm:w-2/3 px-2 mb-2">
                <label for="email" class="block mb-1 font-bold text-xs">Email:</label>
                <input type="email" id="email" name="email" class="border p-2 w-full text-xs rounded-md" placeholder="Ingrese email">
              </div>
            </div>

            <!-- BOTONES DEL FORMULARIO -->
            <div class="flex justify-center space-x-4 mt-3">
              <button type="submit" id="guardar-persona" class="bn btn-primary text-xs text-white font-bold py-2 px-3 rounded-md disabled:bg-gray-300 disabled:cursor-not-allowed"><i class="feather mr-2 icon-save"></i>Registrar</button>
              <button type="button" id="editar-persona" class="bn btn-info text-xs text-white font-bold py-2 px-3 rounded-md disabled:bg-gray-300 disabled:cursor-not-allowed" disabled><i class="feather mr-2 icon-edit"></i>Actualizar</button>
              <button type="button" id="nuevo-registro" class="bn btn-secondary text-xs text-white font-bold py-2 px-3 rounded-md"> <i class="feather mr-2 icon-plus-square"></i>Limpiar</button>
            </div>
          </div>
          <!-- Fin de Card de formulario -->

        </form>
      </div>
      <!-- Fin de formulario -->

      <!-- TABLA DE PERSONAS -->
      <div class="w-2/3">
        <!-- Buscador de personas -->
        <div class="flex justify-between items-center mt-2">
          <input type="text" id="termino" class="px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-lime-300 text-xs" placeholder="Buscar trabajador..." oninput="filtrarTablaPersonas()" />
        </div>
        <!-- Fin de buscador de personas -->

        <!-- Tabla de personas -->
        <input type="hidden" id="personasCount" value="<?php echo count($personas) ?>">
        <div id="tablaContainer" class="relative mt-2 overflow-x-hidden shadow-md sm:rounded-lg bg-white ">
          <table id="tablaTrabajadores" class="w-full text-xs text-left rtl:text-right text-gray-500 bg-white">
            <!-- Encabezado -->
            <thead class="sticky top-0 text-xs text-gray-70 uppercase bg-lime-300">
              <tr>
                <th scope="col" class="px-6 py-1 text-center ">N&deg;</th>
                <th scope="col" class="px-6 py-1 text-center">DNI</th>
                <th scope="col" class="px-6 py-2 text-center">Nombre completo</th>
                <th scope="col" class="px-6 py-2 text-center">Celular</th>
                <th scope="col" class="px-6 py-2 text-center">Email</th>
              </tr>
            </thead>
            <!-- Fin de encabezado -->

            <!-- Inicio de cuerpo de tabla -->
            <tbody>
              <?php $item = 1; // Iniciar contador para el ítem 
              ?>
              <?php foreach ($personas as $persona) : ?>
                <tr class="hover:bg-green-100 hover:scale-[101%] transition-all hover:cursor-pointer border-b" data-cod="<?= $persona['PER_codigo']; ?>" data-dni="<?= $persona['PER_dni']; ?>" data-nombre="<?= $persona['persona']; ?>" data-celular="<?= $persona['PER_celular']; ?>" data-email="<?= $persona['PER_email']; ?>">
                  <th scope="row" class="hidden px-6 py-3 font-medium text-gray-900 whitespace-nowrap"> <?= $persona['PER_codigo']; ?></th>
                  <td class="px-3 py-2 text-center"><?= $item++ ?></td> <!-- Columna de ítem -->
                  <td class="px-6 py-2 w-1/6 text-center"> <?= $persona['PER_dni']; ?></td>
                  <td class="px-6 py-2 w-1/2 text-center"> <?= $persona['persona']; ?></td>
                  <td class="px-6 py-2 w-1/6 text-center"> <?= $persona['PER_celular']; ?></td>
                  <td class="px-6 py-2 w-1/3 text-center"> <?= $persona['PER_email']; ?></td>
                </tr>
              <?php endforeach; ?>
              <?php if (empty($personas)) : ?>
                <tr>
                  <td colspan="5" class="text-center py-3">No hay trabajadores registrados.</td>
                </tr>
              <?php endif; ?>
            </tbody>
            <!-- Fin de cuerpo de tabla -->
          </table>
        </div>
      </div>
      <!-- Fin de tabla -->
    </div>
  </div>
</div>
<script src="https://cdn.tailwindcss.com"></script>