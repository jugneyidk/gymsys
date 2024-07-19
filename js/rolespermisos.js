$(document).ready(function () {
    function cargaListadoRoles() {
        const datos = new FormData();
        datos.append("accion", "listado_roles");
        enviaAjax(datos);
    }

    cargaListadoRoles();

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

    function validarEnvio(formId) {
        let esValido = true;
        const form = $(formId);
        const sufijo = formId === "#f2" ? "_modificar" : "";

        const validaciones = [
            { regex: /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,50}$/, id: "nombre", errorMsg: "Solo letras y espacios (1-50 caracteres)" },
        ];

        validaciones.forEach(({ regex, id, errorMsg }) => {
            esValido &= validarKeyUp(regex, form.find(`#${id}${sufijo}`), form.find(`#s${id}${sufijo}`), errorMsg);
        });

        return esValido;
    }

    $('#btnIncluir, #btnModificar').on('click', function (event) {
        event.preventDefault();
    });

    $("#btnIncluir").on("click", function () {
        if (validarEnvio("#form_incluir")) {
            const datos = new FormData($("#form_incluir")[0]);
            datos.append("accion","incluir")
            enviaAjax(datos);
            $('#modalCrear').modal('hide');
        }
    });

    $("#btnModificar").on("click", function () {
        if (validarEnvio("#f2")) {
            const datos = new FormData($("#f2")[0]);
            enviaAjax(datos);
        }
    });  

    function enviaAjax(datos) {
        $.ajax({
            async: true,
            url: "",
            type: "POST",
            contentType: false,
            data: datos,
            processData: false,
            cache: false,
            beforeSend: function () { },
            timeout: 10000,
            success: function (respuesta) {
                try {
                    const lee = JSON.parse(respuesta);
                    if (lee.devol === "listado_roles") {
                        console.log(lee.respuesta)
                        actualizarListadoRoles(lee.respuesta);
                    } else if (lee.ok) {
                        Swal.fire("Éxito", "Operación realizada con éxito", "success");
                        cargaListadoRoles();
                        $('#modalModificar').modal('hide');
                    } else {
                        Swal.fire("Error", lee.mensaje, "error");
                    }
                } catch (error) {
                    Swal.fire("Error", "Algo salió mal", "error");
                }
            },
            error: function (request, status, err) {
                const errorMsg = status === "timeout" ? "Servidor ocupado, Intente de nuevo" : "Error al procesar la solicitud";
                Swal.fire("Error", errorMsg, "error");
            },
            complete: function () { },
        });
    }

    function actualizarListadoRoles(roles) {
        let listadoRoles = "";
        if ($.fn.DataTable.isDataTable("#tablaroles")) {
            $("#tablaroles").DataTable().destroy();
        }
        roles.forEach(rol => {
            listadoRoles += `
                <tr>
                    <td class='align-middle text-capitalize'>${rol.nombre}</td>
                    <td class='align-middle'>
                        <button class='btn btn-block btn-warning me-2' data-bs-toggle='modal' data-bs-target='#modalModificar' onclick='cargarDatosAtleta()'><i class="fa-regular fa-pen-to-square"></i></button>
                        <button class='btn btn-block btn-danger' onclick='eliminarAtleta()'><i class="fa-solid fa-trash-can"></i></button>
                    </td>
                </tr>
            `;
        });

        $("#listado").html(listadoRoles);
        $("#tablaroles").DataTable({
            columnDefs: [
                { targets: [1], orderable: false, searchable: false },
            ],
            language: {
                lengthMenu: "Mostrar _MENU_ por página",
                zeroRecords: "No se encontraron roles",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay roles disponibles",
                infoFiltered: "(filtrado de _MAX_ registros totales)",
                search: "Buscar:",
                paginate: {
                    first: "Primera",
                    last: "Última",
                    next: "Siguiente",
                    previous: "Anterior",
                },
            },
            autoWidth: true,
            order: [[0, "desc"]],
            dom: '<"top"f>rt<"bottom"lp><"clear">',
        });
    }
    $("input").on("keypress", function (e) {
        const id = $(this).attr("id");
        const regexMap = {
            "nombre": /^[a-zA-ZáéíóúÁÉÍÓÚ\s]*$/,
        };
        if (regexMap[id]) {
            validarKeyPress(e, regexMap[id]);
        }
    });

    $("input").on("keyup", function () {
        const id = $(this).attr("id");
        const formId = $(this).closest("form").attr("id");
        const sufijo = formId === "f2" ? "_modificar" : "";
        const regexMap = {
            "nombre": /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,50}$/,
        };

        if (regexMap[id.replace(sufijo, "")]) {
            validarKeyUp(
                regexMap[id.replace(sufijo, "")],
                $(this),
                $(`#s${id}`),
                $(`#${id.replace(sufijo, "")}_error`).text()
            );
        }
    });
});
