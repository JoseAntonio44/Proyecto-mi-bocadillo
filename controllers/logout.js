document.addEventListener("DOMContentLoaded", function () {

    const botonLogout = document.getElementById("botonLogout");

    botonLogout.addEventListener("click", function () {
        cerrarSesion();
    });

    function cerrarSesion() {
        const url = "sw_logout.php";

        fetch(url, {
            method: "GET",
            headers: {
                "Content-Type": "application/json"
            }
        })
        .then(response => response.json())
        .then(json => {
            if (json.success) {
                window.location.href = 'login.html';
            } else {
                alert("Error al cerrar sesión " + json.message);
            }
        })
        .catch(error => console.error("Error en la solicitud:", error));
    }

});