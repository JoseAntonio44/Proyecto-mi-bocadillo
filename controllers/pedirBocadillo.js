document.addEventListener("DOMContentLoaded", function () {
    // Selecciona el botón de bocadillo frío
    const botonFrio = document.getElementById("botonBocadilloFrio");

    // Maneja el clic en el botón de bocadillo frío
    botonFrio.addEventListener("click", function () {
        // Muestra el cuadro de confirmación
        const confirmacion = confirm("¿Deseas confirmar el pedido de bocadillo frío del día?");
        if (confirmacion) {
            // Si el usuario confirma, realiza el pedido
            hacerPedido("frio");
        }
    });

    function hacerPedido(tipo) {
        const url = "sw_pedirBocadillo.php";
        const data = { tipo_bocadillo: tipo };

        fetch(url, {
            method: "POST",
            body: JSON.stringify(data),
            headers: {
                "Content-Type": "application/json"
            }
        })
        .then(response => response.json())
        .then(json => {
            if (json.success) {
                alert("Pedido realizado con éxito: " + json.message);
            } else {
                alert("Error al realizar el pedido: " + json.message);
            }
        })
        .catch(error => console.error("Error en la solicitud:", error));
    }
});
