$(document).ready(function () {
    function realizarAjax(url, datos, onSuccess, onError = null) {
        $.ajax({
            url: url || "",
            type: "POST",
            contentType: false,
            data: datos,
            processData: false,
            cache: false,
            success: function (respuesta) {
                try {
                    const result = JSON.parse(respuesta);
                    if (result.ok) {
                        onSuccess(result);
                    } else {
                        Swal.fire("Error", result.mensaje || "Error desconocido", "error");
                    }
                } catch (error) {
                    Swal.fire("Error", "Error al procesar la respuesta del servidor", "error");
                }
            },
            error: function () {
                if (onError) {
                    onError();
                } else {
                    Swal.fire("Error", "Error en la solicitud AJAX", "error");
                }
            }
        });
    }

    function cargarEventos() {
        const datos = new FormData();
        datos.append("accion", "listado_eventos");
        realizarAjax("", datos, (result) => {
            actualizarListadoEventos(result.respuesta);
        });
    }

    function cargarEventosAnteriores() {
        const datos = new FormData();
        datos.append("accion", "listado_eventos_anteriores");
        realizarAjax("", datos, (result) => {
            if (result.respuesta.length > 0) {
                actualizarListadoEventosAnteriores(result.respuesta);
            } else {
                $("#tablaEventosAnteriores tbody").html("<tr><td colspan='7'>No hay eventos anteriores</td></tr>");
            }
        });
    }
    function cargarAtletasInscritos(idCompetencia) {
        const datos = new FormData();
        datos.append("accion", "listado_atletas_inscritos");
        datos.append("id_competencia", idCompetencia);
    
        realizarAjax("", datos, (result) => {
            actualizarTablaAtletasInscritos(result.respuesta, idCompetencia);
        });
    }
    
    function actualizarTablaAtletasInscritos(atletas, idCompetencia) {
        let filas = "";
        atletas.forEach((atleta, index) => {
            const tieneResultados = atleta.arranque || atleta.envion; // Revisamos si tiene resultados
            filas += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${atleta.nombre} ${atleta.apellido}</td>
                    <td>${atleta.id_atleta}</td>
                    <td>
                        <button 
                            class="btn btn-outline-primary btn-sm ${tieneResultados ? "modificarResultados" : "registrarResultados"}" 
                            data-bs-toggle="modal" 
                            data-bs-target="#${tieneResultados ? "modalModificarResultados" : "modalRegistrarResultados"}" 
                            data-id-competencia="${idCompetencia}" 
                            data-id-atleta="${atleta.cedula}" 
                            data-nombre="${atleta.nombre} ${atleta.apellido}" 
                            data-cedula="${atleta.cedula}"
                            ${tieneResultados ? `
                                data-arranque="${atleta.arranque}" 
                                data-envion="${atleta.envion}" 
                                data-medalla-arranque="${atleta.medalla_arranque}" 
                                data-medalla-envion="${atleta.medalla_envion}" 
                                data-medalla-total="${atleta.medalla_total}" 
                                data-total="${atleta.total}"` : ""}
                        >
                            ${tieneResultados ? "Modificar Resultados" : "Registrar Resultados"}
                        </button>
                    </td>
                </tr>`;
        });
    
        if (filas === "") {
            filas = "<tr><td colspan='4'>No hay atletas inscritos en esta competencia.</td></tr>";
        }
    
        $("#tablaAtletasInscritos tbody").html(filas);
    }
    
    $(document).on("click", ".modificarResultados", function () {
        const idCompetencia = $(this).data("id-competencia");
        const idAtleta = $(this).data("id-atleta");
     
        $("#id_competencia_modificar").val(idCompetencia);
        $("#id_atleta_modificar").val(idAtleta);
        $("#arranque_modificar").val($(this).data("arranque"));
        $("#envion_modificar").val($(this).data("envion"));
        $("#medalla_arranque_modificar").val($(this).data("medalla-arranque"));
        $("#medalla_envion_modificar").val($(this).data("medalla-envion"));
        $("#medalla_total_modificar").val($(this).data.data("medalla-total"));
    $("#total_modificar").val($(this).data("total"));
});

$("#formModificarResultados").on("submit", function (e) {
    e.preventDefault();

    const datos = new FormData(this);
    datos.append("accion", "modificar_resultados");

    realizarAjax("", datos, (result) => {
        if (result.ok) {
            Swal.fire("Éxito", result.mensaje, "success");
            $("#modalModificarResultados").modal("hide");
            cargarAtletasInscritos($("#id_competencia_modificar").val()); // Recargar la tabla
        } else {
            Swal.fire("Error", result.mensaje || "Error al modificar los resultados.", "error");
        }
    });
});

    
    function actualizarListadoEventos(eventos) {
        let listadoEventos = "";
        eventos.forEach(evento => {
            listadoEventos += `
                <div class="col-md-3 mb-4 d-flex align-items-stretch">
                    <div class="card border-primary position-relative">
                        <button class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 eliminarEvento" 
                            data-id="${evento.id_competencia}" title="Eliminar">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                        <div class="card-header lead m-0 p-0 px-3 pb-1 bg-primary text-white">
                            <small><strong>${evento.nombre}</strong></small>
                        </div>
                        <div class="row g-0">
                            <div class="col-md-1 bg-primary border-end border-primary">
                                <img src="" alt="" class="img-fluid rounded-start">
                            </div>
                            <div class="col-md-10">
                                <div class="card-body p-3 ps-4">
                                    <div class="card-title m-0 lead text-primary"><strong>${evento.nombre}</strong></div>
                                    <p class="card-text lead m-0 fs-6"><strong>Fecha: ${evento.fecha_inicio} al ${evento.fecha_fin}</strong></p>
                                    <p class="card-text lead m-0 fs-6"><strong>Cupos Disponibles: ${evento.cupos_disponibles}</strong></p>
                                    <p class="card-text lead m-0 fs-6"><strong>Participantes: ${evento.participantes}</strong></p>
                                </div>
                                <div class="card-footer my-1 border-primary">
                                    <div class="btn-group">
                                        <button class="btn btn-outline-primary btn-sm verDetallesEvento" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalVerEventoActivo" 
                                            data-id="${evento.id_competencia}" 
                                            data-nombre="${evento.nombre}" 
                                            data-inicio="${evento.fecha_inicio}" 
                                            data-fin="${evento.fecha_fin}" 
                                            data-ubicacion="${evento.lugar_competencia}">
                                            Ver
                                        </button>
                                        <button class="btn btn-outline-primary btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalInscribirEvento"
                                            data-id="${evento.id_competencia}" 
                                            data-id-categoria="${evento.categoria}" 
                                            data-id-sub="${evento.subs}">
                                            Inscribir
                                        </button>
                                        <button class="btn btn-outline-primary btn-sm" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalModificarCompetencia" 
                                            data-id="${evento.id_competencia}">
                                            Modificar
                                        </button>
                                        <button id="cerrarC" class="cerrarC btn btn-outline-danger btn-sm" 
                                            data-id="${evento.id_competencia}">
                                            Cerrar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;
        });
        $("#lista-eventos").html(listadoEventos);
    }
    

    function actualizarListadoEventosAnteriores(eventos) {
        let listado = "";
        eventos.forEach(evento => {
            listado += `
            <tr>
                <td>${evento.id_competencia}</td>
                <td>${evento.nombre}</td>
                <td>${evento.fecha_inicio}</td>
                <td>${evento.fecha_fin}</td>
                <td>${evento.lugar_competencia}</td>
                <td>${evento.estado}</td>
                <td>
                    <button class="btn btn-outline-info btn-sm consultarEventoAnterior" data-id="${evento.id_competencia}">Consultar</button>
                </td>
            </tr>`;
        });
        $("#tablaEventosAnteriores tbody").html(listado);
    }

    function eliminarEvento(idCompetencia) {
        Swal.fire({
            title: '¿Estás seguro de eliminar este evento?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                const datos = new FormData();
                datos.append("accion", "eliminar_evento");
                datos.append("id_competencia", idCompetencia);
                realizarAjax("", datos, () => {
                    $(`button[data-id="${idCompetencia}"]`).closest('.col-md-3').remove();
                    Swal.fire("Éxito", "Evento eliminado con éxito", "success");
                });
            }
        });
    }

    function cargarListadoCategorias() {
        const datos = new FormData();
        datos.append("accion", "listado_categoria");
        realizarAjax("", datos, (result) => {
            actualizarListadoCategorias(result.respuesta);
        });
    }

    function actualizarListadoCategorias(categorias) {
        let opciones = "<option value='' selected>Seleccione una</option>";
        categorias.forEach(categoria => {
            opciones += `<option value="${categoria.id_categoria}">${categoria.nombre}</option>`;
        });
        $("#in_categoria").html(opciones);
        $("#categoria_modificar").html(opciones);
    }

    function cargarListadoSubs() {
        const datos = new FormData();
        datos.append("accion", "listado_subs");
        realizarAjax("", datos, (result) => {
            actualizarListadoSubs(result.respuesta);
        });
    }

    function actualizarListadoSubs(subs) {
        let opciones = "<option selected>Seleccione una</option>";
        subs.forEach(sub => {
            opciones += `<option value="${sub.id_sub}">${sub.nombre}</option>`;
        });
        $("#in_subs").html(opciones);
        $("#subs_modificar").html(opciones);
    }

    function cargarListadoTipos() {
        const datos = new FormData();
        datos.append("accion", "listado_tipo");
    
        realizarAjax("", datos, (result) => {
            if (result.ok) {
                actualizarTablaTipos(result.respuesta);
            } else {
                Swal.fire("Error", result.mensaje || "Error al consultar los tipos", "error");
            }
        });
    }
    

    function actualizarListadoTipos(tipos) {
        let opciones = "<option selected>Seleccione una</option>";
        tipos.forEach(tipo => {
            opciones += `<option value="${tipo.id_tipo_competencia}">${tipo.nombre}</option>`;
        });
        $("#in_tipo").html(opciones);
        $("#tipo_modificar").html(opciones);
    }

    function cargarDatosModificar() {
        cargarListadoCategorias();
        cargarListadoSubs();
        cargarListadoTipos();
    }

//aqui colocare los clicks para los elementos que no son estaticos sino que se crean directamente aqui en el js, porque no me estan agarrando con el jquery
    $(document).on('click', '.eliminarEvento', function () {
        const idCompetencia = $(this).data('id');
        eliminarEvento(idCompetencia);
    });
   
    $(document).on('click', '.cerrarC', function () {
        const idCompetencia = $(this).data('id');
        cerrarEvento(idCompetencia);
    });

    $(document).on("click", ".registrarResultados", function () {
        const idCompetencia = $(this).data("id-competencia");
        const idAtleta = $(this).data("cedula");
        const nombre = $(this).data("nombre");
        const cedula = $(this).data("cedula");
 
        $("#nombreAtletaResultados").text(nombre);
        $("#cedulaAtletaResultados").text(cedula);
        $("#formRegistrarResultados").data("id-competencia", idCompetencia);
        $("#formRegistrarResultados").data("id-atleta", idAtleta);
 
        $("#modalRegistrarResultados").modal("show");
    });
    $(document).on('click', '.consultarEventoAnterior', function () {
        const idCompetencia = $(this).data('id');
        const datos = new FormData();
        datos.append("accion", "obtener_competencia");
        datos.append("id_competencia", idCompetencia);
        realizarAjax("", datos, (result) => {
            const evento = result.respuesta;
            $('#detallesNombreEvento').text(evento.nombre);
            $('#detallesFechaInicio').text(evento.fecha_inicio);
            $('#detallesFechaFin').text(evento.fecha_fin);
            $('#detallesUbicacion').text(evento.lugar_competencia);
            $('#detallesEstado').text(evento.estado);
            $('#modalConsultarEventoAnterior').modal('show');
        });
    });
    $(document).on("click", ".verDetallesEvento", function () {
        const idCompetencia = $(this).data("id");
        const datos = new FormData();
        datos.append("accion", "obtener_competencia");
        datos.append("id_competencia", idCompetencia);
    
        realizarAjax("", datos, (result) => {
            const competencia = result.respuesta;
     
            $("#detallesNombreEvento").text(competencia.nombre);
            $("#detallesFechaInicio").text(competencia.fecha_inicio);
            $("#detallesFechaFin").text(competencia.fecha_fin);
            $("#detallesUbicacion").text(competencia.lugar_competencia);
            $("#detallesEstado").text(competencia.estado);
     
            cargarAtletasInscritos(idCompetencia);
        });
    });
    
    function cerrarEvento(idCompetencia) {
        Swal.fire({
            title: '¿Estás seguro de cerrar este evento?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, cerrar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                const datos = new FormData();
                datos.append("accion", "cerrar_evento");
                datos.append("id_competencia", idCompetencia);
                realizarAjax("", datos, () => {
                    cargarEventos();
                    Swal.fire("Éxito", "Evento cerrado con éxito", "success");
                });
            }
        });
    }
    function cargarAtletasDisponiblesParaInscripcion(idCompetencia, idCategoria, idSub) {
        const datos = new FormData();
        datos.append("accion", "listado_atletas_disponibles");
        datos.append("id_categoria", idCategoria);
        datos.append("id_sub", idSub);
        datos.append("id_competencia", idCompetencia);
        console.log("Enviando datos al servidor:", { idCompetencia, idCategoria, idSub });
    
        realizarAjax("", datos, (result) => {
            console.log("Respuesta recibida:", result);
    
            if (result.ok) {
                actualizarTablaAtletasDisponibles(result.respuesta, idCompetencia);
            } else {
                Swal.fire("Error", result.mensaje || "Error al obtener atletas", "error");
            }
        });
    }
    
    
    function actualizarTablaAtletasDisponibles(atletas, idCompetencia) {
        console.log("Actualizando tabla con atletas:", atletas);
        let tabla = $("#tablaParticipantesInscripcion tbody");
        tabla.empty();
    
        if (atletas.length > 0) {
            atletas.forEach((atleta, index) => {
                tabla.append(`
                    <tr>
                        <td>${index + 1}</td>
                        <td>${atleta.nombre} ${atleta.apellido}</td>
                        <td>${atleta.id_atleta}</td>
                        <td>${atleta.peso} kg</td>
                        <td>${calcularEdad(atleta.fecha_nacimiento)}</td>
                        <td>
                            <input type="checkbox" class="form-check-input" name="atletas" value="${atleta.id_atleta}">
                        </td>
                    </tr>
                `);
            });
        } else {
            tabla.append("<tr><td colspan='6'>No se encontraron atletas que cumplan con los requisitos.</td></tr>");
        }
    }
    
    $("#formInscribirAtletas").on("submit", function (e) {
        e.preventDefault();
    
        const idCompetencia = $("#modalInscribirEvento").data("id-competencia");
        const atletasSeleccionados = $("input[name='atletas']:checked")
            .map(function () {
                return $(this).val();
            })
            .get();
    
        if (atletasSeleccionados.length === 0) {
            Swal.fire("Advertencia", "Debe seleccionar al menos un atleta para inscribir.", "warning");
            return;
        }
    
        const datos = new FormData();
        datos.append("accion", "inscribir_atletas");
        datos.append("id_competencia", idCompetencia);
        datos.append("atletas", JSON.stringify(atletasSeleccionados));
    
        realizarAjax("", datos, (result) => {
            if (result.ok) {
                Swal.fire("Éxito", result.mensaje, "success");
                $("#modalInscribirEvento").modal("hide");
                cargarAtletasInscritos(idCompetencia);
            } else {
                Swal.fire("Error", result.mensaje || "Error al inscribir atletas", "error");
            }
        });
    });
    
    $("#formRegistrarResultados").on("submit", function (e) {
        e.preventDefault();

        const idCompetencia = $(this).data("id-competencia");
        const idAtleta = $(this).data("id-atleta");
        const arranque = $("#arranque").val();
        const envion = $("#envion").val();
        const medallaArranque = $("#medalla_arranque").val();
        const medallaEnvion = $("#medalla_envion").val();
        const medallaTotal = $("#medalla_total").val();
        const total = parseInt(arranque) + parseInt(envion);

        if (!arranque || !envion || isNaN(total)) {
            Swal.fire("Error", "Debes completar todos los campos correctamente.", "error");
            return;
        }

        const datos = new FormData();
        datos.append("accion", "registrar_resultados");
        datos.append("id_competencia", idCompetencia);
        datos.append("id_atleta", idAtleta);
        datos.append("arranque", arranque);
        datos.append("envion", envion);
        datos.append("medalla_arranque", medallaArranque);
        datos.append("medalla_envion", medallaEnvion);
        datos.append("medalla_total", medallaTotal);
        datos.append("total", total);

        realizarAjax("", datos, (result) => {
            if (result.ok) {
                Swal.fire("Éxito", result.mensaje, "success");
                $("#modalRegistrarResultados").modal("hide");
                cargarAtletasInscritos(idCompetencia);
            } else {
                Swal.fire("Error", result.mensaje || "Error al registrar los resultados.", "error");
            }
        });
    });
    $("#formRegistrarSubs").on("submit", function (e) {
        e.preventDefault();
    
        const nombre = $("#in_subs_nombre").val().trim();
        const edadMinima = parseInt($("#in_edad_minima").val());
        const edadMaxima = parseInt($("#in_edad_maxima").val());
    
        if (!nombre || isNaN(edadMinima) || isNaN(edadMaxima) || edadMinima >= edadMaxima) {
            Swal.fire("Error", "Por favor, complete los campos correctamente.", "error");
            return;
        }
    
        const datos = new FormData(this);
        datos.append("accion", "incluir_subs");
    
        realizarAjax("", datos, (result) => {
            if (result.ok) {
                Swal.fire("Éxito", "Sub registrado con éxito", "success");
                $("#formRegistrarSubs")[0].reset();  
                cargarListadoSubs(); 
            } else {
                Swal.fire("Error", result.mensaje || "No se pudo registrar el sub", "error");
            }
        });
    });
    $("#btnConsultarSubs").on("click", function () {
        cargarListadoSubs2();  
        $("#contenedorTablaSubs").show();  
    });
    function cargarListadoSubs2() {
        const datos = new FormData();
        datos.append("accion", "listado_subs");
    
        realizarAjax("", datos, (result) => {
            if (result.ok) {
                actualizarTablaSubs(result.respuesta);  
            } else {
                Swal.fire("Error", result.mensaje || "Error al consultar los subs", "error");
            }
        });
    }
    function actualizarTablaSubs(subs) {
        const tbody = $("#tablaSubs tbody");
        tbody.empty(); 
    
        subs.forEach((sub, index) => {
            tbody.append(`
                <tr>
                    <td>${index + 1}</td>
                    <td>${sub.nombre}</td>
                    <td>${sub.edad_minima}</td>
                    <td>${sub.edad_maxima}</td>
                    <td>
                        <button class="btn btn-warning btn-sm btnEditarSub" data-id="${sub.id_sub}" data-nombre="${sub.nombre}" data-edad-minima="${sub.edad_minima}" data-edad-maxima="${sub.edad_maxima}">Editar</button>
                        <button class="btn btn-danger btn-sm btnEliminarSub" data-id="${sub.id_sub}">Eliminar</button>
                    </td>
                </tr>
            `);
        });
    }
    $(document).on("click", ".btnEliminarSub", function () {
        const idSub = $(this).data("id");
    
        Swal.fire({
            title: "¿Estás seguro?",
            text: "Esta acción eliminará el sub seleccionado.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "No, cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                const datos = new FormData();
                datos.append("accion", "eliminar_sub");
                datos.append("id_sub", idSub);
    
                realizarAjax("", datos, (result) => {
                    if (result.ok) {
                        Swal.fire("Éxito", "Sub eliminado con éxito", "success");
                        cargarListadoSubs(); 
                    } else {
                        Swal.fire("Error", result.mensaje || "Error al eliminar el sub", "error");
                    }
                });
            }
        });
    });
    
    $(document).on("click", ".btnEditarSub", function () {
        const idSub = $(this).data("id");
        const nombre = $(this).data("nombre");
        const edadMinima = $(this).data("edad-minima");
        const edadMaxima = $(this).data("edad-maxima");
    
        Swal.fire({
            title: "Editar Sub",
            html: `
                <label for="nombreSub">Nombre:</label>
                <input id="nombreSub" class="swal2-input" value="${nombre}">
                <label for="edadMinima">Edad Mínima:</label>
                <input id="edadMinima" class="swal2-input" type="number" value="${edadMinima}">
                <label for="edadMaxima">Edad Máxima:</label>
                <input id="edadMaxima" class="swal2-input" type="number" value="${edadMaxima}">
            `,
            focusConfirm: false,
            preConfirm: () => {
                const nuevoNombre = document.getElementById("nombreSub").value;
                const nuevaEdadMinima = document.getElementById("edadMinima").value;
                const nuevaEdadMaxima = document.getElementById("edadMaxima").value;
    
                if (!nuevoNombre || nuevaEdadMinima === "" || nuevaEdadMaxima === "") {
                    Swal.showValidationMessage("Todos los campos son obligatorios");
                }
                if (parseInt(nuevaEdadMinima) >= parseInt(nuevaEdadMaxima)) {
                    Swal.showValidationMessage("La edad mínima debe ser menor que la máxima");
                }
    
                return { nuevoNombre, nuevaEdadMinima, nuevaEdadMaxima };
            },
        }).then((result) => {
            if (result.isConfirmed) {
                const datos = new FormData();
                datos.append("accion", "modificar_sub");
                datos.append("id_sub", idSub);
                datos.append("nombre", result.value.nuevoNombre);
                datos.append("edadMinima", result.value.nuevaEdadMinima);
                datos.append("edadMaxima", result.value.nuevaEdadMaxima);
    
                realizarAjax("", datos, (result) => {
                    if (result.ok) {
                        Swal.fire("Éxito", "Sub modificado con éxito", "success");
                        cargarListadoSubs(); // Recargar la tabla
                    } else {
                        Swal.fire("Error", result.mensaje || "Error al modificar el sub", "error");
                    }
                });
            }
        });
    });
    $("#formRegistrarCategoria").on("submit", function (e) {
        e.preventDefault();
        const datos = new FormData(this);
        datos.append("accion", "incluir_categoria");
    
        realizarAjax("", datos, (result) => {
            if (result.ok) {
                Swal.fire("Éxito", "Categoría registrada con éxito.", "success");
                cargarListadoCategorias2();  
                $("#formRegistrarCategoria")[0].reset();
            } else {
                Swal.fire("Error", result.mensaje || "Error al registrar la categoría.", "error");
            }
        });
    });
    
    function cargarListadoCategorias2() {
        const datos = new FormData();
        datos.append("accion", "listado_categoria");
    
        realizarAjax("", datos, (result) => {
            if (result.ok) {
                actualizarTablaCategorias(result.respuesta);
                $("#contenedorTablaCategorias").show();
            } else {
                Swal.fire("Error", result.mensaje || "Error al consultar las categorías.", "error");
            }
        });
    }
    
    
    function actualizarTablaCategorias(categorias) {
        const tbody = $("#tablaCategorias tbody");
        tbody.empty();
    
        categorias.forEach((categoria, index) => {
            tbody.append(`
                <tr>
                    <td>${index + 1}</td>
                    <td>${categoria.nombre}</td>
                    <td>${categoria.peso_minimo}</td>
                    <td>${categoria.peso_maximo}</td>
                    <td>
                        <button class="btn btn-warning btn-sm btnEditarCategoria" 
                            data-id="${categoria.id_categoria}" 
                            data-nombre="${categoria.nombre}" 
                            data-peso-minimo="${categoria.peso_minimo}" 
                            data-peso-maximo="${categoria.peso_maximo}">
                            Editar
                        </button>
                        <button class="btn btn-danger btn-sm btnEliminarCategoria" 
                            data-id="${categoria.id_categoria}">
                            Eliminar
                        </button>
                    </td>
                </tr>
            `);
        });
    }
    
    
    $(document).on("click", ".btnEditarCategoria", function () {
        const id = $(this).data("id");
        const nombre = $(this).data("nombre");
        const pesoMinimo = $(this).data("peso-minimo");
        const pesoMaximo = $(this).data("peso-maximo");
    
        Swal.fire({
            title: "Editar Categoría",
            html: `
                <input id="nuevoNombre" class="swal2-input" placeholder="Nombre" value="${nombre}">
                <input id="nuevoPesoMinimo" class="swal2-input" type="number" placeholder="Peso Mínimo" value="${pesoMinimo}">
                <input             id="nuevoPesoMaximo" class="swal2-input" type="number" placeholder="Peso Máximo" value="${pesoMaximo}">
        `,
        showCancelButton: true,
        confirmButtonText: "Guardar",
        cancelButtonText: "Cancelar",
        preConfirm: () => {
            const nuevoNombre = document.getElementById("nuevoNombre").value;
            const nuevoPesoMinimo = document.getElementById("nuevoPesoMinimo").value;
            const nuevoPesoMaximo = document.getElementById("nuevoPesoMaximo").value;

            if (!nuevoNombre || nuevoNombre.length < 2) {
                Swal.showValidationMessage("El nombre es inválido.");
            } else if (!nuevoPesoMinimo || !nuevoPesoMaximo || nuevoPesoMinimo < 0 || nuevoPesoMaximo <= nuevoPesoMinimo) {
                Swal.showValidationMessage("El rango de peso es inválido.");
            } else {
                return {
                    nombre: nuevoNombre,
                    pesoMinimo: nuevoPesoMinimo,
                    pesoMaximo: nuevoPesoMaximo
                };
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const datos = new FormData();
            datos.append("accion", "modificar_categoria");
            datos.append("id_categoria", id);
            datos.append("nombre", result.value.nombre);
            datos.append("pesoMinimo", result.value.pesoMinimo);
            datos.append("pesoMaximo", result.value.pesoMaximo);

            realizarAjax("", datos, (respuesta) => {
                if (respuesta.ok) {
                    Swal.fire("Éxito", "Categoría modificada con éxito.", "success");
                    cargarListadoCategorias();
                } else {
                    Swal.fire("Error", respuesta.mensaje || "Error al modificar la categoría.", "error");
                }
            });
        }
    });
});
$(document).on("click", ".btnEliminarCategoria", function () {
    const idCategoria = $(this).data("id");

    Swal.fire({
        title: "¿Estás seguro?",
        text: "Esta acción eliminará la categoría seleccionada.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "No, cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            const datos = new FormData();
            datos.append("accion", "eliminar_categoria");
            datos.append("id_categoria", idCategoria);

            realizarAjax("", datos, (respuesta) => {
                if (respuesta.ok) {
                    Swal.fire("Éxito", "Categoría eliminada con éxito.", "success");
                    cargarListadoCategorias();
                } else {
                    Swal.fire("Error", respuesta.mensaje || "Error al eliminar la categoría.", "error");
                }
            });
        }
    });
});

    
    $("#arranque, #envion").on("input", function () {
        const arranque = parseInt($("#arranque").val()) || 0;
        const envion = parseInt($("#envion").val()) || 0;
        $("#total").val(arranque + envion);
    });

    function actualizarTablaAtletas(atletas) {
        let tabla = $("#tablaParticipantesInscripcion tbody");
        tabla.empty();
        atletas.forEach((atleta, index) => {
            tabla.append(`
            <tr>
                <td>${index + 1}</td>
                              <td>${atleta.nombre} ${atleta.apellido}</td>
                <td>${atleta.cedula}</td>
                <td>${calcularEdad(atleta.fecha_nacimiento)}</td>
                <td>${atleta.peso} kg</td>
                <td>${atleta.altura} cm</td>
                <td>
                    <input type="checkbox" class="form-check-input" name="atleta" value="${atleta.cedula}">
                </td>
            </tr>
        `);
        });

        if ($.fn.DataTable.isDataTable("#tablaParticipantesInscripcion")) {
            $("#tablaParticipantesInscripcion").DataTable().destroy();
        }

        $("#tablaParticipantesInscripcion").DataTable({
            language: {
                lengthMenu: "Mostrar _MENU_ registros por página",
                zeroRecords: "No se encontraron resultados",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay registros disponibles",
                infoFiltered: "(filtrado de _MAX_ registros totales)",
                search: "Buscar:",
                paginate: {
                    first: "Primero",
                    last: "Último",
                    next: "Siguiente",
                    previous: "Anterior"
                }
            },
            autoWidth: true,
            order: [[0, "asc"]],
            dom: '<"top"f>rt<"bottom"lp><"clear">'
        });
    }
   
$('#modalInscribirEvento').on('show.bs.modal', function (event) {
    const button = $(event.relatedTarget);  
    const idCompetencia = button.data('id'); 
    const idCategoria = button.data('id-categoria');  
    const idSub = button.data('id-sub');  

    console.log("Datos del modal: ", { idCompetencia, idCategoria, idSub });
 
    if (!idCompetencia) {
        Swal.fire("Error", "Faltan datos del evento. No se puede continuar.", "error");
        return;
    }
 
    $(this).data("id-competencia", idCompetencia);  
    $("#formInscribirAtletas").data("id-competencia", idCompetencia);  
 
    cargarAtletasDisponiblesParaInscripcion(idCompetencia, idCategoria, idSub);
});

    
    function calcularEdad(fechaNacimiento) {
        const hoy = new Date();
        const nacimiento = new Date(fechaNacimiento);
        let edad = hoy.getFullYear() - nacimiento.getFullYear();
        const mes = hoy.getMonth() - nacimiento.getMonth();
        if (mes < 0 || (mes === 0 && hoy.getDate() < nacimiento.getDate())) {
            edad--;
        }
        return edad;
    }

    
    $("#fRegistrarEvento").on("submit", function (e) {
        e.preventDefault();
    
        const datos = new FormData(this);
        datos.append("accion", "incluir_evento");
    
        realizarAjax("", datos, (result) => {
            if (result.ok) {
                Swal.fire("Éxito", "Evento registrado con éxito", "success");
                $("#modalRegistrarEvento").modal("hide");
                cargarEventos(); 
            } else {
                Swal.fire("Error", result.mensaje || "Error al registrar el evento", "error");
            }
        });
    });
    $('#formModificarCompetencia').on('submit', function (e) {
        e.preventDefault();  
    
        const datos = new FormData(this);  
        datos.append("accion", "modificar_competencia");  
    
        realizarAjax("", datos, (result) => {
            if (result.ok) {
                Swal.fire("Éxito", "El evento se modificó correctamente.", "success");
                $('#modalModificarCompetencia').modal('hide');  
                cargarEventos();  
            } else {
                Swal.fire("Error", result.mensaje || "Error al modificar el evento.", "error");
            }
        });
    });
    
    $('#modalRegistrarEvento').on('show.bs.modal', function () {
        cargarListadoCategorias();
        cargarListadoSubs();
        cargarListadoTipos();
    });

    $('#modalRegistrarCategoria').on('show.bs.modal', function () {
        cargarListadoCategorias();
    });

    $('#modalRegistrarSubs').on('show.bs.modal', function () {
        cargarListadoSubs();
    });

    $('#modalRegistrarTipo').on('show.bs.modal', function () {
        cargarListadoTipos();
    });
    
    $('#modalModificarCompetencia').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);  
        const idCompetencia = button.data('id'); 
    
        const datos = new FormData();
        datos.append("accion", "obtener_competencia"); 
        datos.append("id_competencia", idCompetencia);  
     
        realizarAjax("", datos, (result) => {
            const competencia = result.respuesta;
     
            $('#id_competencia_modificar').val(competencia.id_competencia);
            $('#nombre_modificar').val(competencia.nombre);
            $('#ubicacion_modificar').val(competencia.lugar_competencia);
            $('#fecha_inicio_modificar').val(competencia.fecha_inicio);
            $('#fecha_fin_modificar').val(competencia.fecha_fin);
            $('#categoria_modificar').val(competencia.categoria).change();
            $('#subs_modificar').val(competencia.subs).change();
            $('#tipo_modificar').val(competencia.tipo_competicion).change();
        });
    });
    $("#formRegistrarTipo").on("submit", function (e) {
        e.preventDefault();
        const nombre = $("#in_tipo_nombre").val().trim();
    
        if (!nombre) {
            Swal.fire("Error", "El nombre del tipo no puede estar vacío.", "error");
            return;
        }
    
        const datos = new FormData(this);
        datos.append("accion", "incluir_tipo");
    
        realizarAjax("", datos, (result) => {
            if (result.ok) {
                Swal.fire("Éxito", "Tipo registrado con éxito", "success");
                $("#in_tipo_nombre").val(""); 
                cargarListadoTipos();  
                $("#modalRegistrarTipo").modal("hide");
            } else {
                Swal.fire("Error", result.mensaje || "No se pudo registrar el tipo", "error");
            }
        });
    });
     
    $("#btnConsultarTipos").on("click", function () {
        cargarListadoTipos2();  
        $("#contenedorTablaTipos").show();  
    });
 
    function cargarListadoTipos2() {
        const datos = new FormData();
        datos.append("accion", "listado_tipo");

        realizarAjax("", datos, (result) => {
            if (result.ok) {
                actualizarTablaTipos(result.respuesta);  
            } else {
                Swal.fire("Error", result.mensaje || "Error al consultar los tipos", "error");
            }
        });
    }
    function cargarListadoTipos() {
        const datos = new FormData();
        datos.append("accion", "listado_tipo");
        realizarAjax("", datos, (result) => {
            actualizarListadoTipos(result.respuesta);
        });
    }
    function actualizarTablaTipos(tipos) {
        const tbody = $("#tablaTipos tbody");
        tbody.empty();  
    
        tipos.forEach((tipo, index) => {
            tbody.append(`
                <tr>
                    <td>${index + 1}</td>
                    <td>${tipo.nombre}</td>
                    <td>
                        <button class="btn btn-warning btn-sm btnEditarTipo" 
                                data-id="${tipo.id_tipo_competencia}" 
                                data-nombre="${tipo.nombre}">
                            Editar
                        </button>
                        <button class="btn btn-danger btn-sm btnEliminarTipo" 
                                data-id="${tipo.id_tipo_competencia}">
                            Eliminar
                        </button>
                    </td>
                </tr>
            `);
        });
    
        if (tipos.length === 0) {
            tbody.append("<tr><td colspan='3'>No hay tipos registrados.</td></tr>");
        }
    }
    
    $(document).on("click", ".btnEliminarTipo", function () {
        const idTipo = $(this).data("id");
    
        Swal.fire({
            title: "¿Estás seguro?",
            text: "Esta acción eliminará el tipo seleccionado.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "No, cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                const datos = new FormData();
                datos.append("accion", "eliminar_tipo");
                datos.append("id_tipo", idTipo);
    
                realizarAjax("", datos, (result) => {
                    if (result.ok) {
                        Swal.fire("Éxito", "Tipo eliminado con éxito", "success");
                        cargarListadoTipos();  
                    } else {
                        Swal.fire("Error", result.mensaje || "Error al eliminar el tipo", "error");
                    }
                });
            }
        });
    });
    

    $(document).on("click", ".btnEditarTipo", function () {
        const idTipo = $(this).data("id");
        const nombreTipo = $(this).data("nombre");
    
        Swal.fire({
            title: "Editar Tipo",
            input: "text",
            inputValue: nombreTipo,
            showCancelButton: true,
            confirmButtonText: "Guardar",
            cancelButtonText: "Cancelar",
            preConfirm: (nuevoNombre) => {
                if (!nuevoNombre) {
                    Swal.showValidationMessage("El nombre no puede estar vacío.");
                }
                return nuevoNombre;
            },
        }).then((result) => {
            if (result.isConfirmed) {
                const datos = new FormData();
                datos.append("accion", "modificar_tipo");
                datos.append("id_tipo", idTipo);
                datos.append("nombre", result.value);
    
                realizarAjax("", datos, (result) => {
                    if (result.ok) {
                        Swal.fire("Éxito", "Tipo modificado con éxito", "success");
                        cargarListadoTipos(); 
                    } else {
                        Swal.fire("Error", result.mensaje || "Error al modificar el tipo", "error");
                    }
                });
            }
        });
    });
    
    $("#btnRegresar").on("click", function () {
        $("#modalRegistrarTipo").modal("hide");  
        $("#modalRegistrarEvento").modal("show");  
    });
    $("#btnRegresarSubs").on("click", function () {
        $("#modalRegistrarSubs").modal("hide");  
        $("#modalRegistrarEvento").modal("show");  
    });
    $('#modalRegistrarCategoria').on('show.bs.modal', function () {
        cargarListadoCategorias();
    });
    $("#btnConsultarCategorias").on("click", function () {
        cargarListadoCategorias2();
    });
    
    cargarEventos();
    cargarListadoCategorias();
    cargarListadoSubs();
    cargarListadoTipos();
    cargarEventosAnteriores();
});
