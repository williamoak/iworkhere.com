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
    trackhit($shandle,$webname,$scriptname,"showtext","sumrep","mysql");

    $repname = getvardata("repname","sumrep.txt.php",99);

    show_source($repname);

?>
