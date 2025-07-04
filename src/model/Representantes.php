<?php

namespace Gymsys\Model;

use Gymsys\Core\Database;
use Gymsys\Utils\Cipher;
use Gymsys\Utils\ExceptionHandler;
use Gymsys\Utils\Validar;

class Representantes
{
    private Database $database;
    public function __construct(Database $database)
    {
        $this->database = $database;
    }
    public function existeRepresentante(string $cedula): bool
    {
        $consulta = "SELECT cedula, nombre_completo FROM representantes WHERE cedula = :cedula";
        $response = $this->database->query($consulta, [':cedula' => $cedula], true);
        return !empty($response); // Devuelve los datos si existe o no
    }
    public function incluirRepresentante(array $datos): array
    {
        $keys = ['cedula', 'nombre', 'telefono', 'parentesco'];
        $arrayFiltrado = Validar::validarArray($datos, $keys);
        Validar::validar("cedula", $arrayFiltrado["cedula"]);
        Validar::validar("nombres", $arrayFiltrado["nombre"]);
        Validar::validar("telefono", $arrayFiltrado["telefono"]);
        Validar::validar("parentesco_representante", $arrayFiltrado["parentesco"]);
        return $this->_incluirRepresentante($arrayFiltrado);
    }
    private function _incluirRepresentante(array $datos): array
    {
        $consulta = "INSERT INTO representantes (cedula, nombre_completo, telefono, parentesco) 
        VALUES (:cedula, :nombreCompleto, :telefono, :parentesco)
        ON DUPLICATE KEY UPDATE 
        nombre_completo = :nombreCompleto,
        telefono = :telefono,
        parentesco = :parentesco";
        $valores = [
            ':cedula' => $datos['cedula'],
            ':nombreCompleto' => $datos['nombre'],
            ':telefono' => $datos['telefono'],
            ':parentesco' => $datos['parentesco']
        ];
        $response = $this->database->query($consulta, $valores);
        if (empty($response)) {
            ExceptionHandler::throwException("Ocurrió un error al incluir el representante", 500, \Exception::class);
        }
        $resultado["mensaje"] = "El representante se ha incluido exitosamente";
        return $resultado;
    }
    public function obtenerRepresentante(array $datos): array
    {
        $keys = ['cedula'];
        $arrayFiltrado = Validar::validarArray($datos, $keys);
        $cedula = $arrayFiltrado['cedula'];
        // $cedula = Cipher::aesDecrypt($arrayFiltrado['cedula']);
        Validar::validar("cedula", $cedula);
        return $this->_obtenerRepresentante($cedula);
    }
    private function _obtenerRepresentante(string $cedula): array
    {
        $consulta = "SELECT r.cedula, r.nombre_completo, r.telefono, r.parentesco, a.cedula as atleta_representado 
                    FROM representantes r
                    LEFT JOIN atleta a ON r.cedula = a.representante
                    WHERE r.cedula = :cedula";
        $response = $this->database->query($consulta, [':cedula' => $cedula], true) ?: [];
        if (empty($response)) {
            ExceptionHandler::throwException("No se encontro el representante", 404, \InvalidArgumentException::class);
        }
        return $response;
    }
    public function listadoRepresentantes(): array
    {
        $consulta = "SELECT 
                        r.cedula, 
                        r.nombre_completo, 
                        r.telefono, 
                        r.parentesco,
                        a.cedula as atleta_representado
                    FROM representantes r
                    LEFT JOIN atleta a ON r.cedula = a.representante";
        $response = $this->database->query($consulta);
        $resultado["representantes"] = $response ?: [];
        return $resultado;
    }
}
