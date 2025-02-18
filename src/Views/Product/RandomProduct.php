<hr>
<h3>Productos Aleatorios</h3>
<?php if (!empty($products)): ?>
    <ol style="list-style-type: none;  display: grid; grid-template-columns: repeat(3, 1fr);; align-items: start; justify-content: space-between; gap: 2em; padding: 1em;">
        <?php foreach ($products as $product): ?>
            <li>
            <div class="card" style="width: 15rem;">
            <img src="<?= BASE_URL ?>/public/Img/<?= htmlspecialchars($product['imagen']) ?>" class="card-img-top"  alt="<?= htmlspecialchars($product['nombre']) ?>" width="100"><br>
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($product['nombre']) ?></h5>
                    <p class="card-text"><?= htmlspecialchars($product['descripcion']) ?></p>
                    <strong>Precio:</strong> <?= number_format($product['precio'], 2). '€' ?><br>
                    <strong>Stock:</strong> <?= htmlspecialchars($product['stock']) ?><br>
                    <strong>Oferta:</strong> <?= $product['oferta'] ? 'Sí: ' . number_format($product['oferta'], 2). '%': 'No' ?><br>
                    <a href="<?=BASE_URL?>addProductCart/<?= $product['id'] ?>" class="btn btn-primary">Añadir al carrito</a>
                </div>
            </li>
        <?php endforeach; ?>
    </ol>
<?php else: ?>
    <p>No hay productos aleatorios disponibles.</p>
<?php endif; ?>

