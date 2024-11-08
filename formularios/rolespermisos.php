<form method="post" id="form_incluir" autocomplete="off">
    <input type="text" class="d-none" id="id_rol" name="id_rol">
    <div class="row mt-3">
        <div class="col">
            <div class="form-floating">
                <input type="text" class="form-control" id="nombre_rol" name="nombre_rol" placeholder="Nombre del Rol">
                <label for="nombre" class="form-label">Nombre del Rol</label>
                <div id="snombre_rol" class="invalid-feedback">
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-2">
        <span class="fw-bold">Permisos</span>
    </div>
    <div class="row mb-2">
        <div class="col">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">
                            Modulo
                        </th>
                        <th scope="col" class="text-center">
                            Crear
                        </th>
                        <th scope="col" class="text-center">
                            Leer
                        </th>
                        <th scope="col" class="text-center">
                            Modificar
                        </th>
                        <th scope="col" class="text-center">
                            Eliminar
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Gestionar Entrenadores
                        </td>
                        <td class="text-center">
                            <input class="form-check-input" type="checkbox" name="centrenadores" id="centrenadores"
                                value="1">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input" type="checkbox" name="rentrenadores" id="rentrenadores"
                                value="1">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input" type="checkbox" name="uentrenadores" id="uentrenadores"
                                value="1">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input" type="checkbox" name="dentrenadores" id="dentrenadores"
                                value="1">
                        </td>
                    </tr>
                    <tr>
                        <td>Gestionar Atletas
                        </td>
                        <td class="text-center">
                            <input class="form-check-input" type="checkbox" name="catletas" id="catletas" value="1">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input" type="checkbox" name="ratletas" id="ratletas" value="1">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input" type="checkbox" name="uatletas" id="uatletas" value="1">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input" type="checkbox" name="datletas" id="datletas" value="1">
                        </td>
                    </tr>
                    <tr>
                        <td>Gestionar Roles y Permisos
                        </td>
                        <td class="text-center">
                            <input class="form-check-input" type="checkbox" name="crolespermisos" id="crolespermisos"
                                value="1">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input" type="checkbox" name="rrolespermisos" id="rrolespermisos"
                                value="1">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input" type="checkbox" name="urolespermisos" id="urolespermisos"
                                value="1">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input" type="checkbox" name="drolespermisos" id="drolespermisos"
                                value="1">
                        </td>
                    </tr>
                    <tr>
                        <td>Gestionar Asistencias
                        </td>
                        <td class="text-center">
                            <input class="form-check-input" type="checkbox" name="casistencias" id="casistencias"
                                value="1">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input" type="checkbox" name="rasistencias" id="rasistencias"
                                value="1">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input" type="checkbox" name="uasistencias" id="uasistencias"
                                value="1">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input" type="checkbox" name="dasistencias" id="dasistencias"
                                value="1">
                        </td>
                    </tr>
                    <tr>
                        <td>Gestionar Eventos/Competencias
                        </td>
                        <td class="text-center">
                            <input class="form-check-input" type="checkbox" name="ceventos" id="ceventos" value="1">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input" type="checkbox" name="reventos" id="reventos" value="1">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input" type="checkbox" name="ueventos" id="ueventos" value="1">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input" type="checkbox" name="deventos" id="deventos" value="1">
                        </td>
                    </tr>
                    <tr>
                        <td>Gestionar Mensualidad
                        </td>
                        <td class="text-center">
                            <input class="form-check-input" type="checkbox" name="cmensualidad" id="cmensualidad"
                                value="1">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input" type="checkbox" name="rmensualidad" id="rmensualidad"
                                value="1">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input" type="checkbox" name="umensualidad" id="umensualidad"
                                value="1">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input" type="checkbox" name="dmensualidad" id="dmensualidad"
                                value="1">
                        </td>
                    </tr>
                    <tr>
                        <td>Gestionar Wada
                        </td>
                        <td class="text-center">
                            <input class="form-check-input" type="checkbox" name="cwada" id="cwada" value="1">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input" type="checkbox" name="rwada" id="rwada" value="1">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input" type="checkbox" name="uwada" id="uwada" value="1">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input" type="checkbox" name="dwada" id="dwada" value="1">
                        </td>
                    </tr>
                    <tr>
                        <td>Gestionar Reportes
                        </td>
                        <td class="text-center">
                            <input class="form-check-input" type="checkbox" name="creportes" id="creportes" value="1">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input" type="checkbox" name="rreportes" id="rreportes" value="1">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input" type="checkbox" name="ureportes" id="ureportes" value="1">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input" type="checkbox" name="dreportes" id="dreportes" value="1">
                        </td>
                    </tr>
                    <tr>
                        <td>Gestionar Bitacora
                        </td>
                        <td class="text-center">
                            <input class="form-check-input" type="checkbox" name="cbitacora" id="cbitacora" value="1"
                                disabled>
                        </td>
                        <td class="text-center">
                            <input class="form-check-input" type="checkbox" name="rbitacora" id="rbitacora" value="1">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input" type="checkbox" name="ubitacora" id="ubitacora" value="1"
                                disabled>
                        </td>
                        <td class="text-center">
                            <input class="form-check-input" type="checkbox" name="dbitacora" id="dbitacora" value="1"
                                disabled>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row m-2">
        <button type="button" id="btnSubmit" class="btn btn-primary btn-block">Registrar Rol</button>
    </div>
</form>