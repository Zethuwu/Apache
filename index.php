<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Estudiantes</title>
</head>
<body>
    <h1>CRUD Estudiantes</h1>

    <button onclick="cargarEstudiantes()">Cargar Estudiantes</button>

    <!-- Div para mostrar los estudiantes -->
    <div id="estudiantes"></div>

    <script>
        // Función para cargar estudiantes desde el servidor
        function cargarEstudiantes() {
            fetch('crud.php')
            .then(response => response.json())
            .then(data => {
                let estudiantesHTML = '';
                data.forEach(estudiante => {
                    estudiantesHTML += `<p>${estudiante.nombre} ${estudiante.apellidos} - ${estudiante.correo}</p>`;
                });
                document.getElementById('estudiantes').innerHTML = estudiantesHTML;
            });
        }
    </script>

    
</body>
</html>
