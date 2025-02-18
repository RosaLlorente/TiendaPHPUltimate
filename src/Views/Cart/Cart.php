<?php 
    $cartTotals = $_SESSION['cart_totals'] ?? ['totalItems' => 0, 'totalPrice' => 0.00, 'totalOffer' => 0.00];
?>

<h1>Bienvenido a tu carrito</h1>
<?php if (!empty($_SESSION['cart'])): ?>
    <a href="<?=BASE_URL?>/ClearCart"  class="btn btn-danger">Limpiar todo el carrito</a>
    <h3>Productos que usted desea comprar:</h3>
    <ul class="list-group list-group-horizontal d-flex flex-column">
        <?php foreach ($_SESSION['cart'] as $item): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center gap-3 w-100">
            <img src="<?= BASE_URL ?>/public/Img/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" style="height: 80px; width: 80px;">
                <span><?= htmlspecialchars($item['name']) ?> - <?= number_format($item['price'], 2) ?>€ </span>
                <b>x<?= $item['quantity'] ?></b>
                <div>
                    <a href="<?= BASE_URL ?>DeleteProductCart/<?= $item['id'] ?>" class="btn btn-danger">Eliminar</a>
                    <a  href="<?= BASE_URL ?>addProductCart/<?= $item['id'] ?>"><svg style="width: 30px;" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns" fill="#00ff00" stroke="#00ff00"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>plus</title> <desc>Created with Sketch Beta.</desc> <defs> </defs> <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage"> <g id="Icon-Set-Filled" sketch:type="MSLayerGroup" transform="translate(-362.000000, -1037.000000)" fill="#00ff00"> <path d="M390,1049 L382,1049 L382,1041 C382,1038.79 380.209,1037 378,1037 C375.791,1037 374,1038.79 374,1041 L374,1049 L366,1049 C363.791,1049 362,1050.79 362,1053 C362,1055.21 363.791,1057 366,1057 L374,1057 L374,1065 C374,1067.21 375.791,1069 378,1069 C380.209,1069 382,1067.21 382,1065 L382,1057 L390,1057 C392.209,1057 394,1055.21 394,1053 C394,1050.79 392.209,1049 390,1049" id="plus" sketch:type="MSShapeGroup"> </path> </g> </g> </g></svg></a> 
                    <a href="<?= BASE_URL ?>quitProductCart/<?= $item['id'] ?>"><svg style="width: 30px;"  viewBox="0 -12 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>minus</title> <desc>Created with Sketch Beta.</desc> <defs> </defs> <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage"> <g id="Icon-Set-Filled" sketch:type="MSLayerGroup" transform="translate(-414.000000, -1049.000000)" fill="#a80000"> <path d="M442,1049 L418,1049 C415.791,1049 414,1050.79 414,1053 C414,1055.21 415.791,1057 418,1057 L442,1057 C444.209,1057 446,1055.21 446,1053 C446,1050.79 444.209,1049 442,1049" id="minus" sketch:type="MSShapeGroup"> </path> </g> </g> </g></svg></a>
                </div>
            </li>
        <?php endforeach; ?>
        <li class="list-group-item d-flex justify-content-between align-items-center gap-3 w-100">
            <span><b>Total de productos:</b> <?= $cartTotals['totalItems'] ?></span>
            <span><b>Descuento total aplicado:</b> <?= number_format($cartTotals['totalOffer'], 2) ?>€</span>
            <span><b>Importe total:</b> <?= number_format($cartTotals['totalPrice'], 2) ?>€</span>
        </li>
    </ul>
    <?php if(!isset($_SESSION['user_id'])): ?>
        <p>Debes iniciar sesión para realizar la compra. <a href="<?=BASE_URL?>login">¡Iniciar Sesión!</a></p>

    <?php else: ?>
        <h3>Ordenar Pedido</h3>
    <?php
        $oldData = $_SESSION['old_data'] ?? [];
        $errores = $_SESSION['errores'] ?? [];
        unset($_SESSION['old_data'], $_SESSION['errores']);
    ?>
    <form action="<?= BASE_URL ?>order" method="POST" class="d-flex flex-column align-items-center gap-3 p-2">
        <input type="hidden" name="data[usuario_id]" value="<?= $_SESSION['user_id'] ?>">
        <input type="hidden" name="data[coste]" value="<?=  number_format((double)$cartTotals['totalPrice'], 2) ?>">

        <!-- Campo Provincia -->
        <div class="input-group flex-nowrap w-75">
            <label for="provincia" class="input-group-text" id="addon-wrapping">Provincia</label>
            <input type="text" name="data[provincia]" id="provincia" value="<?= htmlspecialchars($oldData['provincia'] ?? '') ?>" class="form-control" placeholder="Ejemplo: Granada">
        </div>

        <!-- Campo Localidad -->
        <div class="input-group flex-nowrap w-75">
            <label for="localidad" class="input-group-text" id="addon-wrapping">Localidad</label>
            <input type="text" name="data[localidad]" id="localidad" value="<?= htmlspecialchars($oldData['localidad'] ?? '') ?>" class="form-control" placeholder="Ejemplo: Localidad">
        </div>

        <!-- Campo Dirección -->
        <div class="input-group flex-nowrap w-75">
            <label for="direccion" class="input-group-text" id="addon-wrapping">Dirección</label>
            <input type="text" name="data[direccion]" id="direccion" value="<?= htmlspecialchars($oldData['direccion'] ?? '') ?>" class="form-control" placeholder="Ejemplo: Calle Veleta">
        </div>

        <!-- Botones -->
        <div class="d-flex flex-row justify-content-center gap-3 w-100">
            <input type="submit" value="Realizar Pedido" class="btn btn-primary w-25">
            <input type="reset" value="Borrar todo" class="btn btn-outline-secondary w-25">
        </div>
    </form>

    <?php endif; ?>
<?php else: ?>
    <p>Tu carrito está vacío.</p>
<?php endif; ?>
