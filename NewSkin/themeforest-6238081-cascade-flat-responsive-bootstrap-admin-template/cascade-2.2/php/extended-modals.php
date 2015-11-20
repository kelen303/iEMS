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

		<h3 class="page-header"> Extended Modals <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>

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
					Responsive, Stackable, AJAX and more.
					<span class="pull-right">
						<a href="#" class="panel-minimize"><i class="fa fa-chevron-up"></i></a>
						<a href="#" class="panel-close"><i class="fa fa-times"></i></a>
					</span>
				</h3>
			</div>
			<div class="panel-body panel-border">
				<div class="page-container">
					<div class="container" style="position: relative">
						<div class="row">
							<div class="col-md-10">

								<ul class="list-group">
									<li class="list-group-item">
										<div class="responsive">
											Responsive
											<button class="demo btn pull-right btn-info btn-xs " data-toggle="modal" href="#responsive">View Demo</button>
										</div>

									</li>
									<li class="list-group-item">
										<div class="stackable">
											Stackable
											<button class="demo btn pull-right btn-info btn-xs " data-toggle="modal" href="#stack1">View Demo</button>
										</div>
									</li>
									<li class="list-group-item">
										<div class="ajax" style="position: relative; overflow: hidden">
											AJAX (via jQuery.load)
											<button class="demo btn pull-right btn-info btn-xs " data-toggle="modal">View Demo</button>
										</div>
									</li>
									<li class="list-group-item">
										<div class="static" style="position: relative; overflow: hidden">
											Static Background with Animation
											<p>The shake animation is included in bootstrap-modal but any animation in <a href="http://daneden.me/animate/">animate.css</a> is supported, just include the css file in your project.</p>
											<button class="demo btn pull-right btn-info btn-xs " data-toggle="modal" href="#static">View Demo</button>
										</div>

									</li>
									<li class="list-group-item">
										<div class="full-width" style="position: relative; overflow: hidden">
											Full Width
											<button class="demo btn pull-right btn-info btn-xs " data-toggle="modal" href="#full-width">View Demo</button>
										</div>

									</li>
									<li class="list-group-item">
										<div class="long" style="position: relative; overflow: hidden">
											Long Modals
											<button class="demo btn pull-right btn-info btn-xs " data-toggle="modal" href="#long">View Demo</button>
										</div>

									</li>
								</ul>
							</div>
						</div>
					</div>

					<!-- Modal Definitions (tabbed over for <pre>) -->
					<div id="responsive" class="modal fade" tabindex="-1" data-width="760" style="display: none;">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title">Responsive</h4>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-md-6">
									<h4>Some Input</h4>
									<p><input class="form-control" type="text" /></p>
									<p><input class="form-control" type="text" /></p>
									<p><input class="form-control" type="text" /></p>
									<p><input class="form-control" type="text" /></p>
									<p><input class="form-control" type="text" /></p>
									<p><input class="form-control" type="text" /></p>
									<p><input class="form-control" type="text" /></p>
								</div>
								<div class="col-md-6">
									<h4>Some More Input</h4>
									<p><input class="form-control" type="text" /></p>
									<p><input class="form-control" type="text" /></p>
									<p><input class="form-control" type="text" /></p>
									<p><input class="form-control" type="text" /></p>
									<p><input class="form-control" type="text" /></p>
									<p><input class="form-control" type="text" /></p>
									<p><input class="form-control" type="text" /></p>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" data-dismiss="modal" class="btn btn-default">Close</button>
							<button type="button" class="btn btn-primary">Save changes</button>
						</div>
					</div>

					<div id="full-width" class="modal container fade" tabindex="-1" style="display: none;">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title">Full Width</h4>
						</div>
						<div class="modal-body">
							<p>This modal will resize itself to the same dimensions as the container class.</p>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis sollicitudin ipsum ac ante fermentum suscipit. In ac augue non purus accumsan lobortis id sed nibh. Nunc egestas hendrerit ipsum, et porttitor augue volutpat non. Aliquam erat volutpat. Vestibulum scelerisque lobortis pulvinar. Aenean hendrerit risus neque, eget tincidunt leo. Vestibulum est tortor, commodo nec cursus nec, vestibulum vel nibh. Morbi elit magna, ornare placerat euismod semper, dignissim vel odio. Phasellus elementum quam eu ipsum euismod pretium.</p>
						</div>
						<div class="modal-footer">
							<button type="button" data-dismiss="modal" class="btn btn-default">Close</button>
							<button type="button" class="btn btn-primary">Save changes</button>
						</div>
					</div>

					<div id="stack1" class="modal fade" tabindex="-1" data-focus-on="input:first" style="display: none;">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title">Stack One</h4>
						</div>
						<div class="modal-body">
							<p>One fine body…</p>
							<p>One fine body…</p>
							<p>One fine body…</p>
							<input class="form-control" type="text" data-tabindex="1" />
							<input class="form-control" type="text" data-tabindex="2" />
							<button class="btn btn-default" data-toggle="modal" href="#stack2">Launch modal</button>
						</div>
						<div class="modal-footer">
							<button type="button" data-dismiss="modal" class="btn btn-default">Close</button>
							<button type="button" class="btn btn-primary">Ok</button>
						</div>
					</div>

					<div id="stack2" class="modal fade" tabindex="-1" data-focus-on="input:first" style="display: none;">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title">Stack Two</h4>
						</div>
						<div class="modal-body">
							<p>One fine body…</p>
							<p>One fine body…</p>
							<input class="form-control" type="text" data-tabindex="1" />
							<input class="form-control" type="text" data-tabindex="2" />
							<button class="btn btn-default" data-toggle="modal" href="#stack3">Launch modal</button>
						</div>
						<div class="modal-footer">
							<button type="button" data-dismiss="modal" class="btn btn-default">Close</button>
							<button type="button" class="btn btn-primary">Ok</button>
						</div>
					</div>

					<div id="stack3" class="modal fade" tabindex="-1" data-focus-on="input:first" style="display: none;">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title">Stack Three</h4>
						</div>
						<div class="modal-body">
							<p>One fine body…</p>
							<input class="form-control" type="text" data-tabindex="1" />
							<input class="form-control" type="text" data-tabindex="2" />
						</div>
						<div class="modal-footer">
							<button type="button" data-dismiss="modal" class="btn btn-default">Close</button>
							<button type="button" class="btn btn-primary">Ok</button>
						</div>
					</div>

					<div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false" style="display: none;">
						<div class="modal-body">
							<p>Would you like to continue with some arbitrary task?</p>
						</div>
						<div class="modal-footer">
							<button type="button" data-dismiss="modal" class="btn btn-default">Cancel</button>
							<button type="button" data-dismiss="modal" class="btn btn-primary">Continue Task</button>
						</div>
					</div>

					<div id="long" class="modal fade" tabindex="-1" data-replace="true" style="display: none;">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title">A Fairly Long Modal</h4>
						</div>
						<div class="modal-body">
							<button class="btn btn-default" data-toggle="modal" href="#notlong" style="position: absolute; top: 50%; right: 12px">Not So Long Modal</button>
							<img style="height: 800px" src="http://i.imgur.com/KwPYo.jpg" />
						</div>
						<div class="modal-footer">
							<button type="button" data-dismiss="modal" class="btn btn-default">Close</button>
						</div>
					</div>

					<div id="notlong" class="modal fade" tabindex="-1" data-replace="true" style="display: none;">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title">Not That Long</h4>
						</div>
						<div class="modal-body">
							<button class="btn btn-default" data-toggle="modal" href="#verylong" style="position: absolute; top: 50%; right: 12px">Very Long Modal</button>
							<div style="height: 400px; overflow: hidden;">
								<img style="height: 800px" src="http://i.imgur.com/KwPYo.jpg" />
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" data-dismiss="modal" class="btn btn-default">Close</button>
						</div>
					</div>

					<div id="verylong" class="modal fade" tabindex="-1" data-replace="true" style="display: none;">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title">A Very Long</h4>
						</div>
						<div class="modal-body">
							<div style="height: 1000px; overflow: hidden;">
								<img style="height: 800px" src="http://i.imgur.com/KwPYo.jpg" />
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" data-dismiss="modal" class="btn btn-default">Close</button>
						</div>
					</div>

					<div id="ajax-modal" class="modal fade" tabindex="-1" style="display: none;"></div>
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

<script src="js/bootstrap-modalmanager.js"></script>
<script src="js/bootstrap-modal.js"></script>
<script src="js/bootstrap-modal-custom.js"></script>

<!-- Core Jquery File  =============================-->
<script src="js/core.js"></script>


</body>
</html>