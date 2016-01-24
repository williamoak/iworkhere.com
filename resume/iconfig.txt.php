<?
////////////////////////////////////////////////////////////
// init and checktable altered for mysqli driver
////////////////////////////////////////////////////////////
//
// requires baselib.php and /docroot/config/credentials.inc
// credentials file must contain username/pw and host/dbase
// variables.  Additionally, this script requires files that
// define the mysql tables, called tablename.sql which contain
// field definitions and initial data entries.  Additionally
// tablename.sql.force files can contain data entries for 
// previously created mysql tables.
//
////////////////////////////////////////////////////////////
//
// API
//
// function libver_iconfig()
// function libwhy_iconfig()
// function initi( docroot, tracker module docroot, debug )
//    docroot is a pointer to the sites home folder
//    tracker module docroot is a pointer to the tracker module's home folder
//    debug is boolean
//    returns success or failure to get a pointer to the current database
// function ichecktables( shandle, tablename, filename)
//    shandle is a pointer to the current database
//    tablename is a pointer to the current table
//    filename is a pointer to the SQL description of the table
//    does not return any value
// function iprocessfile( filename, shandle, debug)
//    filename is a pointer to the SQL description of the table
//    shandle is a pointer to the current database
//    debug is boolean
//    does not return any value
//
////////////////////////////////////////////////////////////


// most recent changes
function libver_iconfig(){return "December 25, 2015";}
function libwhy_iconfig(){return "extended debug in ichecktables() and iprocessfile()";}

////////////////////////////////////////////////////////////
function initi($web_server_base_path,$tracker_module_base_path,$debug=false){
    // host/pw/dbase/etc variables in credentials.inc
    $cfile = "$web_server_base_path/config/credentials.inc";
    if($debug){
        echo "<!-- into init() seeking [$web_server_base_path]/config/credentials.inc -->\n";
        echo "<!-- and [$tracker_module_base_path]/config/tracker.sql -->\n";
        echo "<!-- vs  [$cfile] -->\n";
    }
    $shandle="error";
    if( file_exists($cfile)){
        include_once $cfile;
        if( isset($user) && isset($pass) && isset($host) && isset($dbase)){
            if( $debug ){echo "<!-- db connect to $user@$host with $pass -->\n";}
            $shandle = mysqli_connect($host,$user,$pass,$dbase);
            if(mysqli_connect_errno()) {
                printf("Connect failed: %s\n", mysqli_connect_error());
                exit();
            }
            // system and tracker databases should always be checked.
            //ichecktables($shandle,"system","$web_server_base_path/baselib/system.sql",$debug);
            ichecktables($shandle,"tracker","$tracker_module_base_path/baselib/tracker.sql",$debug);
        }
    }
    if( $debug == "true" ){echo "<!-- shandle:";print_r($shandle);echo "-->\n";}
    return $shandle;
}

/////////////////////////////////////////////////////////////
// ichecktables(shandle,tablename,filename)
// will create sql tables from tablename.sql
// will insert (once) from tablename.sql.force
// first line of .force file must be an sql query
// that evaluates to a string of non-zero length
// if the file has been inserted once
// or to a zero length answer if the file has
// not been processed yet
////////////////////////////////////////////////////////////
function ichecktables($shandle,$tablename,$filename,$debug=false){
    if($debug){echo "<!-- into ichecktables with [$tablename] & [$filename] -->\n";}
    $outval = false;
    $forcefile = $filename.".force";
    if($debug){
        $sql = "select database()";
        $sresult = mysqli_query($shandle,$sql) or die("Cannot talk to database");
        $sdata = mysqli_fetch_array($sresult);
        $result = $sdata[0];
        echo "<!-- database [$result] -->\n";
    }
    $sql = "show tables";
    if($debug){echo "<!-- sql [$sql] -->\n";}
    $sresult = mysqli_query($shandle,$sql);
    if (!mysqli_query($shandle, $sql)) {printf("Errormessage: %s\n", mysqli_error($shandle));}
    while($sdata = mysqli_fetch_array($sresult,MYSQLI_NUM)){
        $table = $sdata[0];
        $outval = ($table == $tablename) ? true : $outval;
        $ov = ( $outval == true ) ? "true" : "false";
        if($debug){echo "<!-- table[$table]/[$tablename] -> [$outval] -->\n";}
    }

    if( $outval === false ){
        iprocessfile($filename,$shandle,$debug);
    } elseif (is_file($forcefile)) {
        iprocessfile($forcefile,$shandle,$debug);
    }
}

/////////////////////////////////////////////////////////////
function iprocessfile($filename,$shandle,$debug=false){
    if($debug){echo "<!-- table not found, using [$filename] to make -->\n";}
    $firstloop = true;
    $isforced  = ( strpos($filename,"force") > 0 ) ? true : false;
    if($debug){if( $isforced ){echo "<!-- isforced -->\n";}else{echo"<!-- not forced -->\n";}};
    $fhandle = fopen($filename,"r");
    $sql = fread($fhandle,filesize($filename));
    fclose($fhandle);
    if($debug){echo "<!-- raw_file:sql[$sql] -->\n";}
    $sqlines = explode(";",$sql);
    foreach( $sqlines as $thisline ){
        if($debug){if( $firstloop ){echo "<!-- firstloop -->\n";}else{echo"<!-- not firstloop -->\n";}};
        if( $thisline != "" && $thisline != "\n" && strlen($thisline)>1){
            if($debug){echo "<!-- thisline [$thisline] -->\n";}
            if (!mysqli_query($shandle, $thisline)) {printf("Errormessage: %s\n", mysqli_error($shandle));}
        }
        // evaluate the 1st line of a forced file
        // exit if there's an answer or insert the following
        // lines if the answer is empty
        if( $firstloop && $isforced ){
            if($debug){echo "<!-- check forced code -->\n";}
            // check forced condition
            $sdata = mysqli_fetch_row($sresult);
            $answer = $sdata[0];
            if($debug){echo "<!-- answer [$answer] -->\n";}
            if( strlen($answer) > 0 ){break;}
        }
        $firstloop = false;
    }
}
/////////////////////////////////////////////////////////////

?>
