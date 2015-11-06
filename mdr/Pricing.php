<?php
if(!defined('GROK')){header('HTTP/1.0 404 not found'); exit; }
//
//===========================================================================
//
/**
 * Pricing
 *
 * @package IEMS 
 * @name Pricing
 * @author Kevin L. Keegan, Conservation Resource Solutions, Inc.
 * @copyright 2008
 * @version 2.0
 * @access public
 */
class Pricing extends CAO
{
	private $p_id;
    private $p_intervalPrices;

  /**
   * Pricing::id()
   *
   * @return
   */
	function id()
	{
		return $this->p_id;
	}

    function __construct()
    {
        parent::__construct();
    }

    function __destruct()
    {
        parent::__destruct();
    }

   /**
   * Pricing::intervals()
   *
   * @param mixed $pointID
   * @return
   */

    function intervalPrices($priceId,$priceStartDate,$priceEndDate)
    {
        $sql = '
            SELECT * 
            FROM 
                t_priceintervals 
            WHERE 
                PriceID = '.$priceId.' and 
                IntervalDate between "'.$priceStartDate.'" and "'.$priceEndDate.'"
        ';

        //echo "sql='" . $sql . "'<br>\n";
            
        $result = mysql_query($sql, $this->sqlConnection());
        
        if(mysql_numrows($result) != 0)
        {
            while ($row = mysql_fetch_array($result,MYSQL_ASSOC))
            {
                $this->p_intervalPrices[strtotime($row['IntervalDate'])]= $row['IntervalValue'];
            }	
            
        }
        return $this->p_intervalPrices;
    }

			
  /**
   * Pricing::Refresh()
   *
   * @param mixed $pointID
   * @return
   */
/*
	function Refresh($pointID)
    {    	
        $sql = "select " .
	         "o.ObjectID, " .
	         "o.ObjectName, " .
	         "o.ObjectDescription, " .
	         "MaximumChannelID, " .
	         "o.IsInactive, " .
	         "AssetIdentifier, " .
	         "SerialNumber, " .
	         "TimeZoneID, " .
	         "ReadTimeOffset, " .
	         "ReadInterval, " .
	         "IsGenerator, " .
	         "IsAggregate, " .
	         "IsChecked, " .
	         "IsAutoEventGenerated, " .
	         "IsDemo, " .
	         "p.IsEnabled, " .
	         "po.ObjectDescription Program, " .
	         "pt.ParticipationTypeDescription, " .
	         "pppp.CommittedReduction, " .
	         "zo.ObjectDescription Zone, " .
	         "z.DisplayPriceID, " .
	         "z.SettlementPriceID, " . 
	         "pd.PriceDescription DisplayPriceDescription, " .
	         "ps.PriceDescription SettlementPriceDescription, " .
	         "p.CreatedBy, " .
	         "p.UpdatedBy " .
	         "from " .
	             "t_objects o, " .
	             "t_points p, " .
	             "t_pointprogramparticipationprofiles pppp, " .
	             "t_objects po, " .
	             "t_participationtypes pt, " .
	             "t_objectxrefs ox, " .
	             "t_groups g, " .
	             "t_grouptypes gt, " .
	             "t_objects zo, " .
	             "t_zones z, " .
	             "t_prices pd, " .
	             "t_prices ps " .
	         "where " .
	             "o.ObjectID = {$this->p_id} and " .
	             "p.ObjectID = o.ObjectID and " .
	             "pppp.PointObjectID = p.ObjectID and " .
	             "po.ObjectID = pppp.ProgramObjectID and " .
	             "pt.ParticipationTypeID = pppp.ParticipationTypeID and " .
	             "ox.ChildObjectID = pppp.PointObjectID and " .
	             "g.ObjectID = ox.ParentObjectID and " .
	             "gt.GroupTypeName = 'Zone' and " .
	             "g.GroupTypeID = gt.GroupTypeID and " .
	             "zo.ObjectID = g.ObjectID and " .
	             "z.ObjectID = zo.ObjectID and " .
	             "pd.PriceID = z.DisplayPriceID and " .
	             "ps.PriceID = z.SettlementPriceID";
    
        //echo "sql='{$sql}...<br>\n";
        $result = mysql_query($sql, $this->sqlConnection());
    }
*/
}
?>
