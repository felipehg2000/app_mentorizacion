<!DOCTYPE html>
<html>
<head>
    <title>Registro de usuarios</title>
	<link href="{{ asset('css/createUserStyle.css') }}" rel="stylesheet">
    <script src="{{ asset('js/User/createUserScript.js') }}"></script>
</head>
<body>
    <div class="pnlPrincipal">
        <div class="pnlSuperior">
            <h3>Registro de usuarios</h3>
        </div>
        <form action="{{route('users.create.store')}}" method="POST">
            @csrf
            <div class="pnlClient">
                <div class="pnlRight" id="pnlRight">
                    <label for="input_name" id="lbl_name" class="right"></label>                                                                                    <br>
                    <input class="right" type="text" id="input_name" name="name"  placeholder="Nombre" onfocus="createLabel(1)" onblur="deleteLabel(1)">          <br>

                    <label for="input_surname" id="lbl_surname" class="right"></label>                                                                              <br>
                    <input class="right" type="text" id="input_surname" name="surname" placeholder="Apellidos" onfocus="createLabel(2)" onblur="deleteLabel(2)">                   <br>

                    <label for="input_email" id="lbl_email" class="right"></label>                                                                                  <br>
                    <input class="right" type="text" id="input_email"  name="email" placeholder="Email" onfocus="createLabel(3)" onblur="deleteLabel(3)">                         <br>

                    <label for="input_user" id="lbl_user" class="right"></label>                                                                                  <br>
                    <input class="right" type="text" id="input_user"  name="user" placeholder="Usuario" onfocus="createLabel(13)" onblur="deleteLabel(13)">                         <br>

                    <label for="input_password" id="lbl_password" class="right"></label>                                                                            <br>
                    <input class="right" type="password" id="input_password" name="password" placeholder="Contraseña" onfocus="createLabel(4)" onblur="deleteLabel(4)">             <br>

                    <label for="input_rep_password" id="lbl_rep_password" class="right"></label>                                                                    <br>
                    <input class="right" type="password" id="input_rep_password" name="rep_password" placeholder="Repetir contraseña" onfocus="createLabel(5)" onblur="deleteLabel(5)"> <br>

                    <label for="input_study_area" id="lbl_study_area" class="right"></label>                                                                        <br>
                    <select class="right" id="input_study_area" name="campoestudio" onfocus="createLabel(12)" onblur="deleteLabel(12)">
                        <option value="0">Seleccione su area de estudio</option>
                        <option value="1">Rama tecnológica</option>
                        <option value="2">Rama biosanitaria</option>
                        <option value="3">Rama de arte</option>
                        <option value="4">Rama jurista</option>
                        <option value="5">Rama lingüistica</option>
                    </select>                                                                                                                                       <br><br>
                </div>

                <div class="pnlLeft" id="pnlLeft">
                    <label for="input_user_type" id="lbl_user_type"></label><br>
                    <select class="left" id="input_user_type" name="tipousuario" onfocus="createLabel(6)" onblur="deleteLabel(6)" onchange="userTypeChange(this.value)">
                        <option value="0">Seleccione el tipo de usuario</option>
                        <option value="1">Estudiante</option>
                        <option value="2">Mentor</option>
                    </select>
                </div>
            </div>

            <div class="pnlInferior">
                @foreach ($errors->all() as $error)
                    <small class="error">{{ $error }}</small>
                @endforeach
                <button class="btn_create" type="submit">Crear usuario</button><br>
            </div>
        </form>
    </div>
</body>
</html>
