<?php
if(!defined('GROK')){header('HTTP/1.0 404 not found'); exit; }
//
//===========================================================================
//
/**
 * PointChannel
 *
 * @package IEMS 
 * @name Point Channel
 * @author Kevin L. Keegan, Conservation Resource Solutions, Inc.
 * @copyright 2008
 * @version 2.0
 * @access public
 */
class PointChannel extends CAO
{
    private $p_objectId;
    private $p_channelId;
    private $p_channelName;
    private $p_channelDescription;
    private $p_unitOfMeasure;
    private $p_pulseConversionFactor;
    private $p_drawLimit;
    private $p_drawLimitUnitOfMeasure;
    private $p_isGenerator;
    private $p_assetIdentifier;
    private $p_retirementDate;

    private $p_meterPoint;

    private $p_firstIntervalDate;
    private $p_lastIntervalDate;

    private $p_participationTypeID;
    private $p_participationTypeDescription;
    private $p_committedReduction;

  /**
   * PointChannel::objectId()
   *
   * @param mixed $index
   * @return
   */
    function objectId()
    {
        return $this->p_objectId;
    }
    
  /**
   * PointChannel::channelId
   *
   * @param mixed $index
   * @return
   */
    function channelId()
    {
        return $this->p_channelId;
    }

    
  /**
   * PointChannel::assetIdentifier
   *
   * @param mixed $index
   * @return
   */
    function assetIdentifier()
    {
        return $this->p_assetIdentifier;
    }

  /**
   * PointChannel::retirementDate
   *
   * @param mixed $index
   * @return
   */
    function retirementDate()
    {
        return $this->p_retirementDate;
    }
        
  /**
   * PointChannel::channelName()
   *
   * @param mixed $index
   * @return
   */
    function channelName()
    {
        return $this->p_channelName;
    }
    
  /**
   * PointChannel::channelDescription()
   *
   * @param mixed $index
   * @return
   */
    function channelDescription()
    {
        return $this->p_channelDescription;
    }
    
  /**
   * PointChannel::units()
   *
   * @param mixed $index
   * @return
   */
    function units() 
    {
        return $this->p_unitOfMeasure;
    }
    
  /**
   * PointChannel::pulseConversionFactor()
   *
   * @param mixed $index
   * @return
   */
    function pulseConversionFactor()
    {
        return $this->p_pulseConversionFactor;
    }
    
  /**
   * PointChannel::drawLimit()
   *
   * @param mixed $index
   * @return
   */
    function drawLimit() 
    {
        return $this->p_drawLimit;
    }
    
  /**
   * PointChannel::drawLimitUnitOfMeasure()
   *
   * @param mixed $index
   * @return
   */
    function drawLimitUnitOfMeasure()
    {
        return $this->p_drawLimitUnitOfMeasure;
    }

  /**
   * PointChannel::isGenerator()
   *
   * @param mixed $index
   * @return
   */
    function isGenerator()
    {
        return $this->p_isGenerator;
    }

  /**
   * PointChannel::meterPoint()
   *
   * @param object $meterPoint
   * @return
   */
    function meterPoint($meterPoint = null) 
    {
        if (!isset($meterPoint)) {
            return $this->p_meterPoint;
        } else {
            // Do Not Clone -- We WANT a Reference Here...
            $this->p_meterPoint = $meterPoint;
        } 
    }

  /**
   * PointChannel::firstIntervalDate()
   *
   * @param object $meterPoint
   * @return
   */
    function firstIntervalDate()
    {
        return $this->p_firstIntervalDate;
    }

/**
   * PointChannel::participationTypeID()
   *
   * @param object $meterPoint
   * @return
   */
    function participationTypeID()
    {
        return $this->p_participationTypeID;
    }

/**
   * PointChannel::participationTypeDescription()
   *
   * @param object $meterPoint
   * @return
   */
    function participationTypeDescription()
    {
        return $this->p_participationTypeDescription;
    }

/**
   * PointChannel::committedReduction()
   *
   * @param object $meterPoint
   * @return
   */
    function committedReduction()
    {
        return $this->p_committedReduction;
    }

  /**
   * PointChannel::lastItervalDate()
   *
   * @param object $meterPoint
   * @return
   */
    function lastIntervalDate()
    {
        return $this->p_lastIntervalDate;
    }

    function __construct($objectId, 
                         $channelId, 
                         $channelName, 
                         $channelDescription,
                         $unitOfMeasure, 
                         $pulseConversionFactor, 
                         $drawLimit,
                         $drawLimitUnitOfMeasure, 
                         $isGenerator,
                         $assetIdentifier,
                         $retirementDate,
                         $participationTypeID,
                         $participationTypeDescription,
                         $committedReduction)
    {
        parent::__construct();

        $this->p_objectId = $objectId;
        $this->p_channelId = $channelId;
        $this->p_channelName = $channelName;
        $this->p_channelDescription = $channelDescription;
        $this->p_unitOfMeasure = clone $unitOfMeasure;
        $this->p_pulseConversionFactor = $pulseConversionFactor;
        $this->p_drawLimit = $drawLimit;
        $this->p_drawLimitUnitOfMeasure = clone $drawLimitUnitOfMeasure;
        $this->p_isGenerator = $isGenerator;
        $this->p_assetIdentifier = $assetIdentifier;
        $this->p_retirementDate = $retirementDate;
        $this->p_participationTypeID = $participationTypeID;
        $this->p_participationTypeDescription = $participationTypeDescription;
        $this->p_committedReduction = $committedReduction;
        
    }

    function __destruct()
    {
        parent::__destruct();
    }

    function RefreshStats()
    {
        $sql = "select " .
                "FirstIntervalDate, " .
                "LastUnfilledIntervalDate " .
               "from " .
                "t_pointchannelstatistics " .
               "where " .
                "ObjectID = " . $this->p_objectId . " and " .
                "ChannelID = " . $this->p_channelId;

        //echo "PointChannel->RefreshStats: this->sqlConnection='" . $this->sqlConnection() . "'<br>\n";

        $result = mysql_query($sql, $this->sqlConnection());
        if ($row = mysql_fetch_array($result)) {
            $this->p_firstIntervalDate = new CrsDate($row["FirstIntervalDate"]);
            $this->p_lastIntervalDate = new CrsDate($row["LastUnfilledIntervalDate"]);
        } else {
            $this->p_firstIntervalDate = new CrsDate(gmdate());
            $this->p_lastIntervalDate = new CrsDate(gmdate());
        }

        mysql_free_result($result);
    }
}
?>
