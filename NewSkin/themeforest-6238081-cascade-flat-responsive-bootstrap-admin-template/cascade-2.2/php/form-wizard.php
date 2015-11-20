<?php include('partials/header.php'); ?>
                <div class="row">
                 <div class="col-mod-12">

                  <ul class="breadcrumb">
                   <li><a href="index.php">Dashboard</a></li>
                   <li><a href="#">Forms</a></li>
                   <li class="active">Form Wizard</li>
                 </ul>

                 <div class="form-group hiddn-minibar pull-right">
                  <input type="text" class="form-control form-cascade-control nav-input-search" size="20" placeholder="Search through site" />

                  <span class="input-icon fui-search"></span>
                </div>

                <h3 class="page-header"><i class="fa fa-indent"></i> Form Wizard <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>

                <blockquote class="page-information hidden">
                 <p>
                  The form wizard can help you create new web forms in a matter of minutes. In a few simple steps you can create a completely working form to collect information from your visitors.
                </p>
              </blockquote>

            </div>
          </div>

          <!-- Basic Wizard -->
          <div class="row">
           <div class="col-md-12">
            <div class="panel panel-dat">
             <div class="panel-heading">
              <h3 class="panel-title text-primary">
               Basic  Wizard
               <span class="pull-right">
                <a href="#" class="panel-minimize"><i class="fa fa-chevron-up"></i></a>
                <a href="#" class="panel-close"><i class="fa fa-times"></i></a>
              </span>
            </h3>
          </div>
          <div class="panel-body nopadding">


            <!-- Smart Wizard -->
            <div id="wizard" class="swMain">
             <ul>
              <li><a href="#step-1">
                <label class="stepNumber">1</label>
                <span class="stepDesc">
                 Introduction
               </span>
             </a></li>
             <li><a href="#step-2">
              <label class="stepNumber">2</label>
              <span class="stepDesc">
               Registration
             </span>
           </a></li>
           <li><a href="#step-3">
            <label class="stepNumber">3</label>
            <span class="stepDesc">
             Terms
           </span>                   
         </a></li>
         <li><a href="#step-4">
          <label class="stepNumber">4</label>
          <span class="stepDesc">
           Finish
         </span>                   
       </a></li>
     </ul>
     <div id="step-1">	
      <h2 class="StepTitle">Introduction</h2>

      <div class="panel">
        <div class="panel-heading">
          <h3 class="panel-title"> Cascade Template </h3>
        </div>
        <div class="panel-body">
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, 
            sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, 
            quis nostrud <span class="label bg-purple">exercitation</span> ullamco laboris nisi ut aliquip ex ea commodo consequat. 
            Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. 
            Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
            Lorem ipsum dolor sit amet, consectetur adipisicing elit, 
            sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, 
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. 
            Duis aute irure dolor in reprehenderit in <span class="label bg-pink">voluptate</span> velit esse cillum dolore eu fugiat nulla pariatur. 
            Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
          </p>
          <ul type="disk">
            <li>List 1</li>
            <li>List 2</li>
            <li>List 3</li>
            <li>List 4</li>
            <li>List 5</li>
          </ul>
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, 
            sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, 
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. 
            Duis aute irure dolor in reprehenderit in <span class="label bg-success">voluptate</span> velit esse cillum dolore eu fugiat nulla pariatur. 
            Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
            Lorem ipsum dolor sit amet, consectetur adipisicing elit, 
            sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, 
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. 
            Duis aute irure dolor in <span class="label bg-warning">reprehenderit</span> in voluptate velit esse cillum dolore eu fugiat nulla pariatur. 
            Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
          </p>
          <p><span class="label bg-seagreen">Lorem ipsum</span> dolor sit amet, consectetur adipisicing elit, 
            sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, 
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. 
            Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. 
            Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
            <span class="label bg-info">Lorem ipsum</span> dolor sit amet, consectetur adipisicing elit, 
            sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, 
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. 
            Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. 
            Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
          </p>
        </div>
      </div>          			
    </div>
    <div id="step-2">
      <h2 class="StepTitle">Enter Details</h2>	
      <form class="form-horizontal cacade-forms" method="post" action="#" name="signup_form" id="signup_form" novalidate="novalidate">
        <div class="form-group">
          <label class="col-lg-2 col-md-3 control-label">Email</label>
          <div class="col-lg-10 col-md-9">
            <input type="text" class="form-control form-cascade-control input-small" name="email" id="email">
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-2 col-md-3 control-label">Username</label>
          <div class="col-lg-10 col-md-9">
            <input type="text" class="form-control form-cascade-control input-small" name="username" id="username">
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-2 col-md-3 control-label">Password</label>
          <div class="col-lg-10 col-md-9">
            <input type="text" class="form-control form-cascade-control input-small" name="password" id="password">
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-2 col-md-3 control-label">Confirm Password</label>
          <div class="col-lg-10 col-md-9">
            <input type="text" class="form-control form-cascade-control input-small" name="confirmPassword" id="confirmPassword">
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-2 col-md-3 control-label">Site URL</label>
          <div class="col-lg-10 col-md-9">
            <input type="text" class="form-control form-cascade-control input-small" name="url" id="url">
          </div>
        </div>

      </form>       
    </div>                      
    <div id="step-3">
      <h2 class="StepTitle">Terms</h2>	
      <div class="panel">
        <div class="panel-heading">
          <h3 class="panel-title"> Make a Note </h3>
        </div>
        <div class="panel-body">
          <ul type="disk">
            <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
            <li>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</li>
            <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text </li>
            <li>Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it</li>
            <li>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. </li>
            <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
            <li>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</li>
            <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.Suspendisse ultricies orci nec mi pretium lobortis.Suspendisse ultricies orci nec mi pretium lobortis.</li>
            <li>Duis iaculis massa aliquam eros mollis iaculis.</li>
            <li>Suspendisse consectetur erat eget urna adipiscing dignissim.In ut ante fringilla, dignissim metus nec, luctus justo.</li>
            <li>Quisque vitae lectus elementum, molestie ligula non, scelerisque libero.</li>
            <li>Sed ac eros tempor, scelerisque turpis vitae, ullamcorper tellus.Sed sodales ipsum molestie ultrices sagittis.</li>
            <li>Sed sodales ipsum molestie ultrices sagittis.</li>
            <li>Nulla bibendum dui eu risus bibendum consectetur.</li>
            <li>In ut ante fringilla, dignissim metus nec, luctus justo.Ut id nisl suscipit, congue leo vel, rhoncus eros.</li>
            <li>Quisque in purus a arcu tincidunt consequat.</li>
            <li>Suspendisse ultricies orci nec mi pretium lobortis.Duis laoreet lorem vel faucibus pulvinar.Duis laoreet lorem vel faucibus pulvinar.</li>
            <li>Nunc vitae leo sit amet sem egestas accumsan.</li>
            <li>In sed sapien faucibus, venenatis magna sed, posuere sem.</li>
            <li>Mauris id ipsum sed lorem tincidunt venenatis.Ut id nisl suscipit, congue leo vel, rhoncus eros.</li>
            <li>Aenean dapibus elit vel orci auctor, ut euismod sem laoreet.In ut ante fringilla, dignissim metus nec, luctus justo.</li>
            <li>Nunc sagittis diam quis massa tempor congue.</li>
            <li>Ut id nisl suscipit, congue leo vel, rhoncus eros.Duis laoreet lorem vel faucibus pulvinar.</li>
            <li>Morbi a purus consequat, aliquam odio non, pellentesque ante.In ut ante fringilla, dignissim metus nec, luctus justo.</li>
            <li>Duis laoreet lorem vel faucibus pulvinar.</li>
            <li>In quis odio vel enim sodales suscipit id at leo</li>
          </ul>
        </div>
      </div>                      
    </div>

    <div id="step-4">
      <h2 class="StepTitle">Finish</h2>
      <div class="panel">	
        <div class="panel-heading">
          <h3 class="panel-title"> Thanks for your Registration! </h3>
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, 
            sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, 
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. 
            Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. 
            Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
            Lorem ipsum dolor sit amet, consectetur adipisicing elit, 
            sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, 
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. 
            Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. 
            Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
          </p>
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, 
            sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, 
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. 
            Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. 
            Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
            Lorem ipsum dolor sit amet, consectetur adipisicing elit, 
            sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, 
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. 
            Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. 
            Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
          </p>
        </div>
      </div>           			
    </div>
  </div>
  <!-- End SmartWizard Content -->  		



</div> <!-- /panel body -->	
</div>	
</div>


<!-- Vertical Wizard -->
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-dat">
			<div class="panel-heading">
				<h3 class="panel-title text-primary">
					Vertical  Wizard
					<span class="pull-right">
						<a href="#" class="panel-minimize"><i class="fa fa-chevron-up"></i></a>
						<a href="#" class="panel-close"><i class="fa fa-times"></i></a>
					</span>
				</h3>
			</div>
			<div class="panel-body nopadding">
				
        <div id="wizardVertical" class="swMain vertical">
         <ul>
          <li><a href="#step-1">
            <label class="stepNumber">1</label>
            <span class="stepDesc">
             Introduction
           </span>
         </a></li>
         <li><a href="#step-2">
          <label class="stepNumber">2</label>
          <span class="stepDesc">
           Details
         </span>
       </a></li>
       <li><a href="#step-3">
        <label class="stepNumber">3</label>
        <span class="stepDesc">
          Terms
        </span>                   
      </a></li>
      <li><a href="#step-4">
        <label class="stepNumber">4</label>
        <span class="stepDesc">
          Finish
        </span>                   
      </a></li>
    </ul>
    <div id="step-1">	
      <h2 class="StepTitle">Introduction</h2>

      <div class="panel">
        <div class="panel-heading">
          <h3 class="panel-title"> Cascade Template </h3>
        </div>
        <div class="panel-body">
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, 
            sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, 
            quis nostrud <span class="label bg-purple">exercitation</span> ullamco laboris nisi ut aliquip ex ea commodo consequat. 
            Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. 
            Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
            Lorem ipsum dolor sit amet, consectetur adipisicing elit, 
            sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, 
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. 
            Duis aute irure dolor in reprehenderit in <span class="label bg-pink">voluptate</span> velit esse cillum dolore eu fugiat nulla pariatur. 
            Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
          </p>
          <ul type="disk">
            <li>List 1</li>
            <li>List 2</li>
            <li>List 3</li>
            <li>List 4</li>
            <li>List 5</li>
          </ul>
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, 
            sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, 
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. 
            Duis aute irure dolor in reprehenderit in <span class="label bg-success">voluptate</span> velit esse cillum dolore eu fugiat nulla pariatur. 
            Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
            Lorem ipsum dolor sit amet, consectetur adipisicing elit, 
            sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, 
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. 
            Duis aute irure dolor in <span class="label bg-warning">reprehenderit</span> in voluptate velit esse cillum dolore eu fugiat nulla pariatur. 
            Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
          </p>
          <p><span class="label bg-seagreen">Lorem ipsum</span> dolor sit amet, consectetur adipisicing elit, 
            sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, 
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. 
            Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. 
            Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
            <span class="label bg-info">Lorem ipsum</span> dolor sit amet, consectetur adipisicing elit, 
            sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, 
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. 
            Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. 
            Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
          </p>
        </div>
      </div>         			
    </div>
    <div id="step-2">
      <h2 class="StepTitle">Enter Details</h2>    
      <form class="form-horizontal cacade-forms" method="post" action="#" name="signup_form" id="signup_form" novalidate="novalidate">
        <div class="form-group">
          <label class="col-lg-2 col-md-3 control-label">Email</label>
          <div class="col-lg-10 col-md-9">
            <input type="text" class="form-control form-cascade-control input-small" name="email" id="email">
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-2 col-md-3 control-label">Username</label>
          <div class="col-lg-10 col-md-9">
            <input type="text" class="form-control form-cascade-control input-small" name="username" id="username">
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-2 col-md-3 control-label">Password</label>
          <div class="col-lg-10 col-md-9">
            <input type="text" class="form-control form-cascade-control input-small" name="password" id="password">
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-2 col-md-3 control-label">Confirm Password</label>
          <div class="col-lg-10 col-md-9">
            <input type="text" class="form-control form-cascade-control input-small" name="confirmPassword" id="confirmPassword">
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-2 col-md-3 control-label">Site URL</label>
          <div class="col-lg-10 col-md-9">
            <input type="text" class="form-control form-cascade-control input-small" name="url" id="url">
          </div>
        </div>

      </form>  
    </div>                      
    <div id="step-3">
      <div class="panel">
        <div class="panel-heading">
          <h3 class="panel-title"> Make a Note </h3>
        </div>
        <div class="panel-body">
          <ul type="disk">
            <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
            <li>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</li>
            <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text </li>
            <li>Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it</li>
            <li>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. </li>
            <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
            <li>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</li>
            <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.Suspendisse ultricies orci nec mi pretium lobortis.Suspendisse ultricies orci nec mi pretium lobortis.</li>
            <li>Duis iaculis massa aliquam eros mollis iaculis.</li>
            <li>Suspendisse consectetur erat eget urna adipiscing dignissim.In ut ante fringilla, dignissim metus nec, luctus justo.</li>
            <li>Quisque vitae lectus elementum, molestie ligula non, scelerisque libero.</li>
            <li>Sed ac eros tempor, scelerisque turpis vitae, ullamcorper tellus.Sed sodales ipsum molestie ultrices sagittis.</li>
            <li>Sed sodales ipsum molestie ultrices sagittis.</li>
            <li>Nulla bibendum dui eu risus bibendum consectetur.</li>
            <li>In ut ante fringilla, dignissim metus nec, luctus justo.Ut id nisl suscipit, congue leo vel, rhoncus eros.</li>
            <li>Quisque in purus a arcu tincidunt consequat.</li>
            <li>Suspendisse ultricies orci nec mi pretium lobortis.Duis laoreet lorem vel faucibus pulvinar.Duis laoreet lorem vel faucibus pulvinar.</li>
            <li>Nunc vitae leo sit amet sem egestas accumsan.</li>
            <li>In sed sapien faucibus, venenatis magna sed, posuere sem.</li>
            <li>Mauris id ipsum sed lorem tincidunt venenatis.Ut id nisl suscipit, congue leo vel, rhoncus eros.</li>
            <li>Aenean dapibus elit vel orci auctor, ut euismod sem laoreet.In ut ante fringilla, dignissim metus nec, luctus justo.</li>
            <li>Nunc sagittis diam quis massa tempor congue.</li>
            <li>Ut id nisl suscipit, congue leo vel, rhoncus eros.Duis laoreet lorem vel faucibus pulvinar.</li>
            <li>Morbi a purus consequat, aliquam odio non, pellentesque ante.In ut ante fringilla, dignissim metus nec, luctus justo.</li>
            <li>Duis laoreet lorem vel faucibus pulvinar.</li>
            <li>In quis odio vel enim sodales suscipit id at leo</li>
          </ul>
        </div>
      </div>               				          
    </div>
    <div id="step-4">
      <h2 class="StepTitle">Finish</h2>
      <div class="panel"> 
        <div class="panel-heading">
          <h3 class="panel-title"> Thanks for your Registration! </h3>
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, 
            sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, 
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. 
            Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. 
            Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
            Lorem ipsum dolor sit amet, consectetur adipisicing elit, 
            sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, 
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. 
            Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. 
            Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
          </p>
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, 
            sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, 
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. 
            Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. 
            Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
            Lorem ipsum dolor sit amet, consectetur adipisicing elit, 
            sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, 
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. 
            Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. 
            Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
          </p>
        </div>
      </div>              			
    </div>
  </div>



</div> <!-- /panel body -->	
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


<!-- Page Script File  =============================-->
<script src="js/jquery.smartWizard.js"></script>
<script src="js/smartWizard-custom.js"></script>

<!-- Core Jquery File  =============================-->
<script src="js/core.js"></script>


</body>
</html>