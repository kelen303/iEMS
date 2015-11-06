<?php
    if(!isset($_SESSION))
        session_start();
    $_SESSION['formUsed'] = 'assetUploader';
?>
<div style="width: 650px; position: relative;">
        <style>
        .nihilo .dijitFileInputVisible {
            
            }
        .nihilo .dijitFileInputReal {
            
            }
        .nihilo .dijitFileInput {
            
                                }
        </style>
        <p>Please limit your selection to .csv or .txt files.</p>
        <form enctype="multipart/form-data" id="upload_form" action="progressbar/target.php" method="POST" class="nihilo">
            <input id="progress_key"
                name="APC_UPLOAD_PROGRESS" 
                type="hidden" 
                style="color: #000;"
                value="<?php echo uniqid(""); ?>"                
                />            
                
            <input id="asset_file" 
                name="asset_file"
                type="file"
                style="color: #000;" 
                dojoType="dojox.form.FileInput"                
                /><br />
            <div
                value="Upload"
                onclick="javascript:validateUpload(dojo.byId('uploadResponse'));"
                dojoType="dijit.form.Button"
                style="color: #000;"
                >Upload</div>
        </form>
        
        <div id="timerBarContainer"
            style="display: none;"
            >
            <div id="timerBar" 
                style="width:300px" 
                annotate="true"
                maximum="100" 
                progress="0" 
                dojoType="dijit.ProgressBar"    
                ></div>
        </div>
        <br /><br />
	        <div>
                <span style="cursor: pointer;" onclick="AMToggler(dojo.byId('ie8Message'));">Internet Explorer 8 Users:</span>
                <div id="ie8Message" style="display: none;">
                    <p>Once you have selected your file, you may see a file path similar to:<br />
                    <pre>c:\fakepath\myfile.csv</pre>
                    This is normal and nothing about which to be concerned. If it is bothersome, simply add iems.crsolutions.us
                    to your trusted interent sites by following the instructions found here:<br />
                    <pre>
                    <a href="http://windows.microsoft.com/en-us/windows-vista/Security-zones-adding-or-removing-websites" target="_blank">Security zones: adding or removing websites</a>
                    </pre>
                    </p>
                </div>
                
            </div>
        
        <div id="uploadResponse"></div>
</div>

