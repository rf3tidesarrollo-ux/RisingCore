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
    max-width: 750px; /* aprox. ancho útil en A4 menos márgenes */
    margin: 0 auto;
}

  table {
    width: 100%;
    border-collapse: collapse;
    margin: 0 auto; /* centra en página */
  }

  .title {
    border: 1px solid gray;
    text-align: left;
    vertical-align: top;
    position: relative;
    font-size: 9pt;
    border-bottom: 1px solid black;
    padding-bottom: 3px;
  }

  .cell {
    border: 1px solid gray;
    text-align: right;
    vertical-align: top;
    position: relative;
    font-size: 9pt;
    width: 52px;
    height: 50px;
  }

  .pallet-num {
    color: #F4070B;
    font-size: 14pt;
    font-weight: bold;
    display: block;
    width: 50px;
  }

  .dato {
    font-size: 6pt;
    color: #555;
  }

  .w364 { width: 400px; }
  .w208 { width: 208px; }
  .w104 { width: 104px; }
  .muelle {
    background: #aaa;
    color: white;
    font-weight: bold;
    text-align: center;
  }

</style>

    <body>
        <div class="contenedor">
                <table height="12">
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
                <table cellspacing="0" cellpadding="0">
                    <tr>
                        <td>
                            <table cellspacing="0">
                                <tr>
                                    <td>
                                        <table cellspacing="0">
                                            <tr height="50" width="364">
                                                <td rowspan="3" class="cell"><b></b></td>
                                                <td class="pallet-num" valign="top"><b>1</b></td>
                                                <td class="cell" valign="top"><b>2</b></td>
                                                <td class="cell" valign="top"><b>3</b></td>
                                                <td class="cell" valign="top"><b>4</b></td>
                                                <td class="cell" valign="top"><b>5</b></td>
                                                <td class="cell" valign="top"><b>6</b></td>
                                                <td class="cell" valign="top"><b>7</b></td>
                                            </tr>
                                            <tr height="10">
                                                <td colspan="7" width="52" style="border: 1px solid gray;" style="background-color: rgb(210, 221, 226)" align="center"><font face="nunito,arial,verdana" size="1" color="rgba(210,221,226,1.00)"><b>dato_b1</b></font></td>
                                            </tr>
                                            <tr height="10">
                                                <td colspan="7" width="52" style="border: 1px solid gray;" align="center"><font face="nunito,arial,verdana" size="1" color="rgba(255,255,255,1.00)"><b>dato_c1</b></font></td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td style="background-color: rgb(210, 221, 226)" width="104"></td>
                                    <td width="260">
                                            <table style="margin:auto;" cellspacing="0">
                                                <tr height="50">
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">8</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">9</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">10</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">11</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">12</font></b></font></td>
                                                </tr>
                                            <tr height="10">
                                                <td colspan="5" width="52" style="border: 1px solid gray;" style="background-color: rgb(210, 221, 226)" align="center"><font face="nunito,arial,verdana" size="1" color="rgba(210,221,226,1.00)"><b>dato_b1</b></font></td>
                                            </tr>
                                            <tr height="10">
                                                <td colspan="5" width="52" style="border: 1px solid gray;" align="center"><font face="nunito,arial,verdana" size="1" color="rgba(255,255,255,1.00)"><b>dato_c1</b></font></td>
                                            </tr>
                                            </table>
                                    </td>
                                </tr>
                            </table>
                            
                            <table cellspacing="0" style="margin:auto;" width="780">
                                <tr>
                                    <td>
                                        <table style="margin:auto;" cellspacing="0">
                                            <tr height="50">
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">13</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">14</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">15</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">16</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">17</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">18</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">19</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">20</font></b></font></td>
                                            </tr>
                                            <tr height="10">
                                                <td colspan="8" width="52" style="border: 1px solid gray;" style="background-color: rgb(210, 221, 226)" align="center"><font face="nunito,arial,verdana" size="1" color="rgba(210,221,226,1.00)"><b>dato_b1</b></font></td>
                                            </tr>
                                            <tr height="10">
                                                <td colspan="8" width="52" style="border: 1px solid gray;" align="center"><font face="nunito,arial,verdana" size="1" color="rgba(255,255,255,1.00)"><b>dato_c1</b></font></td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td style="background-color: rgb(210, 221, 226)" width="104">
                                    </td>
                                    <td width="260">
                                        <table style="margin:auto;" cellspacing="0">
                                            <tr height="50">
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">21</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">22</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">23</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">24</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">25</font></b></font></td>
                                            </tr>
                                            <tr height="10">
                                                <td colspan="5" width="52" style="border: 1px solid gray;" style="background-color: rgb(210, 221, 226)" align="center"><font face="nunito,arial,verdana" size="1" color="rgba(210,221,226,1.00)"><b>dato_b1</b></font></td>
                                            </tr>
                                            <tr height="10">
                                                <td colspan="5" width="52" style="border: 1px solid gray;" align="center"><font face="nunito,arial,verdana" size="1" color="rgba(255,255,255,1.00)"><b>dato_c1</b></font></td>
                                            </tr>
                                        </table>
                                </td>
                                </tr>
                            </table>
                            
                            <table cellspacing="0" style="margin:auto;" width="780">
                                <tr>
                                    <td>
                                        <table style="margin:auto;" cellspacing="0">
                                            <tr height="50">
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">26</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">27</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">28</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">29</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">30</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">31</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">32</font></b></font></td>
                                                <td width="52" rowspan="3">
                                                        <font color="white" face="nunito,arial,verdana" size="1"><b>dato_bla</b></font></td></td>
                                            </tr>
                                            <tr height="10">
                                                <td colspan="7" width="52" style="border: 1px solid gray;" style="background-color: rgb(210, 221, 226)" align="center"><font face="nunito,arial,verdana" size="1" color="rgba(210,221,226,1.00)"><b>dato_b1</b></font></td>
                                            </tr>
                                            <tr height="10">
                                                <td colspan="7" width="52" style="border: 1px solid gray;" align="center"><font face="nunito,arial,verdana" size="1" color="rgba(255,255,255,1.00)"><b>dato_c1</b></font></td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td style="background-color: rgb(210, 221, 226)" width="104"></td>
                                    <td width="260">
                                        <table style="margin:auto;" cellspacing="0">
                                            <tr height="50">
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">33</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">34</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">35</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">36</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">37</font></b></font></td>
                                            </tr>
                                            <tr height="10">
                                                <td colspan="5" width="52" style="border: 1px solid gray;" style="background-color: rgb(210, 221, 226)" align="center"><font face="nunito,arial,verdana" size="1" color="rgba(210,221,226,1.00)"><b>dato_b1</b></font></td>
                                            </tr>
                                            <tr height="10">
                                                <td colspan="5" width="52" style="border: 1px solid gray;" align="center"><font face="nunito,arial,verdana" size="1" color="rgba(255,255,255,1.00)"><b>dato_c1</b></font></td>
                                            </tr>
                                        </table>
                                </td>
                                </tr>
                            </table>
                            
                            <table cellspacing="0" style="margin:auto;" width="780">
                                <tr>
                                    <td height="70" width="520" style="background-color: rgb(210, 221, 226)" rowspan="3"></td>
                                        <td width="260">
                                            <table cellspacing="0">
                                                <tr height="50">
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">38</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">39</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">40</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">41</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">42</font></b></font></td>
                                                </tr>
                                            <tr height="10">
                                                <td colspan="5" width="52" style="border: 1px solid gray;" style="background-color: rgb(210, 221, 226)" align="center"><font face="nunito,arial,verdana" size="1" color="rgba(210,221,226,1.00)"><b>dato_b1</b></font></td>
                                            </tr>
                                            <tr height="10">
                                                <td colspan="5" width="52" style="border: 1px solid gray;" align="center"><font face="nunito,arial,verdana" size="1" color="rgba(255,255,255,1.00)"><b>dato_c1</b></font></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        
                            <table width="775"cellspacing="0" style="margin:auto;">
                                <tr>
                                    <td>
                                        <table width="780" cellspacing="0" style="margin:auto;">
                                            <tr height="50">
                                                <td width="52" rowspan="3">
                                                        <font color="white" face="nunito,arial,verdana" size="1"><b>dato_bla</b></font></td></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">43</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">44</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">45</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">46</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">47</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">48</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">49</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">50</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">51</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">52</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">53</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">54</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">55</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">56</font></b></font></td>
                                            </tr>
                                            <tr height="10">
                                                <td colspan="14" width="52" style="border: 1px solid gray;" style="background-color: rgb(210, 221, 226)" align="center"><font face="nunito,arial,verdana" size="1" color="rgba(210,221,226,1.00)"><b>dato_b1</b></font></td>
                                            </tr>
                                            <tr height="10">
                                                <td colspan="14" width="52" style="border: 1px solid gray;" align="center"><font face="nunito,arial,verdana" size="1" color="rgba(255,255,255,1.00)"><b>dato_c1</b></font></td>
                                            </tr>
                                    </td>
                                </tr>
                            </table>
                            
                            <table width="780"cellspacing="0">
                                                    <tr height="50">
                                                        <td width="52" rowspan="3">
                                                                <font color="white" face="nunito,arial,verdana" size="1"><b>dato_bla</b></font></td></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">57</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">58</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">59</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">60</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">61</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">62</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">63</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">64</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">65</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">66</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">67</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">68</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">69</font></b></font></td>
                                                <td width="52" height="50" style="border: 1px solid gray;" align="right" valign="top"><font face="nunito,arial,verdana" size="5"><b><font color="#F4070B">70</font></b></font></td>
                                                    </tr>
                                            <tr height="10">
                                                <td colspan="14" width="52" style="border: 1px solid gray;" style="background-color: rgb(210, 221, 226)" align="center"><font face="nunito,arial,verdana" size="1" color="rgba(210,221,226,1.00)"><b>dato_b1</b></font></td>
                                            </tr>
                                            <tr height="10">
                                                <td colspan="14" width="52" style="border: 1px solid gray;" align="center"><font face="nunito,arial,verdana" size="1" color="rgba(255,255,255,1.00)"><b>dato_c1</b></font></td>
                                            </tr>
                                            </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
        </div>
    </body>
</html>
