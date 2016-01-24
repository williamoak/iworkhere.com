<?
    // activecss management page
    // display all styles
    // limit by theme, or browser
    // allow overview  by style
    // allow each style to be edited
    // this is a standalone page
    // needs to load all it's infrastructure
    include_once "header.php";

    // Starting page
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
    echo "	<script src='$baseref/jquery/1.11.2/jquery-1.11.2.js'></script>\n";
    echo "	<script src='$baseref/jquery/1.11.2-ui/jquery-ui.js'></script>\n";
    echo "	<link type='text/css' rel='stylesheet' href=\"$baseref/css/activecss/activecss.css\">\n";
    setstyle('/',true,false,$activetheme);
    echo "</head>\n";
    echo "<body class='activebody'>\n";
    echo "	<form name='accform' id='accform' action=\"$scriptname\" method=\"POST\">\n";
    echo "		<input type=hidden name='nextact' id='nextact' value=\"$nextact\">\n";
    echo "		<input type=hidden name='elaps' id='elaps' value=\"$elaps\">\n";
    echo "		<input type=hidden name='activebrowser' id='activebrowser' value=\"$activebrowser\">\n";
    echo "		<input type=hidden name='activetheme' id='activetheme' value=\"$activetheme\">\n";
    echo "		<input type=hidden name='activepage' id='activepage' value=\"$activepage\">\n";
    echo "		<input type=hidden name='activesection' id='activesection' value=\"$activesection\">\n";

    infobox();
    browserspot();
    themedrop();
    pagebox();
    sectionbox();
    showstyles();

    echo "	</form>\n";
    topjava();
    morejava($start);
    echo "</body>\n";
    echo "</html>\n";

//////////////////////////////////////////////////////////////////////////

function topjava(){
    $baseref = $_SESSION["baseref"];
    echo "	<script type='text/javascript'>\n";
    echo "	  function popup_edit( vType ){\n";
    echo "	    vHeight   = 450;\n";
    echo "	    vWidth    = 500;\n";
    echo "	    vTop      = (( screen.height / 2 ) - ( vHeight / 2 ));\n";
    echo "	    vLeft     = (( screen.width  / 2 ) - ( vWidth  / 2 ));\n";
    echo "	    vCrome    = 'hotkeys=no,menubar=no,scrollbars=auto,toolbar=no,resizable=yes,status=no';\n";
    echo "	    vOptions  = 'top='+vTop+',left='+vLeft+',height='+vHeight+',width='+vWidth+','+vCrome;\n";
    echo "	    vCallProg = '$baseref/css/activecss/popupedit.php?target='+vType;\n";
    echo "	    var myNewWin  = window.open( vCallProg, '_blank', vOptions );\n";
    echo "	  }\n";
    echo "	  function trash( ){\n";
    echo "	    var vBrowser = document.forms[0].activebrowser.value;\n";
    echo "	    popup_edit('trash');\n";
    echo "	  }\n";
    echo "	  function browserchange( vBrowser){\n";
    echo "	    document.forms[0].activebrowser.value = vBrowser;\n";
    echo "	    if( vBrowser == 'New Browser' ){\n";
    echo "	      popup_edit('newbrowser');\n";
    echo "	    } else {\n";
    echo "	      document.forms[0].submit();\n";
    echo "	    }\n";
    echo "	  }\n";
    echo "	  function themechange( thisTheme ){\n";
    echo "	    document.forms[0].activetheme.value = thisTheme;\n";
    echo "	    if( thisTheme == 'New Theme' ){\n";
    echo "	      popup_edit('newtheme');\n";
    echo "	    } else {\n";
    echo "	      document.forms[0].submit();\n";
    echo "	    }\n";
    echo "	  }\n";
    echo "	  function pagechange( thisPage ){\n";
    echo "	    document.forms[0].activepage.value = thisPage;\n";
    echo "	    if( thisPage == 'New Page' ){\n";
    echo "	      popup_edit('newpage');\n";
    echo "	    } else {\n";
    echo "	      document.forms[0].submit();\n";
    echo "	    }\n";
    echo "	  }\n";
    echo "	  function sectionchange( thisSection ){\n";
    echo "	    document.forms[0].activesection.value = thisSection;\n";
    echo "	    if( thisSection == 'New Section' ){\n";
    echo "	      popup_edit('newsection');\n";
    echo "	    } else {\n";
    echo "	      document.forms[0].submit();\n";
    echo "	    }\n";
    echo "	  }\n";
    echo "	  function newsession(){\n";
    echo "	    document.forms[0].nextact.value = 'reset';\n";
    echo "	    document.forms[0].submit();\n";
    echo "	  }\n";
    echo "	  function handle_card_drop( event, ui ){\n";
    echo "	    var lz = $(this).data('name');\n";
    echo "	    var vTargets = ui.draggable.data('name');\n";
    echo "	    ui.draggable.draggable('option', 'revert', false);\n";
    echo "	    popup_edit(vTargets+':'+lz);\n";
    echo "	  }\n";
    echo "	  function setallstyles( tVal ){\n";
    echo "	    document.forms[0].allstylesrbtn.value = tVal;\n";
    echo "	    document.forms[0].submit();\n";
    echo "	  }\n";
//    echo "	  function show_targets( event, ui ){\n";
//    echo "	  }\n";
    echo "	</script>\n";
}

//////////////////////////////////////////////////////////////////////////

function morejava($start){
    $endtime = time();
    $elaps = $start - $endtime;
    $debug = $_SESSION["debug"];
    $debug = "true";
    // make browserlist droppable
    $browsers = $_SESSION["browserlist"];
    $themes   = $_SESSION["themelist"];
    $pages    = $_SESSION["pagelist"];
    $sections = $_SESSION["sectionlist"];
    $styles   = $_SESSION["showstyles"];
    echo "<!-- pages:";print_r($pages);echo "-->\n";

    echo "	<script type='text/javascript'>\n";
    echo "	$(function(){\n";
    echo "	  $(\"#trash_browser_object\")\n";
    echo "	    .droppable({\n";
    echo "	      accept: '.istheme, .isection, .stylebox, .ispage',\n";
    echo "	      activeClass: 'isdragging',\n";
    echo "	      hoverClass: 'candrop',\n";
    echo "	      drop: handle_card_drop\n";
    echo "	    })\n";
    echo "	    .data('name','trash')\n";

    echo "<!-- declaring droppable browser objects -->\n";
    foreach( $browsers as $thisbrowser => $info ){
	$dropname = "$thisbrowser"."_browser_object";
	echo "	  $(\"#$dropname\")\n";
	echo "	    .droppable({\n";
	echo "	      accept: '.istheme, .isection, .stylebox, .ispage',\n";
	echo "	      activeClass: 'isdragging',\n";
	echo "	      hoverClass: 'candrop',\n";
	echo "	      drop: handle_card_drop\n";
	echo "	    })\n";
	echo "	    .data('name','$dropname');\n";
    }

    echo "<!-- declaring droppable theme objects -->\n";
    foreach( $themes as $thistheme => $info ){
	$dropname = $thistheme."_theme_object";
	echo "	  $(\"#$dropname\")\n";
	echo "	    .droppable({\n";
	echo "	      accept: '.isection, .stylebox, .ispage',\n";
	echo "	      activeClass: 'isdragging',\n";
	echo "	      hoverClass: 'candrop',\n";
	echo "	      drop: handle_card_drop\n";
	echo "	    })\n";
	echo "	    .data('name','$dropname');\n";
    }

    echo "<!-- declaring droppable page objects -->\n";
    foreach( $pages as $thispage => $info ){
	$dropname = $thispage."_page_object";
	echo "	  $(\"#$dropname\")\n";
	echo "	    .droppable({\n";
	echo "	      accept: '.isection, .stylebox',\n";
	echo "	      activeClass: 'isdragging',\n";
	echo "	      hoverClass: 'candrop',\n";
	echo "	      drop: handle_card_drop\n";
	echo "	    })\n";
	echo "	    .data('name','$dropname');\n";
    }

    echo "<!-- declaring droppable section objects -->\n";
    foreach( $sections as $thissection => $info ){
	$dropname = $thissection."_section_object";
	echo "	  $(\"#$dropname\")\n";
	echo "	    .droppable({\n";
	echo "	      accept: '.stylebox',\n";
	echo "	      activeClass: 'isdragging',\n";
	echo "	      hoverClass: 'candrop',\n";
	echo "	      drop: handle_card_drop\n";
	echo "	    })\n";
	echo "	    .data('name','$dropname');\n";
    }

    echo "<!-- declaring draggable theme objects -->\n";
    foreach( $themes as $thistheme => $info ){
	$thisbox  = "$thistheme"."_theme_object";
	$callback = "themechange('$thistheme')";
	dragme($thisbox,$callback);
    }

    echo "<!-- declaring draggable page objects -->\n";
    foreach( $pages as $thispage => $info ){
	$thisbox  = "$thispage"."_page_object";
	$callback = "pagechange('$thispage')";
	dragme($thisbox,$callback);
    }

    echo "<!-- declaring draggable section objects -->\n";
    foreach( $sections as $thissection => $info ){
	$thisbox ="$thissection"."_section_object";
	$callback = "sectionchange('$thissection')";
	dragme($thisbox,$callback);
    }

    //$debug="true";
    //if($debug == "true"){echo "<!-- session:";print_r($styles);echo "-->\n";}
    echo "<!-- declaring selectable style objects -->\n";
    foreach( $styles as $thistyle => $info ){
	$thisbox ="style_$thistyle"."_box";
	$callback = "popup_edit('$thisbox')";
	dragme($thisbox,$callback);
    }

    echo "	  $(\"#style_newstyle_box\").click(function(){popup_edit('newstyle');});\n";
    echo "	  $(\"#elaps\").value($elaps);\n";
    echo "	});\n";
    echo "	</script>\n";
}

//////////////////////////////////////////////////////////////////////////

function dragme($thisbox,$callback){
	echo "	  $(\"#$thisbox\")\n";
	echo "	    .draggable({\n";
	echo "	      containment: \"document\",\n";
	echo "	      appendTo: 'body',\n";
	echo "	      scroll: false,\n";
	echo "	      helper: 'clone',\n";
	echo "	      cursor: 'move',\n";
	echo "	      revert: true\n";
	echo "	    })\n";
	echo "	    .data('name','$thisbox')\n";
	echo "	    .click(function(){;\n";
	echo "	      if( $(this).is('.ui-draggable-dragging')){\n";
	echo "	        return;\n";
	echo "	      }\n";
	echo "	      $callback;\n";
	echo "	    });\n";
}

//////////////////////////////////////////////////////////////////////////

function infobox(){
    $rnd       = genkey(5);
    $baseref   = $_SESSION["baseref"];
    $allstylesrbtn = getvardata("allstylesrbtn","no",99);
    $checkyes  = ( $allstylesrbtn == "yes" ) ? "checked" : "";
    $checkno   = ( $allstylesrbtn == "no"  ) ? "checked" : "";
    //echo "<!-- allstylesrbtn[$allstylesrbtn]-->\n";

    echo "	<div name='tbox' id='tbox' class='topbox'>\n";
    echo "		<table border=0 cellpadding=0 cellspacing=0><tr>\n";
    echo "<td>activecss @ $baseref<span id='xxx'></span></td>";
    echo "<td> &nbsp; </td>";
    echo "<td>Show all Styles?</td>";
    echo "<td> &nbsp; </td>";
    echo "<td onclick=\"setallstyles('yes');\" class=\"rbtn midstyle\">Yes:<input type=radio name='allstylesrbtn' id='allstylesrbtn[yes]' value='yes' $checkyes></td>";
    echo "<td onclick=\"setallstyles('no');\" class=\"rbtn midstyle\">No:<input type=radio name='allstylesrbtn'  id='allstylesrbtn[no]'  value='no' $checkno></td>";
    echo "<td> &nbsp; </td>";
    echo "<td>[$rnd]</td>";
    echo "</tr></table>\n";
    echo "	</div>\n";
}

//////////////////////////////////////////////////////////////////////////

function browserspot(){
    $browserlist = array();
    $shandle = $_SESSION["shandle"];
    $debug   = $_SESSION["debug"];
    $activebrowser = getvardata("activebrowser","msie",99);
    if( $debug == "true" ){echo "<!-- activebrowser[$activebrowser]-->\n";}
    $sql = "select looking_for,css_name,css_selector from activecss where conditional=\"browserlist\" order by corder";
    if($debug == "true"){echo "<!-- sql[$sql] -->\n";}
    $sresult = mysqli_query($shandle,$sql) or die("Cannot talk to activecss: ");
    while( $sdata = mysqli_fetch_array($sresult)){
	$thisbrowser = $sdata["looking_for"];
	$thisname    = $sdata["css_name"];
	$thisicon    = $sdata["css_selector"];
	$browserlist[$thisbrowser]["name"]=$thisname;
	$browserlist[$thisbrowser]["icon"]=$thisicon;
	$_SESSION["browserlist"][$thisbrowser]["name"]=$thisname;
	$_SESSION["browserlist"][$thisbrowser]["icon"]=$thisicon;
    }
    // now we have a list of known browsers
    if($debug == "true"){echo "<!-- browsers:";print_r($_SESSION["browserlist"]);echo "-->\n";}
    $classlist = "windowbox vbar";
    echo "	<div name='browserlist' id='browserlist' class='bboxdiv'>\n";
    showcard("trash","browser","Recycle Bin","trash.png","$classlist trash droppable","");
    foreach( $browserlist as $thisbrowser => $browserinfo){
	if($debug == "true"){echo "<!-- testing [$thisbrowser] vs [$activebrowser] -->\n";}
	$selected    = ( $thisbrowser == $activebrowser ) ? "isactive" : "inactive";
	$browsername = $browserinfo["name"];
	$browsericon = $browserinfo["icon"];
	showcard($thisbrowser,"browser",$browsername,$browsericon,"$classlist isbrowser selectable droppable $selected","onclick=\"browserchange('$thisbrowser');\"");
    }
    showcard("new","browser","New Browser","allbrowsers.gif","$classlist selectable","onclick=\"browserchange('New Browser');\"");
    echo "	</div>\n";
}

//////////////////////////////////////////////////////////////////////////

function themedrop(){
    $themelist   = array();
    $shandle     = $_SESSION["shandle"];
    $debug       = $_SESSION["debug"];
    $activetheme = getvardata("activetheme","default",99);
    $sql = "select looking_for,theme,css_selector from activecss where conditional=\"themelist\" group by looking_for";
    if($debug == "true" ){echo "<!-- theme sql[$sql] -->\n";}
    $sresult = mysqli_query($shandle,$sql) or die("Cannot talk to acive css table");
    while( $sdata = mysqli_fetch_array($sresult)){
	$thistheme = $sdata["looking_for"];
	$thisname  = $sdata["theme"];
	$thisicon  = $sdata["css_selector"];
	$themelist[$thistheme]["name"]=$thisname;
	$themelist[$thistheme]["icon"]=$thisicon;
	$_SESSION["themelist"][$thistheme]["name"]=$thisname;
	$_SESSION["themelist"][$thistheme]["icon"]=$thisicon;
    }
    if($debug == "true"){echo "<!-- themes:";print_r($_SESSION["themelist"]);echo "-->\n";}
    $classlist = "windowbox hbar";

    echo "	<div name='themelist' id='themelist' class='tboxdiv'>\n";
    showcard("new","theme","New Theme","themes.gif","$classlist selectable","onclick=\"themechange('New Theme');\"");
    foreach( $themelist as $thistheme => $themeinfo) {
	if($debug == "true"){echo "<!-- testing [$thistheme] vs [$activetheme] -->\n";}
	$selected    = ( $thistheme == $activetheme ) ? "isactive" : "inactive";
	$themename = $themeinfo["name"];
	$themeicon = $themeinfo["icon"];
	showcard($thistheme,"theme",$themename,$themeicon,"$classlist istheme selectable draggable droppable $selected","");
    }
    echo "	</div>\n";
}

//////////////////////////////////////////////////////////////////////////

function pagebox(){
    $classlist   = "windowbox hbar";
    $isdefault   = "";
    $pages       = 0;
    $pagelist    = array();
    $activetheme = $_SESSION["activetheme"];
    $activepage  = $_SESSION["activepage"];
    $shandle     = $_SESSION["shandle"];

    // get a list of pages for this theme
    $pagelist["default"]["name"]="All Pages";
    $pagelist["default"]["icon"]="sections.gif";

    $sql = "select activecss.page, looking_for, css_selector from activecss where ( conditional=\"pagelist\" and theme=\"$activetheme\" )";
    //echo "<!-- sql[$sql] -->\n";
    $sresult = mysqli_query($shandle,$sql) or die("Cannot talk to activecss table");
    while( $sdata = mysqli_fetch_array($sresult)){
	$thispage = $sdata["looking_for"];
	$thisname = $sdata["page"];
	$pageicon = $sdata["css_selector"];
	$pagelist[$thispage]["name"]=$thisname;
	$pagelist[$thispage]["icon"]=$pageicon;
    }
    echo "	<div name='pagelist' id='pagelist' class='pboxdiv'>\n";
    showcard("new","page","New Page","sections.gif","$classlist ispage selectable","onclick=\"pagechange('New Page');\"");

    foreach( $pagelist as $thispage => $pageinfo ){
	$pagename = $pageinfo["name"];
	$pageicon = $pageinfo["icon"];
	$_SESSION["pagelist"][$thispage]["name"]=$pagename;
	$_SESSION["pagelist"][$thispage]["icon"]=$pageicon;
	$selected = ( $thispage == $activepage ) ? "isactive" : "";
	showcard($thispage,"page",$pagename,$pageicon,"$classlist ispage draggable droppable selectable $selected","onclick=\"pagechange('$thispage');\"");
    }
    echo "	</div>\n";
}

//////////////////////////////////////////////////////////////////////////

function sectionbox(){
    $sectionlist = array();
    $shandle = $_SESSION["shandle"];
    $debug   = $_SESSION["debug"];
    $activesection = getvardata("activesection","default",99);
    if( $debug == "true" ){echo "<!-- activesection[$activesection]-->\n";}
    $sql = "select looking_for,theme,css_selector from activecss where conditional=\"sectionlist\" order by looking_for";
    if($debug == "true"){echo "<!-- section sql[$sql] -->\n";}
    $sresult = mysqli_query($shandle,$sql) or die("Cannot talk to activecss: ");
    while( $sdata = mysqli_fetch_array($sresult)){
	$thissection = $sdata["looking_for"];
	$thisname    = $sdata["theme"];
	$thisicon    = $sdata["css_selector"];
	$sectionlist[$thissection]["name"]=$thisname;
	$sectionlist[$thissection]["icon"]=$thisicon;
	$_SESSION["sectionlist"][$thissection]["name"]=$thisname;
	$_SESSION["sectionlist"][$thissection]["icon"]=$thisicon;
    }
    // now we have a list of known browsers
    if($debug == "true"){echo "<!-- sections:";print_r($_SESSION["sectionlist"]);echo "-->\n";}
    $classlist = "windowbox hbar";

    echo "	<div name='sectionbox' id='sectionbox' class='eboxdiv'>\n";
    showcard("new","section","New Section","sections.gif","$classlist selectable","onclick=\"sectionchange('New Section');\"");
    foreach( $sectionlist as $thissection => $sectioninfo) {
	if($debug == "true"){echo "<!-- testing [$thissection] vs [$activesection] -->\n";}
	$selected    = ( $thissection == $activesection ) ? "isactive" : "inactive";
	$sectionname = $sectioninfo["name"];
	$sectionicon = $sectioninfo["icon"];
	showcard($thissection,"section",$sectionname,$sectionicon,"$classlist isection selectable draggable $selected","");
    }
    echo "	</div>\n";
}
//////////////////////////////////////////////////////////////////////////

function showstyles(){
    $allstyles     = array();
    $substyles     = array();
    $dummy         = array("type"=>"","source"=>"default","section"=>"system");
    $shandle       = $_SESSION["shandle"];
    $debug         = $_SESSION["debug"];
    $activebrowser = $_SESSION["activebrowser"];
    $activetheme   = $_SESSION["activetheme"];
    $activesection = $_SESSION["activesection"];
    $defbrowser    = $_SESSION["defbrowser"];
    $deftheme      = $_SESSION["deftheme"];
    $defsection    = $_SESSION["defsection"];
    //$debug = "true";

    $sql = "select looking_for,theme,section,css_name,css_selector,css_value,css_place from activecss where conditional=\"browser\" order by css_name";
    if($debug == "true"){echo "<!-- sql[$sql] -->\n";}
    $sresult = mysqli_query($shandle,$sql) or die("Cannot talk to acctive css table");
    $seltype = "unknown";
    while( $sdata     = mysqli_fetch_array($sresult)){
	$cbrowser     = $sdata["looking_for"];
	$theme        = $sdata["theme"];
	$section      = $sdata["section"];
	$css_name     = $sdata["css_name"];
	$css_name     = goodname($css_name);
	$css_selector = $sdata["css_selector"];
	$css_value    = $sdata["css_value"];
	$css_place    = $sdata["css_place"];
	$selector_type = substr($css_name,0,1);
	$css_name      = substr($css_name,1);
	$seltype      = ( $selector_type == "." ) ? "class" : $seltype;
	$seltype      = ( $selector_type == "#" ) ? "id"    : $seltype;
	$allstyles[$cbrowser][$theme][$css_name][$css_place]["name"]    = $css_selector;
	$allstyles[$cbrowser][$theme][$css_name][$css_place]["value"]   = $css_value;
	$allstyles[$cbrowser][$theme][$css_name]["section"]             = $section;
	$allstyles[$cbrowser][$theme][$css_name]["type"]                = $seltype;
	$_SESSION["stylelist"][$cbrowser][$theme][$css_name][$css_place]["name"]    = $css_selector;
	$_SESSION["stylelist"][$cbrowser][$theme][$css_name][$css_place]["value"]   = $css_value;
	$_SESSION["stylelist"][$cbrowser][$theme][$css_name]["section"]             = $section;
	$_SESSION["stylelist"][$cbrowser][$theme][$css_name]["type"]                = $seltype;
    }

    if($debug == "true"){echo "<!-- allstyles:";print_r($allstyles);echo "-->\n";}
    foreach( $allstyles as $thisbrowser => $browserinfo ){
	// we have msie, firefox, etc....
	if($debug == "true" ){echo "<!-- processing style [$thisbrowser]:";print_r($browserinfo);echo "-->\n";}
	foreach( $browserinfo as $thistheme => $themeinfo ){
	    // we have default, minimal, etc
	    if($debug == "true" ){echo "<!-- processing theme [$thistheme]:";print_r($themeinfo);echo "-->\n";}
	    foreach( $themeinfo as $thistyle => $styleinfo ){
		if($debug == "true" ){echo "<!-- processing styles [$thisbrowser][$thistheme][$thistyle]:";print_r($styleinfo);echo "-->\n";}
		if( $thisbrowser == $defbrowser and $thistheme == $deftheme ){
		    if($debug == "true" ){echo "<!-- inside [$thisbrowser][$thistheme][$thistyle] -->\n";}
		    if($debug == "true" ){echo "<!-- this is the default it can be overwritten -->\n";}
		    if( !array_key_exists($thistyle,$substyles) ) {
			if($debug == "true" ){echo "<!-- adding [$thistyle] -->\n";}
			$substyles[$thistyle]=$styleinfo;
			$substyles[$thistyle]["source"]=$thistheme;
		    } else {
			if($debug == "true" ){echo "<!-- key exists, not writing style for [$thisbrowser][$thistheme] -->\n";}
		    }
		} else {
		    if( $thisbrowser == $activebrowser and $thistheme == $activetheme ){
			if($debug == "true" ){echo "<!-- inside [$thisbrowser][$thistheme][$thistyle] -->\n";}
			if($debug == "true" ){echo "<!-- this is NOT the default it can NOT be overwritten -->\n";}
			if($debug == "true" ){echo "<!-- testing for [$thistyle] in :";print_r($substyles);echo "-->\n";}
			if($debug == "true" ){echo "<!-- adding [$thistyle] -->\n";}
			$substyles[$thistyle]=$styleinfo;
			$substyles[$thistyle]["source"]=$thistheme;
		    } else {
			if($debug == "true" ){echo "<!-- unmatched inside [$thisbrowser] vs [$defbrowser] : [$thistheme] vs [$thistheme] : [$thistyle]-->\n";}
		    }
		}
	    }
	}
    }
    if($debug == "true" ){echo "<!-- substyles:";print_r($substyles);echo "-->\n";}
    foreach($substyles as $thistyle => $styleinfo){
	//echo "<!-- processing [$thistyle]:";print_r($styleinfo);echo "-->\n";
	$_SESSION["showstyles"][$thistyle]=$styleinfo;
    }

    echo "	<div name='stylebox' id='stylebox' class='sboxdiv'>\n";
    //echo "		styles:<br/>\n";
    $classes = "stylebox hbar selectable draggable";
    stylebox("newstyle",$dummy,"stylebox hbar selectable isdefault");
    if( count($substyles) > 0 ){
	foreach( $substyles as $css_name => $css_info){
	    stylebox($css_name,$css_info,$classes);
	}
    }
    echo "	</div>\n";
}
//////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////

?>
