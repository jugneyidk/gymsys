<?php
declare(strict_types=1);

function obtenerHeaderHTML($tituloReporte): string {
    return '<!DOCTYPE html>
    <html lang="es">
    <head>
     <meta charset="UTF-8">
     <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
     <style>
         @import url("https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap");
         
         body { 
             font-family: "Roboto", sans-serif;
             margin: 40px;
             color: #2C3E50;
             line-height: 1.6;
             background: #fff;
         }        
          .header {
             text-align: center;
             margin-bottom: 16px;
             padding: 16px 14px;
             color: #0056b3;
             border: 2px solid #0056b3;
             border-radius: 10px;
             box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
         }
         .institucion {
             margin-bottom: 20px;
             padding-bottom: 15px;
             border-bottom: 1px solid rgba(25s5, 255, 255, 0.3);
         }
 
         .institucion h1 {
             font-size: 22px;
             font-weight: 700;
             margin: 0 0 8px 0;
             text-transform: uppercase;
             letter-spacing: 1px;
         }
 
         .institucion h2 {
             font-size: 18px;
             font-weight: 500;
             margin: 0 0 5px 0;
             border: none;
             padding: 0;
         }
 
         .institucion p {
             font-size: 14px;
             margin: 0;
             font-weight: 300;
             opacity: 0.9;
         }
 
         .reporte-titulo {
             font-size: 26px;
             font-weight: 700;
             margin: 0;
             text-transform: uppercase;
             letter-spacing: 1px;
         }
 
         h2 { 
             font-size: 22px;
             color: #0056b3;
             margin: 10px 0 8px;
             padding-bottom: 8px;
             border-bottom: 3px solid #00897b;
             position: relative;
         }
 
         .seccion { 
             background: white;
             padding: 18px;
             margin: 10px 0;
             border-radius: 10px;
             box-shadow: 0 2px 4px rgba(0,0,0,0.05);
         }
 
         .estadisticas { 
             padding: 10px;
             background: #f8f9fa;
             border-radius: 10px;
             border-left: 5px solid #0056b3;
         }
         .datos-principales {
             column-count: 2;
             column-gap: 20px;
             margin: 14px 0;
         }
 
         .dato {
             padding: 10px;
             background: #f8f9fa;
             border-radius: 8px;
             margin-bottom: 10px;
             break-inside: avoid;
             text-align: left;
         }
          .dato strong {
             color: #0056b3;
             font-size: 0.9em;
             text-transform: uppercase;
             margin-right: 10px;
             flex: 1;
         }
 
         .dato span {
             font-size: 1em;
             color: #2C3E50;
             font-weight: 500;
             text-align: right;
             padding-left: 10px;
         }
 
          .dato-badge {
             float: right;
             background: #0056b3;
             border-radius: 10px;
             color: white;
             line-height: 20px;
             padding: 4px 8px;
         }
       .ficha-atleta{
             border-left: 5px solid #0056b3;
             background: #f8f9fa;
             border-radius: 10px;
             padding: 15px;
          }         
        @media print {
             .datos-principales {
                 column-count: 2 !important;
                 column-gap: 20px !important;
                 margin: 14px 0 !important;
             }
             .dato {
                 break-inside: avoid !important;
             }
          }
         ul { 
             list-style-type: none;
             padding: 0;
         }
 
         ul li {
             padding: 10px 15px;
             margin: 5px 0;
             background: #ffffff;
             border-radius: 6px;
             display: flex;
             justify-content: space-between;
             align-items: center;
         }
 
         ul li strong {
             color: #0056b3;
         }
 
         table { 
             width: 100%;
             border-collapse: separate;
             border-spacing: 0;
             margin-top: 16px;
             background: white;
             border-radius: 10px;
             overflow: hidden;
             box-shadow: 0 2px 4px rgba(0,0,0,0.05);
         }
 
         th { 
             background: #0056b3;
             color: white;
             font-weight: 500;
             text-transform: uppercase;
             font-size: 0.85em;
             padding: 15px;
             text-align: left;
         }
 
         td { 
             padding: 12px 15px;
             border-bottom: 1px solid #eee;
             font-size: 0.95em;
         }
 
         tr:last-child td {
             border-bottom: none;
         }
 
         tr:nth-child(even) { 
             background-color: #f8f9fa;
         }
 
         tr:hover td { 
             background-color: #e3f2fd;
         }
 
      
       .graficos {
             background: #f8f9fa;
             padding: 15px;
             border-radius: 10px;
             border-left: 5px solid #0056b3;
             margin: 10px 0;
             width: 50%;
             display: inline-block;
             vertical-align: top;
             margin-right: 2%;
         }
 
         .graficos img {
             max-width: 100%;
             height: auto;
             max-height: 300px;
         }
         .pagina-nueva {
             page-break-before: always;
             margin-top: 20px;
         }   
 
         @media print {
             body {
                 margin: 0;
                 padding: 20px;
             }
             
             .seccion {
                 break-inside: avoid;
                 page-break-inside: avoid;
             }
             
             table {
                 break-inside: auto;
                 page-break-inside: auto;
             }
             
             tr {
                 break-inside: avoid;
                 page-break-inside: avoid;
             }
         }
     </style>
     </head>
     <body>
     <div class="header">
        <div class="institucion">
              <h1>Gimnasio de Halterofilia "Eddie Suarez"</h1>
              <h2>Universidad Politécnica Territorial "Andrés Eloy Blanco"</h2>
              <p>Barquisimeto, Edo. Lara</p>
        </div>
        <div class="reporte-titulo">Reporte de ' . $tituloReporte . '</div>
        <small>' . date('d/m/Y') . '</small>
     </div>';
}

function obtenerFooterHTML(): string {
    return '</body></html>';
}
