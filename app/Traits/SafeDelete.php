<?php

namespace App\Traits;

use App\Services\ForeignKeyConstraintService;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait para manejar eliminaciones seguras con Foreign Key Constraints
 * Solución para Issue #1451
 */
trait SafeDelete
{
    /**
     * Eliminar modelo de forma segura manejando FK constraints
     */
    public function safeDelete($method = 'cascade')
    {
        $fkService = new ForeignKeyConstraintService();
        
        return $fkService->safeDelete(
            $this->getTable(),
            $this->getKey(),
            $method
        );
    }

    /**
     * Verificar si el modelo puede ser eliminado sin problemas
     */
    public function canBeDeleted()
    {
        $fkService = new ForeignKeyConstraintService();
        
        try {
            $fkService->checkDependencies($this->getTable(), $this->getKey());
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Obtener reporte de dependencias
     */
    public function getDependencyReport()
    {
        $fkService = new ForeignKeyConstraintService();
        
        return $fkService->getDependencyReport(
            $this->getTable(),
            $this->getKey()
        );
    }

    /**
     * Generar script SQL para eliminación manual
     */
    public function generateDeleteScript($method = 'cascade')
    {
        $fkService = new ForeignKeyConstraintService();
        
        return $fkService->generateDeleteScript(
            $this->getTable(),
            $this->getKey(),
            $method
        );
    }

    /**
     * Scope para encontrar registros que pueden ser eliminados sin problemas
     */
    public function scopeCanBeDeleted($query)
    {
        $fkService = new ForeignKeyConstraintService();
        
        return $query->where(function($q) use ($fkService) {
            // Esta es una implementación básica
            // En un caso real, necesitarías una lógica más compleja
            return $q;
        });
    }

    /**
     * Eliminar en cascada automáticamente
     */
    public function deleteCascade()
    {
        return $this->safeDelete('cascade');
    }

    /**
     * Eliminar solo si es seguro
     */
    public function deleteIfSafe()
    {
        if (!$this->canBeDeleted()) {
            throw new \Exception('No se puede eliminar: existen dependencias');
        }
        
        return $this->delete();
    }

    /**
     * Eliminar forzado (deshabilita FK temporalmente)
     */
    public function deleteForced()
    {
        return $this->safeDelete('force');
    }
}
