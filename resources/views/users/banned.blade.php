<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>

    <title>Page Title</title>

    <link href="{{ asset('css/loginUserStyle.css') }}" rel="stylesheet">
    <style>
        .pnlPrincipal{
            background-color: white
        }
        p.text{
            margin-left : 10%;
            margin-right: 10%;

            text-align: justify;
        }
    </style>
</head>
<body>
    <div class = "pnlPrincipal">
        <div class = "pnlSuperior">
            <h3>CUENTA BANEADA</h3>
        </div>
        <div class = "pnlClient">
            <p class = "text">
                <br>Su cuenta ha sido baneada por lo que no puede acceder. <br>
            </p><br>
        </div>
        <div class = "pnlInferior">
            <a href=' {{route('home')}} '><button class="btn_create">Salir</button></a><br>
        </div>
    </div>
</body>
</html>
