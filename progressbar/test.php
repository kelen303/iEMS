
<?php
    /** 
     * to use this, adjust upload.php, getprogress.php, and 
     * target.php paths in js/iems/assetModelling.js [hint: search 
     * 'test.php' to find places that need to be 
     * remarked/unremarked] 
    */
?>
<link href="../js/dijit/themes/nihilo/nihilo.css" rel="stylesheet" type="text/css" />         
<link href="../js/dojox/form/resources/FileInput.css" rel="stylesheet" type="text/css" />         
 
 
<script type="text/javascript"> 
    djConfig = {
        isDebug: true, 
        parseOnLoad: true
        }; 
</script>
 
<script type="text/javascript" src="../js/dojo/dojo.js"></script>
<script type="text/javascript" src="../js/dijit/dijit.js"></script>
<script type="text/javascript" src="../js/iems/assetModelling.js"></script>


    <?php   
       require_once('upload.php');
       /*
session_start();
       print '<pre>';
print_r($_SESSION);

print '<hr />';
print_r($_POST);
print '</pre>';
*/
    ?>


