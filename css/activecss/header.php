<?

    // called from index.php

    // database driver is MYSQLI
    if(session_id() == ""){session_start();}
    $start      = time();
    $webname    = $_SERVER["SERVER_NAME"];
    $scriptname = $_SERVER["PHP_SELF"];
    $docroot    = $_SERVER["DOCUMENT_ROOT"];
    $browser    = $_SERVER["HTTP_USER_AGENT"];
    $protocol   = ( isset($_SERVER["HTTPS"]) ) ? "https://" : "http://";
    $baseref    = "$protocol$webname";
    $modbase    = "$docroot/tracker";
    include_once "$docroot/baselib/baselib.php";
    include_once "$docroot/baselib/iconfig.php";
    include_once "objects.php";

    // contact database and make sure we have the table setup
    $shandle = initi( "$docroot", $modbase );
    ichecktables($shandle,"activecss","$baseref/css/activecss/activecss.sql");

    // proto for trackhit is:
    //trackhit($shandle,$webname,$scriptname,"theme","$theme");

    $btype = (!isset($_SESSION["btype"])) ? browsertype($browser) : $_SESSION["btype"];
    $block = ($btype == "msie") ? "block" : "inline";
    //echo "<!-- session:";print_r($_SESSION);echo "-->\n";

    $nextact       = getvardata("nextact"       ,"home"    ,99);
    $activebrowser = getvardata("activebrowser" ,"msie"    ,99);
    $activetheme   = getvardata("activetheme"   ,"default" ,99);
    $activepage    = getvardata("activepage"    ,"default" ,99);
    $activesection = getvardata("activesection" ,"default" ,99);
    $debug         = getvardata("debug"         ,"false"   ,99);
    $elaps         = getvardata("elaps"         ,"0.00"    ,99);
    $allstylesrbtn = getvardata("allstylesrbtn" ,"no"      ,99);

    // get default browser and default theme
    $sql = "select theme,css_name,css_selector from activecss where conditional=\"default\" and looking_for=\"browser\" limit 1";
    if( $debug == "true" ){echo "<!-- sql[$sql] -->\n";}
    $sresult    = mysqli_query($shandle,$sql) or die("Cannot talk to active css");
    $sdata      = mysqli_fetch_array($sresult);
    $deftheme   = $sdata["theme"];
    $defbrowser = $sdata["css_name"];
    $defsection = $sdata["css_selector"];

    // save session vars for use in functions
    if( $nextact == "reset" ) { session_destroy();session_start();$nextact="home";}
    $_SESSION["shandle"]       = $shandle;
    $_SESSION["baseref"]       = $baseref;
    $_SESSION["webname"]       = $webname;
    $_SESSION["scriptname"]    = $scriptname;
    $_SESSION["browser"]       = $browser;
    $_SESSION["docroot"]       = $docroot;
    $_SESSION["modbase"]       = $modbase;
    $_SESSION["btype"]         = $btype;
    $_SESSION["block"]         = $block;
    $_SESSION["activebrowser"] = $activebrowser;
    $_SESSION["activetheme"]   = $activetheme;
    $_SESSION["activepage"]    = $activepage;
    $_SESSION["activesection"] = $activesection;
    $_SESSION["defbrowser"]    = $defbrowser;
    $_SESSION["deftheme"]      = $deftheme;
    $_SESSION["defsection"]    = $defsection;
    $_SESSION["debug"]         = $debug;
    $_SESSION["allstylesrbtn"] = $allstylesrbtn;

    // return into index.php
?>
