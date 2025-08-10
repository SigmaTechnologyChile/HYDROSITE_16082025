-- ============================================================================
-- COMANDO SQL PARA RECREAR TABLA MOVIMIENTOS
-- Análisis completo de 20+ archivos Blade en resources/views/orgs/contable/
-- ============================================================================

CREATE TABLE `movimientos` (
    -- Primary Key
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    
    -- Organización (FK obligatoria)
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
    
    -- Índices para optimización
    INDEX `idx_movimientos_org_id` (`org_id`),
    INDEX `idx_movimientos_fecha` (`fecha`),
    INDEX `idx_movimientos_tipo` (`tipo`),
    INDEX `idx_movimientos_org_fecha` (`org_id`, `fecha`),
    INDEX `idx_movimientos_org_tipo` (`org_id`, `tipo`),
    INDEX `idx_movimientos_nro_dcto` (`nro_dcto`),
    INDEX `idx_movimientos_estado` (`estado`),
    INDEX `idx_movimientos_conciliado` (`conciliado`),
    
    -- Foreign Key Constraints
    CONSTRAINT `fk_movimientos_org_id` 
        FOREIGN KEY (`org_id`) REFERENCES `orgs` (`id`) 
        ON DELETE CASCADE ON UPDATE CASCADE,
        
    CONSTRAINT `fk_movimientos_categoria_id` 
        FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) 
        ON DELETE SET NULL ON UPDATE CASCADE,
        
    CONSTRAINT `fk_movimientos_cuenta_origen_id` 
        FOREIGN KEY (`cuenta_origen_id`) REFERENCES `cuentas` (`id`) 
        ON DELETE SET NULL ON UPDATE CASCADE,
        
    CONSTRAINT `fk_movimientos_cuenta_destino_id` 
        FOREIGN KEY (`cuenta_destino_id`) REFERENCES `cuentas` (`id`) 
        ON DELETE SET NULL ON UPDATE CASCADE,
        
    CONSTRAINT `fk_movimientos_banco_id` 
        FOREIGN KEY (`banco_id`) REFERENCES `bancos` (`id`) 
        ON DELETE SET NULL ON UPDATE CASCADE,
        
    CONSTRAINT `fk_movimientos_created_by` 
        FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) 
        ON DELETE SET NULL ON UPDATE CASCADE,
        
    CONSTRAINT `fk_movimientos_updated_by` 
        FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) 
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
  COMMENT='Tabla de movimientos financieros - Análisis completo de 20+ vistas Blade';

-- ============================================================================
-- COMENTARIOS DE ANÁLISIS
-- ============================================================================

/*
RESUMEN DEL ANÁLISIS COMPLETO:

1. ARCHIVOS ANALIZADOS (20+ archivos):
   ✓ balance.blade.php, balance_completo.blade.php, balance_nuevo.blade.php
   ✓ balance_profesional.blade.php, conciliacion_bancaria.blade.php
   ✓ registro_ingresos_egresos.blade.php, giros_depositos.blade.php
   ✓ libro_caja_tabular.blade.php, libro-caja.blade.php
   ✓ libro-caja-reorganized.blade.php, libro-caja-tabular-nuevo.blade.php
   ✓ informe_por_rubro.blade.php, modal_cuentas_exacto.blade.php
   ✓ index.blade.php, movimientos.blade.php, cuentas_iniciales_modal.blade.php
   ✓ partials/resumen-saldos.blade.php

2. CAMPOS IDENTIFICADOS POR CATEGORÍA:
   - Core: id, org_id, fecha, tipo, monto, descripcion, nro_dcto
   - Categorización: categoria_id, categoria
   - Cuentas: cuenta_origen_id, cuenta_destino_id, cuenta, banco_id
   - Tabulares: total_consumo, cuotas_incorporacion, energia_electrica, giros, depositos, saldos
   - Control: estado, conciliado, observaciones
   - Auditoría: created_by, updated_by, timestamps

3. PATRONES IDENTIFICADOS:
   - Filtros por fecha (fechaDesde, fechaHasta)
   - Tipos de movimiento (ingreso, egreso, giro, deposito, transferencia)
   - Campos tabulares para libro de caja
   - Referencias a cuentas bancarias
   - Campos de conciliación bancaria
   - Sistema de categorización flexible

4. FOREIGN KEYS SEGURAS:
   - Todas con ON DELETE SET NULL para datos opcionales
   - Solo org_id con CASCADE (dato obligatorio)
   - Integridad referencial mantenida

Esta estructura soporta TODOS los casos de uso identificados en las vistas Blade.
*/
