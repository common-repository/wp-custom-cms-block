<style type="text/css">
.fset {
	width:800px;
	border:1px solid #CCC;
	padding:8px;
	margin:25px 0px;
	border-radius: 3px 3px 3px 3px;
	z-index:1;
}
.tarea {
}
.wp-editor-container {
	position:relative;
	width:783px;
	background-color:#FFF;
}
.padtop1 {
	padding-top:20px;
}
/****************************************/
.padtop-one {
	padding-top:5px;
}
.padtop-two {
	padding-top:10px;
}
.padding-one {
	padding:6px;
}
.padding-two {
	padding:10px;
}
.border {
	border:1px solid #fff !important;
}
.bd_bottom {
	border-bottom:1px solid #fff;
}
.bd_right {
	border-right:1px solid #fff;
}
.margin {
	margin:5px;
}
.wccb-input1
{
	width:195px;
	border: 1px solid #B8B8B8 !important;
    border-radius: 4px 4px 4px 4px !important;
    box-shadow: -1px 1px 2px rgba(0, 0, 0, 0.2) inset !important;
	color: #333 !important;
    font-size: 12px !important;
    height: 26px !important;
   	line-height: 12px !important;
    padding: 0 0.5em !important;
    vertical-align: middle !important;
}
.wccb-input2
{
	border: 1px solid #B8B8B8 !important;
    border-radius: 4px 4px 4px 4px !important;
    box-shadow: -1px 1px 2px rgba(0, 0, 0, 0.2) inset !important;
	color: #333 !important;
    font-size: 13px !important;
   	line-height: 13px !important;
    padding: 0 0.5em !important;
    vertical-align: middle !important;
}
.wccb-select1{
    border: 1px solid #B8B8B8 !important;
    border-radius: 4px 4px 4px 4px !important;
    box-shadow: -1px 1px 2px rgba(0, 0, 0, 0.2) inset !important;
    color: #333 !important;
    font-size: 12px !important;
    height: 26px !important;
	width:120px !important;
    line-height: 12px !important;
    vertical-align: middle !important;
	padding:3px 2px 2px 3px!important;
}
.shadow {
  -moz-box-shadow:    3px 3px 5px 6px #ccc;
  -webkit-box-shadow: 3px 3px 5px 6px #ccc;
  box-shadow:         0px 3px 5px 6px #ccc;
}
</style>
<div class="wrap">
  <h2 style="padding:10px 0px;">Wp Custom CMS Block</h2>
  <?php
			if($_REQUEST['submitBlck']){
				update_custom_cms_block();
			}
			if($_REQUEST['submitAddBlck']){
				add_new_blck();
			}
			if($_REQUEST['submitBlckUpdate']){
				update_blck();
			}
			print_cm_field();	
		?>
</div>
<?php	
	
	function add_new_blck(){
		$mgs = 0;
		global $wpdb;
	
		$btype = $_POST['block_type'];
		$block_label = $_POST['block_label'];
		$block_id = $_POST['block_id'];
		
		if($btype != '' && $block_label != '' && $block_id != '' ){
			
			$chk_b = $wpdb->get_results("select * from ".$wpdb->prefix."custom_cms_block where block_id='".$block_id."'");		
			
			if(!$chk_b){
			
				if(preg_match('/^[0-9a-zA-z-_\\s]{5,500}$/',$block_label) && preg_match('/^[a-z]{5,500}$/',$block_id)){
					$q_sts = $wpdb->query("INSERT INTO ".$wpdb->prefix."custom_cms_block (`block_type` ,`block_label` ,`block_id`)VALUES ('".$btype."', '".$block_label."', '".$block_id."')");
					if($q_sts){
						$mgs = 1;
					}
				}
				else{
					$mgs = 2;
				}//
			}
			else{
				$mgs = 4;
			}
		}
		else
		{
			$mgs = 3;
		}
		switch ($mgs) {
			  case 1:?>
<div id="message" class="updated fade">
  <p>Added successfuly</p>
</div>
<?php
				  break;
			  case 2:?>
<div id="message" class="error fade">
  <p>Error. Use letters,numbers,space,(_),(-) for block label and only lower case letters for block id.(5-500 character)</p>
</div>
<?php
				  break;
			  case 3:?>
<div id="message" class="error fade">
  <p>Error ! select type and enter valid block label and id.</p>
</div>
<?php break;
 case 4:?>
<div id="message" class="error fade">
  <p>Error ! Block id should be unique.</p>
</div>
<?php break;
		  }//
	}//
	
	function update_blck(){
		$mgs = 0;
		global $wpdb;
		;
		
		$sts = $_POST['action_type'];
		$id = $_POST['u_id'];
		$btype = $_POST['block_type'];
		$block_label = $_POST['block_label'];
		$block_id = $_POST['block_id'];
		
		
		if($sts == 1){
			 $wpdb->query("UPDATE ".$wpdb->prefix."custom_cms_block SET 
							`block_type` = '".$btype."',
							`block_label` = '".$block_label."',
							`block_id` = '".$block_id."' WHERE `id` =".$id);						
			
			$mgs = 1;
			
		}
		if($sts == 2)
		{
			$q_sts2 = $wpdb->query("delete from ".$wpdb->prefix."custom_cms_block where id =".$id."");
			if($q_sts2){
				delete_option($block_id);
				$mgs = 2;
			}
		}
	
		switch ($mgs) {
			  case 1:?>
<div id="message" class="updated fade">
  <p>Updated successfuly</p>
</div>
<?php
				  break;
			  case 2:?>
<div id="message" class="updated fade">
  <p>Deleted successfuly</p>
</div>
<?php
				  break;
			  case 0:?>
<div id="message" class="error fade">
  <p>Error ! Try again later.</p>
</div>
<?php break;
		  }//
	}
	
	function update_custom_cms_block(){
		$ok = 0;
		$block_name = $_POST['bl_name'];
		$blck_type = $_POST['bl_typ'];
			
		if($_POST[$block_name])
		{
			if($blck_type == 'text-field')
			{
				update_option($block_name,strip_tags($_POST[$block_name]));
				$ok=1;
			}
			else{
				update_option($block_name,$_POST[$block_name]);
				$ok=1;
			}
		}
		
		if($ok){
				?>
<div id="message" class="updated fade">
  <p>Changes saved successfully.</p>
</div>
<?php
		}
		else{
			?>
<div id="message" class="error fade">
  <p>Failed to save.</p>
</div>
<?php
		}
	}//

	function print_cm_field(){
		 
			global $wpdb;
			$table_prefix = $wpdb->prefix;
			
			$block = $wpdb->get_results('select * from '.$wpdb->prefix.'custom_cms_block');
	?>
<div style="padding-top:15px; background-color:#ECECEC; width:920px; border:1px solid #999;border-radius: 3px 3px 3px 3px;" class="shadow">
  <div style="padding:10px 0px 15px 50px;">
    <fieldset class="fset">
      <legend><strong>Custom CMS Block</strong></legend>
      <form method="post" name="addBlock">
        <table>
          <tr>
            <td style="padding:0px 20px 0px 10px;"><select name="block_type" class="wccb-select1">
                <option value="">Select Type</option>
                <option value="text-field">Text</option>
                <option value="text-area">Text-area</option>
                <option value="fld_editor">Editor</option>
              </select></td>
            <td style="padding:0px 10px 0px 0px;">Block Label</td>
            <td style="padding:0px 10px 0px 0px;"><input type="text" name="block_label" class="wccb-input1" /></td>
            <td style="padding:0px 10px 0px 0px;">Block ID</td>
            <td style="padding:0px 10px 0px 0px;"><input type="text" name="block_id" class="wccb-input1" /></td>
            <td style="padding-top:10px;  padding-bottom:10px;"><input type="submit" name="submitAddBlck" value="Add New" class="button-secondary" /></td>
          </tr>
        </table>
      </form>
      <?php if(sizeof($block)):?>
      <div style="padding:10px 0px 15px 13px;">
        <table class="border" style="width:770px;" cellpadding="0" cellspacing="0">
          <tr>
            <td class="bd_right bd_bottom padding-one" align="center"><strong>Type</strong></td>
            <td class="bd_right bd_bottom padding-one" align="center"><strong>Block Label</strong></td>
            <td class="bd_right bd_bottom padding-one" align="center"><strong>Block ID</strong></td>
            <td class="bd_right bd_bottom padding-one" align="center"><strong>Shortcode</strong></td>
            <td class="padding-one bd_bottom" align="center"><strong>Action</strong></td>
          </tr>
          <?php $count = 1;foreach($block as $blk){?>
          <form name="UpdateBlock" method="post">
            <tr>
              <td class="bd_right <?php if($count < sizeof($block)){echo 'bd_bottom';}?> padding-one"><select name="block_type" style="width:100px !important;" class="wccb-select1">
                  <option value="text-field" <?php if($blk->block_type == "text-field")echo 'selected="selected"';?>>Text</option>
                  <option value="text-area" <?php if($blk->block_type == "text-area")echo 'selected="selected"';?>>Text-area</option>
                  <option value="fld_editor" <?php if($blk->block_type == "fld_editor")echo 'selected="selected"';?>>Editor</option>
                </select></td>
              <td class="bd_right <?php if($count < sizeof($block)){echo 'bd_bottom';}?> padding-one"><input type="text" name="block_label" style="width:160px;" value="<?php echo $blk->block_label;?>" class="wccb-input1" /></td>
              <td class="bd_right <?php if($count < sizeof($block)){echo 'bd_bottom';}?> padding-one"><input type="text" name="block_id" style="width:160px;" value="<?php echo $blk->block_id;?>" class="wccb-input1" /></td>
              <td class="bd_right <?php if($count < sizeof($block)){echo 'bd_bottom';}?> padding-one"><?php echo '[block id="'.$blk->id.'"]';?></td>
              <td class="padding-one <?php if($count < sizeof($block)){echo 'bd_bottom';}?>"><select name="action_type" style="width:100px !important;" class="wccb-select1">
                  <option value="1">Save</option>
                  <option value="2">Delete</option>
                </select>
                <input type="hidden" value="<?php echo $blk->id;?>" name="u_id" />
                <input type="submit" name="submitBlckUpdate" value="Go" class="button-secondary" /></td>
            </tr>
          </form>
          <?php $count++;}?>
        </table>
      </div>
      <?php endif;?>
    </fieldset>
  </div>
  <!---------------------------------------------------------------------------------------------------- -->
  <?php if(sizeof($block)):?>
  <div style="padding:10px 0px 15px 50px;">
    <?php 
		foreach($block as $blck){
	?>
    <form method="post" name="frmInblck">
      <fieldset class="fset">
        <legend><strong><?php echo $blck->block_label;?></strong></legend>
        <?php if($blck->block_type == 'text-field'):?>
        <input type="text" name="<?php echo $blck->block_id;?>" value="<?php echo strip_tags(get_option($blck->block_id));?>" style="width:783px; height:35px;" class="wccb-input2" />
        <?php endif;?>
        <?php if($blck->block_type == 'text-area'):?>
        <textarea type="text" name="<?php echo $blck->block_id;?>" style="width:783px; height:120px; resize:none; padding:6px !important" class="wccb-input2"><?php echo stripslashes(get_option($blck->block_id));?></textarea>
        <?php endif;?>
        <?php if($blck->block_type == 'fld_editor'):
				  		wp_editor(stripslashes(get_option($blck->block_id)),$blck->block_id);
                   endif;?>
        <div style="padding:10px 0px;">
        	<input type="hidden" name="bl_name" value="<?php echo $blck->block_id;?>" />
            <input type="hidden" name="bl_typ" value="<?php echo $blck->block_type;?>" />
          <input type="submit" name="submitBlck" value="Save Changes" class="button-primary" />
        </div>
      </fieldset>
    </form>
    <?php }?>
  </div>
  <?php endif;?>
</div>
<?php
	}