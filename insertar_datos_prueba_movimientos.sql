-- ============================================================================
-- INSERTAR DATOS DE PRUEBA EN LA TABLA MOVIMIENTOS
-- ============================================================================

USE hydrosite_db;

-- Insertar algunos movimientos de prueba
INSERT INTO movimientos (
    org_id, 
    fecha, 
    tipo, 
    monto, 
    descripcion, 
    nro_dcto, 
    categoria,
    estado,
    created_at
) VALUES 
(
    864, 
    '2025-08-10', 
    'ingreso', 
    150000.00, 
    'Pago de cuotas de incorporación - Agosto 2025', 
    'ING-001', 
    'cuotas_incorporacion',
    'activo',
    NOW()
),
(
    864, 
    '2025-08-10', 
    'egreso', 
    85000.00, 
    'Pago de energía eléctrica - Mes de julio', 
    'EGR-001', 
    'energia_electrica',
    'activo',
    NOW()
),
(
    864, 
    '2025-08-09', 
    'ingreso', 
    320000.00, 
    'Total consumo agua potable - Julio 2025', 
    'ING-002', 
    'total_consumo',
    'activo',
    NOW()
);

-- ============================================================================
-- VERIFICAR LOS DATOS INSERTADOS
-- ============================================================================
SELECT 'Datos de prueba insertados exitosamente' AS resultado;

SELECT 
    id,
    org_id,
    fecha,
    tipo,
    monto,
    descripcion,
    nro_dcto,
    categoria,
    estado,
    created_at
FROM movimientos 
ORDER BY created_at DESC;
