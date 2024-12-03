document.addEventListener("DOMContentLoaded", function () {

    const url = "sw_pantallaBocadillo.php";

    confirmarPedido();
    function confirmarPedido() {
        const botonFrio = document.getElementById("botonBocadilloFrio");
        const botonCaliente = document.getElementById("botonBocadilloCaliente");
        const botonConfirmar = document.getElementById("confirmar");
        const botonCancelar = document.getElementById("cancelar");

        let tipoSeleccionado = null;

        botonFrio.addEventListener("click", function () {
            tipoSeleccionado = "frio";
            mostrarSeleccion(tipoSeleccionado);
        }

        );
        botonCaliente.addEventListener("click", function () {
            tipoSeleccionado = "caliente";
            mostrarSeleccion(tipoSeleccionado);
        });

        

        botonConfirmar.addEventListener("click", function () {
            if (tipoSeleccionado) {
                hacerPedido(tipoSeleccionado);
            } else {
                alert("Por favor, selecciona un tipo de bocadillo antes de confirmar.");
            }
        });

        botonCancelar.addEventListener("click", function () {
            tipoSeleccionado = null;
            document.getElementById('bocadilloSeleccionado').innerHTML = "Ha cancelado el pedido. Seleccione un bocadillo por favor";
            document.getElementById('precioSeleccionado').innerHTML = "";
        });
    }

    function mostrarSeleccion(tipo) {
        //Promesa para traer el bocadillo seleccionado y sus detalles
        const data = { action: "mostrarBocadillos" };
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
                    console.log(tipo);
                    if(tipo == "frio"){
                        document.getElementById('bocadilloSeleccionado').innerHTML = json.data[0].nombre;
                        document.getElementById('precioSeleccionado').innerHTML = json.data[0].pvp;
                    }else if(tipo == "caliente"){
                        document.getElementById('bocadilloSeleccionado').innerHTML = json.data[1].nombre;
                        document.getElementById('precioSeleccionado').innerHTML = json.data[1].pvp;
                    }else if(tipo == null){
                        document.getElementById('bocadilloSeleccionado').innerHTML = "Seleccione un bocadillo";
                    }
                
                } else {
                    document.getElementById('mensajePedido').innerHTML = json.message;
                }
            })
    }

    function hacerPedido(tipo) {

        const data = { action: "comprobarPedidosUsuario" };
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
                    document.getElementById('mensajePedido').innerHTML = "No puede realizar otro pedido el dia de hoy";
                } else {
                    const dataHacerPedido = { tipo_bocadillo: tipo, action: "hacerPedido" };
                    fetch(url, {
                        method: "POST",
                        body: JSON.stringify(dataHacerPedido),
                        headers: {
                            "Content-Type": "application/json"
                        }
                    })
                        .then(response => response.json())
                        .then(json => {
                            if (json.success) {
                                document.getElementById('mensajePedido').innerHTML = json.message;
                            } else {
                                document.getElementById('mensajePedido').innerHTML = json.message;
                            }
                        })
                        .catch(error => console.error("Error en la solicitud:", error));
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
                    if (json.data.length >= 1) {

                        if (json.data[0].disponible == 0) {
                            document.getElementById('bocadilloFrio').innerHTML = "Bocadillo frío no disponible";
                            document.getElementById('bocadilloCaliente').innerHTML = "Bocadillo caliente no disponible";
                        } else {
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
