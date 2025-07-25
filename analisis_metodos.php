<?php
/**
 * Script para analizar métodos no utilizados en conexion.php
 */

// Lista de métodos que están comentados y definitivamente no se usan
$metodos_comentados = [
    'procesarEntregaEquipo', 
    'obtenerEntregasPendientes',
    'validarEntregaEquipo'
];

// Lista de métodos relacionados con colaboradores que pueden no usarse si Colaboradores.php fue eliminado
$metodos_colaboradores_dudosos = [
    'existeIdentificacionColaborador',
    'existeUsuarioColaborador', 
    'existeCorreoColaborador',
    'insertarColaborador'
];

// Verificar si el archivo Colaboradores.php existe
$archivo_colaboradores = 'colaboradores/Colaboradores.php';
$colaboradores_existe = file_exists($archivo_colaboradores);

echo "=== ANÁLISIS DE MÉTODOS NO UTILIZADOS EN CONEXION.PHP ===\n\n";

echo "1. MÉTODOS COMENTADOS (definitivamente no se usan):\n";
foreach ($metodos_comentados as $metodo) {
    echo "   - $metodo() - ELIMINAR\n";
}

echo "\n2. ESTADO DEL ARCHIVO COLABORADORES:\n";
echo "   - colaboradores/Colaboradores.php existe: " . ($colaboradores_existe ? "SÍ" : "NO") . "\n";

if (!$colaboradores_existe) {
    echo "\n3. MÉTODOS RELACIONADOS CON COLABORADORES (posiblemente no se usan):\n";
    foreach ($metodos_colaboradores_dudosos as $metodo) {
        echo "   - $metodo() - REVISAR/ELIMINAR\n";
    }
}

echo "\n4. MÉTODOS QUE SÍ SE ESTÁN USANDO (mantener):\n";
$metodos_en_uso = [
    'obtenerDepartamentos',
    'registrarAccesoColaborador', 
    'correoDuplicadoColaborador',
    'actualizarPerfilColaborador',
    'verificarPasswordColaborador',
    'cambiarPasswordColaborador',
    'obtenerHistorialAccesosColaborador',
    'obtenerEstadisticasPorCategoria',
    'obtenerEquiposDisponiblesPorCategoria', 
    'obtenerEquiposAsignadosPorCategoria',
    'obtenerEquiposPorCategoria',
    'obtenerReporteFiltrado',
    'marcarDescarte',
    'restaurarDescarte',
    'obtenerEquiposDescarte',
    'obtenerDetalleDescarte',
    'esAdministrador',
    'obtenerInventarioDisponible'
];

foreach ($metodos_en_uso as $metodo) {
    echo "   - $metodo() - MANTENER\n";
}

echo "\n=== RECOMENDACIONES ===\n";
echo "1. Eliminar métodos comentados relacionados con entregas_colaborador\n";
echo "2. Si Colaboradores.php fue eliminado, revisar métodos de validación de colaboradores\n";
echo "3. Mantener todos los demás métodos que están siendo utilizados\n";
?>
