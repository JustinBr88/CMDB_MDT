document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const usuario = document.getElementById('username').value;
    const contrasena = document.getElementById('password').value;

    const formData = new FormData();
    formData.append('usuario', usuario);
    formData.append('contrasena', contrasena);

    try {
        // Volver al archivo original
        const url = '/TheReturnofMDT/validar_login.php';
        console.log('Enviando a archivo validar_login:', url);

        const response = await fetch(url, {
            method: 'POST',
            body: formData
        });
        
        console.log('Response status:', response.status);
        console.log('Response ok:', response.ok);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const text = await response.text();
        console.log('Respuesta cruda:', text);
        const result = JSON.parse(text);

        if (result.success) {
            alert(result.mensaje);
            window.location.href = 'Home.php'; // Redirigir a Home.php en la misma carpeta Usuario/
        } else {
            alert(result.mensaje);
        }
    } catch (error) {
        console.error('Error en login:', error);
        alert('Error de conexión o respuesta inválida del servidor.');
    }
});