<?php include('partials/header.php'); ?>
    <div class="row">
                 <div class="col-mod-12">

                  <ul class="breadcrumb">
                   <li><a href="index.php">Dashboard</a></li>
                   <li><a href="gallery.php">Gallery</a></li>
                   <li class="active">BreadCrumb</li>
                 </ul>
                 
                 <div class="form-group hiddn-minibar pull-right">
                  <input type="text" class="form-control form-cascade-control nav-input-search" size="20" placeholder="Search through site" />

                  <span class="input-icon fui-search"></span>
                </div>

                <h3 class="page-header"><i class="fa fa-picture-o"></i> Gallery <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>

                <blockquote class="page-information hidden">
                 <p>
                  Gallery is the page to preview your snaps with <b>"Isotope Plugin"</b>. With this plugin you can manage and preview all your snaps according to their own categories. <b>Sorting</b> the images adds an extra feature!
                </p>
              </blockquote>

            </div>
          </div>

          <div class="row">
           <div class="col-md-12">
            <div class="panel panel-cascade">
             <div class="panel-heading">
              <h3 class="panel-title">
               Gallery
               <span class="pull-right">
                <div class="btn-group code">
                 <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Classes used"><i class="fa fa-code"></i></a>
                 <ul class="dropdown-menu  pull-right list-group" role="menu">
                  <li class="list-group-item"><code>.gallery-items</code></li>
                  <li class="list-group-item"><code>.panel-gallery</code></li>
                  <li class="list-group-item"><code>#grid</code></li>
                </ul>
              </div>
              <a href="#" class="panel-minimize"><i class="fa fa-chevron-up"></i></a>
              <a href="#" class="panel-close"><i class="fa fa-times"></i></a>
            </span>
          </h3>
        </div>
        <div class="panel-body gallery">
          <div class="controls">
           <ul class="list-inline pull-left ">
            <li class="filter" data-filter="mix"><a class="btn btn-primary" href="#">All</a></li>
            <li class="filter" data-filter="cats"><a class="btn btn-success" href="#">Cats</a></li>
            <li class="filter" data-filter="dogs"><a class="btn btn-warning" href="#">dogs</a></li>
            <li class="filter" data-filter="dogs cats"><a class="btn bg-purple text-white" href="#">Dogs &amp; Cats</a></li>
          </ul>
          <ul class="list-inline pull-right">
            <li class="sort" data-sort="data-name" data-order="desc"><a class="btn bg-pink text-white" href="#">ASC</a></li>
            <li class="sort" data-sort="data-name" data-order="asc"><a class="btn bg-pink text-white" href="#">DSC</a></li>
          </ul>
        </div>
        <!-- Gallery Items -->
        <div class="row">
         <ul class="list-inline gallery-items" id="Grid">
          <li class="mix dogs " data-name="puffy">
           <div class="panel panel-cascade panel-gallery ">
            <div class="panel-heading">
             <h2 class="panel-title">Puffy Dog
             </h2>
           </div>
           <div class="panel-body nopadding">
             <img src="images/gallery/1Dog.jpg" alt=""> 
           </div>
           <div class="panel-footer">
             <h3>
              <a href="#" class="btn btn-info text-white"><i class="fa fa-link"></i> </a> <a href="images/gallery/1Dog.jpg" data-lightbox="image-1" title="Cutee Cat"  class="btn bg-purple text-white"><i class="fa fa-search"></i> </a>
            </h3>
          </div>
        </div>
      </li>
      <li  class="mix cats " data-name="Snow">
       <div class="panel panel-cascade panel-gallery ">
        <div class="panel-heading">
         <h2 class="panel-title">Snow Cat
         </h2>
       </div>
       <div class="panel-body nopadding">
         <img src="images/gallery/2Cat.jpg" alt=""> 
       </div>
       <div class="panel-footer">
         <h3>
          <a href="#" class="btn btn-info text-white"><i class="fa fa-link"></i> </a> <a href="images/gallery/2Cat.jpg" data-lightbox="image-1" title="Cutee Cat"  class="btn bg-purple text-white"><i class="fa fa-search"></i> </a>
        </h3>
      </div>
    </div>
  </li>
  <li  class="mix dogs " data-name="Cute">
   <div class="panel panel-cascade panel-gallery ">
    <div class="panel-heading">
     <h2 class="panel-title">Cute Dog
     </h2>
   </div>
   <div class="panel-body nopadding">
     <img src="images/gallery/2Dog.jpg" alt=""> 
   </div>
   <div class="panel-footer">
     <h3>
      <a href="#" class="btn btn-info text-white"><i class="fa fa-link"></i> </a> <a href="images/gallery/2Dog.jpg" data-lightbox="image-1" title="Cutee Cat"  class="btn bg-purple text-white"><i class="fa fa-search"></i> </a>
    </h3>
  </div>
</div>
</li>
<li  class="mix cats " data-name="Cutee">
 <div class="panel panel-cascade panel-gallery ">
  <div class="panel-heading">
   <h2 class="panel-title">Cutee Cat
   </h2>
 </div>
 <div class="panel-body nopadding">
   <img src="images/gallery/1Cat.jpg" alt=""> 
 </div>
 <div class="panel-footer">
   <h3>
    <a href="#" class="btn btn-info text-white"><i class="fa fa-link"></i> </a> 
    <a href="images/gallery/1Cat.jpg" data-lightbox="image-1" title="Cutee Cat"  class="btn bg-purple text-white"><i class="fa fa-search"></i> </a>
  </h3>
</div>
</div>
</li>
<!-- second row -->
<li  class="mix dogs " data-name="Kiddy">
 <div class="panel panel-cascade panel-gallery ">
  <div class="panel-heading">
   <h2 class="panel-title">Kiddy Dog
   </h2>
 </div>
 <div class="panel-body nopadding">
   <img src="images/gallery/3Dog.jpg" alt=""> 
 </div>
 <div class="panel-footer">
   <h3>
    <a href="#" class="btn btn-info text-white"><i class="fa fa-link"></i> </a> <a href="images/gallery/3Dog.jpg" data-lightbox="image-1" title="Cutee Cat"  class="btn bg-purple text-white"><i class="fa fa-search"></i> </a>
  </h3>
</div>
</div>
</li>
<li  class="mix cats " data-name="Jumpy">
 <div class="panel panel-cascade panel-gallery ">
  <div class="panel-heading">
   <h2 class="panel-title">Jumpy Cat
   </h2>
 </div>
 <div class="panel-body nopadding">
   <img src="images/gallery/3Cat.jpg" alt=""> 
 </div>
 <div class="panel-footer">
   <h3>
    <a href="#" class="btn btn-info text-white"><i class="fa fa-link"></i> </a> <a href="images/gallery/3Cat.jpg" data-lightbox="image-1" title="Cutee Cat"  class="btn bg-purple text-white"><i class="fa fa-search"></i> </a>
  </h3>
</div>
</div>
</li>
<li  class="mix dogs " data-name="Sleepy">
 <div class="panel panel-cascade panel-gallery ">
  <div class="panel-heading">
   <h2 class="panel-title">Sleepy Dog
   </h2>
 </div>
 <div class="panel-body nopadding">
   <img src="images/gallery/4Dog.jpg" alt=""> 
 </div>
 <div class="panel-footer">
   <h3>
    <a href="#" class="btn btn-info text-white"><i class="fa fa-link"></i> </a> <a href="images/gallery/4Dog.jpg" data-lightbox="image-1" title="Cutee Cat"  class="btn bg-purple text-white"><i class="fa fa-search"></i> </a>
  </h3>
</div>
</div>
</li>
<li  class="mix cats " data-name="staring">
 <div class="panel panel-cascade panel-gallery ">
  <div class="panel-heading">
   <h2 class="panel-title">Staring Cat
   </h2>
 </div>
 <div class="panel-body nopadding">
   <img src="images/gallery/4Cat.jpg" alt=""> 
 </div>
 <div class="panel-footer">
   <h3>
    <a href="#" class="btn btn-info text-white"><i class="fa fa-link"></i> </a> <a href="images/gallery/4Cat.jpg" data-lightbox="image-1" title="Cutee Cat"  class="btn bg-purple text-white"><i class="fa fa-search"></i> </a>
  </h3>
</div>
</div>
</li>
<!-- third row -->
<li  class="mix dogs " data-name="Hairy">
 <div class="panel panel-cascade panel-gallery ">
  <div class="panel-heading">
   <h2 class="panel-title">Hairy Dog
   </h2>
 </div>
 <div class="panel-body nopadding">
   <img src="images/gallery/5Dog.jpg" alt=""> 
 </div>
 <div class="panel-footer">
   <h3>
    <a href="#" class="btn btn-info text-white"><i class="fa fa-link"></i> </a> <a href="images/gallery/5Dog.jpg" data-lightbox="image-1" title="Cutee Cat"  class="btn bg-purple text-white"><i class="fa fa-search"></i> </a>
  </h3>
</div>
</div>
</li>
<li  class="mix cats " data-name="Angry">
 <div class="panel panel-cascade panel-gallery ">
  <div class="panel-heading">
   <h2 class="panel-title">Angry Cat
   </h2>
 </div>
 <div class="panel-body nopadding">
   <img src="images/gallery/5Cat.jpg" alt=""> 
 </div>
 <div class="panel-footer">
   <h3>
    <a href="#" class="btn btn-info text-white"><i class="fa fa-link"></i> </a> <a href="images/gallery/5Cat.jpg" data-lightbox="image-1" title="Cutee Cat"  class="btn bg-purple text-white"><i class="fa fa-search"></i> </a>
  </h3>
</div>
</div>
</li>
<li  class="mix dogs " data-name="Fighter">
 <div class="panel panel-cascade panel-gallery ">
  <div class="panel-heading">
   <h2 class="panel-title">Fighter Dog
   </h2>
 </div>
 <div class="panel-body nopadding">
   <img src="images/gallery/6Dog.jpg" alt=""> 
 </div>
 <div class="panel-footer">
   <h3>
    <a href="#" class="btn btn-info text-white"><i class="fa fa-link"></i> </a> <a href="images/gallery/6Dog.jpg" data-lightbox="image-1" title="Cutee Cat"  class="btn bg-purple text-white"><i class="fa fa-search"></i> </a>
  </h3>
</div>
</div>
</li>
<li  class="mix cats " data-name="Sneeker">
 <div class="panel panel-cascade panel-gallery ">
  <div class="panel-heading">
   <h2 class="panel-title">Sneeker Cat
   </h2>
 </div>
 <div class="panel-body nopadding">
   <img src="images/gallery/6Cat.jpg" alt=""> 
 </div>
 <div class="panel-footer">
   <h3>
    <a href="#" class="btn btn-info text-white"><i class="fa fa-link"></i> </a> <a href="images/gallery/6Cat.jpg" data-lightbox="image-1" title="Cutee Cat"  class="btn bg-purple text-white"><i class="fa fa-search"></i> </a>
  </h3>
</div>
</div>
</li>
<!-- fourth row -->
<li  class="mix dogs " data-name="surprised">
 <div class="panel panel-cascade panel-gallery ">
  <div class="panel-heading">
   <h2 class="panel-title">Surprised Dog
   </h2>
 </div>
 <div class="panel-body nopadding">
   <img src="images/gallery/7Dog.jpg" alt=""> 
 </div>
 <div class="panel-footer">
   <h3>
    <a href="#" class="btn btn-info text-white"><i class="fa fa-link"></i> </a> <a href="images/gallery/7Dog.jpg" data-lightbox="image-1" title="Cutee Cat"  class="btn bg-purple text-white"><i class="fa fa-search"></i> </a>
  </h3>
</div>
</div>
</li>
<li  class="mix cats " data-name="Searcher">
 <div class="panel panel-cascade panel-gallery ">
  <div class="panel-heading">
   <h2 class="panel-title">Searcher Cat
   </h2>
 </div>
 <div class="panel-body nopadding">
   <img src="images/gallery/7Cat.jpg" alt=""> 
 </div>
 <div class="panel-footer">
   <h3>
    <a href="#" class="btn btn-info text-white"><i class="fa fa-link"></i> </a> <a href="images/gallery/7Cat.jpg" data-lightbox="image-1" title="Cutee Cat"  class="btn bg-purple text-white"><i class="fa fa-search"></i> </a>
  </h3>
</div>
</div>
</li>
<li  class="mix dogs " data-name="Stunt">
 <div class="panel panel-cascade panel-gallery ">
  <div class="panel-heading">
   <h2 class="panel-title">Stunt dog
   </h2>
 </div>
 <div class="panel-body nopadding">
   <img src="images/gallery/8Dog.jpg" alt=""> 
 </div>
 <div class="panel-footer">
   <h3>
    <a href="#" class="btn btn-info text-white"><i class="fa fa-link"></i> </a> <a href="images/gallery/8Dog.jpg" data-lightbox="image-1" title="Cutee Cat"  class="btn bg-purple text-white"><i class="fa fa-search"></i> </a>
  </h3>
</div>
</div>
</li>
<li  class="mix cats " data-name="Lovely">
 <div class="panel panel-cascade panel-gallery ">
  <div class="panel-heading">
   <h2 class="panel-title">Lovely Cat
   </h2>
 </div>
 <div class="panel-body nopadding">
   <img src="images/gallery/8Cat.jpg" alt=""> 
 </div>
 <div class="panel-footer">
   <h3>
    <a href="#" class="btn btn-info text-white"><i class="fa fa-link"></i> </a> <a href="images/gallery/8Cat.jpg" data-lightbox="image-1" title="Cutee Cat"  class="btn bg-purple text-white"><i class="fa fa-search"></i> </a>
  </h3>
</div>
</div>
</li>
<!-- fifth row -->
<li  class="mix dogs " data-name="Roaring">
 <div class="panel panel-cascade panel-gallery ">
  <div class="panel-heading">
   <h2 class="panel-title">Roaring Dog
   </h2>
 </div>
 <div class="panel-body nopadding">
   <img src="images/gallery/9Dog.jpg" alt=""> 
 </div>
 <div class="panel-footer">
   <h3>
    <a href="#" class="btn btn-info text-white"><i class="fa fa-link"></i> </a> <a href="images/gallery/9Dog.jpg" data-lightbox="image-1" title="Cutee Cat"  class="btn bg-purple text-white"><i class="fa fa-search"></i> </a>
  </h3>
</div>
</div>
</li>
<li  class="mix dogs " data-name="Naughty">
 <div class="panel panel-cascade panel-gallery ">
  <div class="panel-heading">
   <h2 class="panel-title">Naughty Dog
   </h2>
 </div>
 <div class="panel-body nopadding">
   <img src="images/gallery/10Dog.jpg" alt=""> 
 </div>
 <div class="panel-footer">
   <h3>
    <a href="#" class="btn btn-info text-white"><i class="fa fa-link"></i> </a> <a href="images/gallery/10Dog.jpg" data-lightbox="image-1" title="Cutee Cat"  class="btn bg-purple text-white"><i class="fa fa-search"></i> </a>
  </h3>
</div>
</div>
</li>
<li  class="mix cats " data-name="White">
 <div class="panel panel-cascade panel-gallery ">
  <div class="panel-heading">
   <h2 class="panel-title">White Cat
   </h2>
 </div>
 <div class="panel-body nopadding">
   <img src="images/gallery/9Cat.jpg" alt=""> 
 </div>
 <div class="panel-footer">
   <h3>
    <a href="#" class="btn btn-info text-white"><i class="fa fa-link"></i> </a> <a href="images/gallery/9Cat.jpg" data-lightbox="image-1" title="Cutee Cat"  class="btn bg-purple text-white"><i class="fa fa-search"></i> </a>
  </h3>
</div>
</div>
</li>
<li  class="mix dogs " data-name="Smart">
 <div class="panel panel-cascade panel-gallery ">
  <div class="panel-heading">
   <h2 class="panel-title">Smart Dog
   </h2>
 </div>
 <div class="panel-body nopadding">
   <img src="images/gallery/11Dog.jpg" alt=""> 
 </div>
 <div class="panel-footer">
   <h3>
    <a href="#" class="btn btn-info text-white"><i class="fa fa-link"></i> </a> <a href="images/gallery/11Dog.jpg" data-lightbox="image-1" title="Cutee Cat"  class="btn bg-purple text-white"><i class="fa fa-search"></i> </a>
  </h3>
</div>
</div>
</li>
<!-- sixth row -->
<li  class="mix dogs " data-name="Little">
 <div class="panel panel-cascade panel-gallery ">
  <div class="panel-heading">
   <h2 class="panel-title">Little Dog
   </h2>
 </div>
 <div class="panel-body nopadding">
   <img src="images/gallery/12Dog.jpg" alt=""> 
 </div>
 <div class="panel-footer">
   <h3>
    <a href="#" class="btn btn-info text-white"><i class="fa fa-link"></i> </a> <a href="images/gallery/12Dog.jpg" data-lightbox="image-1" title="Cutee Cat"  class="btn bg-purple text-white"><i class="fa fa-search"></i> </a>
  </h3>
</div>
</div>
</li>
<li  class="mix cats " data-name="Funny">
 <div class="panel panel-cascade panel-gallery ">
  <div class="panel-heading">
   <h2 class="panel-title">Funny Cat
   </h2>
 </div>
 <div class="panel-body nopadding">
   <img src="images/gallery/10Cat.jpg" alt=""> 
 </div>
 <div class="panel-footer">
   <h3>
    <a href="#" class="btn btn-info text-white"><i class="fa fa-link"></i> </a> <a href="images/gallery/10Cat.jpg" data-lightbox="image-1" title="Cutee Cat"  class="btn bg-purple text-white"><i class="fa fa-search"></i> </a>
  </h3>
</div>
</div>
</li>
<li  class="mix dogs " data-name="Healthy">
 <div class="panel panel-cascade panel-gallery ">
  <div class="panel-heading">
   <h2 class="panel-title">Healthy Dog
   </h2>
 </div>
 <div class="panel-body nopadding">
   <img src="images/gallery/13Dog.jpg" alt=""> 
 </div>
 <div class="panel-footer">
   <h3>
    <a href="#" class="btn btn-info text-white"><i class="fa fa-link"></i> </a> <a href="images/gallery/13Dog.jpg" data-lightbox="image-1" title="Cutee Cat"  class="btn bg-purple text-white"><i class="fa fa-search"></i> </a>
  </h3>
</div>
</div>
</li>
<li  class="mix cats " data-name="Dangerous">
 <div class="panel panel-cascade panel-gallery ">
  <div class="panel-heading">
   <h2 class="panel-title">Dangerous Cat
   </h2>
 </div>
 <div class="panel-body nopadding">
   <img src="images/gallery/11Cat.jpg" alt=""> 
 </div>
 <div class="panel-footer">
   <h3>
    <a href="#" class="btn btn-info text-white"><i class="fa fa-link"></i> </a> <a href="images/gallery/11Cat.jpg" data-lightbox="image-1" title="Cutee Cat"  class="btn bg-purple text-white"><i class="fa fa-search"></i> </a>
  </h3>
</div>
</div>
</li>
<!-- seventh row -->
<li  class="mix cats " data-name="Black">
 <div class="panel panel-cascade panel-gallery ">
  <div class="panel-heading">
   <h2 class="panel-title">Black Cat
   </h2>
 </div>
 <div class="panel-body nopadding">
   <img src="images/gallery/12Cat.jpg" alt=""> 
 </div>
 <div class="panel-footer">
   <h3>
    <a href="#" class="btn btn-info text-white"><i class="fa fa-link"></i> </a> <a href="images/gallery/12Cat.jpg" data-lightbox="image-1" title="Cutee Cat"  class="btn bg-purple text-white"><i class="fa fa-search"></i> </a>
  </h3>
</div>
</div>
</li>
<li  class="mix dogs " data-name="Street">
 <div class="panel panel-cascade panel-gallery ">
  <div class="panel-heading">
   <h2 class="panel-title">Street Dog
   </h2>
 </div>
 <div class="panel-body nopadding">
   <img src="images/gallery/14Dog.jpg" alt=""> 
 </div>
 <div class="panel-footer">
   <h3>
    <a href="#" class="btn btn-info text-white"><i class="fa fa-link"></i> </a> <a href="images/gallery/14Dog.jpg" data-lightbox="image-1" title="Cutee Cat"  class="btn bg-purple text-white"><i class="fa fa-search"></i> </a>
  </h3>
</div>
</div>
</li>
<li  class="mix cats " data-name="Dirty">
 <div class="panel panel-cascade panel-gallery ">
  <div class="panel-heading">
   <h2 class="panel-title">Dirty Cat
   </h2>
 </div>
 <div class="panel-body nopadding">
   <img src="images/gallery/13Cat.jpg" alt=""> 
 </div>
 <div class="panel-footer">
   <h3>
    <a href="#" class="btn btn-info text-white"><i class="fa fa-link"></i> </a> <a href="images/gallery/13Cat.jpg" data-lightbox="image-1" title="Cutee Cat"  class="btn bg-purple text-white"><i class="fa fa-search"></i> </a>
  </h3>
</div>
</div>
</li>
<li  class="mix dogs " data-name="Adventurous Dog">
 <div class="panel panel-cascade panel-gallery ">
  <div class="panel-heading">
   <h2 class="panel-title">Adventurous Dog
   </h2>
 </div>
 <div class="panel-body nopadding">
   <img src="images/gallery/15Dog.jpg" alt=""> 
 </div>
 <div class="panel-footer">
   <h3>
    <a href="#" class="btn btn-info text-white"><i class="fa fa-link"></i> </a> <a href="images/gallery/15Dog.jpg" data-lightbox="image-1" title="Cutee Cat"  class="btn bg-purple text-white"><i class="fa fa-search"></i> </a>
  </h3>
</div>
</div>
</li>
</ul>
</div>
</div> <!-- /panel-body -->
</div><!-- Panel -->
</div>
</div>


<div class="footer">
  © 2013 <a href="http://bootstrapguru.com">Bootstrap Guru</a>
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


<!-- Page Script  =============================-->
<script src="js/jquery.mixitup.min.js"></script>
<script src="js/lightbox-2.6.min.js"></script>
<script src="js/gallery-custom.js"></script>

<!-- Core Jquery File  =============================-->
<script src="js/core.js"></script>

</body>
</html>