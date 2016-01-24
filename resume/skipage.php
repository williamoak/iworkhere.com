<?
    date_default_timezone_set("America/Vancouver");
    $webname    = $_SERVER["SERVER_NAME"];
    $scriptname = $_SERVER["PHP_SELF"];
    $docroot    = $_SERVER["DOCUMENT_ROOT"];
    $modbase    = "$docroot/tracker";
    include_once "$docroot/baselib/baselib.php";
    include_once "$docroot/baselib/config.php";

    if(session_id() == ""){session_start();}
    $shandle = init();

    $uselink = getvardata("link","unknown");

    trackhit($shandle,$webname,$scriptname,"skip to page","$uselink","");
    header("Location:$uselink");
?>
