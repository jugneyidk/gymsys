$(document).ready(function () {
  let resultados = [];
  let indiceResultado = 0;
  function normalizarTexto(texto) {
    return texto
      .normalize("NFD")
      .replace(/[\u0300-\u036f]/g, "")
      .toLowerCase();
  }
  function buscarManual() {
    eliminarMarcado();
    var textoBusqueda = document.getElementById("buscar").value; // Obtiene el texto de búsqueda
    var terminoBusqueda = normalizarTexto(textoBusqueda); // Obtiene el término sin acentos para la busqueda
    console.log(terminoBusqueda);
    var titulos = document.querySelectorAll("h2,h3"); // Obtiene todos los titulos
    var parrafos = document.querySelectorAll("p,li"); // Obtiene todos los párrafos y listas
    resultados = [];
    indiceResultado = 0;
    // Primero buscamos en los titulos
    if (textoBusqueda.trim() !== "" && textoBusqueda.length > 0) {
      for (var i = 0; i < titulos.length; i++) {
        let contenidoNormalizado = normalizarTexto(titulos[i].textContent);
        if (contenidoNormalizado.includes(terminoBusqueda)) {
          scrollAlResultado(titulos[i]);
          buscarTextoParaResaltar(titulos[i], textoBusqueda);
          resultados.push(titulos[i]);
        }
      }

      // Si no se encuentra en los titulos, buscamos en los párrafos y listas
      for (var j = 0; j < parrafos.length; j++) {
        let contenidoNormalizado = normalizarTexto(parrafos[j].textContent);
        if (contenidoNormalizado.includes(terminoBusqueda)) {
          scrollAlResultado(parrafos[j]);
          buscarTextoParaResaltar(parrafos[j], terminoBusqueda);
          resultados.push(parrafos[j]);
        }
      }
    }
    if (resultados.length > 0) {
      scrollAlResultado(resultados[indiceResultado]);
    }
    console.log(resultados);
  }

  // Función para resaltar el texto buscado
  function buscarTextoParaResaltar(elemento, textoBuscado) {
    const textoElementoNormalizado = normalizarTexto(elemento.textContent);
    const textoBusquedaNormalizado = normalizarTexto(textoBuscado);
    if (textoElementoNormalizado.includes(textoBusquedaNormalizado)) {
      let indice = 0;
      let posiciones = [];
      while (
        (indice = textoElementoNormalizado.indexOf(
          textoBusquedaNormalizado,
          indice
        )) !== -1
      ) {
        posiciones.push(indice);
        indice += textoBusquedaNormalizado.length;
      }
      resaltarTexto(
        elemento,
        elemento.textContent,
        posiciones,
        textoBusquedaNormalizado
      );
    }
  }

  function resaltarTexto(
    elemento,
    textoOriginal,
    posiciones,
    textoBusquedaNormalizado
  ) {
    let resultado = "";
    let indiceTextoOriginal = 0;
    // Recorremos el texto original y vamos insertando el <mark> alrededor de las coincidencias
    for (let i = 0; i < posiciones.length; i++) {
      const inicio = posiciones[i];
      const fin = inicio + textoBusquedaNormalizado.length;
      // Agregamos la parte del texto antes de la coincidencia
      resultado += textoOriginal.slice(indiceTextoOriginal, inicio);
      // Agregamos la coincidencia resaltada
      resultado += `<mark>${textoOriginal.slice(inicio, fin)}</mark>`;
      // Actualizamos el índice del texto original
      indiceTextoOriginal = fin;
    }
    // Agregamos el texto restante después de la última coincidencia
    resultado += textoOriginal.slice(indiceTextoOriginal);
    elemento.innerHTML = resultado;
  }

  function scrollAlResultado(element) {
    // Calcula la altura de la barra sticky
    var navbarHeight = document.querySelector("nav.navbar")
      ? document.querySelector("nav.navbar").offsetHeight + 30
      : 0;
    window.scrollTo({
      top: element.offsetTop - navbarHeight, // Ajusta la posición del scroll para que no quede tapado por la navbar
      behavior: "smooth",
    });
  }
  function eliminarMarcado() {
    var elementosMarcados = document.querySelectorAll("mark"); // Encuentra todas las etiquetas <mark> en el documento
    elementosMarcados.forEach(function (mark) {
      mark.outerHTML = mark.innerHTML; // Elimina la etiqueta <mark>, pero mantiene el texto dentro
    });
  }
  function irAlSiguienteResultado() {
    if (resultados.length > 0) {
      // Desplazar al siguiente resultado
      indiceResultado = (indiceResultado + 1) % resultados.length; // Vuelve al principio cuando llega al final
      scrollAlResultado(resultados[indiceResultado]);
    }
  }

  $("#btnBuscar").on("click", function () {
    buscarManual();
  });
  $("#btnSiguiente").on("click", function () {
    irAlSiguienteResultado();
  });
});
