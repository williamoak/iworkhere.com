<?

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
    include_once "$docroot/css/activecss/objects.php";

    //unset($_SESSION["shandle"]);
    $shandle = initi( "$docroot", $modbase);
    $_SESSION["shandle"]=$shandle;

    $pe_nextact = getvardata("ps_nextact","view",99);
    $acton      = getvardata("acton","empty",99);
    $target     = getvardata("target","unknown",99);

    $btype = (!isset($_SESSION["btype"])) ? browsertype($browser) : $_SESSION["btype"];
    $block = ($btype == "msie") ? "block" : "inline";

    echo "<!doctype html>\n";
    echo "<html>\n";
    echo "<head>\n";
    echo "	<title>$webname</title>\n";
    echo "	<meta name=\"browser\" content=\"$btype\">\n";
    echo ($btype == "msie") ? "	<META HTTP-EQUIV=\"X-UA-Compatible\" CONTENT=\"IE=edge\"/>\n" : "";
    echo ($btype == "msie") ? "	<meta name=\"msapplication-config\" content=\"none\"/>\n" : "";
    echo "	<link type='text/css' rel='stylesheet' href=\"$baseref/jquery/1.11.2-ui/jquery-ui.css\">\n";
    echo "	<link type='text/css' rel='stylesheet' href=\"$baseref/jquery/1.11.2-ui/jquery-ui.structure.css\">\n";
    echo "	<link type='text/css' rel='stylesheet' href=\"$baseref/jquery/1.11.2-ui/jquery-ui.theme.css\">\n";
    echo "	<link type='text/css' rel='stylesheet' href=\"$baseref/jquery/colorpicker/spectrum.css\">\n";
    echo "	<script src='$baseref/jquery/1.11.2/jquery-1.11.2.js'></script>\n";
    echo "	<script src='$baseref/jquery/1.11.2-ui/jquery-ui.js'></script>\n";
    echo "	<script src='$baseref/jquery/slider/js/jssor.slider.min.js'></script>\n";
    echo "	<script src='$baseref/jquery/colorpicker/spectrum.js'></script>\n";
    echo "	<link type='text/css' rel='stylesheet' href=\"$baseref/css/activecss/activecss.css\">\n";
    setstyle('/',true);
    echo "</head>\n";
    echo "<body class='activebody'>\n";
    echo "	<div name='dbox' id='dbox' class='popupdialogue'>\n";

    $parts = explode(":",$target);
    if( count($parts) > 1 ){
	$from = $parts[0];
	$to   = $parts[1];
    } else {
	$from = "click";
	$to = $target;
    }

    //echo "<!-- with session:";print_r($_SESSION);echo "-->\n";
    echo "		<form name='peform' id='peform' action='$scriptname' method='POST'>\n";
    echo "		<input type=hidden name='pe_nextact' id='pe_nextact' value='$pe_nextact'>\n";
    echo "		<input type=hidden name='acton' id='acton' value='$acton'>\n";
    echo "		<input type=hidden name='target' id='target' value='$target'>\n";

    //echo "<!-- processing [$target] [$from] [$to] -->\n";
    dispatch( $from, $to );

    echo "		</form>\n";
    echo "	</div>\n";
    echo "</body>\n";
    morejava();
    echo "</html>\n";

///////////////////////////////////////////////////////////////

function morejava(){
    echo "<script type='text/javascript'>\n";
    echo "  function nextpage( target, acton ){\n";
    echo "    document.forms[0].acton.value=acton;\n";
    echo "    document.forms[0].target.value=target;\n";
    echo "    document.forms[0].submit();\n";
    echo "  }\n";
    echo "  function oops(){\n";
    echo "    window.opener.document.forms[0].submit();\n";
    echo "    self.close();\n";
    echo "  }\n";
    echo "  function nextaction( tVal ){\n";
    echo "    document.forms[0].acton.value=tVal;\n";
    echo "    document.forms[0].submit();\n";
    echo "  }\n";
    echo "\$(function(){\n";
    echo "    \$(\".picker\").spectrum({\n";
    echo "      allowEmpty: true,";
    echo "      showInitial: true,";
    echo "      showAlpha: true,\n";
    echo "      showInput: true,\n";
    echo "      preferredFormat: 'hex'\n";
    echo "    });\n";
    echo "});\n";
    echo "</script>\n";
}

///////////////////////////////////////////////////////////////

function dispatch( $from, $to ){
    $fromtype = ( strpos($from, "_section_" ) > -1 ) ? "section" : "unknown";
    $fromtype = ( strpos($from, "_theme_"   ) > -1 ) ? "theme"   : $fromtype;
    $fromtype = ( strpos($from, "style_"    ) > -1 ) ? "style"   : $fromtype;
    $fromtype = ( strpos($from, "click"     ) > -1 ) ? "click"   : $fromtype;
    $fromtype = ( strpos($from, "copy"      ) > -1 ) ? "copy"    : $fromtype;
    //$fromtype = ( strpos($from, "trash"     ) > -1 ) ? "delete"  : $fromtype;

    //echo "calling switch on [$from]/[$fromtype] with [$to]<br/>\n";
    if( $to == "trash" ){
	$x = str_replace("_browser_","",$from);
	$x = str_replace("_theme_","",$x);
	$x = str_replace("_section_","",$x);
	$x = str_replace("style_","",$x);
	$x = str_replace("object","",$x);
	$x = str_replace("_box","",$x);
	//echo "<!-- dispatching to trash with [$fromtype] [$x] -->\n";
	if( $x == "default" ){
	    droperror("$x:$fromtype","trash");
	} else {
	    trash($fromtype,$x);
	}
    } else {
	switch( $fromtype ){
	    case "style":
		movestyle( $from , $to );
		break;
	    case "theme":
		movetheme( $from , $to );
		break;
	    case "section":
		movesection( $from , $to );
		break;
	    case "click":
		clickevent( $to );
		break;
	    case "copy":
		docopy( $from , $to );
		break;
	    default:
		droperror( $from , $to );
		break;
	}
    }
}

///////////////////////////////////////////////////////////////

function movetheme($from, $to ){
    // cannot trash default theme
    // cannot move theme to activebrowser
    $from = str_replace("_theme_object","",$from);
    $to = str_replace( "_browser_object","",$to);
    $activebrowser = $_SESSION["activebrowser"];
    $isdefault = ( strpos($from, "default" ) > -1 ) ? "true" : "false";
    //echo "<!-- testing theme for default result is [$isdefault] -->\n";
    //echo "<!-- testing [$activebrowser] vs [$to] -->\n";
    if( ($isdefault == "true" and $to == "trash") or ($to == $activebrowser) ){
	droperror($from,$to);
    } else {
	// we can move theme and all style in that theme to the trash,
	// or move all the styles in the current theme to a new browser
	// this will overwrite any styles currently in the new browser location
	// effectivly making all styles in the new browser EQUAL to styles in
	// the current browser.  We need to Verify with a popup in either case
	echo "<!-- from[$from] to:[$to] -->\n";
	$popupmsg = "Are you sure you want to copy the <span class='subject'>$from</span> theme to the <span class='subject'>$to</span> Browser?";
	echo "$popupmsg <input type=button name='nbtn' id='nbtn' value='No' onclick=\"oops();\"> &nbsp; <input type=button name='ybtn' id='ybtn' value='Yes' onclick=\"nextpage('$from:theme','$to:browser');\">\n";
    }
}

///////////////////////////////////////////////////////////////

function movesection( $from, $to ){
    // cannot trash default section
    // cannot move section to activetheme
    // cannot move section to activebrowser
    $to = str_replace( "_browser_object","",$to);
    $to = str_replace( "_theme_object","",$to);
    $activebrowser = $_SESSION["activebrowser"];
    $activetheme   = $_SESSION["activetheme"];
    $sameblock = ( $to == $activebrowser ) ? "true" : "false";
    $sameblock = ( $to == $activetheme   ) ? "true" : $sameblock;
    $isdefault = ( strpos($from, "default") > -1 ) ? "true" : "false";
    //echo "<!-- testing section for default result is [$isdefault] -->\n";
    if( ($isdefault == "true" and $to == "trash") or ( $sameblock == "true") ){
	droperror($from,$to);
    } else {
	copysection($from,$to);
    }
}

///////////////////////////////////////////////////////////////

function movestyle( $from, $to ){
    echo "<!-- in movestyle with [$from] and [$to] -->\n";
    // may not drag a style to active browser, theme or section
    //echo "move style placeholder";
    // styles can move to a new section
    // a new theme, a new browser or to the trash
    $activebrowser = $_SESSION["activebrowser"];
    $activetheme   = $_SESSION["activetheme"];
    $activesection = $_SESSION["activesection"];

    $source = str_replace( "style_","",$from );
    $source = str_replace( "_box","",$source );
    $desti  = str_replace( "_object","",$to );
    //echo "<!-- desti[$desti] -->\n";
    $dparts = ( $desti == "trash" ) ? explode("_","trash_trash") : explode( "_" , $desti);
    $dname  = $dparts[0];
    $dtype  = $dparts[1];
    $styleinfo = $_SESSION["showstyles"][$source];
    $stylename = ( $styleinfo["type"] == "class" ) ? ".$source" : "#$source";
    //echo "<!-- style info:";print_r($styleinfo);echo "-->\n";

    $sameblocked = ( $dname == $activebrowser ) ? "true" : "false";
    $sameblocked = ( $dname == $activetheme   ) ? "true" : $sameblocked;
    $sameblocked = ( $dname == $activesection ) ? "true" : $sameblocked;

    if( $sameblocked == "true" ){
	droperror($from,$to);
    } else {
	copystyle($from,$to);
    }

}

///////////////////////////////////////////////////////////////

function clickevent( $to ){
    //echo "click event placeholder for [$to]<br/>\n";
    $to = str_replace("style_","",$to);
    $to = str_replace("_box","",$to);
    switch( $to ){
	case "newbrowser":
	    addnewbrowser();
	    break;
	case "newtheme":
	    addnewtheme();
	    break;
	case "newpage":
	    addnewpage();
	    break;
	case "newsection":
	    addnewsection();
	    break;
	case "newstyle":
	    addnewstyle();
	    break;
	default:
	    $stylearray=$_SESSION["showstyles"][$to];
	    editstyle($stylearray,$to);
	    break;
    }
}
///////////////////////////////////////////////////////////////

function droperror( $from , $to ){
    echo "You may not drag $from to $to<br/>\n";
    echo "<input type=button name='nbtn' id='nbtn' value='Quit' onclick=\"oops();\">\n";
}
///////////////////////////////////////////////////////////////

function trash( $type, $from ){
    $shandle = $_SESSION["shandle"];
    $activetheme = $_SESSION["activetheme"];
    $activesection = $_SESSION["activesection"];
    $activebrowser = $_SESSION["activebrowser"];
    //echo "in trash with:<br/>\n";
    //echo "type:[$type]<br/>\n";
    //echo "from:[$from]<br/>\n";
    //echo "activebrowser:[$activebrowser]<br/>\n";
    //echo "activetheme:[$activetheme]<br/>\n";
    //echo "activesection:[$activesection]<br/>\n";
    // del a style deletes that style for that browser
    // del a section deletes all styles of that section in that brower
    // del a theme deletes all styles of that theme in that browser
    switch($type){
	case "style":
	    $from1 = ".$from";
	    $from2 = "#$from";
	    $sqlimit = "conditional=\"browser\" and looking_for=\"$activebrowser\" and theme=\"$activetheme\" and section=\"$activesection\" and css_name=\"$from1\" or css_name=\"$from2\"";
	    break;
	case "theme":
	    $sqlimit = "(conditional=\"themelist\" and looking_for=\"$from\") or (conditional=\"browser\" and looking_for=\"$activebrowser\" and theme=\"$from\")";
	    break;
	case "section":
	    $sqlimit = "(conditional=\"sectionlist\" and looking_for=\"$from\") or (conditional=\"browser\" and looking_for=\"$activebrowser\" and theme=\"$activetheme\" and section=\"$from\")";
	    break;
    }

    $sql = "delete from activecss where $sqlimit";
    echo "<!-- sql[$sql] -->\n";
    $sresult = mysqli_query($shandle,$sql) or die("Cannot talk to activecss table");
    echo "Successfully deleted $type $from for:<br/>\n";
    echo "browser: $activebrowser<br/>\n";
    echo "theme: $activetheme<br/>\n";
    echo "section: $activesection<br/>\n";
    echo "<input type=button name='dbtn' id='dbtn' value='Done' onclick=\"oops();\">\n";
}

///////////////////////////////////////////////////////////////

function docopy( $from , $to ){
    $acton = getvardata("acton","unknown:unknown",99);
    $tparts = explode(":",$acton);
    $theme = $tparts[0];
    $browser = $tparts[1];

    switch ($to){
	case "theme":
	    copytheme($from,$to,$theme,$browser);
	    break;
	case "section":
	    copysection($from,$to,$theme,$browser);
	    break;
	case "style":
	    copystyle($from,$to,$theme,$browser);
	    break;
    }


}
///////////////////////////////////////////////////////////////

function copytheme( $from, $to, $theme, $browser ){
    $activebrowser = $_SESSION["activebrowser"];
    $shandle       = $_SESSION["shandle"];
    //echo "in copy with [$from] and [$to] from $activebrowser:$theme to $browser<br/>\n";
    $sql = "delete from activecss where conditional=\"browser\" and looking_for=\"$browser\" and theme=\"$theme\"";
    //echo "<!-- sql[$sql] -->\n";
    $sresult = mysqli_query($shandle,$sql) or die("Cannot talk to active css");
    $sql = "select section,css_name,css_selector,css_value,css_place from activecss where conditional=\"browser\" and looking_for=\"$activebrowser\" and theme=\"$theme\"";
    //echo "<!-- sql[$sql] -->\n";
    $sresult = mysqli_query($shandle,$sql) or die("Cannot talk to active css");
    while( $sdata = mysqli_fetch_array($sresult)){
	$this_section  = $sdata["section"];
	$this_name     = $sdata["css_name"];
	$this_selector = $sdata["css_selector"];
	$this_value    = $sdata["css_value"];
	$this_place    = $sdata["css_place"];
	$isql = "insert into activecss set conditional=\"browser\", looking_for=\"$browser\", theme=\"$theme\",section=\"$this_section\", css_name=\"$this_name\", css_selector=\"$this_selector\", css_value=\"$this_value\", css_place=\"$this_place\"";
	//echo "<!-- isql[$isql] -->\n";
	$sresult = mysqli_query($shandle,$isql) or die("Cannot insert into active css");
    }
    echo "Theme <span class='subject'>$theme</span> has been copied from <span class='subject'>$activebrowser</span> to <span class='subject'>$browser</span><br/>\n";
    echo "<input type=button name='dbtn' id='dbtn' value='Done' onclick=\"oops();\">\n";
}
///////////////////////////////////////////////////////////////

function copysection( $from, $to ){
    $activebrowser = $_SESSION["activebrowser"];
    $activetheme   = $_SESSION["activetheme"];
    $shandle       = $_SESSION["shandle"];
    echo "in copy section with [$from] and [$to] from $activebrowser:$activetheme<br/>\n";

}

///////////////////////////////////////////////////////////////

function copystyle( $from, $to ){
    $delrecs  = array();
    $result   = "";

    $movetype = ( strpos($to,"theme") > -1 ) ? "theme" : "unknown";
    $movetype = ( strpos($to,"section") > -1 ) ? "section" : $movetype;
    //echo "switching on [$movetype] from [$to]<br/>\n";

    switch( $movetype ){
	case "theme":
	    $result = do_move_style_to_theme($from,$to);
	    break;
	case "section":
	    $result = do_move_style_to_section();
	    break;
    }
    echo "<table>\n";
    echo "<tr><td>$result</td></tr>\n";
    echo "<tr><td><input type=button name='qbtn' id='qbtn' value='Done' onclick=\"oops()\"></td></tr>\n";
    echo "</table>\n";

}
///////////////////////////////////////////////////////////////

function do_move_style_to_theme($from,$to){
    $activebrowser = $_SESSION["activebrowser"];
    $activetheme   = $_SESSION["activetheme"];
    $activesection = $_SESSION["activesection"];
    $shandle       = $_SESSION["shandle"];
    $delrecs       = array();

    $source = str_replace("style_","",$from);
    $source = str_replace("_box","",$source);
    $source = showgoodname($source);
    $desti  = str_replace("_theme_object","",$to);
    $type = get_selector_type($source);
    $css_name = ( $type == "class" ) ? ".$source" : "#$source";
    $status = "Copying of Style $css_name from theme $activetheme to $desti";

    $index=0;
    $sql = "select corder from activecss where conditional=\"browser\" and looking_for=\"$activebrowser\" and theme=\"$activetheme\" and section=\"$desti\" and css_name=\"$css_name\"";
    //echo "<!-- sql[$sql] -->\n";
    $sresult = mysqli_query($shandle,$sql) or die("Cannot talk to active css table");
    while( $sdata = mysqli_fetch_array($sresult)){
	$delrecs[$index++] = $sdata["corder"];
    }

    if( count($delrecs) > 0 ){
	foreach( $delrecs as $thisrec ){
	    // we will be updating an existing record
	    $sql = "delete from activecss where corder=\"$thisrec\"";
	    //echo "<!-- delete[$sql] -->\n";
	    $sresult = mysqli_query($shandle,$sql) or die("Cannot talk to active css table");
	}
    }

    // now we pickup the style entries
    $sql = "select corder,css_selector,css_value,css_place from activecss where conditional=\"browser\" and looking_for=\"$activebrowser\" and theme=\"$activetheme\" and section=\"$activesection\" and css_name=\"$css_name\"";
    //echo "<!-- pickup[$sql] -->\n";
    $sresult = mysqli_query($shandle,$sql) or die("Cannot talk to active css table");
    while( $sdata = mysqli_fetch_array($sresult) ) {
	$corder = $sdata["corder"];
	$css_selector=$sdata["css_selector"];
	$css_value=$sdata["css_value"];
	$css_place=$sdata["css_place"];
	$isql = "insert into activecss set conditional=\"browser\",looking_for=\"$activebrowser\",theme=\"$desti\",section=\"$activesection\",css_name=\"$css_name\",css_selector=\"$css_selector\",css_value=\"$css_value\",css_place=\"$css_place\"";
	//echo "<!-- put down[$isql] -->\n";
	$iresult = mysqli_query($shandle,$isql) or die("Cannot talk to active css table");
	$status = ( $iresult === false ) ? $status.": failed on $css_selector" : $status;
    }
    return $status;
}

///////////////////////////////////////////////////////////////

function do_move_style_to_section($from,$to){
    $activebrowser = $_SESSION["activebrowser"];
    $activetheme   = $_SESSION["activetheme"];
    $activesection = $_SESSION["activesection"];
    $shandle       = $_SESSION["shandle"];
    echo "move style to section placeholder";
}

///////////////////////////////////////////////////////////////

function get_selector_type($source){
    $shandle = $_SESSION["shandle"];

    $sql = "select corder from activecss where css_name=\".$source\" limit 1";
    echo "<!-- sql[$sql] -->\n";
    $sresult = mysqli_query($shandle,$sql) or die("Cannot talk to active css table");
    $sdata = mysqli_fetch_array($sresult);
    $corder1 = $sdata["corder"];
    $sql = "select corder from activecss where css_name=\"#$source\" limit 1";
    echo "<!-- sql[$sql] -->\n";
    $sresult = mysqli_query($shandle,$sql) or die("Cannot talk to active css table");
    $sdata = mysqli_fetch_array($sresult);
    $corder2 = $sdata["corder"];
    echo "<!-- result [$corder1] & [$corder2] -->\n";
    $outval = ( $corder1 > -1 ) ? "class" : "";
    $outval = ( $corder2 > -1 ) ? "id" : $outval;
    return $outval;
}

///////////////////////////////////////////////////////////////

function addnewbrowser(){
    echo "add new browser";
}

///////////////////////////////////////////////////////////////
//
// Add theme functions
//
///////////////////////////////////////////////////////////////
function addnewtheme(){
    //echo "<!-- into addnewtheme() -->\n";
    $newid    = getvardata("newid","",99);
    $newname  = getvardata("newname","",99);
    $newpic   = getvardata("newpic","themes.gif",99);
    $status   = "Enter a name and a description for a new theme.";

    //echo "<!-- testing for empty newid[$newid] -->\n";
    if( $newid != "" ){ $status = updatetheme();}
    //echo "<!-- back into addnewtheme with [$status] -->\n";

    //echo "<!-- into add new theme with:";print_r($_SESSION);echo "-->\n";
    //echo "add new theme";
    echo "<table>\n";
    echo "<tr><td>Name of new Theme?:</td>\n";
    echo "<td><input type=text name='newid' id='newid' value=\"$newid\"></td></tr>\n";
    echo "<tr><td>Description of new Theme?:</td>\n";
    echo "<td><input type=text name='newname' id='newname' value=\"$newname\"></td></tr>\n";
    echo "<tr><td>Theme Icon file?:</td>\n";
    echo "<td><input type=text name='newpic' id='newpic' value=\"$newpic\"></td></tr>\n";

    echo "<tr><td colspan=2><input type=button name='ubtn' id='ubtn' value='Update' onclick=\"nextaction('update');\"> &nbsp; \n";
    echo "<input type=button name='qbtn' id='qbtn' value='Done' onclick=\"oops()\"></td></tr>\n";
    echo "<tr><td colspan=2><span name='status' id='status' class=''>$status</span></td></tr>\n";
    echo "</table>\n";
    setfocus("newid");

}
///////////////////////////////////////////////////////////////

function updatetheme(){
    //echo "<!-- into updatetheme() -->\n";
    $status = "New theme creation failed";
    $newid    = getvardata("newid","",99);
    $newname  = getvardata("newname","",99);
    $newpic   = getvardata("newpic","themes.gif",99);
    //echo "<!-- testing length of [$newid] and [$newname] -->\n";
    if( strlen( $newid ) < 1 or strlen($newname) < 1 ){
	$status = "You need to enter valid name and description";
    } elseif( strpos(" ",$newid) > -1 ){
	$status = "Theme Name cannot contain spaces.";
    } else {
	$status = do_update_theme();
    }
    //echo "<!-- exiting updatetheme with [$status] -->\n";
    return $status;
}
///////////////////////////////////////////////////////////////
function do_update_theme(){
    //echo "<!-- into do_update_theme() -->\n";
    $status = "New theme creation failed";
    $newid    = getvardata("newid","",99);
    $newname  = getvardata("newname","",99);
    $newpic   = getvardata("newpic","themes.gif",99);
    $shandle = $_SESSION["shandle"];

    $sql = "select corder from activecss where conditional='themelist' and looking_for=\"$newid\" or theme=\"$newname\" limit 1";
    //echo "<!-- sql[$sql] -->\n";
    $sresult = mysqli_query($shandle,$sql) or die("Cannot talk to activecss");
    $sdata = mysqli_fetch_array($sresult);
    $thisrec = $sdata["corder"];
    //echo "<!-- result [$thisrec] -->\n";
    if( $thisrec > -1 ){
        $status = "This theme or description already exists, please check you values and try again";
    } else {
        $sql = "insert into activecss set conditional='themelist', looking_for=\"$newid\", theme=\"$newname\", css_selector=\"$newpic\"";
        //echo "<!-- sql[$sql] -->\n";
        $sresult = mysqli_query($shandle,$sql) or die("Cannot talk to tactive css table");
        if( $sresult === false ){
            $status = "Theme creatin failed for [$newid] and [$newname].";
        } else {
            $status = "Theme creatin successfull for [$newid] and [$newname].";
        }
    }
    //echo "<!-- exiting do_update_theme with [$status] -->\n";
    return $status;
}

///////////////////////////////////////////////////////////////
//
// Add page functions
//
///////////////////////////////////////////////////////////////

function addnewpage(){
    //echo "<!-- into addnewpage() -->\n";
    $newid    = getvardata("newid","",99);
    $newname  = getvardata("newname","",99);
    $newpic   = getvardata("newpic","sections.gif",99);
    $status   = "Enter a name and a description for a new Page.";

    //echo "<!-- testing for empty newid[$newid] -->\n";
    if( $newid != "" ){ $status = updatepage();}
    //echo "<!-- back into addnewtheme with [$status] -->\n";

    //echo "<!-- into add new theme with:";print_r($_SESSION);echo "-->\n";
    //echo "add new theme";
    echo "<table>\n";
    echo "<tr><td>Name of new Page?:</td>\n";
    echo "<td><input type=text name='newid' id='newid' value=\"$newid\"></td></tr>\n";
    echo "<tr><td>Description of new Page?:</td>\n";
    echo "<td><input type=text name='newname' id='newname' value=\"$newname\"></td></tr>\n";
    echo "<tr><td>Page Icon file?:</td>\n";
    echo "<td><input type=text name='newpic' id='newpic' value=\"$newpic\"></td></tr>\n";

    echo "<tr><td colspan=2><input type=button name='ubtn' id='ubtn' value='Update' onclick=\"nextaction('update');\"> &nbsp; \n";
    echo "<input type=button name='qbtn' id='qbtn' value='Done' onclick=\"oops()\"></td></tr>\n";
    echo "<tr><td colspan=2><span name='status' id='status' class=''>$status</span></td></tr>\n";
    echo "</table>\n";
    setfocus("newid");

}
///////////////////////////////////////////////////////////////

function updatepage(){
    //echo "<!-- into updatepage() -->\n";
    $status = "New page creation failed";
    $newid    = getvardata("newid","",99);
    $newname  = getvardata("newname","",99);
    $newpic   = getvardata("newpic","sections.gif",99);
    //echo "<!-- testing length of [$newid] and [$newname] -->\n";
    if( strlen( $newid ) < 1 or strlen($newname) < 1 ){
	$status = "You need to enter valid name and description";
    } elseif( strpos(" ",$newid) > -1 ){
	$status = "Page Name cannot contain spaces.";
    } else {
	$status = do_update_page();
    }
    //echo "<!-- exiting updatepage with [$status] -->\n";
    return $status;
}


///////////////////////////////////////////////////////////////

function do_update_page(){
    //echo "<!-- into do_update_page() -->\n";
    $status = "New page creation failed";
    $newid    = getvardata("newid","",99);
    $newname  = getvardata("newname","",99);
    $newpic   = getvardata("newpic","sections.gif",99);
    $shandle  = $_SESSION["shandle"];
    $theme    = $_SESSION["theme"];

    $sql = "select corder from activecss where conditional='pagelist' and looking_for=\"$newid\" or page=\"$newname\" limit 1";
    //echo "<!-- sql[$sql] -->\n";
    $sresult = mysqli_query($shandle,$sql) or die("Cannot talk to activecss");
    $sdata = mysqli_fetch_array($sresult);
    $thisrec = $sdata["corder"];
    //echo "<!-- result [$thisrec] -->\n";
    if( $thisrec > -1 ){
        $status = "This page or description already exists, please check you values and try again";
    } else {
        $sql = "insert into activecss set conditional='pagelist', looking_for=\"$newid\", theme=\"$theme\", page=\"$newname\", css_selector=\"$newpic\"";
        //echo "<!-- sql[$sql] -->\n";
        $sresult = mysqli_query($shandle,$sql) or die("Cannot talk to tactive css table");
        if( $sresult === false ){
            $status = "Page creatin failed for [$newid] and [$newname].";
        } else {
            $status = "Page creatin successfull for [$newid] and [$newname].";
        }
    }
    //echo "<!-- exiting do_update_theme with [$status] -->\n";
    return $status;
}

///////////////////////////////////////////////////////////////
//
// add new section functions
//
///////////////////////////////////////////////////////////////
function addnewsection(){
    //echo "<!-- into addnewtheme() -->\n";
    $newsection = getvardata("newsection","",99);
    $newname    = getvardata("newname","",99);
    $newpic     = getvardata("newpic","menusection.gif",99);
    $status     = "Enter a name for the new section.";

    //echo "<!-- testing for empty newid[$newid] -->\n";
    if( $newsection != "" ){ $status = updatesection();}
    //echo "<!-- back into addnewtheme with [$status] -->\n";

    //echo "<!-- into add new theme with:";print_r($_SESSION);echo "-->\n";
    //echo "add new theme";
    echo "<table>\n";
    echo "<tr><td>Name of new Section?:</td>\n";
    echo "<td><input type=text name='newsection' id='newsection' value=\"$newsection\"></td></tr>\n";
    echo "<tr><td>Description of new Section?:</td>\n";
    echo "<td><input type=text name='newname' id='newname' value=\"$newname\"></td></tr>\n";
    echo "<tr><td>Section Icon file?:</td>\n";
    echo "<td><input type=text name='newpic' id='newpic' value=\"$newpic\"></td></tr>\n";

    echo "<tr><td colspan=2><input type=button name='ubtn' id='ubtn' value='Update' onclick=\"nextaction('update');\"> &nbsp; \n";
    echo "<input type=button name='qbtn' id='qbtn' value='Done' onclick=\"oops()\"></td></tr>\n";
    echo "<tr><td colspan=2><span name='status' id='status' class=''>$status</span></td></tr>\n";
    echo "</table>\n";
    setfocus("newsection");
}

///////////////////////////////////////////////////////////////

function updatesection(){
    //echo "<!-- into updatesection() -->\n";
    $status = "New theme creation failed";
    $newsection = getvardata("newsection","",99);
    $newname    = getvardata("newname","",99);
    $newpic     = getvardata("newpic","menusection.gif",99);

    //echo "<!-- testing length of [$newsection] and [$newname] -->\n";
    if( strlen( $newsection ) < 1 or strlen($newname) < 1 ){
	$status = "You need to enter valid name and description";
    } elseif( strpos(" ",$newsection) > -1 ){
	$status = "Section Name cannot contain spaces.";
    } else {
	$status = do_update_section();
    }
    //echo "<!-- exiting updatesection with [$status] -->\n";
    return $status;
}
///////////////////////////////////////////////////////////////

function do_update_section(){
    //echo "<!-- into do_update_theme() -->\n";
    $status = "New section creation failed";
    $newsection = getvardata("newsection","",99);
    $newname    = getvardata("newname","",99);
    $newpic     = getvardata("newpic","menusection.gif",99);
    $shandle = $_SESSION["shandle"];

    $sql = "select corder from activecss where conditional='sectionlist' and looking_for=\"$newsection\" or theme=\"$newname\" limit 1";
    //echo "<!-- sql[$sql] -->\n";
    $sresult = mysqli_query($shandle,$sql) or die("Cannot talk to activecss");
    $sdata = mysqli_fetch_array($sresult);
    $thisrec = $sdata["corder"];
    //echo "<!-- result [$thisrec] -->\n";
    if( $thisrec > -1 ){
        $status = "This section or description already exists, please check you values and try again";
    } else {
        $sql = "insert into activecss set conditional='sectionlist', looking_for=\"$newsection\", theme=\"$newname\", css_selector=\"$newpic\"";
        //echo "<!-- sql[$sql] -->\n";
        $sresult = mysqli_query($shandle,$sql) or die("Cannot talk to active css table");
        if( $sresult === false ){
            $status = "Section creation failed for [$newsection] and [$newname].";
        } else {
            $status = "Section creation successfull for [$newsection] and [$newname].";
        }
    }
    //echo "<!-- exiting do_update_section with [$status] -->\n";
    return $status;
}


///////////////////////////////////////////////////////////////
//
// add new styles functions
//
///////////////////////////////////////////////////////////////
function addnewstyle(){
    $stylearray = array();
    $stylearray["section"] = "";
    $stylearray["type"]    = "";
    $stylearray["source"]  = "";
    editstyle($stylearray,"");
}
///////////////////////////////////////////////////////////////

function editstyle($stylearray,$name){
    $name  = ( $name == "" ) ? getvardata("name","",99) : $name;
    $acton = getvardata("acton","unknown",99);
    $statusmessage = "";
    //echo "<!-- acton [$acton] -->\n";
    //echo "<!-- edit style with [$name]:";print_r($stylearray);echo "-->\n";
    $section = $stylearray["section"];
    $type    = $stylearray["type"];
    $source  = $stylearray["source"];
    unset($stylearray["section"]);
    unset($stylearray["type"]);
    unset($stylearray["source"]);
    $source  = ( $source == ""  ) ? $_SESSION["activetheme"] : $source;
    $section = ( $section == "" ) ? $_SESSION["activesection"] : $section;
    $type    = ( $type == ""    ) ? "class"   : $type;
    //echo "<!-- edit style with [$name][$section][$type][$source]:";print_r($stylearray);echo "-->\n";
    // set up $_POST["css_parts"] and $_POST["css_values"] from stylearray
    $css_index = 1;
    if(!isset($_POST["css_parts"])){
	foreach($stylearray as $this_place => $props ){
	    $tname = $props["name"];
	    $tvalue = $props["value"];
	    $_POST["css_parts"][$css_index]=$tname;
	    $_POST["css_values"][$css_index++]=$tvalue;
	}
    }
    //echo "<!-- display [css_parts]:";print_r($_POST["css_parts"]);echo "-->\n";
    //echo "<!-- display [css_values]:";print_r($_POST["css_values"]);echo "-->\n";

    $new_place = 1;
    // acton == 1 when we're gonna add a new property
    switch( $acton ){
	case "1":
	    $new_place = count($stylearray);
	    $new_place++;
	    $_POST["css_parts"][$new_place]="";
	    $_POST["css_value"][$new_place]="";
	    break;
	case "2":
	    // update the mysql table
	    $statusmessage = updatestyles($name,$section,$type,$source);
	    break;
    }

    $name = showgoodname($name);
    $stylename = ( $type == "class" ) ? ".$name"  : "#$name";
    $section   = ( $section == ""   ) ? "default" : $section;
    $nameline  = ( $name == ""      ) ? "<td>style Name?</td><td><input type='text' name='name' id='name' value=\"\"></td>" : "<td colspan=2>$stylename</td>";

    echo "<table>\n";
    echo "	<tr><td>theme:$source</td><td>section:$section</td></tr>\n";
    echo "	<tr>$nameline</td></tr>\n";
    if( isset($_POST["css_parts"]) and count($_POST["css_parts"]) > 0 ){
	$index = 0;
	$focuson = "unknown";
	foreach( $_POST["css_parts"] as $this_part => $this_name){
	    $this_value = (isset($_POST["css_values"][$this_part])) ? $_POST["css_values"][$this_part] : "";
	    $showpicker = ( strpos($this_name,"color") > -1 ) ? "picker" : "static";
	    echo "<tr>\n";
	    echo "<td colspan=2><input type='text' name=css_parts[] id=\"x$this_part\" value=\"$this_name\"></td>\n";
	    echo "<td colspan=2><input type='text' class=\"$showpicker\" name=css_values[] id=\"y$this_part\" value=\"$this_value\"></td>\n";
	    echo "</tr>\n";
	    $focuson = ($focuson == "unknown" ) ? "x$this_part" : $focuson;
	}
    } else {
	echo "<td><input type='text' name=css_parts[] id=css_parts[] value=\"\"></td>\n";
	echo "<td><input type='text' name=css_values[] id=css_values[] value=\"\"></td>\n";
	$focuson = "name";
    }
    echo "<tr>\n";
    echo "<td><input type=button name='abtn' id='abtn' value='Add New' onclick=\"nextaction('1');\"></td>\n";
    echo "<td><input type=button name='sbtn' id='sbtn' value='Update' onclick=\"nextaction('2');\"></td>\n";
    echo "<td><input type=button name='qbtn' id='qbtn' value='Quit' onclick=\"oops();\"></td>\n";
    echo "</tr>\n";
    echo "<tr><td colspan=2><hr/></td></tr>\n";
    echo "<tr><td colspan=2>$statusmessage</td></tr>\n";
    echo "</table>\n";
    setfocus($focuson);
}

///////////////////////////////////////////////////////////////

function updatestyles($name,$section,$type,$theme){
    $show_name  = showgoodname($name);
    $css_name   = ( $type == "class" or $type == "" ) ? ".$show_name" : "#$show_name";
    $csection   = ( $section == "" or $section == "default"  ) ? "" : " and section=\"$section\"";
    $usection   = ( $section == "" or $section == "default"  ) ? "," : ",section=\"$section\",";
    $shandle    = $_SESSION["shandle"];
    $name_list  = $_POST["css_parts"];
    $value_list = $_POST["css_values"];
    $activebrowser = $_SESSION["activebrowser"];
    $theme         = ( $theme == "" ) ? $_SESSION["activetheme"] : $theme;
    $section       = ( $section == "" ) ? $_SESSION["activesection"] : $section;

    //echo "<!-- into update with [$css_name]:[$theme]:[$section] -->\n";
    //echo "<!-- update style with [names]:";print_r($name_list);echo "-->\n";
    //echo "<!-- update style with [values]:";print_r($value_list);echo "-->\n";

    $css_place = 0;
    $statusmessage = "Update Complete";
    foreach( $name_list as $index => $name ){
	$css_place++;
	$value = $value_list[$index];
	//echo "<!-- processing [$index] => [$name]:[$value] -->\n";
	// check for an existing css entry
	$sql = "select corder,css_value from activecss where conditional=\"browser\" and css_name=\"$css_name\" and looking_for=\"$activebrowser\" and theme=\"$theme\" $csection and css_selector=\"$name\" limit 1";
	//echo "<!-- sql[$sql] -->\n";
	$sresult = mysqli_query($shandle,$sql) or die("Cannot talk to active css table");
	$sdata = mysqli_fetch_array($sresult);
	$corder = $sdata["corder"];
	$result = $sdata["css_value"];
	//echo "<!-- result [$corder][$result] -->\n";
	// no corder means insert record otherwise update
	if( $corder < 1 ){
	    $sql = "insert into activecss set conditional=\"browser\", looking_for=\"$activebrowser\",theme=\"$theme\"".$usection."css_name=\"$css_name\",css_selector=\"$name\", css_value=\"$value\",css_place=\"$css_place\"";
	} else {
	    $sql = "update activecss set css_value=\"$value\" where corder=\"$corder\"";
	}
	//echo "<!-- sql[$sql] -->\n";
	$sresult = mysqli_query($shandle,$sql) or die("Cannot talk to activecss");
	$statusmessage = ($sresult === false ) ? "Update Failed" : $statusmessage;
    }
    return $statusmessage;
}

///////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////
?>
