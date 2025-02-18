<h1>Gestor de pedidos</h1>
<?php
    $oldData = $_SESSION['old_data'] ?? [];
    $errores = $_SESSION['errores'] ?? [];
    unset($_SESSION['old_data'], $_SESSION['errores']);
?>
<?php if (!empty($orders)): ?>
    <ul class="list-group list-group-flush">
        <?php foreach ($orders as $order): ?>
            <li class="list-group-item d-flex flex-row justify-content-between align-items-center p-4 gap-5 mt-4" style="width: 100%;">
                <div class="d-flex flex-wrap gap-3 w-75">
                    <p class="m-0"><b>Id:</b> <?=$order['id']?></p>
                    <p class="m-0"><b>Usuario:</b> <?=$order['usuario_id']?></p>
                    <p class="m-0"><b>Provincia:</b> <?=$order['provincia']?></p>
                    <p class="m-0"><b>Localidad:</b> <?=$order['localidad']?></p>
                    <p class="m-0"><b>Direccion:</b> <?=$order['direccion']?></p>
                    <p class="m-0"><b>Coste:</b> <?=$order['coste']?></p>
                    <p class="m-0"><b>Estado:</b> <?=$order['estado']?></p>
                    <p class="m-0"><b>Fecha:</b> <?=$order['fecha']?></p>
                    <p class="m-0"><b>Hora:</b> <?=$order['hora']?></p>
                </div>
                <div class="d-flex flex-row justify-content-between align-items-center gap-5">
                    <form action="<?=BASE_URL?>UpdateStatusOrder" method="POST">
                        <input type="hidden" name="id" value="<?=$order['id']?>">
                        <select name="estado" id="estado">
                            <option  selected disabled>Seleccione un estado</option>
                            <option value="confirmado">confirmado</option>
                            <option value="enviado">enviado</option>
                            <option value="entregado">entregado</option>
                        </select>

                        <input type="submit" value="Confirmar cambio">
                    </form>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No hay pedidos disponibles.</p>
<?php endif; ?>