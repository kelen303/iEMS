<?php
if(!defined('GROK')){header('HTTP/1.0 404 not found'); exit; }
//
//===========================================================================
//===========================================================================
//
/**
 * CAO
 *
 * @package IEMS 
 * @name CAO
 * @author Kevin L. Keegan, Conservation Resource Solutions, Inc.
 * @copyright 2008
 * @version 2.0
 * @access public
 */
class CAO
{
    private static $p_connection = "";
    private static $p_master_connection = "";
    private static $mdrAllocations = 0;
    private static $transactionId = 0;
    private static $qId = "select last_insert_id() ID";

    private $p_isPersistent;
    private $p_saveOnDestroy;
    private $p_isProcessed;

  /**
   * CAO::__construct()
   *
   * @return
   */
    function __construct()
    {
        if (!is_resource(CAO::$p_connection)) CAO::$mdrAllocations = 0;

        if (!CAO::$mdrAllocations++) {
            try {
               //master and slave CANNOT be the same dsn|username|password combo as 
                //the second call tries to re-use it and it doesn't do so gracefully.

                CAO::$p_connection = mysql_connect(DSN, USERNAME, PASSWORD,true) or sqlerrorhandler("(".mysql_errno().") ".mysql_error(), $query, $_SERVER['PHP_SELF'], __LINE__);
                mysql_select_db(DATABASE, CAO::$p_connection) or sqlerrorhandler("(".mysql_errno().") ".mysql_error(), $query, $_SERVER['PHP_SELF'], __LINE__);
    

                CAO::$p_master_connection = mysql_connect(DSN_M, USERNAME_M, PASSWORD_M,true) or sqlerrorhandler("(".mysql_errno().") ".mysql_error(), $query, $_SERVER['PHP_SELF'], __LINE__);
                mysql_select_db(DATABASE_M, CAO::$p_master_connection) or sqlerrorhandler("(".mysql_errno().") ".mysql_error(), $query, $_SERVER['PHP_SELF'], __LINE__);

//                CAO::$p_master_connection = mysql_connect('10.168.18.118', 'root', 'fc3582');
//                mysql_select_db('mdr', CAO::$p_master_connection);

                // MCB | KLK : second connection using same credentials and dsn info steps on previous.
                // the following can be used only if the dsn info is different that the other connection.

                /*
                CAO::$p_master_connection = mysql_connect(Connection::$master_dsn, Connection::$master_username, Connection::$master_password);
                */
                
                
//              //  mysql_select_db(Connection::$master_database, CAO::$p_master_connection);

            } catch (Exception $e) {        
                //echo 'e$:'. $e;
            }
    
            $this->InitQueries();
        }

        $this->p_saveOnDestroy = false;
        $this->p_isPersistent = false;
        $this->p_isProcessed = false;
    }

  /**
   * CAO::__destruct()
   *
   * @return
   */
    function __destruct()
    {
        /*
        if (CAO::$mdrAllocations) {
            if (!--CAO::$mdrAllocations) {
                if (is_resource(CAO::$p_connection)) mysql_close(CAO::$p_connection);
                if (is_resource(CAO::$p_master_connection)) mysql_close(CAO::$p_master_connection);
            }
        } 
        */ 
    }

    //function __clone()
    //{
    //    $this->p_saveOnDestroy = $that->p_saveOnDestroy;
    //    $this->p_isPersistent = $that->p_isPersistent;
    //    $this->p_isProcessed = $that->p_isProcessed;
    //}

  /**
   * CAO::isInTransaction()
   *
   * @return
   */
    function isInTransaction()
    {
        return $this->p_getInTransaction;
    }

  /**
   * CAO::sqlConnection()
   *
   * @return
   */
    function sqlConnection() 
    {
        return CAO::$p_connection;
    }
 
  /**
   * CAO::sqlMasterConnection()
   *
   * @return
   */
    function sqlMasterConnection() 
    {
        return CAO::$p_master_connection;
    }
 
  /**
   * CAO::isPersistent()
   *
   * @param mixed $isPersistent
   * @return
   */
    function isPersistent($isPersistent = null)
    {
        if (isset($isPersistent)) {
            $this->p_isPersistent = $isPersistent;    
        } else {
            return $this->p_isPersistent;
        }
    }

  /**
   * CAO::saveOnDestroy()
   *
   * @param mixed $saveOnDestroy
   * @return
   */
    function saveOnDestroy($saveOnDestroy = null)
    {
        if (isset($saveOnDestroy)) {
            $this->p_saveOnGetSaveOnDestroy = $saveOnDestroy;
        } else {
            return ($this->p_isPersistent && $this->p_saveOnDestroy);
        }
    }

  /**
   * CAO::isModified()
   *
   * @return
   */
    function isModified()
    {
        return $this->p_saveOnDestroy;
    }

  /**
   * CAO::isProcessed()
   *
   * @param mixed $isProcessed
   * @return
   */
    function isProcessed($isProcessed = null)
    {
        if (isset($isProcessed)) {
            $this->p_isProecessed = $isProcessed;
        } else {
            return $this->p_isProcessed;
        }
    }

  /**
   * CAO::StartTransaction()
   *
   * @return
   */
    function StartTransaction()
    {
    }

  /**
   * CAO::Commit()
   *
   * @param mixed $td
   * @return
   */
    function Commit($td)
    {
    }

  /**
   * CAO::Rollback()
   *
   * @param mixed $td
   * @return
   */
    function Rollback($td)
    {
    }

  /**
   * CAO::Get()
   *
   * @param bool $isFullGet
   * @return
   */
    function Get($isFullGet=false)
    {
        $this->p_saveOnDestroy = false;
        $this->p_isProcessed = false;
    }

  /**
   * CAO::Put()
   *
   * @param bool $isFullPut
   * @return
   */
    function Put($isFullPut=false)
    {
        $this->p_saveOnDestroy = false;
        $this->p_isProcessed = false;
    }

  /**
   * CAO::Delete()
   *
   * @return
   */
    function Delete()
    {
        $this->p_saveOnDestroy = false;
        $this->p_isProcessed = false;
    }

  /**
   * CAO::Bind()
   *
   * @param mixed $query
   * @return
   */
    protected function Bind($query)
    {
    }

  /**
   * CAO::BindOut()
   *
   * @param mixed $query
   * @return
   */
    protected function BindOut($query)
    {
    }

  /**
   * CAO::CloseQuery()
   *
   * @param mixed $query
   * @return
   */
    protected function CloseQuery($query)
    {
    }

  /**
   * CAO::Copy()
   *
   * @param mixed $cao
   * @return
   */
    protected function Copy($cao)
    {
    }

  /**
   * CAO::GetId()
   *
   * @return
   */
    protected function GetId()
    {
        $result = mysql_query(CAO::$qId, CAO::$p_connection);
        $row = mysql_fetch_array($result);

        return $row["ID"];
    }

  /**
   * CAO::getInTransaction()
   *
   * @return
   */
    private function getInTransaction() 
    {
        return $this->inTransaction;
    }

  /**
   * CAO::InitQueries()
   *
   * @return
   */
    private function InitQueries()
    {
    } 

        /**
   * CAO::preDebugger()
   *
   * @return
   */

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
    FUNCTION : processQuery()
    =============================================================================== */

    function processQuery($sql,$connection,$type)  //tempting to not send in connection -- remember that we use two connections, one for master replica.
    {   
        //$this->preDebugger($sql);
        $result = mysql_query($sql,$connection);

        if(!$result) 
        {
            $errno = mysql_errno($connection);
            $error = mysql_error($connection);
        
            return array(
                'error'=>true,
                'message'=>"Database Error ($errno): $error<br />$sql");
        }
        else
        {
            $records = mysql_affected_rows($connection);
            /*
            $this->preDebugger($records,'pink');      
            $this->preDebugger($result,'cyan');      
            */
            
            switch ($type) {
                case 'update':
                    $return = '';
                    break;
                case 'select':
                    $return = array();                      
                    while($row = mysql_fetch_object($result)) { $return[] = $row; }
                    break;
                case 'insert':
                    $return = mysql_insert_id($connection);
                    break;
                case 'delete':
                    $return = '';
                    break;
            }
            
            return array(
                'error'=>false,
                'message'=>"Number of records involved: $records",
                'records'=>$records,
                'items'=>$return);  //to manage this, use is_array() . . . 
        }
    }
}
?>
