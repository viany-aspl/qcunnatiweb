
<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-store" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        </div>
      <h1>User Group Menu Update</h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>">User Group Menu Update</a></li>
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
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> User Group Menu Update</h3>
      </div>
      <div class="panel-body">
        <form action="" method="post" enctype="multipart/form-data" id="form-store" class="form-horizontal">
           <div class="form-group ">
              <label class="col-sm-2 control-label" for="input-meta-title">User Group</label>
              <div class="col-sm-10">                    
               <select name="filter_user[]" id="input-user" style="width:100%" class="select2 form-control" multiple="multiple" onchange="clear_user(this.value);">
					
						<?php foreach ($usergroup as $user) {  //print_r($user);?>
						<?php if ($user['user_group_id'] == $filter_user) {
						if($filter_user!=""){
						?>
						<option value="<?php echo $user['user_group_id']; ?>" selected="selected"><?php echo $user['name']; ?></option>
						<?php }} else { ?>
						<option value="<?php echo $user['user_group_id']; ?>"><?php echo $user['name']; ?></option>
						<?php } ?>
						<?php } ?>
				</select>                  
			    </div> 
		   </div>
			<div class="form-group ">
           <label class="col-sm-2 control-label" for="input-meta-title">Menu</label>
		   
           <div class="col-sm-10">
               <select name="filter_menu" id="input-menu" style="width:100%" class="select2 form-control" onchange="clear_menu(this.value);">
					<option selected="selected" value="">SELECT MENU</option>
					<?php foreach ($menus as $menu) {  ?>
					<?php if ($menu['category_id'] == $filter_menu) {
					if($filter_user!=""){
				    ?>
					<option value="<?php echo $menu['category_id']; ?>" selected="selected"><?php echo $menu['name']; ?></option>
					<?php }} else { ?>
					<option value="<?php echo $menu['category_id']; ?>"><?php echo $menu['name']; ?></option>
					<?php } ?>
					<?php } ?>										
				</select>
           </div> 
		    </div>
			<div class="form-group ">
              <label class="col-sm-2 control-label" for="input-meta-title">Sub Menu</label>
              <div class="col-sm-10">
              <select name="filter_submenu[]" style="width:100%" id="input-submenu"  multiple="multiple"  class="select2 form-control" >
					
						<?php 
										
					    ?>
					</select>
            </div>
            </div>
         
        </form>
      </div> </div>
    </div>
  </div>
  <script type="text/javascript">
  $("#input-user").select2();  
  $("#input-menu").select2();  
  $("#input-submenu").select2();  
  
  function clear_menu(data) {
//alert(data);
var menuid=data;
var url ="index.php?route=catalog/storemenu/getsubmenubyMenu&token=<?php echo $token; ?>&menuid="+menuid;
 
 //alert(url);
 $.ajax({ 
 type: 'post',
 url: url,
 //data: 'menuid='+menuid,
 //dataType: 'json',
 cache: false,

success: function(data) {

//alert(data);
 $("#input-submenu").html(data);
  
 }
 });
 }
$('select[name=\'config_template\']').on('change', function() {
	$.ajax({
		url: 'index.php?route=setting/setting/template&token=<?php echo $token; ?>&template=' + encodeURIComponent(this.value),
		dataType: 'html',
		beforeSend: function() {
			$('select[name=\'country_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
		},
		complete: function() {
			$('.fa-spin').remove();
		},
		success: function(html) {
      $('.fa-spin').remove();

			$('#template').attr('src', html);
		},
		error: function(xhr, ajaxOptions, thrownError) {

			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('select[name=\'config_template\']').trigger('change');
//--></script> 
  <script type="text/javascript"><!--
$('select[name=\'config_country_id\']').on('change', function() {
	$.ajax({
		url: 'index.php?route=setting/store/country&token=<?php echo $token; ?>&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'config_country_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
		},
		complete: function() {
			$('.fa-spin').remove();
		},
		success: function(json) {
      $('.fa-spin').remove();

			html = '<option value=""><?php echo $text_select; ?></option>';

			if (json['zone'] != '') {
				for (i = 0; i < json['zone'].length; i++) {
					html += '<option value="' + json['zone'][i]['zone_id'] + '"';
					
					if (json['zone'][i]['zone_id'] == '<?php echo $config_zone_id; ?>') {
						html += ' selected="selected"';
					}
					
					html += '>' + json['zone'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
			}

			$('select[name=\'config_zone_id\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('select[name=\'config_country_id\']').trigger('change');
//--></script></div>
<?php echo $footer; ?>