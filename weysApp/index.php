<!DOCTYPE html>
<html lang="en">
<head>
  <title>Weys Web Service Manager</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="http://www.weys.co.uk/media/favicon/default/weys-logo-favicon.gif" type="image/x-icon" />
  <link rel="shortcut icon" href="http://www.weys.co.uk/media/favicon/default/weys-logo-favicon.gif" type="image/x-icon" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="style.css">
  <script src="webServiceManager.js"></script>
</head>
<body>
<!-- main container -->
<div class="container-fluid">
  <!-- start outer row -->
  <div class="row content">
    <!-- start outer col sidebar -->
    <div class="col-sm-3 sidenav">
    <br/>
     <img src="weys.png" alt="weys logo" />
      <h2>Web Service Manager</h2><br/><br/>
      <ul class="nav nav-pills nav-stacked">
        <li class="active"><a href="#home">Home</a></li>
        <li><a href="#pricing">Pricing</a></li>
        <li><a href="#productOverride">Product Override</a></li>
      </ul><br>
    </div>
    <!-- end sidebar -->
    <!-- start master cols main -->
    <div class="col-sm-9">
      <br/><br/><h4><small>WELCOME TO WEB SERVICE MANAGER</small></h4>
      <hr>
      <!-- the Home tab -->
      <div id="home" class="section">
        <h5><span class="glyphicon glyphicon-time"></span></h5><br/><br/>
        <h2>HOW DOES IT WORK?</h2>
        <p>This service is designed to allow the store admin to manage exactly how the pricing margins are applied during the loading 
        of the store inventory from Weys web service.</p>
        <br/><h3>Inituitive Parametric Control Over Pricing</h3><hr>
        <p>In short, the web service uses a config.JSON (JavaScript Object Notation) file in order to apply pricing margins within a given price range.</p>
        <p>This application allows you to select which price ranges you require and to apply a percentage to be added automatically to all products within that range.</p>
        <br><br>
      </div>
      <!-- the Pricing Rules tab -->
      <div id="pricing" class="section">
        <h2>Pricing Rule-set</h2>
        <div class="col-sm-9 nopadding"><p>Select the required price ranges below and apply the percentage profit margin to each:</p>
          <p class="rangeError text-danger">Range Error! You cannot crossover ranges. Please adjust and try again.</p>
          <p class="saveSuccess text-success">Success! You have successfully saved and updated the pricing rules.</p>
        </div>
        <div class="col-sm-2 pull-right">
          <div class="btn-group btn-group-lg">
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
          <i class="fa fa-circle-o-notch fa-spin"></i>
        </div>
        <br/><br/>
        <br/>
        <form class="form-inline">
          <div class="form-group sliderSection">
            <span class="label label-danger"><label for="case1">£ Price Range : </label></span>
            <input type="text" id="case1" readonly class="case"><br/>
            <div id="range1" class="range"></div>                   
            <label for="percentage1">Margin: &nbsp;</label>
            <input id="percentage1" type="number" min="0" maxlength="3" class="form-control percentInput">
            <span class="greenPercent"></span>
            <br/><br/>
          </div>
          <div class="form-group sliderSection">
                  <span class="label label-danger"><label for="case2">£ Price Range : </label></span>
                  <input type="text" id="case2" readonly class="case"><br/>
                  <div id="range2" class="range"></div>                   
                  <label for="percentage2">Margin: &nbsp;</label>
                  <input id="percentage2" type="number" min="0" maxlength="3" class="form-control percentInput">
                  <span class="greenPercent"></span>
                  <br/><br/>
          </div>
          <div class="form-group sliderSection">
                  <span class="label label-danger"><label for="case3">£ Price Range : </label></span>
                  <input type="text" id="case3" readonly class="case"><br/>
                  <div id="range3" class="range"></div>                   
                  <label for="percentage3">Margin: &nbsp;</label>
                  <input id="percentage3" type="number" min="0" maxlength="3" class="form-control percentInput">
                  <span class="greenPercent"></span>
                  <br/><br/>
          </div>
          <div class="form-group sliderSection">
                  <span class="label label-danger"><label for="case4" >£ Price Range : </label></span>
                  <input type="text" id="case4" readonly class="case"><br/>
                  <div id="range4" class="range"></div>                   
                  <label for="percentage4">Margin: &nbsp;</label>
                  <input id="percentage4" type="number" min="0" maxlength="3" class="form-control percentInput">
                  <span class="greenPercent"></span>
                  <br/><br/>
          </div>            
          <div class="form-group sliderSection">
                  <span class="label label-danger"><label for="case5">£ Price Range : </label></span>
                  <input type="text" id="case5" readonly class="case"><br/>
                  <div id="range5" class="range"></div>                   
                  <label for="percentage5">Margin: &nbsp;</label>
                  <input id="percentage5" type="number" min="0" maxlength="3" class="form-control percentInput">
                  <span class="greenPercent"></span>
                  <br/><br/>
          </div>
          <div class="form-group sliderSection">
                  <span class="label label-danger"><label for="case6">£ Price Range : </label></span>
                  <input type="text" id="case6" readonly class="case"><br/>
                  <div id="range6" class="range"></div>                   
                  <label for="percentage6">Margin: &nbsp;</label>
                  <input id="percentage6" type="number" min="0" maxlength="3" class="form-control percentInput">
                  <span class="greenPercent"></span>
                  <br/><br/>
          </div>
          <div class="form-group sliderSection">
                  <span class="label label-danger"><label for="case7" >£ Price Range : </label></span>
                  <input type="text" id="case7" readonly class="case"><br/>
                  <div id="range7" class="range"></div>                   
                  <label for="percentage7">Margin: &nbsp;</label>
                  <input id="percentage7" type="number" min="0" maxlength="3" class="form-control percentInput">
                  <span class="greenPercent"></span>
                  <br/><br/>
          </div>            
          <div class="form-group sliderSection">
                  <span class="label label-danger"><label for="case8">£ Price Range : </label></span>
                  <input type="text" id="case8" readonly class="case"><br/>
                  <div id="range8" class="range"></div>                   
                  <label for="percentage8">Margin: &nbsp;</label>
                  <input id="percentage8" type="number" min="0" maxlength="3" class="form-control percentInput">
                  <span class="greenPercent"></span>
                  <br/><br/>
          </div>
          <div class="form-group sliderSection">
                  <span class="label label-danger"><label for="case9">£ Price Range : </label></span>
                  <input type="text" id="case9" readonly class="case"><br/>
                  <div id="range9" class="range"></div>                   
                  <label for="percentage9">Margin: &nbsp;</label>
                  <input id="percentage9" type="number" min="0" maxlength="3" class="form-control percentInput">
                  <span class="greenPercent"></span>
                  <br/><br/>
          </div>
        </form>
      </div>
      <!-- the Product Override tab -->
      <div id="productOverride" class="section">
        <div class="col-sm-8">
          <h2>Product Overrides</h2>
          <form>
            <br/>
            <p>This feature allows you to 'white list' a specific product. Once you have done so, it is saved in the config and can be used to override certain attributes, i.e. description , price.</p>
            <p>This ensures that the web service doesn't overwrite data for that product.</p><br/>
            <div class="form-group">
              <label for="productId">Product ID: </label>
              <input type="text" id="productId"><br/><br/>
              <label for="productName">Product Name: </label>
              <input type="text" id="productName"><br/><br/>
              <label for="productDesc">Product Description: </label>
              <textarea class="form-control" id="productDesc" rows="6"></textarea>
            </div>
          </form>
        </div>
      </div>
      <!-- end tabs -->
    </div> <!-- end outer cols -->
  </div> <!-- end outer rows -->
</div> <!-- end main container -->
<!-- start foooter -->
<footer class="container-fluid">
  <p>&copy;weys.co.uk 2017 - author: W Edwards</p>
</footer>
<!-- end foooter -->
</body>
</html>
