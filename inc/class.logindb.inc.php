<?php

class logindb
{
    //Private Variables
    public $Select_Result; //Hold the Status of connection
    public $Select_Rows;  //Hold the Active Connection
    //Public Variables
    public $Query;
    public $Insert_ID;
    private $Status;
    private $Con;

    function __construct()
    {
        if (file_exists("../global/config.ini")) {
            $config = parse_ini_file("../global/config.ini");
        } else {
            $config = parse_ini_file("global/config.ini");
        }
        if (!$this->Status) {
            $this->Con = mysqli_connect($config['DBserver'], $config['DBuser'], $config['DBpass'], $config['DBname']);
            if ($this->Con) {
                $this->Status = true;
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    function __destruct()
    {
        if ($this->Status) {
            if (mysqli_close($this->Con)) {
                $this->Status = false;
            }
        }
    }

    public function Select($Table, $Rows = '*', $Where = null, $Order = null)
    {
        $Query = 'SELECT ' . $Rows . ' FROM ' . $Table;
        if ($Where != null) $Query .= ' WHERE ' . $Where;
        if ($Order != null) $Query .= ' ORDER BY ' . $Order;
        $this->Query = $Query;
        $Result = mysqli_query($this->Con, $Query);//echo $Query;
        if ($Result) {
            $this->Select_Rows = mysqli_num_rows($Result);
            $mresult = array();
            for ($i = 0; $i < $this->Select_Rows; $i++) {
                $mresult[$i] = mysqli_fetch_array($Result, MYSQLI_ASSOC);
            }
            $this->Select_Result = $mresult;
        } else {
            $this->Select_Rows = FALSE;
            return FALSE;
        }
        mysqli_free_result($Result);
    }

    public function Insert($Table, $Fields, $Values)
    {
        $Query = "INSERT INTO `{$Table}` ( " . $Fields . " ) VALUES  " . $Values;
        $Result = mysqli_query($this->Con, $Query);//echo $Query;
        if ($Result) {
            $this->Insert_ID = mysqli_insert_id($this->Con);
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function Delete($Table, $Fields, $ID)
    {
        $Query = "DELETE FROM `{$Table}` WHERE " . $Fields . " = " . $ID;
        $Result = mysqli_query($this->Con, $Query);
        if ($Result) return TRUE; else

            return FALSE;
    }

    public function Update($Table, $Set, $Where)
    {
        $Query = "UPDATE `{$Table}` SET ";
        foreach ($Set as $key => $value) {
            $Query .= "`{$key}` = '{$value}', ";
        }
        $Query = substr($Query, 0, -2);
        // WHERE
        $Query .= ' WHERE ' . $Where;
        $Result = mysqli_query($this->Con, $Query);
        if ($Result) return TRUE; else

            return FALSE;
    }

    public function Last_ID()
    {
        if ($id = mysql_insert_id($this->Con)) {
            return $id;
        } else {
            return false;
        }
    }

    public function Escape($string)
    {
        $string = stripslashes(mysqli_real_escape_string($this->Con, $string));
        return $string;
    }
}

?>