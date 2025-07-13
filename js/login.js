document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const usuario = document.getElementById('username').value;
    const contrasena = document.getElementById('password').value;

    const formData = new FormData();
    formData.append('usuario', usuario);
    formData.append('contrasena', contrasena);

    const response = await fetch('php/validar_login.php', {
        method: 'POST',
        body: formData
    });
    const result = await response.json();
    console.log(result);

    if (result.success) {
        alert(result.mensaje);
        window.location.href = 'Perfil.html'; // Redirige al perfil
    } else {
        alert(result.mensaje);
    }
});