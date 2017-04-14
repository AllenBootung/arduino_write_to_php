<?php

    // Prepare variables for database connection
    $link = mysqli_connect('localhost', 'root', '', 'sensor');
    $sql = "SET NAMES UTF8";
    $link->query($sql);

    // $dbusername = "root";  // enter database username, I used "arduino" in step 2.2
    // $dbpassword = "";  // enter database password, I used "arduinotest" in step 2.2
    // $server = "localhost"; // IMPORTANT: if you are using XAMPP enter "localhost", but if you have an online website enter its address, ie."www.yourwebsite.com"

    // // Connect to your database

    // $dbconnect = mysql_pconnect($server, $dbusername, $dbpassword);
    // $dbselect = mysql_select_db("sensor",$dbconnect);

    function sql_injection($str) 
    {
        global $link;
        $str = mysqli_real_escape_string($link,$str);
        $str = htmlspecialchars($str);
        return $str;
    }

    function get_last_no ( $primary_key, $table)
    {
        global $link;
        $sql_lastno  = "SELECT ".$primary_key;
        $sql_lastno .= " FROM ".$table;
        $sql_lastno .= " ORDER BY ".$primary_key;
        $sql_lastno .= " DESC LIMIT 1";
        print_r($sql_lastno); echo "<br>";
        $result_lastno = $link->query($sql_lastno); 
        $number = 1;
        while($row = mysqli_fetch_row($result_lastno))
        {
            $number = $row[0]+1;
            break;
        } 
        
        mysqli_free_result($result_lastno);
        return $number;
    }

    $light_sn = get_last_no("LIGHT_NO", "light");
    $sql = "INSERT INTO light (
                        LIGHT_NO ,
                        LIGHT_VAL ,
                        LIGHT_TIME ) 
                        VALUES (
                        '".$light_sn."' ,
                        '".sql_injection($_GET["LIGHT_VAL"])."' ,
                        '".date("Y-m-d/ H:i:s")."' ) 
            ";    
    
    $result = $link->query($sql);

?>