<?php
include_once '../../../Conexion/BD.php';
$RutaCS = "../../../Login/Cerrar.php";
$RutaSC = "../../../index.php";
include_once "../../../Login/validar_sesion.php";

$imgPath = realpath('../../../Images/Rising_Logo.jpg');
$imgData = base64_encode(file_get_contents($imgPath));
$src = 'data:image/jpeg;base64,' . $imgData;
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html" charset="UTF-8" />
<style>
</style>
</head>

<style>
  body {
    font-family: DejaVu Sans, sans-serif; /* compatible con dompdf */
    font-size: 10px;
  }

  .contenedor {
    width: 100%;
    max-width: 730px; /* aprox. ancho útil en A4 menos márgenes */
    margin: 0 auto;
  table-layout: fixed;
}

  .tabla-principal {
    border-collapse: collapse;
    width: 100%;
    table-layout: fixed;
  }

  .tabla-principal td {
    border: 0.5px solid #727272ff;
    text-align: center;
    vertical-align: middle;
    width: 12.6mm;   /* 190mm ÷ 15 */
    height: 15mm;    /* 277mm ÷ 6 */
    font-size: 7pt;
    word-wrap: break-word;
  }

  .title {
    border: 1px solid gray;
    text-align: left;
    vertical-align: top;
    position: relative;
    font-size: 9pt;
    padding-bottom: 0px;
  }


.celda-num {
  border: 0.5px solid #727272ff;
  width: 48px;    /* ancho fijo */
  height: 68px;   /* alto fijo para calcular */
  padding: 0;
  position: relative;
}

.numeracion {
  position: absolute;
  top: 2px;      /* distancia desde arriba */
  right: 4px;    /* distancia desde la derecha */
  color: red;
  font-weight: bold;
  font-size: 10pt; /* ajusta según tamaño */
}

  .w364 { width: 337px; }
  .w208 { width: 186px; }
  .w104 { width: 102px; }
  .ma1 { width: 310px; }
  .ma2 { width: 256px; }
  .na1 { width: 65px; }
  .na2 { width: 95px; }
  .muelle {
    background: #aaa;
    color: white;
    font-weight: bold;
    text-align: center;
  }
  .vacio {
    background: #ebebebff;
    color: black;
    font-weight: bold;
    text-align: center;
  }

</style>

    <body>
        <div class="contenedor">
                <table width="100%" height="12" >
                    <tr height="6">
                        <td rowspan="3" style="width:90px; height:70px; padding:0; margin:0; overflow:hidden;"><img src="<?= $src ?>" width="90" height="70"/></td>
                        <td colspan="2" class="title" style="text-align:center">Mapa de Camára Fría y Check List de carga</td>
                        <td colspan="2" class="title">Elaboró:</td>
                    </tr>
                    <tr height="6">
                        <td class="title">Fecha:</td>
                        <td class="title">Embarque:</td>
                        <td class="title">PO#:</td>
                        <td class="title">Destino:</td>
                    </tr>
                    <tr height="6">
                        <td class="title" colspan="4">Instrucciones: TACHE (X) LOS PALLETS QUE SE VAN CARGANDO</td>
                    </tr>
                </table>
                <br>
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="w364"></td>
                        <td class="w104 muelle">MUELLE 3</td>
                        <td class="w208"></td>
                        <td class="w104 muelle">MUELLE 2</td>
                    </tr>
                </table>
                <table class="tabla-principal" cellspacing="0"  cellpadding="0">
                    <tr>
                        <td style="border: none"></td>
                        <td class="celda-num"><div class="numeracion">1</div></td>
                        <td class="celda-num"><div class="numeracion">2</div></td>
                        <td class="celda-num"><div class="numeracion">3</div></td>
                        <td class="celda-num"><div class="numeracion">4</div></td>
                        <td class="celda-num"><div class="numeracion">5</div></td>
                        <td class="celda-num"><div class="numeracion">6</div></td>
                        <td class="celda-num"><div class="numeracion">7</div></td>
                        <td style="background-color: rgb(210, 221, 226); border: none;"></td>
                        <td style="background-color: rgb(210, 221, 226); border: none;"></td>
                        <td class="celda-num"><div class="numeracion">8</div></td>
                        <td class="celda-num"><div class="numeracion">9</div></td>
                        <td class="celda-num"><div class="numeracion">10</div></td>
                        <td class="celda-num"><div class="numeracion">11</div></td>
                        <td class="celda-num"><div class="numeracion">12</div></td>
                    </tr>
                     <tr>
                        <td class="celda-num"><div class="numeracion">13</div></td>
                        <td class="celda-num"><div class="numeracion">14</div></td>
                        <td class="celda-num"><div class="numeracion">15</div></td>
                        <td class="celda-num"><div class="numeracion">16</div></td>
                        <td class="celda-num"><div class="numeracion">17</div></td>
                        <td class="celda-num"><div class="numeracion">18</div></td>
                        <td class="celda-num"><div class="numeracion">19</div></td>
                        <td class="celda-num"><div class="numeracion">20</div></td>
                        <td style="background-color: rgb(210, 221, 226); border: none;"></td>
                        <td style="background-color: rgb(210, 221, 226); border: none;"></td>
                        <td class="celda-num"><div class="numeracion">21</div></td>
                        <td class="celda-num"><div class="numeracion">22</div></td>
                        <td class="celda-num"><div class="numeracion">23</div></td>
                        <td class="celda-num"><div class="numeracion">24</div></td>
                        <td class="celda-num"><div class="numeracion">25</div></td>
                    </tr>
                    <tr>
                        <td class="celda-num"><div class="numeracion">26</div></td>
                        <td class="celda-num"><div class="numeracion">27</div></td>
                        <td class="celda-num"><div class="numeracion">28</div></td>
                        <td class="celda-num"><div class="numeracion">29</div></td>
                        <td class="celda-num"><div class="numeracion">30</div></td>
                        <td class="celda-num"><div class="numeracion">31</div></td>
                        <td class="celda-num"><div class="numeracion">32</div></td>
                        <td style="border: none"></td>
                        <td style="background-color: rgb(210, 221, 226); border: none;"></td>
                        <td style="background-color: rgb(210, 221, 226); border: none;"></td>
                        <td class="celda-num"><div class="numeracion">33</div></td>
                        <td class="celda-num"><div class="numeracion">34</div></td>
                        <td class="celda-num"><div class="numeracion">35</div></td>
                        <td class="celda-num"><div class="numeracion">36</div></td>
                        <td class="celda-num"><div class="numeracion">37</div></td>
                    </tr>
                    <tr>
                        <td style="background-color: rgb(210, 221, 226); border: none;"></td>
                        <td style="background-color: rgb(210, 221, 226); border: none;"></td>
                        <td style="background-color: rgb(210, 221, 226); border: none;"></td>
                        <td style="background-color: rgb(210, 221, 226); border: none;"></td>
                        <td style="background-color: rgb(210, 221, 226); border: none;"></td>
                        <td style="background-color: rgb(210, 221, 226); border: none;"></td>
                        <td style="background-color: rgb(210, 221, 226); border: none;"></td>
                        <td style="background-color: rgb(210, 221, 226); border: none;"></td>
                        <td style="background-color: rgb(210, 221, 226); border: none;"></td>
                        <td style="background-color: rgb(210, 221, 226); border: none;"></td>
                        <td class="celda-num"><div class="numeracion">38</div></td>
                        <td class="celda-num"><div class="numeracion">39</div></td>
                        <td class="celda-num"><div class="numeracion">40</div></td>
                        <td class="celda-num"><div class="numeracion">41</div></td>
                        <td class="celda-num"><div class="numeracion">42</div></td>
                    </tr>
                    <tr>
                        <td style="border: none"></td>
                        <td class="celda-num"><div class="numeracion">43</div></td>
                        <td class="celda-num"><div class="numeracion">44</div></td>
                        <td class="celda-num"><div class="numeracion">45</div></td>
                        <td class="celda-num"><div class="numeracion">46</div></td>
                        <td class="celda-num"><div class="numeracion">47</div></td>
                        <td class="celda-num"><div class="numeracion">48</div></td>
                        <td class="celda-num"><div class="numeracion">49</div></td>
                        <td class="celda-num"><div class="numeracion">50</div></td>
                        <td class="celda-num"><div class="numeracion">51</div></td>
                        <td class="celda-num"><div class="numeracion">52</div></td>
                        <td class="celda-num"><div class="numeracion">53</div></td>
                        <td class="celda-num"><div class="numeracion">54</div></td>
                        <td class="celda-num"><div class="numeracion">55</div></td>
                        <td class="celda-num"><div class="numeracion">56</div></td>
                    </tr>
                    <tr>
                        <td style="border: none"></td>
                        <td class="celda-num"><div class="numeracion">57</div></td>
                        <td class="celda-num"><div class="numeracion">58</div></td>
                        <td class="celda-num"><div class="numeracion">59</div></td>
                        <td class="celda-num"><div class="numeracion">60</div></td>
                        <td class="celda-num"><div class="numeracion">61</div></td>
                        <td class="celda-num"><div class="numeracion">62</div></td>
                        <td class="celda-num"><div class="numeracion">63</div></td>
                        <td class="celda-num"><div class="numeracion">64</div></td>
                        <td class="celda-num"><div class="numeracion">65</div></td>
                        <td class="celda-num"><div class="numeracion">66</div></td>
                        <td class="celda-num"><div class="numeracion">67</div></td>
                        <td class="celda-num"><div class="numeracion">68</div></td>
                        <td class="celda-num"><div class="numeracion">69</div></td>
                        <td class="celda-num"><div class="numeracion">70</div></td>
                    </tr>
                </table>
        </div>
        <br> <br>
        <div class="contenedor">
                <table width="100%" height="12" >
                    <tr height="6">
                        <td rowspan="3" style="width:90px; height:70px; padding:0; margin:0; overflow:hidden;"><img src="<?= $src ?>" width="90" height="70"/></td>
                        <td colspan="2" class="title" style="text-align:center">Mapa de Camára Fría y Check List de carga</td>
                        <td colspan="2" class="title">Elaboró:</td>
                    </tr>
                    <tr height="6">
                        <td class="title">Fecha:</td>
                        <td class="title">Embarque:</td>
                        <td class="title">PO#:</td>
                        <td class="title">Destino:</td>
                    </tr>
                    <tr height="6">
                        <td class="title" colspan="4">Instrucciones: TACHE (X) LOS PALLETS QUE SE VAN CARGANDO</td>
                    </tr>
                </table>
                <br>
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="w364"></td>
                        <td class="w104 muelle">MUELLE 3</td>
                        <td class="w208"></td>
                        <td class="w104 muelle">MUELLE 2</td>
                    </tr>
                </table>
                <table class="tabla-principal" cellspacing="0"  cellpadding="0">
                    <tr>
                        <td style="border: none"></td>
                        <td class="celda-num"><div class="numeracion">1</div></td>
                        <td class="celda-num"><div class="numeracion">2</div></td>
                        <td class="celda-num"><div class="numeracion">3</div></td>
                        <td class="celda-num"><div class="numeracion">4</div></td>
                        <td class="celda-num"><div class="numeracion">5</div></td>
                        <td class="celda-num"><div class="numeracion">6</div></td>
                        <td class="celda-num"><div class="numeracion">7</div></td>
                        <td style="background-color: rgb(210, 221, 226); border: none;"></td>
                        <td style="background-color: rgb(210, 221, 226); border: none;"></td>
                        <td class="celda-num"><div class="numeracion">8</div></td>
                        <td class="celda-num"><div class="numeracion">9</div></td>
                        <td class="celda-num"><div class="numeracion">10</div></td>
                        <td class="celda-num"><div class="numeracion">11</div></td>
                        <td class="celda-num"><div class="numeracion">12</div></td>
                    </tr>
                     <tr>
                        <td class="celda-num"><div class="numeracion">13</div></td>
                        <td class="celda-num"><div class="numeracion">14</div></td>
                        <td class="celda-num"><div class="numeracion">15</div></td>
                        <td class="celda-num"><div class="numeracion">16</div></td>
                        <td class="celda-num"><div class="numeracion">17</div></td>
                        <td class="celda-num"><div class="numeracion">18</div></td>
                        <td class="celda-num"><div class="numeracion">19</div></td>
                        <td class="celda-num"><div class="numeracion">20</div></td>
                        <td style="background-color: rgb(210, 221, 226); border: none;"></td>
                        <td style="background-color: rgb(210, 221, 226); border: none;"></td>
                        <td class="celda-num"><div class="numeracion">21</div></td>
                        <td class="celda-num"><div class="numeracion">22</div></td>
                        <td class="celda-num"><div class="numeracion">23</div></td>
                        <td class="celda-num"><div class="numeracion">24</div></td>
                        <td class="celda-num"><div class="numeracion">25</div></td>
                    </tr>
                    <tr>
                        <td class="celda-num"><div class="numeracion">26</div></td>
                        <td class="celda-num"><div class="numeracion">27</div></td>
                        <td class="celda-num"><div class="numeracion">28</div></td>
                        <td class="celda-num"><div class="numeracion">29</div></td>
                        <td class="celda-num"><div class="numeracion">30</div></td>
                        <td class="celda-num"><div class="numeracion">31</div></td>
                        <td class="celda-num"><div class="numeracion">32</div></td>
                        <td style="border: none"></td>
                        <td style="background-color: rgb(210, 221, 226); border: none;"></td>
                        <td style="background-color: rgb(210, 221, 226); border: none;"></td>
                        <td class="celda-num"><div class="numeracion">33</div></td>
                        <td class="celda-num"><div class="numeracion">34</div></td>
                        <td class="celda-num"><div class="numeracion">35</div></td>
                        <td class="celda-num"><div class="numeracion">36</div></td>
                        <td class="celda-num"><div class="numeracion">37</div></td>
                    </tr>
                    <tr>
                        <td style="background-color: rgb(210, 221, 226); border: none;"></td>
                        <td style="background-color: rgb(210, 221, 226); border: none;"></td>
                        <td style="background-color: rgb(210, 221, 226); border: none;"></td>
                        <td style="background-color: rgb(210, 221, 226); border: none;"></td>
                        <td style="background-color: rgb(210, 221, 226); border: none;"></td>
                        <td style="background-color: rgb(210, 221, 226); border: none;"></td>
                        <td style="background-color: rgb(210, 221, 226); border: none;"></td>
                        <td style="background-color: rgb(210, 221, 226); border: none;"></td>
                        <td style="background-color: rgb(210, 221, 226); border: none;"></td>
                        <td style="background-color: rgb(210, 221, 226); border: none;"></td>
                        <td class="celda-num"><div class="numeracion">38</div></td>
                        <td class="celda-num"><div class="numeracion">39</div></td>
                        <td class="celda-num"><div class="numeracion">40</div></td>
                        <td class="celda-num"><div class="numeracion">41</div></td>
                        <td class="celda-num"><div class="numeracion">42</div></td>
                    </tr>
                    <tr>
                        <td style="border: none"></td>
                        <td class="celda-num"><div class="numeracion">43</div></td>
                        <td class="celda-num"><div class="numeracion">44</div></td>
                        <td class="celda-num"><div class="numeracion">45</div></td>
                        <td class="celda-num"><div class="numeracion">46</div></td>
                        <td class="celda-num"><div class="numeracion">47</div></td>
                        <td class="celda-num"><div class="numeracion">48</div></td>
                        <td class="celda-num"><div class="numeracion">49</div></td>
                        <td class="celda-num"><div class="numeracion">50</div></td>
                        <td class="celda-num"><div class="numeracion">51</div></td>
                        <td class="celda-num"><div class="numeracion">52</div></td>
                        <td class="celda-num"><div class="numeracion">53</div></td>
                        <td class="celda-num"><div class="numeracion">54</div></td>
                        <td class="celda-num"><div class="numeracion">55</div></td>
                        <td class="celda-num"><div class="numeracion">56</div></td>
                    </tr>
                    <tr>
                        <td style="border: none"></td>
                        <td class="celda-num"><div class="numeracion">57</div></td>
                        <td class="celda-num"><div class="numeracion">58</div></td>
                        <td class="celda-num"><div class="numeracion">59</div></td>
                        <td class="celda-num"><div class="numeracion">60</div></td>
                        <td class="celda-num"><div class="numeracion">61</div></td>
                        <td class="celda-num"><div class="numeracion">62</div></td>
                        <td class="celda-num"><div class="numeracion">63</div></td>
                        <td class="celda-num"><div class="numeracion">64</div></td>
                        <td class="celda-num"><div class="numeracion">65</div></td>
                        <td class="celda-num"><div class="numeracion">66</div></td>
                        <td class="celda-num"><div class="numeracion">67</div></td>
                        <td class="celda-num"><div class="numeracion">68</div></td>
                        <td class="celda-num"><div class="numeracion">69</div></td>
                        <td class="celda-num"><div class="numeracion">70</div></td>
                    </tr>
                </table>
        </div>
    </body>
</html>
