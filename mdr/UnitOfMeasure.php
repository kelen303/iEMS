<?php
if(!defined('GROK')){header('HTTP/1.0 404 not found'); exit; }
//
//===========================================================================
//
/**
 * UnitOfMeasure
 *
 * @package IEMS 
 * @name Unit of Measure
 * @author Kevin L. Keegan, Conservation Resource Solutions, Inc.
 * @copyright 2008
 * @version 2.0
 * @access public
 */
class UnitOfMeasure extends CAO
{
    private $p_unitOfMeasureId;
    private $p_unitOfMeasureName;
    private $p_unitOfMeasureDescription;
    private $p_unitOfMeasureSymbol;
    
  /**
   * UnitOfMeasure::unitOfMeasureId()
   *
   * @return
   */
    function unitOfMeasureId()
    {
        return $this->p_unitOfMeasureId;
    }
    
  /**
   * UnitOfMeasure::unitOfMeasureName()
   *
   * @return
   */
    function unitOfMeasureName()
    {
        return $this->p_unitOfMeasureName;
    }
    
  /**
   * UnitOfMeasure::unitOfMeasureDescription()
   *
   * @return
   */
    function unitOfMeasureDescription()
    {
        return $this->p_unitOfMeasureDescription;
    }
    
  /**
   * UnitOfMeasure::unitOfMeasureSymbol()
   *
   * @return
   */
    function unitOfMeasureSymbol()
    {
        return $this->p_unitOfMeasureSymbol;
    }
    
  /**
   * UnitOfMeasure::__construct()
   *
   * @return
   */
    function __construct()
    {
        parent::__construct();

        $this->p_unitOfMeasureId = 0;
        $this->p_unitOfMeasureName = "";
        $this->p_unitOfMeasureDescription = "";
        $this->p_unitOfMeasureSymbol = "";
    }

    function __destruct()
    {
        parent::__destruct();
    }
    
  /**
   * UnitOfMeasure::Load()
   *
   * @param mixed $unitOfMeasureId
   * @return
   */
    function Load($unitOfMeasureId)
    {
        $this->p_unitOfMeasureId = $unitOfMeasureId;
        
        $this->Refresh();
    }
    
  /**
   * UnitOfMeasure::Refresh()
   *
   * @return
   */
    function Refresh()
    {    
        $sql = "select " .
                 "UnitOfMeasureName, " .
                 "UnitOfMeasureDescription, " .
                 "UnitOfMeasureSymbol " .
               "from " .
                 "t_unitsofmeasure " .
               "where " .
                 "UnitOfMeasureID = {$this->p_unitOfMeasureId}";
                
        $result = mysql_query($sql, $this->sqlConnection());
        if ($row = mysql_fetch_array($result)) {
            $this->p_unitOfMeasureName = $row["UnitOfMeasureName"];
            $this->p_unitOfMeasureDescription = $row["UnitOfMeasureDescription"];
            $this->p_unitOfMeasureSymbol = $row["UnitOfMeasureSymbol"];
        }
    }
}
?>
