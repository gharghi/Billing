<?php

class database
{
    //Private Variables
    public $Select_Result; //Hold the Status of connection
    public $Select_Rows;  //Hold the Active Connection
    //Public Variables
    public $Query;
    public $insertID;
    public $TotalRows;
    private $Status;
    private $Con;

    function __construct()
    {
        if (!$this->Status) {
            $this->Con = mysqli_connect($_SESSION['DBserver'], $_SESSION['DBuser'], $_SESSION['DBpass'], $_SESSION['DBname']);
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

    public function Free($Query)
    {
        $this->Query = $Query;
        $Result = mysqli_query($this->Con, $Query);
        if ($Result) {
            $total = mysqli_fetch_row(mysqli_query($this->Con, 'SELECT FOUND_ROWS()'));
            $this->TotalRows = $total[0];
            $this->Select_Rows = mysqli_num_rows($Result);
            if ($this->Select_Rows > 0) {
                for ($i = 0; $i < $this->Select_Rows; $i++) {
                    $mresult[$i] = mysqli_fetch_array($Result, MYSQLI_ASSOC);
                }
                $this->Select_Result = $mresult;
            }
        } else {
            $this->Select_Rows = FALSE;
            return FALSE;
        }
        mysqli_free_result($Result);
    }

    public function Select($Table, $Rows = '*', $Where = null, $Order = null)
    {
        $Query = 'SELECT SQL_CALC_FOUND_ROWS ' . $Rows . ' FROM ' . $Table;
        if ($Where != null) $Query .= ' WHERE ' . $Where;
        if ($Order != null) $Query .= ' ORDER BY ' . $Order;
        $this->Query = $Query;
        $Result = mysqli_query($this->Con, $Query);
        if ($Result) {
            $total = mysqli_fetch_row(mysqli_query($this->Con, 'SELECT FOUND_ROWS()'));
            $this->TotalRows = $total[0];
            $this->Select_Rows = mysqli_num_rows($Result);
            if ($this->Select_Rows > 0) {
                for ($i = 0; $i < $this->Select_Rows; $i++) {
                    $mresult[$i] = mysqli_fetch_array($Result, MYSQLI_ASSOC);
                }
                $this->Select_Result = $mresult;
            }
        } else {
            $this->Select_Rows = FALSE;
            return FALSE;
        }
        mysqli_free_result($Result);
    }

    public function Insert($Table, $Fields, $Values)
    {
        $Query = "INSERT INTO `{$Table}` ( " . $Fields . " ) VALUES  " . $Values;
        $Result = mysqli_query($this->Con, $Query);
        $this->Query = $Query;//echo $Query;
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
        $this->Query = $Query;//echo $Query;
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
        $this->Query = $Query;//echo $Query;
        if ($Result) return TRUE; else

            return FALSE;
    }

    public function Last_ID()
    {
        if ($id = mysqli_insert_id($this->Con)) {
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