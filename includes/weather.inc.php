<?php
/* reference for distance calcs:
   http://eclectrics.com/software/2009/09/getting-the-current-weather-conditions/
   http://www.movable-type.co.uk/scripts/latlong.html
   */


class Weather 
{
    var $p_stationURL;
    var $p_clientLatLonURL;
    var $p_stationCandidates;
    var $p_earthRadius = 6378.16; // in km
    var $p_clientLat = 0;
    var $p_clientLon = 0;
    var $p_closestStation = array();

    var $p_temperature = 'N/A';
    var $p_condition = 'Not Available';
    var $p_location = 'Not Available';
	var $p_tenDayURL = null;
    var $p_mainSiteURL = 'http://www.weather.gov'; //'http://www.weather.com/?cm_ven=WOWs_voap&cm_cat=Generic&cm_ite=brand&cm_pla=logo';

    function __construct()
    {
        $this->p_stationURL         = 'http://www.weather.gov/xml/current_obs/index.xml';
        $this->p_clientLatLonURL	= 'http://graphical.weather.gov/xml/SOAP_server/ndfdXMLclient.php?listZipCodeList=';
        $this->p_temperature        = 'N/A';
        $this->p_condition			= 'Not Available';
        $this->p_location			= 'Not Available';

		$this->p_tenDayURL			= 'http://www.weather.com/weather/tenday/';
        $this->p_mainSiteURL		= 'http://forecast.weather.gov/MapClick.php'; 
		
		//error_log( '== ' . microtime(true) . ' inside weather constructor', 0, '/var/log/httpd/error_log');
		
    }

    function load($postalCode = null)
    {
		//error_log( '== ' . microtime(true) . ' start weather load', 0, '/var/log/httpd/error_log');
		
        if($postalCode != null) 
        {
            $reportingStation = $this->findClosestStation($postalCode);
            
            if($weatherOutput = $this->processXML($reportingStation['url']))
            {
                $this->p_temperature = $weatherOutput->temp_f;
                $this->p_condition = $weatherOutput->weather;
                $this->p_location = $weatherOutput->location;

                $url1 = '<a href="'.$this->p_tenDayURL.$postalCode.'" target="_blank">10 Day Forecast at weather.com<span style="font-size: 9px; vertical-align: super;">&#174;</span></a>';
                $url2 = '<a href="'.$this->p_mainSiteURL.'?lat='.$this->p_clientLat.'&lon='.$this->p_clientLon.'" target="_blank">More at weather.gov</a>';

                $tempString = '<div id="temperature">'.$this->p_temperature.'&#186;</div>';
                $condString = $this->p_condition;
                $locString = $this->p_location;

                return '<div id="weather">'.$tempString.'<br />'.$condString.'<br />'.$locString.'<br /><br />'.$url2.'<br /><br />'.$url1.'</div>';
            }
            else
            {
                return 'N/A';
            }
        }
        else
        {
            throw new Exception('A Postal Code Must be Provided.');
        }
        //error_log( '== ' . microtime(true) . ' end weather load', 0, '/var/log/httpd/error_log');
    } // load()

    function findClientLatLon($postalCode = null)
    {
		//error_log( '== ' . microtime(true) . ' start find latlon', 0, '/var/log/httpd/error_log');
		
        $oClientLatLon = $this->processXML($this->p_clientLatLonURL.$postalCode);
        if($oClientLatLon->latLonList) 
        {
            $latLonArray = explode(',',$oClientLatLon->latLonList);
            $this->p_clientLat = $latLonArray[0];
            $this->p_clientLon = $latLonArray[1];
        }
        else
        {
            throw new Exception('No Lat Lon Returned from NOAA.');
        }
		
		//error_log( '== ' . microtime(true) . ' end find latlon', 0, '/var/log/httpd/error_log');
        
    } // findClientlatLon()

    function findClosestStation($postalCode = null)
    {
		//error_log( '== ' . microtime(true) . ' start find station', 0, '/var/log/httpd/error_log');
		
        $minDistance = PHP_INT_MAX;

        $this->findClientLatLon($postalCode);
        
        if($oStations = $this->processXML($this->p_stationURL))
        {
            if($oStations->station != null) 
            {
                foreach($oStations->station as $station)
                {
                    $distance = $this->getDistance($this->p_clientLat, $this->p_clientLon, $station->latitude, $station->longitude);
                    if ($distance < $minDistance)
                    {
                        $this->p_closestStation['id'] = $station->station_id;
                        $this->p_closestStation['url'] = $station->xml_url;
                        $minDistance = $distance;
                    }
                }
                return $this->p_closestStation;
            }
            else
            {
                throw new Exception('No Stations Returned from NOAA.');
            }
        }
		//error_log( '== ' . microtime(true) . ' end find station', 0, '/var/log/httpd/error_log');
    }   // findClosestStations()

    function processXML($URL)
    {
		//error_log( '== ' . microtime(true) . ' start process XML', 0, '/var/log/httpd/error_log');
        try 
        {
            $file_headers = @get_headers($URL);
            //print $URL.'<br />';
            //print $file_headers[0].'<br />';
            if($file_headers[0] != 'HTTP/1.1 404 Not Found') 
            {
                return simpleXML_load_file($URL,"SimpleXMLElement",LIBXML_NOCDATA);
            }
            else 
            {
                throw new Exception('Stations file not currently available from NOAA.');
            }
        } 
        catch (Exception $e) 
        {
            //echo "Error thrown: " . $e->getMessage();
            return false;
        }
		//error_log( '== ' . microtime(true) . ' end process XML', 0, '/var/log/httpd/error_log');
    } // processXML()

    function getDistance($lat1, $lon1, $lat2, $lon2)
    {
		//error_log( '== ' . microtime(true) . ' start get distance', 0, '/var/log/httpd/error_log');
        $radLat1 = $this->toRadians($lat1);
        $radLon1 = $this->toRadians($lon1);
        $radLat2 = $this->toRadians($lat2);
        $radLon2 = $this->toRadians($lon2);
        return acos((sin($radLat1) * sin($radLat2)) + (cos($radLat1) * cos($radLat2) * cos($radLon2 - $radLon1))) * $this->p_earthRadius; 
        //error_log( '== ' . microtime(true) . ' end get distance', 0, '/var/log/httpd/error_log');
    } // getDistance()

    function toRadians($degrees)
    {
		//error_log( '== ' . microtime(true) . ' start to radians', 0, '/var/log/httpd/error_log');
        return ($degrees * pi()) / 180;
		//error_log( '== ' . microtime(true) . ' start to radians', 0, '/var/log/httpd/error_log');
    } // toRadians()
}

$weatherZip = isset($_REQUEST['weatherZip']) ? $_REQUEST['weatherZip'] : '30101';
//print $weatherZip;
$Weather = new Weather;
print($Weather->load( $weatherZip ));
