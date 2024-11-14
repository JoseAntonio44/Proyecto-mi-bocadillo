document.addEventListener("DOMContentLoaded", function () {
    
    const botonFrio = document.getElementById("botonBocadilloFrio");

    //clic en el botón de bocadillo frío
    botonFrio.addEventListener("click", function () {
        
        const confirmacion = confirm("¿Deseas confirmar el pedido de bocadillo frío del día?");
        if (confirmacion) {
            hacerPedido("frio");
        }
    });
    const botonCaliente = document.getElementById("botonBocadilloCaliente");
    botonCaliente.addEventListener("click", function () {
        const confirmacion = confirm("¿Deseas confirmar el pedido de bocadillo caliente del día?");
        if (confirmacion) {
            hacerPedido("caliente");
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

    mostrarBocadillos();
    function mostrarBocadillos(){
        const url = "sw_mostrarBocadillos.php"; // Mandar json de js al php
        
        fetch(url)
        .then(response => response.json())
        .then(json => {
            console.log(json)
            if(json.success){
                document.getElementById('bocadilloFrio').innerHTML = `${json.data[0].nombre}`; 
                document.getElementById('bocadilloCaliente').innerHTML = `${json.data[1].nombre}`;                             
            }
        })
        .catch(error => console.error("Error en la solicitud:", error));
    }
});
