-- ================================================================
-- RECREAR TABLA MOVIMIENTOS COMPLETA Y OPTIMIZADA
-- ================================================================
-- IMPORTANTE: Hacer backup de los datos existentes antes de ejecutar

-- 1. GUARDAR DATOS EXISTENTES (OPCIONAL - SOLO SI HAY DATOS IMPORTANTES)
-- CREATE TABLE movimientos_backup AS SELECT * FROM movimientos;

-- 2. ELIMINAR TABLA EXISTENTE
DROP TABLE IF EXISTS movimientos;

-- 3. CREAR TABLA MOVIMIENTOS OPTIMIZADA Y COMPLETA
CREATE TABLE movimientos (
    -- CAMPOS PRINCIPALES
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    org_id BIGINT(20) UNSIGNED NOT NULL,
    
    -- INFORMACIÓN DEL MOVIMIENTO
    tipo ENUM('ingreso', 'egreso', 'transferencia') NOT NULL,
    subtipo ENUM('giro', 'deposito', 'transferencia_interna', 'transferencia_externa', 'ajuste', 'correccion') NULL,
    fecha DATE NOT NULL,
    monto DECIMAL(15,2) NOT NULL,
    descripcion TEXT NOT NULL,
    nro_dcto VARCHAR(100) NOT NULL,
    
    -- RELACIONES
    categoria_id BIGINT(20) UNSIGNED NULL,
    cuenta_origen_id BIGINT(20) UNSIGNED NULL,
    cuenta_destino_id BIGINT(20) UNSIGNED NULL,
    
    -- INFORMACIÓN ADICIONAL
    proveedor VARCHAR(100) NULL,
    rut_proveedor VARCHAR(20) NULL,
    referencia_externa VARCHAR(100) NULL,
    metodo_pago ENUM('efectivo', 'transferencia', 'cheque', 'tarjeta', 'otro') NULL,
    banco_origen VARCHAR(100) NULL,
    banco_destino VARCHAR(100) NULL,
    
    -- CONTROL Y AUDITORÍA
    estado ENUM('activo', 'anulado', 'reversado') NOT NULL DEFAULT 'activo',
    transferencia_id BIGINT(20) UNSIGNED NULL,
    usuario_id BIGINT(20) UNSIGNED NULL,
    ip_origen VARCHAR(45) NULL,
    motivo_anulacion TEXT NULL,
    
    -- COLUMNAS ESPECÍFICAS PARA LIBRO TABULAR (OPCIONAL - SEGÚN TU SISTEMA)
    total_consumo DECIMAL(15,2) DEFAULT 0.00,
    cuotas_incorporacion DECIMAL(15,2) DEFAULT 0.00,
    otros_ingresos DECIMAL(15,2) DEFAULT 0.00,
    giros DECIMAL(15,2) DEFAULT 0.00,
    energia_electrica DECIMAL(15,2) DEFAULT 0.00,
    sueldos_leyes DECIMAL(15,2) DEFAULT 0.00,
    otros_gastos_operacion DECIMAL(15,2) DEFAULT 0.00,
    gastos_mantencion DECIMAL(15,2) DEFAULT 0.00,
    gastos_administracion DECIMAL(15,2) DEFAULT 0.00,
    gastos_mejoramiento DECIMAL(15,2) DEFAULT 0.00,
    otros_egresos DECIMAL(15,2) DEFAULT 0.00,
    depositos DECIMAL(15,2) DEFAULT 0.00,
    
    -- TIMESTAMPS
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- ÍNDICES PARA PERFORMANCE
    INDEX idx_movimientos_org_fecha (org_id, fecha),
    INDEX idx_movimientos_tipo_subtipo (tipo, subtipo),
    INDEX idx_movimientos_estado (estado),
    INDEX idx_movimientos_usuario (usuario_id),
    INDEX idx_movimientos_fecha_rango (fecha, created_at),
    INDEX idx_movimientos_categoria (categoria_id),
    INDEX idx_movimientos_cuenta_origen (cuenta_origen_id),
    INDEX idx_movimientos_cuenta_destino (cuenta_destino_id),
    INDEX idx_movimientos_proveedor (proveedor),
    INDEX idx_movimientos_nro_dcto (nro_dcto),
    
    -- FOREIGN KEYS
    CONSTRAINT fk_movimientos_org FOREIGN KEY (org_id) REFERENCES orgs(id) ON DELETE CASCADE,
    CONSTRAINT fk_movimientos_categoria FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL,
    CONSTRAINT fk_movimientos_cuenta_origen FOREIGN KEY (cuenta_origen_id) REFERENCES cuentas(id) ON DELETE SET NULL,
    CONSTRAINT fk_movimientos_cuenta_destino FOREIGN KEY (cuenta_destino_id) REFERENCES cuentas(id) ON DELETE SET NULL,
    CONSTRAINT fk_movimientos_usuario FOREIGN KEY (usuario_id) REFERENCES users(id) ON DELETE SET NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. CREAR TABLA DE AUDITORÍA PARA MOVIMIENTOS
CREATE TABLE movimientos_auditoria (
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    movimiento_id BIGINT(20) UNSIGNED NOT NULL,
    accion ENUM('creado', 'modificado', 'anulado', 'reversado') NOT NULL,
    datos_anteriores JSON NULL,
    datos_nuevos JSON NULL,
    usuario_id BIGINT(20) UNSIGNED NULL,
    ip_origen VARCHAR(45) NULL,
    user_agent TEXT NULL,
    observaciones TEXT NULL,
    fecha_accion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_auditoria_movimiento (movimiento_id),
    INDEX idx_auditoria_fecha (fecha_accion),
    INDEX idx_auditoria_usuario (usuario_id),
    
    FOREIGN KEY (movimiento_id) REFERENCES movimientos(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES users(id) ON DELETE SET NULL
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. CREAR TABLA DE CONCILIACIÓN BANCARIA
CREATE TABLE conciliaciones_bancarias (
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    org_id BIGINT(20) UNSIGNED NOT NULL,
    cuenta_id BIGINT(20) UNSIGNED NOT NULL,
    periodo_año INT(4) NOT NULL,
    periodo_mes INT(2) NOT NULL,
    saldo_libro DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    saldo_banco DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    diferencia DECIMAL(15,2) GENERATED ALWAYS AS (saldo_banco - saldo_libro) STORED,
    estado ENUM('pendiente', 'conciliado', 'con_diferencias') NOT NULL DEFAULT 'pendiente',
    fecha_conciliacion DATE NULL,
    usuario_conciliacion BIGINT(20) UNSIGNED NULL,
    observaciones TEXT NULL,
    archivo_extracto VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    UNIQUE KEY unique_periodo_cuenta (org_id, cuenta_id, periodo_año, periodo_mes),
    INDEX idx_conciliacion_org (org_id),
    INDEX idx_conciliacion_cuenta (cuenta_id),
    INDEX idx_conciliacion_periodo (periodo_año, periodo_mes),
    
    FOREIGN KEY (org_id) REFERENCES orgs(id) ON DELETE CASCADE,
    FOREIGN KEY (cuenta_id) REFERENCES cuentas(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_conciliacion) REFERENCES users(id) ON DELETE SET NULL
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. INSERTAR DATOS DE PRUEBA (OPCIONAL)
INSERT INTO movimientos (
    org_id, tipo, fecha, monto, descripcion, nro_dcto, estado, created_at
) VALUES 
(1, 'ingreso', '2025-08-10', 100000.00, 'Movimiento de prueba inicial', 'INIT-001', 'activo', NOW());

-- ================================================================
-- VERIFICACIÓN FINAL
-- ================================================================

-- Verificar estructura de la tabla
DESCRIBE movimientos;

-- Verificar foreign keys
SELECT 
    CONSTRAINT_NAME,
    TABLE_NAME,
    COLUMN_NAME,
    REFERENCED_TABLE_NAME,
    REFERENCED_COLUMN_NAME
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
WHERE TABLE_NAME = 'movimientos' 
  AND CONSTRAINT_NAME LIKE 'fk_%';

-- Verificar que las tablas se crearon correctamente
SHOW TABLES LIKE '%movimientos%';
SHOW TABLES LIKE 'conciliaciones_bancarias';

-- ================================================================
-- SCRIPT COMPLETADO
-- ================================================================
