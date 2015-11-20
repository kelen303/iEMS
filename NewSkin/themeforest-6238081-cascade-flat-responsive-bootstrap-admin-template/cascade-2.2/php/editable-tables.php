<?php include('partials/header.php'); ?>
                <div class="row">
                 <div class="col-mod-12">

                  <ul class="breadcrumb">
                   <li><a href="index.php">Dashboard</a></li>
                   <li><a href="#">Tables</a></li>
                   <li class="active">Editable Tables</li>
                 </ul>
                 
                 <div class="form-group hiddn-minibar pull-right">
                  <input type="text" class="form-control form-cascade-control nav-input-search" size="20" placeholder="Search through site" />

                  <span class="input-icon fui-search"></span>
                </div>

                <h3 class="page-header"> Editable Tables <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>

                <blockquote class="page-information hidden">
                 <p>
                  With editable tables, you can edit information within a table instantly and are more flexible to add or edit or delete any sort of data.
                </p>
              </blockquote>
            </div>
          </div>

          <div class="row">
           <div class="col-md-12">
            <div class="panel panel-archon">
             <div class="panel-heading">
              <h3 class="panel-title">Inline Table Editing
               <span class="pull-right">
                <a  href="#" class="panel-minimize"><i class="fa fa-chevron-up"></i></a>
                <a  href="#"  class="panel-settings"><i class="fa fa-cog"></i></a>
                <a  href="#"  class="panel-close"><i class="fa fa-times"></i></a>
              </span>
            </h3>
          </div> <!-- /panel heading -->
          <div class="panel-body">
            <table class="table table-striped table-condensed table-bordered">
             <tbody>
              <tr><td>Username : </td><td><a href="#" id="username" data-type="text" data-placement="right" data-title="Enter username">superuser</a></td></tr>
              <tr><td>Status: </td><td><a href="#" id="status"></a></td></tr>
              <tr><td>Textarea</td><td><a href="#" id="comments" data-type="textarea" data-pk="1">awesome comment!</a></td></tr>
              <tr><td>Date</td><td><a href="#" id="dob" data-type="date" data-pk="1" data-title="Select date">15/05/1984</a></td></tr>
              <tr><td>Date Time</td><td><a href="#" id="last_seen" data-type="datetime" data-pk="1"  title="Select date &amp; time">15/03/2013 12:45</a></td></tr>
            </tbody>
          </table>
          
        </div> <!-- /panel body -->
      </div>
    </div> <!-- /col-md-12 -->
  </div>

  <!-- todo list -->
  <div class="row">
   <div class="col-md-12">
    <div class="panel panel-archon panel-todo">
     <div class="panel-heading">
      <h3 class="panel-title">Todo List 
       <span class="pull-right">
        <a  href="#" class="panel-minimize"><i class="fa fa-chevron-up"></i></a>
        <a  href="#"  class="panel-settings"><i class="fa fa-cog"></i></a>
        <a  href="#"  class="panel-close"><i class="fa fa-times"></i></a>
      </span>
    </h3>
  </div>
  <div class="panel-body">
    <table class="todo-table table table-bordered">
     <tr class="header-row">
      <th><i class="fa fa-check-square-o finish-task fa-square-o"></i></th>
      <th>Subject</th>
      <th>Time</th>
      <th>Delete</th>
      
    </tr>
    <tr >
      <td><i class="fa fa-check-square-o finish-task fa-square-o"></i></td>
      <td><span class="description">Check to finish this task</span></td>
      <td><span class="time badge bg-warning">Tomorrow</span></td>
      <td><a class="btn btn-danger btn-xs btn-delete"><i class="fa fa-trash-o"</i> Delete?</a></td>
    </tr>
    <tr>
      <td><i class="fa fa-check-square-o finish-task fa-square-o"></i></td>
      <td><span class="description">Click delete to remove me</span></td>
      <td><span class="time badge bg-warning">Tomorrow</span></td>
      <td><a class="btn btn-danger btn-xs btn-delete"><i class="fa fa-trash-o"</i> Delete?</a></td>
    </tr>
    <tr>
      <td><i class="fa fa-check-square-o finish-task fa-square-o"></i></td>
      <td><span class="description">Feed the dog with milk</span></td>
      <td><span class="time badge bg-purple">Night</span></td>
      <td><a class="btn btn-danger btn-xs btn-delete"><i class="fa fa-trash-o"</i> Delete?</a></td>
    </tr>
    <tr>
      <td><i class="fa fa-check-square-o finish-task fa-square-o"></i></td>
      <td><span class="description">Body workout for 2 hours</span></td>
      <td><span class="time badge bg-purple">Evening</span></td>
      <td><a class="btn btn-danger btn-xs btn-delete"><i class="fa fa-trash-o"</i> Delete?</a></td>
    </tr>
    <tr>
      <td><i class="fa fa-check-square-o finish-task fa-square-o"></i></td>
      <td><span class="description">Meeting with Charlie about his new idea</span></td>
      <td><span class="time badge bg-success">Saturday</span></td>
      <td><a class="btn btn-danger btn-xs btn-delete"><i class="fa fa-trash-o"</i> Delete?</a></td>
    </tr>
    <tr>
      <td><i class="fa fa-check-square-o finish-task fa-square-o"></i></td>
      <td><span class="description">Watch Cricket match Team A vs Team B</span></td>
      <td><span class="time badge bg-purple">Morning</span></td>
      <td><a class="btn btn-danger btn-xs btn-delete"><i class="fa fa-trash-o"</i> Delete?</a></td>
    </tr>
    <tr>
      <td><i class="fa fa-check-square-o finish-task fa-square-o"></i></td>
      <td><span class="description">Collect the mobile from repair shop</span></td>
      <td><span class="time badge bg-warning">2 days later</span></td>
      <td><a class="btn btn-danger btn-xs btn-delete"><i class="fa fa-trash-o"</i> Delete?</a></td>
    </tr>
    <tr>
      <td><i class="fa fa-check-square-o finish-task fa-square-o"></i></td>
      <td><span class="description">Dinner with my Girl friend tonight</span></td>
      <td><span class="time badge bg-success">12/25/2013</span></td>
      <td><a class="btn btn-danger btn-xs btn-delete"><i class="fa fa-trash-o"</i> Delete?</a></td>
    </tr>
    <tr>
      <td><i class="fa fa-check-square-o finish-task fa-square-o"></i></td>
      <td><span class="description">Install more plugins </span></td>
      <td><span class="time badge bg-success">later</span></td>
      <td><a class="btn btn-danger btn-xs btn-delete"><i class="fa fa-trash-o"</i> Delete?</a></td>
    </tr>
    <tr>
      <td><i class="fa fa-check-square-o finish-task fa-square-o"></i></td>
      <td><span class="description">Indentation with 2 spaces</span></td>
      <td><span class="time badge bg-purple">now</span></td>
      <td><a class="btn btn-danger btn-xs btn-delete"><i class="fa fa-trash-o"</i> Delete?</a></td>
    </tr>
    <tr>
      <td><i class="fa fa-check-square-o finish-task fa-square-o"></i></td>
      <td><span class="description">Clean up clode</span></td>
      <td><span class="time badge bg-purple">Next hour</span></td>
      <td><a class="btn btn-danger btn-xs btn-delete"><i class="fa fa-trash-o"</i> Delete?</a></td>
    </tr>
    <tr  class="finish">
      <td><i class="fa fa-check-square-o finish-task"></i></td>
      <td><span class="description">Call with a customer for resolution</span></td>
      <td><span class="time badge bg-purple">Afternoon</span></td>
      <td><a class="btn btn-danger btn-xs btn-delete"><i class="fa fa-trash-o"</i> Delete?</a></td>
    </tr>
    <tr>
      <td><i class="fa fa-check-square-o finish-task fa-square-o"></i></td>
      <td><span class="description">Visit Doc for dental check up</span></td>
      <td><span class="time badge bg-success">when possible</span></td>
      <td><a class="btn btn-danger btn-xs btn-delete"><i class="fa fa-trash-o"</i> Delete?</a></td>
    </tr>
    <tr>
      <td><i class="fa fa-check-square-o finish-task fa-square-o"></i></td>
      <td><span class="description">One more todo </span></td>
      <td><span class="time badge bg-warning">Today</span></td>
      <td><a class="btn btn-danger btn-xs btn-delete"><i class="fa fa-trash-o"</i> Delete?</a></td>
    </tr>
    <tr>
      <td><i class="fa fa-check-square-o finish-task fa-square-o"></i></td>
      <td><span class="description">Nothing else for now</span></td>
      <td><span class="time badge bg-success">ok</span></td>
      <td><a class="btn btn-danger btn-xs btn-delete"><i class="fa fa-trash-o"</i> Delete?</a></td>
    </tr>
  </table>
</div>
</div>						
</div><!-- / col-md-12 -->
</div>	
<!-- /todo list -->


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

<!-- Page Scripts =============================-->
<script type="text/javascript" src="js/bootstrap-editable.min.js"></script>
<script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="js/bootstrap-editable-custom.js"></script>

<!-- Core JS =============================-->
<script src="js/core.js"></script>
</body>
</html>