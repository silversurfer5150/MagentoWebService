<?php
error_reporting(E_ALL);

ini_set('display_errors', '1');


// assuming that your script file is located in magmi/integration/scripts/somedirectory/myscript.php,
// include "magmi_defs.php" , once done, you will be able to use any magmi includes without specific path.
require_once("../../inc/magmi_defs.php");

//Datapump include
require_once("magmi_datapump.php");

// create a Product import Datapump using Magmi_DatapumpFactory

echo "web service starting....\n\n";
echo "The date is " . date("m/d/Y")."\n";
echo "The run time is " . date("h:i:sa")."\n\n";

class magentoWebService {
		
	public function runDataPump($serviceType){
			
		$dp=Magmi_DataPumpFactory::getDataPumpInstance("productimport");	
		switch($serviceType){
			// Loads the Products via http from a live xml feed
			case "httpCatalog":
				// 	Begin import session with a profile & running mode, here profile is "default" & running mode is "create".
				// 	Available modes: "create" creates and updates items, "update" updates only, "xcreate creates only.
				// Important: for values other than "default" profile has to be an existing magmi profile 
				$dp->beginImportSession("default","create");
				// Get URL resource
				$url = 'https://www.targetcomponents.co.uk/datafeed/download.asp?account=WEY00001&feedid=4915';

				echo "starting http request...loading xml data from feed\n";

				$xml = simpleXML_load_file($url) or die(error_log("NOTICE OF FAILURE OF WEBSERVICE:\nThe webservice has failed due to the xml content being unavailable at the below url: \n\n https://www.targetcomponents.co.uk/datafeed/download.asp?account=WEY00001&feedid=4915", 1, "will@weys.co.uk,ben@weys.co.uk"));
				echo "data ready...looping to prep for Magmi DataPump\n";
				$price = 0;
				foreach($xml->children() as $item) { 
					if($item->name){
						$price = $item->price;  // Get priginal price from feed
						$costPrice = $item->price; // get price again
						$price = $price + (($item->price/ 100) * 30); // add percentage 30% to price
						$allProducts=array("name" =>$item->name,
									"sku" =>  "tg_".$item->stockcode,
									"ean" =>  $item->ean,
									"mpn" =>  $item->mpn,
									"price" =>$price,  // insert price with margin
									"cost" =>$costPrice, // insert original price
									"categories" => "/".$item->categorypath,
									"brand" => $item->brand,
									"attribute_set" =>"Default",
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
									"status" => ($item->qty > 0 && $price >0) ? 1 : 2
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
				$dp->beginImportSession("default","create");
				// Get URL resource
				$url = 'https://www.targetcomponents.co.uk/datafeed/download.asp?account=WEY00001&feedid=5018';
				echo "starting http request...loading xml data from feed\n";

				// load the stock check feed
				$xml = simpleXML_load_file($url) or die(error_log("NOTICE OF FAILURE OF WEBSERVICE:\nThe webservice has failed due to the xml inventory content being unavailable at the below url: \n\n https://www.targetcomponents.co.uk/datafeed/download.asp?account=WEY00001&feedid=5018", 1, "will@weys.co.uk, ben@weys.co.uk"));
				echo "data ready...looping to prep for Magmi DataPump\n";

				// loop the feed and take price, quantity information relative to sku
				$price = 0;
				foreach($xml->children() as $item) { 
					if($item->name){
					$price = $item->price;
					$costPrice = $item->price;
					$price += (($item->price/ 100) * 30);
					$allProducts=array("name" =>$item->name,
								"sku" =>  $item->ean,
								"price" =>$price,
								"cost" =>$costPrice,
								"attribute_set" =>"Default",
								"store" =>"admin",
								"_attribute_set" =>"Default",
								"_type" =>"simple",
								"_root_category" =>"HOME",
								"_product_websites" =>"base",
								"qty" =>$item->qty,
								"is_in_stock" => ($item->qty > 0) ? 1 : 0,
								"status" => ($item->qty > 0) ? 1 : 2
								);
					}
				} 
				echo "Injecting Data into Magento DataBase\n";
				$dp->ingest($allProducts);
				// End import Session
				$dp->endImportSession();
				echo"inventory\n";
			break;
			default:
			break;
		}
     }
}

$webService= new magentoWebService();
echo $argv[1]."\n";


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
	default:
	break;
}


$webService->runDataPump("httpCatalog");

echo "web service complete\n";
//Save string to log, use FILE_APPEND to append.
$log = "Web Service Sucessfully run at ".date("h:i:sa")." on ".date("m/d/Y")."\n\nCheck Latest Content in Magento Store to confirm\n\nService Type: ".$argv[1]." \n\n\n\n";
file_put_contents('./runLogs/log_'.date("j.n.Y").'.txt', $log, FILE_APPEND);

?>