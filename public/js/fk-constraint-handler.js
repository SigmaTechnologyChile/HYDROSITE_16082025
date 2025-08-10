/**
 * FK Constraint Handler
 * Solución para Issue #1451 - Manejo de Foreign Key Constraints
 * 
 * Este módulo proporciona funciones para manejar eliminaciones seguras
 * de registros que tienen dependencias de foreign keys.
 */

class FKConstraintHandler {
    constructor(options = {}) {
        this.baseUrl = options.baseUrl || '/fk-constraint';
        this.csrfToken = options.csrfToken || $('meta[name="csrf-token"]').attr('content');
        this.defaultMethod = options.defaultMethod || 'cascade';
    }

    /**
     * Verificar dependencias de un registro
     */
    async checkDependencies(table, id) {
        try {
            const response = await $.post(`${this.baseUrl}/check-dependencies`, {
                table: table,
                id: id,
                _token: this.csrfToken
            });
            
            return response;
        } catch (error) {
            throw new Error('Error al verificar dependencias: ' + error.responseJSON?.message || error.statusText);
        }
    }

    /**
     * Eliminar registro de forma segura
     */
    async safeDelete(table, id, method = null) {
        method = method || this.defaultMethod;
        
        try {
            const response = await $.post(`${this.baseUrl}/safe-delete`, {
                table: table,
                id: id,
                method: method,
                _token: this.csrfToken
            });
            
            return response;
        } catch (error) {
            throw new Error('Error al eliminar registro: ' + error.responseJSON?.message || error.statusText);
        }
    }

    /**
     * Eliminar con confirmación automática
     */
    async deleteWithConfirmation(table, id, options = {}) {
        const {
            confirmMessage = '¿Está seguro de que desea eliminar este registro?',
            showDependencies = true,
            method = 'cascade'
        } = options;

        try {
            // Verificar dependencias primero
            const dependencyReport = await this.checkDependencies(table, id);
            
            if (!dependencyReport.success) {
                throw new Error(dependencyReport.message);
            }

            let confirmText = confirmMessage;
            
            if (showDependencies && !dependencyReport.data.can_delete_safely) {
                confirmText += '\n\nEste registro tiene dependencias:';
                dependencyReport.data.dependencies.forEach(dep => {
                    confirmText += `\n- ${dep.count} registros en ${dep.table}`;
                });
                confirmText += `\n\nTotal de registros que se eliminarán: ${dependencyReport.data.total_dependent_records + 1}`;
            }

            if (!confirm(confirmText)) {
                return { success: false, cancelled: true };
            }

            // Proceder con eliminación
            const deleteMethod = dependencyReport.data.can_delete_safely ? 'check_only' : method;
            return await this.safeDelete(table, id, deleteMethod);

        } catch (error) {
            throw error;
        }
    }

    /**
     * Generar script SQL para eliminación manual
     */
    async generateScript(table, id, method = 'cascade') {
        try {
            const response = await $.post(`${this.baseUrl}/generate-script`, {
                table: table,
                id: id,
                method: method,
                _token: this.csrfToken
            });
            
            return response;
        } catch (error) {
            throw new Error('Error al generar script: ' + error.responseJSON?.message || error.statusText);
        }
    }

    /**
     * Mostrar modal de confirmación con detalles de dependencias
     */
    showDependencyModal(table, id, callback) {
        const modalHtml = `
            <div class="modal fade" id="fkConstraintModal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-warning">
                            <h4 class="modal-title">
                                <i class="fas fa-exclamation-triangle"></i> 
                                Confirmar Eliminación
                            </h4>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="fkConstraintModalContent">
                                <div class="text-center">
                                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                                    <p>Verificando dependencias...</p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                Cancelar
                            </button>
                            <button type="button" class="btn btn-danger" id="fkConstraintConfirmBtn" disabled>
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Remover modal existente si existe
        $('#fkConstraintModal').remove();
        
        // Agregar modal al DOM
        $('body').append(modalHtml);
        
        // Mostrar modal
        $('#fkConstraintModal').modal('show');

        // Verificar dependencias
        this.checkDependencies(table, id)
            .then(response => {
                if (response.success) {
                    this.renderDependencyModalContent(response.data);
                    $('#fkConstraintConfirmBtn').prop('disabled', false);
                } else {
                    throw new Error(response.message);
                }
            })
            .catch(error => {
                $('#fkConstraintModalContent').html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                        Error al verificar dependencias: ${error.message}
                    </div>
                `);
            });

        // Manejar confirmación
        $('#fkConstraintConfirmBtn').off('click').on('click', () => {
            if (callback) {
                callback();
            }
            $('#fkConstraintModal').modal('hide');
        });

        // Limpiar modal al cerrarse
        $('#fkConstraintModal').on('hidden.bs.modal', function() {
            $(this).remove();
        });
    }

    /**
     * Renderizar contenido del modal con información de dependencias
     */
    renderDependencyModalContent(data) {
        let html = `
            <div class="alert alert-info">
                <strong>Registro a eliminar:</strong> ${data.table} ID ${data.id}
            </div>
        `;

        if (data.can_delete_safely) {
            html += `
                <div class="alert alert-success">
                    <i class="fas fa-check"></i>
                    Este registro puede eliminarse de forma segura (sin dependencias).
                </div>
            `;
        } else {
            html += `
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>¡Atención!</strong> Este registro tiene dependencias.
                    Se eliminarán <strong>${data.total_dependent_records}</strong> registros adicionales.
                </div>
                
                <h5>Registros que se eliminarán:</h5>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Tabla</th>
                                <th>Cantidad</th>
                                <th>Ejemplos</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

            data.dependencies.forEach(dep => {
                html += `
                    <tr>
                        <td>${dep.table}</td>
                        <td><span class="badge badge-warning">${dep.count}</span></td>
                        <td>${dep.examples.join(', ')}</td>
                    </tr>
                `;
            });

            html += `
                        </tbody>
                    </table>
                </div>
            `;
        }

        $('#fkConstraintModalContent').html(html);
    }

    /**
     * Integración con botones de eliminación existentes
     */
    attachToDeleteButtons(selector = '.btn-delete') {
        const self = this;
        
        $(document).off('click.fkconstraint', selector).on('click.fkconstraint', selector, function(e) {
            e.preventDefault();
            
            const $btn = $(this);
            const table = $btn.data('table');
            const id = $btn.data('id');
            
            if (!table || !id) {
                console.error('FKConstraintHandler: Los botones deben tener data-table y data-id');
                return;
            }

            self.showDependencyModal(table, id, async () => {
                try {
                    $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Eliminando...');
                    
                    const result = await self.deleteWithConfirmation(table, id, {
                        confirmMessage: '', // Ya confirmado en el modal
                        showDependencies: false
                    });
                    
                    if (result.success) {
                        // Recargar página o actualizar tabla
                        if (typeof window.dataTable !== 'undefined') {
                            window.dataTable.ajax.reload();
                        } else {
                            location.reload();
                        }
                        
                        // Mostrar mensaje de éxito
                        toastr.success('Registro eliminado exitosamente');
                    }
                } catch (error) {
                    toastr.error(error.message);
                } finally {
                    $btn.prop('disabled', false).html('<i class="fas fa-trash"></i>');
                }
            });
        });
    }
}

// Instancia global
window.FKConstraint = new FKConstraintHandler();

// Auto-inicialización cuando el DOM esté listo
$(document).ready(function() {
    // Adjuntar automáticamente a botones con clase .fk-safe-delete
    window.FKConstraint.attachToDeleteButtons('.fk-safe-delete');
    
    // También adjuntar a botones con data-fk-safe="true"
    window.FKConstraint.attachToDeleteButtons('[data-fk-safe="true"]');
});

// Exportar para uso en módulos
if (typeof module !== 'undefined' && module.exports) {
    module.exports = FKConstraintHandler;
}
