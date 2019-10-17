<?php
// Courthouse Address Management Screen
// Developed by Metatheria, LLC in 2019
// GNU GPLv2

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

require_once('pika-danio.php');
pika_init();

require_once('pikaTempLib.php');


$action = DB::escapeString($_GET['action']);
$base_url = pl_settings_get('base_url');

$main_html = array();
$main_html['content'] = '';

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

                $main_html['content'] = "<h2>Manage Courthouse Addresses</h2>"; 

switch ($action) {
	case 'addcourt':
		$main_html['content'] .= "courthouse added.";
	default:
                $dump_sql = "select * from courthouses";
                $dump_result = DB::query($dump_sql) or trigger_error("SQL: " . $sql . " Error: " . DB::error());
                //$dump_rows = array();
                $main_html['content'] .= "<table class=\"table table-hover\">";
                $main_html['content'] .= "<thead><tr><th>Court Name</th><th>Address Line 1</th><th>Address Line 2</th><th>City</th><th>State Name</th><th>State Abbreviation</th><th>ZIP</th><th>Delete</th></tr></thead>";
                while ($r = DBResult::fetchRow($dump_result)) {
                  $main_html['content'] .= "<tr>
                                              <td>{$r['crths_courtname']}</td>
                                              <td>{$r['crths_address1']}</td>
                                              <td>{$r['crths_address2']}</td>
                                              <td>{$r['crths_city']}</td>
                                              <td>{$r['crths_state']}</td>
                                              <td>{$r['crths_stt_abbrev']}</td>
                                              <td>{$r['crths_zip']}</td>
                                              <td><span style=\"color: #00d; cursor: pointer\" onclick=\"crthsdelete({$r['crths_id']})\">Delete</span></td>
                                            </tr>";
                } 
                $main_html['content'] .= "<tr>
                                            <td><input name=\"new_crtname\" id=\"new_crtname\" style=\"width: 100%; box-sizing: border-box\"></td>
                                            <td><input name=\"new_addy1\" id=\"new_addy1\" style=\"width: 100%; box-sizing: border-box\"></td>
                                            <td><input name=\"new_addy2\" id=\"new_addy2\" style=\"width: 100%; box-sizing: border-box\"></td>
                                            <td><input name=\"new_city\" id=\"new_city\" style=\"width: 100%; box-sizing: border-box\"></td>
                                            <td><input name=\"new_state\" id=\"new_state\" style=\"width: 100%; box-sizing: border-box\"></td>
                                            <td><input name=\"new_st_abbrev\" id=\"new_st_abbrev\" style=\"width: 100%; box-sizing: border-box\"></td>
                                            <td><input name=\"new_zip\" id=\"new_zip\" style=\"width: 100%; box-sizing: border-box\"></td>
                                            <td><span style=\"color: #00d; cursor: pointer\" onclick=\"crthsadd()\">Add</span></td>
                                         </tr>";
                $main_html['content'] .= "</table>";
                $main_html['content'] .= "<script>

                                          function sleep(milliseconds) {
                                            var start = new Date().getTime();
                                            for (var i = 0; i < 1e7; i++) {
                                              if ((new Date().getTime() - start) > milliseconds){
                                                break;
                                              }
                                            }
                                          }

                                          function crthsdelete(id){
                                            var xhr = new XMLHttpRequest();
                                            xhr.onreadystatechange = function() {};
                                            xhr.open('GET', 'services/crths_srvr.php?mode=delete&crtid='.concat(id));
                                            xhr.send();
                                            sleep(1000);
                                            location.reload();
                                          }
                                                    
                                          function crthsadd() {
                                            newcrtname = document.getElementById('new_crtname').value;
                                            new_addy1 = document.getElementById('new_addy1').value; 
                                            new_addy2 = document.getElementById('new_addy2').value;
                                            new_city = document.getElementById('new_city').value;
                                            new_state = document.getElementById('new_state').value;
                                            new_st_abbrev = document.getElementById('new_st_abbrev').value;
                                            new_zip = document.getElementById('new_zip').value;
                                            if (newcrtname != '' && new_addy1 != '' && new_city != '' && new_state != '' && new_st_abbrev != '' && new_zip != '') {
                                              additionstring = '';
                                              additionstring = additionstring.concat('courtname=',newcrtname,'&address1=',new_addy1,'&address2=',new_addy2,'&city=',new_city,'&state=',new_state,'&abbrev=','new_st_abbrev','&zip=',new_zip); 
                                              var xhr = new XMLHttpRequest();
                                              xhr.open('GET', 'services/crths_srvr.php?mode=add&'.concat(additionstring));
                                              xhr.send();
                                              sleep(1000);
                                              location.reload();
                                            } 
                                            else {
                                              alert('please fill in all required fields. All fields are required except address line #2');
                                            }
                                          }
                                         </script>";
		break;
		
}

// Display a screen

$main_html['page_title'] = "Manage Courthouse Addresses";
$main_html['nav'] = "<a href=\"{$base_url}\">Pika Home</a> &gt;
					 <a href=\"{$base_url}/site_map.php\">Site Map</a> &gt;
					 Manage Courthouse Addresses";


$default_template = new pikaTempLib('templates/default.html',$main_html);
$buffer = $default_template->draw();
pika_exit($buffer);

?>
