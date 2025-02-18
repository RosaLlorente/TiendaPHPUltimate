<h3>Crear nuevo producto</h3>
<?php
    $oldData = $_SESSION['old_data'] ?? [];
    $errores = $_SESSION['errores'] ?? [];
    unset($_SESSION['old_data'], $_SESSION['errores']);
?>

<form action="<?= BASE_URL ?>CreateProduct" method="POST" enctype="multipart/form-data" class="d-flex flex-column align-items-center gap-3 p-2 mt-5">
    <div class="input-group flex-nowrap w-75">
        <label for="categoria_id" class="input-group-text" id="addon-wrapping">Categoria(insertarID)</label>
        <input  type="number" name="data[categoria_id]" id="categoria_id" value="<?= htmlspecialchars($oldData['categoria_id'] ?? '') ?>" style="background-color: darkseagreen;" class="form-control" placeholder="Ejemplo: 1" aria-label="categoria_id" aria-describedby="addon-wrapping">
    </div>

    <div class="input-group flex-nowrap w-75">
        <label for="nombre" class="input-group-text" id="addon-wrapping">Nombre</label>
        <input  type="text" name="data[nombre]" id="nombre" value="<?= htmlspecialchars($oldData['nombre'] ?? '') ?>" style="background-color: darkseagreen;" class="form-control" placeholder="Ejemplo: Flores" aria-label="Nombre" aria-describedby="addon-wrapping">
    </div>

    <div class="input-group flex-nowrap w-75">
        <label for="descripcion" class="input-group-text" id="addon-wrapping">Descripcion</label>
        <input  type="text" name="data[descripcion]" id="descripcion" value="<?= htmlspecialchars($oldData['descripcion'] ?? '') ?>" style="background-color: darkseagreen;" class="form-control" placeholder="Ejemplo: Una planta de flores" aria-label="descripcion" aria-describedby="addon-wrapping">
    </div>

    <div class="input-group flex-nowrap w-75">
        <label for="precio" class="input-group-text" id="addon-wrapping">Precio</label>
        <input  type="number" name="data[precio]" id="precio" value="<?= htmlspecialchars($oldData['precio'] ?? '') ?>" style="background-color: darkseagreen;" class="form-control" placeholder="Ejemplo: 44" aria-label="precio" aria-describedby="addon-wrapping">
    </div>

    <div class="input-group flex-nowrap w-75">
        <label for="stock" class="input-group-text" id="addon-wrapping">Stock</label>
        <input  type="number" name="data[stock]" id="stock" value="<?= htmlspecialchars($oldData['stock'] ?? '') ?>" style="background-color: darkseagreen;" class="form-control" placeholder="Ejemplo: 12" aria-label="stock" aria-describedby="addon-wrapping">
    </div>

    <div class="input-group flex-nowrap w-75">
        <label for="oferta" class="input-group-text" id="addon-wrapping">Oferta</label>
        <input  type="text" name="data[oferta]" id="oferta" value="<?= htmlspecialchars($oldData['oferta'] ?? '') ?>" style="background-color: darkseagreen;" class="form-control" placeholder="Ejemplo: 15" aria-label="oferta" aria-describedby="addon-wrapping">
    </div>

    <div class="input-group flex-nowrap w-75">
        <label for="fecha" class="input-group-text" id="addon-wrapping">Fecha</label>
        <input  type="date" name="data[fecha]" id="fecha" value="<?= htmlspecialchars($oldData['fecha'] ?? '') ?>" style="background-color: darkseagreen;" class="form-control" placeholder="Ejemplo: 2023-01-01" aria-label="fecha" aria-describedby="addon-wrapping">
    </div>

    <div class="input-group flex-nowrap w-75">
        <label for="imagen" class="input-group-text" id="addon-wrapping">Imagen</label>
        <input type="file" name="data[imagen]" id="imagen" class="form-control" aria-label="imagen" aria-describedby="addon-wrapping">
    </div>
    

    <div class="d-flex flex-row justify-content-center gap-3 w-100">
        <input type="submit" value="Crear producto" class="btn-outline-primary w-25">
        <input type="reset" value="Borrar todo" class="btn-outline-primary w-25">
    </div>
</form>
