<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>CRUD Estudiantes</title>
</head>
<body>
<div class="container">
        <!-- Formulario para agregar un estudiante -->
        <div class="form-container">
            <h1>Agregar Estudiante</h1>
            <form id="agregarEstudianteForm">
                <label for="no_cuenta">No. de Cuenta:</label>
                <input type="text" id="no_cuenta" name="no_cuenta"><br>
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre"><br>
                <label for="apellidos">Apellidos:</label>
                <input type="text" id="apellidos" name="apellidos"><br>
                <label for="correo">Correo:</label>
                <input type="text" id="correo" name="correo"><br>
                <button type="submit">Agregar Estudiante</button>
            </form>

            <h1>Actualizar Estudiante</h1>
            <form id="editarEstudianteForm">
                <input type="text" id="no_cuenta_act" name="no_cuenta" placeholder="No. de Cuenta">
                <input type="text" id="nombre_act" name="nombre" placeholder="Nombre">
                <input type="text" id="apellidos_act" name="apellidos" placeholder="Apellidos">
                <input type="text" id="correo_act" name="correo" placeholder="Correo electrónico">
                <button type="submit">Actualizar Estudiante</button>
            </form>

            <h1>Eliminar Estudiante</h1>
            <form id="eliminarEstudianteForm">
                <label for="no_cuenta_eliminar">No. de Cuenta:</label>
                <input type="text" id="no_cuenta_eliminar" name="no_cuenta_eliminar">
                <button type="button" onclick="eliminarEstudiante()">Eliminar Estudiante</button>
            </form>
        </div>

        <div>
            <h1>Estudiantes</h1>
            <div class="result-container" id="estudiantes">
        
            </div>
        </div>
        
    </div>
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const noCuenta = urlParams.get('no_cuenta');
        /* 
        fetch(`crud.php?no_cuenta=${noCuenta}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('no_cuenta').value = data.no_cuenta;
                document.getElementById('nombre').value = data.nombre;
                document.getElementById('apellidos').value = data.apellidos;
                document.getElementById('correo').value = data.correo;
            });
        */
        document.getElementById('editarEstudianteForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            fetch('crud.php', {
                method: 'PUT',
                body: JSON.stringify(Object.fromEntries(formData)),
            })
            .then(response => response.json())
            .then(data => {
                alert(data.mensaje);
                window.location.href = 'index.php';
            });
        });
        function cargarEstudiantes() {
            fetch('crud.php')
                .then(response => response.json())
                .then(data => {
                    let estudiantesHTML = '<table>';
                    estudiantesHTML += '<tr><th>No. de Cuenta</th><th>Nombre</th><th>Apellidos</th><th>Correo</th></tr>';
                    data.forEach(estudiante => {
                        estudiantesHTML += `<tr><td>${estudiante.no_cuenta}</td><td>${estudiante.nombre}</td><td>${estudiante.apellidos}</td><td>${estudiante.correo}</td></tr>`;
                    });
                    estudiantesHTML += '</table>';
                    document.getElementById('estudiantes').innerHTML = estudiantesHTML;
                });
        }

        document.getElementById('agregarEstudianteForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            fetch('crud.php', {
                method: 'POST',
                body: JSON.stringify(Object.fromEntries(formData)),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                alert(data.mensaje);
                cargarEstudiantes();
                limpiarFormulario();
            });
        });
        /* 
        function actualizarEstudiante() {
            const formData = new FormData();
            formData.append('no_cuenta', document.getElementById('no_cuenta_act').value);
            formData.append('nombre', document.getElementById('nombre_act').value);
            formData.append('apellidos', document.getElementById('apellidos_act').value);
            formData.append('correo', document.getElementById('correo_act').value);
            

            fetch('crud.php', {
                method: 'PUT',
                body: formData,
            
            })
            .then(response => response.json())
            .then(data => {
                alert(data.mensaje);
                cargarEstudiantes();
                limpiarFormulario();
            });
        }
        */
        function eliminarEstudiante() {
            const noCuenta = document.getElementById('no_cuenta_eliminar').value;
            fetch(`crud.php?no_cuenta=${noCuenta}`, {
                method: 'DELETE',
            })
            .then(response => response.json())
            .then(data => {
                alert(data.mensaje);
                cargarEstudiantes();
                limpiarFormulario();
            });
        }

        function limpiarFormulario() {
            document.getElementById('no_cuenta').value = '';
            document.getElementById('nombre').value = '';
            document.getElementById('apellidos').value = '';
            document.getElementById('correo').value = '';
            document.getElementById('no_cuenta_act').value = '';
            document.getElementById('nombre_act').value = '';
            document.getElementById('apellidos_act').value = '';
            document.getElementById('correo_act').value = '';
            document.getElementById('no_cuenta_eliminar').value = '';
        }

        window.onload = cargarEstudiantes;
    </script>
</body>
</html>
