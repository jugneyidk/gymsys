import {
   validarKeyPress,
   REGEX,
   enviaAjax,
   muestraMensaje,
   validarKeyUp,
   validarFecha,
   obtenerNotificaciones,
   limpiarForm,
} from "./comunes.js";
$(document).ready(function () {
   cargarEventos();
   cargarListadoCategorias();
   cargarListadoSubs();
   cargarListadoTipos();
   cargarEventosAnteriores();
   function cargarEventos() {
      enviaAjax("", "?p=eventos&accion=listadoEventos", "GET").then((result) => {
         actualizarListadoEventos(result.eventos);
      });
   }
   var modales = document.querySelectorAll(
      ".modal:not(#carga,#modalVerEventoActivo)"
   );
   modales.forEach(function (modal) {
      modal.addEventListener("hidden.bs.modal", function (event) {
         limpiarForm();
      });
   });
   obtenerNotificaciones();
   setInterval(() => obtenerNotificaciones(), 35000);
   function cargarEventosAnteriores() {
      enviaAjax("", "?p=eventos&accion=listadoEventosAnteriores", "GET").then((result) => {
         if (result.eventos.length > 0) {
            actualizarListadoEventosAnteriores(result.eventos);
         } else {
            $("#tablaEventosAnteriores tbody").html(
               "<tr><td colspan='7'>No hay eventos anteriores</td></tr>"
            );
         }
      });
   }
   function cargarAtletasInscritos(idCompetencia) {
      enviaAjax("", `?p=eventos&accion=listadoAtletasInscritos&id_competencia=${idCompetencia}`, "GET").then((resultado) => {
         actualizarTablaAtletasInscritos(resultado.atletas, idCompetencia);
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
                            class="btn btn-outline-primary btn-sm ${tieneResultados
               ? "modificarResultados"
               : "registrarResultados"
            }" 
                            data-bs-toggle="modal" 
                            data-bs-target="#${tieneResultados
               ? "modalModificarResultados"
               : "modalRegistrarResultados"
            }" 
                            data-id-competencia="${idCompetencia}" 
                            data-id-atleta="${atleta.id_atleta}" 
                            data-nombre="${atleta.nombre} ${atleta.apellido}" 
                            data-cedula="${atleta.id_atleta}"
                            ${tieneResultados
               ? `
                                data-arranque="${atleta.arranque}" 
                                data-envion="${atleta.envion}" 
                                data-medalla-arranque="${atleta.medalla_arranque}" 
                                data-medalla-envion="${atleta.medalla_envion}" 
                                data-medalla-total="${atleta.medalla_total}" 
                                data-total="${atleta.total}"`
               : ""
            }
                        >
                            ${tieneResultados
               ? "Modificar Resultados"
               : "Registrar Resultados"
            }
                        </button>
                    </td>
                </tr>`;
      });
      if (filas === "") {
         filas =
            "<tr><td colspan='4'>No hay atletas inscritos en esta competencia.</td></tr>";
      }
      $("#tablaAtletasInscritos tbody").html(filas);
   }
   $(document).on("click", ".modificarResultados", function () {
      const idCompetencia = $(this).data("id-competencia");
      enviaAjax("", `?p=eventos&accion=obtenerCompetencia&id=${idCompetencia}`, "GET").then((respuesta) => {
         const datosCompetencia = respuesta.competencia;
         $("#nombreCompetenciaModificarResultados").text(datosCompetencia.nombre);
         $("#fechaCompetenciaModificarResultados").text(datosCompetencia.fecha_fin);
      });
      const idAtleta = $(this).data("id-atleta");
      $("#id_competencia_modificar_resultado").val(idCompetencia);
      $("#nombreAtletaModificarResultados").text($(this).data("nombre"));
      $("#id_atleta_modificar").val(idAtleta);
      $("#cedulaAtletaModificarResultados").text(idAtleta);
      $("#arranque_modificar").val($(this).data("arranque"));
      $("#envion_modificar").val($(this).data("envion"));
      $("#medalla_arranque_modificar").val($(this).data("medalla-arranque"));
      $("#medalla_envion_modificar").val($(this).data("medalla-envion"));
      $("#medalla_total_modificar").val($(this).data("medalla-total"));
      $("#total_modificar").val($(this).data("total"));
   });

   $("#formModificarResultados").on("submit", function (e) {
      e.preventDefault();
      const datos = new FormData(this);
      enviaAjax(datos, "?p=eventos&accion=modificarResultados").then((respuesta) => {
         muestraMensaje("Éxito", respuesta.mensaje, "success"
         );
         $("#modalModificarResultados").modal("hide");
         cargarAtletasInscritos($("#id_competencia_modificar_resultado").val()); // Recargar la tabla
      });
   });

   function actualizarListadoEventos(eventos) {
      let listadoEventos = "";
      eventos.forEach((evento) => {
         listadoEventos += `
                <div class="col col-md-6 col-lg-4 mb-4 d-flex align-items-stretch">
                    <div class="card border-primary position-relative flex-grow-1">
                    ${eliminar == 1
               ? `<button class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 eliminarEvento" 
                            data-id="${evento.id_competencia}" title="Eliminar">
                            <i class="fas fa-trash-alt"></i>
                        </button>`
               : ""
            }
                        <div class="card-header lead m-0 p-0 px-3 pb-1 bg-primary text-white">
                            <small><strong>${evento.nombre}</strong></small>
                        </div>
                        <div class="card-body row g-0 p-0">
                            <div class="col-md-1 bg-primary border-end border-primary">
                            </div>
                            <div class="col-md-11">
                                <div class="p-3 ps-4">
                                    <div class="card-title m-0 lead text-primary"><strong>${evento.nombre
            }</strong></div>
                                    <p class="card-text lead m-0 fs-6"><strong>Fecha: ${evento.fecha_inicio
            } al ${evento.fecha_fin}</strong></p>
                                    <p class="card-text lead m-0 fs-6"><strong>Cupos Disponibles: ${evento.cupos_disponibles
            }</strong></p>
                                    <p class="card-text lead m-0 fs-6"><strong>Participantes: ${evento.participantes
            }</strong></p>
                                </div>
                                ${actualizar == 1
               ? `<div class="card-footer my-1 border-primary">
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
                                            data-id-sub="${evento.subs}"
                                            data-nombre="${evento.nombre}" 
                                            data-inicio="${evento.fecha_inicio}" 
                                            data-fin="${evento.fecha_fin}" 
                                            data-ubicacion="${evento.lugar_competencia}">
                                            Inscribir
                                        </button>
                                        <button class="btn btn-outline-primary btn-sm" 
                                            data-modificar="modificar"
                                            data-id="${evento.id_competencia}">
                                            Modificar
                                        </button>
                                        <button id="cerrarC" class="cerrarC btn btn-outline-danger btn-sm" 
                                            data-id="${evento.id_competencia}">
                                            Cerrar
                                        </button>
                                    </div>
                                </div>`
               : ""
            }
                            </div>
                        </div>
                    </div>
                </div>`;
      });
      $("#lista-eventos").html(listadoEventos);
   }

   function actualizarListadoEventosAnteriores(eventos) {
      let listado = "";
      eventos.forEach((evento) => {
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
         title: "¿Estás seguro de eliminar este evento?",
         icon: "warning",
         showCancelButton: true,
         confirmButtonText: "Sí, eliminar",
         cancelButtonText: "No, cancelar",
      }).then((result) => {
         if (result.isConfirmed) {
            const datos = new FormData();
            datos.append("id_competencia", idCompetencia);
            enviaAjax(datos, "?p=eventos&accion=eliminarEvento").then((respuesta) => {
               muestraMensaje("Éxito", respuesta.mensaje, "success");
               cargarEventos();
            });
         }
      });
   }

   function cargarListadoCategorias() {
      enviaAjax("", "?p=categorias&accion=listadoCategorias", "GET").then((result) => {
         actualizarListadoCategorias(result.categorias);
         actualizarTablaCategorias(result.categorias);
      });
   }

   function actualizarListadoCategorias(categorias) {
      let opciones = "<option value='' selected>Seleccione una</option>";
      categorias.forEach((categoria) => {
         opciones += `<option value="${categoria.id_categoria}">${categoria.nombre}</option>`;
      });
      $("#in_categoria").html(opciones);
      $("#categoria_modificar").html(opciones);
   }

   function cargarListadoSubs() {
      enviaAjax("", "?p=subs&accion=listadoSubs", "GET").then((result) => {
         actualizarListadoSubs(result.subs);
         actualizarTablaSubs(result.subs);
      });
   }

   function actualizarListadoSubs(subs) {
      let opciones = "<option selected>Seleccione una</option>";
      subs.forEach((sub) => {
         opciones += `<option value="${sub.id_sub}">${sub.nombre}</option>`;
      });
      $("#in_subs").html(opciones);
      $("#subs_modificar").html(opciones);
   }

   function cargarListadoTipos() {
      enviaAjax("", "?p=tipocompetencia&accion=listadoTipos", "GET").then((result) => {
         actualizarTablaTipos(result.tipos);
         actualizarListadoTipos(result.tipos);
      });
   }

   function actualizarListadoTipos(tipos) {
      let opciones = "<option selected>Seleccione una</option>";
      tipos.forEach((tipo) => {
         opciones += `<option value="${tipo.id_tipo_competencia}">${tipo.nombre}</option>`;
      });
      $("#in_tipo").html(opciones);
      $("#tipo_modificar").html(opciones);
   }

   $("input").on("keypress", function (e) {
      var id = $(this).attr("id");
      switch (id) {
         case "in_nombre":
         case "in_ubicacion":
         case "in_nombre_categoria":
         case "in_subs_nombre":
         case "in_tipo_nombre":
            validarKeyPress(e, REGEX.keypress_alfanumerico.regex);
            break;
         case "cedula":
         case "in_edad_minima":
         case "in_edad_maxima":
            validarKeyPress(e, REGEX.keypress_numerico.regex);
            break;
         case "in_peso_minimo":
         case "in_peso_maximo":
            validarKeyPress(e, REGEX.keypress_decimal.regex);
            break;
         case "correo_electronico":
            validarKeyPress(e, REGEX.keypress_correo.regex);
            break;
         case "password":
            validarKeyPress(e, REGEX.keypress_password.regex);
            break;
      }
   });

   //aqui colocare los clicks para los elementos que no son estaticos sino que se crean directamente aqui en el js, porque no me estan agarrando con el jquery
   $(document).on("click", ".eliminarEvento", function () {
      const idCompetencia = $(this).data("id");
      eliminarEvento(idCompetencia);
   });

   $(document).on("click", ".cerrarC", function () {
      const idCompetencia = $(this).data("id");
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
   $(document).on("click", ".consultarEventoAnterior", function () {
      const idCompetencia = $(this).data("id");
      enviaAjax("", `?p=eventos&accion=obtenerCompetencia&id=${idCompetencia}`, "GET").then((result) => {
         const evento = result.competencia;
         $("#detallesNombreEventoAnterior").text(evento.nombre);
         $("#detallesFechaInicioAnterior").text(evento.fecha_inicio);
         $("#detallesFechaFinAnterior").text(evento.fecha_fin);
         $("#detallesUbicacionAnterior").text(evento.lugar_competencia);
         $("#detallesEstadoAnterior").text(evento.estado);
         $("#modalConsultarEventoAnterior").modal("show");
         $("#modalEventoConsultaAnterior").modal("hide");
      });
      obtenerResultadosCompetencia(idCompetencia);
   });
   function obtenerResultadosCompetencia(idCompetencia) {
      enviaAjax("", `?p=eventos&accion=obtenerResultadosCompetencia&id=${idCompetencia}`, "GET").then((result) => {
         const resultados = result.resultados;
         var contenido = '';
         resultados.forEach(resultado => {
            contenido += `
                <tr>
                    <td class='text-center align-middle'>${resultado.cedula}</td>
                    <td class='text-center align-middle'>${resultado.nombre} ${resultado.apellido}</td>
                    <td class='text-center align-middle'>${resultado.arranque}kg</td>
                    <td class='text-center align-middle'>${resultado.envion}kg</td>
                    <td class='text-center align-middle'>${resultado.total}kg</td>
                    <td class='text-center align-middle text-capitalize'><span class='badge bg-${!resultado.medalla_arranque || resultado.medalla_arranque == "ninguna" ? "dark'" : resultado.medalla_arranque == 'bronce' ? "danger" : resultado.medalla_arranque == 'plata' ? "secondary text-black" : resultado.medalla_arranque == "oro" ? "warning" : ""}'>${resultado.medalla_arranque ?? "No"}</span></td>
                    <td class='text-center align-middle text-capitalize'><span class='badge bg-${!resultado.medalla_envion || resultado.medalla_envion == "ninguna" ? "dark'" : resultado.medalla_envion == 'bronce' ? "danger" : resultado.medalla_envion == 'plata' ? "secondary text-black" : resultado.medalla_envion == "oro" ? "warning" : ""}'>${resultado.medalla_envion ?? "No"}</span></td>
                    <td class='text-center align-middle text-capitalize'><span class='badge bg-${!resultado.medalla_total || resultado.medalla_total == "ninguna" ? "dark'" : resultado.medalla_total == 'bronce' ? "danger" : resultado.medalla_total == 'plata' ? "secondary text-black" : resultado.medalla_total == "oro" ? "warning" : ""}'>${resultado.medalla_total ?? "No"}</span></td>
                </tr>
            `;
         });
         $("#resultadosEventoAnterior").html(contenido);
      });
   }
   $(document).on("click", ".verDetallesEvento", function () {
      const idCompetencia = $(this).data("id");
      enviaAjax("", `?p=eventos&accion=obtenerCompetencia&id=${idCompetencia}`, "GET").then((result) => {
         const competencia = result.competencia;
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
         title: "¿Estás seguro de cerrar este evento?",
         icon: "warning",
         showCancelButton: true,
         confirmButtonText: "Sí, cerrar",
         cancelButtonText: "No, cancelar",
      }).then((result) => {
         if (result.isConfirmed) {
            const datos = new FormData();
            datos.append("id_competencia", idCompetencia);
            enviaAjax(datos, "?p=eventos&accion=cerrarEvento").then((respuesta) => {
               muestraMensaje("Éxito", respuesta.mensaje, "success");
               cargarEventos();
               cargarEventosAnteriores();
            });
         }
      });
   }
   function cargarAtletasDisponiblesParaInscripcion(
      idCompetencia,
      idCategoria,
      idSub
   ) {
      console.log("Enviando datos al servidor:", {
         idCompetencia,
         idCategoria,
         idSub,
      });
      enviaAjax("", `?p=eventos&accion=listadoAtletasDisponibles&categoria=${idCategoria}&sub=${idSub}&idCompetencia=${idCompetencia}`, "GET").then((resultado) => {
         actualizarTablaAtletasDisponibles(resultado.atletas, idCompetencia);
      });
   }

   function actualizarTablaAtletasDisponibles(atletas, idCompetencia) {
      console.log("Actualizando tabla con atletas:", atletas);
      let tabla = $("#tablaParticipantesInscripcion tbody");
      tabla.empty();

      if (atletas?.length > 0) {
         atletas.forEach((atleta, index) => {
            tabla.append(`
                    <tr>
                        <td>${index + 1}</td>
                        <td>${atleta.nombre} ${atleta.apellido}</td>
                        <td>${atleta.id_atleta}</td>
                        <td>${atleta.peso} kg</td>
                        <td>${calcularEdad(atleta.fecha_nacimiento)}</td>
                        <td>
                            <input type="checkbox" class="form-check-input" name="atletas" value="${atleta.id_atleta
               }">
                        </td>
                    </tr>
                `);
         });
      } else {
         tabla.append(
            "<tr><td colspan='6'>No se encontraron atletas que cumplan con los requisitos.</td></tr>"
         );
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
         Swal.fire(
            "Advertencia",
            "Debe seleccionar al menos un atleta para inscribir.",
            "warning"
         );
         return;
      }
      const datos = new FormData();
      datos.append("id_competencia", idCompetencia);
      datos.append("atletas", JSON.stringify(atletasSeleccionados));
      enviaAjax(datos, "?p=eventos&accion=inscribirAtletas").then((resultado) => {
         muestraMensaje(
            "Éxito",
            resultado.mensaje,
            "success"
         );
         $("#modalInscribirEvento").modal("hide");
         cargarAtletasInscritos(idCompetencia);
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
         Swal.fire(
            "Error",
            "Debes completar todos los campos correctamente.",
            "error"
         );
         return;
      }
      const datos = new FormData();
      datos.append("id_competencia", idCompetencia);
      datos.append("id_atleta", idAtleta);
      datos.append("arranque", arranque);
      datos.append("envion", envion);
      datos.append("medalla_arranque", medallaArranque);
      datos.append("medalla_envion", medallaEnvion);
      datos.append("medalla_total", medallaTotal);
      datos.append("total", total);
      enviaAjax(datos, "?p=eventos&accion=registrarResultados").then((respuesta) => {
         muestraMensaje("Éxito", respuesta.mensaje, "success");
         $("#modalRegistrarResultados").modal("hide");
         cargarAtletasInscritos(idCompetencia);
      });
   });
   $("#formRegistrarSubs").on("submit", function (e) {
      e.preventDefault();
      const nombre = $("#in_subs_nombre").val().trim();
      const edadMinima = parseInt($("#in_edad_minima").val());
      const edadMaxima = parseInt($("#in_edad_maxima").val());
      if (!validarEnvio($(this))) {
         return;
      }
      if (edadMinima >= edadMaxima) {
         muestraMensaje(
            "Error",
            "La edad mínima no debe ser mayor o igual a la edad máxima",
            "error"
         );
         return;
      }
      const datos = new FormData(this);
      enviaAjax(datos, "?p=subs&accion=incluirSub").then((respuesta) => {
         muestraMensaje("Éxito", respuesta.mensaje, "success");
         cargarListadoSubs();
         $("#formRegistrarSubs")[0].reset();
      });
   });
   $("#btnConsultarSubs").on("click", function () {
      $("#contenedorTablaSubs").show();
   });
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
                        <button class="btn btn-warning btn-sm btnEditarSub" data-id="${sub.id_sub
            }" data-nombre="${sub.nombre}" data-edad-minima="${sub.edad_minima
            }" data-edad-maxima="${sub.edad_maxima}">Editar</button>
                        <button class="btn btn-danger btn-sm btnEliminarSub" data-id="${sub.id_sub
            }">Eliminar</button>
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
            datos.append("id_sub", idSub);
            enviaAjax(datos, "?p=subs&accion=eliminarSub").then(() => {
               muestraMensaje("Éxito", "Sub eliminado con éxito", "success");
               cargarListadoSubs();
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
               Swal.showValidationMessage(
                  "La edad mínima debe ser menor que la máxima"
               );
            }

            return { nuevoNombre, nuevaEdadMinima, nuevaEdadMaxima };
         },
      }).then((result) => {
         if (result.isConfirmed) {
            const datos = new FormData();
            datos.append("id_sub", idSub);
            datos.append("nombre", result.value.nuevoNombre);
            datos.append("edadMinima", result.value.nuevaEdadMinima);
            datos.append("edadMaxima", result.value.nuevaEdadMaxima);
            enviaAjax(datos, "?p=subs&accion=modificarSub").then((respuesta) => {
               muestraMensaje("Éxito", respuesta.mensaje, "success");
               cargarListadoSubs();
            });
         }
      });
   });
   $("#formRegistrarCategoria").on("submit", function (e) {
      e.preventDefault();
      if (!validarEnvio($(this))) {
         return;
      }
      if ($("#in_peso_minimo").val() > $("#in_peso_maximo").val()) {
         muestraMensaje(
            "Error",
            "El peso mínimo no puede ser mayor al peso máximo",
            "error"
         );
         return;
      }
      const datos = new FormData(this);
      enviaAjax(datos, "?p=categorias&accion=incluirCategoria").then((respuesta) => {
         muestraMensaje("Éxito", respuesta.mensaje, "success");
         $("#formRegistrarCategoria")[0].reset();
         cargarListadoCategorias();
      });
   });
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
            const nuevoPesoMinimo =
               document.getElementById("nuevoPesoMinimo").value;
            const nuevoPesoMaximo =
               document.getElementById("nuevoPesoMaximo").value;

            if (!nuevoNombre || nuevoNombre.length < 2) {
               Swal.showValidationMessage("El nombre es inválido.");
            } else if (
               !nuevoPesoMinimo ||
               !nuevoPesoMaximo ||
               nuevoPesoMinimo < 0 ||
               nuevoPesoMaximo <= nuevoPesoMinimo
            ) {
               Swal.showValidationMessage("El rango de peso es inválido.");
            } else {
               return {
                  nombre: nuevoNombre,
                  pesoMinimo: nuevoPesoMinimo,
                  pesoMaximo: nuevoPesoMaximo,
               };
            }
         },
      }).then((result) => {
         if (result.isConfirmed) {
            const datos = new FormData();
            datos.append("id_categoria", id);
            datos.append("nombre", result.value.nombre);
            datos.append("pesoMinimo", result.value.pesoMinimo);
            datos.append("pesoMaximo", result.value.pesoMaximo);
            enviaAjax(datos, "?p=categorias&accion=modificarCategoria").then((respuesta) => {
               muestraMensaje("Éxito", respuesta.mensaje, "success");
               cargarListadoCategorias();
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
         cancelButtonText: "No, cancelar",
      }).then((result) => {
         if (result.isConfirmed) {
            const datos = new FormData();
            datos.append("id_categoria", idCategoria);
            enviaAjax(datos, "?p=categorias&accion=eliminarCategoria").then((respuesta) => {
               muestraMensaje("Éxito", respuesta.mensaje, "success");
               cargarListadoCategorias();
            });
         }
      });
   });

   $("#arranque, #envion").on("input", function () {
      const arranque = parseInt($("#arranque").val()) || 0;
      const envion = parseInt($("#envion").val()) || 0;
      $("#total").val(arranque + envion);
   });

   $("#modalInscribirEvento").on("show.bs.modal", function (event) {
      const button = $(event.relatedTarget);
      const idCompetencia = button.data("id");
      const idCategoria = button.data("id-categoria");
      const idSub = button.data("id-sub");
      const eventoNombre = button.data("nombre");
      const ubicacion = button.data("ubicacion");
      const fechaFin = button.data("fin");
      const fechaInicio = button.data("inicio");
      $("#nombreEventoInscripcion").text(eventoNombre);
      $("#ubicacionEventoInscripcion").text(ubicacion);
      $("#fechaInicioEventoInscripcion").text(fechaInicio);
      $("#fechaFinEventoInscripcion").text(fechaFin);
      if (!idCompetencia) {
         muestraMensaje(
            "Error",
            "Faltan datos del evento. No se puede continuar.",
            "error"
         );
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

   $("input").on("keyup", function () {
      var id = $(this).attr("id");
      validarKeyUp(REGEX[id].regex, $(this), $("#s" + id), REGEX[id].mensaje);
   });

   function validarEnvio(formId) {
      let esValido = true;
      const form = $(formId);
      // Validación de campos comunes
      form.find('input[type="text"]:not([name="accion"])').each(function () {
         const idInput = $(this).attr("id");
         esValido &= validarKeyUp(
            REGEX[idInput].regex,
            form.find(`#${idInput}`),
            form.find(`#s${idInput}`),
            REGEX[idInput].mensaje
         );
         console.log(`${idInput} ${esValido}`);
      });
      return esValido;
   }

   $("#fRegistrarEvento").on("submit", function (e) {
      e.preventDefault();
      if (!validarEnvio($(this))) {
         return;
      }
      if (!validarFecha($("#in_date_start").val())) {
         muestraMensaje("Error", "La fecha de apertura no es válida", "error");
         $("#in_date_start").addClass("is-invalid");
         return;
      }
      if (!validarFecha($("#in_date_end").val())) {
         muestraMensaje("Error", "La fecha de clausura no es válida", "error");
         $("#in_date_end").addClass("is-invalid");
         return;
      }
      if (
         isNaN($("#in_tipo").val()) ||
         $("#in_tipo").val() === "" ||
         isNaN($("#in_subs").val()) ||
         $("#in_subs").val() === "" ||
         isNaN($("#in_categoria").val()) ||
         $("#in_categoria").val() === ""
      ) {
         muestraMensaje(
            "Error",
            "Debe seleccionar una categoria, sub y un tipo",
            "error"
         );
         console.log(!isNaN($("#in_tipo").val()) && $("#in_tipo").val() !== "");
         console.log(!isNaN($("#in_subs").val()) && $("#in_subs").val() !== "");
         console.log(
            !isNaN($("#in_categoria").val()) && $("#in_categoria").val() !== ""
         );
         return;
      }
      const datos = new FormData(this);
      enviaAjax(datos, "?p=eventos&accion=incluirEvento").then((respuesta) => {
         muestraMensaje("Éxito", respuesta.mensaje, "success");
         $("#modalRegistrarEvento").modal("hide");
         cargarEventos();
      });
   });

   $("#formModificarCompetencia").on("submit", function (e) {
      e.preventDefault();
      const datos = new FormData(this);
      enviaAjax(datos, "?p=eventos&accion=modificarEvento").then((respuesta) => {
         muestraMensaje(
            "Éxito",
            respuesta.mensaje,
            "success"
         );
         $("#modalModificarCompetencia").modal("hide");
         cargarEventos();
      });
   });

   $("#modalRegistrarEvento").on("show.bs.modal", function () {
      cargarListadoCategorias();
      cargarListadoSubs();
      cargarListadoTipos();
   });

   $("#modalRegistrarCategoria").on("show.bs.modal", function () {
      cargarListadoCategorias();
   });

   $("#modalRegistrarSubs").on("show.bs.modal", function () {
      cargarListadoSubs();
   });

   $("#modalRegistrarTipo").on("show.bs.modal", function () {
      cargarListadoTipos();
   });

   $("body").on("click", '[data-modificar="modificar"]', function (event) {
      const button = $(this);
      const idCompetencia = button.data("id");
      enviaAjax("", `?p=eventos&accion=obtenerCompetencia&id=${idCompetencia}`, "GET").then((result) => {
         const competencia = result.competencia;
         actualizarDatosModificar(competencia);
      });
   });

   function actualizarDatosModificar(competencia) {
      $("#id_competencia_modificar").val(competencia.id_competencia);
      $("#nombre_modificar").val(competencia.nombre);
      $("#ubicacion_modificar").val(competencia.lugar_competencia);
      $("#fecha_inicio_modificar").val(competencia.fecha_inicio);
      $("#fecha_fin_modificar").val(competencia.fecha_fin);
      $("#categoria_modificar").val(competencia.categoria).change();
      $("#subs_modificar").val(competencia.subs).change();
      $("#tipo_modificar").val(competencia.tipo_competicion).change();
      $("#modalModificarCompetencia").modal("show");
   }
   $("#formRegistrarTipo").on("submit", function (e) {
      e.preventDefault();
      if (!validarEnvio($(this))) {
         return;
      }
      const datos = new FormData(this);
      enviaAjax(datos, "?p=tipocompetencia&accion=incluirTipo", "POST").then((respuesta) => {
         muestraMensaje("Éxito", respuesta.mensaje, "success");
         $("#in_tipo_nombre").val("");
         cargarListadoTipos();
         $("#formRegistrarTipo")[0].reset();
         $("#modalRegistrarTipo").modal("hide");
      });
   });

   $("#btnConsultarTipos").on("click", function () {
      cargarListadoTipos();
      $("#contenedorTablaTipos").show();
   });

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
            datos.append("id_tipo", idTipo);
            enviaAjax(datos, "?p=tipocompetencia&accion=eliminarTipo").then((result) => {
               muestraMensaje("Éxito", result.mensaje, "success");
               cargarListadoTipos();
            });
         }
      });
   });

   $(document).on("click", ".btnEditarTipo", function () {
      const idTipo = $(this).data("id");
      const nombreTipo = $(this).data("nombre");
      Swal.fire({
         title: 'Editar Tipo',
         input: 'text',
         inputValue: nombreTipo,
         showCancelButton: true,
         confirmButtonText: 'Guardar',
         cancelButtonText: 'Cancelar',
         focusConfirm: false,
         inputValidator: (value) => {      // si devuelve texto, lo muestra como error
            if (!value || !REGEX.in_tipo_nombre.regex.test(value)) {
               return 'El nombre no puede estar vacío o no es válido.';
            }
         }
      }).then((result) => {
         if (result.isConfirmed) {
            const datos = new FormData();
            datos.append("id_tipo", idTipo);
            datos.append("nombre", result.value);
            enviaAjax(datos, "?p=tipocompetencia&accion=modificarTipo").then((respuesta) => {
               muestraMensaje("Éxito", respuesta.mensaje, "success");
               cargarListadoTipos();
            });
         }
      });
   });

   $("#btnRegresarTipo").on("click", function () {
      $("#modalRegistrarTipo").modal("hide");
      $("#modalRegistrarEvento").modal("show");
   });
   $("#btnRegresarCategorias").on("click", function () {
      $("#modalRegistrarCategoria").modal("hide");
      $("#modalRegistrarEvento").modal("show");
   });
   $("#btnRegresarSubs").on("click", function () {
      $("#modalRegistrarSubs").modal("hide");
      $("#modalRegistrarEvento").modal("show");
   });
   $("#btnConsultarCategorias").on("click", function () {
      $("#contenedorTablaCategorias").show();
   });
});
