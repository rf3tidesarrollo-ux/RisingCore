<?php
    /*
    $Host = "localhost";
    $User = "id20759305_master_chief";
    $Pass = "JErgL%$[v?aD7pjF";
    $DB = "id20759305_bd_inventario";
    */
    $Host = "localhost";
    $User = "root";
    $Pass = "";
    $DB = "bd_risingfarms3";
    

    $Con = mysqli_connect($Host, $User, $Pass, $DB) or die ("Problema al conectar");
           mysqli_select_db($Con,$DB) or die ("Problema al conectar BD");
    
    /* check connection */
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
    
    /* change character set to utf8 */
    if (!$Con->set_charset("utf8")) {
        printf("Error loading character set utf8: %s\n", $Con->error);
    }

    /* RESPALDO */
    date_default_timezone_set('America/El_Salvador');
    $FechaR=date("Y-m-d");
    $HoraR=date("H:i:s");

    $NombreSQL = $DB . '_' . $FechaR . '.sql.gz';

    $dump = "mysqldump -h'$Host' -u'$User' -p'$Pass' $DB > $NombreSQL";
    //exec($dump);

    //HISTORIAL DE SESIONES
    /*function Historial($History,$Con){
        $Usuario=$_SESSION['Name'];
        date_default_timezone_set('America/El_Salvador');
        $Fecha=date("d-m-Y h:i:s");
    
        function getRealIP() {
            if (isset($_SERVER["HTTP_CLIENT_IP"])){
                return $_SERVER["HTTP_CLIENT_IP"];
            }elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
                return $_SERVER["HTTP_X_FORWARDED_FOR"];
            }elseif (isset($_SERVER["HTTP_X_FORWARDED"])){
                return $_SERVER["HTTP_X_FORWARDED"];
            }elseif (isset($_SERVER["HTTP_FORWARDED_FOR"])){
                return $_SERVER["HTTP_FORWARDED_FOR"];
            }elseif (isset($_SERVER["HTTP_FORWARDED"])){
                return $_SERVER["HTTP_FORWARDED"];
            }else{
                return $_SERVER["REMOTE_ADDR"];
            }
        }
        $IPv6=getRealIP();
        $array = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $IPv6,true));
        $City = $array->geoplugin_city;
        $State = $array->geoplugin_regionName;
        $Country = $array->geoplugin_countryName;
        $Continet = $array->geoplugin_continentName;
        /* 
        $stmt = $Con->prepare('SELECT @usuario := ?');
        $stmt->bind_param('s', $Usuario);
        $stmt->execute();
        $stmt->close(); 
        
        $stmt = $Con->prepare("INSERT INTO historial (usuario, fecha, continente, pais, estado, ciudad, ipv6, pagina) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssssss', $Usuario, $Fecha, $Continet, $Country, $State, $City, $IPv6, $History);
        $stmt->execute();
        $stmt->close(); 
    } */
?>