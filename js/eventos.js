$(document).ready(function () {
    function cargarEventos() {
        console.log("Recargando eventos...");
        const datos = new FormData();
        datos.append("accion", "listado_eventos");
        $.ajax({
            url: "",
            type: "POST",
            contentType: false,
            data: datos,
            processData: false,
            cache: false,
            success: function (respuesta) {
                try {
                    const result = JSON.parse(respuesta);
                    if (result.ok && result.respuesta.length > 0) {
                        actualizarListadoEventos(result.respuesta);
                    } else {
                        console.log("No hay eventos disponibles.");
                    }
                } catch (error) {
                    console.error("Error al procesar la respuesta de eventos:", error);
                }
            },
            error: function () {
                console.error("Error en la solicitud AJAX para cargar eventos.");
            }
        });
    }
    

    function actualizarListadoEventos(eventos) {
        let listadoEventos = "";
        eventos.forEach(evento => {
            listadoEventos += `
                <div class="col-md-3 mb-4 d-flex align-items-stretch">
                    <div class="card border-primary position-relative">
                        <!-- Icono de eliminar -->
                        <button class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 eliminarEvento" data-id="${evento.id_competencia}" title="Eliminar">
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
                                        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalVerEventoActivo" data-id="${evento.id_competencia}" data-nombre="${evento.nombre}" data-inicio="${evento.fecha_inicio}" data-fin="${evento.fecha_fin}" data-ubicacion="${evento.lugar_competencia}">Ver</button>
                                        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalInscribirEvento" data-id="${evento.id_competencia}">Inscribir</button>
                                        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalModificarCompetencia" data-id="${evento.id_competencia}">Modificar</button>
                                        <button class="cerrarC btn btn-outline-danger btn-sm" data-id="${evento.id_competencia}">Cerrar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
     
        $("#lista-eventos").html(""); 
        $("#lista-eventos").html(listadoEventos);
    }
    
    



    cargarEventos();

    $("#fRegistrarEvento").on("submit", function (e) {
        e.preventDefault();
        const datos = new FormData(this);
        datos.append("accion", "incluir_evento");

        $.ajax({
            url: "",
            type: "POST",
            contentType: false,
            data: datos,
            processData: false,
            cache: false,
            success: function (respuesta) {
                try {
                    const result = JSON.parse(respuesta);
                    if (result.ok) {
                        Swal.fire("Éxito", "Evento registrado con éxito", "success");
                        cargarEventos();
                        $('#modalRegistrarEvento').modal('hide');
                    } else {
                        Swal.fire("Error", result.mensaje, "error");
                    }
                } catch (error) {
                    Swal.fire("Error", "Algo salió mal al registrar el evento", "error");
                }
            },
            error: function () {
                Swal.fire("Error", "Error en la solicitud AJAX para registrar evento", "error");
            }
        });
    });
    $('#modalEventoConsultaAnterior').on('shown.bs.modal', function () {
        cargarEventosAnteriores();
    });
    $("#registrarCategoria").on("submit", function (e) {
        e.preventDefault();
    
        const nombre = $('#in_categoria_nombre').val().trim();
        const pesoMinimo = $('#in_peso_minimo').val().trim();
        const pesoMaximo = $('#in_peso_maximo').val().trim();
    
        if (!nombre || nombre.length < 2) {
            alert("El nombre de la categoría debe tener al menos 2 caracteres.");
            $('#in_categoria_nombre').addClass('is-invalid');
            return;
        } else {
            $('#in_categoria_nombre').removeClass('is-invalid').addClass('is-valid');
        }
    
        if (!pesoMinimo || isNaN(pesoMinimo) || parseFloat(pesoMinimo) < 0) {
            alert("El peso mínimo debe ser un número válido y mayor o igual a 0.");
            $('#in_peso_minimo').addClass('is-invalid');
            return;
        } else {
            $('#in_peso_minimo').removeClass('is-invalid').addClass('is-valid');
        }
    
        if (!pesoMaximo || isNaN(pesoMaximo) || parseFloat(pesoMaximo) <= parseFloat(pesoMinimo)) {
            alert("El peso máximo debe ser un número válido y mayor que el peso mínimo.");
            $('#in_peso_maximo').addClass('is-invalid');
            return;
        } else {
            $('#in_peso_maximo').removeClass('is-invalid').addClass('is-valid');
        }
    
        const datos = new FormData(this);
        datos.append("accion", "incluir_categoria");
    
        $.ajax({
            url: "",  
            type: "POST",
            contentType: false,
            data: datos,
            processData: false,
            cache: false,
            success: function (respuesta) {
                try {
                    const result = JSON.parse(respuesta);
                    if (result.ok) {
                        Swal.fire("Éxito", "Categoría registrada con éxito", "success");
                        $('#modalRegistrarCategoria').modal('hide');
                    } else {
                        Swal.fire("Error", result.mensaje, "error");
                    }
                } catch (error) {
                    Swal.fire("Error", "Algo salió mal al registrar la categoría", "error");
                }
            },
            error: function () {
                Swal.fire("Error", "Error en la solicitud AJAX para registrar categoría", "error");
            }
        });
    });
    
    $("#registrarSubs").on("submit", function (e) {
        e.preventDefault();
    
        const nombre = $('#in_subs_nombre').val().trim();
        const edadMinima = $('#in_edad_minima').val().trim();
        const edadMaxima = $('#in_edad_maxima').val().trim();
    
        if (!nombre || nombre.length < 2) {
            alert("El nombre de los subs debe tener al menos 2 caracteres.");
            $('#in_subs_nombre').addClass('is-invalid');
            return;
        } else {
            $('#in_subs_nombre').removeClass('is-invalid').addClass('is-valid');
        }
    
        if (!edadMinima || isNaN(edadMinima) || parseInt(edadMinima) < 0) {
            alert("La edad mínima debe ser un número válido y mayor o igual a 0.");
            $('#in_edad_minima').addClass('is-invalid');
            return;
        } else {
            $('#in_edad_minima').removeClass('is-invalid').addClass('is-valid');
        }
    
        if (!edadMaxima || isNaN(edadMaxima) || parseInt(edadMaxima) <= parseInt(edadMinima)) {
            alert("La edad máxima debe ser un número válido y mayor que la edad mínima.");
            $('#in_edad_maxima').addClass('is-invalid');
            return;
        } else {
            $('#in_edad_maxima').removeClass('is-invalid').addClass('is-valid');
        }
    
        const datos = new FormData(this);
        datos.append("accion", "incluir_subs");
    
        $.ajax({
            url: "", 
            type: "POST",
            contentType: false,
            data: datos,
            processData: false,
            cache: false,
            success: function (respuesta) {
                try {
                    const result = JSON.parse(respuesta);
                    if (result.ok) {
                        Swal.fire("Éxito", "Subs registrado con éxito", "success");
                        $('#modalRegistrarSubs').modal('hide');
                    } else {
                        Swal.fire("Error", result.mensaje, "error");
                    }
                } catch (error) {
                    Swal.fire("Error", "Algo salió mal al registrar los subs", "error");
                }
            },
            error: function () {
                Swal.fire("Error", "Error en la solicitud AJAX para registrar subs", "error");
            }
        });
    });
    
    $("#registrarTipo").on("submit", function (e) {
        e.preventDefault();
    
        const nombre = $('#in_tipo_nombre').val().trim();
    
        if (!nombre || nombre.length < 2) {
            alert("El nombre del tipo debe tener al menos 2 caracteres.");
            $('#in_tipo_nombre').addClass('is-invalid');
            return;
        } else {
            $('#in_tipo_nombre').removeClass('is-invalid').addClass('is-valid');
        }
    
        const datos = new FormData(this);
        datos.append("accion", "incluir_tipo");
    
        $.ajax({
            url: "", 
            type: "POST",
            contentType: false,
            data: datos,
            processData: false,
            cache: false,
            success: function (respuesta) {
                try {
                    const result = JSON.parse(respuesta);
                    if (result.ok) {
                        Swal.fire("Éxito", "Tipo registrado con éxito", "success");
                        $('#modalRegistrarTipo').modal('hide');
                    } else {
                        Swal.fire("Error", result.mensaje, "error");
                    }
                } catch (error) {
                    Swal.fire("Error", "Algo salió mal al registrar el tipo", "error");
                }
            },
            error: function () {
                Swal.fire("Error", "Error en la solicitud AJAX para registrar tipo", "error");
            }
        });
    });
    

    function cargarListadoCategorias() {
        const datos = new FormData();
        datos.append("accion", "listado_categoria");

        $.ajax({
            url: "",
            type: "POST",
            contentType: false,
            data: datos,
            processData: false,
            cache: false,
            success: function (respuesta) {
                try {
                    const result = JSON.parse(respuesta);
                    if (result.ok) {
                        actualizarListadoCategorias(result.respuesta);
                    }
                } catch (error) {
                    console.error("Error al procesar la respuesta de categorías:", error);
                }
            },
            error: function () {
                console.error("Error en la solicitud AJAX para cargar categorías.");
            }
        });
    }

    function actualizarListadoCategorias(categorias) {
        let opciones = "<option value='' selected>Seleccione una</option>";
        categorias.forEach(categoria => {
            opciones += `<option value="${categoria.id_categoria}">${categoria.nombre}</option>`;
        });
        $("#categoria_modificar").html(opciones);
    }
    

    function cargarListadoSubs() {
        const datos = new FormData();
        datos.append("accion", "listado_subs");

        $.ajax({
            url: "",
            type: "POST",
            contentType: false,
            data: datos,
            processData: false,
            cache: false,
            success: function (respuesta) {
                try {
                    const result = JSON.parse(respuesta);
                    if (result.ok) {
                        actualizarListadoSubs(result.respuesta);
                    }
                } catch (error) {
                    console.error("Error al procesar la respuesta de subs:", error);
                }
            },
            error: function () {
                console.error("Error en la solicitud AJAX para cargar subs.");
            }
        });
    }

    function actualizarListadoSubs(subs) {
        let opciones = "<option selected>Seleccione una</option>";
        subs.forEach(sub => {
            opciones += `<option value="${sub.id_sub}">${sub.nombre}</option>`;
        });
        $("#in_subs").html(opciones);
    }

    function cargarListadoTipos() {
        const datos = new FormData();
        datos.append("accion", "listado_tipo");

        $.ajax({
            url: "",
            type: "POST",
            contentType: false,
            data: datos,
            processData: false,
            cache: false,
            success: function (respuesta) {
                try {
                    const result = JSON.parse(respuesta);
                    if (result.ok) {
                        actualizarListadoTipos(result.respuesta);
                    }
                } catch (error) {
                    console.error("Error al procesar la respuesta de tipos:", error);
                }
            },
            error: function () {
                console.error("Error en la solicitud AJAX para cargar tipos.");
            }
        });
    }

    function actualizarListadoTipos(tipos) {
        let opciones = "<option selected>Seleccione una</option>";
        tipos.forEach(tipo => {
            opciones += `<option value="${tipo.id_tipo_competencia}">${tipo.nombre}</option>`;
        });
        $("#in_tipo").html(opciones);
    }

    cargarListadoCategorias();
    cargarListadoSubs();
    cargarListadoTipos();

    $('#modalModificarCompetencia').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);
        const idCompetencia = button.data('id');
    
        const datos = new FormData();
        datos.append("accion", "obtener_competencia");
        datos.append("id_competencia", idCompetencia);
    
        $.ajax({
            url: "", 
            type: "POST",
            contentType: false,
            data: datos,
            processData: false,
            cache: false,
            success: function (respuesta) {
                try {
                    const result = JSON.parse(respuesta);
                    if (result.ok && result.respuesta) {
                        const competencia = result.respuesta;
                        $('#id_competencia_modificar').val(competencia.id_competencia);
                        $('#nombre_modificar').val(competencia.nombre);
                        $('#ubicacion_modificar').val(competencia.lugar_competencia);
                        $('#fecha_inicio_modificar').val(competencia.fecha_inicio);
                        $('#fecha_fin_modificar').val(competencia.fecha_fin);
    
                        $('#categoria_modificar').val(competencia.categoria).change();
                        $('#subs_modificar').val(competencia.subs).change();
                        $('#tipo_modificar').val(competencia.tipo_competencia).change();
    
                        cargarDatosModificar(); 
                    } else {
                        Swal.fire("Error", "No se pudieron cargar los datos de la competencia", "error");
                    }
                } catch (error) {
                    Swal.fire("Error", "Error al procesar la respuesta", "error");
                }
            },
            error: function () {
                Swal.fire("Error", "Error en la solicitud AJAX para obtener la competencia", "error");
            }
        });
    });
    

    $('#formModificarCompetencia').on('submit', function (e) {
        e.preventDefault();
    
      
        let esValido = true;
     
        const nombre = $('#nombre_modificar').val().trim();
        if (nombre === "") {
            esValido = false;
            $('#nombre_modificar').addClass('is-invalid');
            $('#nombre_modificar').next('.invalid-feedback').text('El nombre es obligatorio').show();
        } else {
            $('#nombre_modificar').removeClass('is-invalid').addClass('is-valid');
            $('#nombre_modificar').next('.invalid-feedback').hide();
        }
    
        const ubicacion = $('#ubicacion_modificar').val().trim();
        if (ubicacion === "") {
            esValido = false;
            $('#ubicacion_modificar').addClass('is-invalid');
            $('#ubicacion_modificar').next('.invalid-feedback').text('La ubicación es obligatoria').show();
        } else {
            $('#ubicacion_modificar').removeClass('is-invalid').addClass('is-valid');
            $('#ubicacion_modificar').next('.invalid-feedback').hide();
        }
    
        const fechaInicio = $('#fecha_inicio_modificar').val().trim();
        const fechaFin = $('#fecha_fin_modificar').val().trim();
        if (fechaInicio === "") {
            esValido = false;
            $('#fecha_inicio_modificar').addClass('is-invalid');
            $('#fecha_inicio_modificar').next('.invalid-feedback').text('La fecha de inicio es obligatoria').show();
        } else {
            $('#fecha_inicio_modificar').removeClass('is-invalid').addClass('is-valid');
            $('#fecha_inicio_modificar').next('.invalid-feedback').hide();
        }
    
        if (fechaFin === "") {
            esValido = false;
            $('#fecha_fin_modificar').addClass('is-invalid');
            $('#fecha_fin_modificar').next('.invalid-feedback').text('La fecha de fin es obligatoria').show();
        } else {
            $('#fecha_fin_modificar').removeClass('is-invalid').addClass('is-valid');
            $('#fecha_fin_modificar').next('.invalid-feedback').hide();
        }
    
        const categoria = $('#categoria_modificar').val();
        const subs = $('#subs_modificar').val();
        const tipo = $('#tipo_modificar').val();
        if (!categoria || categoria === "Seleccione una") {
            esValido = false;
            $('#categoria_modificar').addClass('is-invalid');
            $('#categoria_modificar').next('.invalid-feedback').text('La categoría es obligatoria').show();
        } else {
            $('#categoria_modificar').removeClass('is-invalid').addClass('is-valid');
            $('#categoria_modificar').next('.invalid-feedback').hide();
        }
    
        if (!subs || subs === "Seleccione una") {
            esValido = false;
            $('#subs_modificar').addClass('is-invalid');
            $('#subs_modificar').next('.invalid-feedback').text('El campo Subs es obligatorio').show();
        } else {
            $('#subs_modificar').removeClass('is-invalid').addClass('is-valid');
            $('#subs_modificar').next('.invalid-feedback').hide();
        }
    
        if (!tipo || tipo === "Seleccione una") {
            esValido = false;
            $('#tipo_modificar').addClass('is-invalid');
            $('#tipo_modificar').next('.invalid-feedback').text('El tipo de competencia es obligatorio').show();
        } else {
            $('#tipo_modificar').removeClass('is-invalid').addClass('is-valid');
            $('#tipo_modificar').next('.invalid-feedback').hide();
        }
    
        if (!esValido) {
            return; 
        }
     
        const datos = new FormData(this);
        datos.append("accion", "modificar_competencia");
    
        $.ajax({
            url: "",
            type: "POST",
            contentType: false,
            data: datos,
            processData: false,
            cache: false,
            success: function (respuesta) {
                try {
                    const result = JSON.parse(respuesta);
                    if (result.ok) {
                        Swal.fire("Éxito", "Competencia modificada con éxito", "success");
                        $('#modalModificarCompetencia').modal('hide');
                        cargarEventos();  
                    } else {
                        Swal.fire("Error", result.mensaje, "error");
                    }
                } catch (error) {
                    Swal.fire("Error", "Error al procesar la respuesta del servidor", "error");
                }
            },
            error: function () {
                Swal.fire("Error", "Error en la solicitud AJAX para modificar la competencia", "error");
            }
        });
    });
    
 
    function validarFormularioModificarCompetencia() {
        let esValido = true;
 
        const nombre = $('#nombre_modificar').val().trim();
        const ubicacion = $('#ubicacion_modificar').val().trim();
        const fechaInicio = $('#fecha_inicio_modificar').val().trim();
        const fechaFin = $('#fecha_fin_modificar').val().trim();
        const categoria = $('#categoria_modificar').val();
        const subs = $('#subs_modificar').val();
        const tipo = $('#tipo_modificar').val();
 
        if (nombre === "") {
            esValido = false;
            $('#nombre_modificar').addClass('is-invalid');
            $('#nombre_modificar').next('.invalid-feedback').text('El nombre es obligatorio').show();
        } else {
            $('#nombre_modificar').removeClass('is-invalid').addClass('is-valid');
            $('#nombre_modificar').next('.invalid-feedback').hide();
        }
 
        if (ubicacion === "") {
            esValido = false;
            $('#ubicacion_modificar').addClass('is-invalid');
            $('#ubicacion_modificar').next('.invalid-feedback').text('La ubicación es obligatoria').show();
        } else {
            $('#ubicacion_modificar').removeClass('is-invalid').addClass('is-valid');
            $('#ubicacion_modificar').next('.invalid-feedback').hide();
        }
 
        if (fechaInicio === "") {
            esValido = false;
            $('#fecha_inicio_modificar').addClass('is-invalid');
            $('#fecha_inicio_modificar').next('.invalid-feedback').text('La fecha de inicio es obligatoria').show();
        } else {
            $('#fecha_inicio_modificar').removeClass('is-invalid').addClass('is-valid');
            $('#fecha_inicio_modificar').next('.invalid-feedback').hide();
        }
 
        if (fechaFin === "") {
            esValido = false;
            $('#fecha_fin_modificar').addClass('is-invalid');
            $('#fecha_fin_modificar').next('.invalid-feedback').text('La fecha de fin es obligatoria').show();
        } else {
            $('#fecha_fin_modificar').removeClass('is-invalid').addClass('is-valid');
            $('#fecha_fin_modificar').next('.invalid-feedback').hide();
        }
 
        if (!categoria || categoria === "Seleccione una") {
            esValido = false;
            $('#categoria_modificar').addClass('is-invalid');
            $('#categoria_modificar').next('.invalid-feedback').text('La categoría es obligatoria').show();
        } else {
            $('#categoria_modificar').removeClass('is-invalid').addClass('is-valid');
            $('#categoria_modificar').next('.invalid-feedback').hide();
        }

  
        if (!subs || subs === "Seleccione una") {
            esValido = false;
            $('#subs_modificar').addClass('is-invalid');
            $('#subs_modificar').next('.invalid-feedback').text('El campo Subs es obligatorio').show();
        } else {
            $('#subs_modificar').removeClass('is-invalid').addClass('is-valid');
            $('#subs_modificar').next('.invalid-feedback').hide();
        }

      
        if (!tipo || tipo === "Seleccione una") {
            esValido = false;
            $('#tipo_modificar').addClass('is-invalid');
            $('#tipo_modificar').next('.invalid-feedback').text('El tipo de competencia es obligatorio').show();
        } else {
            $('#tipo_modificar').removeClass('is-invalid').addClass('is-valid');
            $('#tipo_modificar').next('.invalid-feedback').hide();
        }

        return esValido;
    }

    $(document).on('click', '.eliminarEvento', function() {
        const idCompetencia = $(this).data('id');
    
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
    
                $.ajax({
                    url: "", 
                    type: "POST",
                    contentType: false,
                    data: datos,
                    processData: false,
                    cache: false,
                    success: function (respuesta) {
                        try {
                            const result = JSON.parse(respuesta);
                            if (result.ok) { 
                                $(`button[data-id="${idCompetencia}"]`).closest('.col-md-3').remove();
                                Swal.fire("Éxito", "Evento eliminado con éxito", "success");
                            } else {
                                Swal.fire("Error", result.mensaje, "error");
                            }
                        } catch (error) {
                            Swal.fire("Error", "Error al procesar la respuesta", "error");
                        }
                    },
                    error: function () {
                        Swal.fire("Error", "Error en la solicitud AJAX", "error");
                    }
                });
            }
        });
    });
    
    $('#modalModificarCompetencia').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);  
        const idCompetencia = button.data('id'); 

        if (!idCompetencia) {
            console.error("No se pudo obtener el ID de la competencia.");
            return;
        }

        const datos = new FormData();
        datos.append("accion", "obtener_competencia");
        datos.append("id_competencia", idCompetencia);

     
        $.ajax({
            url: "",  
            type: "POST",
            contentType: false,
            data: datos,
            processData: false,
            cache: false,
            success: function (respuesta) {
                try {
                    const result = JSON.parse(respuesta);
                    if (result.ok) {
                        const competencia = result.respuesta;
                        $('#id_competencia_modificar').val(competencia.id_competencia);
                        $('#nombre_modificar').val(competencia.nombre);
                        $('#ubicacion_modificar').val(competencia.lugar_competencia);
                        $('#fecha_inicio_modificar').val(competencia.fecha_inicio);
                        $('#fecha_fin_modificar').val(competencia.fecha_fin);
                        $('#categoria_modificar').val(competencia.categoria).change();
                        $('#subs_modificar').val(competencia.subs).change();
                        $('#tipo_modificar').val(competencia.tipo_competencia).change();                        
                      

                    } else {
                        Swal.fire("Error", "No se pudieron cargar los datos de la competencia", "error");
                    }
                } catch (error) {
                    Swal.fire("Error", "Error al procesar la respuesta", "error");
                }
            },
            error: function () {
                Swal.fire("Error", "Error en la solicitud AJAX para obtener la competencia", "error");
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


    function validarKeyPress(e, regex) {
        if (!regex.test(e.key)) {
            e.preventDefault();
        }
    }

    function validarKeyUp(regex, input, mensaje, textoError) {
        const isValid = regex.test(input.val());
        input.toggleClass("is-invalid", !isValid).toggleClass("is-valid", isValid);
        mensaje.text(isValid ? "" : textoError);
        return isValid;
    }

    function verificarFecha(fechaInput, mensaje) {
        const fecha = fechaInput.val();
        const hoy = new Date();
        const fechaInicio = new Date(fecha);
        const isValid = fecha && fechaInicio >= hoy;

        fechaInput.toggleClass("is-invalid", !isValid).toggleClass("is-valid", isValid);
        mensaje.text(isValid ? "" : (fecha ? "La fecha debe ser posterior al día actual" : "La fecha de inicio es obligatoria"));
        return isValid;
    }
    $("#formInscribirAtletas").on("submit", function (e) {
        e.preventDefault();
        const idCompetencia = $('#modalInscribirEvento').data('id_competencia');

        const datos = new FormData(this);
        datos.append("accion", "inscribir_atletas");
        datos.append("id_competencia", idCompetencia);

        const atletasSeleccionados = [];
        $("#tablaParticipantesInscripcion input[name='atleta']:checked").each(function () {
            atletasSeleccionados.push($(this).val());
        });

        if (atletasSeleccionados.length === 0) {
            Swal.fire("Error", "Debes seleccionar al menos un atleta", "error");
            return;
        }

        atletasSeleccionados.forEach(atleta => {
            datos.append("atleta[]", atleta);
        });

        $.ajax({
            url: "",
            type: "POST",
            contentType: false,
            data: datos,
            processData: false,
            cache: false,
            success: function (respuesta) {
                try {
                    const result = JSON.parse(respuesta);
                    if (result.ok) {
                        Swal.fire("Éxito", result.mensaje, "success");
                        $('#modalInscribirEvento').modal('hide');
                        cargarEventos();
                    } else {
                        Swal.fire("Error", result.mensaje, "error");
                    }
                } catch (error) {
                    Swal.fire("Error", "Hubo un error al procesar la respuesta", "error");
                }
            },
            error: function () {
                Swal.fire("Error", "Error en la solicitud AJAX para inscribir atletas", "error");
            }
        });
    });

    function validarEnvio(formId) {
        let esValido = true;
        const form = $(formId);

        const validaciones = [
            { regex: /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,50}$/, id: "in_nombre", errorMsg: "Solo letras y espacios (1-50 caracteres)" },
            { regex: /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,100}$/, id: "in_ubicacion", errorMsg: "Ubicación no puede estar vacía" }
        ];

        validaciones.forEach(({ regex, id, errorMsg }) => {
            esValido &= validarKeyUp(regex, form.find(`#${id}`), form.find(`#s${id}`), errorMsg);
        });

        esValido &= verificarFecha(form.find("#in_date_start"), form.find("#sfecha_inicio"));
        esValido &= verificarFecha(form.find("#in_date_end"), form.find("#sfecha_fin"));

        return esValido;
    }
    function cargarDatosModificar() {
        const datosCategoria = new FormData();
        datosCategoria.append("accion", "listado_categoria");

        const datosSubs = new FormData();
        datosSubs.append("accion", "listado_subs");

        const datosTipo = new FormData();
        datosTipo.append("accion", "listado_tipo");
 
        const solicitudes = [
            $.ajax({
                url: "",
                type: "POST",
                contentType: false,
                data: datosCategoria,
                processData: false,
                cache: false
            }),
            $.ajax({
                url: "",
                type: "POST",
                contentType: false,
                data: datosSubs,
                processData: false,
                cache: false
            }),
            $.ajax({
                url: "",
                type: "POST",
                contentType: false,
                data: datosTipo,
                processData: false,
                cache: false
            })
        ];
 
        Promise.all(solicitudes)
            .then(respuestas => {
                try {
                    const [respuestaCategorias, respuestaSubs, respuestaTipos] = respuestas;

                    const categorias = JSON.parse(respuestaCategorias);
                    const subs = JSON.parse(respuestaSubs);
                    const tipos = JSON.parse(respuestaTipos);

                    if (categorias.ok) {
                        actualizarOpcionesSelect('#categoria_modificar', categorias.respuesta, $('#categoria_modificar').data('valor-seleccionado'));
                    }
                    if (subs.ok) {
                        actualizarOpcionesSelect('#subs_modificar', subs.respuesta, $('#subs_modificar').data('valor-seleccionado'));
                    }
                    if (tipos.ok) {
                        actualizarOpcionesSelect('#tipo_modificar', tipos.respuesta, $('#tipo_modificar').data('valor-seleccionado'));
                    }
                } catch (error) {
                    console.error("Error al procesar una de las respuestas:", error);
                }
            })
            .catch(error => {
                console.error("Error en las solicitudes AJAX para cargar datos:", error);
            });
    }
 
    function actualizarOpcionesSelect(selector, opciones, valorSeleccionado) {
        let htmlOpciones = '<option value="" selected>Seleccione una</option>';
        opciones.forEach(opcion => {
            const seleccionado = opcion.id == valorSeleccionado ? 'selected' : '';
            htmlOpciones += `<option value="${opcion.id}" ${seleccionado}>${opcion.nombre}</option>`;
        });
        $(selector).html(htmlOpciones);
    }

    $(document).on('click', '.cerrarC', function() {
        var dataId = $(this).data('id');
        cerrarEvento(dataId);
    });
     
    $('#modalModificarCompetencia').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);
        const idCompetencia = button.data('id');
 
        const datos = new FormData();
        datos.append("accion", "obtener_competencia");
        datos.append("id_competencia", idCompetencia);

        $.ajax({
            url: "",
            type: "POST",
            contentType: false,
            data: datos,
            processData: false,
            cache: false,
            success: function (respuesta) {
                try {
                    const result = JSON.parse(respuesta);
                    if (result.ok) {
                        const competencia = result.respuesta;
                        $('#id_competencia_modificar').val(competencia.id_competencia);
                        $('#nombre_modificar').val(competencia.nombre);
                        $('#ubicacion_modificar').val(competencia.lugar_competencia);
                        $('#fecha_inicio_modificar').val(competencia.fecha_inicio);
                        $('#fecha_fin_modificar').val(competencia.fecha_fin);
 
                        $('#categoria_modificar').data('valor-seleccionado', competencia.categoria);
                        $('#subs_modificar').data('valor-seleccionado', competencia.subs);
                        $('#tipo_modificar').data('valor-seleccionado', competencia.tipo_competencia);

                        cargarDatosModificar();  
                    } else {
                        Swal.fire("Error", "No se pudieron cargar los datos de la competencia", "error");
                    }
                } catch (error) {
                    Swal.fire("Error", "Error al procesar la respuesta", "error");
                }
            },
            error: function () {
                Swal.fire("Error", "Error en la solicitud AJAX para obtener la competencia", "error");
            }
        });
    });

    $("input").on("keypress", function (e) {
        const id = $(this).attr("id");
        const regexMap = {
            "in_nombre": /^[a-zA-ZáéíóúÁÉÍÓÚ\s]*$/,
            "in_ubicacion": /^[a-zA-ZáéíóúÁÉÍÓÚ\s]*$/,
        };

        if (regexMap[id]) {
            validarKeyPress(e, regexMap[id]);
        }
    });

    $("input").on("keyup", function () {
        const id = $(this).attr("id");
        const formId = $(this).closest("form").attr("id");
        const regexMap = {
            "in_nombre": /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,50}$/,
            "in_ubicacion": /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,100}$/,
        };

        if (regexMap[id]) {
            validarKeyUp(regexMap[id], $(this), $(`#s${id}`), $(`#${id}_error`).text());
        }
    });

    $("#in_date_start, #in_date_end").on("change", function () {
        const form = $(this).closest("form");
        verificarFecha($(this), form.find(`#s${this.id}`));
    });

    $('#modalInscribirEvento').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);
        const idCompetencia = button.data('id');
        $('#modalInscribirEvento').data('id_competencia', idCompetencia);

        const datos = new FormData();
        datos.append("accion", "listado_atletas_disponibles");
        datos.append("id_competencia", idCompetencia);

        $.ajax({
            url: "",
            type: "POST",
            contentType: false,
            data: datos,
            processData: false,
            cache: false,
            success: function (respuesta) {
                try {
                    const result = JSON.parse(respuesta);
                    if (result.ok) {
                        actualizarTablaAtletas(result.respuesta);
                    } else { 
                        Swal.fire("Error", "No se pudieron cargar los atletas disponibles", "error");
                    }
                } catch (error) {
                    Swal.fire("Error", "Algo salió mal al cargar los atletas disponibles", "error");
                }
            },
            error: function () {
                Swal.fire("Error", "Error en la solicitud AJAX para cargar atletas disponibles", "error");
            }
        });
    });

    $('#modalRegistrarResultados').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);
        const idCompetencia = button.data('id-competencia');
        const idAtleta = button.data('id-atleta');
        const nombreAtleta = button.closest('tr').find('td:eq(1)').text();
        const cedulaAtleta = button.closest('tr').find('td:eq(2)').text();

        $('#nombreAtletaResultados').text(nombreAtleta);
        $('#cedulaAtletaResultados').text(cedulaAtleta);

        $('#formRegistrarResultados').data('id-competencia', idCompetencia);
        $('#formRegistrarResultados').data('id-atleta', idAtleta);
    });



    function cargarAtletasDisponibles(id_competencia) {
        const datos = new FormData();
        datos.append("accion", "listado_atletas_disponibles");
        datos.append("id_competencia", id_competencia);

        $.ajax({
            url: "",
            type: "POST",
            contentType: false,
            data: datos,
            processData: false,
            cache: false,
            success: function (respuesta) {
                try {
                    const result = JSON.parse(respuesta);
                    if (result.ok) {
                        actualizarTablaAtletas(result.respuesta);
                    } else {
                        Swal.fire("Error", "No se pudieron cargar los atletas disponibles", "error");
                    }
                } catch (error) {
                    Swal.fire("Error", "Algo salió mal al cargar los atletas disponibles", "error");
                }
            },
            error: function () {
                Swal.fire("Error", "Error en la solicitud AJAX para cargar atletas disponibles", "error");
            }
        });
    }
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
    
                $.ajax({
                    url: "",  
                    type: "POST",
                    contentType: false,
                    data: datos,
                    processData: false,
                    cache: false,
                    success: function (respuesta) {
                        try {
                            const result = JSON.parse(respuesta);
                            console.log("Respuesta de cerrar evento:", result);  
                            if (result.ok) {
                              
                                $(`div[data-id="${idCompetencia}"]`).remove();  
                                cargarEventos();  
                            } else {
                                Swal.fire("Error", result.mensaje, "error");
                            }
                        } catch (error) {
                            Swal.fire("Error", "Error al procesar la respuesta", "error");
                        }
                    },
                    error: function () {
                        Swal.fire("Error", "Error en la solicitud AJAX", "error");
                    }
                });
            }
        });
    }
    
    

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
                },
            },
            autoWidth: true,
            order: [[0, "asc"]],
            dom: '<"top"f>rt<"bottom"lp><"clear">',
        });
    }

    $("#registrarEvento").on("submit", function (e) {
        e.preventDefault();
        const datos = new FormData(this);
        datos.append("accion", "inscribir_atletas");

        $.ajax({
            url: "",
            type: "POST",
            contentType: false,
            data: datos,
            processData: false,
            cache: false,
            success: function (respuesta) {
                try {
                    const result = JSON.parse(respuesta);
                    if (result.ok) {
                        Swal.fire("Éxito", "Atletas inscritos con éxito", "success");
                        $('#modalInscribirEvento').modal('hide');
                        cargarEventos();
                    } else {
                        Swal.fire("Error", result.mensaje, "error");
                    }
                } catch (error) {
                    Swal.fire("Error", "Algo salió mal al inscribir los atletas", "error");
                }
            },
            error: function () {
                Swal.fire("Error", "Error en la solicitud AJAX para inscribir atletas", "error");
            }
        });
    });
    function cargarEventosAnteriores() {
        const datos = new FormData();
        datos.append("accion", "listado_eventos_anteriores");

        $.ajax({
            url: "",
            type: "POST",
            contentType: false,
            data: datos,
            processData: false,
            cache: false,
            success: function (respuesta) {
                try {
                    const result = JSON.parse(respuesta);
                    if (result.ok && result.respuesta.length > 0) {
                        actualizarListadoEventosAnteriores(result.respuesta);
                    } else {
                        console.log("No hay eventos anteriores.");

                    }
                } catch (error) {
                    console.error("Error al procesar la respuesta de eventos anteriores:", error);
                }
            },
            error: function () {
                console.error("Error en la solicitud AJAX para cargar eventos anteriores.");
            }
        });
    }

    function actualizarListadoEventosAnteriores(eventos) {
        let listadoEventos = "";
        eventos.forEach(evento => {
            listadoEventos += `
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
            </tr>
        `;
        });
    
        $("#tablaEventosAnteriores tbody").html(listadoEventos);
    }
    
    $('#modalVerEventoActivo').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);
        const idCompetencia = button.data('id');

        const datos = new FormData();
        datos.append("accion", "listado_atletas_inscritos");
        datos.append("id_competencia", idCompetencia);

        $.ajax({
            url: "",
            type: "POST",
            contentType: false,
            data: datos,
            processData: false,
            cache: false,
            success: function (respuesta) {
                try {
                    const result = JSON.parse(respuesta);
                    if (result.ok && result.respuesta.length > 0) {
                        actualizarTablaInscritos(result.respuesta);
                    } else {
                        $('#tablaParticipantes tbody').html("<tr><td colspan='7'>No hay atletas inscritos</td></tr>");
                    }
                } catch (error) {
                    console.error("Error al procesar la respuesta de atletas inscritos:", error);
                }
            },
            error: function () {
                console.error("Error en la solicitud AJAX para cargar atletas inscritos.");
            }
        });
    });

    function actualizarTablaInscritos(atletas) {
        let filas = "";
        atletas.forEach((atleta, index) => {
            const resultadoRegistrado = atleta.resultado_registrado;

            filas += `
            <tr>
                <td>${index + 1}</td>
                <td>${atleta.nombre} ${atleta.apellido}</td>
                <td>${atleta.cedula}</td>
                <td>${calcularEdad(atleta.fecha_nacimiento)}</td>
                <td>${atleta.peso} kg</td>
                <td>${atleta.altura} cm</td>
                <td>
                    ${resultadoRegistrado ?
                    `<button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalModificarResultados" data-id-competencia="${atleta.id_competencia}" data-id-atleta="${atleta.cedula}">Modificar Resultados</button>` :
                    `<button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalRegistrarResultados" data-id-competencia="${atleta.id_competencia}" data-id-atleta="${atleta.cedula}">Registrar Resultados</button>`
                }
                </td>
            </tr>
        `;
        });

        $('#tablaParticipantes tbody').html(filas);
    }

    $('#modalModificarResultados').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);
        const idCompetencia = button.data('id-competencia');
        const idAtleta = button.data('id-atleta');

        const datos = new FormData();
        datos.append("accion", "obtener_resultados");
        datos.append("id_competencia", idCompetencia);
        datos.append("id_atleta", idAtleta);

        $.ajax({
            url: "",
            type: "POST",
            contentType: false,
            data: datos,
            processData: false,
            cache: false,
            success: function (respuesta) {
                try {
                    const result = JSON.parse(respuesta);
                    if (result.ok) {
                        $('#arranque_modificar').val(result.respuesta.arranque);
                        $('#envion_modificar').val(result.respuesta.envion);
                        $('#medalla_arranque_modificar').val(result.respuesta.medalla_arranque);
                        $('#medalla_envion_modificar').val(result.respuesta.medalla_envion);
                        $('#medalla_total_modificar').val(result.respuesta.medalla_total);
                        $('#total_modificar').val(result.respuesta.total);
                        $('#nombreAtletaModificarResultados').text(result.respuesta.nombre_atleta);
                        $('#cedulaAtletaModificarResultados').text(result.respuesta.cedula_atleta);
                    } else {
                        Swal.fire("Error", "No se pudieron cargar los resultados del atleta", "error");
                    }
                } catch (error) {
                    Swal.fire("Error", "Error al procesar la respuesta de resultados", "error");
                }
            },
            error: function () {
                Swal.fire("Error", "Error en la solicitud AJAX para cargar los resultados", "error");
            }
        });
    });

    $('#modalRegistrarResultados').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);
        const idCompetencia = button.data('id-competencia');
        const idAtleta = button.data('id-atleta');

        $('#formRegistrarResultados').data('id-competencia', idCompetencia);
        $('#formRegistrarResultados').data('id-atleta', idAtleta);
    });

    $('#arranque, #envion').on('input', function () {
        const arranque = parseFloat($('#arranque').val()) || 0;
        const envion = parseFloat($('#envion').val()) || 0;
        const total = arranque + envion;
        $('#total').val(total);
    });

    $('#formRegistrarResultados').on('submit', function (e) {
        e.preventDefault();

        const idCompetencia = $(this).data('id-competencia');
        const idAtleta = $(this).data('id-atleta');

        const datos = new FormData(this);
        datos.append("accion", "registrar_resultados");
        datos.append("id_competencia", idCompetencia);
        datos.append("id_atleta", idAtleta);

        $.ajax({
            url: "",
            type: "POST",
            contentType: false,
            data: datos,
            processData: false,
            cache: false,
            success: function (respuesta) {
                try {
                    const result = JSON.parse(respuesta);
                    if (result.ok) {
                        Swal.fire("Éxito", "Resultados registrados con éxito", "success");
                        $('#modalRegistrarResultados').modal('hide');
                    } else {
                        Swal.fire("Error", result.mensaje, "error");
                    }
                } catch (error) {
                    Swal.fire("Error", "Algo salió mal al registrar los resultados", "error");
                }
            },
            error: function () {
                Swal.fire("Error", "Error en la solicitud AJAX para registrar resultados", "error");
            }
        });
    });

    $('#formRegistrarResultados').on('submit', function (e) {
        e.preventDefault();
        const idCompetencia = $(this).data('id-competencia');
        const idAtleta = $(this).data('id-atleta');

        const datos = new FormData(this);
        datos.append("accion", "registrar_resultados");
        datos.append("id_competencia", idCompetencia);
        datos.append("id_atleta", idAtleta);

        $.ajax({
            url: "",
            type: "POST",
            contentType: false,
            data: datos,
            processData: false,
            cache: false,
            success: function (respuesta) {
                try {
                    const result = JSON.parse(respuesta);
                    if (result.ok) {
                        Swal.fire("Éxito", "Resultados registrados con éxito", "success");
                        $('#modalRegistrarResultados').modal('hide');
                    } else {
                        Swal.fire("Error", result.mensaje, "error");
                    }
                } catch (error) {
                    Swal.fire("Error", "Algo salió mal al registrar los resultados", "error");
                }
            },
            error: function () {
                Swal.fire("Error", "Error en la solicitud AJAX para registrar resultados", "error");
            }
        });
    });
    $(document).on('click', '.consultarEventoAnterior', function() {
        const idCompetencia = $(this).data('id');
    
        const datos = new FormData();
        datos.append("accion", "obtener_competencia");
        datos.append("id_competencia", idCompetencia);
    
        $.ajax({
            url: "",
            type: "POST",
            contentType: false,
            data: datos,
            processData: false,
            cache: false,
            success: function (respuesta) {
                try {
                    const result = JSON.parse(respuesta);
                    if (result.ok && result.respuesta) {
                        
                        $('#detallesNombreEvento').text(result.respuesta.nombre);
                        $('#detallesFechaInicio').text(result.respuesta.fecha_inicio);
                        $('#detallesFechaFin').text(result.respuesta.fecha_fin);
                        $('#detallesUbicacion').text(result.respuesta.lugar_competencia);
                        $('#detallesEstado').text(result.respuesta.estado);
     
                        $('#modalConsultarEventoAnterior').modal('show');
                    } else {
                        Swal.fire("Error", "No se pudo cargar la información del evento", "error");
                    }
                } catch (error) {
                    Swal.fire("Error", "Error al procesar la respuesta del servidor", "error");
                }
            },
            error: function () {
                Swal.fire("Error", "Error en la solicitud AJAX para consultar el evento", "error");
            }
        });
    });
    
    $('#formModificarResultados').on('submit', function (e) {
        e.preventDefault();

        const idCompetencia = $(this).data('id-competencia');
        const idAtleta = $(this).data('id-atleta');

        const datos = new FormData(this);
        datos.append("accion", "modificar_resultados");
        datos.append("id_competencia", idCompetencia);
        datos.append("id_atleta", idAtleta);

        $.ajax({
            url: "",
            type: "POST",
            contentType: false,
            data: datos,
            processData: false,
            cache: false,
            success: function (respuesta) {
                try {
                    const result = JSON.parse(respuesta);
                    if (result.ok) {
                        Swal.fire("Éxito", "Resultados modificados con éxito", "success");
                        $('#modalModificarResultados').modal('hide');
                        cargarEventos();
                    } else {
                        Swal.fire("Error", result.mensaje, "error");
                    }
                } catch (error) {
                    Swal.fire("Error", "Algo salió mal al modificar los resultados", "error");
                }
            },
            error: function () {
                Swal.fire("Error", "Error en la solicitud AJAX para modificar resultados", "error");
            }
        });
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
});
