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
                <div id="slugar_nacimiento" class="invalid-feedback"></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col mb-3">
            <div class="form-floating">
                <input type="text" class="form-control" id="peso" name="peso" placeholder="Peso (KG)">
                <label for="peso" class="form-label">Peso (KG)</label>
                <div id="speso" class="invalid-feedback"></div>
            </div>
        </div>
        <div class="col mb-3">
            <div class="form-floating">
                <input type="text" class="form-control" id="altura" name="altura" placeholder="Altura (CM)">
                <label for="altura" class="form-label">Altura (CM)</label>
                <div id="saltura" class="invalid-feedback"></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="tipo_atleta" class="form-label">Tipo de Atleta:</label>
            <select class="form-select" id="tipo_atleta" name="tipo_atleta">
            </select>
            <div id="stipo_atleta" class="invalid-feedback"></div>
            <span class="text-primary mt-2" id="openTipoAtletaModal" style="cursor: pointer;">
                Agregar nuevo tipo de atleta</span>
        </div>
        <div class="col-md-6 mb-3">
            <label for="entrenador_asignado" class="form-label">Entrenador asignado:</label>
            <select class="form-select" id="entrenador_asignado" name="entrenador_asignado">
            </select>
            <div id="sentrenador_asignado" class="invalid-feedback"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-md mb-3">
            <div class="form-floating">
                <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Teléfono">
                <label for="telefono" class="form-label">Teléfono</label>
                <div id="stelefono" class="invalid-feedback"></div>
            </div>
        </div>
        <div class="col-12 col-md mb-3">
            <div class="form-floating">
                <input type="email" class="form-control" id="correo_electronico" name="correo_electronico"
                    placeholder="Correo Electrónico">
                <label for="correo_electronico" class="form-label">Correo Electrónico</label>
                <div id="scorreo_electronico" class="invalid-feedback"></div>
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
        <div class="col mb-3">
            <div class="form-floating">
                <input type="password" class="form-control" id="password" name="password" autocomplete="new-password"
                    placeholder="Contraseña">
                <label for="password" class="form-label">Contraseña</label>
                <div id="spassword" class="invalid-feedback"></div>
            </div>
        </div>
    </div>
    <div class="row d-none" id="representantesContainer">
        <div class="col-12 col-md-12 col-lg-4 mb-3">
            <div class="form-floating">
                <input type="text" class="form-control" id="nombre_representante" name="nombre_representante"
                    placeholder="Nombre del Representante">
                <label for="nombre_representante" class="form-label">Nombre del Representante</label>
                <div id="snombre_representante" class="invalid-feedback"></div>
            </div>
        </div>
        <div class="col-12 col-md-12 col-lg-4 mb-3">
            <div class="form-floating">
                <input type="text" class="form-control" id="cedula_representante" name="cedula_representante"
                    placeholder="Cedula del Representante">
                <label for="cedula_representante" class="form-label">Cedula del Representante</label>
                <div id="scedula_representante" class="invalid-feedback"></div>
            </div>
        </div>
        <div class="col-12 col-md-12 col-lg-4 mb-3">
            <div class="form-floating">
                <input type="text" class="form-control" id="telefono_representante" name="telefono_representante"
                    placeholder="Telefono del Representante">
                <label for="telefono_representante" class="form-label">Telefono del Representante</label>
                <div id="stelefono_representante" class="invalid-feedback"></div>
            </div>
        </div>
        <div class="col-12 col-md-12 col-lg mb-3">
            <div class="form-floating">
                <input type="text" class="form-control" id="parentesco_representante" name="parentesco_representante"
                    placeholder="Parentesco">
                <label for="parentesco_representante" class="form-label">Parentesco</label>
                <div id="sparentesco_representante" class="invalid-feedback"></div>
            </div>
        </div>
    </div>
    <div class="row mx-2 mb-3">
        <button type="submit" id="incluir" class="btn btn-primary btn-block">Enviar formulario</button>
    </div>
</form>