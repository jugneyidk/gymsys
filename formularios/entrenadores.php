<form method="post" id="f1" autocomplete="off">
    <input type="hidden" name="accion" id="accion" value="">
    <div class="row mt-3">
        <div class="col-12 col-md mb-3">
            <div class="form-floating">
                <input type="text" class="form-control" id="cedula" name="cedula" placeholder="Cedula">
                <label for="cedula" class="form-label">Cédula</label>
                <div id="scedula" class="invalid-feedback"></div>
            </div>
        </div>
        <div class="col-6 col-md mb-3">
            <div class="form-floating">
                <input type="text" class="form-control" id="nombres" name="nombres" placeholder="Nombres">
                <label for="nombres" class="form-label">Nombres</label>
                <div id="snombres" class="invalid-feedback"></div>
            </div>
        </div>
        <div class="col-6 col-md mb-3">
            <div class="form-floating">
                <input type="text" class="form-control" id="apellidos" name="apellidos" placeholder="Apellidos">
                <label for="apellidos" class="form-label">Apellidos</label>
                <div id="sapellidos" class="invalid-feedback"></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="genero" class="form-label">Género:</label>
            <select class="form-select" id="genero" name="genero">
                <option>Masculino</option>
                <option>Femenino</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label for="estado_civil" class="form-label">Estado Civil:</label>
            <select class="form-select" id="estado_civil" name="estado_civil">
                <option>Soltero</option>
                <option>Casado</option>
                <option>Divorciado</option>
                <option>Viudo</option>
            </select>
        </div>
        <div class="col mb-3">
            <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
            <input type="date" class="form-control text-center" id="fecha_nacimiento" name="fecha_nacimiento">
            <div id="sfecha_nacimiento" class="invalid-feedback"></div>
        </div>
    </div>
    <div class="row">
        <div class="col mb-3">
            <div class="form-floating">
                <input type="text" class="form-control" id="lugar_nacimiento" name="lugar_nacimiento"
                    placeholder="Lugar de Nacimiento">
                <label for="lugar_nacimiento" class="form-label">Lugar de Nacimiento</label>
                <div id="slugarnacimiento" class="invalid-feedback"></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-md-6 col-lg-4 mb-3">
            <div class="form-floating">
                <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Teléfono">
                <label for="telefono" class="form-label">Teléfono</label>
                <div id="stelefono" class="invalid-feedback"></div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-4 mb-3">
            <div class="form-floating">
                <input type="email" class="form-control" id="correo_electronico" name="correo_electronico"
                    placeholder="Correo Electrónico">
                <label for="correo_electronico" class="form-label">Correo Electrónico</label>
                <div id="scorreo_electronico" class="invalid-feedback"></div>
            </div>
        </div>
        <div class="col-12 col-lg-4 mb-3">
            <div class="form-floating">
                <input type="text" class="form-control" id="grado_instruccion" name="grado_instruccion"
                    placeholder="Grado de Instrucción">
                <label for="grado_instruccion" class="form-label">Grado de Instrucción</label>
                <div id="sgrado_instruccion" class="invalid-feedback"></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3 d-none" id="modificar_contraseña_container">
            <div class="form-check form-switch">
                <label for="modificar_contraseña" class="form-check-label">Modificar Contraseña:</label>
                <input type="checkbox" class="form-check-input" id="modificar_contraseña" name="modificar_contraseña"
                    placeholder="Contraseña">
            </div>
        </div>
        <div class="col">
            <div class="form-floating">
                <input type="password" class="form-control" id="password" name="password" autocomplete="new-password"
                    placeholder="Contraseña">
                <label for="password" class="form-label">Contraseña</label>
                <div id="spassword" class="invalid-feedback"></div>
            </div>
        </div>
    </div>
    <div class="row m-2">
        <button type="submit" id="incluir" class="btn btn-primary btn-block">Enviar formulario</button>
    </div>
</form>