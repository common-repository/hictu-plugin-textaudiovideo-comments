<?php
/*
Plugin Name: Hictu Widget
Plugin URI: http://www.hictu.com/wordpress/plugin
Description: Easily include the Hictu Widget in WordPress posts.
Version: 1.1
Author: Abbeynet S.P.A.
Author URI: http://www.abbeynet.com/
*/


/*
Copyright (C) 2006 Abbeynet S.P.A.

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

http://www.gnu.org/licenses/gpl.txt

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

*/   

add_filter('comments_template', 'execute_widget');
add_action('comment_form', 'clean_comment_form');

add_action('admin_menu', 'hictuWidget_mgr_admin_menu');

function execute_widget(){
	print'<h3 id="hictu_comments">Responses to '.get_the_title($id).'</h3>
		<div style="clear:both"> <br /><br /> 
			<script type="text/javascript" src="http://www.hictu.com/plugins/widget/func.js.php?wid='.get_option('WIDGET_ID').'"></script>
			 <br /><br /></div>';
	
	 if(get_option('WIDGET_REPLACE') == 1)		 
		ob_start();		 
}

function clean_comment_form(){
	if(get_option('WIDGET_REPLACE') == 1)
		ob_end_clean();
}

function hictu_comments_number($comment_text) {
	if (get_option('WIDGET_REPLACE') == 1) {
		ob_start();
		the_permalink();
		$the_permalink = ob_get_contents();
		ob_end_clean();

		return '</a><noscript><a href="http://'.$the_permalink.'">View comments</a></noscript><a href="'.$the_permalink.'">View Comments</a>';
	} else {
		return $comment_text;
	}
}

add_filter('comments_number', 'hictu_comments_number');

function hictuWidget_mgr_admin_menu()
{
  add_options_page(__('Hictu Widget Manager Options'), __('Hictu'), 5, basename(__FILE__), 'hictuWidget_mgr_options_page');
}

function hictuWidget_mgr_add_options()
{
	add_option('WIDGET_ID', '');
	add_option('WIDGET_REPLACE', '1');
}

hictuWidget_mgr_add_options();

function hictuWidget_mgr_options_page()
{
  $updated = false;
  if (isset($_POST['hictu_widget_id']))
  {
    $hictu_widget_id = $_POST['hictu_widget_id'];
	$hictu_replace = $_POST['hictu_replace'];
	
	if($hictu_replace == '')
		$hictu_replace = 0;
	else	
		$hictu_replace = 1;
			
    update_option('WIDGET_ID', $hictu_widget_id);
	update_option('WIDGET_REPLACE', $hictu_replace);
    $updated = true;
  }
  $hictu_widget_id = get_option('WIDGET_ID');
  $hictu_replace = get_option('WIDGET_REPLACE');
  
  
  if($hictu_replace == 1){
	$hictu_replace = 1;
	$isChecked = 'checked="checked"';
  }
  else{
  	$isChecked = '';
	$hictu_replace = 0;
  }
  
  if ($updated)
  {
    ?>
    <div class="updated"><p><strong>Options saved.</strong></p></div>
    <?php
  }
  // print the form page
  ?>
  <div class="wrap">
	  <h2>Hictu Widget Settings</h2>
	  <form name="form1" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
		  <fieldset class="options">
			  <legend>Hictu Widget in WordPress Pages</legend>
			  <table width="100%" cellspacing="2" cellpadding="5" class="editform">
			  <tr valign="top">
				<th width="25%" scope="row">Widget ID (wid):</th>
				<td><input name="hictu_widget_id" type="text" value="<?php echo $hictu_widget_id; ?>" style="width:60px" maxlength="8"/></td>
			 </tr>
			 <tr>
			 	<th scope="row">Replace Wordpress comments:</th>
				<td><input name="hictu_replace" type="checkbox" <? echo $isChecked ?> /></td>
			 </tr>
			 <tr valign="top">	
				<td colspan="2">
					<span style="font-size:9px">
						Take your <strong>Hictu Widget ID</strong> going to <strong><a href="http://www.hictu.com">www.hictu.com</a></strong> -> <strong>Widget</strong> -> <strong>Generate</strong> <br />
						Your Widget ID is the number in bold in the code automatically generated <br /><br />
						<b>i.e.</b> &lt;script type="text/javascript" src="http://www.hictu.com/widget/func.js.php?wid=<b>XXX</b>"&gt;&lt;/script&gt;
					</span>
				</td>
			  </tr>
			  </table>
		  </fieldset>
		  <p class="submit">
		    <input type="submit" name="update_favicons" value="Update Options &raquo;" />
		  </p>
	  </form>
  </div>
  <?php 
}
?>