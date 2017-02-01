# MagentoWebService
A Web Service which interfaces with Magento using Magmi DataPump API to do smart inventory

<p>Magmi is a tool written in order to simplify data injection into the Magento Database. If you have never used Magento before, the schema can be very complex which is why the tool was written so that you do not have to interface directly with the database.</p>

<p>The webservice I have written pulls product data from an XML live feed and formats them into an object which the Magmi DataPump API can use to inject the data in bulk. </p>


<p>I am also providing different services here to inject the data via an uploaded CSV file (incomplete) and also to do automatic updating of the inventory / stock in order to keep the quantity of items and stock status in sync with the supplier. This is intended to run more frequently and be more lightweight. (incomplete as yet)</p>

<h2>Running The Service</h2>

<p>Different tasks must run at different intervals so here you can inject the variable in the terminal command and then retrieve it from the <strong>$argv[]</strong> array.

<p>For example:</p>

<p><strong>php webservice.php "httpCatalog"</strong> or <strong>php webservice.php "inventory"</strong></p>

<p>The cron job can then be scheduled to perform different smart shop tasks at the required intervals. </p>
