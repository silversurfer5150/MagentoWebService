<?php
if(isset($_POST['myjson'])) {
  saveConfig();
}

function saveConfig(){
$info = $_POST['myjson'];
  $file = fopen('config.json','w+');
   fwrite($file, $info);
   fclose($file);
}

?>