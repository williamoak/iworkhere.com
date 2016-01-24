<?
    header("Content-type: text/css; charset: UTF-8");
    ////////////////////////////////////////////////////////////////////
    // to make this work, this folder requires .htaccess to contain:
    // AddType text/css .css
    // AddHandler application/x-httpd-php .css
    // additionally, the httpd.conf file needs to set the VirtualHost to
    // AllowOverride AuthConfig FileInfo (+ other options if required)
    ////////////////////////////////////////////////////////////////////

    $css_rules = array();
    if(session_id() == ""){session_start();}
    $webname    = $_SERVER["SERVER_NAME"];
    $docroot    = $_SERVER["DOCUMENT_ROOT"];
    $browser    = $_SERVER["HTTP_USER_AGENT"];
    $modbase    = "$docroot/tracker";
    include_once "$docroot/baselib/baselib.php";
    include_once "$docroot/baselib/iconfig.php";

    $btype = browsertype($browser);
    $activesource = getvardata("activesource","activecss",99);
    $theme        = getvardata("theme","default",99);
    $debug        = getvardata("debug",false,99);
    $page         = getvardata("page","all",99);

    $shandle = (!isset($_SESSSION["shandle"] ) ) ? initi($docroot,$modbase) : $_SESSION["shandle"];

    $sql = "select looking_for, css_name from $activesource where conditional=\"default\" limit 1";
    if($debug){echo "<!-- sql[$sql] -->\n";}
    $sresult = mysqli_query($shandle,$sql) or die("cannot connect to activecss: ".mysqli_connect_error());
    $sdata= mysqli_fetch_array($sresult);
    $default_condition = $sdata["looking_for"];
    $default_seeking = $sdata["css_name"];
    if($debug){echo "<!-- result[$default_seeking]-->\n";}

    $sql = "select css_name,css_selector,css_value,css_place from $activesource where conditional=\"$default_condition\" and looking_for=\"$default_seeking\" and theme=\"$theme\" order by css_place";
    if($debug){echo "<!-- sql[$sql] -->\n";}
    $sresult = mysqli_query($shandle,$sql) or die("cannot read $activesource: ".mysqli_connect_error());
    while( $sdata = mysqli_fetch_array($sresult)){
        $this_name = $sdata["css_name"];
        $this_selector = $sdata["css_selector"];
        $this_value = $sdata["css_value"];
        $this_place = $sdata["css_place"];
        $css_rules[$this_name][$this_place]="$this_selector: $this_value";
    }
    if($debug){echo "<!-- rules:";print_r($css_rules);echo "-->\n";}

    // now check for non-default rules
    if($debug){echo "<!-- testing [$btype] vs [$default_seeking] -->\n";}
    if( $btype != $default_seeking ){
        $sql = "select css_name,css_selector,css_value,css_place from $activesource where conditional=\"$default_condition\" and looking_for=\"$btype\" and theme=\"$theme\" order by css_place";
        if($debug){echo "<!-- sql[$sql] -->\n";}
        $sresult = mysqli_query($shandle,$sql) or die("cannot read $activesource: ".mysqli_connect_error());
        while( $sdata = mysqli_fetch_array($sresult)){
            $this_name = $sdata["css_name"];
            $this_selector = $sdata["css_selector"];
            $this_value = $sdata["css_value"];
            $this_place = $sdata["css_place"];
            $css_rules[$this_name][$this_place]="$this_selector: $this_value";
        }
    }

    // now write the styles out to apache
    if($debug){echo "<!-- rules:";print_r($css_rules);echo "-->\n";}
    echo "/"."* styles for $default_seeking *"."/\n";
    foreach( $css_rules as $rule_name => $this_rule ){
        echo "$rule_name {\n";
        foreach( $this_rule as $rule_place => $rule_value ){
            echo "	".$rule_value.";\n";
        }
        echo "}\n";
    }
/////////////////////////////////////////////////////////////////////////

?>
