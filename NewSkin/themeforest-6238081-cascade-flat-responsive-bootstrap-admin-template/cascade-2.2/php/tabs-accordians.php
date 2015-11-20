<?php include('partials/header.php'); ?>  
                <div class="row">
                 <div class="col-mod-12">

                  <ul class="breadcrumb">
                   <li><a href="index.php">Dashboard</a></li>
                   <li><a href="#">UI Elements</a></li>
                   <li class="active">Tabs &amp; Accordians</li>
                 </ul>

                 <div class="form-group hiddn-minibar pull-right">
                  <input type="text" class="form-control form-cascade-control nav-input-search" size="20" placeholder="Search through site" />

                  <span class="input-icon fui-search"></span>
                </div>

                <h3 class="page-header"><i class="fa fa-check"></i> Tabs &amp; Accordians <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>

                <blockquote class="page-information hidden">
                 <p>
                  With <b>Tabs &amp; Accordions</b> you can add quick and dynamic functionality to transition through panes of local content, even via dropdown menus.
                </p>
              </blockquote>

            </div>
          </div>

          <div class="row">
           <div class="col-md-12">
            <div class="panel text-primary panel-cascade">
             <div class="panel-heading">
              <h3 class="panel-title">
               <i class="fa fa-th-list"></i>
               Tabs on Panels
               <ul id="myTab" class="nav nav-tabs pull-right">
                <li class=""><a href="#home" data-toggle="tab">Users</a></li>
                <li><a href="#profile" data-toggle="tab">Projects</a></li>
                <li class="dropdown">
                 <a href="#" id="myTabDrop1" class="dropdown-toggle" data-toggle="dropdown">Comments <b class="caret"></b></a>
                 <ul class="dropdown-menu" role="menu" aria-labelledby="myTabDrop1">
                  <li><a href="#dropdown1" tabindex="-1" data-toggle="tab">Approved</a></li>
                  <li><a href="#dropdown2" tabindex="-1" data-toggle="tab">Pending</a></li>
                </ul>
              </li>
            </ul>
          </h3>
        </div>
        <div class="panel-body nopadding">
          <div id="myTabContent" class="tab-content">
           <div class="tab-pane fade " id="home">
            <ul class="list-inline list-users">
             <li><img src="images/profile50x50.png" class="avatar" alt="" /> <h4>Vijay Kumar</h4> 
              <div class="btn-group user-options">
               <button type="button" class="btn btn-default dropdown-toggle text-success" data-toggle="dropdown">
                Approved <i class="fa fa-chevron-circle-down"></i>
              </button>
              <ul class="dropdown-menu" role="menu">
                <li><a href="#">Action</a></li>
                <li><a href="#">Approve</a></li>
                <li><a href="#">Reject</a></li>
                <li><a href="#">Send Email</a></li>
                <li class="divider"></li>
                <li class="bg-danger"><a href="#"><i class="fa fa-trash-o"></i> Delete</a></li>
              </ul>
            </div>
          </li>
          <li><img src="images/profile50x50.png" class="avatar" alt="" /> <h4>Vijay Kumar</h4> 
            <div class="btn-group user-options">
             <button type="button" class="btn btn-default dropdown-toggle text-warning" data-toggle="dropdown">
              Pending <i class="fa fa-chevron-circle-down"></i>
            </button>
            <ul class="dropdown-menu" role="menu">
              <li><a href="#">Action</a></li>
              <li><a href="#">Approve</a></li>
              <li><a href="#">Reject</a></li>
              <li><a href="#">Send Email</a></li>
              <li class="divider"></li>
              <li class="bg-danger"><a href="#"><i class="fa fa-trash-o"></i> Delete</a></li>
            </ul>
          </div>
        </li>
        <li><img src="images/profile50x50.png" class="avatar" alt="" /> <h4>Vijay Kumar</h4> 
          <div class="btn-group user-options">
           <button type="button" class="btn btn-default dropdown-toggle text-danger" data-toggle="dropdown">
            Deleted <i class="fa fa-chevron-circle-down"></i>
          </button>
          <ul class="dropdown-menu" role="menu">
            <li><a href="#">Action</a></li>
            <li><a href="#">Approve</a></li>
            <li><a href="#">Reject</a></li>
            <li><a href="#">Send Email</a></li>
            <li class="divider"></li>
            <li class="bg-danger"><a href="#"><i class="fa fa-trash-o"></i> Delete</a></li>
          </ul>
        </div>
      </li>
      <li><img src="images/profile50x50.png" class="avatar" alt="" /> <h4>Vijay Kumar</h4> 
        <div class="btn-group user-options">
         <button type="button" class="btn btn-default dropdown-toggle text-warning" data-toggle="dropdown">
          Pending <i class="fa fa-chevron-circle-down"></i>
        </button>
        <ul class="dropdown-menu" role="menu">
          <li><a href="#">Action</a></li>
          <li><a href="#">Approve</a></li>
          <li><a href="#">Reject</a></li>
          <li><a href="#">Send Email</a></li>
          <li class="divider"></li>
          <li class="bg-danger"><a href="#"><i class="fa fa-trash-o"></i> Delete</a></li>
        </ul>
      </div>
    </li>
    <li><img src="images/profile50x50.png" class="avatar" alt="" /> <h4>Vijay Kumar</h4> 
      <div class="btn-group user-options">
       <button type="button" class="btn btn-default dropdown-toggle text-success" data-toggle="dropdown">
        Approved <i class="fa fa-chevron-circle-down"></i>
      </button>
      <ul class="dropdown-menu" role="menu">
        <li><a href="#">Action</a></li>
        <li><a href="#">Approve</a></li>
        <li><a href="#">Reject</a></li>
        <li><a href="#">Send Email</a></li>
        <li class="divider"></li>
        <li class="bg-danger"><a href="#"><i class="fa fa-trash-o"></i> Delete</a></li>
      </ul>
    </div>
  </li>
  <li><img src="images/profile50x50.png" class="avatar" alt="" /> <h4>Vijay Kumar</h4> 
    <div class="btn-group user-options">
     <button type="button" class="btn btn-default dropdown-toggle " data-toggle="dropdown">
      Action <i class="fa fa-chevron-circle-down"></i>
    </button>
    <ul class="dropdown-menu" role="menu">
      <li><a href="#">Action</a></li>
      <li><a href="#">Approve</a></li>
      <li><a href="#">Reject</a></li>
      <li><a href="#">Send Email</a></li>
      <li class="divider"></li>
      <li class="bg-danger"><a href="#"><i class="fa fa-trash-o"></i> Delete</a></li>
    </ul>
  </div>
</li>
<li><img src="images/profile50x50.png" class="avatar" alt="" /> <h4>Vijay Kumar</h4> 
  <div class="btn-group user-options">
   <button type="button" class="btn btn-default dropdown-toggle text-danger" data-toggle="dropdown">
    Rejected <i class="fa fa-chevron-circle-down"></i>
  </button>
  <ul class="dropdown-menu" role="menu">
    <li><a href="#">Action</a></li>
    <li><a href="#">Approve</a></li>
    <li><a href="#">Reject</a></li>
    <li><a href="#">Send Email</a></li>
    <li class="divider"></li>
    <li class="bg-danger"><a href="#"><i class="fa fa-trash-o"></i> Delete</a></li>
  </ul>
</div>
</li>
<li><img src="images/profile50x50.png" class="avatar" alt="" /> <h4>Vijay Kumar</h4> 
  <div class="btn-group user-options">
   <button type="button" class="btn btn-default dropdown-toggle " data-toggle="dropdown">
    Action <i class="fa fa-chevron-circle-down"></i>
  </button>
  <ul class="dropdown-menu" role="menu">
    <li><a href="#">Action</a></li>
    <li><a href="#">Approve</a></li>
    <li><a href="#">Reject</a></li>
    <li><a href="#">Send Email</a></li>
    <li class="divider"></li>
    <li class="bg-danger"><a href="#"><i class="fa fa-trash-o"></i> Delete</a></li>
  </ul>
</div>
</li>
<li><img src="images/profile50x50.png" class="avatar" alt="" /> <h4>Vijay Kumar</h4> 
  <div class="btn-group user-options">
   <button type="button" class="btn btn-default dropdown-toggle text-info" data-toggle="dropdown">
    Email Sent <i class="fa fa-chevron-circle-down"></i>
  </button>
  <ul class="dropdown-menu" role="menu">
    <li><a href="#">Action</a></li>
    <li><a href="#">Approve</a></li>
    <li><a href="#">Reject</a></li>
    <li><a href="#">Send Email</a></li>
    <li class="divider"></li>
    <li class="bg-danger"><a href="#"><i class="fa fa-trash-o"></i> Delete</a></li>
  </ul>
</div>
</li>

</ul>
</div>
<div class="tab-pane fade active in" id="profile">
  <div class="list-group list-projects">
   <a href="#" class="list-group-item">
    <img src="images/profile50x50.png" class="project-img pull-left" alt="" />
    Cras justo odio 
    <span class="badge bg-success">80%</span>
    <div class="progress progress-mini">
     <div class="progress-bar progress-bar-success " role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 80%;">
      <span class="sr-only">80% Complete</span>
    </div>
  </div>
</a>
<a href="#" class="list-group-item">
  <img src="images/profile50x50.png" class="project-img pull-left" alt="" />
  Dapibus ac facilisis in
  <span class="badge bg-primary">60%</span>
  <div class="progress progress-mini">
   <div class="progress-bar progress-bar-primary " role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
    <span class="sr-only">60% Complete</span>
  </div>
</div>

</a>
<a href="#" class="list-group-item">
  <img src="images/profile50x50.png" class="project-img pull-left" alt="" />
  Morbi leo risus
  <span class="badge bg-info">50%</span>
  <div class="progress progress-mini">
   <div class="progress-bar progress-bar-info " role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 50%;">
    <span class="sr-only">50% Complete</span>
  </div>
</div>

</a>
<a href="#" class="list-group-item">
  <img src="images/profile50x50.png" class="project-img pull-left" alt="" />
  Porta ac consectetur ac
  <span class="badge bg-warning">30%</span>
  <div class="progress progress-mini">
   <div class="progress-bar progress-bar-warning " role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 30%;">
    <span class="sr-only">30% Complete</span>
  </div>
</div>

</a>
<a href="#" class="list-group-item">
  <img src="images/profile50x50.png" class="project-img pull-left" alt="" />
  Vestibulum at eros
  <span class="badge bg-danger">20%</span>
  <div class="progress progress-mini">
   <div class="progress-bar progress-bar-danger " role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 20%;">
    <span class="sr-only">20% Complete</span>
  </div>
</div>

</a>
</div>
</div>
<div class="tab-pane fade " id="dropdown1">
  <div class="list-group list-comments">
   <a href="#" class="list-group-item">
    <img src="images/profile50x50.png" class="comment-img pull-left" alt="" />
    <h4 class="list-group-item-heading">John Deo <span class="label label-success pull-right">Approved</span></h4>
    <p class="list-group-item-text"> E topic chala bagundhi, nenu andharini chudamani chepthunnanu</p>
  </a>
  <a href="#" class="list-group-item">
    <img src="images/profile50x50.png" class="comment-img pull-left" alt="" />
    <h4 class="list-group-item-heading">John Deo <span class="label label-success pull-right">Approved</span></h4>
    <p class="list-group-item-text"> E topic chala bagundhi, nenu andharini chudamani chepthunnanu</p>
  </a>
  <a href="#" class="list-group-item">
    <img src="images/profile50x50.png" class="comment-img pull-left" alt="" />
    <h4 class="list-group-item-heading">John Deo <span class="label label-success pull-right">Approved</span></h4>
    <p class="list-group-item-text"> E topic chala bagundhi, nenu andharini chudamani chepthunnanu</p>
  </a>
  <a href="#" class="list-group-item">
    <img src="images/profile50x50.png" class="comment-img pull-left" alt="" />
    <h4 class="list-group-item-heading">John Deo <span class="label label-success pull-right">Approved</span></h4>
    <p class="list-group-item-text"> E topic chala bagundhi, nenu andharini chudamani chepthunnanu</p>
  </a>
  <a href="#" class="list-group-item">
    <img src="images/profile50x50.png" class="comment-img pull-left" alt="" />
    <h4 class="list-group-item-heading">John Deo <span class="label label-success pull-right">Approved</span></h4>
    <p class="list-group-item-text"> E topic chala bagundhi, nenu andharini chudamani chepthunnanu</p>
  </a>
</div>
</div>
<div class="tab-pane fade" id="dropdown2">
  <div class="list-group list-comments">
   <a href="#" class="list-group-item">
    <img src="images/profile50x50.png" class="comment-img pull-left" alt="" />
    <h4 class="list-group-item-heading">John Deo <span class="label label-warning pull-right">Pending</span></h4>
    <p class="list-group-item-text"> E topic chala bagundhi, nenu andharini chudamani chepthunnanu</p>
  </a>
  <a href="#" class="list-group-item">
    <img src="images/profile50x50.png" class="comment-img pull-left" alt="" />
    <h4 class="list-group-item-heading">John Deo <span class="label label-warning pull-right">Pending</span></h4>
    <p class="list-group-item-text"> E topic chala bagundhi, nenu andharini chudamani chepthunnanu</p>
  </a>
  <a href="#" class="list-group-item">
    <img src="images/profile50x50.png" class="comment-img pull-left" alt="" />
    <h4 class="list-group-item-heading">John Deo <span class="label label-warning pull-right">Pending</span></h4>
    <p class="list-group-item-text"> E topic chala bagundhi, nenu andharini chudamani chepthunnanu</p>
  </a>
  <a href="#" class="list-group-item">
    <img src="images/profile50x50.png" class="comment-img pull-left" alt="" />
    <h4 class="list-group-item-heading">John Deo <span class="label label-warning pull-right">Pending</span></h4>
    <p class="list-group-item-text"> E topic chala bagundhi, nenu andharini chudamani chepthunnanu</p>
  </a>
  <a href="#" class="list-group-item">
    <img src="images/profile50x50.png" class="comment-img pull-left" alt="" />
    <h4 class="list-group-item-heading">John Deo <span class="label label-warning pull-right">Pending</span></h4>
    <p class="list-group-item-text"> E topic chala bagundhi, nenu andharini chudamani chepthunnanu</p>
  </a>
</div>
</div>
</div>
</div>
</div>
</div>
</div>

<div class="row">
	<div class="col-md-6">
		<div class="panel panel-cascade">
			<div class="panel-heading">
				<h3 class="panel-title text-primary">
					Basic Tab
					<span class="pull-right">
						<div class="btn-group code">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Classes used"><i class="fa fa-code"></i></a>
							<ul class="dropdown-menu  pull-right list-group" role="menu">
								<li class="list-group-item"><code>.nav-tabs</code></li>
								<li class="list-group-item"><code>.tab-content</code></li>
								<li class="list-group-item"><code>.tab-pane</code></li>
							</ul>
						</div>
						<a href="#" class="panel-minimize"><i class="fa fa-chevron-up"></i></a>
						<a href="#" class="panel-close"><i class="fa fa-times"></i></a>
					</span>
				</h3>
			</div>
			<div class="panel-body ">
				<ul id="myTab" class="nav nav-tabs">
					<li><a href="#home2" data-toggle="tab">Home</a></li>
					<li><a href="#profile2" data-toggle="tab">Profile</a></li>
					<li class="dropdown">
						<a href="#" id="myTabDrop1" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
						<ul class="dropdown-menu" role="menu" aria-labelledby="myTabDrop1">
							<li><a href="#dropdown3" tabindex="-1" data-toggle="tab">@fat</a></li>
							<li><a href="#dropdown4" tabindex="-1" data-toggle="tab">@mdo</a></li>
						</ul>
					</li>
				</ul>
				<div id="myTabContent" class="tab-content">
					<div class="tab-pane fade in active" id="home2">
						<p>Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terry richardson ex squid. Aliquip placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi qui.</p>
					</div>
					<div class="tab-pane fade" id="profile2">
						<p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui photo booth letterpress, commodo enim craft beer mlkshk aliquip jean shorts ullamco ad vinyl cillum PBR. Homo nostrud organic, assumenda labore aesthetic magna delectus mollit. Keytar helvetica VHS salvia yr, vero magna velit sapiente labore stumptown. Vegan fanny pack odio cillum wes anderson 8-bit, sustainable jean shorts beard ut DIY ethical culpa terry richardson biodiesel. Art party scenester stumptown, tumblr butcher vero sint qui sapiente accusamus tattooed echo park.</p>
					</div>
					<div class="tab-pane fade" id="dropdown3">
						<p>Etsy mixtape wayfarers, ethical wes anderson tofu before they sold out mcsweeney's organic lomo retro fanny pack lo-fi farm-to-table readymade. Messenger bag gentrify pitchfork tattooed craft beer, iphone skateboard locavore carles etsy salvia banksy hoodie helvetica. DIY synth PBR banksy irony. Leggings gentrify squid 8-bit cred pitchfork. Williamsburg banh mi whatever gluten-free, carles pitchfork biodiesel fixie etsy retro mlkshk vice blog. Scenester cred you probably haven't heard of them, vinyl craft beer blog stumptown. Pitchfork sustainable tofu synth chambray yr.</p>
					</div>
					<div class="tab-pane fade" id="dropdown4">
						<p>Trust fund seitan letterpress, keytar raw denim keffiyeh etsy art party before they sold out master cleanse gluten-free squid scenester freegan cosby sweater. Fanny pack portland seitan DIY, art party locavore wolf cliche high life echo park Austin. Cred vinyl keffiyeh DIY salvia PBR, banh mi before they sold out farm-to-table VHS viral locavore cosby sweater. Lomo wolf viral, mustache readymade thundercats keffiyeh craft beer marfa ethical. Wolf salvia freegan, sartorial keffiyeh echo park vegan.</p>
					</div>
				</div>
			</div> <!-- /panel body -->	
		</div>	
	</div>
	<div class="col-md-6">
		<div class="panel panel-cascade">
			<div class="panel-heading">
				<h3 class="panel-title text-primary">
					Right Tab
					<span class="pull-right">
						<div class="btn-group code">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Classes used"><i class="fa fa-code"></i></a>
							<ul class="dropdown-menu  pull-right list-group" role="menu">
								<li class="list-group-item"><code>.nav-tabs</code></li>
								<li class="list-group-item"><code>.tab-content</code></li>
								<li class="list-group-item"><code>.tab-pane</code></li>
								<li class="list-group-item"><code>.nav-tab-right</code></li>
							</ul>
						</div>
						<a href="#" class="panel-minimize"><i class="fa fa-chevron-up"></i></a>
						<a href="#" class="panel-close"><i class="fa fa-times"></i></a>
					</span>
				</h3>
			</div>
			<div class="panel-body ">
				<ul id="myTab" class="nav nav-tabs nav-tab-right">
					<li><a href="#home3" data-toggle="tab">Home</a></li>
					<li><a href="#profile3" data-toggle="tab">Profile</a></li>
					<li class="dropdown">
						<a href="#" id="myTabDrop1" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
						<ul class="dropdown-menu" role="menu" aria-labelledby="myTabDrop1">
							<li><a href="#dropdown5" tabindex="-1" data-toggle="tab">@fat</a></li>
							<li><a href="#dropdown6" tabindex="-1" data-toggle="tab">@mdo</a></li>
						</ul>
					</li>
				</ul>
				<div id="myTabContent" class="tab-content">
					<div class="tab-pane fade in active" id="home3">
						<p>Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terry richardson ex squid. Aliquip placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi qui.</p>
					</div>
					<div class="tab-pane fade" id="profile3">
						<p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui photo booth letterpress, commodo enim craft beer mlkshk aliquip jean shorts ullamco ad vinyl cillum PBR. Homo nostrud organic, assumenda labore aesthetic magna delectus mollit. Keytar helvetica VHS salvia yr, vero magna velit sapiente labore stumptown. Vegan fanny pack odio cillum wes anderson 8-bit, sustainable jean shorts beard ut DIY ethical culpa terry richardson biodiesel. Art party scenester stumptown, tumblr butcher vero sint qui sapiente accusamus tattooed echo park.</p>
					</div>
					<div class="tab-pane fade" id="dropdown5">
						<p>Etsy mixtape wayfarers, ethical wes anderson tofu before they sold out mcsweeney's organic lomo retro fanny pack lo-fi farm-to-table readymade. Messenger bag gentrify pitchfork tattooed craft beer, iphone skateboard locavore carles etsy salvia banksy hoodie helvetica. DIY synth PBR banksy irony. Leggings gentrify squid 8-bit cred pitchfork. Williamsburg banh mi whatever gluten-free, carles pitchfork biodiesel fixie etsy retro mlkshk vice blog. Scenester cred you probably haven't heard of them, vinyl craft beer blog stumptown. Pitchfork sustainable tofu synth chambray yr.</p>
					</div>
					<div class="tab-pane fade" id="dropdown6">
						<p>Trust fund seitan letterpress, keytar raw denim keffiyeh etsy art party before they sold out master cleanse gluten-free squid scenester freegan cosby sweater. Fanny pack portland seitan DIY, art party locavore wolf cliche high life echo park Austin. Cred vinyl keffiyeh DIY salvia PBR, banh mi before they sold out farm-to-table VHS viral locavore cosby sweater. Lomo wolf viral, mustache readymade thundercats keffiyeh craft beer marfa ethical. Wolf salvia freegan, sartorial keffiyeh echo park vegan.</p>
					</div>
				</div>
			</div> <!-- /panel body -->	
		</div>	
	</div>
</div>


<!-- Left -->
<div class="row">
	<div class="col-md-6">
		<div class="panel panel-cascade">
			<div class="panel-heading">
				<h3 class="panel-title text-primary">
					Left Tab Navigation
					<span class="pull-right">
						<div class="btn-group code">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Classes used"><i class="fa fa-code"></i></a>
							<ul class="dropdown-menu  pull-right list-group" role="menu">
								<li class="list-group-item"><code>.tab-content</code></li>
								<li class="list-group-item"><code>.tab-pane</code></li>
								<li class="list-group-item"><code>.nav-stacked-left</code></li>
							</ul>
						</div>
						<a href="#" class="panel-minimize"><i class="fa fa-chevron-up"></i></a>
						<a href="#" class="panel-close"><i class="fa fa-times"></i></a>
					</span>
				</h3>
			</div>
			<div class="panel-body ">
				
				<div class="row">
					<div class="col-md-3">
						<ul id="myTab" class="nav nav-stacked-left">
							<li><a href="#home4" data-toggle="tab">Home</a></li>
							<li><a href="#profile4" data-toggle="tab">Profile</a></li>
							<li class="dropdown">
								<a href="#" id="myTabDrop1" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
								<ul class="dropdown-menu" role="menu" aria-labelledby="myTabDrop1">
									<li><a href="#dropdown7" tabindex="-1" data-toggle="tab">@fat</a></li>
									<li><a href="#dropdown8" tabindex="-1" data-toggle="tab">@mdo</a></li>
								</ul>
							</li>
						</ul>
						
					</div>
					<div class="col-md-9">
						<div id="myTabContent" class="tab-content">
							<div class="tab-pane fade in active" id="home4">
								<p>Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terry richardson ex squid. Aliquip placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi qui.</p>
							</div>
							<div class="tab-pane fade" id="profile4">
								<p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui photo booth letterpress, commodo enim craft beer mlkshk aliquip jean shorts ullamco ad vinyl cillum PBR. Homo nostrud organic, assumenda labore aesthetic magna delectus mollit. Keytar helvetica VHS salvia yr, vero magna velit sapiente labore stumptown. Vegan fanny pack odio cillum wes anderson 8-bit, sustainable jean shorts beard ut DIY ethical culpa terry richardson biodiesel. Art party scenester stumptown, tumblr butcher vero sint qui sapiente accusamus tattooed echo park.</p>
							</div>
							<div class="tab-pane fade" id="dropdown7">
								<p>Etsy mixtape wayfarers, ethical wes anderson tofu before they sold out mcsweeney's organic lomo retro fanny pack lo-fi farm-to-table readymade. Messenger bag gentrify pitchfork tattooed craft beer, iphone skateboard locavore carles etsy salvia banksy hoodie helvetica. DIY synth PBR banksy irony. Leggings gentrify squid 8-bit cred pitchfork. Williamsburg banh mi whatever gluten-free, carles pitchfork biodiesel fixie etsy retro mlkshk vice blog. Scenester cred you probably haven't heard of them, vinyl craft beer blog stumptown. Pitchfork sustainable tofu synth chambray yr.</p>
							</div>
							<div class="tab-pane fade" id="dropdown8">
								<p>Trust fund seitan letterpress, keytar raw denim keffiyeh etsy art party before they sold out master cleanse gluten-free squid scenester freegan cosby sweater. Fanny pack portland seitan DIY, art party locavore wolf cliche high life echo park Austin. Cred vinyl keffiyeh DIY salvia PBR, banh mi before they sold out farm-to-table VHS viral locavore cosby sweater. Lomo wolf viral, mustache readymade thundercats keffiyeh craft beer marfa ethical. Wolf salvia freegan, sartorial keffiyeh echo park vegan.</p>
							</div>
						</div>
						
					</div>
				</div>
			</div> <!-- /panel body -->	
		</div>	
	</div>
	<div class="col-md-6">
		<div class="panel panel-cascade">
			<div class="panel-heading">
				<h3 class="panel-title text-primary">
					Right Tab Navigation
					<span class="pull-right">
						<div class="btn-group code">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Classes used"><i class="fa fa-code"></i></a>
							<ul class="dropdown-menu  pull-right list-group" role="menu">
								<li class="list-group-item"><code>.tab-content</code></li>
								<li class="list-group-item"><code>.tab-pane</code></li>
								<li class="list-group-item"><code>.nav-stacked-right</code></li>
							</ul>
						</div>
						<a href="#" class="panel-minimize"><i class="fa fa-chevron-up"></i></a>
						<a href="#" class="panel-close"><i class="fa fa-times"></i></a>
					</span>
				</h3>
			</div>
			<div class="panel-body ">
				
				<div class="row">
					<div class="col-md-9">
						<div id="myTabContent" class="tab-content">
							<div class="tab-pane fade in active" id="home5">
								<p>Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terry richardson ex squid. Aliquip placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi qui.</p>
							</div>
							<div class="tab-pane fade" id="profile8">
								<p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui photo booth letterpress, commodo enim craft beer mlkshk aliquip jean shorts ullamco ad vinyl cillum PBR. Homo nostrud organic, assumenda labore aesthetic magna delectus mollit. Keytar helvetica VHS salvia yr, vero magna velit sapiente labore stumptown. Vegan fanny pack odio cillum wes anderson 8-bit, sustainable jean shorts beard ut DIY ethical culpa terry richardson biodiesel. Art party scenester stumptown, tumblr butcher vero sint qui sapiente accusamus tattooed echo park.</p>
							</div>
							<div class="tab-pane fade" id="dropdown9">
								<p>Etsy mixtape wayfarers, ethical wes anderson tofu before they sold out mcsweeney's organic lomo retro fanny pack lo-fi farm-to-table readymade. Messenger bag gentrify pitchfork tattooed craft beer, iphone skateboard locavore carles etsy salvia banksy hoodie helvetica. DIY synth PBR banksy irony. Leggings gentrify squid 8-bit cred pitchfork. Williamsburg banh mi whatever gluten-free, carles pitchfork biodiesel fixie etsy retro mlkshk vice blog. Scenester cred you probably haven't heard of them, vinyl craft beer blog stumptown. Pitchfork sustainable tofu synth chambray yr.</p>
							</div>
							<div class="tab-pane fade" id="dropdown10">
								<p>Trust fund seitan letterpress, keytar raw denim keffiyeh etsy art party before they sold out master cleanse gluten-free squid scenester freegan cosby sweater. Fanny pack portland seitan DIY, art party locavore wolf cliche high life echo park Austin. Cred vinyl keffiyeh DIY salvia PBR, banh mi before they sold out farm-to-table VHS viral locavore cosby sweater. Lomo wolf viral, mustache readymade thundercats keffiyeh craft beer marfa ethical. Wolf salvia freegan, sartorial keffiyeh echo park vegan.</p>
							</div>
						</div>
						
					</div>
					<div class="col-md-3">
						<ul id="myTab" class="nav nav-stacked-right">
							<li><a href="#home5" data-toggle="tab">Home</a></li>
							<li><a href="#profile8" data-toggle="tab">Profile</a></li>
							<li class="dropdown">
								<a href="#" id="myTabDrop1" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
								<ul class="dropdown-menu" role="menu" aria-labelledby="myTabDrop1">
									<li><a href="#dropdown9" tabindex="-1" data-toggle="tab">@fat</a></li>
									<li><a href="#dropdown10" tabindex="-1" data-toggle="tab">@mdo</a></li>
								</ul>
							</li>
						</ul>
						
					</div>
				</div>
			</div> <!-- /panel body -->	
		</div>	
	</div>
</div>


<h2 class="page-header text-success"><i class="fa fa-stack-exchange"></i> Accordians </h2>

<!-- Accordians -->
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-cascade">
			<div class="panel-heading">
				<h3 class="panel-title text-primary">
					Default Accordians
					<span class="pull-right">
						<div class="btn-group code">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Classes used"><i class="fa fa-code"></i></a>
							<ul class="dropdown-menu  pull-right list-group" role="menu">
								<li class="list-group-item"><code>#accordion</code></li>
								<li class="list-group-item"><code>.panel-collapse</code></li>
							</ul>
						</div>
						<a href="#" class="panel-minimize"><i class="fa fa-chevron-up"></i></a>
						<a href="#" class="panel-close"><i class="fa fa-times"></i></a>
					</span>
				</h3>
			</div>
			<div class="panel-body ">
				<div class="panel-group" id="accordion">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
									Collapsible Group Item #1
								</a>
							</h4>
						</div>
						<div id="collapseOne" class="panel-collapse collapse in">
							<div class="panel-body">
								Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
							</div>
						</div>
					</div>
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
									Collapsible Group Item #2
								</a>
							</h4>
						</div>
						<div id="collapseTwo" class="panel-collapse collapse">
							<div class="panel-body">
								Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
							</div>
						</div>
					</div>
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
									Collapsible Group Item #3
								</a>
							</h4>
						</div>
						<div id="collapseThree" class="panel-collapse collapse">
							<div class="panel-body">
								Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
							</div>
						</div>
					</div>
				</div>
			</div> <!-- /panel body -->	
		</div>	
	</div>
</div>

<!-- Accordians -->
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-cascade">
			<div class="panel-heading">
				<h3 class="panel-title text-primary">
					Accordians with Cascade Panels
					<span class="pull-right">
						<div class="btn-group code">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Classes used"><i class="fa fa-code"></i></a>
							<ul class="dropdown-menu  pull-right list-group" role="menu">
								<li class="list-group-item"><code>#accordion</code></li>
								<li class="list-group-item"><code>.panel-collapse</code></li>
							</ul>
						</div>
						<a href="#" class="panel-minimize"><i class="fa fa-chevron-up"></i></a>
						<a href="#" class="panel-close"><i class="fa fa-times"></i></a>
					</span>
				</h3>
			</div>
			<div class="panel-body ">
				<div class="panel-group" id="accordion">
					<div class="panel panel-cascade">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion" href="#collapse1">
									Collapsible Group Item #1
								</a>
							</h4>
						</div>
						<div id="collapse1" class="panel-collapse collapse in">
							<div class="panel-body">
								Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
							</div>
						</div>
					</div>
					<div class="panel panel-cascade">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion" href="#collapse2">
									Collapsible Group Item #2
								</a>
							</h4>
						</div>
						<div id="collapse2" class="panel-collapse collapse">
							<div class="panel-body">
								Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
							</div>
						</div>
					</div>
					<div class="panel panel-cascade">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion" href="#collapse3">
									Collapsible Group Item #3
								</a>
							</h4>
						</div>
						<div id="collapse3" class="panel-collapse collapse">
							<div class="panel-body">
								Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
							</div>
						</div>
					</div>
				</div>
			</div> <!-- /panel body -->	
		</div>	
	</div>
</div>

<!-- Accordians with custom colors -->
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-cascade">
			<div class="panel-heading">
				<h3 class="panel-title text-primary">
					Accordians with Custom Colors
					<span class="pull-right">
						<div class="btn-group code">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Classes used"><i class="fa fa-code"></i></a>
							<ul class="dropdown-menu  pull-right list-group" role="menu">
								<li class="list-group-item"><code>#accordion</code></li>
								<li class="list-group-item"><code>.panel-collapse</code></li>
								<li class="list-group-item"><code>.panel-info</code></li>
								<li class="list-group-item"><code>.panel-success</code></li>
								<li class="list-group-item"><code>.panel-danger</code></li>
							</ul>
						</div>
						<a href="#" class="panel-minimize"><i class="fa fa-chevron-up"></i></a>
						<a href="#" class="panel-close"><i class="fa fa-times"></i></a>
					</span>
				</h3>
			</div>
			<div class="panel-body ">
				<div class="panel-group" id="accordion">
					<div class="panel panel-success">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion" href="#collapse4">
									Collapsible Group Item #1
								</a>
							</h4>
						</div>
						<div id="collapse4" class="panel-collapse collapse in">
							<div class="panel-body">
								Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
							</div>
						</div>
					</div>
					<div class="panel panel-info">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion" href="#collapse5">
									Collapsible Group Item #2
								</a>
							</h4>
						</div>
						<div id="collapse5" class="panel-collapse collapse">
							<div class="panel-body">
								Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
							</div>
						</div>
					</div>
					<div class="panel panel-danger">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion" href="#collapse6">
									Collapsible Group Item #3
								</a>
							</h4>
						</div>
						<div id="collapse6" class="panel-collapse collapse">
							<div class="panel-body">
								Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
							</div>
						</div>
					</div>
				</div>
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