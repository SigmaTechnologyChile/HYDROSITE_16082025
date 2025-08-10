-- ============================================================================
-- COMANDO SQL SIMPLIFICADO PARA RECREAR TABLA MOVIMIENTOS
-- Sin Foreign Keys problemáticas - Estructura base funcional
-- ============================================================================

-- Eliminar tabla si existe (para recrear)
DROP TABLE IF EXISTS `movimientos`;

CREATE TABLE `movimientos` (
    -- Primary Key
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    
    -- Organización (sin FK por ahora)
    `org_id` BIGINT UNSIGNED NOT NULL,
    
    -- Campos Core/Básicos
    `fecha` DATE NOT NULL,
    `tipo` ENUM('ingreso', 'egreso', 'giro', 'deposito', 'transferencia') NOT NULL,
    `monto` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `descripcion` TEXT NULL,
    `nro_dcto` VARCHAR(100) NULL COMMENT 'Número de documento/comprobante',
    
    -- Campos de Categorización
    `categoria_id` BIGINT UNSIGNED NULL COMMENT 'FK a tabla categorias',
    `categoria` VARCHAR(100) NULL COMMENT 'Categoría directa desde formularios',
    
    -- Campos de Cuentas/Bancarios
    `cuenta_origen_id` BIGINT UNSIGNED NULL COMMENT 'FK a tabla cuentas',
    `cuenta_destino_id` BIGINT UNSIGNED NULL COMMENT 'FK a tabla cuentas',
    `cuenta` VARCHAR(100) NULL COMMENT 'Identificador de cuenta',
    `banco_id` BIGINT UNSIGNED NULL COMMENT 'FK a tabla bancos',
    
    -- Campos Tabulares (Libro de Caja)
    `total_consumo` DECIMAL(15,2) NULL DEFAULT 0.00,
    `cuotas_incorporacion` DECIMAL(15,2) NULL DEFAULT 0.00,
    `energia_electrica` DECIMAL(15,2) NULL DEFAULT 0.00,
    `giros` DECIMAL(15,2) NULL DEFAULT 0.00,
    `depositos` DECIMAL(15,2) NULL DEFAULT 0.00,
    `saldo_inicial` DECIMAL(15,2) NULL DEFAULT 0.00,
    `saldo_final` DECIMAL(15,2) NULL DEFAULT 0.00,
    
    -- Campos de Estado y Control
    `estado` ENUM('activo', 'anulado', 'pendiente') NOT NULL DEFAULT 'activo',
    `conciliado` BOOLEAN NOT NULL DEFAULT FALSE,
    `observaciones` TEXT NULL,
    
    -- Campos de Auditoría
    `created_by` BIGINT UNSIGNED NULL COMMENT 'FK a tabla users',
    `updated_by` BIGINT UNSIGNED NULL COMMENT 'FK a tabla users',
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Primary Key
    PRIMARY KEY (`id`),
    
    -- Índices para optimización (sin FK constraints)
    INDEX `idx_movimientos_org_id` (`org_id`),
    INDEX `idx_movimientos_fecha` (`fecha`),
    INDEX `idx_movimientos_tipo` (`tipo`),
    INDEX `idx_movimientos_org_fecha` (`org_id`, `fecha`),
    INDEX `idx_movimientos_org_tipo` (`org_id`, `tipo`),
    INDEX `idx_movimientos_nro_dcto` (`nro_dcto`),
    INDEX `idx_movimientos_estado` (`estado`),
    INDEX `idx_movimientos_conciliado` (`conciliado`)
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
  COMMENT='Tabla de movimientos financieros - Estructura base sin FK constraints';

-- ============================================================================
-- COMANDO PARA VERIFICAR TABLA CREADA
-- ============================================================================
SELECT 'Tabla movimientos creada exitosamente' AS resultado;
DESCRIBE movimientos;

-- ============================================================================
-- INSTRUCCIONES PARA AGREGAR FOREIGN KEYS DESPUÉS
-- ============================================================================
/*
DESPUÉS DE CREAR LA TABLA BASE, EJECUTAR ESTOS COMANDOS PARA VERIFICAR
QUE TABLAS EXISTEN Y AGREGAR LAS FOREIGN KEYS CORRESPONDIENTES:

1. Verificar tablas existentes:
   SHOW TABLES;

2. Si existe tabla 'orgs':
   ALTER TABLE movimientos ADD CONSTRAINT fk_movimientos_org_id 
   FOREIGN KEY (org_id) REFERENCES orgs(id) ON DELETE CASCADE;

3. Si existe tabla 'users':
   ALTER TABLE movimientos ADD CONSTRAINT fk_movimientos_created_by 
   FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL;
   
   ALTER TABLE movimientos ADD CONSTRAINT fk_movimientos_updated_by 
   FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL;

4. Si existen otras tablas (categorias, cuentas, bancos):
   ALTER TABLE movimientos ADD CONSTRAINT fk_movimientos_categoria_id 
   FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL;
   
   -- Y así sucesivamente para cada tabla que exista
*/
