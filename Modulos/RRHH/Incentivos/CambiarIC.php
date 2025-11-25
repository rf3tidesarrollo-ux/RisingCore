<?php  
if(isset($_GET['id']) && !empty($_GET['id'])) {
    
    include_once '../../../Conexion/BD.php';
    
    $es = $_GET['es'];
    $id = $_GET['id'];
    $id = $Con->real_escape_string($id);
    
    if ($es == 1) {
        $stmt = $Con->prepare("UPDATE rh_incentivos SET estado_i = 2 WHERE id_incentivo = ?");
        $stmt->bind_param("i", $id);
    }else{
        $stmt = $Con->prepare("UPDATE rh_incentivos SET estado_i = 3 WHERE id_incentivo = ?");
        $stmt->bind_param("i", $id);
    }
    
   
    if ($stmt->execute()) {
        // $Pagina="EliminarArticulo";
        // Historial($Pagina,$Con);
        header("Location: CatalogoIC.php");
        exit();
    } else {
        echo '<script>swal("Error!", "¡Ha ocurrido un error!", "error");</script>';;
    }
    $stmt->close();
    $Con->close();
} else {
    header("Location: CatalogoIC.php");
    exit();
}
?>  