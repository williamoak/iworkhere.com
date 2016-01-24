<?
    date_default_timezone_set("America/Vancouver");
    $webname    = $_SERVER["SERVER_NAME"];
    $scriptname = $_SERVER["PHP_SELF"];
    $docroot    = $_SERVER["DOCUMENT_ROOT"];
    $browser    = $_SERVER["HTTP_USER_AGENT"];
    $protocol   = ( isset($_SERVER["HTTPS"]) ) ? "https://" : "http://";
    $baseref    = "$protocol$webname";
    $modbase    = "$docroot/tracker";
    include_once "$docroot/baselib/baselib.php";
    include_once "$docroot/baselib/config.php";

    if(session_id() == ""){session_start();}
    $shandle = init();
    trackhit($shandle,$webname,$scriptname,"","","mysqli",false,true);

    $btype = browsertype($browser);
    $block = ( $btype == "msie" ) ? "block" : "inline";
    $bpic = rand(0,9);
    $bclass = "mainbox$bpic";
    $maxcol = 2;
    $col = 0;

    $links = array();

    $links["wiab"]["name"]="Websiteinabox.com";
    $links["wiab"]["link"]="skipage.php?link=http://www.websiteinabox.com";
    $links["wiab"]["target"]="_blank";
    $links["wiab"]["title"]="view Website in a box site";
    $links["cssobj"]["name"]="CSS Parsing/Editing";
    $links["cssobj"]["link"]="skipage.php?link=http://mastergeek.homedns.org/cssobj/cssobj.php?filename=index.php";
    $links["cssobj"]["target"]="_blank";
    $links["cssobj"]["title"]="Some code for editing CSS in a web browser";
    $links["redev"]["name"]="ReMuS Dev Site";
    $links["redev"]["link"]="skipage.php?link=http://bbydev.homedns.org/bbystart.cgi";
    $links["redev"]["target"]="_blank";
    $links["redev"]["title"]="ReMuS Dev Site";
    $links["bizcard1"]["name"]="Business Card Sample: Desktop";
    $links["bizcard1"]["link"]="skipage.php?link=http://bizcards.homedns.org/ilimpright1/desktop/";
    $links["bizcard1"]["target"]="_blank";
    $links["bizcard1"]["title"]="Desktop style business card sample";
    $links["bmspro"]["name"]="Blue Mountain Shale Prospectus";
    $links["bmspro"]["link"]="skipage.php?link=http://www.bluemountainshale.com";
    $links["bmspro"]["target"]="_blank";
    $links["bmspro"]["title"]="Custom built report reader";
    $links["sumrep"]["name"]="ReMuS Period Summary Report";
    $links["sumrep"]["link"]="skipage.php?link=http://mastergeek.homedns.org/sumrep";
    $links["sumrep"]["target"]="_blank";
    $links["sumrep"]["title"]="Sample page for java and php code";
    $links["bizcard2"]["name"]="Business Card Sample: Mobile";
    $links["bizcard2"]["link"]="skipage.php?link=http://bizcards.homedns.org/ilimpright1/mobile/";
    $links["bizcard2"]["target"]="_blank";
    $links["bizcard2"]["title"]="Mobile style business card sample";
    $links["bmsrep"]["name"]="Blue Mountain Shale Hit Tracker";
    $links["bmsrep"]["link"]="skipage.php?link=http://www.bluemountainshale.com/tracker";
    $links["bmsrep"]["target"]="_blank";
    $links["bmsrep"]["title"]="Hit Report with jQuery";
    $links["sumtxt"]["name"]="Summary Report Codebase";
    $links["sumtxt"]["link"]="showcode.php?repname=$docroot/resume/sumrep.txt.php";
    $links["sumtxt"]["target"]="_blank";
    $links["sumtxt"]["title"]="Summary report Code";
    $links["tracker"]["name"]="iworkhere.com Page Hit Tracker";
    $links["tracker"]["link"]="./tracker";
    $links["tracker"]["target"]="_blank";
    $links["tracker"]["title"]="Page Hit Tracker module";
    $links["avihs"]["name"]="Loki&#8217;s Homepage";
    $links["avihs"]["link"]="skipage.php?link=http://mastergeek.homedns.org/avihs";
    $links["avihs"]["target"]="_blank";
    $links["avihs"]["title"]="Home page for Loki, enter at peril of your soul";
    $links["iconfig"]["name"]="mysqli connect codebase";
    $links["iconfig"]["link"]="showcode.php?repname=$docroot/resume/iconfig.txt.php";
    $links["iconfig"]["target"]="_blank";
    $links["iconfig"]["title"]="iconfig codebase";
    $links["ufcw"]["name"]="2012 UFCW Site";
    $links["ufcw"]["link"]="http://bizcards.homedns.org";
    $links["ufcw"]["target"]="_blank";
    $links["ufcw"]["title"]="2012 UFCW Site";
    $links["abby"]["name"]="2014 Anglican Abbotsford Site";
    $links["abby"]["link"]="http://abby.homedns.org";
    $links["abby"]["target"]="_blank";
    $links["abby"]["title"]="Anglican Abbotsford site";
    $links["sumcode"]["name"]="Serve CSS file from Mysql Table";
    $links["sumcode"]["link"]="showcode.php?repname=$docroot/css/activecss/style.css.php";
    $links["sumcode"]["target"]="_blank";
    $links["sumcode"]["title"]="Sample code page with java and php";

    echo "<!doctype html>\n";
    echo "<html>\n";
    echo "<head>\n";
    echo "<title>$webname</title>\n";
    //echo "<link REL=STYLESHEET HREF='$baseref/css/activecss/main.css' TYPE='text/css'>\n";
    setstyle('/resume/',true);
    echo "<meta name=\"browser\" content=\"$btype\">\n";
    echo "<STYLE TYPE=\"text/css\">\n";
    echo "BODY{font-family:Arial,Helvetica,sans-serif;}\n";
    echo "</STYLE>\n";
    echo "</head>\n";
    echo "<body onmouseout=\"showdis('off');\" class='bodydiv'>\n";
    echo "<div name='bodybg' id='bodybg'>\n";
    echo "	<img id='bgi' name='bgi' class='bodyimage' src='bgpics/small_office_space.jpg'>\n";
    echo "	<div id='lowspot' name='lowspot' class='lowerdiv'>\n";
    echo "		<img src='bgpics/office_desk.gif' height=100%>\n";
    echo "	</div>\n";
    echo "</div>\n";

    echo "<div class='topdiv'>\n";
    echo "	$webname<br/>\n";
    echo "	<p class='test'>This is my resume site, where I have links to samples of my past work, The ReMuS Period \n";
    echo "	Summary Report shows arbitrary information.  The Websiteinabox.com and ReMuS Dev sites require \n";
    echo "	user authentication, and are intended to be toured with my assistance, the Business Card samples \n";
    echo "	(Desktop and Mobile versions) are part of the now outdated UFCW site created by Mission:IMPOSSIBLE \n";
    echo "	which is about half my work.</p>\n";
    echo "</div>\n";

    echo "<div class='maindiv'>\n";
    echo "	<table name='linktable' id='linktable' border=0 cellpadding=0 cellspacing=10 class='maintable'>\n";
    foreach( $links as $thislink => $linkinfo ){
	//echo "<!-- testing [$col] vs [$maxcol] -->\n";
	switch ($col){
	    case $maxcol:
		showline($links,$thislink,$webname,$btype);
		echo "		</tr>\n";
		break;
	    case 0;
		echo "		<tr>\n";
	    default:
		showline($links,$thislink,$webname,$btype);
		break;
	}
	$col++;
	$col = ( $col > $maxcol ) ? 0 : $col;
    }
    echo "	</table>\n";
    echo "</div>\n";

    echo "<div class='bottomdiv'>\n";
    echo "	<p style='color:red;font-size:12px;text-decoration:underline' onmouseover=\"showdis('on');\">Contents subject to change without notice</p>\n";
    echo "	<span id='disclaimer' name='disclaimer' onmouseover=\"showdis('on');\">\n";
    echo "		If you are <span class='rt'>NOT</span> William-Randolph:Oak, or you are <span class='rt'>NOT</span> here at the specific invitation of same, you should leave.  This site and it's contents are for personal use only, or by invitation. All materials not otherwise copyrighted are the property of the living, breathing, human being of undiminished capacity named William-Randolph:Oak and or the Canadian corporation WILLIAM RANDOLPH OAK, as appropriate. Access to this area is by invitation only. If you have arrived without an invitation, or if you have been invited, all risk lays with you for the use of any information herein found for any purpose.</br>\n";
    echo "	</span>\n";
    echo "</div>\n";
    echo "<div class='copyright' title='All materials not otherwise copyrighted are the property of the living, breathing, human being of undiminished capacity named William-Randolph:Oak and or the Canadian corporation WILLIAM RANDOLPH OAK, as appropriate.'>&copy; William Oak</div>\n";
    echo "<script type='text/javascript'>\n";
    echo " function showdis( vDir ){\n";
    echo "  vOut = ( vDir == 'on' ) ? '$block' : 'none';\n";
    echo "  document.getElementById('disclaimer').style.display=vOut;\n";
    echo " }\n";
    echo " function actuateLink(link){\n";
    echo "    var allowDefaultAction = true;\n";
    echo "    if (link.click){\n";
    echo "        link.click();\n";
    echo "        return;\n";
    echo "    } else if (document.createEvent){\n";
    echo "        var e = document.createEvent('MouseEvents');\n";
    echo "        e.initEvent('click',true,true);\n";
    echo "        allowDefaultAction = link.dispatchEvent(e);\n";
    echo "    }\n";
    echo "    if (allowDefaultAction){\n";
    echo "        var f = document.createElement('form');\n";
    echo "        f.action = link.href;\n";
    echo "        document.body.appendChild(f);\n";
    echo "        f.submit();\n";
    echo "    }\n";
    echo " }\n";
    echo "</script>\n";
    echo "</body>\n";
    echo "</html>\n";

//////////////////////////////////////////////

function showline($links,$thislink,$webname,$btype){
    $linkval   = (isset($links[$thislink]["link"]))   ? $links[$thislink]["link"]   : "&nbsp;";
    $titleval  = (isset($links[$thislink]["title"]))  ? $links[$thislink]["title"]  : "&nbsp;";
    $targetval = (isset($links[$thislink]["target"])) ? $links[$thislink]["target"] : "&nbsp;";
    $linkname  = (isset($links[$thislink]["name"]))   ? $links[$thislink]["name"]   : "&nbsp;";
    $altype = "title";
    $tdid = "td$thislink";
    echo "			<td id=\"$tdid\" name=\"$tdid\" class='tablecell' onclick=\"actuateLink(document.getElementById(\"$thislink\"));\" onmouseover=\"document.title='$titleval';\" onmouseout=\"document.title='$webname'\" $altype=\"$titleval\" >\n";
    echo "					<a id=\"$thislink\" class=\"xhref\" href=\"$linkval\" target=\"$targetval\" onmouseover=\"document.title='$titleval';\" onmouseout=\"document.title='$webname'\">$linkname</a>\n";
    echo "			</td>\n";
}
//////////////////////////////////////////////

function addinfades($links){
#    foreach( $links as $thislink => $linkinfo ){
#	$tdid = "td$thislink";
	echo "$('#linktable').hover(\n";
	echo "  function(){\n";
	echo "    $('#linktable').find(this).fadeTo('fast',1);\n";
	echo "  },\n";
	echo "  function(){\n";
	echo "    $('#linktable').find(this).fadeTo('fast',0.4);\n";
	echo "  });\n";
#    }
}
//////////////////////////////////////////////
?>

