@extends('layouts.app')

@section('title', 'Gestión de Foreign Key Constraints')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-database text-danger"></i>
                        Gestión de Foreign Key Constraints
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-warning">Issue #1451</span>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="alert alert-info">
                        <h5><i class="icon fas fa-info"></i> Información</h5>
                        Esta herramienta te ayuda a eliminar registros que tienen dependencias de foreign keys de forma segura.
                    </div>

                    <!-- Formulario de verificación -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Verificar Dependencias</h3>
                                </div>
                                <div class="card-body">
                                    <form id="checkDependenciesForm">
                                        <div class="form-group">
                                            <label for="table">Tabla</label>
                                            <select class="form-control" id="table" name="table" required>
                                                <option value="">Seleccionar tabla...</option>
                                                <option value="orgs">Organizaciones (orgs)</option>
                                                <option value="cuentas">Cuentas</option>
                                                <option value="categorias">Categorías</option>
                                                <option value="movimientos">Movimientos</option>
                                                <option value="users">Usuarios</option>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="record_id">ID del Registro</label>
                                            <input type="number" class="form-control" id="record_id" name="id" required min="1">
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i> Verificar Dependencias
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card card-secondary">
                                <div class="card-header">
                                    <h3 class="card-title">Eliminación Segura</h3>
                                </div>
                                <div class="card-body">
                                    <form id="safeDeleteForm">
                                        <div class="form-group">
                                            <label for="delete_table">Tabla</label>
                                            <select class="form-control" id="delete_table" name="table" required>
                                                <option value="">Seleccionar tabla...</option>
                                                <option value="orgs">Organizaciones (orgs)</option>
                                                <option value="cuentas">Cuentas</option>
                                                <option value="categorias">Categorías</option>
                                                <option value="movimientos">Movimientos</option>
                                                <option value="users">Usuarios</option>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="delete_id">ID del Registro</label>
                                            <input type="number" class="form-control" id="delete_id" name="id" required min="1">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="delete_method">Método</label>
                                            <select class="form-control" id="delete_method" name="method">
                                                <option value="cascade">Cascade (eliminar dependencias)</option>
                                                <option value="check_only">Solo verificar (no eliminar si hay dependencias)</option>
                                                <option value="force">Forzado (deshabilitar FK temporalmente)</option>
                                            </select>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash"></i> Eliminar de Forma Segura
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Resultados -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div id="results" style="display: none;">
                                <!-- Los resultados se mostrarán aquí dinámicamente -->
                            </div>
                        </div>
                    </div>

                    <!-- Instrucciones -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title">Instrucciones de Uso</h3>
                                </div>
                                <div class="card-body">
                                    <h5>Métodos de eliminación:</h5>
                                    <ul>
                                        <li><strong>Cascade:</strong> Elimina primero los registros dependientes, luego el registro principal.</li>
                                        <li><strong>Check Only:</strong> Solo verifica si existen dependencias. No elimina si las hay.</li>
                                        <li><strong>Force:</strong> Deshabilita temporalmente las foreign keys para eliminar.</li>
                                    </ul>
                                    
                                    <h5>Recomendaciones:</h5>
                                    <ul>
                                        <li>Siempre verificar dependencias antes de eliminar.</li>
                                        <li>Hacer backup de la base de datos antes de eliminaciones masivas.</li>
                                        <li>Usar "Check Only" en producción para verificar impacto.</li>
                                        <li>El método "Force" debe usarse con extremo cuidado.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h4 class="modal-title">
                    <i class="fas fa-exclamation-triangle"></i> Confirmar Eliminación
                </h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="confirmDeleteContent">
                    <!-- Contenido dinámico -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="fas fa-trash"></i> Eliminar
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Verificar dependencias
    $('#checkDependenciesForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        
        $.post('{{ route("fk-constraint.check-dependencies") }}', formData)
            .done(function(response) {
                if (response.success) {
                    showDependencyReport(response.data);
                } else {
                    showError('Error al verificar dependencias: ' + response.message);
                }
            })
            .fail(function(xhr) {
                showError('Error de conexión: ' + xhr.statusText);
            });
    });

    // Eliminación segura
    $('#safeDeleteForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        
        $.post('{{ route("fk-constraint.safe-delete") }}', formData)
            .done(function(response) {
                if (response.success) {
                    showSuccess('Registro eliminado exitosamente. Registros afectados: ' + response.affected_records);
                } else {
                    if (response.dependencies) {
                        showDependencyError(response.message, response.dependencies);
                    } else {
                        showError('Error al eliminar: ' + response.message);
                    }
                }
            })
            .fail(function(xhr) {
                showError('Error de conexión: ' + xhr.statusText);
            });
    });

    function showDependencyReport(data) {
        let html = '<div class="card card-info"><div class="card-header"><h3 class="card-title">Reporte de Dependencias</h3></div><div class="card-body">';
        
        html += '<p><strong>Tabla:</strong> ' + data.table + '</p>';
        html += '<p><strong>ID:</strong> ' + data.id + '</p>';
        
        if (data.can_delete_safely) {
            html += '<div class="alert alert-success"><i class="fas fa-check"></i> Puede eliminarse de forma segura (sin dependencias)</div>';
        } else {
            html += '<div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> Tiene dependencias - Se eliminarán ' + data.total_dependent_records + ' registros adicionales</div>';
            
            html += '<h5>Dependencias encontradas:</h5><ul>';
            data.dependencies.forEach(function(dep) {
                html += '<li><strong>' + dep.table + '</strong> (' + dep.column + '): ' + dep.count + ' registros';
                if (dep.examples.length > 0) {
                    html += ' - Ejemplos: ' + dep.examples.join(', ');
                }
                html += '</li>';
            });
            html += '</ul>';
        }
        
        html += '</div></div>';
        
        $('#results').html(html).show();
    }

    function showDependencyError(message, dependencies) {
        let html = '<div class="alert alert-danger"><h5><i class="fas fa-exclamation-triangle"></i> No se puede eliminar</h5>';
        html += '<p>' + message + '</p>';
        
        if (dependencies && dependencies.length > 0) {
            html += '<p><strong>Dependencias:</strong></p><ul>';
            dependencies.forEach(function(dep) {
                html += '<li>' + dep.count + ' registros en ' + dep.table + '</li>';
            });
            html += '</ul>';
        }
        
        html += '</div>';
        
        $('#results').html(html).show();
    }

    function showSuccess(message) {
        const html = '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="fas fa-check"></i> ' + message + '</div>';
        $('#results').html(html).show();
    }

    function showError(message) {
        const html = '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="fas fa-exclamation-triangle"></i> ' + message + '</div>';
        $('#results').html(html).show();
    }
});
</script>
@endsection
