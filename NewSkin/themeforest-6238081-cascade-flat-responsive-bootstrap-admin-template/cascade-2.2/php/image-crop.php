<?php include('partials/header.php'); ?>  
<!-- /header -->
<div class="row">
	<div class="col-mod-12">

		<ul class="breadcrumb">
			<li><a href="index.php">Dashboard</a></li>
			<li><a href="template.php">Basic Template</a></li>
			<li class="active">BreadCrumb</li>
		</ul>

		<div class="form-group hiddn-minibar pull-right">
			<input type="text" class="form-control form-cascade-control nav-input-search" size="20" placeholder="Search through site" />

			<span class="input-icon fui-search"></span>
		</div>

		<h3 class="page-header"> Image Crop <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>

		<blockquote class="page-information hidden">
			<p>
				<b>Template Page</b> is the basic page where you can add more pages according to your requirements easily within this division.
			</p>
		</blockquote>

	</div>
</div>

<!-- Demo Panel -->
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-cascade">
			<div class="panel-heading">
				<h3 class="panel-title text-primary">
					Image Crop
					<span class="pull-right">
						<a href="#" class="panel-minimize"><i class="fa fa-chevron-up"></i></a>
						<a href="#" class="panel-close"><i class="fa fa-times"></i></a>
					</span>
				</h3>
			</div>
			<div class="panel-body panel-border">

				

				<!-- Basic Image crop behaviour -->
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-archon">
							<div class="panel-heading">
								<h3 class="panel-title">
									Basic Image crop behaviour
									<span class="pull-right">
										<a  href="#" class="panel-minimize"><i class="fa fa-chevron-up"></i></a>
										<a  href="#"  class="panel-settings"><i class="fa fa-cog"></i></a>
										<a  href="#"  class="panel-close"><strong>X</strong></a>
									</span>
								</h3>
							</div>
							<div class="panel-body">
								<img src="images/image-01.jpg" class="image-crop" width="100%" id="target" />
							</div>
							<div class="panel-footer">
								<!-- This is the form that our event handler fills -->
								<form onsubmit="return false;" class="coords">
									<ul class="list-inline">
										<li><label>X1 <input type="text" size="4" id="x1" name="x1" /></label></li>
										<li><label>Y1 <input type="text" size="4" id="y1" name="y1" /></label></li>
										<li><label>X2 <input type="text" size="4" id="x2" name="x2" /></label></li>
										<li><label>Y2 <input type="text" size="4" id="y2" name="y2" /></label></li>
										<li><label>W <input type="text" size="4" id="wid" name="wid" /></label></li>
										<li><label>H <input type="text" size="4" id="hei" name="hei" /></label></li>	
									</ul>
								</form>
							</div>
						</div>
					</div>
				</div>


				<!-- Image crop on server side-->
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-archon">
							<div class="panel-heading">
								<h3 class="panel-title">
									Image crop on server side (PHP)
									<span class="pull-right">
										<a  href="#" class="panel-minimize"><i class="fa fa-chevron-up"></i></a>
										<a  href="#"  class="panel-settings"><i class="fa fa-cog"></i></a>
										<a  href="#"  class="panel-close"><strong>X</strong></a>
									</span>
								</h3>
							</div>
							<div class="panel-body">
								<!-- This is the image we're attaching Jcrop to -->
								<img src="images/image-01.jpg" width="100%" id="cropbox"/>
							</div>
							<div class="panel-footer">
								<!-- This is the form that our event handler fills -->
								<form action="crop.php" method="post" onsubmit="return checkCoords();">
									<input type="hidden" id="x" name="x" />
									<input type="hidden" id="y" name="y" />
									<input type="hidden" id="w" name="w" />
									<input type="hidden" id="h" name="h" />
									<input type="submit" value="Crop Image" class="btn btn-large btn-info" />
									<p>An example server-side crop script. Hidden form values are set when a selection is made. If you press the Crop Image button, the form will be submitted and a 150x150 thumbnail will be dumped to the browser. Try it!</p>
								</form>
							</div>
						</div>
					</div>
				</div>

				<!-- Modal -->
				<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h5 class="modal-title">Panel Settings</h5>
								<span class="small">Some sort of settings with a form</span>
							</div>
							<div class="modal-body">
								<form class="form-horizontal" role="form">
									<div class="form-group">
										<label for="inputEmail1" class="col-lg-2 control-label">Label</label>
										<div class="col-lg-10">
											<input type="email" class="form-control" id="inputEmail1" placeholder="Label">
										</div>
									</div>
									<div class="form-group">
										<label for="inputPassword1" class="col-lg-2 control-label">Second Label</label>
										<div class="col-lg-10">
											<input type="text" class="form-control" id="inputPassword1" placeholder="Label two">
										</div>
									</div>
								</form>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary">Save changes</button>
							</div>
						</div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
				</div><!-- /.modal -->




					</div> <!-- /panel body -->	
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

<!-- /rightside bar -->

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


<script src="js/jcrop/jquery.Jcrop.min.js"></script>
	<script src="js/jcrop/Jcrop.custom.js"></script>

<!-- Core Jquery File  =============================-->
<script src="js/core.js"></script>


</body>
</html>