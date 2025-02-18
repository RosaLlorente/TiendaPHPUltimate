<h1>Gestor de productos</h1>
<?php
    $oldData = $_SESSION['old_data'] ?? [];
    $errores = $_SESSION['errores'] ?? [];
    unset($_SESSION['old_data'], $_SESSION['errores']);
    function Productos($products)
    {
        for( $i = 0; $i < count($products); $i++ )
        {
            yield $products[$i];
        }
    }
?>
<?php  if (!empty($products)): ?>
    <ul class="list-group list-group-flush  mt-5">
        <?php foreach (Productos( $products ) as $product): ?>
            <li class="list-group-item d-flex flex-row justify-content-between align-items-center p-4 gap-5" style="background-color: darkgrey;">
                <div class="d-flex flex-column align-items-left  gap-2">
                    <p><b>Nombre:</b> <?= htmlspecialchars($product['nombre']) ?></p>
                    <p><b>Categoria:</b><?=htmlspecialchars($product['categoria_nombre'])?> (<?= htmlspecialchars($product['categoria_id']) ?>)</p>
                    <p><b>Descripcion:</b> <?= htmlspecialchars($product['descripcion']) ?></p>
                    <p><b>Precio:</b> <?= htmlspecialchars($product['precio']) ?></p>
                    <p><b>Stock:</b> <?= htmlspecialchars($product['stock']) ?></p>
                    <p><b>Oferta:</b> <?= htmlspecialchars($product['oferta']) ?></p>
                    <p><b>Fecha:</b> <?= htmlspecialchars($product['fecha']) ?></p>
                    <img src="<?= BASE_URL ?>/public/Img/<?= htmlspecialchars($product['imagen']) ?>" alt="<?= htmlspecialchars($product['nombre']) ?>" width="100">
                </div>
                <div class="d-flex flex-row justify-content-between align-items-center gap-5 w-75">
                    <?php if ($product['id'] !== '13'): ?>
                        <a href="<?= BASE_URL ?>DeleteProduct/<?= $product['id'] ?>" class="btn btn-danger">Eliminar</a>
                        <div class="d-flex flex-column gap-5 w-75">
                            <form  action="<?= BASE_URL ?>EditProduct" method="POST" class="list-group-item d-flex flex-column gap-5 w-75">
                                <input type="hidden" name="data[id]" value="<?= $product['id'] ?>">

                                <div class="input-group flex-nowrap w-75">
                                    <label for="nombre" class="input-group-text" id="addon-wrapping">Editar nombre</label>
                                    <input type="text" name="data[nombre]" id="nombre" value="<?= htmlspecialchars($oldData['nombre'] ?? $product['nombre']) ?>" style="background-color: darkseagreen;" class="form-control" placeholder="Ejemplo: Flores" aria-label="Nombre" aria-describedby="addon-wrapping">
                                </div>

                                <div class="input-group flex-nowrap w-75">
                                    <label for="categoria_id" class="input-group-text" id="addon-wrapping">Editar categoria</label>
                                    <input type="number" name="data[categoria_id]" id="categoria_id" value="<?= htmlspecialchars($oldData['categoria_id'] ?? $product['categoria_id']) ?>" style="background-color: darkseagreen;" class="form-control" placeholder="Ejemplo: 1" aria-label="categoria_id" aria-describedby="addon-wrapping">
                                </div>

                                <div class="input-group flex-nowrap w-75">
                                    <label for="descripcion" class="input-group-text" id="addon-wrapping">Editar descripcion</label>
                                    <input type="text" name="data[descripcion]" id="descripcion" value="<?= htmlspecialchars($oldData['descripcion'] ?? $product['descripcion']) ?>" style="background-color: darkseagreen;" class="form-control" placeholder="Ejemplo: Una planta de flores" aria-label="descripcion" aria-describedby="addon-wrapping">
                                </div>

                                <div class="input-group flex-nowrap w-75">
                                    <label for="precio" class="input-group-text" id="addon-wrapping">Editar precio</label>
                                    <input type="number" name="data[precio]" id="precio" value="<?= htmlspecialchars($oldData['precio'] ?? $product['precio']) ?>" style="background-color: darkseagreen;" class="form-control" placeholder="Ejemplo: 44" aria-label="precio" aria-describedby="addon-wrapping">
                                </div>

                                <div class="input-group flex-nowrap w-75">
                                    <label for="stock" class="input-group-text" id="addon-wrapping">Editar stock</label>
                                    <input type="number" name="data[stock]" id="stock" value="<?= htmlspecialchars($oldData['stock'] ?? $product['stock']) ?>" style="background-color: darkseagreen;" class="form-control" placeholder="Ejemplo: 12" aria-label="stock" aria-describedby="addon-wrapping">
                                </div>

                                <div class="input-group flex-nowrap w-75">
                                    <label for="oferta" class="input-group-text" id="addon-wrapping">Editar oferta</label>
                                    <input type="text" name="data[oferta]" id="oferta" value="<?= htmlspecialchars($oldData['oferta'] ?? $product['oferta']) ?>" style="background-color: darkseagreen;" class="form-control" placeholder="Ejemplo: 15" aria-label="oferta" aria-describedby="addon-wrapping">
                                </div>

                                <div class="input-group flex-nowrap w-75">
                                    <label for="fecha" class="input-group-text" id="addon-wrapping">Editar fecha</label>
                                    <input type="date" name="data[fecha]" id="fecha" value="<?= htmlspecialchars($oldData['fecha'] ?? $product['fecha']) ?>" style="background-color: darkseagreen;" class="form-control" placeholder="Ejemplo: 2023-01-01" aria-label="fecha" aria-describedby="addon-wrapping">
                                </div>

                                <div class="d-flex flex-row justify-content-center gap-3 w-100">
                                    <input type="submit" value="Guardar cambios" class="btn-outline-primary w-25">
                                    <input type="reset" value="Borrar todo" class="btn-outline-primary w-25">
                                </div>
                            </form>
                            <form  action="<?= BASE_URL ?>EditImage" method="POST" enctype="multipart/form-data" class="list-group-item d-flex flex-column gap-5 w-75">
                                <input type="hidden" name="data[id]" value="<?= $product['id'] ?>">
                                <div class="input-group flex-nowrap w-100">
                                    <label for="imagen" class="input-group-text" id="addon-wrapping">Editar imagen</label>
                                    <input type="file" name="data[imagen]" id="imagen" value="<?= htmlspecialchars($oldData['imagen'] ?? null) ?>" enctype="multipart/form-data" style="background-color: darkseagreen;" class="form-control" placeholder="Ejemplo: img.jpg" aria-label="imagen" aria-describedby="addon-wrapping">
                                </div>
                                <div class="d-flex flex-row justify-content-center gap-3 w-100">
                                    <input type="submit" value="Guardar cambios" class="btn-outline-primary w-25">
                                    <input type="reset" value="Borrar todo" class="btn-outline-primary w-25">
                                </div>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No hay productos disponibles.</p>
<?php endif; ?>