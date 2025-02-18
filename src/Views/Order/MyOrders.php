<h1>Gestor de pedidos</h1>
<?php
    $oldData = $_SESSION['old_data'] ?? [];
    $errores = $_SESSION['errores'] ?? [];
    unset($_SESSION['old_data'], $_SESSION['errores']);
?>
<?php if (!empty($orders)): ?>
    <ul class="list-group list-group-flush">
        <?php foreach ($orders as $order): ?>
            <li class="list-group-item d-flex flex-row justify-content-start align-items-center p-4 gap-5 mt-4" style="width: 100%;">
                <div class="d-flex flex-wrap gap-3">
                    <p class="m-0"><b>Id:</b> <?=$order['id']?></p>
                    <p class="m-0"><b>Provincia:</b> <?=$order['provincia']?></p>
                    <p class="m-0"><b>Localidad:</b> <?=$order['localidad']?></p>
                    <p class="m-0"><b>Direccion:</b> <?=$order['direccion']?></p>
                    <p class="m-0"><b>Coste:</b> <?=$order['coste']?></p>
                    <p class="m-0"><b>Estado:</b> <?=$order['estado']?></p>
                    <p class="m-0"><b>Fecha:</b> <?=$order['fecha']?></p>
                    <p class="m-0"><b>Hora:</b> <?=$order['hora']?></p>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No hay pedidos disponibles.</p>
<?php endif; ?>