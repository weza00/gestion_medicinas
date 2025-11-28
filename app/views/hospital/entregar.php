<div class="search-hero" style="background: white; padding: 40px; border-radius: 15px; box-shadow: var(--shadow); text-align: center; max-width: 600px; margin: 0 auto;">
    <h2 style="color: var(--primary-dark);">Entrega de Medicamentos</h2>
    <p style="margin-bottom: 20px;">Ingrese el código presentado por el paciente</p>
    
    <form action="<?php echo BASE_URL; ?>/hospital/entregar" method="POST">
        <div class="form-group">
            <input type="text" name="codigo" class="input-code" placeholder="MED-XXXXXX" required autocomplete="off" style="font-size: 1.5rem; letter-spacing: 2px; text-transform: uppercase; text-align: center; border: 2px solid #eee; width: 100%; padding: 15px;">
        </div>
        <button type="submit" class="btn btn-primary btn-block" style="font-size: 1.2rem; padding: 15px;">BUSCAR PEDIDO</button>
    </form>

    <?php if($datos['error']): ?>
        <div class="alert alert-error mt-20">
            ⚠ <?php echo $datos['error']; ?>
        </div>
    <?php endif; ?>
</div>

<?php if(isset($datos['resultado']) && $datos['resultado']): ?>
    <div class="auth-card" style="max-width: 800px; margin: 30px auto; border-top: 5px solid var(--success);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 style="color: var(--success); margin: 0;">Pedido Listo para Entrega</h2>
            <span style="font-size: 2rem;">💊</span>
        </div>
        
        <hr style="margin: 15px 0; border: 0; border-top: 1px dashed #ccc;">

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <p><strong>👤 Paciente:</strong> <?php echo $datos['resultado']->usuario_nombre; ?></p>
                <p><strong>🔑 Código:</strong> <span style="font-family: monospace; background: #eee; padding: 4px 8px; border-radius: 4px; font-weight: bold;"><?php echo $datos['resultado']->codigo_retiro; ?></span></p>
            </div>
            <div>
                <p><strong>💊 Total medicamentos:</strong> <?php echo $datos['resultado']->total_medicamentos; ?> unidades</p>
                <p><strong>💰 Valor total:</strong> $<?php echo number_format($datos['resultado']->total_precio, 2); ?></p>
            </div>
        </div>

        <div class="alert" style="background: #d4edda; border: 1px solid #c3e6cb; color: #155724; margin: 20px 0; padding: 15px; border-radius: 8px;">
            <strong>✅ ESTADO:</strong> Pedido validado y listo para entrega<br>
            <strong>⚠️ VERIFICAR:</strong> Identidad del paciente antes de entregar los medicamentos
        </div>

        <h4 style="color: var(--primary-dark); margin: 20px 0 15px 0;">📋 Medicamentos a entregar:</h4>
        
        <div style="background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; overflow: hidden; margin-bottom: 25px;">
            <table style="width: 100%; margin: 0;">
                <thead style="background: #e9ecef;">
                    <tr>
                        <th style="padding: 12px; text-align: left;">Medicamento</th>
                        <th style="padding: 12px; text-align: left;">Categoría</th>
                        <th style="padding: 12px; text-align: center;">Cantidad</th>
                        <th style="padding: 12px; text-align: right;">Precio Unit.</th>
                        <th style="padding: 12px; text-align: right;">Subtotal</th>
                    </tr>
                </thead>
                <tbody style="background: white;">
                    <?php foreach($datos['resultado']->detalles as $detalle): ?>
                        <tr>
                            <td style="padding: 12px; border-bottom: 1px solid #eee;">
                                <strong><?php echo $detalle->medicamento_nombre; ?></strong><br>
                                <small style="color: var(--text-light);"><?php echo $detalle->presentacion; ?></small><br>
                                <small style="color: var(--text-light); font-style: italic;"><?php echo substr($detalle->descripcion, 0, 60); ?>...</small>
                            </td>
                            <td style="padding: 12px; border-bottom: 1px solid #eee;">
                                <span style="background: var(--primary); color: white; padding: 3px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: bold;">
                                    <?php echo $detalle->categoria_nombre; ?>
                                </span>
                            </td>
                            <td style="padding: 12px; text-align: center; border-bottom: 1px solid #eee;">
                                <span style="background: var(--success); color: white; padding: 4px 10px; border-radius: 20px; font-weight: bold;">
                                    <?php echo $detalle->cantidad; ?>
                                </span>
                            </td>
                            <td style="padding: 12px; text-align: right; border-bottom: 1px solid #eee;">
                                $<?php echo number_format($detalle->precio, 2); ?>
                            </td>
                            <td style="padding: 12px; text-align: right; border-bottom: 1px solid #eee; font-weight: bold;">
                                $<?php echo number_format($detalle->cantidad * $detalle->precio, 2); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot style="background: #f8f9fa; border-top: 2px solid #dee2e6;">
                    <tr>
                        <td colspan="4" style="padding: 15px; font-weight: bold; text-align: right; font-size: 1.1rem;">
                            TOTAL A ENTREGAR:
                        </td>
                        <td style="padding: 15px; text-align: right; font-weight: bold; color: var(--primary); font-size: 1.3rem;">
                            $<?php echo number_format($datos['resultado']->total_precio, 2); ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div style="background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; padding: 15px; border-radius: 8px; margin: 20px 0;">
            <strong>📋 Instrucciones para la entrega:</strong><br>
            1. Verificar identidad del paciente con documento oficial<br>
            2. Confirmar que el código coincide: <strong><?php echo $datos['resultado']->codigo_retiro; ?></strong><br>
            3. Entregar todos los medicamentos listados arriba<br>
            4. Explicar posología si es necesario<br>
            5. Hacer clic en "CONFIRMAR ENTREGA" al finalizar
        </div>

        <div style="text-align: center; margin-top: 25px;">
            <a href="<?php echo BASE_URL; ?>/hospital/confirmar_entrega/<?php echo $datos['resultado']->id; ?>" 
               class="btn btn-success" 
               style="padding: 15px 30px; font-size: 1.2rem; font-weight: bold;"
               onclick="return confirm('¿Confirmar que se entregaron TODOS los medicamentos al paciente <?php echo $datos['resultado']->usuario_nombre; ?>?');">
                ✅ CONFIRMAR ENTREGA COMPLETA
            </a>
        </div>
    </div>
<?php endif; ?>