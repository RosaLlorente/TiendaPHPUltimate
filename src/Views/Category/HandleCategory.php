<h1>Gestor de Categorias</h1>
<?php
    $oldData = $_SESSION['old_data'] ?? [];
    $errores = $_SESSION['errores'] ?? [];
    unset($_SESSION['old_data'], $_SESSION['errores']);
?>
<?php if (!empty($categories)): ?>
    <ul class="list-group list-group-flush">
        <?php foreach ($categories as $category): ?>
            <li class="list-group-item d-flex flex-row justify-content-between align-items-center p-4 gap-5 mt-4">
                <?= htmlspecialchars($category['nombre']) ?> 
                    <?php if ($category['id'] !== '1'): ?>
                        <a href="<?= BASE_URL ?>DeleteCategory/<?= $category['id'] ?>" class="btn btn-danger">Eliminar</a>
                        <form  action="<?= BASE_URL ?>EditCategory" method="POST" class="list-group-item d-flex flex-row justify-content-between align-items-center gap-5">
                            <input type="hidden" name="data[id]" value="<?= $category['id'] ?>">

                            <label for="nombre">Editar nombre</label>
                            <input type="text" name="data[nombre]" id="nombre" value="<?= htmlspecialchars($oldData['nombre'] ?? '') ?>" required>
                            <input type="submit" value="Guardar cambios">
                            <input type="reset" value="Borrar todo">
                        </form>
                    <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No hay categorías disponibles.</p>
<?php endif; ?>