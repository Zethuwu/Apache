<?php
$servername = "localhost";
$username = "root";
$password = "Default123#";
$database = "sistemas_informacion";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

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

function agregarEstudiante($no_cuenta, $nombre, $apellidos, $correo) {
    global $conn;

    $sql = "INSERT INTO estudiante (no_cuenta, nombre, apellidos, correo) VALUES ('$no_cuenta', '$nombre', '$apellidos', '$correo')";
    if ($conn->query($sql) === TRUE) {
        return "Estudiante agregado correctamente";
    } else {
        return "Error al agregar estudiante: " . $conn->error;
    }
}

function actualizarEstudiante($no_cuenta, $nombre, $apellidos, $correo) {
    global $conn;
    $sql = "UPDATE estudiante SET nombre=?, apellidos=?, correo=? WHERE no_cuenta=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $nombre, $apellidos, $correo, $no_cuenta);
    if ($stmt->execute()) {
        $mensaje = "Estudiante actualizado correctamente";
    } else {
        $mensaje = "Error al actualizar estudiante: " . $stmt->error;
    }
    //echo json_encode(["mensaje" => $mensaje]);
}

function eliminarEstudiante($no_cuenta) {
    global $conn;
    $sql = "DELETE FROM estudiante WHERE no_cuenta=$no_cuenta";
    if ($conn->query($sql) === TRUE) {
        return "Estudiante eliminado correctamente";
    } else {
        return "Error al eliminar estudiante: " . $conn->error;
    }
}


if ($_SERVER["REQUEST_METHOD"] == "GET") {

    $estudiantes = obtenerEstudiantes();
    header('Content-Type: application/json');
    echo json_encode($estudiantes);

} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {

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

    $no_cuenta_eliminar = $_GET['no_cuenta'];
    $mensaje = eliminarEstudiante($no_cuenta_eliminar);
    echo json_encode(["mensaje" => $mensaje]);
}

$conn->close();
?>


