<?
    // Resource Overview and Management (ROAM)
    // date_default_timezone_set("America/Vancouver");
    // dastabase driver is MYSQLI
    $webname    = $_SERVER["SERVER_NAME"];
    $scriptname = $_SERVER["PHP_SELF"];
    $docroot    = $_SERVER["DOCUMENT_ROOT"];
    $browser    = $_SERVER["HTTP_USER_AGENT"];
    $protocol   = ( isset($_SERVER["HTTPS"]) ) ? "https://" : "http://";
    $baseref    = "$protocol$webname";
    $modbase    = "$docroot/tracker";
    include_once "$docroot/baselib/baselib.php";
    include_once "$docroot/baselib/iconfig.php";
    $gac = "UA-70162812-1";

    if(session_id() == ""){session_start();}
    $shandle = initi( "$docroot", $modbase );
    trackhit($shandle,$webname,$scriptname,"","","mysqli");

    $btype = (!isset($_SESSION["btype"])) ? browsertype($browser) : $_SESSION["btype"];
    $block = ($btype == "msie") ? "block" : "inline";

    echo "<!doctype html>\n";
    echo "<html>\n";
    echo "<head>\n";
    echo "<title>$webname</title>\n";
    echo "<meta name=\"browser\" content=\"$btype\">\n";
    echo ($btype == "msie") ? "<META HTTP-EQUIV=\"X-UA-Compatible\" CONTENT=\"IE=edge\"/>\n" : "";
    echo ($btype == "msie") ? "<meta name=\"msapplication-config\" content=\"none\"/>\n" : "";
    echo "<link type='text/css' rel='stylesheet' href='$baseref/jquery/1.11.2-ui/jquery-ui.css'>\n";
    echo "<link type='text/css' rel='stylesheet' href='$baseref/jquery/1.11.2-ui/jquery-ui.structure.css'>\n";
    echo "<link type='text/css' rel='stylesheet' href='$baseref/jquery/1.11.2-ui/jquery-ui.theme.css'>\n";
    echo "<script src='$baseref/jquery/1.11.2/jquery-1.11.2.js'></script>\n";
    echo "<script src='$baseref/jquery/1.11.2-ui/jquery-ui.js'></script>\n";
    setstyle('/',true);
    gac($gac);
    echo "</head>\n";
    echo "<body class='bodydiv'>\n";

    echo "<span class='test'>$baseref in $btype</span> &nbsp; \n";
    echo "jQuery <span id='jqmsg'>is not active</span><br/>\n";
    echo "<a href='./resume/' title='Resume Page'>Resume Page</a>\n";
    echo "<hr/>\n";

    mainjava();
    echo "</body>\n";
    echo "</html>\n";

/////////////////////////////////////////////////////////////////////////////////////

function mainjava(){
    echo "<script type='text/javascript'>\n";
    echo "$(function(){\n";
    echo "  $('#jqmsg').html(\"is <span class='ired'>Active</span>\");\n";
    echo "})\n";
    echo "</script>\n";
}
/////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////

?>
