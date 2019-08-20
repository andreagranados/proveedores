<?php
require_once('barcode.inc.php'); 
$code_number = '125689365472365458';
#new barCodeGenrator($code_number,0,'hello.gif'); 
new barCodeGenrator($code_number,0,'C:\proyectos\toba_2.6.3\proyectos\proveedores\www\prueba.gif', 190, 130, true);
?> 