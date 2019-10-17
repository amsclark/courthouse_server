<?php
//Authored by Alex Clark, Metatheria, LLC 2019. Released under GNU GPLv3

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

chdir("..");
require_once('pika-danio.php');
pika_init();

if (!pika_authorize("system", array()))
{
	$temp["content"] = "Access denied";
	$temp["nav"] = "<a href=\"{$base_url}\">Pika Home</a> &gt;
					 <a href=\"site_map.php\">Site Map</a> &gt;
					 Reset Counters";
	
	$default_template = new pikaTempLib('templates/default.html',$temp);
	$buffer = $default_template->draw();
	pika_exit($buffer);
}

$safe_mode = DB::escapeString($_GET['mode']);
$safe_crt_id = DB::escapeString($_GET['crtid']);
$safe_search_string = DB::escapeString(html_entity_decode($_GET['search']));
$safe_crths_courtname = DB::escapeString(html_entity_decode($_GET['courtname']));
$safe_crths_address1 = DB::escapeString(html_entity_decode($_GET['address1']));
$safe_crths_address2 = DB::escapeString(html_entity_decode($_GET['address2']));
$safe_crths_city = DB::escapeString(html_entity_decode($_GET['city']));
$safe_crths_state = DB::escapeString(html_entity_decode($_GET['state']));
$safe_crths_stt_abbrev = DB::escapeString(html_entity_decode($_GET['abbrev']));
$safe_crths_zip = DB::escapeString(html_entity_decode($_GET['zip']));



if( $safe_mode == '') {
 exit('no mode specified');
}

switch ($safe_mode) {
  case "dump":
    $dump_sql = "select * from courthouses";
    $dump_result = DB::query($dump_sql) or trigger_error("SQL: " . $sql . " Error: " . DB::error());
    $dump_rows = array();
    while ($r = DBResult::fetchRow($dump_result)) {
      $dump_rows['courthouses'][] = $r;
    } 
    print json_encode($dump_rows);
    break;
  case "search":
    if ($safe_search_string == '') {exit("no search string");}
    else { 
      $search_sql= "select * from courthouses where crths_courtname=\"$safe_search_string\"";}
      $search_result = DB::query($search_sql) or trigger_error("SQL: " . $sql . " Error: " . DB::error());
      $search_rows = array();
      while ($r = DBResult::fetchRow($search_result)) {
        $search_rows['courthouses'][] = $r;
      }
      print json_encode($search_rows);
    break;
  case "add":
    if ($safe_crths_courtname != '' && $safe_crths_address1 != '' && $safe_crths_city != '' && $safe_crths_state != '' && $safe_crths_stt_abbrev != '' && $safe_crths_zip != '') { 
      $add_sql = "insert into courthouses(crths_courtname, 
                                    crths_address1, 
                                    crths_address2, 
                                    crths_city, 
                                    crths_state, 
                                    crths_stt_abbrev, 
                                    crths_zip
                                   ) 
                                    values 
                                   (
                                    \"$safe_crths_courtname\", 
                                    \"$safe_crths_address1\", 
                                    \"$safe_crths_address2\", 
                                    \"$safe_crths_city\", 
                                    \"$safe_crths_state\", 
                                    \"$safe_crths_stt_abbrev\", 
                                    \"$safe_crths_zip\")";
       DB::query($add_sql) or trigger_error("SQL: " . $sql . " Error: " . DB::error());
    }
    else { 
      echo "incomplete courthouse information";
    }
    break;
  case "delete":
    if ($safe_crt_id == '') {echo "no courthouse ID";}
    else { 
      $delete_sql = "delete from courthouses where crths_id=$safe_crt_id";
      DB::query($delete_sql) or trigger_error("SQL: " . $sql . " Error: " . DB::error());
    } 
    break;
  default:
    echo "invalid mode";
} 

?>
