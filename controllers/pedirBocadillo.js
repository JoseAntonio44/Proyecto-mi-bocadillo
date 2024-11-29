document.addEventListener("DOMContentLoaded", function () {

    const botonFrio = document.getElementById("botonBocadilloFrio");

    const url = "sw_pantallaBocadillo.php";

    //clic en el botón de bocadillo frío
    botonFrio.addEventListener("click", function () {
        const getElementById = document.getElementById("exampleModal");

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
        const data = { tipo_bocadillo: tipo, action: "hacerPedido" };

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
    function mostrarBocadillos() {
        const data = { action: "mostrarBocadillos" };

        fetch(url,
            {
                method: "POST",
                body: JSON.stringify(data),
                headers: {
                    "Content-Type": "application/json"
                }
            }
        )
            .then(response => response.json())
            .then(json => {
                console.log(json)
                if (json.success) {
                    if(json.data.length >= 1) {
                        
                        if(json.data[0].disponible == 0) {
                            document.getElementById('bocadilloFrio').innerHTML = "Bocadillo frío no disponible";
                            document.getElementById('bocadilloCaliente').innerHTML = "Bocadillo caliente no disponible";
                        }else{
                            document.getElementById('bocadilloFrio').innerHTML = json.data[0].nombre;
                            document.getElementById('bocadilloCaliente').innerHTML = json.data[1].nombre;
                        }
                    }
                    document.getElementById('ingredientesFrio').innerHTML = json.data[0].ingredientes;
                    document.getElementById('ingredientesCaliente').innerHTML = json.data[1].ingredientes;
                    
                    document.getElementById('alergenoFrio').innerHTML = json.data[0].id_alergeno;
                    document.getElementById('alergenoCaliente').innerHTML = json.data[1].id_alergeno;

                    document.getElementById('precioFrio').innerHTML = json.data[0].pvp;
                    document.getElementById('precioCaliente').innerHTML = json.data[1].pvp;
                    
                
                }
            })
            .catch(error => console.error("Error en la solicitud:", error));
    }
    
    mostrarNombre();
    function mostrarNombre() {
        const data = { action: "mostrarNombre" };

        fetch(url,
            {
                method: "POST",
                body: JSON.stringify(data),
                headers: {
                    "Content-Type": "application/json"
                }
            })
            .then(response => response.json())
            .then(json => {
                console.log(json)
                if (json.success) {
                    document.getElementById('nombre_usuario').innerHTML = json.data[0].nombre;
                }
            })
            .catch(error => console.error("Error en la solicitud:", error));
    }

    acceso();
    function acceso() {
        const data = { action: "verificarAutenticacion" };

        fetch(url, {
            method: "POST",
            body: JSON.stringify(data),
            headers: {
                "Content-Type": "application/json"
            }
        })
            .then(response => response.json())
            .then(data => {
                console.log("Json bocata: ", data);
                if (data.success) {
                    // Usuario autenticado, permanece en la página
                    console.log("Usuario autenticado.");
                } else {
                    // Redirige al login si no está autenticado
                    window.location.href = 'login.html';
                }
            })
            .catch(error => console.error("Error al verificar autenticación:", error));
    }

});
