<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd"> 
<html>
<head>
    <title>pointchannels.store.php tests</title>
    <!--<link rel="stylesheet" type="text/css" href="_template/crs_php.inc.css">-->

    <link href="js/dijit/themes/tundra/tundra.css" rel="stylesheet" type="text/css" />    
    <link href="js/dojox/grid/enhanced/resources/tundraEnhancedGrid.css" rel="stylesheet" type="text/css" />    
    
    
    <script type="text/javascript"> 
        djConfig = {
            isDebug: false, 
            parseOnLoad: true
            }; 
    </script>
    
    <script type="text/javascript" src="js/dojo/dojo.js"></script>
    <script type="text/javascript" src="js/dijit/dijit.js"></script>
    <script type="text/javascript" src="js/iems/login.js"></script>
    <style>
           a {
        	    font-weight: bold;
        	    color: #666666;
	}
	.tundra .dijitButtonNode {
        	background: none;
        	color: #FFFFFF;
        	width: 50px;
        	background: #2B2B47;
        	font-size: 10px;
        	font-weight: bold;
        	text-align: center;
        	padding: 2px;
        	border:none;
        	cursor: pointer;
	}
	
	.tundra .dijitButtonNode:hover {
        	color: #000;
	}
	
	.tundra .dijitButtonContents {
        	color: #FFF;
	}
	
	.tundra .dijitButtonContents:hover {
        	color: #000;
	}
	.dijitInputField input {
        	color: #000;
	}
	
	.tundra .dijitButtonHover {
        	color: #000;
	}
	.tundra .dijitHover {
        	color: #000;
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

	.bolded {
        	font-weight:bold; 
        	color:#000033;
	}
	#loginResponse {color: #FFF; margin-bottom: 6px;}
	#loginResponse a {color: #2B2B47;}
	#forgotPasswordMessage {color: #FFF; display: none;}
	#forgotPasswordToggle {color: #FFF; cursor: pointer;}

    </style>
    <?php if(isset($_POST['username'])){ ?>
        <script type="text/javascript">
             dojo.require('dojo.data.ItemFileReadStore');
             dojo.require("dojox.grid.EnhancedGrid");  
             dojo.require("dojox.grid.TreeGrid");          
             dojo.require('dijit.tree.ForestStoreModel');
        </script>
    <?php } ?>
</head>
<body class="tundra">
<h2>pointchannels.store.php usage tests</h2>    
<?php if(!isset($_POST['username'])){ ?>
    <form id="loginForm" 
            dojoType="dijit.form.Form"
            action=""
            method="POST"
            style="margin-top: 14px;"
            >
        <table border="0" cellspacing="0" cellpadding="1">
            <tr>								
            <td align="right">
                <input id="username"
                name="username" 
                type="text" 
                dojoType="dijit.form.ValidationTextBox" 
                required="true"     
                invalidMessage="You must enter this information.<br />This is your CRS-issued user name. <br />If you are an iEMS user, this is your iEMS user name." 
                size="22"
                value="" />			
            </td>
            <td width="200" style="color: #FFF;">Username</td>
        
            </tr>
            <tr>								
            <td align="right">
                <input id="password"
                name="password" 
                type="password" 
                dojoType="dijit.form.ValidationTextBox" 
                required="true" 
                invalidMessage="Please provide your password."
                size="22"
                value="" />
            </td>
            <td style="color: #FFF;">Password</td>
            </tr>
            <tr>	
            <td style="text-align: right;">        
                <button id="submit"
                    type="submit"
                    dojoType="dijit.form.Button"
                    >Log In
                    <script type="dojo/method" event="startup">
                        var form = dijit.byId("loginForm");
                        this.attr("disabled", !form.isValid()); // set initial state
                        this.connect(form, "onValidStateChange", function(state){
                        this.attr("disabled", !state);
                        });                                    
                    </script>
                </button>
            </td>
            <td>
                <button 
                    dojoType="dijit.form.Button" 
                    type="reset"
                     >Reset
                    <script type="dojo/method" event="onClick">
                        dojo.byId("username").value="";
                        dojo.byId("password").value=""; 
                        dijit.byId("submit").attr("disabled", true);
                    </script>
                </button>
            </td>				  
            </tr>
        
        </table>
    </form><!-- Login Form -->
<?php } ?>
<?php if(isset($_POST['username'])){ ?>
    
    <h3>dojo table using o=json</h3>  
<!-- json data store -->
    <div dojoType="dojo.data.ItemFileReadStore"
        jsId="jsonStore" 
        urlPreventCache="true"
        url="pointchannels.store.php?u=<?php echo $_POST['username']; ?>&p=<?php echo $_POST['password']; ?>&o=json"
        >
    </div>
    <!-- end json data store -->
    <div 
        dojoType="dijit.tree.ForestStoreModel"         
        store="jsonStore"         
        query="{type:'resource'}"
        jsId="jsonModel"
        rootId="jsonRoot" 
        rootLabel="Resources" 
        childrenAttrs="children"
    ></div>

<h4>Resources</h4>
<table dojoType="dojox.grid.EnhancedGrid" store="jsonStore" style="width: 550px; margin: 20px;">
<thead>
<tr>
<th field="id" width="100px">Resource ID</th>
<th field="name" width="400px">Resource Name</th>            
</tr>            
</thead>
</table>
<h4>Resources with Assets</h4>
    <table 
            dojoType="dojox.grid.TreeGrid"  
            store="jsonStore" 
            style="width: 1000px; height: 500px; margin: 20px;">
      <thead>
        <tr>        
          <th field="name" width="400px">Resource Name</th>
          <th field="children">
            <table>
              <thead>
                <tr>
                  <th 
                      field="identifier" 
                      width="200px" 
                      aggregate="value"
                  >Identifier</th>              
                  <th field="name" width="300px">Name</th>
                </tr>
              </thead>
            </table>
          </th>
        </tr>
      </thead>
    </table>

<?php } ?>
          


    </div>
    
</body>
</html>
