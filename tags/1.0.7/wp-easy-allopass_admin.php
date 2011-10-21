<?php
global $wpdb;
$wpdb->flush();
$btn_retour="";
if (isset($_GET['edit']) and $_GET['edit'] != "")
	{
	$re_ = $wpdb->get_row("SELECT * FROM ".TBL_PROD." WHERE id='".$_GET['edit']."';");
	$id_allopass = $re_->id_prod;
	$txt_allopass = stripslashes($re_->prod_descr);
	$btn_action = WEA_SAVE;
	$titre_box = WEA_MODIF;
		if (http_build_query($_GET) != "")
		{
		$url_action = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'] . '?' . http_build_query($_GET).'&act=save';
		$g__ = $_GET;
		unset($g__["edit"]);
		$url_cancel= "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'] . '?' . http_build_query($g__);
		$btn_cancel= "<input class='button-secondary' type='button' name='cancel' value='".WEA_CANCEL."' onClick='window.location=\"".$url_cancel."\";' />";
		$btn_retour= "<input class='button-primary' type='button' name='retour' value='".WEA_GOBACK."' onClick='window.location=\"".$url_cancel."\";' />";
		}
		else
		{
		$url_action = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']. '?act=save';
		$url_cancel= "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
		$btn_cancel= "<input class='button-secondary' type='button' name='cancel' value='".WEA_CANCEL."' onClick='window.location=\"".$url_cancel."\";' />";
		}
	}
if (isset($_GET['stats']))
	{
		$g__ = $_GET;
		unset($g__["stats"]);
		$url_retour= "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'] . '?' . http_build_query($g__);
		$btn_retour= "<input class='button-primary' type='button' name='retour' value='".WEA_GOBACK."' onClick='window.location=\"".$url_retour."\";' />";
	}	
if (isset($_GET['del']))
	{
	$re_ = $wpdb->get_row("SELECT * FROM ".TBL_PROD." WHERE id='".$_GET['del']."';");
	$id_allopass = $re_->id_prod;
	$txt_allopass = stripslashes($re_->prod_descr);
	$btn_action = WEA_DEL;
	$titre_box = WEA_DEL2;
	$disabled='readonly=readonly';
		if (http_build_query($_GET) != "")
		{
		$g__ = $_GET;
		unset($g__["del"]);
		$url_action = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'] . '?' . http_build_query($g__);
		$url_cancel= "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'] . '?' . http_build_query($g__);
		$btn_cancel= "<input class='button-secondary' type='button' name='cancel' value='".WEA_CANCEL."' onClick='window.location=\"".$url_cancel."\";' />";
		$btn_retour= "<input class='button-primary' type='button' name='retour' value='".WEA_GOBACK."' onClick='window.location=\"".$url_cancel."\";' />";
		$hidden= "<input type=hidden name=del value=".$_GET['del'].">";
		}
		else
		{
		$url_action = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
		$url_cancel= "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
		$btn_cancel= "<input class='button-secondary' type='button' name='cancel' value='".WEA_CANCEL."' onClick='window.location=\"".$url_cancel."\";' />";
		}	
	}
		
function CreerLiens($lien, $debut, $nbr_par_page, $id_count)
{
    echo "<center><div class='tablenav-pages'>";
    if ($id_count >= ($nbr_par_page + 1))
	{
        // liens vers pages precedentes/suivantes
        if ($debut - $nbr_par_page >= 0) {
            echo " <a href='$lien" . 0 . "'>&lt;&lt;</a>\n ";
            echo " <a href='$lien" . ($debut - $nbr_par_page) . "'>&lt;</a> ";
        } 


        $p = 1;
        for ($i = 0 ;
            $i < $id_count ;
            $i += $nbr_par_page) {
            if (($debut / $nbr_par_page) == ($p-1)) echo " <b>$p</b> ";
            else echo " <a href='$lien$i'>$p</a>\n ";
            $p++;
        } 


        if ($debut + $nbr_par_page < $id_count) {
            echo " <a href='$lien" . ($debut + $nbr_par_page) . "'>&gt;</a> ";
            $pos = ($id_count - ($id_count % $nbr_par_page));
            if (($id_count % $nbr_par_page) == 0) $pos = $pos - $nbr_par_page;
            echo " <a HREF='$lien$pos'>&gt;&gt;</a>\n ";
        } 
        echo "\n<br>";
    } 
    echo "</div></center>";
}
if (isset($_GET['debut'])){$debut = $_GET['debut'];}
if (isset($_GET['debus'])){$debus = $_GET['debus'];}
if (empty($debut)) $debut = 0;
if (empty($debus)) $debus = 0;

foreach($_GET AS $key=>$value)
{
	if ( $key == 'debut' or $key == 'debus') 
		{
			unset($_GET[$key]);
		}
}

if (http_build_query($_GET) != "")
	{
	$lien  = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'] . '?' . http_build_query($_GET).'&debut=';
	$lien2 = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'] . '?' . http_build_query($_GET).'&debus=';
	}
	else
	{
	$lien  = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']. '?debut=';
	$lien2 = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']. '?debus=';
	}
	
if (http_build_query($_GET) != "")
	{
	$linkfr  = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'] . '?' . http_build_query($_GET).'&lang=fr';
	$linken  = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'] . '?' . http_build_query($_GET).'&lang=en';
	}
	else
	{
	$linkfr  = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']. '?lang=fr';
	$linken  = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']. '?lang=en';
	}
		
$nbr_par_page = 10;
$id_count = 0;
?>
<div class='wrap'>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="2" valign="middle"><div id="icon-options-general" class="icon32"></div>
    <h2><?php echo WEA_PLUGIN ?></h2></td>
  </tr>
<?php if ($btn_retour!="")
 {
?>
  <tr>
    <td colspan="2" valign="middle">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" valign="middle"><?php echo $btn_retour ?></td>
  </tr>
<?php
 }
?>  
<?php if (!isset($_GET["stats"]))
{  
?> 
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td width="534">
	<div align="left">
	<form method=post action="<?php echo $url_action ?>"><?php echo $hidden ?>
	<table width="534" class="widefat">
	<thead>
      <tr>
        <th><div align="left"><strong><?php echo $titre_box ?></strong></div></th>
        <th><div align="right"><?php echo WEA_CHOOSELANG ?>&nbsp;&nbsp;<a href="<?php echo $linkfr ?>"><img src="<?php echo URL_WEA ?>image/fr.gif" width="23" height="16" border="0" align="absmiddle"></a>&nbsp;&nbsp;<a href="<?php echo $linken ?>"><img src="<?php echo URL_WEA ?>image/en.gif" width="23" height="16" border="0" align="absmiddle"></a></div></th>
      </tr>
    </thead>
	<tbody>
      <tr>
        <td width="25%"><div align="left"><?php echo WEA_YOUR_PROD_ID ?>: </div></td>
        <td width="75%"><div align="left"><input type=text name=id_allopass value="<?php echo $id_allopass ?>" style='width:200px' <?php echo $disabled ?>></div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="left"><em> <?php echo WEA_ID_AVLBL_ON ?> <a href="http://<?php echo $lang ?>.allopass.com/advert?from=sponsorship&target=1605852" target="_blank"><?php echo WEA_AP_ACCOUNT ?></a>.<br />
  <?php echo WEA_IDEXAMPLE ?>: </em><font color='#000080'>123456</font>/<font color='#008000'>789012</font>/<font color='#FF0000'>3456789</font> &lt;&lt; <a href="<?php echo URL_HELP.$lang."/" ?>" target="_blank"><?php echo WEA_NEED_HELP ?></a></div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="left"><?php echo WEA_NOT_YET_AP ?> <a href="http://<?php echo $lang ?>.allopass.com/advert?from=sponsorship&target=1605852" target="_blank"><?php echo WEA_CLICK_HERE ?></a>.</div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td valign="top"><div align="left"><?php echo WEA_DESCR_PROD ?>: </div></td>
        <td><textarea name=txt_allopass style='width:95%;height:60px;text-weight:bolder' <?php echo $disabled ?>><?php echo $txt_allopass?></textarea></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="left"><em><?php echo WEA_DESCR_EXPL ?></em></div></td>
      </tr>
      
      <tr>
        <td>&nbsp;</td>
        <td><div align="left"><input class=button-primary type=submit value="<?php echo $btn_action ?>"><?php echo $btn_cancel ?></div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2"><em><?php echo WEA_NOTES_BOX_1 ?>: <font color="#FF0000"><strong>&quot;<?php echo get_bloginfo('siteurl'); ?>&quot;</strong></font>. <?php echo WEA_NOTES_BOX_2 ?></em></td>
        </tr>
    </tbody>  
    </table>
	</form>
	</div></td>
    <td><div align="left"><img src="<?php echo URL_WEA ?>image/allo<?php echo $lang ?>.gif" width="534" height="289" /></div></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
<?php
} 
else
{
$re_ = $wpdb->get_row("SELECT * FROM ".TBL_PROD." WHERE id='".$_GET['stats']."';");
?>
  
  
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">
	<div align="left">
	<table class="widefat" width="100%">
	  <thead>
	    <tr>
          <th colspan="6"><div align="left"><strong><?php echo WEA_STATS ?> </strong>(<?php echo WEA_REF ?> : <?php echo $re_->id_prod ?>)</div></th>
        </tr>
        <tr>
          <th><div align="left"><?php echo WEA_NUMBER ?></div></th>
          <th><div align="left"><?php echo WEA_AP_CODE ?></div></th>
          <th><div align="left"><?php echo WEA_DATE ?></div></th>
          <th><div align="center"><?php echo WEA_ID_POST ?></div></th>
          <th width="180"><div align="center"><?php echo WEA_STATUT ?></div></th>
          <th width="50">&nbsp;</th>
        </tr>
	  </thead>
	  <tbody>
	  <?php
		$wpdb->flush();
		$id_count = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(*) FROM ".TBL_STAT." WHERE id = '".$_GET["stats"]."';") );
		if ($id_count > 0)
		{
			// Manage link admin
			$get___ = $_GET;
			foreach($get___ AS $key=>$value)
			{
				if ($key == 'edit' or $key == 'stats' or $key == 'del') 
					{
						unset($get___[$key]);
					}
			}
			
			if (http_build_query($get___) != "")
				{
				$link = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'] . '?' . http_build_query($get___).'&';
				}
				else
				{
				$link = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']. '?';
				}
		
			$row = $wpdb->get_results("SELECT * FROM ".TBL_STAT." WHERE id = '".$_GET["stats"]."' LIMIT ".$debut.",".$nbr_par_page."");
			foreach ($row as $row) 
			{
			// Date
			$Y__ = substr($row->date, 0, 4);
			$m__ = substr($row->date, 4, 2);
			$d__ = substr($row->date, 6, 2);
			$date__ = $d__."/".$m__."/".$Y__;
		    ?>
			<tr>
			  <td><div align="left"><?php echo $row->id_pmt ?></div>
			    <div align="center"></div></td>
			  <td><div align="left"><?php echo $row->codes ?></div></td>
			  <td><div align="left"><?php echo $date__ ?></div></td>
			  <td><div align="center"><a href="<?php echo get_permalink($row->post) ?>" target="_blank" title="<?php echo get_the_title($row->post) ?>"><?php echo $row->post  ?></a></div></td>
			  <td width="180"><div align="center"><?php echo $row->stats ?></div>
			    <div align="center"></div></td>
			  <td width="50"></td>
			</tr>
		    <?php 
			}
		}
		else
		{
		    ?>
			<tr>
			  <td colspan="6"><div align="center"><br>
			  <?php echo WEA_NOT_YET_SALE ?><br>
			  &nbsp;</div></td>
			</tr>
		    <?php 
	    }
	  ?>		
	  		<tr>
			  <td colspan="6">
			  <?php CreerLiens($lien2, $debus, $nbr_par_page, $id_count); ?>			  </td>
			</tr>
	  </tbody>
      </table>
	</div></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
<?php
}
?>    
  <tr>
    <td colspan="2">
	<div align="left">
	<form method=post>
	  <table class="widefat" width="100%">
	  <thead>
	    <tr>
          <th colspan="7"><div align="left"><strong><?php echo WEA_YOUR_PROD_LIST ?></strong></div></th>
        </tr>
        <tr>
          <th><?php echo WEA_ID ?></th>
          <th width="160"><?php echo WEA_ID_PROD ?></th>
          <th><?php echo WEA_DESCRIPTION ?></th>
          <th><?php echo WEA_DATE_UPDATE ?></th>
          <th><div align="center"><?php echo WEA_SALE?></div></th>
          <th width="250"><?php echo WEA_SHORTCODE ?></th>
          <th width="50">&nbsp;</th>
        </tr>
	  </thead>
	  <tbody>
	  <?php
		$wpdb->flush();
		$id_count = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(*) FROM ".TBL_PROD." WHERE id_prod <> 'DontDelete';") );
		if ($id_count > 0)
		{
			// Manage link admin
			$get___ = $_GET;
			foreach($get___ AS $key=>$value)
			{
				if ($key == 'edit' or $key == 'stats' or $key == 'del') 
					{
						unset($get___[$key]);
					}
			}
			
			if (http_build_query($get___) != "")
				{
				$link = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'] . '?' . http_build_query($get___).'&';
				}
				else
				{
				$link = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']. '?';
				}
		
			$row = $wpdb->get_results("SELECT * FROM ".TBL_PROD." WHERE id_prod <> 'DontDelete' LIMIT ".$debut.",".$nbr_par_page."");
			foreach ($row as $row) 
			{
			// Stats
			$stat = 0;
			$stat = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(*) FROM ".TBL_STAT." WHERE id = '".$row->id."';") );
			
			// Date
			$Y__ = substr($row->last_update, 0, 4);
			$m__ = substr($row->last_update, 4, 2);
			$d__ = substr($row->last_update, 6, 2);
			$date__ = $d__."/".$m__."/".$Y__;
			
			// Description
			$descr___ = stripslashes($row->prod_descr);
			if(strlen($descr___)>=200){$descr___=substr($descr___,0,200) . "..." ;} 
			$descr___ = htmlspecialchars($descr___);
		    ?>
			<tr>
			  <td><div align="left"><?php echo $row->id ?></div></td>
			  <td width="160"><div align="left"><?php echo str_replace("/", "<strong><font color='#FF0000'>/</font></strong>", $row->id_prod) ?></div></td>
			  <td><div align="left"><?php echo $descr___ ?></div></td>
			  <td><div align="left"><?php echo $date__ ?></div></td>
			  <td><div align="center"><?php echo $stat ?></div></td>
			  <td width="250"><div align="left"><code><input type='text' value='<?php echo '[allopass id="'.$row->id.'"] ... [/allopass]' ?>' readonly='readonly' style='width:240px'/></code></div></td>
			  <td width="50">
				  <a href="<?php echo $link."edit=".$row->id ?>" title="<?php echo WEA_TTL_EDIT ?>"><img src="<?php echo URL_WEA ?>image/edit.png" width="12" height="13" border="0" align="absmiddle"/></a>
				  <a href="<?php echo $link."stats=".$row->id ?>" title="<?php echo WEA_TTL_STAT ?>"><img src="<?php echo URL_WEA ?>image/fiche.png" width="12" height="13" border="0" align="absmiddle"/></a>
				  <a href="<?php echo $link."del=".$row->id ?>" title="<?php echo WEA_TTL_DEL ?>"><img src="<?php echo URL_WEA ?>image/suppr.png" width="12" height="13" border="0" align="absmiddle"/></a>			  </td>
			</tr>
		    <?php 
			}
		}
		else
		{
		    ?>
			<tr>
			  <td colspan="7"><div align="center"><br><?php echo WEA_NOT_YET_PROD ?><br>&nbsp;</div></td>
			</tr>
		    <?php 
	    }
	  ?>		
	  		<tr>
			  <td colspan="7">
			  <?php CreerLiens($lien, $debut, $nbr_par_page, $id_count); ?>			  </td>
			</tr>
	  </tbody>
      </table>
	</form>
	</div>	</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><div align="left">
      <table class="widefat">
	  <thead>
        <tr>
          <th><div align="left"><strong><?php echo WEA_INSTRUCTION ?></strong></div></th>
        </tr>
	  </thead>
	  <tbody>
        <tr>
          <td><div align="left"><?php echo WEA_TODO ?></div></td>
          </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><div align="left"><?php echo WEA_FREE_CONTENT ?> ... <font color="#FF0000"><strong><input type='text' value='[allopass id=&quot;X&quot;] ...<?php echo WEA_PAID_CONTENT ?>... [/allopass]' readonly='readonly' style='width:300px;text-align:center' /></strong></font> ... <?php echo WEA_FREE_CONTENT ?>...</div></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><div align="left"><strong><?php echo WEA_TTL_NOTES ?></strong></div></td>
        </tr>
        <tr>
          <td>
		  	<div align="left">
			<strong>&raquo; </strong><?php echo WEA_NOTES_1 ?><br>
            <strong>&raquo; </strong><?php echo WEA_NOTES_2 ?>			</div>		  </td>
        </tr>
        <tr>
          <td><div align="left"><?php echo WEA_OTHER_NOTES_1 ?> <a href="http://<?php echo $lang ?>.allopass.com/advert?from=sponsorship&amp;target=1605852" target="_blank">Allopass</a> <?php echo WEA_OTHER_NOTES_2 ?></div></td>
        </tr>
	  </tbody>
      </table>
    </div></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><?php echo WEA_SUPPORT_US ?></td>
  </tr>
</table>
</div>
