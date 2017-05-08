<?php
/************ Magento Web Service ****************/
/************ written by W.Edwards ***************/
/************ @weys 2017 *************************/
/************ Magento Web Service ****************/

// Enable errors and warnings for debugging
error_reporting(E_ALL);
ini_set('display_errors', '1');


// assuming that your script file is located in magmi/integration/scripts/,
// include "magmi_defs.php" , once done, you will be able to use any magmi includes without specific path.
require_once("../../inc/magmi_defs.php");

//Datapump include
require_once("magmi_datapump.php");

// create a Product import Datapump using Magmi_DatapumpFactory

echo "web service starting....\n\n";
echo "The date is " . date("m/d/Y")."\n";
echo "The run time is " . date("h:i:sa")."\n\n";

// Main Web Service Class
class magentoWebService {
   
    public $JsonConfig = [];
    public function setUpConfig(){
        $json = file_get_contents("./weysApp/config.json");
        $this->JsonConfig = json_decode($json, true);
        $this->JsonConfig = $this->JsonConfig['data'];
        print_r($this->JsonConfig);
    }
	public function runDataPump($serviceType){
			
		$dp=Magmi_DataPumpFactory::getDataPumpInstance("productimport");	
		switch($serviceType){
			// Loads the Products via http from a live xml feed - this is passed as in Args array
			case "httpCatalog":
			    
				// 	Begin import session with a profile & running mode, here profile is "default" & running mode is "create".
				// 	Available modes: "create" creates and updates items, "update" updates only, "xcreate creates only.
				// Important: for values other than "default" profile has to be an existing magmi profile 
				
				$dp->beginImportSession("default","create");
				
				// Get URL resource
				$url = 'https://www.targetcomponents.co.uk/datafeed/download.asp?account=WEY00001&feedid=4915';
				echo "starting http request...loading xml data from feed\n";

                // Use simpleXML_load_file to convert feed xml to local XML tree and Email administrators if the url fails and data is unavailable 
				$xml = simpleXML_load_file($url) or die(error_log("NOTICE OF FAILURE OF WEBSERVICE:\nThe webservice has failed due to the xml content being unavailable at the below url: \n\n https://www.targetcomponents.co.uk/datafeed/download.asp?account=WEY00001&feedid=4915", 1, "will@weys.co.uk,ben@weys.co.uk"));
				
				// Loop the xml and Map for Magmi
				/** THIS PART WILL BE CUSTOM DEPENDING ON SUPPLIER'S DATA STRUCTURE ***/
				/** However the fields to be mapped to must be the correct Magento fields***/
				echo "data ready...looping to prep for Magmi DataPump\n";
				$price = 0;
				foreach($xml->children() as $item) { 
					if($item->name){
						$costPrice = $item->price; // store cost price
						$price = ceil((float)$costPrice);
						$margin = $this->getPriceRule($costPrice); // get margin rule for price range
						$margin =  ($margin/100) * $price; // calculate price plus margin, round up to nearest pound and - 0.1 to for .99 pricing scheme
						$price += $margin;
						$profit = ($price - $costPrice); // profit 
						$allProducts=array("name" =>$item->name,
									"sku" =>  "tg_".$item->stockcode,
									"ean" =>  $item->ean,
									"mpn" =>  $item->mpn,
									"price" =>$price,  // insert price with margin
									"cost" =>$costPrice, // insert original cost
									"profit" => $profit, // our profit
									"margin" => $margin, // our margin
									"categories" => "/".$item->categorypath,
									"brand" => $item->brand,
									"store" =>"admin",
									"description" => $item->description,
									"short_description" => $item->short_description,
									"_attribute_set" =>"Default",
									"_type" =>"simple",
									"weight" =>$item->weight,
									"image" => strtolower($item->image),
									"small_image" => strtolower($item->small_image),
									"thumbnail" => strtolower($item->thumbnail),
									"_root_category" =>"HOME",
									"visibility" =>4,
									"_product_websites" =>"base",
									"qty" =>$item->qty,
									"is_in_stock" => ($item->qty > 0) ? 1 : 0,
									"status" => ($item->qty > 0 && $price >0 && $price < 400) ? 1 : 2
									);
						$dp->ingest($allProducts);
					}
				} 
				// End import Session
				$dp->endImportSession();
			break;
			// Loads the Products from a local CSV file - ( many suppliers offer an automated file transfer of .csv catalog )
			case "csvCatalog":
				$dp->beginImportSession("default","create");
				$dp->endImportSession();
				echo"csv\n";
			break;
			// A function to check the inventory - this is run more regularly to ensure stock is in sync through the day			
			case "inventory":
                // 	This time we are checking the stock.
                $dp->beginImportSession("default","create");
                // Get URL resource
                $url = 'https://www.targetcomponents.co.uk/datafeed/download.asp?account=WEY00001&feedid=5116';

                echo "starting http request...loading xml data from feed\n";

                $xml = simpleXML_load_file($url) or die(error_log("NOTICE OF FAILURE OF WEBSERVICE:\nThe webservice has failed due to the xml content being unavailable at the below url: \n\n https://www.targetcomponents.co.uk/datafeed/download.asp?account=WEY00001&feedid=4915", 1, "will@weys.co.uk,ben@weys.co.uk"));
                echo "data ready...looping to prep for Magmi DataPump now\n";
                $price = 0;
                foreach($xml->children() as $item) {
                    if($item->sku){
						$costPrice = $item->price; // store cost price
						$price = ceil((float)$costPrice);
						$margin = $this->getPriceRule($costPrice); // get margin rule for price range
						$margin =  ($margin/100) * $price; // calculate price plus margin, round up to nearest pound and - 0.1 to for .99 pricing scheme
						$price += $margin;
						$profit = ($price - $costPrice); // profit 
                        $allProducts=array(
                            "sku" =>  "tg_".$item->sku,
                            "price" =>$price,  // insert price with margin
                            "cost" =>$costPrice, // insert original price
                            "profit" => $profit, // our profit
                            "margin" => $margin, // our margin
                            "store" =>"admin",
                            "_attribute_set" =>"Default",
                            "_type" =>"simple",
                            "_root_category" =>"HOME",
                            "visibility" =>4,
                            "_product_websites" =>"base",
                            "qty" =>$item->qty,
                            "is_in_stock" => ($item->qty > 0) ? 1 : 0,
                            "status" => ($item->qty > 0 && $price >0 && $price < 400) ? 1 : 2
                        );
                        $dp->ingest($allProducts);
                    }
                }
                // End import Session
                $dp->endImportSession();
			break;
			case "testDec":
			echo "testing decimal integrity\n";
		                $url = 'https://www.targetcomponents.co.uk/datafeed/download.asp?account=WEY00001&feedid=4915';	
		                echo "starting http request...loading xml data from feed\n";	
		                $xml = simpleXML_load_file($url);
		                $i = 0;
         			 $price = 0;
		                foreach($xml->children() as $item) {
		                if($i <10){
		                    $cost = $item->price;
		                    $margin = $this->getPriceRule($cost);
		                    echo $margin."\n\n";
		                   /*
		                $item->price = "999.23";
		                echo "The original price".(float)$item->price;
		                echo "The original price rounded up".ceil((float)$item->price)."\n";
		                $price = (30/100) * ceil((float)$item->price);
		                echo "The percentage of the price ".$price; */
		                }
		                $i++;
		                }

			break;
			default:
			break;
		}
     }

    // This function uses the JSON config data structure to compare prices to our ruleset to determine which profit margin to apply
    public function getPriceRule($price) {
        // Load into local arry variable from config
        $jsonArray = $this->JsonConfig;
        // length for loop
        $length = sizeof($jsonArray);
        
        // loop main config array
        for($i=0;$i <$length; $i++) {
            // loop inner associative config array 
           foreach($jsonArray[$i] as $key => $rule){
               // first time minumum is 0
                if($i===0){
                    if($key == 'val'){
                        if($price >0 && $price < $rule){
                           // echo "the price is --- : ".$price." therefore rule ".$i." is chosen \n\n";
                            return $jsonArray[$i]['margin'];
                        }
                    }
                }
               else{
                   // otherwise the minimum is the previous upper limit
                   if($key == 'val') {
                    //echo $rule."<br/>";
                        if($price > $jsonArray[$i-1]['val'] && $price < $rule){
                           // echo "the price is --- : ".$price." therefore rule ".$i." is chosen \n\n";
                            return $jsonArray[$i]['margin'];
                        } 
                   }
               }
           }
        }
    }
}


// Instantiate Web Service object
$webService= new magentoWebService();
echo "Selected Mode to run is: ".$argv[1]."\n";
$webService->setUpConfig();

switch($argv[1]){
	// Loads the Products via http from a live xml feed
	case "httpCatalog":
		$webService->runDataPump("httpCatalog");
	break;
	// Loads the Products from a csv file
	case "csvCatalog":
		$webService->runDataPump("csvCatalog");
	break;
	// Updates the inventory via live xml feed
	case "inventory":
		$webService->runDataPump("inventory");
	break;
	case "testDec":
		$webService->runDataPump("testDec");
	break;
	default:
	break;
}


echo "web service complete\n";
//Save string to log, use FILE_APPEND to append.
$log = "Web Service Sucessfully run at ".date("h:i:sa")." on ".date("m/d/Y")."\n\nCheck Latest Content in Magento Store to confirm\n\nService Type: ".$argv[1]." \n\n\n\n";
file_put_contents('./runLogs/log_'.date("j.n.Y").'.txt', $log, FILE_APPEND);

?>