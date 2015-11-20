<?php include('partials/header.php'); ?>


                <div class="row">
                 <div class="col-mod-12">

                  <ul class="breadcrumb">
                   <li><a href="index.php">Dashboard</a></li>
                   <li><a href="#">Tables</a></li>
                   <li class="active">Dynamic Tables</li>
                   
                 </ul>
                 
                 <div class="form-group hiddn-minibar pull-right">
                  <input type="text" class="form-control form-cascade-control nav-input-search" size="20" placeholder="Search through site" />

                  <span class="input-icon fui-search"></span>
                </div>

                <h3 class="page-header"> Dynamic Tables <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>

                <blockquote class="page-information hidden">
                 <p>
                  Different styles of dynamic tables are available to represent dynamic information which can include sorting,pagination and many more .
                </p>
              </blockquote>
            </div>
          </div>

          <!-- Most visited and Traffic sources Graph -->
          <div class="row">
           <div class="col-md-12">
            <div class="panel">
             <div class="panel-heading text-primary">
              <h3 class="panel-title">
               <i class="fa fa-pencil-square"></i>
               Data Tables
               <span class="pull-right">
                <div class="btn-group code">
                 <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Classes used"><i class="fa fa-code"></i></a>
                 <ul class="dropdown-menu pull-right list-group" role="menu">
                  <li class="list-group-item"><code>.table-bordered</code></li>
                  <li class="list-group-item"><code>.table-hover</code></li>
                  <li class="list-group-item"><code>.table-striped</code></li>
                  <li class="list-group-item"><code>.display</code></li>
                </ul>
              </div>
              <a href="#" class="panel-minimize"><i class="fa fa-chevron-up"></i></a>
              <a href="#" class="panel-close"><i class="fa fa-times"></i></a>
            </span>
          </h3>
        </div>
        <div class="panel-body">
          <div class="alert alert-info alert-dismissable">
           <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
           Working search box, pagination ,ascending and descending buttons
         </div>
         <table  class="table table-bordered table-hover table-striped display" id="example" >
           <thead>
            <tr>
             <th>Rendering engine</th>
             <th>Browser</th>
             <th>Platform(s)</th>
             <th>Engine version</th>
             <th>CSS grade</th>
           </tr>
         </thead>
         <tbody>
          <tr class="odd gradeX">
           <td>Trident</td>
           <td><span class="label bg-info">Internet
            Explorer 4.0</span></td>
            <td>Win 95+</td>
            <td class="center"> 4</td>
            <td class="center"><span class="badge bg-warning">X</span></td>
          </tr>
          <tr class="even gradeC">
            <td>Trident</td>
            <td>Internet
             Explorer 5.0</td>
             <td>Win 95+</td>
             <td class="center">5</td>
             <td class="center"><span class="badge bg-purple">C</span></td>
           </tr>
           <tr class="odd gradeA">
             <td>Trident</td>
             <td><span class="label bg-danger">Internet
              Explorer 5.5</span></td>
              <td>Win 95+</td>
              <td class="center">5.5</td>
              <td class="center"><span class="badge bg-seagreen">A</span></td>
            </tr>
            <tr class="even gradeA">
              <td>Trident</td>
              <td>Internet
               Explorer 6</td>
               <td>Win 98+</td>
               <td class="center">6</td>
               <td class="center"><span class="badge bg-seagreen">A</span></td>
             </tr>
             <tr class="odd gradeA">
               <td>Trident</td>
               <td><span class="label bg-danger">Internet Explorer 7</span></td>
               <td>Win XP SP2+</td>
               <td class="center">7</td>
               <td class="center"><span class="badge bg-seagreen">A</span></td>
             </tr>
             <tr class="even gradeA">
               <td>Trident</td>
               <td><span class="label bg-warning">AOL browser (AOL desktop)</span></td>
               <td>Win XP</td>
               <td class="center">6</td>
               <td class="center"><span class="badge bg-seagreen">A</span></td>
             </tr>
             <tr class="gradeA">
               <td>Gecko</td>
               <td><span class="label bg-info">Firefox 1.0</span></td>
               <td>Win 98+ / OSX.2+</td>
               <td class="center">1.7</td>
               <td class="center"><span class="badge bg-seagreen">A</span></td>
             </tr>
             <tr class="gradeA">
               <td>Gecko</td>
               <td>Firefox 1.5</td>
               <td>Win 98+ / OSX.2+</td>
               <td class="center">1.8</td>
               <td class="center"><span class="badge bg-seagreen">A</span></td>
             </tr>
             <tr class="gradeA">
               <td>Gecko</td>
               <td><span class="label bg-info">Firefox 2.0</span></td>
               <td>Win 98+ / OSX.2+</td>
               <td class="center">1.8</td>
               <td class="center"><span class="badge bg-seagreen">A</span></td>
             </tr>
             <tr class="gradeA">
               <td>Gecko</td>
               <td>Firefox 3.0</td>
               <td>Win 2k+ / OSX.3+</td>
               <td class="center">1.9</td>
               <td class="center"><span class="badge bg-seagreen">A</span></td>
             </tr>
             <tr class="gradeA">
               <td>Gecko</td>
               <td><span class="label bg-purple">Camino 1.0</span></td>
               <td>OSX.2+</td>
               <td class="center">1.8</td>
               <td class="center"><span class="badge bg-seagreen">A</span></td>
             </tr>
             <tr class="gradeA">
               <td>Gecko</td>
               <td>Camino 1.5</td>
               <td>OSX.3+</td>
               <td class="center">1.8</td>
               <td class="center"><span class="badge bg-pink">C</span></td>
             </tr>
             <tr class="gradeA">
               <td>Gecko</td>
               <td><span class="label bg-gray">Netscape 7.2</span></td>
               <td>Win 95+ / Mac OS 8.6-9.2</td>
               <td class="center">1.7</td>
               <td class="center"><span class="badge bg-seagreen">A</span></td>
             </tr>
             <tr class="gradeA">
               <td>Gecko</td>
               <td>Netscape Browser 8</td>
               <td>Win 98SE+</td>
               <td class="center">1.7</td>
               <td class="center"><span class="badge bg-pink">C</span></td>
             </tr>
             <tr class="gradeA">
               <td>Gecko</td>
               <td><span class="label bg-gray">Netscape Navigator 9</span></td>
               <td>Win 98+ / OSX.2+</td>
               <td class="center">1.8</td>
               <td class="center"><span class="badge bg-pink">C</span></td>
             </tr>
             <tr class="gradeA">
               <td>Gecko</td>
               <td><span class="label bg-info">Mozilla 1.0</span></td>
               <td>Win 95+ / OSX.1+</td>
               <td class="center">1</td>
               <td class="center"><span class="badge bg-pink">C</span></td>
             </tr>
             <tr class="gradeA">
               <td>Gecko</td>
               <td>Mozilla 1.1</td>
               <td>Win 95+ / OSX.1+</td>
               <td class="center">1.1</td>
               <td class="center"><span class="badge bg-seagreen">A</span></td>
             </tr>
             <tr class="gradeA">
               <td>Gecko</td>
               <td><span class="status status-info">Mozilla 1.2</span></td>
               <td>Win 95+ / OSX.1+</td>
               <td class="center">1.2</td>
               <td class="center"><span class="badge bg-seagreen">A</span></td>
             </tr>
             <tr class="gradeA">
               <td>Gecko</td>
               <td>Mozilla 1.3</td>
               <td>Win 95+ / OSX.1+</td>
               <td class="center">1.3</td>
               <td class="center"><span class="badge bg-seagreen">A</span></td>
             </tr>
             <tr class="gradeA">
               <td>Gecko</td>
               <td><span class="status status-info">Mozilla 1.4</span></td>
               <td>Win 95+ / OSX.1+</td>
               <td class="center">1.4</td>
               <td class="center"><span class="badge bg-seagreen">A</span></td>
             </tr>
             <tr class="gradeA">
               <td>Gecko</td>
               <td>Mozilla 1.5</td>
               <td>Win 95+ / OSX.1+</td>
               <td class="center">1.5</td>
               <td class="center"><span class="badge bg-warning">X</span></td>
             </tr>
             <tr class="gradeA">
               <td>Gecko</td>
               <td><span class="status status-info">Mozilla 1.6</span></td>
               <td>Win 95+ / OSX.1+</td>
               <td class="center">1.6</td>
               <td class="center"><span class="badge bg-warning">X</span></td>
             </tr>
             <tr class="gradeA">
               <td>Gecko</td>
               <td>Mozilla 1.7</td>
               <td>Win 98+ / OSX.1+</td>
               <td class="center">1.7</td>
               <td class="center"><span class="badge bg-warning">X</span></td>
             </tr>
             <tr class="gradeA">
               <td>Gecko</td>
               <td><span class="status status-info">Mozilla 1.8</span></td>
               <td>Win 98+ / OSX.1+</td>
               <td class="center">1.8</td>
               <td class="center"><span class="badge bg-warning">X</span></td>
             </tr>
             <tr class="gradeA">
               <td>Gecko</td>
               <td><span class="status status-warning">Seamonkey 1.1</span></td>
               <td>Win 98+ / OSX.2+</td>
               <td class="center">1.8</td>
               <td class="center"><span class="badge bg-pink">C</span></td>
             </tr>
             <tr class="gradeA">
               <td>Gecko</td>
               <td><span class="status status-danger">Epiphany 2.20</span></td>
               <td>Gnome</td>
               <td class="center">1.8</td>
               <td class="center"><span class="badge bg-pink">C</span></td>
             </tr>
             <tr class="gradeA">
               <td>Webkit</td>
               <td><span class="status status-danger">Safari 1.2</span></td>
               <td>OSX.3</td>
               <td class="center">125.5</td>
               <td class="center"><span class="badge bg-pink">C</span></td>
             </tr>
             <tr class="gradeA">
               <td>Webkit</td>
               <td>Safari 1.3</td>
               <td>OSX.3</td>
               <td class="center">312.8</td>
               <td class="center"><span class="badge bg-warning">X</span></td>
             </tr>
             <tr class="gradeA">
               <td>Webkit</td>
               <td><span class="status status-danger">Safari 2.0</span></td>
               <td>OSX.4+</td>
               <td class="center">419.3</td>
               <td class="center"><span class="badge bg-warning">x</span></td>
             </tr>
             <tr class="gradeA">
               <td>Webkit</td>
               <td>Safari 3.0</td>
               <td>OSX.4+</td>
               <td class="center">522.1</td>
               <td class="center"><span class="badge bg-warning">X</span></td>
             </tr>
             <tr class="gradeA">
               <td>Webkit</td>
               <td><span class="status status-warning">OmniWeb 5.5</td>
               <td>OSX.4+</td>
               <td class="center">420</td>
               <td class="center"><span class="badge bg-seagreen">A</span></td>
             </tr>
             <tr class="gradeA">
               <td>Webkit</td>
               <td><span class="status status-purple">iPod Touch / iPhone</span></td>
               <td>iPod</td>
               <td class="center">420.1</td>
               <td class="center"><span class="badge bg-pink">C</span></td>
             </tr>
             <tr class="gradeA">
               <td>Webkit</td>
               <td>S60</td>
               <td>S60</td>
               <td class="center">413</td>
               <td class="center"><span class="badge bg-pink">C</span></td>
             </tr>
             <tr class="gradeA">
               <td>Presto</td>
               <td><span class="status status-info">Opera 7.0</span></td>
               <td>Win 95+ / OSX.1+</td>
               <td class="center">-</td>
               <td class="center"><span class="badge bg-seagreen">A</span></td>
             </tr>
             <tr class="gradeA">
               <td>Presto</td>
               <td>Opera 7.5</td>
               <td>Win 95+ / OSX.2+</td>
               <td class="center">-</td>
               <td class="center"><span class="badge bg-warning">X</span></td>
             </tr>
             <tr class="gradeA">
               <td>Presto</td>
               <td><span class="status status-info">Opera 8.0</span></td>
               <td>Win 95+ / OSX.2+</td>
               <td class="center">-</td>
               <td class="center"><span class="badge bg-seagreen">A</span></td>
             </tr>
             <tr class="gradeA">
               <td>Presto</td>
               <td>Opera 8.5</td>
               <td>Win 95+ / OSX.2+</td>
               <td class="center">-</td>
               <td class="center"><span class="badge bg-warning">X</span></td>
             </tr>
             <tr class="gradeA">
               <td>Presto</td>
               <td><span class="status status-info">Opera 9.0</span></td>
               <td>Win 95+ / OSX.3+</td>
               <td class="center">-</td>
               <td class="center"><span class="badge bg-warning">X</span></td>
             </tr>
             <tr class="gradeA">
               <td>Presto</td>
               <td>Opera 9.2</td>
               <td>Win 88+ / OSX.3+</td>
               <td class="center">-</td>
               <td class="center"><span class="badge bg-seagreen">A</span></td>
             </tr>
             <tr class="gradeA">
               <td>Presto</td>
               <td><span class="status status-info">Opera 9.5</span></td>
               <td>Win 88+ / OSX.3+</td>
               <td class="center">-</td>
               <td class="center"><span class="badge bg-seagreen">A</span></td>
             </tr>
             <tr class="gradeA">
               <td>Presto</td>
               <td>Opera for Wii</td>
               <td>Wii</td>
               <td class="center">-</td>
               <td class="center"><span class="badge bg-pink">C</span></td>
             </tr>
             <tr class="gradeA">
               <td>Presto</td>
               <td><span class="status status-primary">Nokia N800</span></td>
               <td>N800</td>
               <td class="center">-</td>
               <td class="center"><span class="badge bg-pink">C</span></td>
             </tr>
             <tr class="gradeA">
               <td>Presto</td>
               <td>Nintendo DS browser</td>
               <td>Nintendo DS</td>
               <td class="center">8.5</td>
               <td class="center"><span class="badge bg-purple">C/A<sup>1</sup></span></td>
             </tr>
             <tr class="gradeC">
               <td>KHTML</td>
               <td><span class="status status-success">Konqureror 3.1</span></td>
               <td>KDE 3.1</td>
               <td class="center">3.1</td>
               <td class="center"><span class="badge bg-pink">C</span></td>
             </tr>
             <tr class="gradeA">
               <td>KHTML</td>
               <td>Konqureror 3.3</td>
               <td>KDE 3.3</td>
               <td class="center">3.3</td>
               <td class="center"><span class="badge bg-seagreen">A</span></td>
             </tr>
             <tr class="gradeA">
               <td>KHTML</td>
               <td><span class="status status-success">Konqureror 3.5</span></td>
               <td>KDE 3.5</td>
               <td class="center">3.5</td>
               <td class="center"><span class="badge bg-warning">X</span></td>
             </tr>
             <tr class="gradeX">
               <td>Tasman</td>
               <td>Internet Explorer 4.5</td>
               <td>Mac OS 8-9</td>
               <td class="center">-</td>
               <td class="center"><span class="badge bg-warning">X</span></td>
             </tr>
             <tr class="gradeC">
               <td>Tasman</td>
               <td><span class="status status-info">Internet Explorer 5.1</span></td>
               <td>Mac OS 7.6-9</td>
               <td class="center">1</td>
               <td class="center"><span class="badge bg-pink">C</span></td>
             </tr>
             <tr class="gradeC">
               <td>Tasman</td>
               <td>Internet Explorer 5.2</td>
               <td>Mac OS 8-X</td>
               <td class="center">1</td>
               <td class="center"><span class="badge bg-pink">C</span></td>
             </tr>
             <tr class="gradeA">
               <td>Misc</td>
               <td><span class="status status-purple">NetFront 3.1</span></td>
               <td>Embedded devices</td>
               <td class="center">-</td>
               <td class="center"><span class="badge bg-pink">C</span></td>
             </tr>
             <tr class="gradeA">
               <td>Misc</td>
               <td>NetFront 3.4</td>
               <td>Embedded devices</td>
               <td class="center">-</td>
               <td class="center"><span class="badge bg-warning">X</span></td>
             </tr>
             <tr class="gradeX">
               <td>Misc</td>
               <td><span class="status status-purple">Dillo 0.8</span></td>
               <td>Embedded devices</td>
               <td class="center">-</td>
               <td class="center"><span class="badge bg-warning">X</span></td>
             </tr>
             <tr class="gradeX">
               <td>Misc</td>
               <td>Links</td>
               <td>Text only</td>
               <td class="center">-</td>
               <td class="center"><span class="badge bg-warning">X</span></td>
             </tr>
             <tr class="gradeX">
               <td>Misc</td>
               <td><span class="status status-purple">Lynx</span></td>
               <td>Text only</td>
               <td class="center">-</td>
               <td class="center"><span class="badge bg-warning">X</span></td>
             </tr>
             <tr class="gradeC">
               <td>Misc</td>
               <td><span class="status status-danger">IE Mobile</span></td>
               <td>Windows Mobile 6</td>
               <td class="center">-</td>
               <td class="center"><span class="badge bg-pink">c</span></td>
             </tr>
             <tr class="gradeC">
               <td>Misc</td>
               <td>PSP browser</td>
               <td>PSP</td>
               <td class="center">-</td>
               <td class="center"><span class="badge bg-pink">c</span></td>
             </tr>
             <tr class="gradeU">
               <td>Other browsers</td>
               <td><span class="status status-danger">All others</span></td>
               <td>-</td>
               <td class="center">-</td>
               <td class="center"><span class="badge bg-pink">c</span></td>
             </tr>				
           </tbody>
           <tfoot>
            <tr>
             <th>Rendering engine</th>
             <th>Browser</th>
             <th>Platform(s)</th>
             <th>Engine version</th>
             <th>CSS grade</th>
           </tr>	
         </tfoot>
       </table>
     </div> <!-- /panel body -->
   </div>
 </div> <!-- /col-md-12 -->
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


</div>  <!-- /.right-sidebar-holder -->
</div>  <!-- /.right-sidebar -->


</div> <!-- /.box-holder -->
</div><!-- /.site-holder -->


<!-- Load JS here for Faster site load =============================-->
<script src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
<script src="js/jquery-ui-1.10.3.custom.min.js"></script>
<script src="js/jquery.ui.touch-punch.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-select.js"></script>
<script src="js/bootstrap-switch.js"></script>
<script src="js/jquery.tagsinput.js"></script>
<script src="js/jquery.placeholder.js"></script>
<script src="js/bootstrap-typeahead.js"></script>
<script src="js/application.js"></script>
<script src="js/moment.min.js"></script>
<script src="js/jquery.dataTables.min.js"></script> <!-- Edited for search input -->
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

  <script src="js/bootstrap-datatables.js"></script>
<script src="js/dataTables-custom.js"></script>

<!-- Core JS =============================-->
<script src="js/core.js"></script>
</body>
</html>