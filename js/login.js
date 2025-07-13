document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const usuario = document.getElementById('username').value;
    const contrasena = document.getElementById('password').value;

    const formData = new FormData();
    formData.append('usuario', usuario);
    formData.append('contrasena', contrasena);

    try {
        const response = await fetch('validar_login.php', {
            method: 'POST',
            body: formData
        });
        const text = await response.text();
        console.log('Respuesta cruda:', text);
        const result = JSON.parse(text);

        if (result.success) {
            alert(result.mensaje);
            window.location.href = 'home.php';
        } else {
            alert(result.mensaje);
        }
    } catch (error) {
        console.error('Error en login:', error);
        alert('Error de conexión o respuesta inválida del servidor.');
    }
});