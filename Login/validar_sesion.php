<?php
// =======================================
// Configuración inicial
// =======================================
ob_start();
session_start();
$Inactividad = 3600; // Tiempo de inactividad en segundos (1 hora)

// =======================================
// Función para cerrar sesión
// =======================================
function cerrar_sesion($RutaCS) {
    session_destroy();
    ob_end_flush();
    header("Location: $RutaCS");
    exit;
}

// =======================================
// Variables de sesión
// =======================================
$Name      = $_SESSION['Name'] ?? null;
$Password  = $_SESSION['Password'] ?? null;
$TipoRol   = $_SESSION['Rol'] ?? null;
$Titular   = $_SESSION['Titular'] ?? null;
$Modulo    = $_SESSION['Modulo'] ?? null;
$Sede      = $_SESSION['Sede'] ?? null;
$CodigoS   = $_SESSION['CodigoS'] ?? null;
$ID        = $_SESSION['ID'] ?? null;

// =======================================
// Detectar peticiones AJAX
// =======================================
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
          strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

// =======================================
// Comprobar timeout
// =======================================
if (isset($_SESSION["Timeout"])) {
    $TiempoSession = time() - $_SESSION["Timeout"];
    if ($TiempoSession > $Inactividad) {
        session_destroy();
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['expired' => true]);
            exit;
        } else {
            cerrar_sesion($RutaCS);
        }
    }
}
$_SESSION["Timeout"] = time();

// =======================================
// Evitar acceso sin sesión en páginas normales
// =======================================
if (!$isAjax && (empty($Name) || empty($Password) || empty($TipoRol) || empty($Titular) || empty($Modulo))) {
    header("Location: $RutaSC");
    exit;
}

// =======================================
// Endpoint AJAX para verificar sesión manualmente
// =======================================
if (isset($_GET['check_session']) && $_GET['check_session'] == 1) {
    header('Content-Type: application/json');
    $expired = empty($_SESSION['ID']); // true si sesión expiró
    echo json_encode(['expired' => $expired]);
    exit;
}

// =======================================
// Funciones de permisos
// =======================================
function TienePermiso($ID, $NM, $TP, $Con) {
    $stmt = $Con->prepare('SELECT 1 
        FROM permisos_usuarios pu 
        JOIN modulos m ON pu.id_modulo_u = m.id_seccion
        WHERE pu.id_usuario_u = ? 
        AND m.nombre_seccion = ? 
        AND pu.id_permisos_u = ?');
    $stmt->bind_param('isi', $ID, $NM, $TP);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $Permiso = $resultado->num_rows > 0;
    $stmt->close();
    return $Permiso;
}

function PermisoModulo($ID, $NM, $Con) {
    $stmt = $Con->prepare('SELECT 1
        FROM permisos_usuarios pu
        JOIN modulos m ON pu.id_modulo_u = m.id_seccion
        WHERE pu.id_usuario_u = ?
        AND m.nombre_seccion LIKE ?');
    $stmt->bind_param('is', $ID, $NM);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $Acceso = $resultado->num_rows > 0;
    $stmt->close();
    return $Acceso;
}
?>
