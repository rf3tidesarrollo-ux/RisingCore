<?php  
if(isset($_GET['id']) && !empty($_GET['id'])) {
    
    include_once '../../../Conexion/BD.php';
    
    $id = $_GET['id'];
    $id = $Con->real_escape_string($id);
    
    $stmt = $Con->prepare("UPDATE registro_merma SET activo_m = 0 WHERE id_registro_m = ?");
    $stmt->bind_param("i", $id);
   
    if ($stmt->execute()) {
        // $Pagina="EliminarArticulo";
        // Historial($Pagina,$Con);
        header("Location: CatalogoM.php");
        exit();
    } else {
        echo '<script>swal("Error!", "¡Ha ocurrido un error!", "error");</script>';;
    }
    $stmt->close();
    $Con->close();
} else {
    header("Location: CatalogoM.php");
    exit();
}
?>  