<html>
<head>
<title>Conservation Resource Solutions</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="crsolutions_styles.css" rel="stylesheet" type="text/css">
<?Php
define('APPLICATION', TRUE);
session_start();
?>

<script type="text/javascript" src="Scripts/mootools.v1.1_packed.js"></script>
<script type="text/javascript" src="Scripts/crs_homepage.js"></script>
<script type="text/JavaScript">
<!--
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_nbGroup(event, grpName) { //v6.0
  var i,img,nbArr,args=MM_nbGroup.arguments;
  if (event == "init" && args.length > 2) {
    if ((img = MM_findObj(args[2])) != null && !img.MM_init) {
      img.MM_init = true; img.MM_up = args[3]; img.MM_dn = img.src;
      if ((nbArr = document[grpName]) == null) nbArr = document[grpName] = new Array();
      nbArr[nbArr.length] = img;
      for (i=4; i < args.length-1; i+=2) if ((img = MM_findObj(args[i])) != null) {
        if (!img.MM_up) img.MM_up = img.src;
        img.src = img.MM_dn = args[i+1];
        nbArr[nbArr.length] = img;
    } }
  } else if (event == "over") {
    document.MM_nbOver = nbArr = new Array();
    for (i=1; i < args.length-1; i+=3) if ((img = MM_findObj(args[i])) != null) {
      if (!img.MM_up) img.MM_up = img.src;
      img.src = (img.MM_dn && args[i+2]) ? args[i+2] : ((args[i+1])? args[i+1] : img.MM_up);
      nbArr[nbArr.length] = img;
    }
  } else if (event == "out" ) {
    for (i=0; i < document.MM_nbOver.length; i++) {
      img = document.MM_nbOver[i]; img.src = (img.MM_dn) ? img.MM_dn : img.MM_up; }
  } else if (event == "down") {
    nbArr = document[grpName];
    if (nbArr)
      for (i=0; i < nbArr.length; i++) { img=nbArr[i]; img.src = img.MM_up; img.MM_dn = 0; }
    document[grpName] = nbArr = new Array();
    for (i=2; i < args.length-1; i+=2) if ((img = MM_findObj(args[i])) != null) {
      if (!img.MM_up) img.MM_up = img.src;
      img.src = img.MM_dn = (args[i+1])? args[i+1] : img.MM_up;
      nbArr[nbArr.length] = img;
  } }
}

sfHover = function() {
	var sfEls = document.getElementById("nav").getElementsByTagName("LI");
	for (var i=0; i<sfEls.length; i++) {
		sfEls[i].onmouseover=function() {
			this.className+=" sfhover";
		}
		sfEls[i].onmouseout=function() {
			this.className=this.className.replace(new RegExp(" sfhover\\b"), "");
		}
	}
}
if (window.attachEvent) window.attachEvent("onload", sfHover);

//-->
</script>
<style type="text/css">
<!--
.style2 {
	font-size: 18px;
	color: #FF6600;
}
.style3 {color: #0C2C4F}
body {
	background-color: #FFFFFF;
}
-->
</style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="MM_preloadImages('images/CRS_nav5contact_on.gif','images/CRS_nav1about_on.gif','images/CRS_nav2custsol_on.gif','images/CRS_nav3iems_on.gif','images/CRS_nav4news_on.gif')">
<table width="750" height="158" border="0" align="center" cellpadding="0" cellspacing="0" class="MainTable">
  <tr>
    <td height="84" colspan="2" valign="top"><a href="index.html"><img src="images/CRS_header.gif" alt="CRS, inc. Conservation Through Technology" width="750" height="84" border="0"></a></td>
  </tr>
  <tr>
    <td height="23" colspan="2" valign="top" class="menuholder"><table border="0" cellpadding="0" cellspacing="0" width="100%">
      <td width="250" height="22" align="left" valign="top" bgcolor="#FFFFFF"><img src="images/CRS_navblank_2.gif" alt="conservation resources" width="126" height="22"></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="2" valign="top" class="flashtitlebox"><script src="Scripts/AC_RunActiveContent.js"></script>
        <script type="text/javascript">
		AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0','width','750','height','100','title','Customer Solutions','src','images/titleflash_iEMSlogin','quality','high','pluginspage','http://www.macromedia.com/go/getflashplayer','movie','images/titleflash_iEMSlogin','wmode','transparent' ); //end AC code
		</script>
        <noscript>
        <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="750" height="100" title="Customer Solutions">
          <param name="movie" value="images/titleflash_iEMSlogin.swf">
          <param name="wmode" value="transparent">
          <param name="quality" value="high">
          <embed src="images/titleflash_iEMSlogin.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="750" height="100"></embed>
        </object>
        </noscript>
    </td>
  </tr>
  <tr>
    <td width="549" height="19" valign="top" class="content"><span class="style2" style="font-weight: bold">NEW! <span class="style3">In iEMS 2.0</span> </span>
        <ul>
          <li>Set your default meters to view.</li>
          <li><strong>Create your own list </strong>of meters to manage.</li>
          <li>Users can now<strong> update and change passwords</strong>.</li>
          <li>Users can<strong> view meter data on individual charts or  combine onto one chart</strong>.</li>
          <li><strong>New Event Performance </strong>Section allows users to monitor performance during a demand response event and provides post event metrics.</li>
          <li><strong>New Advanced Charting</strong> section allows users to compare  different dates against each other for one meter, as well export and view data  over larger spans.</li>
          <li>On the fly baseline and pricing information that can be  turned on or off with a single click.</li>
          <li><strong>CSV data export functionality</strong> on all charts and  reports.</li>
          <li>Managers and customers can <strong>manage email  and voice contacts</strong> for demand response events and price and demand alarms.</li>
        </ul>
      <p><strong>MORE COMING SOON!  </strong></p>
      <ul>
          <li>Meter management will allow managers and users to  assess metering equipment uptime, reliability and performance. </li>
        <li>Alarm management:&nbsp;  Managers and users will now be able set and mange demand threshold and  price alarms.</li>
        <li>Summary Reports: Users can take data analysis to a new  level with customizable usage and demand reports.&nbsp; </li>
    </ul></td>
    <td width="201" valign="top"><table width="199" border="0" align="right" cellpadding="0" cellspacing="0">
      <tr class="MainTable">
        <td valign="top">&nbsp;</td>
      </tr>
      <tr class="MainTable">
        <td width="201" valign="top"><img src="images/CRS_sectiontitle_accessrigh.gif" alt="access account" width="199" height="26" class="rightmenu"></td>
      </tr>
      <tr class="MainTable">
        <td valign="top" class="rightmenubottom"><div align="center"><a href="http://iems.crsolutions.us" target="_blank"><img src="images/CRS_iemslogo.jpg" alt="iEMS logo" width="142" height="102" border="0"><br>
                    <br>
          </a>
<form action="../index.php" method="post" name="form1">
                    <span class="content">User</span>
                    <input name="username" type="text" id="username">
<span class="content">Pass</span>
                    <input name="password" type="text" id="password">
                    <input name="login" type="submit" id="login" value="login">
                  </form>
              <a href="http://iems.crsolutions.us" target="_blank"> </a></div></td>
      </tr>
      <tr class="MainTable">
        <td valign="top">&nbsp;</td>
      </tr>
      <tr class="MainTable">
        <td valign="top"><div align="right"><span class="sectiontitlebox"><img src="images/CRS_sectiontitle_hottopics.gif" alt="hot topics" width="199" height="26" align="absbottom" class="rightmenutop"></span></div></td>
      </tr>
      <tr class="MainTable">
        <td valign="top" class="rightmenubottom"><div id="log_res">
          <div id="randomttl">Did you know that CRS... </div>
          <div id="randomtext"></div>
        </div></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="19" colspan="2" valign="top" class="background"><div align="center"><a href="http://iems.crsolutions.us" target="_blank"><br>
    </a></div></td>
  </tr>
  <tr>
    <td height="19" colspan="2" valign="top" class="footer">&nbsp;</td>
  </tr>
</table>
</body>
</html>