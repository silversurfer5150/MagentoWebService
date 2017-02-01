# MagentoWebService
A Web Service which interfaces with Magento using Magmi DataPump API to do smart inventory

<p>Magmi is a tool written in order to simplify data injection into the Magento Database. If you have never used Magento before, the schema can be very complex which is why the tool was written so that you do not have to interface directly with the database.</p>

<p>The webservice I have written pulls product data from an XML live feed and formats them into an object which the Magmi DataPump API can use to inject the data in bulk. </p>


<p>I am also providing different services here to inject the data via an uploaded CSV file (incomplete) and also to do automatic updating of the inventory / stock in order to keep the quantity of items and stock status in sync with the supplier. This is intended to run more frequently and be more lightweight. (incomplete as yet)</p>

