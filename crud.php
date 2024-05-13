<?php
// Datos de conexión a la base de datos
$servername = "localhost";
$username = "oscar";
$password = "Default123#";
$database = "sistemas_informacion";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Función para obtener todos los estudiantes
function obtenerEstudiantes() {
    global $conn;
    $sql = "SELECT * FROM estudiante";
    $result = $conn->query($sql);
    $estudiantes = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $estudiantes[] = $row;
        }
    }
    return $estudiantes;
}

// Función para cerrar la conexión
function cerrarConexion() {
    global $conn;
    $conn->close();
}
?>

