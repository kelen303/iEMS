<?php include('partials/header.php'); ?>  


<div class="row">
 <div class="col-mod-12">

  <ul class="breadcrumb">
   <li><a href="index.php">Dashboard</a></li>
   <li><a href="#">Forms</a></li>
   <li class="active">Wysiwyg &amp; Markdown</li>
 </ul>

 <div class="form-group hiddn-minibar pull-right">
  <input type="text" class="form-control form-cascade-control nav-input-search" size="20" placeholder="Search through site" />

  <span class="input-icon fui-search"></span>
</div>

<h3 class="page-header"><i class="fa fa-pencil-square"></i> Wysiwyg &amp; Markdown <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>

<blockquote class="page-information hidden">
 <p>
  WYSIWYG and Markdown implies a user interface that allows the user to view something very similar to the end result while the document is being created. In general WYSIWYG implies the ability to directly manipulate the layout of a document without having to type or remember names of layout commands.
</p>
</blockquote>

</div>
</div>

<!-- WYSIWYG Editor -->
<div class="row  hidden-xs">
 <div class="col-md-12">
  <div class="panel panel-archon panel-wysiwyg">
   <div class="panel-heading">
    <h3 class="panel-title">
     WYSIWYG Editor
     <span class="pull-right">
      <a  href="#" class="panel-minimize"><i class="fa fa-chevron-up"></i></a>
      <a  href="#"  class="panel-settings"><i class="fa fa-cog"></i></a>
      <a  href="#"  class="panel-close"><i class="fa fa-times"></i></a>
    </span>
  </h3>
</div>
<div class="panel-body toolbar-menu">
  <div id="alerts"></div>
  <div class="btn-toolbar" data-role="editor-toolbar" data-target="#editor">
   <div class="btn-group styles">
    <a class="btn dropdown-toggle " data-toggle="dropdown" title="Font"><i class="fa fa-font"></i><b class="caret"></b></a>
    <ul class="dropdown-menu">
      <li><a data-edit="fontName Serif" style="font-family:'Serif'">Serif</a></li>
      <li><a data-edit="fontName Sans" style="font-family:'Sans'">Sans</a></li>
      <li><a data-edit="fontName Arial" style="font-family:'Arial'">Arial</a></li>
      <li> <a data-edit="fontName Arial Black" style="font-family:'Arial Black'">Arial Black</a></li>
      <li><a data-edit="fontName Courier" style="font-family:'Courier'">Courier</a></li>
      <li> <a data-edit="fontName Courier New" style="font-family:'Courier New'">Courier New</a></li>
      <li><a data-edit="fontName Comic Sans MS" style="font-family:'Comic Sans MS'">Comic Sans MS</a></li>
      <li><a data-edit="fontName Helvetica" style="font-family:'Helvetica'">Helvetica</a></li>
      <li><a data-edit="fontName Impact" style="font-family:'Impact'">Impact</a></li>
      <li><a data-edit="fontName Lucida Grande" style="font-family:'Lucida Grande'">Lucida Grande</a></li>
      <li><a data-edit="fontName Lucida Sans" style="font-family:'Lucida Sans'">Lucida Sans</a></li>
      <li><a data-edit="fontName Tahoma" style="font-family:'Tahoma'">Tahoma</a></li>
      <li><a data-edit="fontName Times" style="font-family:'Times'">Times</a></li>
      <li> <a data-edit="fontName Times New Roman" style="font-family:'Times New Roman'">Times New Roman</a></li>
      <li> <a data-edit="fontName Verdana" style="font-family:'Verdana'">Verdana</a></li>

    </ul>
  </div>
  <div class="btn-group size">
    <a class="btn dropdown-toggle " data-toggle="dropdown" title="Font Size"><i class="fa fa-text-height"></i>&nbsp;<b class="caret"></b></a>
    <ul class="dropdown-menu">
     <li><a data-edit="fontSize 5"><font size="5">Huge</font></a></li>
     <li><a data-edit="fontSize 3"><font size="3">Normal</font></a></li>
     <li><a data-edit="fontSize 1"><font size="1">Small</font></a></li>
   </ul>
 </div>
 <div class="btn-group btn-animate-demo text-style">
  <a class="btn btn-animate-demo" data-edit="bold" title="Bold (Ctrl/Cmd+B)"><i class="fa fa-bold"></i></a>
  <a class="btn btn-animate-demo" data-edit="italic" title="Italic (Ctrl/Cmd+I)"><i class="fa fa-italic"></i></a>
  <a class="btn btn-animate-demo" data-edit="strikethrough" title="Strikethrough"><i class="fa fa-strikethrough"></i></a>
  <a class="btn btn-animate-demo " data-edit="underline" title="Underline (Ctrl/Cmd+U)"><i class="fa fa-underline"></i></a>
</div>
<div class="btn-group btn-animate-demo">
  <a class="btn" data-edit="insertunorderedlist" title="Bullet list"><i class="fa fa-list-ul"></i></a>
  <a class="btn" data-edit="insertorderedlist" title="Number list"><i class="fa fa-list-ol"></i></a>
  <a class="btn" data-edit="outdent" title="Reduce indent (Shift+Tab)"><i class="fa fa-indent"></i></a>
  <a class="btn" data-edit="indent" title="Indent (Tab)"><i class="fa fa-indent"></i></a>
</div>
<div class="btn-group btn-animate-demo">
  <a class="btn" data-edit="justifyleft" title="Align Left (Ctrl/Cmd+L)"><i class="fa fa-align-left"></i></a>
  <a class="btn" data-edit="justifycenter" title="Center (Ctrl/Cmd+E)"><i class="fa fa-align-center"></i></a>
  <a class="btn" data-edit="justifyright" title="Align Right (Ctrl/Cmd+R)"><i class="fa fa-align-right"></i></a>
  <a class="btn" data-edit="justifyfull" title="Justify (Ctrl/Cmd+J)"><i class="fa fa-align-justify"></i></a>
</div>
<div class="btn-group add-url">
  <a class="btn dropdown-toggle" data-toggle="dropdown" title="Hyperlink"><i class="fa fa-link"></i></a>
  <div class="dropdown-menu input-append">
   <input class="span2" placeholder="URL" type="text" data-edit="createLink"/>
   <button class="btn" type="button">Add</button>
 </div>
 <a class="btn cut" data-edit="unlink" title="Remove Hyperlink"><i class="fa fa-cut"></i></a>

</div>

<div class="btn-group file-upload">
  <a class="btn" title="Insert picture (or just drag &amp; drop)" id="pictureBtn"><i class="fa fa-picture-o"></i></a>
  <input type="file" data-role="magic-overlay" data-target="#pictureBtn" data-edit="insertImage" />
</div>
<div class="btn-group undo-redo">
  <a class="btn" data-edit="undo" title="Undo (Ctrl/Cmd+Z)"><i class="fa fa-undo"></i></a>
  <a class="btn" data-edit="redo" title="Redo (Ctrl/Cmd+Y)"><i class="fa fa-repeat"></i></a>
</div>
<input class="mic" type="text" data-edit="inserttext" id="voiceBtn" x-webkit-speech="">
</div>

<div id="editor">
 Go ahead&hellip;
</div>
</div>
</div>
</div> <!-- / col-md-12 -->
</div>


<div class="row">
 <div class="col-md-12">
  <div class="panel panel-cascade">
   <div class="panel-heading">
    <h3 class="panel-title">
     MarkDown editor
     <span class="pull-right">
      <a  href="#" class="panel-minimize"><i class="fa fa-chevron-up"></i></a>
      <a  href="#"  class="panel-settings"><i class="fa fa-cog"></i></a>
      <a  href="#"  class="panel-close"><i class="fa fa-times"></i></a>
    </span>
  </h3>
</div>
<div class="panel-body">
  <div data-provide="markdown-editable">

  </div>
  <div>
    <textarea class="textarea" placeholder="Go ahead ..." style="width: 1050px; height: 200px"></textarea>
  </div>				
</div>
</div><!-- / panel -->
</div>
</div> <!-- / Markdown editor -->

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



<!-- Page scripts-->
<script src="js/jquery.autosize.min.js"></script>
<script src="js/jquery.hotkeys.min.js" ></script>
<script src="js/bootstrap-colorpicker.js"></script>
<script src="js/bootstrap-wysiwyg.js"></script>
<script src="js/bootstrap-markdown.js"></script>

<script src="js/wysiwyg-custom.js"></script>


<script src="js/wysihtml5-0.3.0.js"></script>
<script src="js/bootstrap-wysihtml5.js"></script>

<!-- Core Jquery File  =============================-->
<script src="js/core.js"></script>

<script>  
  $('document').ready(function(){
    $('.textarea').wysihtml5();
    $(prettyPrint);
  });
</script>

</body>
</html>