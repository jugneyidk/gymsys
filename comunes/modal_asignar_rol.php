<div class="modal fade" id="modalAsignarRol" tabindex="-1" aria-labelledby="modalAsignarRolLabel" aria-hidden="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAsignarRolLabel">Asignar Rol a usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="f1">
                    <div class="container">
                        <div class="row">
                            <div class="col mb-3" id="datosUsuario">
                                <strong>Usuario a modificar: </strong><span id="nombreUsuario"
                                    class="badge bg-secondary">No seleccionado</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="cedula" name="cedula"
                                        placeholder="Cedula">
                                    <label for="cedula" class="form-label">Cedula</label>
                                    <div class="position-absolute end-0 top-50 pe-3 translate-middle-y d-none"
                                        id="spinner-usuario">
                                        <div class="spinner-border spinner-border-sm" role="status">
                                            <span class="visually-hidden">Cargando usuario...</span>
                                        </div>
                                    </div>
                                </div>
                                <div id="scedula" class="text-danger"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="id_rol_asignar" class="form-label">Rol:</label>
                                <select class="form-select" id="id_rol_asignar" name="id_rol_asignar">

                                </select>
                            </div>
                        </div>
                        <div class="row px-2">
                            <button type="submit" class="btn btn-primary btn-block">Asignar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>