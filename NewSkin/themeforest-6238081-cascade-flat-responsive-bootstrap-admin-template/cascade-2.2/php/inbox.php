<?php include('partials/header.php'); ?>


  						<div class="row">
  							<div class="col-mod-12">

  								<ul class="breadcrumb">
  									<li><a href="index.php">Dashboard</a></li>
  									<li><a href="#">Pages</a></li>
  									<li class="active">Inbox</li>
  								</ul>

  								<div class="form-group hiddn-minibar pull-right">
  									<input type="text" class="form-control form-cascade-control nav-input-search" size="20" placeholder="Search through site" />

  									<span class="input-icon fui-search"></span>
  								</div>

  								<h3 class="page-header"><i class="fa fa-envelope"></i> Inbox </h3>

  							</div>
  						</div>

  						<!-- Mail Panel -->
  						<div class="row inbox">
  							<!-- Left Side bar -->
  							<div class="col-md-2 mail-left-box">
  								<a  class="btn btn-block btn-compose btn-lg" ><i class="fa fa-" ></i> Compose Mail </a> 

  								<div class="list-group inbox-options">
  									<a  class="list-group-item" ><i class="fa fa-inbox" ></i> Inbox <span class="badge  bg-primary">1,234</span> </a> 
  									<a  class="list-group-item" ><i class="fa fa-bolt" ></i> Unread <span class="badge  bg-primary">58</span> </a> 
  									<a  class="list-group-item" ><i class="fa fa-magic" ></i> Important <span class="badge  bg-primary">65</span> </a> 
  									<a  class="list-group-item" ><i class="fa fa-bookmark-o" ></i> Starred <span class="badge  bg-primary">56</span> </a> 
  									<a  class="list-group-item" ><i class="fa fa-check-square-o" ></i> Sent <span class="badge  bg-primary">658</span> </a> 
  									<a  class="list-group-item" ><i class="fa fa-ban" ></i> Spam <span class="badge  bg-primary">20</span> </a> 
  									<a  class="list-group-item" ><i class="fa fa-trash-o" ></i> Trash <span class="badge  bg-primary">620</span> </a> 
  									<a  class="list-group-item" ><i class="fa fa-archive" ></i> Archived <span class="badge  bg-primary">30</span> </a> 
  								</div>

  								<h5 class="text-primary "> Labels</h5>
  								<div class="list-group inbox-labels">
  									<a class="list-group-item"  href="#"><span class="badge pull-right bg-purple">&nbsp;</span>Work</a>
  									<a class="list-group-item"  href="#"><span class="badge pull-right bg-primary">&nbsp;</span>Shopping</a>
  									<a class="list-group-item"  href="#"><span class="badge pull-right bg-success">&nbsp;</span>Promotions</a>
  									<a class="list-group-item"  href="#"><span class="badge pull-right bg-warning">&nbsp;</span>Finance</a>
  									<a class="list-group-item"  href="#"><span class="badge pull-right bg-info">&nbsp;</span>Games</a>
  									<a class="list-group-item"  href="#"><span class="badge pull-right bg-danger">&nbsp;</span>Family</a>
  								</div>			

  								<div class="input-group">
  									<input type="text" name="createLabel" class="form-control" id="write-label" placeholder="Create Label Here"> 
  									<span class="input-group-btn">
  										<button class="btn bg-primary text-white" type="button" id="create-label">Go!</button>
  									</span>
  								</div><!-- /input-group -->


  							</div><!-- /Mail Left Side bar -->

  							<!-- Mail Right Sidebar -->
  							<div class="col-md-10  mail-right-box">
  								<div class="mail-options-nav">

  									<div class="input-group select-all" >
  										<span class="input-group-addon">
  											<input type="checkbox" >
  										</span>
  										<div class="input-group-btn">
  											<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" tabindex="-1">
  												<span class="caret"></span>
  												<span class="sr-only">Toggle Dropdown</span>
  											</button>
  											<ul class="dropdown-menu" role="menu">
  												<li><a href="#">Mark as Unread</a></li>
  												<li><a href="#">Mark as Important</a></li>
  												<li><a href="#">Add to Tasks</a></li>
  												<li class="divider"></li>
  												<li><a href="#">Delete</a></li>
  											</ul>
  										</div>
  									</div><!-- /input-group -->

  									<div class="btn-group mail-options">
  										<a href="" class="btn btn-success"><i class="fa fa-archive" ></i> Archive</a>
  										<a href="" class="btn btn-warning"><i class="fa fa-ban" ></i> Spam</a>
  										<a href="" class="btn btn-danger"><i class="fa fa-trash-o" ></i> Delete</a>
  									</div>
  									<div class="mail-pagination pull-right">
  										<label class="label text-primary">1-60 of 500</label>
  										<a href="#" class="btn btn-default"><i class="fa fa-angle-double-left"></i></a>
  										<a href="#" class="btn btn-default"><i class="fa fa-angle-double-right"></i></a>
  										<a href="#" class="btn btn-info"><i class="fa fa-cogs"></i></a>
  									</div>
  								</div>
  								<div class="mails">
  									<table class="table table-hover table-condensed">
  										<tr>
  											<td><i class="fa fa-check-square-o fa-square-o"></i></td>
  											<td><i class="fa fa-star"></i></td>
  											<td><i class="fa fa-circle"></i></td>
  											<td class="subject">My Work <i class="fa fa-paperclip"></i></td>
  											<td class="body">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</td>
  											<td><label class="label bg-purple">Work</label></td>
  											<td class="time">07:08 PM</td>
  										</tr>
  										<tr class="unread">
  											<td><i class="fa fa-check-square-o fa-square-o"></i></td>
  											<td><i class="fa fa-star"></i></td>
  											<td><i class="fa fa-circle"></i></td>
  											<td class="subject">Flipkart.com </td>
  											<td class="body">
  												Looking forward to this weekend with hope of rejuvenating yourself? Watch Sachin's last match, party hard, go for a trek, watch movies back to back, or just laze around with a good book. Explore our wide range of products that will add more fun to your weekends. 

  												Shop for your weekend today </td>
  												<td><label class="label bg-primary">Shopping</label></td>
  												<td class="time">06:58 PM</td>
  											</tr>
  											<tr>
  												<td><i class="fa fa-check-square-o fa-square-o"></i></td>
  												<td><i class="fa fa-star"></i></td>
  												<td><i class="fa fa-circle"></i></td>
  												<td class="subject">MySmartPrice</td>
  												<td class="body">We are giving away Rs. 1,000 flipkart gift vouchers to 50 lucky winners. You need to simply send us a copy of the invoice of any past onlince purchase to deals@mysmartprice.com. Only 5 more days are left. So go ahead and send us the invoice copy if you have not already done so.</td>
  												<td><label class="label bg-primary">Shopping</label></td>
  												<td class="time">10:12 AM</td>
  											</tr>
  											<tr>
  												<td><i class="fa fa-check-square-o fa-square-o"></i></td>
  												<td><i class="fa fa-star"></i></td>
  												<td><i class="fa fa-circle"></i></td>
  												<td class="subject">Web Design <i class="fa fa-paperclip"></i></td>
  												<td class="body">Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia</td>
  												<td><label class="label bg-purple">Work</label></td>
  												<td class="time">7:54 AM</td>
  											</tr>
  											<tr>
  												<td><i class="fa fa-check-square-o fa-square-o"></i></td>
  												<td><i class="fa fa-star"></i></td>
  												<td><i class="fa fa-circle"></i></td>
  												<td class="subject">Theme Work</td>
  												<td class="body">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. </td>
  												<td><label class="label bg-purple">Work</label></td>
  												<td class="time">02:34 AM</td>
  											</tr>
  											<tr>
  												<td><i class="fa fa-check-square-o fa-square-o"></i></td>
  												<td><i class="fa fa-star"></i></td>
  												<td><i class="fa fa-circle"></i></td>
  												<td class="subject">Finance</td>
  												<td class="body">Body can be lorem ipsum is a dummy text to be unknown text or unrelated to text to you or me </td>
  												<td><label class="label bg-warning">Finance</label></td>
  												<td class="time">10:00 PM</td>
  											</tr>
  											<tr>
  												<td><i class="fa fa-check-square-o fa-square-o"></i></td>
  												<td><i class="fa fa-star"></i></td>
  												<td><i class="fa fa-circle"></i></td>
  												<td class="subject">Hello <i class="fa fa-paperclip"></i></td>
  												<td class="body">Hope you are doing good today! Please make a call when you are free.</td>
  												<td><label class="label bg-pink">Family</label></td>
  												<td class="time">07:58 PM</td>
  											</tr>
  											<tr>
  												<td><i class="fa fa-check-square-o fa-square-o"></i></td>
  												<td><i class="fa fa-star"></i></td>
  												<td><i class="fa fa-circle"></i></td>
  												<td class="subject">Promotion <i class="fa fa-paperclip"></i></td>
  												<td class="body">Body can be lorem ipsum is a dummy text to be unknown text or unrelated to text to you or me </td>
  												<td><label class="label bg-success">Promotions</label></td>
  												<td class="time">03:47 PM</td>
  											</tr>
  											<tr>
  												<td><i class="fa fa-check-square-o fa-square-o"></i></td>
  												<td><i class="fa fa-star"></i></td>
  												<td><i class="fa fa-circle"></i></td>
  												<td class="subject">Mario</td>
  												<td class="body">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. </td>
  												<td><label class="label bg-info">Games</label></td>
  												<td class="time">01:28 PM</td>
  											</tr>
  											<tr>
  												<td><i class="fa fa-check-square-o fa-square-o"></i></td>
  												<td><i class="fa fa-star"></i></td>
  												<td><i class="fa fa-circle"></i></td>
  												<td class="subject">HTML <i class="fa fa-paperclip"></i></td>
  												<td class="body">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever</td>
  												<td><label class="label bg-purple">Work</label></td>
  												<td class="time">09:12 AM</td>
  											</tr>
  											<tr>
  												<td><i class="fa fa-check-square-o fa-square-o"></i></td>
  												<td><i class="fa fa-star"></i></td>
  												<td><i class="fa fa-circle"></i></td>
  												<td class="subject">Chits <i class="fa fa-paperclip"></i></td>
  												<td class="body">Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections</td>
  												<td><label class="label bg-warning">Finance</label></td>
  												<td class="time">08:58 AM</td>
  											</tr>
  											<tr>
  												<td><i class="fa fa-check-square-o fa-square-o"></i></td>
  												<td><i class="fa fa-star"></i></td>
  												<td><i class="fa fa-circle"></i></td>
  												<td class="subject">Promotions <i class="fa fa-paperclip"></i></td>
  												<td class="body">All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. </td>
  												<td><label class="label bg-success">Promotions</label></td>
  												<td class="time">07:20 AM</td>
  											</tr>
  											<tr>
  												<td><i class="fa fa-check-square-o fa-square-o"></i></td>
  												<td><i class="fa fa-star"></i></td>
  												<td><i class="fa fa-circle"></i></td>
  												<td class="subject">shopping</td>
  												<td class="body">Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock,</td>
  												<td><label class="label bg-primary">Shopping</label></td>
  												<td class="time">06:58 AM</td>
  											</tr>
  											<tr>
  												<td><i class="fa fa-check-square-o fa-square-o"></i></td>
  												<td><i class="fa fa-star"></i></td>
  												<td><i class="fa fa-circle"></i></td>
  												<td class="subject">Shopping</td>
  												<td class="body">The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. </td>
  												<td><label class="label bg-primary">Shopping</label></td>
  												<td class="time">04:32 AM</td>
  											</tr>
  											<tr>
  												<td><i class="fa fa-check-square-o fa-square-o"></i></td>
  												<td><i class="fa fa-star"></i></td>
  												<td><i class="fa fa-circle"></i></td>
  												<td class="subject">Games</td>
  												<td class="body">All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. </td>
  												<td><label class="label bg-info">Games</label></td>
  												<td class="time">03:26 AM</td>
  											</tr>
  											<tr>
  												<td><i class="fa fa-check-square-o fa-square-o"></i></td>
  												<td><i class="fa fa-star"></i></td>
  												<td><i class="fa fa-circle"></i></td>
  												<td class="subject">Subject <i class="fa fa-paperclip"></i></td>
  												<td class="body">Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections</td>
  												<td><label class="label bg-purple">Work</label></td>
  												<td class="time">10:58 PM</td>
  											</tr>
  											<tr>
  												<td><i class="fa fa-check-square-o fa-square-o"></i></td>
  												<td><i class="fa fa-star"></i></td>
  												<td><i class="fa fa-circle"></i></td>
  												<td class="subject">Shopping</td>
  												<td class="body">The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. </td>
  												<td><label class="label bg-primary">Shopping</label></td>
  												<td class="time">08:58 PM</td>
  											</tr>
  											<tr>
  												<td><i class="fa fa-check-square-o fa-square-o"></i></td>
  												<td><i class="fa fa-star"></i></td>
  												<td><i class="fa fa-circle"></i></td>
  												<td class="subject">Family <i class="fa fa-paperclip"></i></td>
  												<td class="body">All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. </td>
  												<td><label class="label bg-pink">Family</label></td>
  												<td class="time">06:47 PM</td>
  											</tr>
  											<tr>
  												<td><i class="fa fa-check-square-o fa-square-o"></i></td>
  												<td><i class="fa fa-star"></i></td>
  												<td><i class="fa fa-circle"></i></td>
  												<td class="subject">Shopping</td>
  												<td class="body">Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections</td>
  												<td><label class="label bg-primary">Shopping</label></td>
  												<td class="time">05:26 PM</td>
  											</tr>
  											<tr>
  												<td><i class="fa fa-check-square-o fa-square-o"></i></td>
  												<td><i class="fa fa-star"></i></td>
  												<td><i class="fa fa-circle"></i></td>
  												<td class="subject">Promotions <i class="fa fa-paperclip"></i></td>
  												<td class="body">All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. </td>
  												<td><label class="label bg-success">Promotions</label></td>
  												<td class="time">03:17 PM</td>
  											</tr>
  											<tr>
  												<td><i class="fa fa-check-square-o fa-square-o"></i></td>
  												<td><i class="fa fa-star"></i></td>
  												<td><i class="fa fa-circle"></i></td>
  												<td class="subject">Shopping</td>
  												<td class="body">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever</td>
  												<td><label class="label bg-primary">Shopping</label></td>
  												<td class="time">01:11 PM</td>
  											</tr>
  											<tr>
  												<td><i class="fa fa-check-square-o fa-square-o"></i></td>
  												<td><i class="fa fa-star"></i></td>
  												<td><i class="fa fa-circle"></i></td>
  												<td class="subject">finance</td>
  												<td class="body">Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections</td>
  												<td><label class="label bg-warning">Finance</label></td>
  												<td class="time">06:46 AM</td>
  											</tr>
  											<tr>
  												<td><i class="fa fa-check-square-o fa-square-o"></i></td>
  												<td><i class="fa fa-star"></i></td>
  												<td><i class="fa fa-circle"></i></td>
  												<td class="subject">Promotions <i class="fa fa-paperclip"></i></td>
  												<td class="body">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever</td>
  												<td><label class="label label-success">Promotions</label></td>
  												<td class="time">06:32 AM</td>
  											</tr>
  										</table>
  									</div>

  								</div><!-- /Right Side mail bar -->
  								<!-- Modal -->
  								<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  									<div class="modal-dialog">
  										<div class="modal-content">
  											<div class="modal-header bg-primary text-white">
  												<button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true">&times;</button>
  												<h3 class="modal-title">Subject</h3>
  											</div>
  											<div class="modal-body">
  												<div class="icon-show">

  												</div>
  											</div>
  											<div class="modal-footer">
  												<textarea class="form-control" rows="4"></textarea>
  												<br />
  												<button type="button" class="btn bg-primary text-white">Send Reply! </button>
  											</div>
  										</div><!-- /.modal-content -->
  									</div><!-- /.modal-dialog -->
  								</div><!-- /.modal -->

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


<!-- Page Scripts   =============================-->
<script src="js/mail-custom.js"></script>

<!-- Core Jquery File  =============================-->
<script src="js/core.js"></script>


</body>
</html>