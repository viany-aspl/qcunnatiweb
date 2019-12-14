<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-user-group" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-user-group" class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              <?php if ($error_name) { ?>
              <div class="text-danger"><?php echo $error_name; ?></div>
              <?php  } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_access; ?></label>
            <div class="col-sm-10">
              <div class="well well-sm" style="height: 150px; overflow: auto;">
                <?php foreach ($permissions as $permission) { ?>
                <div class="checkbox">
                  <label>
                    <?php if (in_array($permission, $access)) { ?>
                    <input type="checkbox" name="permission[access][]" value="<?php echo $permission; ?>" checked="checked" />
                    <?php echo $permission; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="permission[access][]" value="<?php echo $permission; ?>" />
                    <?php echo $permission; ?>
                    <?php } ?>
                  </label>
                </div>
                <?php } ?>
              </div>
              <a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a></div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_modify; ?></label>
            <div class="col-sm-10">
              <div class="well well-sm" style="height: 150px; overflow: auto;">
                <?php foreach ($permissions as $permission) { ?>
                <div class="checkbox">
                  <label>
                    <?php if (in_array($permission, $modify)) { ?>
                    <input type="checkbox" name="permission[modify][]" value="<?php echo $permission; ?>" checked="checked" />
                    <?php echo $permission; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="permission[modify][]" value="<?php echo $permission; ?>" />
                    <?php echo $permission; ?>
                    <?php } ?>
                  </label>
                </div>
                <?php } ?>
              </div>
              <a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> 
		/ 
	<a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a>
	</div>
          </div>
          
             <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_mobile; ?></label>
            <div class="col-sm-10">
              <div class="well well-sm" style="height: 150px; overflow: auto;">
                <?php foreach ($mpermissions as $permission) { ?>
                <div class="checkbox">
                  <label>
                    <?php if (in_array(($permission['name'].'-'.$permission['category_id']), $mobile)) { ?>
                    <input type="checkbox" id="sbmt_btn" name="permission[mobile][]" value="<?php echo $permission['name'].'-'.$permission['category_id']; ?>" onclick="return open_model('<?php echo $permission['category_id']; ?>')"  checked="checked" />
                    <?php echo $permission['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" id="m<?php echo $permission['category_id']; ?>" name="permission[mobile][]" onclick="return open_model('<?php echo $permission['category_id']; ?>')"  value="<?php echo $permission['name'].'-'.$permission['category_id']; ?>" />
                    <?php echo $permission['name']; ?>
                    <?php } ?>
                  </label>
                </div>
                  <!-- Modal -->
            <div class="modal fade" id="myModal<?php echo $permission['category_id']; ?>" role="dialog">
            <div class="modal-dialog">
    
            <!-- Modal content-->
              <div class="modal-content">
              <div class="modal-header">
              
              <button type="button" class="close" id="partner_cncl_btn2" data-dismiss="modal">&times;</button>
              <h4 class="modal-title"><b>Update Sub Menu (<?php echo $permission['name'].'-'.$permission['category_id']; ?>) for <?php echo $name; ?></b></h4>
              </div>
              <div class="modal-body">
                  
              <div class="form-group">
              <label class="col-sm-2 control-label">Mobile Permission</label>
              <div class="col-sm-10">
              <div class="well well-sm" style="height: 150px; overflow: auto;">
              <?php  foreach (($msubpermissions[$permission['category_id']]) as $permissiont) { ?>
                <div class="checkbox">
                  <label>
                    <?php if (in_array($permissiont['category_id'], $access)) { ?>
                    <input type="checkbox" name="permission[mobile][child<?php echo $permissiont['category_id']; ?>][]" value="<?php echo $permissiont['category_id']; ?>" checked="checked" />
                    <?php echo $permissiont['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="permission[mobile][child<?php echo $permissiont['category_id']; ?>][]" value="<?php echo $permissiont['category_id']; ?>" />
                    <?php echo $permissiont['name']; ?>
                    <?php } ?>
                  </label>
                </div>
                <?php } ?>
            </div>
                  
              <a onclick="$(this).parent().find(':checkbox').prop('checked', true);">Select All</a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);">Un-Select All</a></div>
          </div>

              </div>
<!--              <a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> 
		/ 
	<a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a>-->
	</div>
          </div>
            
       
      </div>
                  <!-- end Modal -->
                <?php } ?>
                  </div>
              <!--<a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> 
		/ -->
	<a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a>
         <p>Please click on check box to select child </p>
	</div>
          </div>
 </form>
                    
    </div>
  </div>
</div>


<script type="text/javascript">
function open_model(category_id)
{
    //call ajax
  
    try{
    $('#m'+category_id).prop('checked',true);
    //end ajax
    $('#myModal'+category_id).modal('show');
    }catch(e){alert(e);}
    return true;
}
function submit_form(category_id) 
{
    var favorite = [];
    $.each($("input[name='permission"+category_id+"[]']:checked"), function(){            

        favorite.push($(this).val());

    });
    
     $.ajax({
            url: 'index.php?route=user/user_permission&token=<?php echo $token; ?>&category_id=' +  encodeURIComponent(category_id)+'&user_id=<?php echo $user_id; ?>',
            type: 'get',
            cache:false,
            beforeSend:function()
            {
                $("#sbmt_btn"+category_id).hide();
                $("#cncl_btn"+category_id).hide();
                $("#cr_img"+category_id).show();
            },
            data:{'selected':favorite},
            success: function(json) 
            {
                $("#sbmt_btn"+category_id).show();
                $("#cncl_btn"+category_id).show();
                $("#cr_img"+category_id).hide();
                //alert(json);
                alertify.success('Menu Updated Successfully');
            },
            error:function(json)
            {
                $("#sbmt_btn"+category_id).show();
                $("#cncl_btn"+category_id).show();
                $("#cr_img"+category_id).hide();
                //alert(JSON.stringify(json));
                alertify.success('Menu Updated Successfully');
            }
        
        });
    if(favorite=='')
    {
        $('#checkbox'+category_id).prop('checked', false);              
    }
    else
    {
        $('#checkbox'+category_id).prop('checked', true); // Checks it
    }
    $('#myModal'+category_id).modal('hide');
    return false;
    
}
</script> 
<?php echo $footer; ?> 