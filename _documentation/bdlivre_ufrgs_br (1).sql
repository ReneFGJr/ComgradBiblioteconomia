<!DOCTYPE HTML><html lang='en' dir='ltr' class='chrome chrome61'><meta charset="utf-8" /><meta name="robots" content="noindex,nofollow" /><meta http-equiv="X-UA-Compatible" content="IE=Edge"><style id="cfs-style">html{display: none;}</style><link rel="icon" href="favicon.ico" type="image/x-icon" /><link rel="shortcut icon" href="favicon.ico" type="image/x-icon" /><link rel="stylesheet" type="text/css" href="./themes/pmahomme/jquery/jquery-ui-1.11.2.css" /><link rel="stylesheet" type="text/css" href="phpmyadmin.css.php?nocache=5850345724ltr" /><title>www.ufrgs.br / bdlivre.ufrgs.br | phpMyAdmin 4.4.10</title><script data-cfasync='false' type='text/javascript' src='js/whitelist.php?lang=en&amp;db=&amp;token=c6738617a69cca4a112bf5b133625197'></script><script data-cfasync="false" type="text/javascript" src="js/get_scripts.js.php?scripts%5B%5D=jquery/jquery-1.11.1.min.js&amp;scripts%5B%5D=sprintf.js&amp;scripts%5B%5D=ajax.js&amp;scripts%5B%5D=keyhandler.js&amp;scripts%5B%5D=jquery/jquery-ui-1.11.2.min.js&amp;scripts%5B%5D=jquery/jquery.cookie.js&amp;scripts%5B%5D=jquery/jquery.mousewheel.js&amp;scripts%5B%5D=jquery/jquery.event.drag-2.2.js&amp;scripts%5B%5D=jquery/jquery-ui-timepicker-addon.js&amp;scripts%5B%5D=jquery/jquery.ba-hashchange-1.3.js&amp;scripts%5B%5D=jquery/jquery.debounce-1.0.5.js&amp;scripts%5B%5D=menu-resizer.js&amp;scripts%5B%5D=cross_framing_protection.js&amp;scripts%5B%5D=rte.js&amp;scripts%5B%5D=tracekit/tracekit.js&amp;scripts%5B%5D=error_report.js&amp;scripts%5B%5D=doclinks.js&amp;scripts%5B%5D=functions.js&amp;scripts%5B%5D=navigation.js&amp;scripts%5B%5D=indexes.js&amp;scripts%5B%5D=common.js&amp;scripts%5B%5D=codemirror/lib/codemirror.js&amp;scripts%5B%5D=codemirror/mode/sql/sql.js&amp;scripts%5B%5D=codemirror/addon/runmode/runmode.js&amp;scripts%5B%5D=codemirror/addon/hint/show-hint.js&amp;scripts%5B%5D=codemirror/addon/hint/sql-hint.js&amp;scripts%5B%5D=console.js&amp;scripts%5B%5D=config.js"></script><script data-cfasync='false' type='text/javascript' src='js/messages.php?lang=en&amp;db=&amp;token=c6738617a69cca4a112bf5b133625197'></script><script data-cfasync='false' type='text/javascript' src='js/get_image.js.php?theme=pmahomme'></script><script data-cfasync="false" type="text/javascript">// <![CDATA[
PMA_commonParams.setAll({common_query:"?token=c6738617a69cca4a112bf5b133625197",opendb_url:"db_structure.php",safari_browser:"0",collation_connection:"utf8mb4_unicode_ci",lang:"en",server:"1",table:"",db:"",token:"c6738617a69cca4a112bf5b133625197",text_dir:"ltr",show_databases_navigation_as_tree:"1",pma_absolute_uri:"https://www.ufrgs.br/zpanel/phpmyadmin/",pma_text_default_tab:"Browse",pma_text_left_default_tab:"Structure",pma_text_left_default_tab2:"",LimitChars:"50",pftext:"",confirm:"1",LoginCookieValidity:"1440",logged_in:"1",auth_type:"cookie"});
AJAX.scriptHandler.add("jquery/jquery-1.11.1.min.js",0).add("whitelist.php?lang=en&amp;db=&amp;token=c6738617a69cca4a112bf5b133625197",1).add("sprintf.js",1).add("ajax.js",0).add("keyhandler.js",1).add("jquery/jquery-ui-1.11.2.min.js",0).add("jquery/jquery.cookie.js",0).add("jquery/jquery.mousewheel.js",0).add("jquery/jquery.event.drag-2.2.js",0).add("jquery/jquery-ui-timepicker-addon.js",0).add("jquery/jquery.ba-hashchange-1.3.js",0).add("jquery/jquery.debounce-1.0.5.js",0).add("menu-resizer.js",1).add("cross_framing_protection.js",0).add("rte.js",1).add("tracekit/tracekit.js",1).add("error_report.js",1).add("messages.php?lang=en&amp;db=&amp;token=c6738617a69cca4a112bf5b133625197",0).add("get_image.js.php?theme=pmahomme",0).add("doclinks.js",1).add("functions.js",1).add("navigation.js",0).add("indexes.js",1).add("common.js",1).add("codemirror/lib/codemirror.js",0).add("codemirror/mode/sql/sql.js",0).add("codemirror/addon/runmode/runmode.js",0).add("codemirror/addon/hint/show-hint.js",0).add("codemirror/addon/hint/sql-hint.js",0).add("console.js",1).add("config.js",1);
$(function() {AJAX.fireOnload("whitelist.php?lang=en&amp;db=&amp;token=c6738617a69cca4a112bf5b133625197");AJAX.fireOnload("sprintf.js");AJAX.fireOnload("keyhandler.js");AJAX.fireOnload("menu-resizer.js");AJAX.fireOnload("rte.js");AJAX.fireOnload("tracekit/tracekit.js");AJAX.fireOnload("error_report.js");AJAX.fireOnload("doclinks.js");AJAX.fireOnload("functions.js");AJAX.fireOnload("indexes.js");AJAX.fireOnload("common.js");AJAX.fireOnload("console.js");AJAX.fireOnload("config.js");});
// ]]></script><noscript><style>html{display:block}</style></noscript></head><body><div id="pma_navigation"><div id="pma_navigation_resizer"></div><div id="pma_navigation_collapser"></div><div id="pma_navigation_content"><div id="pma_navigation_header"><a class="hide navigation_url" href="navigation.php?ajax_request=1&amp;token=c6738617a69cca4a112bf5b133625197"></a><!-- LOGO START --><div id="pmalogo">    <a href="index.php?token=c6738617a69cca4a112bf5b133625197"><img src="./themes/pmahomme/img/logo_left.png" alt="phpMyAdmin" id="imgpmalogo" /></a></div><!-- LOGO END --><!-- LINKS START --><div id="navipanellinks"><a href="index.php?token=c6738617a69cca4a112bf5b133625197" title="Home"><img src="themes/dot.gif" title="Home" alt="Home" class="icon ic_b_home" /></a><a href="index.php?token=c6738617a69cca4a112bf5b133625197&amp;old_usr=comgradbib" class="disableAjax" title="Log out"><img src="themes/dot.gif" title="Log out" alt="Log out" class="icon ic_s_loggoff" /></a><a href="./doc/html/index.html" target="documentation" title="phpMyAdmin documentation"><img src="themes/dot.gif" title="phpMyAdmin documentation" alt="phpMyAdmin documentation" class="icon ic_b_docs" /></a><a href="./url.php?url=http%3A%2F%2Fdev.mysql.com%2Fdoc%2Frefman%2F5.5%2Fen%2Findex.html" target="mysql_doc" title="Documentation"><img src="themes/dot.gif" title="Documentation" alt="Documentation" class="icon ic_b_sqlhelp" /></a><a href="#" id="pma_navigation_reload" title="Reload navigation panel"><img src="themes/dot.gif" title="Reload navigation panel" alt="Reload navigation panel" class="icon ic_s_reload" /></a></div><!-- LINKS ENDS --><img src="./themes/pmahomme/img/ajax_clock_small.gif" title="Loading…" alt="Loading…" style="visibility: hidden; display:none" class="throbber" /></div><div id="pma_navigation_tree" class="list_container synced highlight"><div class="pma_quick_warp"><div class="drop_list"><span title="Recent tables" class="drop_button">Recent</span><ul id="pma_recent_list"><li class="warp_link">There are no recent tables.</li></ul></div><div class="drop_list"><span title="Favorite tables" class="drop_button">Favorites</span><ul id="pma_favorite_list"><li class="warp_link">There are no favorite tables.</li></ul></div><div class="clearfloat"></div></div><div class="clearfloat"></div><ul><!-- CONTROLS START --><li id="navigation_controls_outer"><div id="navigation_controls"><a href="#" id="pma_navigation_collapse" title="Collapse all"><img src="./themes/pmahomme/img/s_collapseall.png" title="Collapse all" alt="Collapse all" /></a><a href="#" id="pma_navigation_sync" title="Unlink from main panel"><img src="themes/dot.gif" title="Unlink from main panel" alt="Unlink from main panel" class="icon ic_s_link" /></a></div></li><!-- CONTROLS ENDS --></ul><div id='pma_navigation_tree_content'><ul><li class="first database"><div class='block'><i class='first'></i><b></b><a class="expander" href='#'><span class='hide aPath'>cm9vdA==.Y29tZ3JhZGJpYg==</span><span class='hide vPath'>cm9vdA==.Y29tZ3JhZGJpYg==</span><span class='hide pos'>0</span><img src="themes/dot.gif" title="Expand/Collapse" alt="Expand/Collapse" class="icon ic_b_plus" /></a></div><div class='block '><a href='db_operations.php?server=1&amp;db=comgradbib&amp;token=c6738617a69cca4a112bf5b133625197'><img src="themes/dot.gif" title="Database operations" alt="Database operations" class="icon ic_s_db" /></a></div><a class='hover_show_full' href='db_structure.php?server=1&amp;db=comgradbib&amp;token=c6738617a69cca4a112bf5b133625197' title='Structure'>comgradbib</a><div class="clearfloat"></div></ul></div></div></div><div class="pma_drop_handler">Drop files here</div><div class="pma_sql_import_status"><h2>SQL upload ( <span class="pma_import_count">0</span> ) <span class="close">x</span><span class="minimize">-</span></h2><div></div></div></div><div id="prefs_autoload" class="notice" style="display:none"><form action="prefs_manage.php" method="post"><input type="hidden" name="token" value="c6738617a69cca4a112bf5b133625197" /><input type="hidden" name="json" value="" /><input type="hidden" name="submit_import" value="1" /><input type="hidden" name="return_url" value="export.php?" />Your browser has phpMyAdmin configuration for this domain. Would you like to import it for current session?<br /><a href="#yes">Yes</a> / <a href="#no">No</a></form></div><noscript><div class="error"><img src="themes/dot.gif" title="" alt="" class="icon ic_s_error" /> Javascript must be enabled past this point!</div></noscript><div id='floating_menubar'></div><div id='serverinfo'><img src="themes/dot.gif" title="" alt="" class="icon ic_s_host item" /><a href="index.php?token=c6738617a69cca4a112bf5b133625197" class="item">Server: bdlivre.ufrgs.br</a><div class="clearfloat"></div></div><div id="topmenucontainer" class="menucontainer"><ul id="topmenu"  class="resizable-menu"><li><a class="tab" href="server_databases.php?db=&amp;token=c6738617a69cca4a112bf5b133625197" ><img src="themes/dot.gif" title="Databases" alt="Databases" class="icon ic_s_db" /> Databases</a></li><li><a class="tab" href="server_sql.php?db=&amp;token=c6738617a69cca4a112bf5b133625197" ><img src="themes/dot.gif" title="SQL" alt="SQL" class="icon ic_b_sql" /> SQL</a></li><li><a class="tab" href="server_status.php?db=&amp;token=c6738617a69cca4a112bf5b133625197" ><img src="themes/dot.gif" title="Status" alt="Status" class="icon ic_s_status" /> Status</a></li><li><a class="tab" href="server_export.php?db=&amp;token=c6738617a69cca4a112bf5b133625197" ><img src="themes/dot.gif" title="Export" alt="Export" class="icon ic_b_export" /> Export</a></li><li><a class="tab" href="server_import.php?db=&amp;token=c6738617a69cca4a112bf5b133625197" ><img src="themes/dot.gif" title="Import" alt="Import" class="icon ic_b_import" /> Import</a></li><li><a class="tab" href="prefs_manage.php?db=&amp;token=c6738617a69cca4a112bf5b133625197" ><img src="themes/dot.gif" title="Settings" alt="Settings" class="icon ic_b_tblops" /> Settings</a></li><li><a class="tab" href="server_variables.php?db=&amp;token=c6738617a69cca4a112bf5b133625197" ><img src="themes/dot.gif" title="Variables" alt="Variables" class="icon ic_s_vars" /> Variables</a></li><li><a class="tab" href="server_collations.php?db=&amp;token=c6738617a69cca4a112bf5b133625197" ><img src="themes/dot.gif" title="Charsets" alt="Charsets" class="icon ic_s_asci" /> Charsets</a></li><li><a class="tab" href="server_engines.php?db=&amp;token=c6738617a69cca4a112bf5b133625197" ><img src="themes/dot.gif" title="Engines" alt="Engines" class="icon ic_b_engine" /> Engines</a></li><div class="clearfloat"></div></ul>
</div>
<span id="lock_page_icon"></span><a id="goto_pagetop" href="#"><img src="themes/dot.gif" title="Click on the bar to scroll to top of page" alt="Click on the bar to scroll to top of page" class="icon ic_s_top" /></a><div id="pma_console_container"><div id="pma_console"><div class="toolbar collapsed"><div class="switch_button console_switch"><img src="themes/dot.gif" title="SQL Query Console" alt="SQL Query Console" class="icon ic_console" /><span>Console</span></div><div class="button clear"><span>Clear</span></div><div class="button history"><span>History</span></div><div class="button options"><span>Options</span></div></div><div class="content"><div class="console_message_container"><div class="message welcome"><span>Press Ctrl+Enter to execute query</span></div></div><div class="query_input"><span class="console_query_input"></span></div></div><div class="mid_layer"></div><div class="card" id="pma_console_options"><div class="toolbar"><div class="switch_button"><span>Options</span></div><div class="button default"><span>Set default</span></div></div><div class="content"><label><input type="checkbox" name="always_expand">Always expand query messages</label><br><label><input type="checkbox" name="start_history">Show query history at start</label><br><label><input type="checkbox" name="current_query">Show current browsing query</label><br></div></div><div class="templates"><div class="query_actions"><span class="action collapse">Collapse</span> <span class="action expand">Expand</span> <span class="action requery">Requery</span> <span class="action edit">Edit</span> <span class="action explain">Explain</span> <span class="action profiling">Profiling</span> <span class="text failed">Query failed</span> <span class="text targetdb">Database: <span></span></span> <span class="text query_time">Queried time: <span></span></span> </div></div></div></div><div id="page_content"><!DOCTYPE HTML>
<html lang="en" dir="ltr">
<head>
    <link rel="icon" href="favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    <title>phpMyAdmin</title>
    <meta charset="utf-8" />
    <style type="text/css">
    <!--
    html {
        padding: 0;
        margin: 0;
    }
    body  {
        font-family: sans-serif;
        font-size: small;
        color: #000000;
        background-color: #F5F5F5;
        margin: 1em;
    }
    h1 {
        margin: 0;
        padding: 0.3em;
        font-size: 1.4em;
        font-weight: bold;
        color: #ffffff;
        background-color: #ff0000;
    }
    p {
        margin: 0;
        padding: 0.5em;
        border: 0.1em solid red;
        background-color: #ffeeee;
    }
    //-->
    </style>
</head>
<body>
<h1>phpMyAdmin - Error</h1>
<p>export.php: Missing parameter: export_type&lt;a href="./doc/html/faq.html#faqmissingparameters" target="documentation"&gt;&lt;img src="themes/dot.gif" title="Documentation" alt="Documentation" class="icon ic_b_help" /&gt;&lt;/a&gt;<br /></p>
</body>
</html>
</div><div id="selflink" class="print_ignore"><a href="export.php?db=&amp;table=&amp;server=1&amp;target=&amp;token=c6738617a69cca4a112bf5b133625197" title="Open new phpMyAdmin window" target="_blank"><img src="themes/dot.gif" title="Open new phpMyAdmin window" alt="Open new phpMyAdmin window" class="icon ic_window-new" /></a></div><div class="clearfloat" id="pma_errors"><div class="notice"><strong>Notice</strong> in ./export.php#162<br />
<img src="themes/dot.gif" title="" alt="" class="icon ic_s_notice" /> Undefined index: what<br />
<br />
<strong>Backtrace</strong><br />
<br />
</div><form method="post" action="error_report.php" id="pma_report_errors_form"><input type="hidden" name="token" value="c6738617a69cca4a112bf5b133625197"/><input type="hidden" name="exception_type" value="php"/><input type="hidden" name="send_error_report" value="1" /><input type="submit" value="Report" id="pma_report_errors" style="float: right; margin: 20px;"><input type="checkbox" name="always_send" id="always_send_checkbox" value="true"/><label for="always_send_checkbox">Automatically send report next time</label></form><input type="submit" value="Ignore" id="pma_ignore_errors_bottom" style="float: right; margin: 20px;"><input type="submit" value="Ignore All" id="pma_ignore_all_errors_bottom" style="float: right; margin: 20px;"></div><script data-cfasync="false" type="text/javascript">// <![CDATA[
PMA_ajaxShowMessage(PMA_messages["phpErrorsFound"]);$("#pma_ignore_errors_popup").bind("click", function() {
                            PMA_ignorePhpErrors()
                        });$("#pma_ignore_all_errors_popup").bind("click",
                            function() {
                                PMA_ignorePhpErrors(false)
                            });$("#pma_ignore_errors_bottom").bind("click", function() {
                            PMA_ignorePhpErrors()
                        });$("#pma_ignore_all_errors_bottom").bind("click",
                            function() {
                                PMA_ignorePhpErrors(false)
                            });$("html, body").animate({
                            scrollTop:$(document).height()
                        }, "slow");
AJAX.scriptHandler;
$(function() {});
// ]]></script></body></html>