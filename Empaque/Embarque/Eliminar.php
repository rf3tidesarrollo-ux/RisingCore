<?php  
if(isset($_GET['id']) && !empty($_GET['id'])) {
    
    include_once '../../../Conexion/BD.php';
    
    $id = $_GET['id'];
    $id = $Con->real_escape_string($id);
    
    $stmt = $Con->prepare("UPDATE embarques_pallets SET activo_em = 0 WHERE id_embarque = ?");
    $stmt->bind_param("i", $id);
   
    if ($stmt->execute()) {
        // $Pagina="EliminarArticulo";
        // Historial($Pagina,$Con);
        header("Location: CatalogoE.php");
        exit();
    } else {
        echo '<script>swal("Error!", "¡Ha ocurrido un error!", "error");</script>';;
    }
    $stmt->close();
    $Con->close();
} else {
    header("Location: CatalogoE.php");
    exit();
}
?>  