# Guía de uso de los modales reutilizables

## Archivos necesarios:
1. `modelos.php` - Contiene todos los modales HTML
2. `js/modales.js` - Contiene las funciones JavaScript reutilizables

## Cómo implementar en una nueva sección:

### 1. Incluir en tu archivo PHP:
```php
<?php include('../modelos.php'); ?>
<script src="../js/modales.js"></script>
```

### 2. Funciones disponibles:

#### Mostrar errores:
```javascript
mostrarErrores("Error 1\nError 2\nError 3");
```

#### Confirmar guardado:
```javascript
confirmarGuardar(() => {
    // Código a ejecutar si el usuario confirma
    console.log("Usuario confirmó guardar");
});
```

#### Confirmar eliminación:
```javascript
confirmarEliminar(() => {
    // Código a ejecutar si el usuario confirma eliminar
    console.log("Usuario confirmó eliminar");
});
```

#### Mostrar éxito:
```javascript
mostrarExito("¡Operación exitosa!", "Los datos se guardaron correctamente");
```

#### Mostrar información:
```javascript
mostrarInfo("Información importante", "Este es un mensaje informativo");
```

#### Confirmación personalizada:
```javascript
confirmarAccion(
    "¿Continuar con la operación?", 
    "Esta acción realizará cambios importantes", 
    () => {
        console.log("Usuario confirmó");
    },
    "Sí, continuar"
);
```

#### Manejo de respuestas AJAX:
```javascript
fetch('mi_api.php', {
    method: 'POST',
    body: JSON.stringify(datos)
})
.then(res => res.json())
.then(resp => {
    manejarRespuestaAjax(resp, 
        (response) => {
            // Éxito
            mostrarExito("¡Guardado!", "Datos guardados correctamente");
        },
        (response) => {
            // Error personalizado (opcional)
            mostrarErrores(response.error);
        }
    );
})
.catch(manejarErrorFetch);
```

## Ejemplo de implementación completa:

```javascript
// En tu archivo JS específico
document.addEventListener('DOMContentLoaded', function() {
    
    // Ejemplo de eliminar con confirmación
    document.querySelectorAll('.btn-eliminar').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            
            confirmarEliminar(() => {
                fetch(`mi_api.php?action=delete&id=${id}`, {
                    method: 'DELETE'
                })
                .then(res => res.json())
                .then(resp => {
                    manejarRespuestaAjax(resp, () => {
                        this.closest('tr').remove();
                        mostrarExito("¡Eliminado!", "Elemento eliminado correctamente");
                    });
                })
                .catch(manejarErrorFetch);
            });
        });
    });
    
    // Ejemplo de guardar con validación
    document.getElementById('miFormulario').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('mi_api.php?action=save', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(resp => {
            manejarRespuestaAjax(resp, () => {
                mostrarExito("¡Guardado!", "Formulario guardado correctamente");
                this.reset();
            });
        })
        .catch(manejarErrorFetch);
    });
});
```

## Ventajas:
- ✅ Reutilizable en cualquier sección
- ✅ Diseño consistente
- ✅ Fácil mantenimiento
- ✅ Manejo centralizado de errores
- ✅ Mejor experiencia de usuario
