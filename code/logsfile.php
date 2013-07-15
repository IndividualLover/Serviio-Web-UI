<?php
$dosya = fopen ("../logfile.txt" , 'w'); //File creating processor

$data=$_POST['data'];
$yaz="deneme.com"; //file content.
fwrite ( $dosya , $data ) ; 
fclose ($dosya);
return $data;
?>