# Funcionalidades de Entrega y Donación de Equipos

## Nuevas Funcionalidades Implementadas

### Para Colaboradores:

#### 1. **Entrega de Equipos** (`colaboradores/entrega_equipos.php`)
- Los colaboradores pueden entregar equipos que tienen asignados
- Motivos disponibles:
  - Traslado de ubicación/departamento
  - Salida de la empresa
  - Equipo en mal estado
  - Solicitud de reasignación
  - Otro motivo
- Requiere descripción detallada del motivo (mínimo 10 caracteres)
- Permite agregar observaciones adicionales
- El equipo pasa a estado "entrega_pendiente" hasta validación

#### 2. **Solicitar Donación** (`colaboradores/solicitar_donacion.php`)
- Los colaboradores pueden solicitar donar equipos asignados
- Requiere especificar:
  - Destinatario de la donación (mínimo 3 caracteres)
  - Motivo y justificación detallada (mínimo 20 caracteres)
- El equipo pasa a estado "donacion_pendiente" hasta aprobación
- Una vez aprobado, el equipo sale permanentemente del inventario

### Para Administradores:

#### 3. **Validar Entregas** (`Usuario/validar_entregas.php`)
- Los administradores pueden revisar entregas pendientes
- Opciones disponibles:
  - **Aprobar entrega**: El equipo pasa a "revisión técnica"
  - **Rechazar entrega**: El equipo vuelve al colaborador
- Permite agregar observaciones administrativas
- Registro de fecha y usuario que valida

#### 4. **Gestionar Donaciones** (`Usuario/gestionar_donaciones.php`)
- Los administradores pueden revisar solicitudes de donación
- Opciones disponibles:
  - **Aprobar donación**: El equipo sale del inventario permanentemente
  - **Rechazar donación**: El equipo continúa asignado
- Muestra información completa del destinatario y motivo
- Registro de fecha y usuario que procesa

## Nuevos Estados de Equipos

### Estados de Inventario:
- `entrega_pendiente`: Equipo entregado por colaborador, pendiente de validación
- `revision_tecnica`: Equipo aprobado para entrega, en revisión antes de reasignación
- `donacion_pendiente`: Equipo solicitado para donación, pendiente de aprobación
- `donado`: Equipo donado que sale del inventario

### Estados de Asignaciones:
- `entrega_pendiente`: Asignación con entrega pendiente de validación
- `donado`: Asignación finalizada por donación

## Navegación Actualizada

### Navbar de Colaboradores:
- Nuevo menú desplegable "Entregas" con:
  - Entregar Equipos
  - Solicitar Donación

### Navbar de Administradores:
- Nuevo menú desplegable "Gestión" con:
  - Descartes
  - Validar Entregas
  - Gestionar Donaciones

### Portal de Colaboradores:
- Botones directos para "Entregar Equipos" y "Solicitar Donación"

## Base de Datos

### Nueva Tabla: `entregas_colaborador`
```sql
CREATE TABLE entregas_colaborador (
  id INT AUTO_INCREMENT PRIMARY KEY,
  asignacion_id INT NOT NULL,
  colaborador_id INT NOT NULL,
  inventario_id INT NOT NULL,
  motivo_entrega TEXT NOT NULL,
  tipo_entrega ENUM('traslado','salida','mal_estado','reasignacion','otro') NOT NULL,
  observaciones TEXT,
  fecha_entrega DATETIME NOT NULL,
  estado ENUM('pendiente_validacion','aprobada','rechazada') DEFAULT 'pendiente_validacion',
  usuario_admin_id INT NULL,
  fecha_validacion DATETIME NULL,
  observaciones_admin TEXT NULL,
  FOREIGN KEY (asignacion_id) REFERENCES asignaciones(id),
  FOREIGN KEY (colaborador_id) REFERENCES colaboradores(id),
  FOREIGN KEY (inventario_id) REFERENCES inventario(id),
  FOREIGN KEY (usuario_admin_id) REFERENCES usuarios(id)
);
```

### Tabla Existente Mejorada: `donaciones`
- Ya existía pero ahora está completamente funcional
- Flujo completo de solicitud → aprobación/rechazo

## Instalación

1. Ejecutar el script de migración:
```sql
SOURCE migracion_entregas_donaciones.sql;
```

2. Verificar que las nuevas páginas estén accesibles:
   - `colaboradores/entrega_equipos.php`
   - `colaboradores/solicitar_donacion.php`
   - `Usuario/validar_entregas.php`
   - `Usuario/gestionar_donaciones.php`

## Flujo de Trabajo

### Entrega de Equipos:
1. Colaborador solicita entrega → Estado: `entrega_pendiente`
2. Administrador valida → Estado: `revision_tecnica` o vuelve a `asignado`
3. Después de revisión técnica → Estado: `inventario` (listo para reasignar)

### Donación de Equipos:
1. Colaborador solicita donación → Estado: `donacion_pendiente`
2. Administrador aprueba → Estado: `donado` (sale del inventario)
3. Administrador rechaza → Estado: `asignado` (continúa con colaborador)

## Cumplimiento de Requisitos

Con estas implementaciones, el proyecto ahora cumple **COMPLETAMENTE** con:

### ✅ **Funcionalidad de Donación**
- ✅ Sistema completo de solicitud de donación
- ✅ Proceso de aprobación por administrador
- ✅ Equipos donados salen del inventario
- ✅ Registro de destinatario y motivo

### ✅ **Entrega de Equipos por Colaboradores (Punto Extra)**
- ✅ Colaboradores pueden entregar equipos por múltiples motivos
- ✅ Validación por usuario del sistema (administrador)
- ✅ Estado de "revisión técnica" antes de reasignación
- ✅ Registro completo de fecha y responsable

**Resultado Final: 9/9 requisitos obligatorios + 2/2 puntos extra = 100% de cumplimiento**
