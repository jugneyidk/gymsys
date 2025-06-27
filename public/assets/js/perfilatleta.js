import { enviaAjax, obtenerNotificaciones, modalCarga } from "./comunes.js";
$(document).ready(function () {
   cargarPerfilUsuario();
   obtenerNotificaciones();
   async function cargarPerfilUsuario() {
      const idUsuario = obtenerParametro("cedula");
      const respuesta = await enviaAjax("", `?p=perfilatleta&accion=obtenerPerfilUsuario&cedula=${idUsuario}`, "GET");
      llenarPerfil(respuesta.usuario);
   }
   function obtenerParametro(nombre) {
      nombre = nombre.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
      var regex = new RegExp("[\\?&]" + nombre + "=([^&#]*)"),
         results = regex.exec(location.search);
      return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
   }
   function llenarPerfil(usuario) {
      const fecha = new Date(usuario.fecha_nacimiento).toLocaleDateString();
      $("#nombre-completo").text(`${usuario.nombre} ${usuario.apellido}`);
      $("#cedula").text(formatearCedula(usuario.cedula));
      $("#fecha-nacimiento").text(fecha);
      const edad = calcularEdad(usuario.fecha_nacimiento);
      $("#edad").text(`${edad} años`);
      $("#genero").text(usuario.genero);
      $("#peso").text(`${usuario.peso}kg`);
      $("#altura").text(`${usuario.altura}cm`);
      $("#correo-electronico").text(usuario.correo_electronico);
      $("#telefono").text(usuario.telefono);
   }
   function formatearCedula(cedula) {
      // Elimina cualquier carácter que no sea dígito
      const soloDigitos = cedula.replace(/\D/g, '');
      // Inserta un punto cada tres dígitos empezando desde la derecha
      const conPuntos = soloDigitos.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
      return `V-${conPuntos}`;
   }
   function calcularEdad(fechaNacimiento) {
      const nacimiento = new Date(fechaNacimiento);
      const hoy = new Date();
      let edad = hoy.getFullYear() - nacimiento.getFullYear();
      // Ajuste por si aun no ha ocurrido el cumpleaños este año
      const diferenciaMes = hoy.getMonth() - nacimiento.getMonth();
      const diferenciaDia = hoy.getDate() - nacimiento.getDate();
      if (diferenciaMes < 0 || (diferenciaMes === 0 && diferenciaDia < 0)) {
         edad--;
      }
      return edad;
   }
});
