$(document).ready(function () {
  $("#login").on("submit", function (e) {
    e.preventDefault();
  });
  $("#submit").on("click", function () {
    var datos = new FormData($("#login")[0]);
    datos.append("accion", "login");
    enviaAjax(datos);
  });
});
function muestraMensaje(titulo, mensaje, icono) {
  Swal.fire({
    title: titulo,
    text: mensaje,
    icon: icono,
    showConfirmButton: false,
    showCancelButton: true,
    cancelButtonText: "Cerrar",
  });
}
function enviaAjax(datos) {
  $.ajax({
    async: true,
    url: "",
    type: "POST",
    contentType: false,
    data: datos,
    processData: false,
    cache: false,
    beforeSend: function () {},
    timeout: 10000,
    success: function (respuesta) {
      try {
        var lee = JSON.parse(respuesta);
        if (lee.resultado) {
          location = ".";
        } else if (!lee.resultado) {
          muestraMensaje(lee.mensaje, "", "error");
        } else if (lee.resultado == "error") {
          muestraMensaje(lee.mensaje, "", "error");
        }
      } catch (e) {
        alert("Error en JSON " + e.name);
        muestraMensaje(lee.mensaje, "", "success");
        console.error(respuesta);
      }
    },
    error: function (request, status, err) {
      // if (status == "timeout") {
      //   muestraMensaje("Servidor ocupado", "Intente de nuevo", "error");
      // } else {
      //   muestraMensaje("Error", request + status + err, "error");
      // }
    },
    complete: function () {},
  });
}
