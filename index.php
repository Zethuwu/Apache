<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Estudiantes</title>
</head>
<body>
    <h1>CRUD Estudiantes</h1>

    <!-- Formulario para agregar un estudiante -->
    <h2>Agregar Estudiante</h2>
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
    <h2>Actualizar Estudiante</h2>
    <div class="estudiante-form">
        <form id="editarEstudianteForm">
            <input type="text" id="no_cuenta_act" name="no_cuenta" placeholder="No_cuenta">
            <input type="text" id="nombre_act" name="nombre" placeholder="Nombre">
            <input type="text" id="apellidos_act" name="apellidos" placeholder="Apellidos">
            <input type="text" id="correo_act" name="correo" placeholder="Correo electrónico">

            <input type="submit" value="Actualizar">
        </form>
    </div>


    <!-- Formulario para eliminar un estudiante -->
    <h2>Eliminar Estudiante</h2>
    <form id="eliminarEstudianteForm">
        <label for="no_cuenta_eliminar">No. de Cuenta:</label>
        <input type="text" id="no_cuenta_eliminar" name="no_cuenta_eliminar"><br>
        <button type="button" onclick="eliminarEstudiante()">Eliminar Estudiante</button>
    </form>

    <!-- Div para mostrar los estudiantes -->
    <h2>Estudiantes</h2>
    <div id="estudiantes"></div>

    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const noCuenta = urlParams.get('no_cuenta');
        fetch(`crud.php?no_cuenta=${noCuenta}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('no_cuenta_act').value = data.no_cuenta;
                document.getElementById('nombre_act').value = data.nombre;
                document.getElementById('apellidos_act').value = data.apellidos;
                document.getElementById('correo_act').value = data.correo;
            });

        // Escuchar el evento submit del formulario y enviar la solicitud PUT
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
                // Redirigir a la página de inicio después de la actualización
                window.location.href = 'index.php';
            });
        });
        // Función para cargar estudiantes desde el servidor
        function cargarEstudiantes() {
            fetch('crud.php')
            .then(response => response.json())
            .then(data => {
                let estudiantesHTML = '';
                data.forEach(estudiante => {
                    estudiantesHTML += `<p>No. de Cuenta: ${estudiante.no_cuenta}, Nombre: ${estudiante.nombre}, Apellidos: ${estudiante.apellidos}, Correo: ${estudiante.correo}</p>`;
                });
                document.getElementById('estudiantes').innerHTML = estudiantesHTML;
            });
        }

        // Función para agregar un estudiante
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


        // Función para eliminar un estudiante
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


        // Función para limpiar el formulario
        function limpiarFormulario() {
            document.getElementById('no_cuenta').value = '';
            document.getElementById('nombre').value = '';
            document.getElementById('apellidos').value = '';
            document.getElementById('correo').value = '';
            document.getElementById('no_cuenta_actualizar').value = '';
            document.getElementById('nombre_act').value = '';
            document.getElementById('apellidos_actualizar').value = '';
            document.getElementById('correo_actualizar').value = '';
            document.getElementById('no_cuenta_eliminar').value = '';
        }

        // Cargar estudiantes al cargar la página
        window.onload = cargarEstudiantes;
    </script>
</body>
</html>
