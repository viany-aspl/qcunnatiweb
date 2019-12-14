<?php echo $header; ?><?php echo $column_left; ?>



<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-user" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-user" class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-username"><?php echo $entry_username; ?></label>
            <div class="col-sm-10">
              <input type="text" name="username" value="<?php echo $username; ?>" placeholder="<?php echo $entry_username; ?>" id="input-username" class="form-control" />
              <?php if ($error_username) { ?>
              <div class="text-danger"><?php echo $error_username; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-user-group"><?php echo $entry_user_group; ?></label>
            <div class="col-sm-10">
              <select name="user_group_id" id="input-user-group" class="form-control" onchange="return show_hide_store(this.value);">
                <?php foreach ($user_groups as $user_group) { ?>
                <?php if ($user_group['user_group_id'] == $user_group_id) { ?>
                <option value="<?php echo $user_group['user_group_id']; ?>" selected="selected"><?php echo $user_group['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $user_group['user_group_id']; ?>"><?php echo $user_group['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
            <!--store addition -->
            
            <div class="form-group" id="store_user_div" <?php if(($user_group_id==27) || ($user_group_id==28)){ ?> style="display: none;" <?php } ?>>
            <label class="col-sm-2 control-label" for="input-user-store"><?php echo $entry_user_store; ?></label>
            <div class="col-sm-10">
             
              
              <select class="select2 form-control" id="input-user-store" required="required" name="user_store_id[]" <?php if(($user_group_id==26) || ($user_group_id==22)) { ?> multiple="multiple" <?php } ?>  style="width: 100%">
                     
                    <?php //print_r($config_unit);
                    foreach ($user_stores as $user_store)
                    {
                      
                    ?>
                        <option value="<?php echo $user_store['store_id']; ?>"
                               <?php
                               foreach($user_store_id as $user_store_id1)
                                {
                                    if (in_array($user_store['store_id'], $user_store_id1))
                                    {
                                     ?>
                                        selected="selected"
                                <?php
                                    }
                                }
                                ?>
                                ><?php echo $user_store['name']; ?></option>
                    <?php
                    }
                    ?>
                        </select> 
            </div>
          </div>
          
            <!--store addition end-->
            <!--store addition -->
            <!--<div class="form-group">
            <label class="col-sm-2 control-label" for="input-user-store"><?php echo $entry_user_store; ?></label>
            <div class="col-sm-10">
              <select name="user_store_id" id="input-user-store" class="form-control">
                <?php foreach ($user_stores as $user_store) { ?>
                <?php if ($user_store['store_id'] == $user_store_id) { ?>
                <option value="<?php echo $user_store['store_id']; ?>" selected="selected"><?php echo $user_store['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $user_store['store_id']; ?>"><?php echo $user_store['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>-->
	<div class="form-group required">
                <label class="col-sm-2 control-label" for="input-storetype">Company</label>
                <div class="col-sm-10">
                  <select name="config_company" id="input-company" class="form-control" onchange="return getunitsbycompany(this.value);">
	       <option value="">Select Company</option>
	       <?php foreach ($companies_list as $company) { ?>
                    <?php if ($company['company_id'] == $config_company) { ?>
                    <option value="<?php echo $company['company_id']; ?>" selected="selected"><?php echo $company['company_name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $company['company_id']; ?>"><?php echo $company['company_name']; ?></option>
                    <?php } ?>
                    <?php } ?>

                  </select>
	    <?php if ($error_company) { ?>
                  <div class="text-danger"><?php echo $error_company; ?></div>
                  <?php } ?>
                </div>
              </div> 

	<div class="form-group required">
                <label class="col-sm-2 control-label" for="input-storetype">Company Unit</label>
                <div class="col-sm-10">
                  
                    <select class="select2" id="funit"  name="config_unit[]"   class="form-control" style="width: 100%">
                     
                    <?php //print_r($config_unit);
                    foreach ($unit_list as $unit_list1) 
                    {
                      
                          
                      
                    ?>
                        <option value="<?php echo $unit_list1['unit_id']; ?>"
                               <?php 
                               foreach($config_unit as $config_unit1)
                                {
                                    if (in_array($unit_list1['unit_id'], $config_unit1)) 
                                    { 
                                     ?>
                                        selected="selected"
                                <?php 
                                    }
                                }
                                ?>
                                ><?php echo $unit_list1['unit_name']; ?></option>
                    <?php
                    }
                    ?>
                        </select>
                  
	    <?php if ($error_unit) { ?>
                  <div class="text-danger"><?php echo $error_unit; ?></div>
                  <?php } ?>
                </div>
              </div>
           <div class="form-group">
            <label class="col-sm-2 control-label" for="input-cash"><?php echo "Allow Cash"; ?></label>
            <div class="col-sm-10">
              <select name="allow_cash" id="input-cash" class="form-control">
                <?php if ($allow_cash) { ?>
                <option value="0"><?php echo $text_disabled; ?></option>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <?php } else { ?>
                exit;
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <option value="1"><?php echo $text_enabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
            <div class="form-group">
            <label class="col-sm-2 control-label" for="input-credit_limt"><?php echo "Credit Amount Limit"; ?></label>
            <div class="col-sm-10">
                <input type="number" min="0" name="max_credit_amount" value="<?php echo $max_credit_amount; ?>" placeholder="<?php echo "Credit Amount Limit"; ?>" id="input-email" class="form-control" />
            </div>
          </div>
             <div class="form-group">
            <label class="col-sm-2 control-label" for="input-product_quantity_limt"><?php echo "Product Quantity Limit"; ?></label>
            <div class="col-sm-10">
                <input type="number" min="0" name="user_max_qunatity" value="<?php echo $user_max_qunatity; ?>" placeholder="<?php echo "Product Quantity Limit"; ?>" id="input-email" class="form-control" />
            </div>
          </div>
            <!--store addition end-->
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-firstname"><?php echo $entry_firstname; ?></label>
            <div class="col-sm-10">
              <input type="text" name="firstname" value="<?php echo $firstname; ?>" placeholder="<?php echo $entry_firstname; ?>" id="input-firstname" class="form-control" />
              <?php if ($error_firstname) { ?>
              <div class="text-danger"><?php echo $error_firstname; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-lastname"><?php echo $entry_lastname; ?></label>
            <div class="col-sm-10">
              <input type="text" name="lastname" value="<?php echo $lastname; ?>" placeholder="<?php echo $entry_lastname; ?>" id="input-lastname" class="form-control" />
              <?php if ($error_lastname) { ?>
              <div class="text-danger"><?php echo $error_lastname; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
            <div class="col-sm-10">
              <input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-image"><?php echo $entry_image; ?></label>
            <div class="col-sm-10"><a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
              <input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-password"><?php echo $entry_password; ?></label>
            <div class="col-sm-10">
              <input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" autocomplete="off" />
              <?php if ($error_password) { ?>
              <div class="text-danger"><?php echo $error_password; ?></div>
              <?php  } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-confirm"><?php echo $entry_confirm; ?></label>
            <div class="col-sm-10">
              <input type="password" name="confirm" value="<?php echo $confirm; ?>" placeholder="<?php echo $entry_confirm; ?>" id="input-confirm" class="form-control" />
              <?php if ($error_confirm) { ?>
              <div class="text-danger"><?php echo $error_confirm; ?></div>
              <?php  } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="status" id="input-status" class="form-control">
                <?php if ($status) { ?>
                <option value="0"><?php echo $text_disabled; ?></option>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <?php } else { ?>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <option value="1"><?php echo $text_enabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
$("#input-user-store").select2();
$("#funit").select2();  
function getunitsbycompany(company_id)
{
if(company_id!="") 
{
$.ajax({
		url: 'index.php?route=unit/unit/getunitsbycompany&token=<?php echo $token; ?>&company_id='+company_id,
		dataType: 'html',
		success: function(html) {
			//alert(html);
      			$('#funit').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}
}
</script>
<script>
function show_hide_store(selected_group)
{
    
    if((selected_group==27) || (selected_group==28))
    {
        
        document.getElementById("input-user-store").multiple = false;
        $("#store_user_div").hide();
    }
    else if(selected_group==26)
    {
        document.getElementById("input-user-store").multiple = true;
	$("#store_user_div").show();
    }
    else if(selected_group==22)
    {
	
        document.getElementById("input-user-store").multiple = true;
	//alert(selected_group); 
	$("#store_user_div").show();
    }
    else
    {
        document.getElementById("input-user-store").multiple = false;
        $("#store_user_div").show();
        
    }
    return false;
}
</script>
<?php echo $footer; ?> 