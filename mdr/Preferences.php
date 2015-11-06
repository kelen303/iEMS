<?php
/** Marian says: ========================================================= 
    expressions beginning 'return array('error'=>' are not returned to
    client. They can be viewed using print_r() from the calling document.
    ====================================================================== */ 

if(!defined('GROK')){header('HTTP/1.0 404 not found'); exit; }
//
//===========================================================================
//
class Preferences extends CAO
{
    private $p_userId;

    private $p_maxPreference;
    
    private $p_preferenceList = array();

   /**
   * Preferences::userId()
   *
   * @return
   */
    function userId()
    {
        $this->p_userId;
    }

  /**
   * Preferences::count()
   *
   * @return
   */
    function count()
    {
        return $this->p_maxPrivilege;
    }
    
  /**
   * Preferences::__construct()
   *
   * @return
   */
    function __construct()
    {
        parent::__construct();
        
        $this->p_userId = -1;
        $this->p_maxPreference = 0;
    }        

    function __destruct()
    {
        parent::__destruct();
    }

  /**
   * Preferences::Load()
   *
   * @param mixed $userId
   * @return
   */
    function Load($userId)
    {
        if ($this->p_userId < 0) {
            $this->p_userId = $userId;
            
            $this->Refresh();
        }
    }

    function Refresh()
    {
        $sql = "select " .
                "up.UserPreferenceID, " .
                "lower(PreferenceTypeName) PreferenceName " .
               "from " .
                "t_userpreferences up, " .
                "t_preferencetypes pt " .
               "where " .
                "up.UserObjectID = " . $this->p_userId . " and " .
                "pt.PreferenceTypeID = up.PreferenceTypeID " .

               "union " .

               "select " .
                "up.UserPreferenceID, " .
                "lower(concat(PreferenceTypeName, \".\", ChannelName)) PreferenceName " .
               "from " .
                "t_userpreferences up, " .
                "t_preferencetypes pt, " .
                "t_pointchannels pc, " .
                "t_pointchannelpreferences pcp " .
               "where " .
                "up.UserObjectID = " . $this->p_userId . " and " .
                "pt.PreferenceTypeID = up.PreferenceTypeID and " .
                "pcp.UserPreferenceID = up.UserPreferenceID and " .
                "pc.ObjectID = pcp.PointObjectID and " .
                "pc.ChannelID = pcp.ChannelID and " .
                "pc.IsEnabled = 1 " .
               "order by " .
                 "PreferenceName";

        //echo "sql = " . $sql . "<br>\n";

        $result = mysql_query($sql, $this->sqlConnection());
        while ($row = mysql_fetch_array($result)) {
            $this->p_preferenceList[$this->p_maxPreference++] = $row["PreferenceName"];
            //echo "Have preference '", $this->p_preferenceList[$this->p_maxPreference-1], "'...<br>\n";
        }

        sort($this->p_preferenceList, SORT_STRING);
    }

  /**
   * Preferences::HasPreference()
   *
   * @param mixed $privilege
   * @return
   */
    function HasPreference($preference)
    {
        $hasPreference = false;
        $testPreference = strtolower($preference);

        for ($inx=0; $inx<$this->p_maxPreference; $inx++) {
            if ($testPreference == $this->p_preferenceList[$inx]) {
                $hasPreference = true;

                break;
            } elseif ($this->p_preferenceList[$inx] > $testPreference) {
                break;
            }
        }

        return $hasPreference;
    }

  /**
   * Preferences::DumpPreferences()
   *
   * @param mixed
   * @return
   */
    function Dump()
    {
        $dump = '<table><thead><th><td colspan="2">Preferences</td></th><th><td>ID</td><td>Name</td></th></thead><tbody>';
        for ($inx=0; $inx<$this->p_maxPreference; $inx++) {
            $dump .= "<tr><td>{$inx}</td><td>{$this->p_preferenceList[$inx]}</td></tr>";
        }

        $dump .= "</tbody></table>";

        return $dump;
    }

  /**
   * Preferences::Insert()
   *
   * @param mixed $preference
   * @return
   */
    private function Insert($preference)
    {    
        for ($inx1=0; $inx<$this->p_maxPreference; $inx++) {
            if ($this->p_preferenceList[$inx1] > $privilege) {
                for ($inx2=$this->p_maxPreference-1; $inx2>$inx; $inx2--) {
                    $this->p_preferenceList[$inx2 + 1] = $this->p_preferenceList[$inx2];
                }
                
                $this->p_preferenceList[$inx1] = $preference;
            }
        }
    }
    
  /**
   * Preferences::item()
   *
   * @param mixed $index
   * @return
   */
    function item($index)
    {
        $this->p_preferenceList[$index];
    }
    
  /**
   * Privileges::Size()
   *
   * @return
   */
    function Size()
    {
        return $this->p_maxPreference;
    }

  /**
   * Privileges::udpatePostalCode()
   *
   * @return
   */
    function updatePostalCode($newCode,$userId)
    {
        
        $sql = '
            UPDATE
                t_contacts,
                t_contacttypes
            SET
                t_contacts.ContactValue = "'.$newCode.'"
            WHERE
                t_contacts.ObjectID = '.$userId.' and
                t_contacts.ContactTypeID = t_contacttypes.ContactTypeID and
                t_contacttypes.ContactTypeName = "c_address"
        ';

        $result = mysql_query($sql,$this->sqlMasterConnection());

        
        if (!$result) {
        	$errno = mysql_errno($this->sqlMasterConnection());
        	$error = mysql_error($this->sqlMasterConnection());
        
        	return array('error'=>true,'message'=>"Database Error ($errno): $error",'value'=>'');
        }
        else
        {   
            $affected = mysql_affected_rows($this->sqlMasterConnection());
            return array('error'=>false,'message'=>"Number of records modified: $affected",'value'=>$newCode);

        }
        
    }

  /**
   * Privileges::udpatePassword()
   *
   * @return
   */
    function updatePassword($oldPassword,$newPassword,$userId)
    {
        
        $checkSql = '
            SELECT * 
			FROM
				t_users
			WHERE ObjectID = '.$userId
        ;

        $check = mysql_query($checkSql,$this->sqlMasterConnection());

        if (!$check) {

        	$errno = mysql_errno($this->sqlMasterConnection());
        	$error = mysql_error($this->sqlMasterConnection());
        
        	return array('error'=>true,'message'=>"Database Error ($errno): $error",'value'=>'');
        }
        else
        {   
            $secCheck = mysql_fetch_object($check);
             /*   print '<pre>';
                print $secCheck->Password;
                //print_r($secCheck);
                print '</pre>';*/

            if(strtolower($oldPassword) == strtolower($secCheck->Password))
            {
                $pwSQL = '
    				UPDATE 
    					t_users 
    				SET
    					Password = "'.$newPassword.'"
    				WHERE 
    					ObjectID = '.$userId;

    			$result = mysql_query($pwSQL, $this->sqlMasterConnection());

                if(!$result) 
                {
                    $errno = mysql_errno($this->sqlMasterConnection());
                	$error = mysql_error($this->sqlMasterConnection());
                
                	return array('error'=>true,'message'=>"Database Error ($errno): $error",'value'=>'');
                }
                else
                {
                    $affected = mysql_affected_rows($this->sqlMasterConnection());
                    return array('error'=>false,'message'=>"Number of records modified: $affected",'value'=>'');
                }
            }
            else
            {
                return array('error'=>true,'message'=>"Password mismatch error.",'value'=>'');
            }
        }
        
    }

/*  ===============================================================================
    FUNCTION : addDefaultMeter()
    =============================================================================== */ 
    function addDefaultMeter($pointSet,$defaultId,$connection)
    {
        foreach($pointSet as $point=>$value)
        {
            $meter = explode(':',$point);

            $sql = '
                INSERT INTO
                    t_pointchannelpreferences
                    (   UserPreferenceID, 
                        PointObjectID, 
                        ChannelID   ) 
                VALUES
                    (' . 
                        $defaultId . ', ' . 
                        $meter[0] . ', ' . 
                        $meter[1] . ')'
                ;

            $query = $this->processQuery($sql,$connection,'insert') ;
            if($query['error']) {
                $result['failures'][] = $value;

                print_r($query);
            }
            else
            {
                $result['successes'][] = $value;
            }
        }
        print $result;
    }

/*  ===============================================================================
    FUNCTION : hideRetired()
    =============================================================================== */
    function hideRetired($userId)
    {
        $sql = '
            SELECT
                pt.PreferenceTypeID
            FROM
                t_preferencetypes pt
            WHERE
                pt.PreferenceTypeName="HideRetiredPointChannels"
            LIMIT 1';
        
        $query = $this->processQuery($sql,$this->sqlMasterConnection(),'select') ;
        if($query['error']) 
        {
            header("HTTP/1.1 500");
        }
        else
        {
            $sql2 = '
                INSERT INTO
                    t_userpreferences
                VALUES
    				("",
    				'.$query['items'][0]->PreferenceTypeID.',
                    '.$userId.',
                    NOW(),
    				0)
            ';
            
            $query2 = $this->processQuery($sql2,$this->sqlMasterConnection(),'insert');
            if($query2['error']) 
            {
                header("HTTP/1.1 500");
            }
            else
            {
                return true;
            }
        }
        
    } // hideRetired

/*  ===============================================================================
    FUNCTION : showRetired()
    =============================================================================== */
    function showRetired($userId)
    {
        $sql = '
            SELECT
                up.UserPreferenceID
            FROM
                t_preferencetypes pt,
                t_userpreferences up
            WHERE
                pt.PreferenceTypeName="HideRetiredPointChannels"
                and pt.PreferenceTypeID = up.PreferenceTypeID
                and up.UserObjectID = '.$userId.'
                LIMIT 1';
        
        $query = $this->processQuery($sql,$this->sqlMasterConnection(),'select') ;
        
        if($query['error']) 
        {
            header("HTTP/1.1 500");
        }
        else
        {
            $sql2 = '
                DELETE FROM
                    t_userpreferences
                WHERE
                    UserPreferenceID = '.$query['items'][0]->UserPreferenceID;
            ;
            
            $query2 = $this->processQuery($sql2,$this->sqlMasterConnection(),'delete');
            if($query2['error']) 
            {
                header("HTTP/1.1 500");
            }
            else
            {
                return true;
            }
        }
    } // showRetired
}

/* 
if($_POST['defaultChartPref'] == $defaultChartType && $userPrefID['AlternateChartPresentation'] != '')
	{
        $deleteSQL = '
            DELETE FROM
                t_userpreferences
            WHERE
				UserPreferenceID = '.$userPrefID['AlternateChartPresentation']
        ;
		 mysql_query($deleteSQL, $master_connection);
		$userPrefID['AlternateChartPresentation'] = '';
		$message = '<div><div class="error" style="width: 500px;">Your preference has been saved.<br />To apply the changes to your Control Panel, click the Apply to Control Panel button:<br /><br /><a href="index.php?action=refresh" class="defaultButton" style="padding: 3px;">Apply to Control Panel</a></div></div>';
	}

	if($_POST['defaultChartPref'] != $defaultChartType && $userPrefID['AlternateChartPresentation'] == '')
	{
		$insertSQL = '
            INSERT INTO
                t_userpreferences
            VALUES
    				("",
    				'.$systemPrefID['AlternateChartPresentation'].',
                    '.$mdrUser->id().',
                    NOW(),
    				0)
        ';

        mysql_query($insertSQL, $master_connection);
		$userPrefID['AlternateChartPresentation'] = mysql_insert_id();
		$message = '<div><div class="error" style="width: 500px;">Your preference has been saved.<br />To apply the changes to your Control Panel, click the Apply to Control Panel button:<br /><br /><a href="index.php?action=refresh" class="defaultButton" style="padding: 3px;">Apply to Control Panel</a></div></div>';
	} 
*/
?>
