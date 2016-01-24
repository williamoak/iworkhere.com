<?

// functions callable by php or jquery
function jsondispatch(){
    $target = getvardata("target","unknown",99);
    switch( $target ){
	case "browser":
	    $browserval = $_SESSION["browserval"];
	    $browsername = $_SESSION["browsername"];
	    $browsericon = $_SESSION["browsericon"];
	    showcard($browserval,$browsername,$browsericon,"windowbox vbar selectable droppable","onclick=\"browserchange();\"");
	    break;
	case "theme":
	    break;
	case "section":
	    break;
    }
}

//////////////////////////////////////////////////////////////////////////
// proto: showcard($browserval,$browsername,$browsericon,"windowbox vbar selectable droppable","onclick=\"browserchange();\"");
function showcard($objectid,$objectype,$objectname,$objecticon,$objectclass,$objectcallback){
    $objective = "$objectid"."_"."$objectype"."_object";
    echo "		<div id='$objective' name='$objective' class='$objectclass' title=\"$objectname\" $objectcallback>\n";
    echo "			<div class='windowicon'>\n";
    echo "				<img class='inwindowicon' src='./images/$objecticon' title=\"$objectname\">\n";
    echo "			</div>\n";
    echo "			<div class='windowtext'>\n";
    echo "				<center>$objectname</center>\n";
    echo "			</div>\n";
    echo "		</div>\n";
}
//////////////////////////////////////////////////////////////////////////

function stylebox($css_name,$css_info,$classes){
    $show_name = showgoodname($css_name);
    $boxname = "style_$css_name"."_box";
    $activesection = $_SESSION["activesection"];
    $activetheme = $_SESSION["activetheme"];
    $debug   = $_SESSION["debug"];
    $allstylesrbtn = getvardata("allstylesrbtn","no",99);
    //$debug = "true";
    if($debug == "true" ){echo "<!-- in showstyle with [$css_name]:";print_r($css_info);echo "-->\n";}

    $name_prefix = "";
    $source = (isset($css_info["source"])) ? $css_info["source"] : "unknown";
    unset($css_info["source"]);

    $type = (isset($css_info["type"])) ? $css_info["type"] : $name_prefix;
    unset($css_info["type"]);

    $section = (isset($css_info["section"])) ? $css_info["section"] : "default";
    unset($css_info["section"]);

    // section is section, source is theme
    $name_prefix = ( $type == "class" ) ? "." : $name_prefix;
    $name_prefix = ( $type == "id"    ) ? "#" : $name_prefix;
    $insection   = ( $section == $activesection && $source == $activetheme ) ? "insec" : "nonsec";
    //echo "<!-- testing [$section] vs [$activesection] and [$source] vs [$activetheme] => [$insection] -->\n";
    $insection   = ( $section == "system" ) ? "isdefault" : $insection;
    $showstyle   = ( $allstylesrbtn == "no" && $insection == "nonsec" ) ? "noshow" : "";

    echo "		<div name=\"$boxname\" id=\"$boxname\" class=\"$classes $insection $showstyle\">\n";
    echo "			<table border=0 cellspacing=0 cellpadding=0>\n";
    echo "				<tr><td colspan=3 width=170px>$name_prefix"."$show_name</td></tr>\n";
    if( count($css_info) > 0 ){
	foreach( $css_info as $thisplace => $placeinfo ){
	    $name = $placeinfo["name"];
	    $value = $placeinfo["value"];
	    echo "				<tr>\n";
	    echo "					<td width=20px>&nbsp;</td>\n";
	    echo "					<td width=120px;>$name :</td>\n";
	    echo "					<td width=120px>$value</td>\n";
	    echo "				</tr>\n";
	}
    } else {
	echo "				<tr><td colspan=3>Click to add a new style</td></tr>";
    }
    echo "			</table>\n";
    echo "		</div>\n";
}
//////////////////////////////////////////////////////////////////////////

function show_theme_drop_down( $vpos = "top-left" ){
    $shandle = $_SESSION["shandle"];
    $theme   = $_SESSION["theme"];
    $themes  = array();
    $sql     = "select looking_for,theme from activecss where conditional=\"themelist\" order by corder";
    //echo "<!-- sql[$sql] -->\n";
    $sresult = mysqli_query($shandle,$sql) or die("Cannot talk to activecss");
    while( $sdata = mysqli_fetch_array($sresult) ){
	$themeid   = $sdata["looking_for"];
	$themename = $sdata["theme"];
	$themes[$themeid]=$themename;
    }

    echo "		<div name='gtheme' id='gtheme' class='gtheme'>\n";
    echo "			<select name='theme' id='theme' onchange=\"themechange()\">\n";
    foreach( $themes as $thisid => $thisname ){
	//echo "<!-- testing theme [$theme] vs [$thisid] -->\n";
	$selected = ( $theme == $thisid ) ? "SELECTED" : "";
	echo "				<option value=\"$thisid\" $selected>$thisname</option>\n";
    }
    echo "			</select>\n";
    echo "		</div>\n";
}

//////////////////////////////////////////////////////////////////////////

function get_def_theme($debug=false){
    // check for theme in system, or fail over to activecss
    if($debug){echo "<!-- into get_def_theme -->\n";}
    $shandle = $_SESSION["shandle"];
    $sql = "select vvalue from system where vname=\"theme\" limit 1";
    if($debug){echo "<!-- sql[$sql] -->\n";}
    $sresult = mysqli_query($shandle,$sql) or die("Cannot talk to system table");
    $sdata = mysqli_fetch_array($sresult);
    $def_theme = $sdata["vvalue"];
    if( strlen($def_theme) < 1 ){
        $sql = "select theme from activecss where conditional=\"default\" and looking_for=\"browser\" limit 1";
        if($debug){echo "<!-- sql[$sql] -->\n";}
        $sresult = mysqli_query($shandle, $sql) or die("Cannot talk to activecss");
        $sdata = mysqli_fetch_array($sresult);
        $def_theme = $sdata["theme"];
    }
    if($debug){echo "<!-- exiting get_def_theme with [$def_theme] -->\n";}
    return $def_theme;
}

//////////////////////////////////////////////////////////////////////////

function goodname( $name_in ){
    $name_out = $name_in;
    $name_out = str_replace(" ","_space_",$name_out);
    $name_out = str_replace(":","_colon_",$name_out);
    return $name_out;
}

//////////////////////////////////////////////////////////////////////////

function showgoodname( $name_in ){
    $name_out = $name_in;
    $name_out = str_replace("_space_"," ",$name_out);
    $name_out = str_replace("_colon_",":",$name_out);
    return $name_out;
}

//////////////////////////////////////////////////////////////////////////

function update_theme( $newtheme, $debug=false){
    if($debug){echo "<!-- into update_theme with [$newtheme] -->\n";}
    $shandle = $_SESSION["shandle"];
    $sql = "update system set vvalue=\"$newtheme\" where vname=\"theme\"";
    if($debug){echo "<!-- sql[$sql] -->\n";}
    $sresult = mysqli_query($shandle,$sql) or die("Cannot talk to system table");
}
//////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////

?>
