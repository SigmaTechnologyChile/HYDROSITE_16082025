-- ============================================================================
-- AGREGAR FOREIGN KEYS A LA TABLA MOVIMIENTOS
-- Basado en las tablas existentes en la base de datos
-- ============================================================================

USE hydrosite_db;

-- 1. Foreign Key a tabla 'orgs' (OBLIGATORIA)
ALTER TABLE movimientos 
ADD CONSTRAINT fk_movimientos_org_id 
FOREIGN KEY (org_id) REFERENCES orgs(id) 
ON DELETE CASCADE ON UPDATE CASCADE;

-- 2. Foreign Key a tabla 'users' para auditoría
ALTER TABLE movimientos 
ADD CONSTRAINT fk_movimientos_created_by 
FOREIGN KEY (created_by) REFERENCES users(id) 
ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE movimientos 
ADD CONSTRAINT fk_movimientos_updated_by 
FOREIGN KEY (updated_by) REFERENCES users(id) 
ON DELETE SET NULL ON UPDATE CASCADE;

-- 3. Foreign Key a tabla 'categorias'
ALTER TABLE movimientos 
ADD CONSTRAINT fk_movimientos_categoria_id 
FOREIGN KEY (categoria_id) REFERENCES categorias(id) 
ON DELETE SET NULL ON UPDATE CASCADE;

-- 4. Foreign Key a tabla 'cuentas'
ALTER TABLE movimientos 
ADD CONSTRAINT fk_movimientos_cuenta_origen_id 
FOREIGN KEY (cuenta_origen_id) REFERENCES cuentas(id) 
ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE movimientos 
ADD CONSTRAINT fk_movimientos_cuenta_destino_id 
FOREIGN KEY (cuenta_destino_id) REFERENCES cuentas(id) 
ON DELETE SET NULL ON UPDATE CASCADE;

-- 5. Foreign Key a tabla 'bancos'
ALTER TABLE movimientos 
ADD CONSTRAINT fk_movimientos_banco_id 
FOREIGN KEY (banco_id) REFERENCES bancos(id) 
ON DELETE SET NULL ON UPDATE CASCADE;

-- ============================================================================
-- VERIFICAR FOREIGN KEYS CREADAS
-- ============================================================================
SELECT 'Foreign Keys agregadas exitosamente' AS resultado;

-- Mostrar información de foreign keys
SELECT 
    CONSTRAINT_NAME,
    COLUMN_NAME,
    REFERENCED_TABLE_NAME,
    REFERENCED_COLUMN_NAME,
    DELETE_RULE,
    UPDATE_RULE
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
WHERE TABLE_SCHEMA = 'hydrosite_db' 
  AND TABLE_NAME = 'movimientos' 
  AND REFERENCED_TABLE_NAME IS NOT NULL
ORDER BY CONSTRAINT_NAME;
