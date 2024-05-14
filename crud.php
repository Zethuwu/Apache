<?php
// Datos de conexión a la base de datos
$servername = "localhost";
$username = "root";
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

// Función para agregar un estudiante
function agregarEstudiante($no_cuenta, $nombre, $apellidos, $correo) {
    global $conn;

    $sql = "INSERT INTO estudiante (no_cuenta, nombre, apellidos, correo) VALUES ('$no_cuenta', '$nombre', '$apellidos', '$correo')";
    if ($conn->query($sql) === TRUE) {
        return "Estudiante agregado correctamente";
    } else {
        return "Error al agregar estudiante: " . $conn->error;
    }
}

// Función para actualizar un estudiante
function actualizarEstudiante($no_cuenta, $nombre, $apellidos, $correo) {
    global $conn;
    // Preparar la consulta SQL con una consulta preparada
    $sql = "UPDATE estudiante SET nombre=?, apellidos=?, correo=? WHERE no_cuenta=?";
    $stmt = $conn->prepare($sql);
    // Vincular los parámetros
    $stmt->bind_param("sssi", $nombre, $apellidos, $correo, $no_cuenta);
    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Retornar un mensaje JSON indicando que la actualización fue exitosa
        $mensaje = "Estudiante actualizado correctamente";
    } else {
        // Retornar un mensaje JSON con el error en caso de fallo
        $mensaje = "Error al actualizar estudiante: " . $stmt->error;
    }

    // Combinar los mensajes en uno solo antes de enviarlos
    //echo json_encode(["mensaje" => $mensaje]);
}



// Función para eliminar un estudiante
function eliminarEstudiante($no_cuenta) {
    global $conn;
    $sql = "DELETE FROM estudiante WHERE no_cuenta=$no_cuenta";
    if ($conn->query($sql) === TRUE) {
        return "Estudiante eliminado correctamente";
    } else {
        return "Error al eliminar estudiante: " . $conn->error;
    }
}

// Manejar las solicitudes de acuerdo al método
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Si la solicitud es GET, obtenemos todos los estudiantes
    $estudiantes = obtenerEstudiantes();
    header('Content-Type: application/json');
    echo json_encode($estudiantes);
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Si la solicitud es POST, intentamos agregar un estudiante
    $data = json_decode(file_get_contents("php://input"), true);
    $mensaje = agregarEstudiante($data['no_cuenta'], $data['nombre'], $data['apellidos'], $data['correo']);
    echo json_encode(["mensaje" => $mensaje]);
} elseif ($_SERVER["REQUEST_METHOD"] == "PUT") {
    $data = json_decode(file_get_contents("php://input"), true);

    $no_cuenta = $data['no_cuenta'] ?? null;
    $nombre = $data['nombre'] ?? null;
    $apellidos = $data['apellidos'] ?? null;
    $correo = $data['correo'] ?? null;

    $sql = "UPDATE estudiante SET nombre=?, apellidos=?, correo=? WHERE no_cuenta=?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {    
        $stmt->bind_param("sssi", $nombre, $apellidos, $correo, $no_cuenta);
        
        if ($stmt->execute()) {
            echo json_encode(["mensaje" => "Estudiante actualizado correctamente"]);
        } else {
            echo json_encode(["mensaje" => "Error al actualizar estudiante: " . $stmt->error]);
        }
    } else {
        echo json_encode(["mensaje" => "Error de preparación de consulta: " . $conn->error]);
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "DELETE") {
    // Si la solicitud es DELETE, intentamos eliminar un estudiante
    $no_cuenta_eliminar = $_GET['no_cuenta'];
    $mensaje = eliminarEstudiante($no_cuenta_eliminar);
    echo json_encode(["mensaje" => $mensaje]);
}

// Cerrar la conexión
$conn->close();
?>


