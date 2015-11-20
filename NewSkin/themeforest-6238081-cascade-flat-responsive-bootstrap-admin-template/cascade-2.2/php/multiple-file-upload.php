<?php include('partials/header.php'); ?>

                <div class="row">
                 <div class="col-mod-12">

                  <ul class="breadcrumb">
                   <li><a href="index.php">Dashboard</a></li>
                   <li><a href="#">Forms</a></li>
                   <li class="active">Multiple File Upload</li>
                 </ul>
                 
                 <div class="form-group hiddn-minibar pull-right">
                  <input type="text" class="form-control form-cascade-control nav-input-search" size="20" placeholder="Search through site" />

                  <span class="input-icon fui-search"></span>
                </div>

                <h3 class="page-header"><i class="fa fa-cloud-upload"></i> Multiple File Upload <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>

                <blockquote class="page-information hidden">
                 <p>
                  Using Jquery we can validate the forms before sending to a server which minimizes the server load. This can be one  using a jquery plugin called validate.js , 
                  I have included it with enough examples on this page. For eg the first input checks for a email is input or not and also it should be required. 
                  It checks the format of someone@example.com 
                </p>
              </blockquote>

            </div>
          </div>

          <!-- Demo Panel -->
          <div class="row">
           <div class="col-md-12">
            <blockquote>
             <p>File Upload widget with multiple file selection, drag&amp;drop support, progress bars, validation and preview images, audio and video for AngularJS.<br>
              Supports cross-domain, chunked and resumable file uploads and client-side image resizing.<br>
              Works with any server-side platform (PHP, Python, Ruby on Rails, Java, Node.js, Go etc.) that supports standard HTML form file uploads.</p>
            </blockquote>
            <!-- The file upload form used as target for the file upload widget -->
            <form id="fileupload" action="//jquery-file-upload.appspot.com/" method="POST" enctype="multipart/form-data" data-ng-app="demo" data-ng-controller="DemoFileUploadController" data-file-upload="options" data-ng-class="{'fileupload-processing': processing() || loadingFiles}">
              <!-- Redirect browsers with JavaScript disabled to the origin page -->
              <noscript><input type="hidden" name="redirect" value="http://blueimp.github.io/jQuery-File-Upload/"></noscript>
              <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
              <div class="row fileupload-buttonbar">
               <div class="col-lg-7">
                <!-- The fileinput-button span is used to style the file input field as button -->
                <span class="btn btn-success fileinput-button" ng-class="{disabled: disabled}">
                 <i class="glyphicon glyphicon-plus"></i>
                 <span>Add files...</span>
                 <input type="file" name="files[]" multiple ng-disabled="disabled">
               </span>
               <button type="button" class="btn btn-primary start" data-ng-click="submit()">
                 <i class="glyphicon glyphicon-upload"></i>
                 <span>Start upload</span>
               </button>
               <button type="button" class="btn btn-warning cancel" data-ng-click="cancel()">
                 <i class="glyphicon glyphicon-ban-circle"></i>
                 <span>Cancel upload</span>
               </button>
               <!-- The loading indicator is shown during file processing -->
               <div class="fileupload-loading"></div>
             </div>
             <!-- The global progress information -->
             <div class="col-lg-5 fade" data-ng-class="{in: active()}">
              <!-- The global progress bar -->
              <div class="progress progress-striped active" data-file-upload-progress="progress()"><div class="progress-bar progress-bar-success" data-ng-style="{width: num + '%'}"></div></div>
              <!-- The extended global progress information -->
              <div class="progress-extended">&nbsp;</div>
            </div>
          </div>
          <!-- The table listing the files available for upload/download -->
          <table class="table table-striped files ng-cloak">
           <tr data-ng-repeat="file in queue">
            <td data-ng-switch data-on="!!file.thumbnailUrl">
             <div class="preview" data-ng-switch-when="true">
              <a data-ng-href="{{file.url}}" title="{{file.name}}" download="{{file.name}}" data-gallery><img data-ng-src="{{file.thumbnailUrl}}" alt=""></a>
            </div>
            <div class="preview" data-ng-switch-default data-file-upload-preview="file"></div>
          </td>
          <td>
           <p class="name" data-ng-switch data-on="!!file.url">
            <span data-ng-switch-when="true" data-ng-switch data-on="!!file.thumbnailUrl">
             <a data-ng-switch-when="true" data-ng-href="{{file.url}}" title="{{file.name}}" download="{{file.name}}" data-gallery>{{file.name}}</a>
             <a data-ng-switch-default data-ng-href="{{file.url}}" title="{{file.name}}" download="{{file.name}}">{{file.name}}</a>
           </span>
           <span data-ng-switch-default>{{file.name}}</span>
         </p>
         <div data-ng-show="file.error"><span class="label label-danger">Error</span> {{file.error}}</div>
       </td>
       <td>
         <p class="size">{{file.size | formatFileSize}}</p>
         <div class="progress progress-striped active fade" data-ng-class="{pending: 'in'}[file.$state()]" data-file-upload-progress="file.$progress()"><div class="progress-bar progress-bar-success" data-ng-style="{width: num + '%'}"></div></div>
       </td>
       <td>
         <button type="button" class="btn btn-primary start" data-ng-click="file.$submit()" data-ng-hide="!file.$submit">
          <i class="glyphicon glyphicon-upload"></i>
          <span>Start</span>
        </button>
        <button type="button" class="btn btn-warning cancel" data-ng-click="file.$cancel()" data-ng-hide="!file.$cancel">
          <i class="glyphicon glyphicon-ban-circle"></i>
          <span>Cancel</span>
        </button>
        <button data-ng-controller="FileDestroyController" type="button" class="btn btn-danger destroy" data-ng-click="file.$destroy()" data-ng-hide="!file.$destroy">
          <i class="glyphicon glyphicon-trash"></i>
          <span>Delete</span>
        </button>
      </td>
    </tr>
  </table>
</form>
<div class="panel panel-cascade">
  <div class="panel-heading">
   <h3 class="panel-title">Demo Notes</h3>
 </div>
 <div class="panel-body">
   <ul>
    <li>The maximum file size for uploads in this demo is <strong>5 MB</strong> (default file size is unlimited).</li>
    <li>Only image files (<strong>JPG, GIF, PNG</strong>) are allowed in this demo (by default there is no file type restriction).</li>
    <li>Uploaded files will be deleted automatically after <strong>5 minutes</strong> (demo setting).</li>
    <li>You can <strong>drag &amp; drop</strong> files from your desktop on this webpage (see <a href="https://github.com/blueimp/jQuery-File-Upload/wiki/Browser-support">Browser support</a>).</li>
    <li>Please refer to the <a href="https://github.com/blueimp/jQuery-File-Upload">project website</a> and <a href="https://github.com/blueimp/jQuery-File-Upload/wiki">documentation</a> for more information.</li>
    <li>Built with Twitter's <a href="http://twitter.github.com/bootstrap/">Bootstrap</a> CSS framework and Icons from <a href="http://glyphicons.com/">Glyphicons</a>.</li>
  </ul>
</div>
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


<!-- page Scripts  =============================-->
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.0.5/angular.min.js"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="http://blueimp.github.io/JavaScript-Load-Image/js/load-image.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="http://blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
<!-- Bootstrap JS is not required, but included for the responsive demo navigation -->
<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
<!-- blueimp Gallery script -->
<script src="http://blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="js/blueimp/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="js/blueimp/jquery.fileupload.js"></script>
<!-- The File Upload processing plugin -->
<script src="js/blueimp/jquery.fileupload-process.js"></script>
<!-- The File Upload image preview & resize plugin -->
<script src="js/blueimp/jquery.fileupload-image.js"></script>
<!-- The File Upload audio preview plugin -->
<script src="js/blueimp/jquery.fileupload-audio.js"></script>
<!-- The File Upload video preview plugin -->
<script src="js/blueimp/jquery.fileupload-video.js"></script>
<!-- The File Upload validation plugin -->
<script src="js/blueimp/jquery.fileupload-validate.js"></script>
<!-- The File Upload Angular JS module -->
<script src="js/blueimp/jquery.fileupload-angular.js"></script>
<!-- The main application script -->
<script src="js/blueimp/app.js"></script>

<!-- Core Jquery File  =============================-->
<script src="js/core.js"></script>


</body>
</html>