document.addEventListener('DOMContentLoaded', function() {
    // Al hacer clic en "Solicitar"
    document.querySelectorAll('.btn-solicitar').forEach(btn => {
        btn.addEventListener('click', function() {
            const tr = btn.closest('tr');
            const nombreEquipo = tr.getAttribute('data-nombre');
            const inventarioId = tr.getAttribute('data-id');
            document.getElementById('nombreEquipoSolicitado').textContent = nombreEquipo;
            document.getElementById('inputInventarioId').value = inventarioId;
            const modal = new bootstrap.Modal(document.getElementById('modalSolicitar'));
            modal.show();
        });
    });

    // Al confirmar solicitud
    document.getElementById('formSolicitar').addEventListener('submit', function(e) {
        e.preventDefault();
        const inventarioId = document.getElementById('inputInventarioId').value;
        fetch('conexinvetcolab.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({inventario_id: inventarioId})
        })
        .then(r => r.json())
        .then(resp => {
            if (resp.success) {
                alert('¡Solicitud enviada correctamente!');
                location.reload();
            } else {
                alert('Error: ' + resp.error);
            }
        });
    });
});