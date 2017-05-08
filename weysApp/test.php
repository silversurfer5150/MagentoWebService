<?php
echo "testing logic"."<br/>";

$string = file_get_contents("config.json");
$jsonArray = json_decode($string, true);
$jsonArray = $jsonArray['data'];
$length = sizeof($jsonArray);
$price = 410.33;
//print_r($jsonArray);
for($i=0;$i <$length; $i++) {
   foreach($jsonArray[$i] as $key => $rule){
       //echo $key." : ".$rule."<br/>";
        if($i===0){
            if($key == 'val'){
                if($price >0 && $price < $rule){
                    echo "the price is: ".$price." therefore rule ".$i." is chosen <br/>";
                }
            }
        }
       else{
           if($key == 'val') {
            echo $rule."<br/>";
            echo $jsonArray[$i-1]['val']."<br/>";
                if($price > $jsonArray[$i-1]['val'] && $price < $rule){
                    echo "the price is: ".$price." therefore rule ".$i." is chosen <br/>";
                } 
           }
       }
   }
}

?> 