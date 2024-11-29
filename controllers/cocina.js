document.addEventListener("DOMContentLoaded", function () {


    const url = "http://localhost/mi_bocadillo/sw_cocina.php";
    listarPedidos();
    function listarPedidos() {
        const data = { action: "listarPedidos" };

        fetch(url, {
            method: "POST",
            body: JSON.stringify(data),
            headers: {
                "Content-Type": "application/json"
            }
        })
            .then(response => response.json())
            .then(json => {
                console.log(json);
                if (json.success && json.data.length > 0) {
                    var tabla = document.getElementById("tbodyPedidos");

                    tabla.innerHTML = "";

                    for (var i = 0; i < json.data.length; i++) {
                        const fila = tabla.insertRow();
                        var celda1 = fila.insertCell(0);
                        var celda2 = fila.insertCell(1);
                        var celda3 = fila.insertCell(2);
                        var celda4 = fila.insertCell(3);
                        var celda5 = fila.insertCell(4);

                        celda1.textContent = json.data[i].id_alumno;
                        celda2.textContent = json.data[i].id_bocadillo;
                        celda3.textContent = json.data[i].fecha;
                        celda4.textContent = json.data[i].pvp;

                        //Crear un botón
                        var boton = document.createElement("button");
                        boton.textContent = "Seleccionar";
                        boton.className = "btn btn-success"; //Clase de bootstrap para darle estilo al boton
                        boton.onclick = (function (index) {
                            return function () {
                                console.log("Botón de la fila " + index + " presionado");


                                //Obtiene la fecha de la fila seleccionada
                                var fechaPedido = json.data[index].fecha;
                                console.log("Fecha seleccionada: " + fechaPedido);

                                //Se manda la fecha al php
                                var data = { fecha: fechaPedido, action: "eliminarPedido" };


                                //Hace una promesa para eliminar el pedido que ha sido recogido
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
                                            console.log("Pedido eliminado con éxito.");
                                            listarPedidos(); // Actualiza la lista después de eliminar
                                            document.getElementById("mensaje").textContent = json.message;
                                        } else {
                                            document.getElementById("mensaje").textContent = json.message;
                                        }
                                    })
                                    .catch(error => console.error("Error en la solicitud:", error));
                            };
                        })(i);//i del for para que sepa que fila es

                        celda5.appendChild(boton);

                    }
                } else {
                    console.log("No hay datos disponibles o la solicitud falló.");
                }
            })
            .catch(error => console.error("Error en la solicitud:", error));
    }
    mostrarBocadillosSemanales();
    function mostrarBocadillosSemanales() {
        const data = { action: "listarBocadillosSemanales" };
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
                console.log(json);
                if (json.success && json.data.length > 0) {
                    var tabla = document.getElementById("tbodyBocadillos");

                    tabla.innerHTML = "";

                    for (var i = 0; i < json.data.length; i++) {
                        const fila = tabla.insertRow();
                        var celda1 = fila.insertCell(0);
                        var celda2 = fila.insertCell(1);
                        var celda3 = fila.insertCell(2);
                        var celda4 = fila.insertCell(3);
                        var celda5 = fila.insertCell(4);
                        var celda6 = fila.insertCell(5);

                        celda1.textContent = json.data[i].nombre;
                        celda2.textContent = json.data[i].ingredientes;
                        celda3.textContent = json.data[i].pvp;
                        celda4.textContent = json.data[i].frio;
                        celda5.textContent = json.data[i].id_alergeno;
                        celda6.textContent = json.data[i].dia;

                    }
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
                    //Usuario autenticado, permanece en la página
                    console.log("Usuario autenticado.");
                } else {
                    //Redirige al login si no está autenticado
                    window.location.href = 'login.html';
                }
            })
            .catch(error => console.error("Error al verificar autenticación:", error));
    }
});
