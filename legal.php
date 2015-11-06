<?php
define('APPLICATION', TRUE);                            // legacy
define('GROK', TRUE);                                   // iEMS 3.0 'cause we spliced some of that work in for 2.2
define('iEMS_PATH', '');                                // this is where the root of the site is; defined in all php pages.

require_once iEMS_PATH.'Connections/crsolutions.php';   // in iEMS 3.0, connections get loaded by iEMSLoader

require_once iEMS_PATH.'iEMSLoader.php';                // this contains some tidbits like preDebugger() which will 

$Loader = new iEMSLoader;

if(empty($_SESSION['iemsID']))          header('location: login.php');
if(!empty($_SESSION['agreement']))      header('location: index.php');

if(isset($_POST['submit']))
{
    if($_POST['agree'] == 'yes')
    {
        $_SESSION['agreement'] = true;
        header('location: index.php');
    }
    else
    {
        header('location: logout.php');
    }
}
else
{
?>     
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>iEMS Login<?php echo $server; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	
	<link href="js/dijit/themes/tundra/tundra.css" rel="stylesheet" type="text/css" />         

	<script type="text/javascript"> 
        	djConfig = {
                	isDebug: false, 
                	parseOnLoad: true
        	}; 
	</script>

	<script type="text/javascript" src="js/dojo/dojo.js"></script>
	<script type="text/javascript" src="js/dijit/dijit.js"></script>

    <script>
        dojo.require('dijit.form.Form');
        dojo.require('dijit.form.RadioButton');
    </script>
    
    <style type="text/css">
	body{
        	font-family:Arial, Helvetica, sans-serif; 
        	font-size:12px; 
        	color:#666666; 
        	background:#EAEBEB
	}
	a {
        	font-weight: bold;
        	color: #666666;
	}

	
	#footer a {
        	color: #5386AF;
	}
	#footer {
        	clear: both;
        	font-size: 10px;
        	color: #5386AF;
        	text-align: left;
        	padding-left: 45px;
	}
	#maincontent {
        	background:url(_template/images/iEMS_Legal_bottom.png) no-repeat bottom left;        	
	}
	.bolded {
        	font-weight:bold; 
        	color:#EF7601;
	}

	#top
        {
                background: url(_template/images/iEMS_Legal_top.png) no-repeat; 
                width: 815px; 
                height: 166px; 
                vertical-align: bottom;
                margin: 0px;
                padding: 0px;
        }
        h1
        {
                margin: 0px;
                padding: 0px;
                text-align: center;
        }
	</style>
</head>
<body bgcolor="#F4F4F4" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" class="tundra">
	<table width="825" border="0" align="center" cellpadding="0" cellspacing="0">	
        <tr>
			<td width="815" id="top">
				<h1 class="bolded">Notice</h1>	
			</td>
		</tr>
        <tr>
			<td valign="top" style="background:url(_template/images/iEMS_Legal_bg_repeat.png) repeat-y">
			<div id="maincontent">
				<div style="margin-left: 30px; margin-right: 30px;">
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sollicitudin, metus vel vestibulum fermentum, dolor elit iaculis velit, et mattis augue nibh vel mi. Praesent sodales vulputate risus nec porta. Suspendisse semper, justo eu porta auctor, erat purus convallis lacus, nec lacinia ante diam a mi. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Phasellus sed odio sed massa vehicula ultrices nec nec massa. Fusce pellentesque accumsan augue, et dictum purus pulvinar non. In quis nisi ante, sit amet ultricies mauris. Donec quis nisi tortor, et accumsan sem. Phasellus eleifend ullamcorper enim et placerat. Morbi commodo gravida magna. Sed consectetur porta tincidunt. Pellentesque sit amet nisl ligula. Nulla facilisi. Phasellus pulvinar ante in mauris tincidunt venenatis. Duis ultrices lobortis urna vel elementum. Donec imperdiet, risus eu scelerisque ultricies, nisl ligula sodales sapien, eget aliquet eros nisl eget felis. Nullam hendrerit mattis eros, nec varius leo pulvinar ut. Cras blandit, turpis non elementum bibendum, sapien mi elementum quam, nec posuere justo lorem eu mi. Praesent in nibh est, nec pretium libero.</p>
					<p>Phasellus elit libero, pretium luctus convallis interdum, dictum non eros. Aliquam ut lectus id dolor sodales vulputate. Mauris ornare, urna vel volutpat auctor, magna libero congue quam, vel aliquam enim ligula eget nunc. Proin hendrerit mauris eget nisl pharetra ultrices. Ut non lorem dolor. Donec velit nibh, sodales dignissim feugiat elementum, euismod ut enim. Suspendisse potenti. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Integer at ipsum non odio rhoncus tempor vitae eu erat. Suspendisse lobortis accumsan ultrices. Aliquam et eros ut felis mollis vestibulum ac id metus. Donec sollicitudin blandit tincidunt. Suspendisse dapibus luctus libero, non faucibus eros ullamcorper sed. Duis rhoncus consequat dapibus. Curabitur at nisl faucibus felis pulvinar pretium luctus nec est. Mauris venenatis sodales posuere. Nam euismod odio vel ligula bibendum elementum. Ut vel libero nibh. Sed nec lacinia libero. Suspendisse vel metus lectus, accumsan tincidunt justo.</p>
					<p>Suspendisse id ligula metus, in cursus risus. Nunc vel lorem est. Fusce tempor rhoncus nunc commodo condimentum. Nullam pellentesque consectetur massa et gravida. Vivamus sed ante at magna laoreet consectetur. Aliquam enim felis, pellentesque ut posuere in, egestas sed ligula. Phasellus ut lacus vel ipsum ultrices porttitor. Proin faucibus convallis purus ultricies dictum. Etiam eu eleifend mi. In gravida urna sed sapien dignissim faucibus mattis lacus lacinia. Vivamus pretium nisi sit amet dolor lacinia id semper velit iaculis. Duis dapibus auctor metus id aliquet. Mauris interdum, nisl ac accumsan congue, sapien lacus blandit nisi, vel viverra ligula nisl ac diam. Suspendisse potenti. Pellentesque egestas rhoncus risus id suscipit. Nunc porttitor feugiat blandit.</p>
					<p>Praesent at ante dui. Suspendisse mi augue, varius id tempus at, hendrerit et sapien. Suspendisse in faucibus odio. Etiam mollis gravida purus. Phasellus blandit imperdiet imperdiet. Etiam vitae lectus sed nulla rhoncus fringilla quis id ante. Praesent pharetra ligula auctor nisi varius vestibulum. Nulla eu leo in dolor vulputate ornare. Fusce suscipit, mauris quis pretium sodales, odio mauris tempor massa, ut fringilla nibh est placerat elit. Nulla quis massa eu risus dictum commodo. Sed dui mauris, congue nec mollis vel, semper at ante. Phasellus id arcu sit amet lorem ornare sodales. Aliquam ut sem eget tortor pulvinar aliquam. Quisque faucibus tristique elit id posuere. Sed in massa id diam suscipit scelerisque vel sit amet eros. Sed commodo ornare interdum.</p>
					<p>Fusce mattis dictum dui, sed elementum mauris auctor quis. Fusce velit libero, cursus sed mollis ut, blandit sed ipsum. Nulla volutpat quam ut risus iaculis rutrum. Sed consequat sapien nec sapien commodo gravida. Nullam nunc neque, tempus non imperdiet quis, varius tempus augue. Pellentesque quis metus at tortor fermentum volutpat. Sed eget sem enim, et imperdiet libero. Nullam vel nisl et dui molestie scelerisque. Vestibulum egestas, felis porta varius sollicitudin, neque justo molestie odio, quis euismod ante est et purus. Aliquam erat volutpat. Pellentesque vel elit leo, ut pharetra velit. Vestibulum congue mi vitae leo ullamcorper rutrum. Integer id nibh est. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Maecenas gravida, arcu sed ornare gravida, sem magna adipiscing dui, at dapibus odio leo sit amet enim. Duis leo sapien, sagittis eget rhoncus quis, viverra ac enim. Suspendisse rhoncus augue a nibh varius posuere. </p>
				</div>
				<div style="min-height: 100px; text-align: center; margin: auto; width: 250px;">
                                        <script>
                                            function toggleLegalButton(answer)
                                            {
                                                if(answer == 'yes')
                                                {
                                                    dojo.byId('submit').innerHTML = 'Continue';
                                                }
                                                else
                                                {
                                                    dojo.byId('submit').innerHTML = 'Log Out';
                                                }
                                                
                                            }
                                        </script>
					<form id="legalForm" method="POST" dojoType="dijit.form.Form">
                                                <div style="text-align: left;">
        						<input dojoType="dijit.form.RadioButton" type="radio" name="agree" value="yes" onClick="toggleLegalButton(this.value);"/>I agree to the above stuff.<br />                           
                                                        <input dojoType="dijit.form.RadioButton" type="radio" name="agree" value="no" onClick="toggleLegalButton(this.value);" checked />I DO NOT agree to the above stuff.<br />                           
                                                </div>
                                                <br />
						 <button id="submit"
							type="submit"
							dojoType="dijit.form.Button"
                                                        name="submit"
							>Log Out
						</button>
					</form>
				</div>
			</div>
			</td>
		</tr>
		<tr>
			<td id="footer">Copyright &copy; 2011 Conservation Resource Solutions, Inc.  All rights reserved. Site design by <a href="http://www.rvadv.com" target="_blank">Rearview</a>. </td>
		</tr>
	</table>
</body>
</html>
<?php }
//$Loader->preDebugger($_POST);
?>
