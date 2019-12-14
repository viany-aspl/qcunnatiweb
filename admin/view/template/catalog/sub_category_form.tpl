<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
       
       <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
		
		</div>
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
	<?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> Add Product to Sub Category</h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-category" class="form-horizontal">
          
              
              <div class="tab-content">
               
               
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-name">Sub Category</label>
                    <div class="col-sm-10">
                      <select name="filter_category"  id="input-category" style="width: 100%;" class="select2 form-control">
                      <option value="">SELECT</option>
                  <?php foreach ($sub_categories as $sub_category) { ?>
                  <?php if ($sub_category['sid'] == $filter_category) { ?>
                  <option value="<?php echo $sub_category['sid']; ?>" selected="selected"><?php echo $sub_category['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $sub_category['sid']; ?>"><?php echo $sub_category['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-description">Product</label>
                    <div class="col-sm-10">
                      <input type="text" name="filter_name" value="" placeholder="Product Name" id="input-name" class="form-control" />
					  <input type="text" name="filter_name_id" value="" placeholder="Product Name" style="display: none;" id="input-name_id" class="form-control" />
                    </div>
                  </div>
                 
                  <div class="form-group">
               
               <button onclick="return check_product();" style="float: right;margin-right: 18px;" type="button" form="form-category" data-toggle="tooltip" title="Save" class="btn btn-primary">Submit</button>
            		</div>
	</div>
           
      
        </form>
      </div>
    </div>
  </div>
  
  <script type="text/javascript">
  $("#input-category").select2();
  
  function check_product()
  {
	  var product_id=$("#input-name_id").val();
	  var category_id=$("#input-category").val();
	  //alert(product_id+' ='+category_id);
	  if(!category_id)
	  {
		  alertify.error('Please Select Category ');
		  return false;
	  }
	  if(!product_id)
	  {
		  alertify.error('Please Select Product ');
		  return false;
	  }
	  $("#form-category").submit();
	  return false;
  }
  
$('input[name=\'filter_name\']').autocomplete({
	'source': function(request, response) {
		$('input[name=\'filter_name_id\']').val('');
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['product_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_name\']').val(item['label']);
		$('input[name=\'filter_name_id\']').val(item['value']);
	}
});
</script> 
 </div>
<?php echo $footer; ?>