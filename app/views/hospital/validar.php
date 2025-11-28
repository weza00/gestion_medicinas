<?php if(empty($datos['pendientes'])): ?>
    <div style="text-align: center; padding: 50px; background: white; border-radius: 12px; box-shadow: var(--shadow);">
        <div style="font-size: 3rem;">✅</div>
        <h3>Todo al día</h3>
        <p>No hay recetas pendientes de validación en este momento.</p>
    </div>
<?php else: ?>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Paciente</th>
                    <th>Fecha Solicitud</th>
                    <th>Medicamentos</th>
                    <th>Total</th>
                    <th>Receta</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($datos['pendientes'] as $p): ?>
                    <tr>
                        <td><strong>#<?php echo $p->id; ?></strong></td>
                        <td style="font-weight: bold;"><?php echo $p->usuario_nombre; ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($p->creado_en)); ?></td>
                        <td>
                            <button onclick="toggleDetalles(<?php echo $p->id; ?>)" class="btn btn-outline" style="font-size: 0.8rem; padding: 5px 10px;">
                                📋 Ver Detalles (<?php echo $p->total_medicamentos; ?> items)
                            </button>
                        </td>
                        <td style="font-weight: bold; color: var(--primary);">$<?php echo number_format($p->total_precio, 2); ?></td>
                        <td>
                            <a href="<?php echo BASE_URL; ?>/uploads/<?php echo $p->receta_archivo; ?>" target="_blank" class="btn btn-outline" style="border-color: var(--primary); color: var(--primary); font-size: 0.8rem; padding: 5px 10px;">
                                👁 Receta
                            </a>
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                <a href="<?php echo BASE_URL; ?>/hospital/aprobar/<?php echo $p->id; ?>" class="btn btn-success" style="font-size: 0.8rem; padding: 6px 12px;" onclick="return confirm('¿Aprobar y generar código de retiro?');">
                                    ✔ Aprobar
                                </a>
                                <a href="<?php echo BASE_URL; ?>/hospital/rechazar/<?php echo $p->id; ?>" class="btn btn-danger" style="font-size: 0.8rem; padding: 6px 12px;" onclick="return confirm('¿Rechazar solicitud?\n\nEsto restaurará el stock de los medicamentos.');">
                                    ✖ Rechazar
                                </a>
                            </div>
                        </td>
                    </tr>
                    <!-- Fila de detalles expandible -->
                    <tr id="detalles-<?php echo $p->id; ?>" style="display: none;">
                        <td colspan="7" style="padding: 0;">
                            <div style="background: #f8f9fa; padding: 20px; border: 2px solid #e9ecef;">
                                <h4 style="margin: 0 0 15px 0; color: var(--primary-dark);">📋 Detalles del Pedido #<?php echo $p->id; ?></h4>
                                
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                                    <div>
                                        <strong>👤 Paciente:</strong> <?php echo $p->usuario_nombre; ?><br>
                                        <strong>📅 Fecha:</strong> <?php echo date('d/m/Y H:i', strtotime($p->creado_en)); ?><br>
                                        <strong>📄 Receta:</strong> 
                                        <a href="<?php echo BASE_URL; ?>/uploads/<?php echo $p->receta_archivo; ?>" target="_blank" style="color: var(--primary);">
                                            Abrir archivo médico
                                        </a>
                                    </div>
                                    <div>
                                        <strong>💊 Total medicamentos:</strong> <?php echo $p->total_medicamentos; ?> unidades<br>
                                        <strong>💰 Valor total:</strong> $<?php echo number_format($p->total_precio, 2); ?><br>
                                        <strong>🏥 Estado:</strong> <span style="color: var(--warning); font-weight: bold;">Pendiente validación</span>
                                    </div>
                                </div>

                                <h5 style="margin: 15px 0 10px 0; color: var(--primary-dark);">Medicamentos solicitados:</h5>
                                <div style="background: white; border: 1px solid #ddd; border-radius: 8px; overflow: hidden;">
                                    <table style="width: 100%; margin: 0;">
                                        <thead style="background: #e9ecef;">
                                            <tr>
                                                <th style="padding: 10px; text-align: left;">Medicamento</th>
                                                <th style="padding: 10px; text-align: left;">Categoría</th>
                                                <th style="padding: 10px; text-align: center;">Cantidad</th>
                                                <th style="padding: 10px; text-align: right;">Precio Unit.</th>
                                                <th style="padding: 10px; text-align: right;">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($p->detalles as $detalle): ?>
                                                <tr>
                                                    <td style="padding: 10px;">
                                                        <strong><?php echo $detalle->medicamento_nombre; ?></strong><br>
                                                        <small style="color: var(--text-light);"><?php echo $detalle->presentacion; ?></small>
                                                    </td>
                                                    <td style="padding: 10px;">
                                                        <span style="background: var(--primary); color: white; padding: 2px 8px; border-radius: 10px; font-size: 0.75rem;">
                                                            <?php echo $detalle->categoria_nombre; ?>
                                                        </span>
                                                    </td>
                                                    <td style="padding: 10px; text-align: center; font-weight: bold;">
                                                        <?php echo $detalle->cantidad; ?>
                                                    </td>
                                                    <td style="padding: 10px; text-align: right;">
                                                        $<?php echo number_format($detalle->precio, 2); ?>
                                                    </td>
                                                    <td style="padding: 10px; text-align: right; font-weight: bold;">
                                                        $<?php echo number_format($detalle->cantidad * $detalle->precio, 2); ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tfoot style="background: #f8f9fa; border-top: 2px solid #dee2e6;">
                                            <tr>
                                                <td colspan="4" style="padding: 12px; font-weight: bold; text-align: right;">
                                                    TOTAL DEL PEDIDO:
                                                </td>
                                                <td style="padding: 12px; text-align: right; font-weight: bold; color: var(--primary); font-size: 1.1rem;">
                                                    $<?php echo number_format($p->total_precio, 2); ?>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <div style="text-align: center; margin-top: 20px; padding-top: 15px; border-top: 1px solid #ddd;">
                                    <div style="display: flex; gap: 15px; justify-content: center;">
                                        <a href="<?php echo BASE_URL; ?>/hospital/aprobar/<?php echo $p->id; ?>" 
                                           class="btn btn-success" 
                                           onclick="return confirm('¿Aprobar pedido #<?php echo $p->id; ?> por $<?php echo number_format($p->total_precio, 2); ?>?\n\nSe generará un código de retiro.');">
                                            ✔ Aprobar Pedido
                                        </a>
                                        <a href="<?php echo BASE_URL; ?>/hospital/rechazar/<?php echo $p->id; ?>" 
                                           class="btn btn-danger" 
                                           onclick="return confirm('¿Rechazar pedido #<?php echo $p->id; ?>?\n\nSe restaurará el stock de todos los medicamentos.');">
                                            ✖ Rechazar Pedido
                                        </a>
                                        <button onclick="toggleDetalles(<?php echo $p->id; ?>)" class="btn btn-secondary">
                                            ⬆️ Ocultar Detalles
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<script>
function toggleDetalles(pedidoId) {
    const fila = document.getElementById('detalles-' + pedidoId);
    if (fila.style.display === 'none' || fila.style.display === '') {
        fila.style.display = 'table-row';
    } else {
        fila.style.display = 'none';
    }
}
</script>

<style>
.table-container table {
    width: 100%;
    border-collapse: collapse;
    background: white;
}

.table-container th,
.table-container td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.table-container th {
    background: #f8f9fa;
    font-weight: 600;
    color: var(--primary-dark);
}

.table-container tr:hover {
    background: #f8f9fa;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
}

@media (max-width: 768px) {
    .table-container {
        overflow-x: auto;
    }
    
    .table-container th,
    .table-container td {
        padding: 8px;
        font-size: 0.9rem;
    }
}
</style>