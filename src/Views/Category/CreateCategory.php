<h3>Crear nueva categoria</h3>
<?php
    $oldData = $_SESSION['old_data'] ?? [];
    $errores = $_SESSION['errores'] ?? [];
    unset($_SESSION['old_data'], $_SESSION['errores']);
?>

<form action="<?= BASE_URL ?>CreateCategory" method="POST" class="d-flex flex-column align-items-center gap-3 p-2 mt-5">
    <div class="input-group flex-nowrap w-75">
        <label for="nombre" class="input-group-text" id="addon-wrapping">Nombre</label>
        <input  type="text" name="data[nombre]" id="nombre" value="<?= htmlspecialchars($oldData['nombre'] ?? '') ?>" style="background-color: darkseagreen;" class="form-control" placeholder="Ejemplo: Flores" aria-label="Nombre" aria-describedby="addon-wrapping">
    </div>
    <div class="d-flex flex-row justify-content-center gap-3 w-100">
        <input type="submit" value="Crear categoría" class="btn-outline-primary w-25">
        <input type="reset" value="Borrar todo" class="btn-outline-primary w-25">
    </div>
</form>