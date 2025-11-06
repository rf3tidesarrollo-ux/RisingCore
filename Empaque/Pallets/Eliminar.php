<?php  
if(isset($_GET['id']) && !empty($_GET['id'])) {
    
    include_once '../../../Conexion/BD.php';
    
    $id = $_GET['id'];
    $id = $Con->real_escape_string($id);
    
    $stmt = $Con->prepare("UPDATE pallets SET activo_p = 0 WHERE id_pallet = ?");
    $stmt->bind_param("i", $id);
   
    if ($stmt->execute()) {
        // $Pagina="EliminarMezcla";
        // Historial($Pagina,$Con);
        header("Location: CatalogoP.php");
        exit();
    } else {
        echo '<script>swal("Error!", "¡Ha ocurrido un error!", "error");</script>';;
    }
    $stmt->close();
    $Con->close();
} else {
    header("Location: CatalogoP.php");
    exit();
}
?>  