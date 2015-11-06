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

    function __construct()
    {
        $this->p_stationURL       = 'http://www.weather.gov/xml/current_obs/index.xml';
        $this->p_clientLatLonURL  = 'http://graphical.weather.gov/xml/SOAP_server/ndfdXMLclient.php?listZipCodeList=';
    }

    function load($postalCode = null)
    {
        if($postalCode != null) 
        {
            $reportingStation = $this->findClosestStation($postalCode);
            if($this->processXML($reportingStation['url']))
            {
                return '<div id="weather"><div id="temperature">'.$temperature.'&#186;</div>'.$condition.'<br />'.$location.'<br /><br /><br /><a href="'.$tenDayURL.'" target="_blank">10 Day Forecast</a><br /><a href="'.$mainSiteURL.'" target="_blank">weather.com<span style="font-size: 9px; vertical-align: super;">&#174;</span></a><br /><br /><br /></div>';
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
        
    } // load()

    function findClientLatLon($postalCode = null)
    {
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
        
    } // findClientlatLon()

    function findClosestStation($postalCode = null)
    {
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
    }   // findClosestStations()

    function processXML($URL)
    {
        try 
        {
            $file_headers = @get_headers($URL);
        
            if($file_headers[0] != 'HTTP/1.1 404 Not Found') 
            {
                return simpleXML_load_file($URL,"SimpleXMLElement",LIBXML_NOCDATA);
            }
            else 
            {
                throw new Exception('Stations file not currently available from NOAA.');
                return false;
            }
        } 
        catch (Exception $e) 
        {
            echo "Error thrown: " . $e->getMessage();
            return false;
        }
    } // processXML()

    function getDistance($lat1, $lon1, $lat2, $lon2)
    {
        $radLat1 = $this->toRadians($lat1);
        $radLon1 = $this->toRadians($lon1);
        $radLat2 = $this->toRadians($lat2);
        $radLon2 = $this->toRadians($lon2);
        return acos((sin($radLat1) * sin($radLat2)) + (cos($radLat1) * cos($radLat2) * cos($radLon2 - $radLon1))) * $this->p_earthRadius; 
         
    } // getDistance()

    function toRadians($degrees)
    {
        return ($degrees * pi()) / 180;
    } // toRadians()
}

$Weather = new Weather;
$Weather->load('30076');

/*
$zipcode = '30076';
$baseURL = 'http://www.weather.gov/xml/current_obs/index.xml';


*/
/*if($oStations = simpleXML_load_file($baseURL,"SimpleXMLElement",LIBXML_NOCDATA))
{
  print '<pre>';
  foreach($oStations->station as $station)
  {
    print_r($station);
  }
  print '</pre>';
}
else
{
  print 'There was an error retrieving the station list';
}*/


/* 
GET DISTANCE STUFF 
 
 /// <summary>
        /// A brute force great-circle computation to find the closest NWS observation
        /// station.
        /// </summary>
        /// <param name="longitude">longitude for evaluation</param>
        /// <param name="latitude">latitude for evaluation</param>
        /// <param name="distance">distance to closest station</param>
        /// <returns>closest observation station</returns>
        public static ObservationStation GetClosest(decimal longitude, decimal latitude, out double minDistance)
        {
            ObservationStation closestStation = null;
            minDistance = double.MaxValue;
            IList<ObservationStation> stations;

            string status;
            if (GetStations(out stations, out status) && stations != null)
            {
                foreach (ObservationStation station in stations)
                {
                    double distance = GetDistance((double)latitude, (double)longitude, (double)station.Latitude, (double)station.Longitude);

                    if (distance < minDistance)
                    {
                        closestStation = station;
                        minDistance = distance;
                    }
                }
            }

            return closestStation;
        }
 
 
double distance = GetDistance((double)latitude, (double)longitude, (double)station.Latitude, (double)station.Longitude); 
/// <summary>
/// A quick and dirty great-circle distance computation using the
/// spherical law of cosines. You can find a nice description here:
/// http://www.movable-type.co.uk/scripts/latlong.html
/// </summary>
/// <param name="lat1">point1 latitude</param>
/// <param name="lon1">point1 longitude</param>
/// <param name="lat2">point2 latitude</param>
/// <param name="lon2">point2 longitude</param>
/// <returns>distance in km.</returns>
private static double GetDistance(double lat1, double lon1, double lat2, double lon2)
{
    double radLat1 = ToRadians(lat1);
    double radLon1 = ToRadians(lon1);
    double radLat2 = ToRadians(lat2);
    double radLon2 = ToRadians(lon2);
    return Math.Acos((Math.Sin(radLat1) * Math.Sin(radLat2)) + 
                     (Math.Cos(radLat1) * Math.Cos(radLat2) * 
                      Math.Cos(radLon2 - radLon1))) * EarthRadius;
} 
 
 private static double ToRadians(double degrees)
        {
            return (degrees * Math.PI) / 180;
        }
 
*/ 









/* noaa-weather.php by detour@metalshell.com
 *
 * Simple PHP5 class to retrieve a list of weather stations for a state, and
 * specific data for a selected station.  Only supports US weather.
 *
 * http://www.metalshell.com/
 *
 */
 /*
// Change to get a list of stations in your state
define('STATION_LIST_STATE', 'FL');
// Copy and paste station XML URL for your closest location
define('WEATHER_STATION', 'http://weather.gov/xml/current_obs/KRSW.xml');
 
try {
  $weather = new weather();
 
  echo "Getting weather stations for " . STATION_LIST_STATE . "\n---------------------\n";
  $weather->show_weather_stations(STATION_LIST_STATE);
 
  $station_obs = $weather->get_weather_data(WEATHER_STATION);
  echo "\n\nGetting current weather observations\n---------------------\n";
 
  foreach($station_obs as $k=>$v) {
    echo "$k = $v\n";
  }
} catch (Exception $e) {
 
  echo "Error thrown: " . $e->getMessage();
 
}
 
class weather {
  private $xml_parser, $state, $current_element, $element_depth = 0;
  private $matched_stations = array(), $station_details = array();
  private $station_obs = array(), $parent_elements = array();
 
  // Store some information so we know the current element, and depth.
  private function start_element($parser, $name, $attribs) {
    $this->current_element = $name;
    $this->element_depth++;
    $this->parent_elements[$this->element_depth] = $name;
  }
 
  private function end_element($parser, $name) {
    $this->current_element = '';
    $this->element_depth--;
  }
 
  // If parent xml element is STATION, store the data
  private function station_parse($parser, $data) {
    if($this->current_element) {
      $parent_el = strtolower($this->parent_elements[$this->element_depth-1]);
      if($parent_el == 'station') {
        $this->station_details[$this->current_element] = $data;
      } else if($parent_el == 'wx_station_index' && count($this->station_details)) {
        $this->matched_stations[] = $this->station_details;
      }
    }
  }
 
  function __construct() {
  }
 
  function __destruct() {
    if(is_resource($this->xml_parser)) xml_parser_free($this->xml_parser);
  }
 
  // Parser must be cleared and recreated
  private function create_parser() {
    if(is_resource($this->xml_parser)) xml_parser_free($this->xml_parser);
 
    $this->xml_parser = xml_parser_create();
    xml_set_object($this->xml_parser, $this);
  }
 
  // Get current local conditions.
  public function get_weather_data($station_url) {
    $this->create_parser();
 
    xml_set_element_handler($this->xml_parser, "start_element", "end_element");
    xml_set_character_data_handler($this->xml_parser, "current_obs_data");
    $this->start_xml_parse($this->dl_xml($station_url));
 
    return $this->station_obs;
  }
 
  private function current_obs_data($parser, $data) {
    if($this->current_element) {
      $parent_el = strtolower($this->parent_elements[$this->element_depth-1]);
      if($parent_el == 'current_observation' && $this->current_element != 'IMAGE') {
        $this->station_obs[$this->current_element] = $data;
      }
    }
  }
 
  // Accepts 2 letter state, then echo's all stations in that state
  public function show_weather_stations($state) {
    if(strlen($state) != 2) throw new Exception("State should be 2 letter abbreviation.  i.e. FL, CA");
 
    $this->state = $state;
 
    $this->create_parser();
    xml_set_element_handler($this->xml_parser, "start_element", "end_element");
    xml_set_character_data_handler($this->xml_parser, "station_parse");
    $this->start_xml_parse($this->dl_xml('http://www.weather.gov/xml/current_obs/index.xml'));
 
    if(count($this->matched_stations)) {
      foreach($this->matched_stations as $k=>$detail) {
        if(strtolower($detail["STATE"]) == strtolower($this->state)) {
          echo "{$this->state} Station: {$detail["STATION_ID"]} ({$detail["STATION_NAME"]}) {$detail["XML_URL"]}\n";
        }
      }
    } else {
      throw new Exception("Unable to retrieve station list.");
    }
  }
 
  private function start_xml_parse($data) {
    if(!xml_parse($this->xml_parser, $data, true))
      throw new Exception(sprintf("XML error: %s at line %d",
        xml_error_string(xml_get_error_code($this->xml_parser)),
        xml_get_current_line_number($this->xml_parser)));
  }
 
  // D/L and return xml data.
  public function dl_xml($url) {
    $buf = '';
 
    $fd = fopen($url, "r");
    if(!$fd) throw new Exception("Unable to open '$url'");
 
    while(!feof($fd)) {
      $buf .= fgets($fd, 4096);
    }
 
    fclose($fd);
 
    return $buf;
  }
}

*/
