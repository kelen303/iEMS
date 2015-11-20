<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Screens | Cascade Flat , Responsive Bootstrap 3.0 Admin Template</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Loading Bootstrap -->
  <link href="css/bootstrap.css" rel="stylesheet">

  <!-- Loading Stylesheets -->    
  <link href="css/style.css" rel="stylesheet">
  <link href="css/login.css" rel="stylesheet">
  
  <!-- Loading Custom Stylesheets -->    
  <link href="css/custom.css" rel="stylesheet">

  <link rel="shortcut icon" href="images/favicon.ico">

  <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
      <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <![endif]-->
    </head>
    <body >
      <div class="list-group side-menu ">
        <a class="list-group-item" href="#lock-screen">Lock Screen</a>
        <a class="list-group-item" href="#login">Login</a>
        <a class="list-group-item" href="#register">Register</a>
        <a class="list-group-item" href="#forgot-password">Forgot Password?</a>
      </div>


      <section id="lock-screen">
        <div class="row ">
         <div class="login-holder col-md-6 col-md-offset-3 text-center">
           <h2 class="page-header text-center text-primary"> Welcome to Cascade </h2>
           <form role="form" action="index.php" method="post">
            <img src="images/profiles/eleven.png" alt="" class="user-avatar" />
            <h5>Logging in as <strong class="text-success">vijay kumar</strong></h5>
            <div class="form-group">
              <input type="password" class="form-control"  placeholder="Password">
            </div>
            <div class="form-footer text-info">
              
             Not You? , <a class="" href="#login">Click here to Login as different User</a>
             
             <button type="submit" class="btn btn-info pull-right btn-submit">Login</button>
           </div>

         </form>
       </div>
     </div>
    </section>

   <section id="login">
    <div class="row animated fadeILeftBig">
     <div class="login-holder col-md-6 col-md-offset-3">
       <h2 class="page-header text-center text-primary"> Welcome to Cascade </h2>
       <form role="form" action="index.php" method="post">
        <div class="form-group">
          <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
        </div>
        <div class="form-group">
          <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
        </div>
        <div class="form-footer">
          <label>
            <input type="checkbox" class="hidden" id="input-checkbox" value="0" >  <i class="fa fa-check-square-o input-checkbox fa-square-o"></i> Remember me?
          </label>
          <label>
            <input type="checkbox" class="hidden" id="input-checkbox" value="0" >  <i class="fa fa-check-square-o input-checkbox fa-square-o"></i> Forgot Password?
          </label>
          <button type="submit" class="btn btn-info pull-right btn-submit">Login</button>
        </div>

      </form>
    </div>
  </div>
</section>
<section id="register">
  <div class="row animated fadeILeftBig">
   <div class="login-holder col-md-6 col-md-offset-3">
     <h2 class="page-header text-center text-primary"> Welcome to Cascade </h2>
     <form role="form" action="index.php" method="post">
      <div class="form-group">
        <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Full Name">
      </div>
      <div class="form-group">
        <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
      </div>
      <div class="form-group">
        <input type="email" class="form-control" id="exampleInputEmail1" placeholder="City">
      </div>
      <div class="form-group">
        <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
      </div>
      <div class="form-footer">
        <label>
          <input type="checkbox" class="hidden" id="input-checkbox" value="0" >  <i class="fa fa-check-square-o input-checkbox fa-square-o"></i> I agree to the Terms &amp; Conditions
        </label>
        <button type="submit" class="btn btn-info pull-right btn-submit">Register</button>
      </div>
    </form>
  </div>
</div>
</section>
<section id="forgot-password">
  <div class="row animated fadeILeftBig">
   <div class="login-holder col-md-6 col-md-offset-3">
     <h2 class="page-header text-center text-primary"> Welcome to Cascade </h2>
     <form role="form" action="index.php" method="post">
      <div class="form-group">
        <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter Username / Email">
      </div>
      <div class="form-footer">
        
        <button type="submit" class="btn btn-info pull-right btn-submit">Send Instructions</button>
      </div>
    </form>
  </div>
</div>
</section>


<!-- Load JS here for Faster site load =============================-->
<script src="js/jquery-1.10.2.min.js"></script>
<script src="js/jquery-ui-1.10.3.custom.min.js"></script>
<script src="js/jquery.ui.touch-punch.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-select.js"></script>
<script src="js/bootstrap-switch.js"></script>
<script src="js/jquery.tagsinput.js"></script>
<script src="js/jquery.placeholder.js"></script>
<script src="js/bootstrap-typeahead.js"></script>
<script src="js/application.js"></script>
<script src="js/moment.min.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/jquery.sortable.js"></script>
<script type="text/javascript" src="js/jquery.gritter.js"></script>
<script src="js/jquery.nicescroll.min.js"></script>
<script src="js/skylo.js"></script>
<script src="js/prettify.min.js"></script>
<script src="js/jquery.noty.js"></script>
<script src="js/scroll.js"></script>
<script src="js/jquery.panelSnap.js"></script>
<script src="js/login.js"></script>





</body>
</html>