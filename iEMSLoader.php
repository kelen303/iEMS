<?php
/*  ===============================================================================
    --------------------------------iEMSLoader.php---------------------------------
                                         v 1.0									  
 
 	This provides a centralized point to be used used to load all of the MDR
 	requirements and establish the definitions which most pages need such as paths
 	to support products.
 
 	This also instiates the session for most pages.
 
    Created by: Marian C. Buford                                                 
                Conservation Resource Solutions, Inc. (CRS) 
    Created on: 10.07.2010
    License:    Proprietary
    Copyright:  2010-2011 Conservation Resource Solutions, Inc.  All rights reserved.
                                                                               
    =============================================================================== */  
/*  ===============================================================================
    MAINTENANCE OVERRIDE
    -------------------------------------Notes-------------------------------------
    Simply unremark the following line to put the site into maintenance. Reverse
    the process to re-enable the site.
    =============================================================================== */    
    //header('location: maint.html');  

/* 	===============================================================================
 	AUTHORIZED USE CHECK
 	=============================================================================== */
    if(!defined('APPLICATION')){header('HTTP/1.0 404 not found'); exit; }

/*  ===============================================================================
    ENVIRONMENT
    =============================================================================== */
    set_error_handler("mdrErrorHandler");

/*  ===============================================================================
    FUNCTION : mdrErrorHandler()
    =============================================================================== */
	function mdrErrorHandler($errno, $errstr, $errfile, $errline){
		switch ($errno) {
		case E_USER_ERROR:
			include_once('error_header.html');
			echo '<div style="margin: 30px;">';
			if ($errstr == "(SQL)"){
				// handling an sql error
				echo "<b>SQL Error</b> [$errno] " . SQLMESSAGE . "<br />\n";
				echo "Query : " . SQLQUERY . "<br />\n";
				echo "On line " . SQLERRORLINE . " in file " . SQLERRORFILE . " ";
				echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
				echo "Aborting...<br />\n";
				echo "Please try again [<a href=\"".$_SERVER['HTTP_REFERER']."\">return</a>].<br />\n";
				echo "If the error continues to occur,\n";
				echo "please contact <a href=\"http://help.crsolutions.us\">iEMS Help Desk</a>.";
				
			} else {
				echo "<b>iEMS ERROR</b> [$errno] $errstr<br />\n";
				echo "Please try again [<a href=\"".$_SERVER['HTTP_REFERER']."\">return</a>].<br />\n";
				echo "If the error continues to occur,\n";
				echo "please contact <a href=\"http://help.crsolutions.us\">iEMS Help Desk</a>.";
				//echo "  Fatal error on line $errline in file $errfile";
				//echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
				//echo "Aborting...<br />\n";
			}
			echo '</div>';
			exit(1);
			break;
	
		case E_USER_WARNING:
		case E_USER_NOTICE:
		}
		/* Don't execute PHP internal error handler */
		return true;
	} // mdrErrorHandler
	
/*  ===============================================================================
    FUNCTION : sqlerrorhandler()
    =============================================================================== */
	function sqlerrorhandler($ERROR, $QUERY, $PHPFILE, $LINE){
		define("SQLQUERY", $QUERY);
		define("SQLMESSAGE", $ERROR);
		define("SQLERRORLINE", $LINE);
		define("SQLERRORFILE", $PHPFILE);
		trigger_error("(SQL)", E_USER_ERROR);
	} // sqlerrorhandler
    /*
        // trigger an sql error for testing
        $query = "SELECT * FROM tbl LIMIT 1";
        $sql = @mysql_query($query)
        or sqlerrorhandler("(".mysql_errno().") ".mysql_error(), $query, $_SERVER['PHP_SELF'], __LINE__);
    */

/*  ===============================================================================
    CLASS : iEMSLoader()
    =============================================================================== */
class iEMSLoader
{
/*  ===============================================================================
    CONSTRUCTOR
    =============================================================================== */
    public function __construct($loggingOn = false)
    {
        if($loggingOn)
            $log = new Logging();    

        if($loggingOn) $log->lwrite('_____ BEGIN iEMSLoader Initialization _____');
        
        require_once('mdr/CAO.php');
        if($loggingOn) $log->lwrite('loaded: mdr/CAO.php');        

        require_once('mdr/User.php');
        if($loggingOn) $log->lwrite('loaded: mdr/User.php');

        require_once('mdr/Domain.php');
        if($loggingOn) $log->lwrite('loaded: mdr/Domain.php');

        require_once('mdr/Privileges.php');
        if($loggingOn) $log->lwrite('loaded: mdr/Privileges.php');

        require_once('mdr/UnitOfMeasure.php');
        if($loggingOn) $log->lwrite('loaded: mdr/UnitOfMeasure.php');

        require_once('mdr/CRSDate.php');
        if($loggingOn) $log->lwrite('loaded: mdr/CRSDate.php');

        require_once('mdr/PointChannels.php');
        if($loggingOn) $log->lwrite('loaded: mdr/PointChannels.php');

        require_once('mdr/PointChannel.php');
        if($loggingOn) $log->lwrite('loaded: mdr/PointChannel.php');

        require_once('mdr/PointType.php');
        if($loggingOn) $log->lwrite('loaded: mdr/PointType.php');

        require_once('mdr/MeterPoint.php');
        if($loggingOn) $log->lwrite('loaded: mdr/MeterPoint.php');

        require_once('mdr/TimeZone.php');
        if($loggingOn) $log->lwrite('loaded: mdr/TimeZone.php');

        require_once('mdr/Preferences.php');
        if($loggingOn) $log->lwrite('loaded: mdr/Preferences.php');

        require_once('mdr/TimeSpan.php');
        if($loggingOn) $log->lwrite('loaded: mdr/TimeSpan.php');

        require_once('mdr/IntervalValueSets.php');
        if($loggingOn) $log->lwrite('loaded: mdr/IntervalValueSets.php');    


        /* in iEMS3 the following are loaded as needed and not on initialization */
        
        $this->includePricing();
        if($loggingOn) $log->lwrite('loaded: includePricing()');

        $this->includeContactManager();
        if($loggingOn) $log->lwrite('loaded: includeContactManager()');

        $this->includeContactManager();
        if($loggingOn) $log->lwrite('loaded: includeContactManager()');

        $this->includeReports();
        if($loggingOn) $log->lwrite('loaded: includeReports()');

        $this->includeStatistics();
        if($loggingOn) $log->lwrite('loaded: includeStatistics();');

        $this->includeEventPerformance();
        if($loggingOn) $log->lwrite('loaded: includeEventPerformance();');

        $this->includeObject(); // contactmanager uses this
        if($loggingOn) $log->lwrite('loaded: includeObject()');

        $this->includeContactUse();
        if($loggingOn) $log->lwrite('loaded: includeContactUse()');
       
        $this->includePriority();
        if($loggingOn) $log->lwrite('loaded: includePriority()');

        $this->includeType();
        if($loggingOn) $log->lwrite('loaded: includeType()');

        $this->includeContactOwner();
        if($loggingOn) $log->lwrite('loaded: includeContactOwner()');
        
        $this->includeContactProfile();
        if($loggingOn) $log->lwrite('loaded: includeContactProfile()');

        $this->includeContactValue();
        if($loggingOn) $log->lwrite('loaded: includeContactValue()');

        $this->includeContactValueSubType();
        if($loggingOn) $log->lwrite('loaded: includeContactValueSubType()');

        $this->includeContactValueType();
        if($loggingOn) $log->lwrite('loaded: includeContactValueType()');

        $this->includeEmailQueue();
        if($loggingOn) $log->lwrite('loaded: includeEmailQueue()');

        session_start();//this has to be started after the above includes (has to do with serialization and passing the object via $_SESSION)

        $_SESSION['SYSTEM_ALERT'] = false;
        
        define('iEMS_VERSION','2.2');
       
        if($loggingOn) $log->lwrite('END iEMSLoader Initialization ===================');
    } // CONSTRUCTOR

/*  ===============================================================================
    FUNCTION : preDebugger()
    -----------------------------------variables-----------------------------------
    $data           mixed   :   string or array to display
    $color          string  :   what css color to use -- handy when outputing
                                different sets of data.
    =============================================================================== */
    function preDebugger($data,$color="#980000")
    {
        print '<pre style="margin: 20px; 
                        color:'.$color.'; 
                        border-top: 1px double #000; 
                        border-bottom: 1px double #000; 
                        background-color: #d5dfe7;">';
        print_r($data);
        print '</pre>';
    } // preDebugger()

/*  ===============================================================================
    FUNCTION : includePricing()
    =============================================================================== */
    function includePricing()
    {
        require_once('mdr/Pricing.php');
    } // includePricing

/*  ===============================================================================
    FUNCTION : includeContactManager()
    =============================================================================== */
    function includeContactManager()
    {
        require_once('mdr/ContactManager.php');
        require_once('mdr/ContactReportLineItem.php');
    } // includeContactManager

/*  ===============================================================================
    FUNCTION : includeReports()
    =============================================================================== */
    function includeReports()
    {
        require_once('mdr/Reports.php');
    } // includeReports

/*  ===============================================================================
    FUNCTION : includeStatistics()
    =============================================================================== */
    function includeStatistics()
    {
        require_once('mdr/Statistics.php');
    } // includeStatistics.

/*  ===============================================================================
    FUNCTION : includeEventPerformance()
    =============================================================================== */
    function includeEventPerformance()
    {
        require_once('mdr/EvtPerfSummary.php');
        require_once('mdr/EvtPerfSummaryLineItem.php');
    } // includeEventPerformance

/*  ===============================================================================
    FUNCTION : includeObject()
    =============================================================================== */
    function includeObject()
    {
        require_once('mdr/Object.php');
    } // includeObject

/*  ===============================================================================
    FUNCTION : includeContactUse()
    =============================================================================== */
    function includeContactUse()
    {
        require_once('mdr/ContactUse.php');
    } // includeContactUse

/*  ===============================================================================
    FUNCTION : includePriority()
    =============================================================================== */
    function includePriority()
    {
        require_once('mdr/Priority.php');
    } // includePriority

/*  ===============================================================================
    FUNCTION : includeType()
    =============================================================================== */
    function includeType()
    {
        require_once('mdr/Type.php');
    } // includeType
/*  ===============================================================================
    FUNCTION : includeContactOwner()
    =============================================================================== */
    function includeContactOwner()
    {
        require_once('mdr/ContactOwner.php');
    } // includeContactOwner

/*  ===============================================================================
    FUNCTION : includeContactProfile()
    =============================================================================== */
    function includeContactProfile()
    {
        require_once('mdr/ContactProfile.php');
    } // includeContactProfile

/*  ===============================================================================
    FUNCTION : includeContactValue()
    =============================================================================== */
    function includeContactValue()
    {
        require_once('mdr/ContactValue.php');
    } // includeContactValue

/*  ===============================================================================
    FUNCTION : includeContactValueSubType()
    =============================================================================== */
    function includeContactValueSubType()
    {
        require_once('mdr/ContactValueSubType.php');
    } // includeContactValueSubType

/*  ===============================================================================
    FUNCTION : includeContactValueType()
    =============================================================================== */
    function includeContactValueType()
    {
        require_once('mdr/ContactValueType.php');
    } // includeContactValueType

/*  ===============================================================================
    FUNCTION : includeEmailQueue()
    =============================================================================== */
    function includeEmailQueue()
    {
        require_once('mdr/EmailQueue.php');
    } // includeEmailQueue

/*  ===============================================================================
    FUNCTION : includeIsoneDayAheadBids()
    =============================================================================== */
    function includeIsoneDayAheadBids()
    {
        require_once('mdr/IsoneDayAheadBid.php');
        require_once('mdr/IsoneDayAheadBids.php');
    } // includeIsoneDayAheadBids

/*  ===============================================================================
    FUNCTION : includeIsoneDayAheadBidForms()
    =============================================================================== */
    function includeIsoneDayAheadBidForms()
    {
        require_once('includes/dayAheadBids.php');
    } // includeIsoneDayAheadBidForms

/*  ===============================================================================
    FUNCTION : dateSpanCalculator()
    =============================================================================== */ 
    function dateSpanCalculator($fromDate,$toDate)
	{
		$uxDay = 60 * 60 * 24;
		
        $difference = strtotime($toDate,0) - strtotime($fromDate,0); // Difference in seconds
        
        $dateSpan = round($difference / $uxDay) +1; //for this app, we add a day as our span is inclusive
        
		return $dateSpan; // END : dateSpanCalculator();
	} // dateSpanCalculator

} // iEMSLoader

/*  ===============================================================================
    CLASS : Logging()
    =============================================================================== */
class Logging
{
	private $log_file = 'logs/iemslog.txt';
	private $fp = null;

/*  ===============================================================================
    FUNCTION : lwrite()
    =============================================================================== */
	public function lwrite($message){
		
		if (!$this->fp) $this->lopen();
		
		$script_name = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
		
		$time = date('H:i:s');
		
		fwrite($this->fp, "$time ($script_name) $message\n");
	} // lwrite

/*  ===============================================================================
    FUNCTION : lopen()
    =============================================================================== */
	private function lopen(){
		
		$lfile = $this->log_file;
		
		$today = date('Y-m-d');
		
		$this->fp = fopen($lfile . '_' . $today, 'a') or exit("Can't open $lfile!");        
	} // lopen

} // Logging

?>
