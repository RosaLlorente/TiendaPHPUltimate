<h3>Iniciar sesion</h3>
<?php
    $oldData = $_SESSION['old_data'] ?? [];
    $errores = $_SESSION['errores'] ?? [];
    unset($_SESSION['old_data'], $_SESSION['errores']);
?>
<form action="<?=BASE_URL?>login" method="POST" class="d-flex flex-column align-items-center gap-3 p-2">
    <div class="input-group flex-nowrap w-75">
        <label for="email" class="input-group-text" id="addon-wrapping">@</label>
        <input type="email" name="data[email]" id="email" value="<?= htmlspecialchars($oldData['email'] ?? '') ?>" style="background-color: darkseagreen;" class="form-control" placeholder="Ejemplo: juan@gmail.com" aria-label="Email" aria-describedby="addon-wrapping">
    </div>
    <div class="input-group flex-nowrap w-75">
        <label for="password" class="input-group-text" id="addon-wrapping">🔐</label>
        <input type="password" name="data[password]" id="password" style="background-color: darkseagreen;" class="form-control" placeholder="Contraseña" aria-label="Contraseña" aria-describedby="addon-wrapping">
    </div>

    <div class="d-flex flex-row justify-content-center gap-3 w-100">
        <input type="submit" value="Iniciar Sesión" class="btn-outline-primary w-25">
        <input type="reset" value="Borrar todo" class="btn-outline-primary w-25">
    </div>
</form>