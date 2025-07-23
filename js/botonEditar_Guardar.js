// Edición en línea y eliminación para inventario
// Requiere: modales.js y modelos.php incluidos
document.addEventListener('DOMContentLoaded', function () {
    console.log('Script botonEditar_Guardar.js cargado');
    const tabla = document.getElementById('tabla-inventario');
    
    if (!tabla) {
        console.error('No se encontró la tabla con ID tabla-inventario');
        return;
    }
    
    console.log('Tabla encontrada, agregando eventos...');

    tabla.addEventListener('click', function (e) {
        console.log('Click detectado en:', e.target);
        
        // Editar
        if (e.target.classList.contains('btn-editar')) {
            console.log('Botón editar clickeado');
            const tr = e.target.closest('tr');
            
            tr.querySelectorAll('.editable').forEach(function (td) {
                const valor = td.textContent.trim();
                const campo = td.getAttribute('data-campo');
                
                // Si es el campo de categoría, crear un select
                if (campo === 'categoria_id') {
                    // Primero obtenemos las categorías
                    fetch('../api_categorias.php?action=getCategorias')
                        .then(res => res.json())
                        .then(categorias => {
                            let selectHtml = '<select class="form-control" data-campo="categoria_id">';
                            categorias.forEach(cat => {
                                const selected = cat.nombre === valor ? 'selected' : '';
                                selectHtml += `<option value="${cat.id}" ${selected}>${cat.nombre}</option>`;
                            });
                            selectHtml += '</select>';
                            td.innerHTML = selectHtml;
                        })
                        .catch(() => {
                            // Si falla, usar input normal
                            td.innerHTML = `<input type="text" class="form-control" value="${valor}">`;
                        });
                } else if (campo === 'estado') {
                    // Para el estado, crear select con opciones predefinidas
                    const estados = [
                        {value: 'activo', text: 'Activo'},
                        {value: 'baja', text: 'Baja'},
                        {value: 'reparacion', text: 'En reparación'},
                        {value: 'descarte', text: 'En descarte'},
                        {value: 'donado', text: 'Donado'},
                        {value: 'inventario', text: 'Inventario'},
                        {value: 'solicitado', text: 'Solicitado'},
                        {value: 'asignado', text: 'Asignado'}
                    ];
                    
                    let selectHtml = '<select class="form-control">';
                    estados.forEach(estado => {
                        const selected = estado.text === valor ? 'selected' : '';
                        selectHtml += `<option value="${estado.value}" ${selected}>${estado.text}</option>`;
                    });
                    selectHtml += '</select>';
                    td.innerHTML = selectHtml;
                } else if (campo === 'fecha_ingreso') {
                    // Para fechas, usar input type date
                    td.innerHTML = `<input type="date" class="form-control" value="${valor}">`;
                } else if (campo === 'costo' || campo === 'tiempo_depreciacion') {
                    // Para números
                    td.innerHTML = `<input type="number" class="form-control" value="${valor}" ${campo === 'costo' ? 'step="0.01"' : ''}>`;
                } else {
                    // Para campos normales
                    td.innerHTML = `<input type="text" class="form-control" value="${valor}">`;
                }
            });
            
            tr.querySelector('.btn-editar').classList.add('d-none');
            tr.querySelector('.btn-guardar').classList.remove('d-none');
        }

        // Guardar
        if (e.target.classList.contains('btn-guardar')) {
            console.log('Botón guardar clickeado');
            const tr = e.target.closest('tr');
            const id = tr.getAttribute('data-id');
            let datos = { id };
            tr.querySelectorAll('.editable').forEach(function (td) {
                const campo = td.getAttribute('data-campo');
                const input = td.querySelector('input');
                const select = td.querySelector('select');
                
                if (select) {
                    datos[campo] = select.value;
                } else if (input) {
                    datos[campo] = input.value.trim();
                }
            });

            console.log('Datos a enviar:', datos);
            
            confirmarGuardar(() => {
                fetch('conexinventario.php?action=update', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(datos)
                })
                .then(res => res.json())
                .then(resp => {
                    console.log('Respuesta del servidor:', resp);
                    manejarRespuestaAjax(resp, () => {
                        tr.querySelectorAll('.editable').forEach(function (td) {
                            const campo = td.getAttribute('data-campo');
                            td.textContent = datos[campo];
                        });
                        tr.querySelector('.btn-editar').classList.remove('d-none');
                        tr.querySelector('.btn-guardar').classList.add('d-none');
                        mostrarExito('¡Cambios guardados!', 'Los datos se actualizaron correctamente.');
                    });
                })
                .catch(manejarErrorFetch);
            });
        }

        // Eliminar
        if (e.target.classList.contains('btn-eliminar')) {
            console.log('Botón eliminar clickeado');
            const tr = e.target.closest('tr');
            const id = tr.getAttribute('data-id');
            
            confirmarEliminar(() => {
                fetch('conexinventario.php?action=delete', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                })
                .then(res => res.json())
                .then(resp => {
                    console.log('Respuesta del servidor:', resp);
                    manejarRespuestaAjax(resp, () => {
                        tr.remove();
                        mostrarExito('¡Elemento eliminado!', 'El elemento se eliminó correctamente del inventario.');
                    });
                })
                .catch(manejarErrorFetch);
            });
        }
    });

    // Alta de nuevo equipo/software
    const formNuevo = document.getElementById('formNuevo');
    if (formNuevo) {
        formNuevo.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Formulario de alta enviado');
            
            // Verificar que los campos tengan valores antes de enviar
            const nombreEquipo = this.querySelector('input[name="nombre_equipo"]').value.trim();
            const categoriaId = this.querySelector('select[name="categoria_id"]').value;
            
            console.log('Nombre equipo antes de enviar:', nombreEquipo);
            console.log('Categoria ID antes de enviar:', categoriaId);
            
            // Validación básica en el frontend
            if (nombreEquipo.length < 2) {
                mostrarErrores('El nombre del equipo debe tener al menos 2 caracteres');
                return;
            }
            
            if (!categoriaId || categoriaId === '' || categoriaId === '0') {
                mostrarErrores('Debe seleccionar una categoría');
                return;
            }
            
            const formData = new FormData(this);
            
            // Debug: mostrar todos los datos del formulario
            console.log('=== DEBUG FORM DATA ===');
            console.log('Elementos del formulario:', this.elements.length);
            console.log('Datos del FormData:');
            for (let [key, value] of formData.entries()) {
                console.log(`${key}: ${value}`);
                if (value instanceof File) {
                    console.log(`  -> Archivo: ${value.name}, tamaño: ${value.size}, tipo: ${value.type}`);
                }
            }
            
            // Verificar si hay archivos adjuntos
            const fileInput = this.querySelector('input[type="file"]');
            if (fileInput && fileInput.files.length > 0) {
                console.log('Archivo seleccionado:', fileInput.files[0]);
            } else {
                console.log('No hay archivo seleccionado');
            }
            
            console.log('URL de envío:', 'conexinventario.php?action=alta');
            
            fetch('conexinventario.php?action=alta', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(resp => {
                console.log('Respuesta del servidor (alta):', resp);
                manejarRespuestaAjax(resp, () => {
                    // Cerrar modal
                    const modalNuevo = bootstrap.Modal.getInstance(document.getElementById('modalNuevo'));
                    if (modalNuevo) {
                        modalNuevo.hide();
                    }
                    
                    // Mostrar éxito y recargar
                    mostrarExito('¡Elemento agregado!', 'El nuevo elemento se agregó correctamente al inventario.');
                    
                    // Recargar después de un pequeño delay para que el usuario vea el mensaje
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                });
            })
            .catch(manejarErrorFetch);
        });
    } else {
        console.error('No se encontró el formulario con ID formNuevo');
    }
});