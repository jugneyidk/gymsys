console.log("aqui");

$("#btnRegistrarCategoria").on("click",function(event){
  event.preventDefault();
  
  var data = new FormData(document.getElementById("registrarCategoria"));
console.log(data);
  data.append('option','registrarCategoria');
  var respuesta = enviarConsultaAjax(data);
  console.log(respuesta);
});

$('#in_categoria').on('click',function(event){
  var form = new FormData();
  form.append("option","consultarCategoria");

  var datos = enviarConsultaAjax(form);
  console.log(datos);
  
  
});

function enviarConsultaAjax(value){
  console.log(value);
    var comp = $.ajax({
            async: true,
            url: "",
            type: "POST",
            contentType: false,
            data: value,
            processData: false,
            cache: false,
            beforeSend: function () { },
            timeout: 10000,
            success: "",
            error: function (request, status, err) {
                const errorMsg = status === "timeout" ? "Servidor ocupado, Intente de nuevo" : "Error al procesar la solicitud";
                Swal.fire("Error", errorMsg, "error");
            },
            complete: function () { },
        });
  }

document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('fRegistrarEvento');

  form.addEventListener('submit', function(event) {
      if (!validarEnvio()) {
          event.preventDefault();
          event.stopPropagation();
          data = new FormData($("#fRegistrarEvento"));
          enviarAjax(data);
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
                    if (lee.devol === "listado_atletas") {
                        actualizarListadoAtletas(lee.respuesta);
                    } else if (lee.ok) {
                        Swal.fire("Éxito", "Operación realizada con éxito", "success");
                        cargaListadoAtleta();
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

  function validarKeyPress(event, regex) {
      const key = event.key;
      if (!regex.test(key)) {
          event.preventDefault();
      }
  }

  function validarKeyUp(input, regex, mensaje) {
      if (regex.test(input.value)) {
          input.classList.remove('is-invalid');
          input.classList.add('is-valid');
          mensaje.textContent = '';
      } else {
          input.classList.remove('is-valid');
          input.classList.add('is-invalid');
          mensaje.textContent = 'Entrada inválida';
      }
  }

  
  function validarEnvio() {
      let isValid = true;

    
      const nombre = document.getElementById('in_nombre');
      const nombreMensaje = nombre.nextElementSibling;
      if (!/^[a-zA-Z0-9\s]{1,100}$/.test(nombre.value)) {
          isValid = false;
          nombre.classList.add('is-invalid');
          nombreMensaje.textContent = 'Solo puede usar letras y números (1-100 caracteres)';
      } else {
          nombre.classList.remove('is-invalid');
          nombre.classList.add('is-valid');
      }

     
      const ubicacion = document.getElementById('in_ubicacion');
      const ubicacionMensaje = ubicacion.nextElementSibling;
      if (!/^[a-zA-Z0-9\s]{1,100}$/.test(ubicacion.value)) {
          isValid = false;
          ubicacion.classList.add('is-invalid');
          ubicacionMensaje.textContent = 'Solo puede usar letras y números (1-100 caracteres)';
      } else {
          ubicacion.classList.remove('is-invalid');
          ubicacion.classList.add('is-valid');
      }

      const fechaApertura = document.getElementById('in_date_start');
      const fechaAperturaMensaje = fechaApertura.nextElementSibling;
      if (fechaApertura.value === '') {
          isValid = false;
          fechaApertura.classList.add('is-invalid');
          fechaAperturaMensaje.textContent = 'La fecha de apertura es obligatoria';
      } else {
          fechaApertura.classList.remove('is-invalid');
          fechaApertura.classList.add('is-valid');
      }

      const fechaClausura = document.getElementById('in_date_end');
      const fechaClausuraMensaje = fechaClausura.nextElementSibling;
      if (fechaClausura.value === '') {
          isValid = false;
          fechaClausura.classList.add('is-invalid');
          fechaClausuraMensaje.textContent = 'La fecha de clausura es obligatoria';
      } else if (fechaClausura.value < fechaApertura.value) {
          isValid = false;
          fechaClausura.classList.add('is-invalid');
          fechaClausuraMensaje.textContent = 'La fecha de clausura debe ser posterior a la fecha de apertura';
      } else {
          fechaClausura.classList.remove('is-invalid');
          fechaClausura.classList.add('is-valid');
      }
      
      const categoria = document.getElementById('in_categoria');
      const categoriaMensaje = categoria.nextElementSibling;
      if (categoria.value === 'Seleccione una') {
          isValid = false;
          categoria.classList.add('is-invalid');
          categoriaMensaje.textContent = 'Debe seleccionar una categoría';
      } else {
          categoria.classList.remove('is-invalid');
          categoria.classList.add('is-valid');
      }

      const subs = document.getElementById('in_subs');
      const subsMensaje = subs.nextElementSibling;
      if (subs.value === 'Seleccione una') {
          isValid = false;
          subs.classList.add('is-invalid');
          subsMensaje.textContent = 'Debe seleccionar un subs';
      } else {
          subs.classList.remove('is-invalid');
          subs.classList.add('is-valid');
      }

      const tipo = document.getElementById('in_tipo');
      const tipoMensaje = tipo.nextElementSibling;
      if (tipo.value === 'Seleccione una') {
          isValid = false;
          tipo.classList.add('is-invalid');
          tipoMensaje.textContent = 'Debe seleccionar un tipo';
      } else {
          tipo.classList.remove('is-invalid');
          tipo.classList.add('is-valid');
      }

      return isValid;
  }

  const nombre = document.getElementById('in_nombre');
  const ubicacion = document.getElementById('in_ubicacion');
  nombre.addEventListener('keypress', (event) => validarKeyPress(event, /^[a-zA-Z0-9\s]$/));
  ubicacion.addEventListener('keypress', (event) => validarKeyPress(event, /^[a-zA-Z0-9\s]$/));

  nombre.addEventListener('keyup', () => validarKeyUp(nombre, /^[a-zA-Z0-9\s]{1,100}$/, nombre.nextElementSibling));
  ubicacion.addEventListener('keyup', () => validarKeyUp(ubicacion, /^[a-zA-Z0-9\s]{1,100}$/, ubicacion.nextElementSibling));
});


  
 
