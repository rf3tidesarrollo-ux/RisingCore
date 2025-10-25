<?php  
if(isset($_GET['id']) && !empty($_GET['id'])) {
    
    include_once '../../../Conexion/BD.php';
    
    $id = $_GET['id'];
    $id = $Con->real_escape_string($id);
    
    $stmt = $Con->prepare("UPDATE rh_tipos_horarios SET estado_h = 0 WHERE id_thorario = ?");
    $stmt->bind_param("i", $id);
   
    if ($stmt->execute()) {
        // $Pagina="EliminarArticulo";
        // Historial($Pagina,$Con);
        header("Location: CatalogoH.php");
        exit();
    } else {
        echo '<script>swal("Error!", "¡Ha ocurrido un error!", "error");</script>';;
    }
    $stmt->close();
    $Con->close();
} else {
    header("Location: CatalogoH.php");
    exit();
}
?>  