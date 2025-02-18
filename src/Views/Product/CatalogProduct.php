<h3>Catálogo de productos</h3>
<?php
    $oldData = $_SESSION['old_data'] ?? [];
    $errores = $_SESSION['errores'] ?? [];
    unset($_SESSION['old_data'], $_SESSION['errores']);
?>

<form action="<?=BASE_URL?>Catalog"  method="POST">
<select name="category" id="category">
    <option selected disabled>Seleccione una categoría</option>
    <option value="0"> Ver todos los productos </option>
    <?php if (!empty($categories)): ?>
        <?php foreach ($categories as $category): ?>
            <?php if ($category['id'] != 1): ?>
                <option value='<?=$category['id']?>'> <?= htmlspecialchars($category['nombre']) ?> </option>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <option selected disabled>No hay categorías disponibles.</option>
    <?php endif; ?>
</select>

<input type="submit" value="Buscar">
</form>
    

<?php if (!empty($products)): ?>
    <ol style="list-style-type: none; display: grid; grid-template-columns: repeat(4, 1fr); align-items: start; justify-content: space-between; gap: 2em; padding: 1em;">
        <?php foreach ($products as $product): ?>
            <li>
                <div class="card" style="width: 15rem;">
                    <img src="<?= BASE_URL ?>/public/Img/<?= htmlspecialchars($product['imagen']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['nombre']) ?>" style="height: 180px; object-fit: cover;"><br>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($product['nombre']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($product['descripcion']) ?></p>
                        <strong>Precio:</strong> <?= number_format($product['precio'], 2) . '€' ?><br>
                        <strong>Stock:</strong> <?= htmlspecialchars($product['stock']) ?><br>
                        <strong>Oferta:</strong> <?= $product['oferta'] ? 'Sí: ' . number_format($product['oferta'], 2) . '%' : 'No' ?><br>
                        <a href="<?=BASE_URL?>addProductCart/<?= $product['id'] ?>" class="btn btn-primary">Añadir al carrito</a>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ol>
<?php else: ?>
    <p>No hay productos disponibles.</p>
<?php endif; ?>

