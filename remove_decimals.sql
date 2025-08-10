-- SQL para cambiar campos de decimal a entero (sin decimales)
-- Ejecutar en phpMyAdmin o cliente MySQL

USE hydrosite_db;

-- ✅ TABLA configuracion_cuentas_iniciales YA ESTÁ CORRECTA
-- El campo saldo_inicial ya es int(11) - SIN DECIMALES

-- ❌ TABLA cuentas NECESITA CORRECCIÓN:
-- El campo saldo_actual es decimal(15,2) - TIENE DECIMALES

-- CAMBIAR saldo_actual de decimal(15,2) a int en tabla cuentas
ALTER TABLE cuentas 
MODIFY COLUMN saldo_actual INT NOT NULL DEFAULT 0;

-- OPCIONAL: Actualizar valores existentes para remover decimales
UPDATE cuentas 
SET saldo_actual = FLOOR(saldo_actual) 
WHERE saldo_actual IS NOT NULL;

-- VERIFICAR CAMBIOS
DESCRIBE cuentas;

-- VER ALGUNOS REGISTROS PARA CONFIRMAR
SELECT id, nombre, tipo, saldo_actual, banco_id 
FROM cuentas 
LIMIT 10;
