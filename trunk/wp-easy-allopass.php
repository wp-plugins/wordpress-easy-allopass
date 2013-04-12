<?php
/*
Plugin Name: Wp Easy Allopass
Plugin URI: http://www.vitar.123.fr
Description: The Wp Easy Allopass Plugin (WEA) is a free plugin that allows you to integrate allopass payment solution on your wordpress site. Allopass is a great supplement to PayPal and Google Checkout to sell digital content on your wordpress site. Allopass offers 6 different payment solutions: *Audiotel: surcharged phone call - SMS +: surcharged SMS - Internet+: Internet Service Provider direct debit (France only) - Neosurf: prepaid card available in all Neosurf points of sale - Credit/Debit Card - Electronic wallet : HiPay, Dineromail
Author: Hasiniaina Ragaby 
Author URI:  http://www.vitar.123.fr
Version: 3.3.0
	Copyright 2011  H. Ragaby  (email : hragaby@hotmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Define Server Plugin (for code verification and redirection).
@define("URL_WES","http://www.vitar.123.fr/");
// Plugin dir URL
@define("URL_WEA",plugin_dir_url( __FILE__ ));
// Plugin Dirname
@define("DIR_WEA",dirname(__FILE__)."/");
// Plugin Setup Instructions
@define("URL_HELP","http://www.vitar.123.fr/wea-wp-easy-allopass-instructions-");

global $wpdb;
@define("TBL_PROD",$wpdb->prefix ."weallopass_prod");
@define("TBL_STAT",$wpdb->prefix ."weallopass_stat");

// SSL ON or OFF
if($_SERVER['HTTPS'] != 'on')
	{
		@define("WEA_PROTOCOL","http://");
    }
	else
	{
        @define("WEA_PROTOCOL","https://");
    }	

register_activation_hook(__FILE__,'WEA_init');

// Manage user language
$default_lang ="en";
if (!isset($_GET["lang"]))
	{
		$lang = get_post_meta(2, "wea_lang", true);
		if ($lang=="")
		{
			$lang=$default_lang;
			delete_post_meta(2,"wea_lang");
			add_post_meta(2,"wea_lang", $lang);			
		}
	}
	else
	{
		$lang = $_GET["lang"];
		if(!file_exists(DIR_WEA . "lang/".$lang.".php")) $lang=$default_lang;
		delete_post_meta(2,"wea_lang");
		add_post_meta(2,"wea_lang", $lang);
		unset($_GET["lang"]);
	}
require_once(DIR_WEA . "lang/".$lang.".php");

if (isset($_GET["data"]) and $_GET["data"]!="") $_GET["DATAS"] = $_GET["data"];
if (isset($_GET["codes"]) and $_GET["codes"]!="") $_GET["RECALL"] = $_GET["codes"];
if (isset($_GET["code"]) and $_GET["code"]!="") $_GET["RECALL"] = $_GET["code"];

if ($_GET["DATAS"]!="")
{
	if (!isset($_GET["trxid"]) and !isset($_GET["transaction_id"]) ) $_GET["trxid"] = $_GET["transaction_id"] = "D";
	add_action('init', 'WEA_redirect', 0);
}

add_action('admin_menu', 'WEA_allopass' );
add_shortcode('allopass', 'LCK_allopass');

function WEA_redirect()
{
	$tmp = explode('::',$_GET["DATAS"]);
	$PAGE    = $tmp[0];
	$SECTION = $tmp[1];
	if (isset($_GET["RECALL"]) and $_GET["RECALL"]!="")
		{
		setcookie("POST_".$PAGE."_".$SECTION,$_GET["RECALL"],time()+3600*24,"/");
		header( "Location: ".WEA_PROTOCOL.$_SERVER['HTTP_HOST']."/?p=$PAGE&s=$SECTION&ok=".$_GET["RECALL"] );
		}
		else
		{
		header( "Location: ".WEA_PROTOCOL.$_SERVER['HTTP_HOST']."/?p=$PAGE&s=$SECTION" );
		}
	exit();
}

function WEA_init()
{
	global $wpdb;
	$re_ = $wpdb->get_var("SELECT id_prod FROM ".TBL_PROD." LIMIT 1;");
	if ($re_ == "" or !$re_)
		{
		$sql = "
				CREATE TABLE ".TBL_PROD." (
				id int(10) NOT NULL AUTO_INCREMENT, 
				id_prod VARCHAR(50) NOT NULL, 
				prod_descr TEXT NOT NULL, 
				last_update VARCHAR(8) NOT NULL, 
				UNIQUE KEY id (id) );
				CREATE TABLE ".TBL_STAT." (
				id_pmt int(10) NOT NULL AUTO_INCREMENT, 
				codes VARCHAR(50) NOT NULL, 
				id int(10) NOT NULL, 
				post INT(10) NOT NULL, 
				date VARCHAR(8) NOT NULL, 
				stats VARCHAR(1) NOT NULL,
				UNIQUE KEY id_pmt (id_pmt));
				INSERT INTO ".TBL_PROD." (id_prod, prod_descr, last_update) VALUES ('DontDelete','This','00000000');
			   ";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		}
}

// [allopass id="xxx"] [/allopass]
function LCK_allopass($atts, $content)
	{
		$lang = get_post_meta(2, "wea_lang", true);
		if ($lang=="") $lang="en";
		
		global $wpdb;
		$wpdb->flush();
		extract(shortcode_atts(array('id' => '0'), $atts));
		$page  = get_the_ID();
		
		$re_ = $wpdb->get_row("SELECT * FROM ".TBL_PROD." WHERE id='$id';");
		$id_allopass = $re_->id_prod;
		$txt = stripslashes($re_->prod_descr);
		
		$r  = '<center>	
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td valign="middle" bgcolor="#CCCCCC">'. nl2br($txt) .'</td>
					<td width="160" valign="top"><div align="center">'. wp_remote_retrieve_body(wp_remote_get(URL_WES . "wp-easy-allopass_btn.php?page=$page&id=$id&id_allopass=$id_allopass&txt=$txt_allopass&lang=$lang")).'</div></td>
				  </tr>
				</table>
			  </center>';
			  
		// For demonstration
		if ($id == 0) $r = '[allopass id="X"]'.do_shortcode($content).'[/allopass]';
		
			if (isset($_GET["ok"]) and isset($_GET["s"]) and $_GET["s"]==$id)
				{
					$return_url__ = WEA_PROTOCOL.$_SERVER['HTTP_HOST'];
					if (isset($_COOKIE["POST_".$page."_".$id]) and wp_remote_retrieve_body(wp_remote_get(URL_WES . "wea-verif.php?id=$id_allopass&return_url=".urlencode($return_url__)."&code=" . $_GET["ok"])) == 1)
						{
						// Update stats for post
						$sql = "INSERT INTO ".TBL_STAT." (codes, id, post, date, stats) VALUES ('".$_GET["ok"]."','".$id."','".$page."','".date('Ymd')."','O');";		
						$wpdb->query($sql);
						$r_d = "<div style='color:#999999;background-color:#FFFF99;text-align:center;padding:5px;border:solid 1px #000000'><b><em>".WEA_BG_PAID_CONTENT."</em></b></div><br>";
						$r_f = "<br><br><div style='color:#999999;background-color:#FFFF99;text-align:center;padding:5px;border:solid 1px #000000'><b><em>".WEA_END_PAID_CONTENT."</em></b></div>";						
						$r = $r_d.do_shortcode($content).$r_f;
						}
						else
						{
						$r_d = "<div style='color:#FF0000;background-color:#FFFF99;text-align:center;padding:5px;border:solid 1px #000000'><b><em>".WEA_WRN_ERROR_CODE."</em></b></div>";
						$r_f = "<div style='color:#FF0000;background-color:#FFFF99;text-align:center;padding:5px;border:solid 1px #000000'><b><em>".WEA_WRN_END."</em></b></div>";
						$r =  $r_d.$r.$r_f;
						}
				}
				else
				{
				if ( isset($_COOKIE["POST_".$page."_".$id]))
						{
						if (wp_remote_retrieve_body(wp_remote_get(URL_WES . "wea-verif.php?id=$id_allopass&code=" . $_COOKIE["POST_".$page."_".$id])) == 1)
							{
							$r_d = "<div style='color:#999999;background-color:#FFFF99;text-align:center;padding:5px;border:solid 1px #000000'><b><em>".WEA_BG_PAID_CONTENT."</em></b></div><br>";
							$r_f = "<br><br><div style='color:#999999;background-color:#FFFF99;text-align:center;padding:5px;border:solid 1px #000000'><b><em>".WEA_END_PAID_CONTENT."</em></b></div>";								
							$r = $r_d.do_shortcode($content).$r_f;
							}
							else
							{
							$r_d = "<div style='color:#FF0000;background-color:#FFFF99;text-align:center;padding:5px;border:solid 1px #000000'><b><em>".WEA_WRN_EXPIRE_CODE."(&quot;".$_COOKIE["POST_".$page."_".$id]."&quot;)</em></b></div>";
							$r_f = "<div style='color:#FF0000;background-color:#FFFF99;text-align:center;padding:5px;border:solid 1px #000000'><b><em>".WEA_WRN_END."</em></b></div>";							
							$r = $r_d.$r.$r_f;
							}
						}
				}
		
		if (!is_single() and !is_page() ) $r='';
		
		return $r;
	}

function WEA_allopass() 
	{
		add_options_page('Easy Allopass', 'Wp Easy Allopass' , 'manage_options', 'Wp-Easy-Allopass', 'WEA_allopass_admin');
	}

function WEA_allopass_admin() 
{
	$lang = get_post_meta(2, "wea_lang", true);
	if ($lang=="") $lang="en";
	
	global $wpdb;
	$wpdb->flush();

	if (!current_user_can('manage_options'))
	{
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}

	$id_allopass = "";
	$txt_allopass = "";
	// Add new product
	if ( isset($_POST) and !isset($_GET["act"]) and !isset($_POST["del"]) )
	{
		if (isset($_POST["id_allopass"]))
		{
			if ( eregi("[[:digit:]]*\/[[:digit:]]*\/[[:digit:]]*",$_POST["id_allopass"]) )
			{
			$sql = "INSERT INTO ".TBL_PROD." (id_prod, prod_descr, last_update) VALUES ('".$_POST["id_allopass"]."','".addslashes($_POST["txt_allopass"])."','".date('Ymd')."');";		
			$wpdb->query($sql);
			echo '<div id="message" class="updated highlight"><p>'.WEA_MSG_ADD_SUCCESS.' <strong>'.$_POST["id_allopass"].'</strong></p></div>';
			}
			else
			{
			echo '<div id="message" class="error"><p>'.WEA_MSG_ADD_ERROR.'</p></div>';
			}
		}
	}
	// Update product infos
	if (isset($_POST) and isset($_GET["act"]) and $_GET["act"] == "save")
	{
		unset($_GET["act"]);
		if (isset($_POST["id_allopass"]))
		{
			if ( eregi("[[:digit:]]*\/[[:digit:]]*\/[[:digit:]]*",$_POST["id_allopass"]) )
			{
			$sql = "UPDATE ".TBL_PROD." SET id_prod='".$_POST["id_allopass"]."', prod_descr='".addslashes($_POST["txt_allopass"])."', last_update='".date('Ymd')."' WHERE id='".$_GET["edit"]."';";		
			$wpdb->query($sql);
			echo '<div id="message" class="updated highlight"><p>'.WEA_MSG_UPD_SUCCESS.' <strong>'.$_POST["id_allopass"].'</strong></p></div>';
			}
			else
			{
			echo '<div id="message" class="error"><p>'.WEA_MSG_UPD_ERROR.'</p></div>';
			}
		}
	}
	// Delete product infos
	if (isset($_POST) and isset($_POST["del"]) and $_POST["del"] != "")
	{
		if (isset($_POST["del"]))
		{
			$sql = "DELETE FROM ".TBL_PROD." WHERE id='".$_POST["del"]."';";		
			$wpdb->query($sql);
			echo '<div id="message" class="updated highlight"><p>'.WEA_MSG_DELPROD_1.' <strong>'.$_POST["id_allopass"].' '.WEA_MSG_DELPROD_2.'</strong></p></div>';
		}
	}	
	$id_allopass = "";
	$txt_allopass = WEA_DEFAULT_DESCR;
	$btn_action = WEA_BTN_ADDPROD;
	$url_action = "";
	$titre_box = WEA_TITLE_BOX;
	$btn_cancel ="";
	$hidden ="";
	$disabled = "";
	include_once(DIR_WEA . "wp-easy-allopass_admin.php");
}
?>