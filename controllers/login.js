function loginUser() {
    // Obtiene los valores del formulario de login
    const usuario = document.getElementById('Usuario').value;
    const password = document.getElementById('contraseña').value;

    // URL del servicio web de login
    const url = "sw_login.php";
    
    const data = {
        Usuario: usuario,
        password: password
    };

    // Realiza la solicitud con fetch
    fetch(url, {
        method: "POST",
        body: JSON.stringify(data),
        headers: {
            "Content-Type": "application/json"
        }
    })
    .then((response) => response.json())
    .catch((error) => console.error("error", error))
    .then(function (json){
        console.log(json);

        // Verifica si el login fue exitoso
        if (json.success) {
            // Mostrar mensaje de éxito
            alert(json.message);

            // Redirige a la página principal en caso de login exitoso
            window.location.href = "pedirBocadillo.html";
        } else {
            // Mostrar mensaje de error
            document.getElementById("errorMsg").textContent = json.message;
        }
    })
}
