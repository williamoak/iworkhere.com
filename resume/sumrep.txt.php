<?
    /////////////////////////////////////
    // Summary.php
    // May 7, 2014
    // show journal summary for a period for a set of courses
    // with appropriate granularity and visability
    /////////////////////////////////////
    // files in this ReMuS update are:
    // /localremus/cgi/baselib.pm
    // /localremus/reports/css/style.css
    // /localremus/php/altlib.php
    // /localremus/php/baselib.php
    // /localremus/php/config.php
    // /localremus/php/summary.php
    // /localremus/php/sumrep.php
    // /localremus/reports/sumreport.php
    // /localremus/reports/sumreport.xml
    // /localremus/php/outstanding.php <- url fix
    /////////////////////////////////////

    include_once "config/altlib.php";
    $today        = date("m/d/Y");
    $tstamp       = strtotime($today);
    $btnvals      = array("custom","day","week","month","quarter","half","year");
    $webname      = $_SERVER["PHP_SELF"];
    $baseref      = sysval("baseref",$shandle);
    //$shandle      = db_connect();
    $username     = get_content("username",$shandle);
    $truename     = get_content("tname",$shandle);
    $tablename    = "uT$username";

    // getvardata uses default mysql-injection checking
    // since all POST variables are from an authenticated user
    // no data is from arbitrary web-facing entities
    $nextact      = getvardata("nextact","xview");
    $btncheck     = getvardata("btncheck","false");
    $xBtn         = getvardata("xBtn",$btnvals);
    $fromdate     = getvardata("fromdate","$today");
    $todate       = getvardata("todate","$today");
    $uset         = getvardata("uset","unknown");
    $utype        = getvardata("utype","");
    $ucourse      = getvardata("ucourse","");
    $topcourse    = getvardata("topcourse","");
    $boxopen      = getvardata("boxopen","");
    $boxtop       = getvardata("boxtop","");
    $firstloop    = getvardata("firstloop","yes");
    $cfrom        = getvardata("cfrom","to");

    $uset = ( $uset == "unknown" ) ? getdefset($shandle) : $uset;
    if( $todate == "unknown"){$todate = $today;}
    if( $btncheck == "false" ){$xBtn="custom";}

    // set DEFAULT values for first time page is loaded
    if( $firstloop == "yes" ){
	$xBtn = "month";
	$btncheck = "xBtn";
	$_POST["xBtn"] = $xBtn;
	$_POST["btncheck"] = $btncheck;
	$_POST["cfrom"] = $cfrom;
	$_POST["uset"] = $uset;
    }

    $defrom    = getstartdate();
    $_POST["defrom"] = $defrom;
    //echo "<!-- before [$btncheck] [$cfrom] [$defrom] [$fromdate] [$todate] -->\n";
    $todate    = ( $cfrom == "from"   && $btncheck == "xBtn" ) ? date("m/d/Y",strtotime($defrom,strtotime($fromdate))) : $todate;
    $fromdate  = ( $cfrom == "to" && $btncheck == "xBtn" ) ? date("m/d/Y",strtotime($defrom,strtotime($todate))) : $fromdate;
    //echo "<!-- after [$fromdate] [$todate] -->\n";
    $_POST["fromdate"] = $fromdate;
    $_POST["todate"]   = $todate;

//    echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">\n";
    echo "<!doctype html>\n";
    echo "<HTML>\n";
    echo "<HEAD>\n";
    //echo "<!-- username[$username] -->\n";
    echo "<meta name=\"msapplication-config\" content=\"none\"/>\n";
    echo "<title>Period Summary Report Generator</title>\n";
    echo "<link REL=STYLESHEET HREF=\"./jquery/css/ui-lightness/jquery-ui-1.10.4.custom.css\" TYPE='text/css'>\n";
    set_style($baseref);
    setstyle("/");
    echo "<script src='./jquery/js/jquery-1.10.2.js'></script>\n";
    echo "<script src='./jquery/js/jquery-ui-1.10.4.custom.js'></script>\n";
    echo "<style>\n";
    echo "	.ui-datepicker {\n";
    echo "	  margin-left:-100px;\n";
    echo "	  z-index: 1000;\n";
    echo "	  font-size:12px;\n";
    echo "	}\n";
    echo "</style>\n";
    echo "</HEAD>\n";
    echo "<body class='main_box'>\n";
    echo "	<form name='iform' id='iform' action='$webname' method='POST'>\n";
    echo "		<input type=hidden name='nextact' id='nextact' value='$nextact'>\n";
    echo "		<input type=hidden name='btncheck' id='btncheck' value='$btncheck'>\n";
    echo "		<input type=hidden name='firstloop' id='firstloop' value='no'>\n";
    echo "		<input type=hidden name='cfrom' id='cfrom' value='$cfrom'>\n";
    echo "		<input type=hidden name='uset' id='uset' value='$uset'>\n";
    echo "		<input type=hidden name='utype' id='utype' value='$utype'>\n";
    echo "		<input type=hidden name='ucourse' id='ucourse' value='$ucourse'>\n";
    echo "		<input type=hidden name='boxopen' id='boxopen' value='false'>\n";
    echo "		<input type=hidden name='boxtop' id='boxtop' value='$boxtop'>\n";
    echo "		<input type=hidden name='topcourse' id='topcourse' value='$topcourse'>\n";
    echo "		<div name='pselect' id='pselect' class='topdiv'>\n";
    echo "			<table border=1 class='toptable'>\n";
    echo "			<tr>\n";
    echo "				<td width=62%>Select the Start and End Dates for your report:</td>\n";
    echo "				<td width=38%>Select the Courses to report on:</td>\n";
    echo "			</tr>\n";
    echo "			<tr>\n";
    echo "				<td width=62%>\n";
    echo "					Select Start Date: &nbsp; <input type=text name='fromdate' id='fromdate' value='$fromdate' size=7> &nbsp;\n";
    echo "					Select End Date: &nbsp; <input type=text name='todate' id='todate' value='$todate' size=7>\n";
    echo "				</td>\n";
    echo "				<td width=38%>\n";
					    courset($shandle);
    echo "				</td>\n";
    echo "			</tr>\n";
    echo "			<tr>\n";
    echo "				<td>\n";
    echo "					<table><tr>\n";

    foreach( $btnvals as $thisbtn ){
	$selected = ( $thisbtn == $xBtn ) ? "CHECKED" :"";
	$ttitle = ucfirst($thisbtn);
	echo "					<td class='selectme' title=\"Click to select $ttitle\" onclick=\"document.getElementById('$thisbtn').checked=true;radiocheck('xBtn');\">\n";
	echo "					$ttitle <input type=radio name=xBtn id=$thisbtn value='$thisbtn' onchange=\"radiocheck('xBtn');\" $selected> &nbsp;\n";
    }

    echo "					</tr></table>\n";
    echo "				</td>\n";
					listcourses($shandle);
    echo "			</tr>\n";
    echo "			</table>\n";
    echo "		</div>\n";
    echo "		<div name='mbox' id='mbox' class='centerbox'>\n";
			    include "sumrep.php";
    echo "		</div>\n";
    echo "		<div name='bbox' id='bbox' class='bottombox'>\n";
    echo "			<input type=button name='ubtn' id='ubtn' value=\"Update\" onclick=\"np('xupdate');\"> &nbsp; \n";
    echo "			<input type=button name='qbtn' id='qbtn' value=\"Quit\" onclick=\"self.close();\">\n";
    echo "			&nbsp;<span id='msg' name='msg'>&nbsp;</span>\n";
    echo "		</div>\n";
    echo "	</form>\n";
    echo "</body>\n";

    echo "<script type='text/javascript'>\n";
    echo " function np( vNext ){\n";
    echo "   document.forms[0].nextact.value = vNext;\n";
    echo "   document.forms[0].submit();\n";
    echo " }\n";
    echo " function setcourse(){\n";
    echo "   document.forms[0].uset.value = document.forms[0].tset.value;\n";
    echo "   document.forms[0].submit();\n";
    echo " }\n";
    echo " function changecourse(){\n";
    echo "   document.forms[0].ucourse.value = document.forms[0].tcourse.value;\n";
    echo "   document.forms[0].submit();\n";
    echo " }\n";
    echo " function typechange(){\n";
    echo "   document.forms[0].utype.value = document.forms[0].ttype.value;\n";
    echo "   document.forms[0].submit();\n";
    echo " }\n";
    echo " function radiocheck( btnValue ){\n";
    echo "   document.forms[0].btncheck.value=btnValue;\n";
    echo "   if( btnValue == 'false' ){\n";
    echo "     document.getElementById('custom').checked='true';\n";
    echo "   }\n";
    echo "   document.forms[0].submit();\n";
    echo " }\n";
    echo " function vswitch( divId, vDir ){\n";
    echo "   document.getElementById( divId ).style.display=vDir;\n";
    echo " }\n";
    echo " function mswitch( oId, oType ){\n";
    echo "   document.getElementById( oId ).className=oType;\n";
    echo "   bTop = oId;\n";
    echo " }\n";
    echo " function radioflip( oId ){\n";
    echo "   document.getElementById( oId ).checked=true;\n";
    echo "   document.forms[0].submit();\n";
    echo " }\n";
    echo " function pickchange( cId ){\n";
    echo "   uId = '_'+cId;\n";
    echo "   bId = 'x'+cId;\n";
    echo "   xId = document.getElementById(uId).checked;\n";
    echo "   nId = ( xId == true ) ? false : true;\n";
    echo "   nBg = ( xId == true ) ? 'oclass' : 'iclass';\n";
    echo "   document.getElementById(uId).checked=nId;\n";
    echo "   document.getElementById(bId).className=nBg;\n";
    echo "   document.forms[0].boxopen.value = true;\n";
    echo "   document.forms[0].boxtop.value  = document.getElementById('seldiv').scrollTop;\n";
    echo "   document.forms[0].submit();\n";
    echo " }\n";
    echo "$(function() {\n";
    echo "  $( '#fromdate' ).datepicker({\n";
    echo "    defaultDate: \"$fromdate\",\n";
    echo "    changeMonth: true,\n";
    echo "    changeYear: true,\n";
    echo "    numberOfMonths: 1,\n";
    echo "    dateFormat: 'mm/dd/yy',\n";
    echo "    onClose: function( selectedDate ) {\n";
    echo "      $('#todate').datepicker('option','minDate',selectedDate);\n";
    echo "      document.forms[0].cfrom.value='from';\n";
    echo "      document.forms[0].btncheck.value='false';\n";
    echo "      document.forms[0].submit();\n";
    echo "    }\n";
    echo "  });\n";
    echo "  $( '#todate' ).datepicker({\n";
    echo "    defaultDate: \"$todate\",\n";
    echo "    changeMonth: true,\n";
    echo "    changeYear: true,\n";
    echo "    numberOfMonths: 1,\n";
    echo "    dateFormat: 'mm/dd/yy',\n";
    echo "    onClose: function( selectedDate ) {\n";
    echo "      $('#fromdate').datepicker('option','maxDate',selectedDate);\n";
    echo "      document.forms[0].cfrom.value='to';\n";
    echo "      document.forms[0].btncheck.value='false';\n";
    echo "      document.forms[0].submit();\n";
    echo "    }\n";
    echo "  });\n";
    echo "});\n";
    echo "if('$boxopen' == 'true'){\n";
    echo "$(function(){\n";
    echo "  $(\"#seldiv\").scrollTop($boxtop);\n";
    echo "});\n";
    echo "}\n";
    echo "</script>\n";
    echo "</html>\n";

//////////////////////////////////////////////////////////
// functions from here to end
//////////////////////////////////////////////////////////
//echo "function callrep(oId){\n";
//$ovar = "";
//foreach( $_POST as $thisvar => $thisval ){$ovar .= "'$thisvar':'$thisval',";}
//$ovar = xnotrailing($ovar,",");
//echo "  $(function(){\n";
//echo "    $('#mbox').load('sumrep.php',{".$ovar."});\n";
//echo "    $('#mbox').load('sumrep.php',{'granularity':'oId'});\n";
//echo "  });\n";
//echo "}\n";
//////////////////////////////////////////////////////////

function getstartdate(){
    $outvar   = "unknown";
    $btncheck = getvardata("btncheck","false");
    $cfrom    = getvardata("cfrom","");
    $xBtn     = getvardata("xBtn","");
    $btndir = ( $cfrom == "from" ) ? "+" : "-";
    //echo "<!-- cfrom[$cfrom] btncheck[$btncheck] xBtn[$xBtn] -->\n";
    if( $btncheck == "xBtn" ){
	switch($xBtn){
	    case "day":
		$outvar = $btndir."1 day";
		break;
	    case "week":
		$outvar = $btndir."1 week";
		break;
	    case "month":
		$outvar = $btndir."1 month";
		break;
	    case "quarter":
		$outvar = $btndir."3 months";
		break;
	    case "half":
		$outvar = $btndir."6 months";
		break;
	    case "year":
		$outvar = $btndir."1 year";
		break;
	}
    } else {
	$outvar = "+0 days";
    }
    //echo "<!-- returning [$outvar] -->\n";
    return $outvar;
}

//////////////////////////////////////////////////////////
//    echo "function callrep(){\n";
//    $ovar = "";
//    foreach( $_POST as $thisvar => $thisval ){$ovar .= "'$thisvar':'$thisval',";}
//    $ovar = notrailing($ovar,",");
//    echo "$('#mbox').load('sumrep.php',{".$ovar."});}\n";
//    echo "}\n";
//////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////

function getdefset($shandle){
    $sql = "select value from system where varname='defaultset'";
    //echo "<!-- sql[$sql] -->\n";
    $sresult = mysql_query($sql,$shandle) or die("cannot connect to system table");
    $sdata = mysql_fetch_array($sresult);
    $sysval = $sdata["value"];
    return $sysval;
}
//////////////////////////////////////////////////////////

function courset($shandle){
    $uset    = getvardata("uset","");
    $utype   = getvardata("utype","");
    $ctypes  = array("all courses","one course","some courses");
    $outlist = "";
    $sql = "select setname,comment from coursets order by corder";
    //echo "<!-- sql[$sql] -->\n";
    $sresult = mysql_query($sql,$shandle) or die("cannot connect to coursets");
    while( $sdata = mysql_fetch_array($sresult)){
	$sname = $sdata["setname"];
	$scom  = $sdata["comment"];
	$outlist[$sname] = $scom;
    }

    echo "					<select name=\"tset\" id=\"tset\" onchange=\"setcourse()\">\n";

    foreach( $outlist as $thisname => $thisval ){
	$selected = ( $thisname == $uset ) ? "SELECTED" : "";
	echo "						<option value=\"$thisname\" $selected>$thisval</option>\n";
    }

    echo "					</select>\n";
    echo " &nbsp; \n";
    echo "					<select name='ttype' id='ttype' onchange='typechange()'>\n";

    foreach( $ctypes as $thistype ){
	$selected = ( $thistype == $utype ) ? "SELECTED" : "";
	echo "						<option value='$thistype' $selected>$thistype</option>\n";
    }

    echo "					</select>\n";
    return;
}
//////////////////////////////////////////////////////////

function listcourses($shandle){
    $utype = getvardata("utype","");
    switch ($utype){
	case "all courses":
	    echo "				<td>\n";
	    echo "All courses Selected\n";
	    break;
	case "one course":
	    echo "				<td>\n";
	    selectone($shandle);
	    break;
	case "some courses":
	    echo "				<td onmouseover=\"vswitch('seldiv','block');\">\n";
	    selectsome($shandle);
	    break;
    }
    echo "				</td>\n";
}
//////////////////////////////////////////////////////////

function selectone($shandle){
    $uset    = getvardata("uset","");
    $ucourse = getvardata("ucourse","");
    $outlist = "";
    $topcourse = "unknown";
    $sql = "select plan_name,description from client_plan where plan_set=\"$uset\" order by plan_id";
    //echo "<!-- sql[$sql] -->\n";
    $sresult = mysql_query($sql,$shandle) or die("Cannot connect to client_plan");
    while( $sdata = mysql_fetch_array($sresult)){
	$tid   = $sdata["plan_name"];
	$tdes  = $sdata["description"];
	$outlist[$tid]=$tdes;
    }
    //echo "<!-- course array:\n";print_r($outlist);echo " ---------------------- -->\n";
    echo "					<select name=\"tcourse\" id=\"tcourse\" onchange=\"changecourse()\">\n";
    foreach( $outlist as $thiscourse => $coursedata){
	$topcourse = ( $topcourse == "unknown" ) ? $thiscourse : $topcourse;
	$thisdes  = $coursedata;
	$selected = ( $thiscourse == $ucourse ) ? "SELECTED" : "";
	echo "						<option value=\"$thiscourse\" $selected>$thisdes</option>\n";
    }
    echo "					</select>\n";
    echo "<script type='text/javascript'>document.forms[0].topcourse.value='$topcourse';</script>\n";
    $_POST["topcourse"]=$topcourse;
}
//////////////////////////////////////////////////////////

function selectsome($shandle){
    $uset    = getvardata("uset","");
    $boxopen = getvardata("boxopen","");
    $courselist = (isset($_POST["courselist"])) ? $_POST["courselist"] : "";
    $showbox = ($boxopen == "false" ) ? "display='none';" : "display='block';";
    //echo "<!-- courselist[\n";print_r($courselist);echo "] -->\n";
    $outlist = "";
    $sql = "select plan_name,description from client_plan where plan_set=\"$uset\" order by plan_id";
    //echo "<!-- sql[$sql] -->\n";
    $sresult = mysql_query($sql,$shandle) or die("Cannot connect to client_plan");
    while( $sdata = mysql_fetch_array($sresult)){
	$tid   = $sdata["plan_name"];
	$tdes  = $sdata["description"];
	$outlist[$tid]=$tdes;
    }
    echo "					Mouseover this cell to display course list\n";
    echo "					<div name='seldiv' id='seldiv' class='selbox' onmouseout=\"vswitch('seldiv','none');\" onmouseover=\"vswitch('seldiv','block');\" style=\"$showbox\">\n";
    foreach( $outlist as $thiscourse => $thisdes ){
	$checked = "";
	$oclass  = "oclass";
	if( is_array($courselist) ){
	    if(array_key_exists($thiscourse,$courselist)){
		$checked  = ( $courselist[$thiscourse] == "on" ) ? "CHECKED" : "";
		$oclass   = ( $courselist[$thiscourse] == "on" ) ? "iclass"  : "oclass";
	    }
	}
	//echo "<span id='x$thiscourse' class='$oclass' onmouseover=\"document.getElementById('x$thiscourse').className='vclass';\" onmouseout=\"document.getElementById('x$thiscourse').className='$oclass';\" onclick=\"pickchange('x$thiscourse');\">\n";
	echo "						<span id='x$thiscourse' class='$oclass' onmouseover=\"mswitch('x$thiscourse','vclass');\" onmouseout=\"mswitch('x$thiscourse','$oclass');\" onclick=\"pickchange('$thiscourse');\">\n";
	echo "							<input type='checkbox' name='courselist[$thiscourse]' id='_$thiscourse' $checked/>$thisdes\n";
	echo "						</span><br/>\n";
    }
    echo "					</div>\n";
}
//////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////

?>
