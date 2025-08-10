-- SCRIPT DE MEJORAS PARA TABLA MOVIMIENTOS
-- Ejecutar en phpMyAdmin o cliente MySQL

-- 1. AGREGAR CAMPOS CRÍTICOS FALTANTES
ALTER TABLE movimientos 
ADD COLUMN org_id BIGINT(20) UNSIGNED NOT NULL AFTER id,
ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER creado_en,
ADD COLUMN estado ENUM('activo', 'anulado', 'reversado') NOT NULL DEFAULT 'activo' AFTER transferencia_id,
ADD COLUMN usuario_id BIGINT(20) UNSIGNED NULL AFTER estado,
ADD COLUMN ip_origen VARCHAR(45) NULL AFTER usuario_id,
ADD COLUMN motivo_anulacion TEXT NULL AFTER ip_origen;

-- 2. EXPANDIR ENUMS PARA MÁS FLEXIBILIDAD
ALTER TABLE movimientos 
MODIFY COLUMN subtipo ENUM('giro', 'deposito', 'transferencia_interna', 'transferencia_externa', 'ajuste', 'correccion') NULL;

-- 3. AGREGAR CAMPOS PARA MEJOR TRAZABILIDAD
ALTER TABLE movimientos 
ADD COLUMN referencia_externa VARCHAR(100) NULL AFTER motivo_anulacion,
ADD COLUMN metodo_pago ENUM('efectivo', 'transferencia', 'cheque', 'tarjeta', 'otro') NULL AFTER referencia_externa,
ADD COLUMN banco_origen VARCHAR(100) NULL AFTER metodo_pago,
ADD COLUMN banco_destino VARCHAR(100) NULL AFTER banco_origen;

-- 4. MEJORAR CAMPOS EXISTENTES
ALTER TABLE movimientos 
MODIFY COLUMN descripcion TEXT NOT NULL,
MODIFY COLUMN nro_dcto VARCHAR(100) NOT NULL;

-- 5. AGREGAR ÍNDICES PARA PERFORMANCE
CREATE INDEX idx_movimientos_org_fecha ON movimientos(org_id, fecha);
CREATE INDEX idx_movimientos_tipo_subtipo ON movimientos(tipo, subtipo);
CREATE INDEX idx_movimientos_estado ON movimientos(estado);
CREATE INDEX idx_movimientos_usuario ON movimientos(usuario_id);
CREATE INDEX idx_movimientos_fecha_rango ON movimientos(fecha, creado_en);

-- 6. AGREGAR FOREIGN KEYS
ALTER TABLE movimientos 
ADD CONSTRAINT fk_movimientos_org FOREIGN KEY (org_id) REFERENCES orgs(id) ON DELETE CASCADE,
ADD CONSTRAINT fk_movimientos_categoria FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL,
ADD CONSTRAINT fk_movimientos_cuenta_origen FOREIGN KEY (cuenta_origen_id) REFERENCES cuentas(id) ON DELETE SET NULL,
ADD CONSTRAINT fk_movimientos_cuenta_destino FOREIGN KEY (cuenta_destino_id) REFERENCES cuentas(id) ON DELETE SET NULL,
ADD CONSTRAINT fk_movimientos_usuario FOREIGN KEY (usuario_id) REFERENCES users(id) ON DELETE SET NULL;

-- 7. CREAR TABLA DE AUDITORÍA PARA MOVIMIENTOS
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

-- 8. CREAR TABLA DE CONCILIACIÓN BANCARIA
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
