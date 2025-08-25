<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="./src/img/round-table.png">
    <link rel="stylesheet" href="./Recursos/animate.css" />
    <link rel="stylesheet" href="./Recursos/fontawessonmeAll.css" />
    <link rel="stylesheet" href="./Recursos/bootstrap/Css/bootstrap.min.css" />
    <link rel="stylesheet" href="./Recursos/pro.fontawessome.css" />
    <link rel="stylesheet" href="./src/css/Login.css">
    <title>Mozo Local</title>
</head>

<body>
    <div class="parallax">
        <nav class="navbar navbar-expand-lg bg-dark d-flex justify-content-center">
            <h1 class="text-white">Mozo</h1>
        </nav>

        <!-- class="container row animate__animated" -->
        <main class="animate__animated" id="containera">
            <h1 class="text-white">Introduzca su pin</h1>
            <div id="error" style="display: none;">
                <div class="text-danger">Error your ping is incorrect</div>
            </div>
            <section id="code">
                <div class="code " id="code1"></div>
                <div class="code " id="code2"></div>
                <div class="code " id="code3"></div>
                <div class="code " id="code4"></div>
            </section>
            <section id="keyboard">
                <button class="btn animate__animated pin" id="pin1" onclick="addpin(1);">1</button>
                <button class="btn animate__animated pin" id="pin2" onclick="addpin(2);">2</button>
                <button class="btn animate__animated pin" id="pin3" onclick="addpin(3);">3</button>
                <button class="btn animate__animated pin" id="pin4" onclick="addpin(4);">4</button>
                <button class="btn animate__animated pin" id="pin5" onclick="addpin(5);">5</button>
                <button class="btn animate__animated pin" id="pin6" onclick="addpin(6);">6</button>
                <button class="btn animate__animated pin" id="pin7" onclick="addpin(7);">7</button>
                <button class="btn animate__animated pin" id="pin8" onclick="addpin(8);">8</button>
                <button class="btn animate__animated pin" id="pin9" onclick="addpin(9);">9</button>
                <button class="btn animate__animated pin  pin-delete" onclick="Delete();"><i class="fas fa-minus fa-1x"></i></button>
                <button class="btn animate__animated pin pin0" id="pin0" onclick="addpin(0);">0</button>
                <button class="btn animate__animated pin pin-send" onclick="Sendcode();"><i class="fas fa-arrow-right fa-1x"></i></button>
            </section>

        </main>

    </div>
</body>
<script src="./Recursos/jquery.js"></script>
<script src="./Recursos/popper.min.js"></script>
<script src="./Recursos/bootstrap/Js/bootstrap.min.js"></script>
<script src="./src/js/Default.js"></script>
<script src="./src/js/pin.js"></script>

</html>