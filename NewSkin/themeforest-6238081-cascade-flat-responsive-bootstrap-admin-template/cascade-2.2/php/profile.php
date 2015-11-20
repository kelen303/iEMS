<?php include('partials/header.php'); ?>
              <div class="row">
                  <div class="col-mod-12">
                    <ul class="breadcrumb">
                      <li><a href="index.php">Dashboard</a></li>
                      <li><a href="template.php">Basic Template</a></li>
                      <li class="active">BreadCrumb</li>
                    </ul>
                  </div>
                </div>
                <div class="row profile">
                  <div class="col-md-3 user-details well text-center col-sm-12" >
                    <img src="images/profiles/eleven.png" class="main-avatar" />
                    <div class="user-main-info">
                      <h2 class="text-primary user-name">@John Deo</h2>
                      <h5 class="text-info user-designation">UI/UX Designer</h5>
                      <a href="#" class="btn btn-success"><i class="fa fa-check"></i> Following </a>
                      <a href="#" class="btn btn-info"><i class="fa fa-comment"></i> Chat </a>
                    </div>
                    <div class="about">
                      <p><strong>About: </strong>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a </p>
                    </div>
                    <ul class="list-group details-list">
                     <li class="list-group-item">
                      <span class="badge bg-info">903</span>
                      Friends
                    </li>
                    <li class="list-group-item">
                      <span class="badge bg-pink">981</span>
                      Followers
                    </li>
                    <li class="list-group-item">
                      <span class="badge bg-warning">46</span>
                      Following
                    </li>
                    <li class="list-group-item">
                      <span class="badge bg-purple">50M</span>
                      Likes
                    </li>
                  </ul>
                </div>
                <div class="col-md-9 profile-tabs">

                  <ul id="myTab" class="nav nav-tabs">
                    <li class="active"><a href="#photos" data-toggle="tab">Photos</a></li>
                    <li><a href="#friends" data-toggle="tab">Friends</a></li>
                    <li class="dropdown">
                      <a href="#" id="myTabDrop1" class="dropdown-toggle" data-toggle="dropdown">More <b class="caret"></b></a>
                      <ul class="dropdown-menu" role="menu" aria-labelledby="myTabDrop1">
                        <li><a href="#dropdown3" tabindex="-1" data-toggle="tab">More here</a></li>
                        <li><a href="#dropdown4" tabindex="-1" data-toggle="tab">And Here too</a></li>
                      </ul>
                    </li>
                  </ul>

                  <div id="myTabContent" class="tab-content">

                    <div class="tab-pane fade  in active" id="photos">
                      <ul>
                        <li class="col-md-4">
                          <div class="img-container">
                            <img src="images/carousel/imageOne.jpg" alt="" class="imggg" />
                            <a href="#">Image Name<span class="pull-right">Added One hour ago</span></a>
                          </div>
                        </li>
                        <li class="col-md-4">
                          <div class="img-container">
                            <img src="images/carousel/imageTwo.jpg" alt="" class="imggg" />
                            <a href="#">Image Name<span class="pull-right">Added One hour ago</span></a>
                          </div>
                        </li>
                        <li class="col-md-4">
                          <div class="img-container">
                            <img src="images/carousel/imageThree.jpg" alt="" class="imggg" />
                            <a href="#">Image Name<span class="pull-right">Added One hour ago</span></a>
                          </div>
                        </li>
                        <li class="col-md-4">
                          <div class="img-container">
                            <img src="images/carousel/imageFour.jpg" alt="" class="imggg" />
                            <a href="#">Image Name<span class="pull-right">Added One hour ago</span></a>
                          </div>
                        </li>
                        <li class="col-md-4">
                          <div class="img-container">
                            <img src="images/carousel/imageFive.jpg" alt="" class="imggg" />
                            <a href="#">Image Name<span class="pull-right">Added One hour ago</span></a>
                          </div>
                        </li>
                        <li class="col-md-4">
                          <div class="img-container">
                            <img src="images/carousel/imageSix.jpg" alt="" class="imggg" />
                            <a href="#">Image Name<span class="pull-right">Added One hour ago</span></a>
                          </div>
                        </li>
                        <li class="col-md-4">
                          <div class="img-container">
                            <img src="images/carousel/imageSeven.jpg" alt="" class="imggg" />
                            <a href="#">Image Name<span class="pull-right">Added One hour ago</span></a>
                          </div>
                        </li>
                        <li class="col-md-4">
                          <div class="img-container">
                            <img src="images/carousel/imageEight.jpg" alt="" class="imggg" />
                            <a href="#">Image Name<span class="pull-right">Added One hour ago</span></a>
                          </div>
                        </li>
                        <li class="col-md-4">
                          <div class="img-container">
                            <img src="images/carousel/imageNine.jpg" alt="" class="imggg" />
                            <a href="#">Image Name<span class="pull-right">Added One hour ago</span></a>
                          </div>
                        </li>
                        <li class="col-md-4">
                          <div class="img-container">
                            <img src="images/carousel/imageOne.jpg" alt="" class="imggg" />
                            <a href="#">Image Name<span class="pull-right">Added One hour ago</span></a>
                          </div>
                        </li>
                        <li class="col-md-4">
                          <div class="img-container">
                            <img src="images/carousel/imageFive.jpg" alt="" class="imggg" />
                            <a href="#">Image Name<span class="pull-right">Added One hour ago</span></a>
                          </div>
                        </li>
                        <li class="col-md-4">
                          <div class="img-container">
                            <img src="images/carousel/imageSix.jpg" alt="" class="imggg" />
                            <a href="#">Image Name<span class="pull-right">Added One hour ago</span></a>
                          </div>
                        </li>
                      </ul>
                    </div>
                    <div class="tab-pane fade" id="friends">
                      <ul>
                        <li class="col-md-2">
                          <img src="images/profiles/one.png" />
                          <a href="#">John Deo</a>
                          <span class="username">@johndeo</span>
                        </li>
                        <li class="col-md-2">
                          <img src="images/profiles/two.png" />
                          <a href="#">John Deo</a>
                          <span class="username">@johndeo</span>
                        </li>
                        <li class="col-md-2">
                          <img src="images/profiles/three.png" />
                          <a href="#">John Deo</a>
                          <span class="username">@johndeo</span>
                        </li>
                        <li class="col-md-2">
                          <img src="images/profiles/four.png" />
                          <a href="#">John Deo</a>
                          <span class="username">@johndeo</span>
                        </li>
                        <li class="col-md-2">
                          <img src="images/profiles/five.png" />
                          <a href="#">John Deo</a>
                          <span class="username">@johndeo</span>
                        </li>
                        <li class="col-md-2">
                          <img src="images/profiles/six.png" />
                          <a href="#">John Deo</a>
                          <span class="username">@johndeo</span>
                        </li>
                        <li class="col-md-2">
                          <img src="images/profiles/seven.png" />
                          <a href="#">John Deo</a>
                          <span class="username">@johndeo</span>
                        </li>
                        <li class="col-md-2">
                          <img src="images/profiles/eight.png" />
                          <a href="#">John Deo</a>
                          <span class="username">@johndeo</span>
                        </li>
                        <li class="col-md-2">
                          <img src="images/profiles/nine.png" />
                          <a href="#">John Deo</a>
                          <span class="username">@johndeo</span>
                        </li>
                        <li class="col-md-2">
                          <img src="images/profiles/ten.png" />
                          <a href="#">John Deo</a>
                          <span class="username">@johndeo</span>
                        </li>
                        <li class="col-md-2">
                          <img src="images/profiles/eleven.png" />
                          <a href="#">John Deo</a>
                          <span class="username">@johndeo</span>
                        </li>
                        <li class="col-md-2">
                          <img src="images/profiles/twelve.png" />
                          <a href="#">John Deo</a>
                          <span class="username">@johndeo</span>
                        </li>
                        <li class="col-md-2">
                          <img src="images/profiles/thirteen.png" />
                          <a href="#">John Deo</a>
                          <span class="username">@johndeo</span>
                        </li>
                        <li class="col-md-2">
                          <img src="images/profiles/fourteen.png" />
                          <a href="#">John Deo</a>
                          <span class="username">@johndeo</span>
                        </li>
                        <li class="col-md-2">
                          <img src="images/profiles/fifteen.png" />
                          <a href="#">John Deo</a>
                          <span class="username">@johndeo</span>
                        </li>
                        <li class="col-md-2">
                          <img src="images/profiles/sixteen.png" />
                          <a href="#">John Deo</a>
                          <span class="username">@johndeo</span>
                        </li>
                        <li class="col-md-2">
                          <img src="images/profiles/seventeen.png" />
                          <a href="#">John Deo</a>
                          <span class="username">@johndeo</span>
                        </li>
                        <li class="col-md-2">
                          <img src="images/profiles/eighteen.png" />
                          <a href="#">John Deo</a>
                          <span class="username">@johndeo</span>
                        </li>
                        <li class="col-md-2">
                          <img src="images/profiles/nineteen.png" />
                          <a href="#">John Deo</a>
                          <span class="username">@johndeo</span>
                        </li>
                        <li class="col-md-2">
                          <img src="images/profiles/twenty.png" />
                          <a href="#">John Deo</a>
                          <span class="username">@johndeo</span>
                        </li>
                        <li class="col-md-2">
                          <img src="images/profiles/twentyOne.png" />
                          <a href="#">John Deo</a>
                          <span class="username">@johndeo</span>
                        </li>
                        <li class="col-md-2">
                          <img src="images/profiles/twentyTwo.png" />
                          <a href="#">John Deo</a>
                          <span class="username">@johndeo</span>
                        </li>
                        <li class="col-md-2">
                          <img src="images/profiles/twentyThree.png" />
                          <a href="#">John Deo</a>
                          <span class="username">@johndeo</span>
                        </li>
                        <li class="col-md-2">
                          <img src="images/profiles/twentyFour.png" />
                          <a href="#">John Deo</a>
                          <span class="username">@johndeo</span>
                        </li>
                      </ul>
                    </div>
                    <div class="tab-pane fade" id="dropdown3">
                      <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum..</p>
                    </div>
                    <div class="tab-pane fade" id="dropdown4">
                      <p>Trust fund seitan letterpress, keytar raw denim keffiyeh etsy art party before they sold out master cleanse gluten-free squid scenester freegan cosby sweater. Fanny pack portland seitan DIY, art party locavore wolf cliche high life echo park Austin. Cred vinyl keffiyeh DIY salvia PBR, banh mi before they sold out farm-to-table VHS viral locavore cosby sweater. Lomo wolf viral, mustache readymade thundercats keffiyeh craft beer marfa ethical. Wolf salvia freegan, sartorial keffiyeh echo park vegan.</p>
                    </div>
                  </div>
                </div>

             </div>



           </div> <!-- /.content -->

           <!-- .right-sidebar -->
           <div class="right-sidebar right-sidebar-hidden">
             <div class="right-sidebar-holder">

              <!-- @Demo part only The part from here can be removed till end of the @demo  -->
              <a href="screens.php" class="btn btn-danger btn-block">Logout </a>


              <h4 class="page-header text-primary text-center">
               Theme Options
               <a  href="#"  class="theme-panel-close text-primary pull-right"><strong><i class="fa fa-times"></i></strong></a>
             </h4>

             <ul class="list-group theme-options">
               <li class="list-group-item" href="#">	
                <div class="checkbox">
                 <label>
                  <input type="checkbox" id="fixedNavbar" value=""> Fixed Top Navbar
                </label>
              </div>
              <div class="checkbox">
               <label>
                <input type="checkbox" id="fixedNavbarBottom" value=""> Fixed Bottom Navbar
              </label>
            </div>
            <div class="checkbox">
             <label>
              <input type="checkbox" id="boxed" value=""> Boxed Version
            </label>
          </div>

          <div class="form-group backgroundImage hidden" >
            <label class="control-label">Paste Image Url and then hit enter</label>
            <input type="text" class="form-control" id="backgroundImageUrl" />
          </div>
				<!-- 
				<div class="checkbox">
					<label>
						<input type="checkbox" id="responsive" value=""> Disable Responsiveness
					</label>
				</div> 
			-->			
		</li>
		<li class="list-group-item" href="#">Predefined Themes
			<ul class="list-inline predefined-themes"> 
				<li><a class="badge" style="background-color:#54b5df" data-color-primary="#54b5df" data-color-secondary="#2f4051" data-color-link="#FFFFFF"> &nbsp; </a></li>
				<li><a class="badge" style="background-color:#d85f5c" data-color-primary="#d85f5c" data-color-secondary="#f0f0f0" data-color-link="#474747"> &nbsp; </a></li>
				<li><a class="badge" style="background-color:#3d4a5d" data-color-primary="#3d4a5d" data-color-secondary="#edf0f1" data-color-link="#777e88"> &nbsp; </a></li>
				<li><a class="badge" style="background-color:#A0B448" data-color-primary="#A0B448" data-color-secondary="#485257" data-color-link="#AFB5AA"> &nbsp; </a></li>
				<li><a class="badge" style="background-color:#2FA2D1" data-color-primary="#2FA2D1" data-color-secondary="#484D51" data-color-link="#A5B1B7"> &nbsp; </a></li>
				<li><a class="badge" style="background-color:#2f343b" data-color-primary="#2f343b" data-color-secondary="#525a65" data-color-link="#FFFFFF"> &nbsp; </a></li>
			</ul>
		</li>
		<li class="list-group-item" href="#">Change Primary Color
			<div class="input-group colorpicker-component bscp" data-color="#54728c" data-color-format="hex" id="colr">
				<span class="input-group-addon"><i style="background-color: #54728c"></i></span>
				<input type="text" value="#54728c" id="primaryColor" readonly class="form-control" />

			</div>
		</li>
		<li class="list-group-item" href="#">Change LeftSidebar Background
			<div class="input-group colorpicker-component bscp" data-color="#f9f9f9" data-color-format="hex" id="Scolr">
				<span class="input-group-addon"><i style="background-color: #f9f9f9"></i></span>
				<input type="text" value="#f9f9f9" id="secondaryColor" readonly class="form-control" />

			</div>
		</li>
		<li class="list-group-item" href="#">Change Leftsidebar Link Color
			<div class="input-group colorpicker-component bscp" data-color="#54728c" data-color-format="hex" id="Lcolr">
				<span class="input-group-addon"><i style="background-color: #54728c"></i></span>
				<input type="text" value="#54728c" id="linkColor" readonly class="form-control" />

			</div>
		</li>
		<li class="list-group-item" href="#">Change RightSidebar Background
			<div class="input-group colorpicker-component bscp" data-color="#f9f9f9" data-color-format="hex" id="Rcolr">
				<span class="input-group-addon"><i style="background-color: #f9f9f9"></i></span>
				<input type="text" value="#f9f9f9" id="rightsidebarColor" readonly class="form-control" />

			</div>
		</li>
	</li>
</ul>
<!-- /.@Demo part only The part to here can be removed   -->
<div id="bic_calendar_right" class="bg-white"></div>

<h4 class="page-header text-primary">Current Projects </h4>

<div class="list-group projects">
	<a class="list-group-item" href="#">	
		<p> Archon <span class="pull-right label bg-success">90%</span></p>
		<div class="progress progress-mini">
			<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 90%;">
				<span class="sr-only">90% Complete</span>
			</div>
		</div>

	</a>
	<a class="list-group-item" href="#">	
		<p> Banshee <span class="pull-right label bg-warning">40%</span></p>
		<div class="progress progress-mini">
			<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 40%;">
				<span class="sr-only">40% Complete</span>
			</div>
		</div>

	</a>
	<a class="list-group-item" href="#">	
		<p> Cascade <span class="pull-right label bg-primary">40%</span></p>
		<div class="progress progress-mini">
			<div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 75%;">
				<span class="sr-only">75% Complete</span>
			</div>
		</div>
	</a>
</div>
<h4 class="page-header">Contacts</h4>
<div class="list-group contact-list">
 <a class="list-group-item">
  <img src="images/profiles/one.png" class="chat-user-avatar" alt="">
  Jane Doe
  <i class="fa fa-circle"></i>
</a>
<a class="list-group-item contact">
  <img src="images/profiles/two.png" class="chat-user-avatar" alt="">
  Jenny
  <i class="fa fa-circle online"></i>
</a>
<a class="list-group-item contact">
  <img src="images/profiles/three.png" class="chat-user-avatar" alt="">
  Vijay
  <i class="fa fa-circle busy"></i>
</a>
<a class="list-group-item">
  <img src="images/profiles/four.png" class="chat-user-avatar" alt="">
  Nikky
  <i class="fa fa-circle offline"></i>
</a>
<a class="list-group-item contact">
  <img src="images/profiles/five.png" class="chat-user-avatar" alt="">
  John
  <i class="fa fa-circle"></i>
</a>
<a class="list-group-item contact">
  <img src="images/profiles/six.png" class="chat-user-avatar" alt="">
  Anusha
  <i class="fa fa-circle"></i>
</a>
<a class="list-group-item">
  <img src="images/profiles/seven.png" class="chat-user-avatar" alt="">
  Antony
  <i class="fa fa-circle offline"></i>
</a>
<a class="list-group-item contact">
  <img src="images/profiles/eight.png" class="chat-user-avatar" alt="">
  Fana
  <i class="fa fa-circle busy"></i>
</a>
<a class="list-group-item contact">
  <img src="images/profiles/nine.png" class="chat-user-avatar" alt="">
  Chris
  <i class="fa fa-circle offline"></i>
</a>
<a class="list-group-item">
  <img src="images/profiles/ten.png" class="chat-user-avatar" alt="">
  Sandy
  <i class="fa fa-circle online"></i>
</a>
<a class="list-group-item contact">
  <img src="images/profiles/eleven.png" class="chat-user-avatar" alt="">
  Ajay
  <i class="fa fa-circle"></i>
</a>
<a class="list-group-item contact">
  <img src="images/profiles/twelve.png" class="chat-user-avatar" alt="">
  Sanju
  <i class="fa fa-circle"></i>
</a>
</div>
</div>


</div>	<!-- /.right-sidebar-holder -->
</div>  <!-- /.right-sidebar -->


</div> <!-- /.box-holder -->
</div><!-- /.site-holder -->



<!-- Load JS here for Faster site load =============================-->
<script src="js/jquery-1.10.2.min.js"></script>
<script src="js/jquery-ui-1.10.3.custom.min.js"></script>
<script src="js/less-1.5.0.min.js"></script>
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
<script src="js/bic_calendar.js"></script>
<script src="js/jquery.accordion.js"></script>
<script src="js/theme-options.js"></script>

<script src="js/bootstrap-progressbar.js"></script>
<script src="js/bootstrap-progressbar-custom.js"></script>
<script src="js/bootstrap-colorpicker.min.js"></script>
<script src="js/bootstrap-colorpicker-custom.js"></script>

<!-- Core Jquery File  =============================-->
<script src="js/core.js"></script>


</body>
</html>