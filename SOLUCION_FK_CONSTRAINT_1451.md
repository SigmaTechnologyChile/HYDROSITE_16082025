# Solución Foreign Key Constraint - Issue #1451

## Problema Identificado

El issue #1451 indica que no se puede eliminar una línea padre debido a restricciones de foreign key. Esto es un problema común en bases de datos relacionales cuando existen registros dependientes.

## Análisis del Sistema

### Estructura de Foreign Keys Detectada:
```
auditoria_cuentas -> cuenta_id -> cuentas.id
conciliaciones -> movimiento_id -> movimientos.id
modulos_vistas -> modulo_id -> modulos.id
modulos_vistas -> vista_id -> vistas.id
movimientos -> categoria_id -> categorias.id
movimientos -> cuenta_destino_id -> cuentas.id
movimientos -> cuenta_origen_id -> cuentas.id
org_planes -> id_plan -> planes.id
planes_vistas -> plan_id -> planes.id
planes_vistas -> vista_id -> vistas.id
tier_config -> org_id -> orgs.id
```

### Dependencias Principales:
1. **orgs** → cuentas, tier_config, users, configuracion_cuentas_iniciales
2. **cuentas** → auditoria_cuentas, movimientos (origen/destino)
3. **categorias** → movimientos
4. **movimientos** → conciliaciones

## Solución Implementada

### 1. Servicio Central: `ForeignKeyConstraintService`

**Ubicación:** `app/Services/ForeignKeyConstraintService.php`

**Métodos principales:**
- `safeDelete($table, $id, $method)` - Eliminación segura
- `getDependencyReport($table, $id)` - Reporte de dependencias
- `generateDeleteScript($table, $id, $method)` - Script SQL manual

**Métodos de eliminación:**
- **cascade**: Elimina registros hijos primero, luego el padre
- **check_only**: Solo verifica dependencias, no elimina si las hay
- **force**: Deshabilita FK temporalmente para eliminar

### 2. Trait para Modelos: `SafeDelete`

**Ubicación:** `app/Traits/SafeDelete.php`

**Uso en modelos:**
```php
use App\Traits\SafeDelete;

class Cuenta extends Model {
    use SafeDelete;
    
    // Ahora puedes usar:
    // $cuenta->safeDelete();
    // $cuenta->canBeDeleted();
    // $cuenta->getDependencyReport();
}
```

### 3. Controlador API: `FKConstraintController`

**Ubicación:** `app/Http/Controllers/FKConstraintController.php`

**Rutas disponibles:**
```
GET  /fk-constraint/                    - Vista de gestión
POST /fk-constraint/check-dependencies - Verificar dependencias
POST /fk-constraint/safe-delete        - Eliminación segura
POST /fk-constraint/generate-script    - Generar script SQL
POST /fk-constraint/handle-delete      - Manejo automático
```

### 4. Interfaz Web

**Ubicación:** `resources/views/admin/fk-constraints.blade.php`

Acceso: `/fk-constraint/`

Permite:
- Verificar dependencias de cualquier registro
- Eliminar registros de forma segura
- Generar scripts SQL para ejecución manual
- Ver reportes de impacto antes de eliminar

### 5. JavaScript Handler

**Ubicación:** `public/js/fk-constraint-handler.js`

**Uso automático:**
```html
<!-- Para botones con eliminación segura automática -->
<button class="btn btn-danger fk-safe-delete" data-table="cuentas" data-id="123">
    Eliminar
</button>

<!-- O con atributo data -->
<button class="btn btn-danger" data-fk-safe="true" data-table="orgs" data-id="456">
    Eliminar Organización
</button>
```

**Uso programático:**
```javascript
// Verificar dependencias
const report = await FKConstraint.checkDependencies('cuentas', 123);

// Eliminar con confirmación
const result = await FKConstraint.deleteWithConfirmation('cuentas', 123);

// Mostrar modal de dependencias
FKConstraint.showDependencyModal('orgs', 456, () => {
    // Callback de confirmación
});
```

## Ejemplos de Uso

### 1. Eliminación desde Controlador

```php
use App\Services\ForeignKeyConstraintService;

class CuentaController extends Controller {
    public function destroy($id) {
        $fkService = new ForeignKeyConstraintService();
        
        $result = $fkService->safeDelete('cuentas', $id, 'cascade');
        
        if ($result['success']) {
            return redirect()->back()->with('success', 'Cuenta eliminada exitosamente');
        } else {
            return redirect()->back()->with('error', $result['message']);
        }
    }
}
```

### 2. Eliminación desde Modelo

```php
// En el modelo Cuenta
use App\Traits\SafeDelete;

class Cuenta extends Model {
    use SafeDelete;
}

// En el controlador
public function destroy(Cuenta $cuenta) {
    $result = $cuenta->safeDelete('cascade');
    
    return response()->json($result);
}
```

### 3. Verificación antes de mostrar botón de eliminar

```php
// En la vista Blade
@if($cuenta->canBeDeleted())
    <button class="btn btn-danger" onclick="deleteCuenta({{ $cuenta->id }})">
        Eliminar
    </button>
@else
    <button class="btn btn-warning fk-safe-delete" data-table="cuentas" data-id="{{ $cuenta->id }}">
        Eliminar (con dependencias)
    </button>
@endif
```

### 4. Script SQL Manual

Para casos donde prefieras ejecutar SQL manualmente:

```sql
-- Ejemplo generado automáticamente para eliminar org ID 1
START TRANSACTION;

-- Eliminar 4 registros de cuentas
DELETE FROM cuentas WHERE org_id = 1;

-- Eliminar 4 registros de configuracion_cuentas_iniciales
DELETE FROM configuracion_cuentas_iniciales WHERE org_id = 1;

-- Eliminar registro padre
DELETE FROM orgs WHERE id = 1;

COMMIT;
```

## Soluciones Rápidas

### Para emergencias (deshabilitar FK temporalmente):
```sql
SET FOREIGN_KEY_CHECKS = 0;
DELETE FROM tabla_problema WHERE id = valor;
SET FOREIGN_KEY_CHECKS = 1;
```

### Para verificar dependencias manualmente:
```php
php artisan tinker
>>> $fkService = new App\Services\ForeignKeyConstraintService();
>>> $report = $fkService->getDependencyReport('orgs', 1);
>>> dd($report);
```

## Archivos Creados/Modificados

1. **Servicios:**
   - `app/Services/ForeignKeyConstraintService.php`
   - `app/Traits/SafeDelete.php`

2. **Controladores:**
   - `app/Http/Controllers/FKConstraintController.php`

3. **Rutas:**
   - Agregadas en `routes/web.php`

4. **Vistas:**
   - `resources/views/admin/fk-constraints.blade.php`

5. **JavaScript:**
   - `public/js/fk-constraint-handler.js`

6. **Scripts de diagnóstico:**
   - `diagnostico_fk_completo.php`
   - `resolver_fk_1451.php`
   - `solucion_fk_1451.php`

## Recomendaciones

1. **Siempre hacer backup** antes de eliminaciones masivas
2. **Usar método "check_only"** en producción para verificar impacto
3. **Revisar reportes de dependencias** antes de confirmar eliminaciones
4. **Usar la interfaz web** para operaciones complejas
5. **Implementar el trait SafeDelete** en modelos críticos

## Monitoreo y Logs

Todas las operaciones se registran en los logs de Laravel:
```bash
tail -f storage/logs/laravel.log | grep "FK Constraint"
```

La solución está lista para uso inmediato y resuelve completamente el issue #1451.
