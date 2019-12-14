<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
     <div class="pull-right"><a href="index.php?route=margin/margin/setmargin&token=<?php echo $token;//echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="return confirm_delete();"><i class="fa fa-trash-o"></i></button>
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
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
		
      </div>
      <div class="panel-body">
      
        <div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-name">Select Product<?php //echo $entry_name; ?></label>
                 <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="Product Name<?php //echo $entry_name; ?>" id="input-name" class="form-control" />
              </div>
              
            </div>
            <div class="col-sm-6">
              <div class="form-group">
            <label class="control-label" for="input-model">Select Month<?php //echo $entry_model; ?></label>
             <select name="filter_month" id="month" class="form-control ">
				<option value=''>Select Month</option>
				<?php
				foreach($allmonth as $key=>$value){
				if($key>=7){
				$Y=2019;
				}else{
				$Y=date('Y');
				}
				?>
				<option value="<?php echo $key;?>" <?php echo ($key == $month_id) ? 'selected' : ''; ?>><?php echo $value.', '.$Y; ?></option>
				<?php
				}
				?>
				</select>
              </div>
            </div>
              
              
               <div class="col-sm-6 pull-right">
            
              <button type="button" id="button-filter" class="btn btn-primary pull-right">Search</button>
            </div>
         
          </div>
            
        </div>
         <form action="<?php echo $deletemargin; ?>" method="post" enctype="multipart/form-data" id="form-product">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                <td class="text-center">Product Name</td>  
			  
                  <td class="text-center">Month</td>
         
                  <td class="text-center">Margin</td>
                  <td class="text-center">Action</td>
                </tr>
              </thead>
              <tbody>
                <?php if ($margins) { ?>
                <?php foreach ($margins as $margin) { //print_r($product); ?>
                <tr>
                  <td class="text-left"><?php if (in_array($margin['margin_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $margin['margin_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $margin['margin_id']; ?>" />
                    <?php } ?></td>
				   <td class="text-center"><?php echo $margin['product_name']; ?></td>
				   <td class="text-center"><?php echo $margin['month_year']; ?></td>
				   <td class="text-center"><?php echo $margin['margin']; ?></td>
                  <td class="text-center">
                      <a href="<?php echo $margin['editmargin']; ?>" style="width: 40px;margin-top: 2px;" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                  </td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div> 
              
      </div>
    </div>
  </div>
  <script type="text/javascript">
  function confirm_delete()
  {
	  //var cnfrm=confirm('<?php echo $text_confirm; ?>') ? $('#form-location').submit() : false;
	  alertify.confirm('Are you sure ?',
                function(e){ 
                    if(e)
					{
                    
                    $("#form-product").submit();
                }else{
                    
                    return false;
                }
            }
                    
                        );
                 $("#alertify-ok").html('Continue'); 
	  return false;
  }
  $("#filter_store").select2();
  <!--
$('#button-filter').on('click', function() {
	var url = 'index.php?route=margin/margin/margingetList&token=<?php echo $token; ?>';



	var filter_name = $('input[name=\'filter_name\']').val();

	if (filter_name != '*') {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
        var filter_month = $('select[name=\'filter_month\']').val();

	if (filter_month != '*') {
		url += '&filter_month=' + encodeURIComponent(filter_month);
	}

	location = url;
});

//--></script> 
 <script type="text/javascript"><!--
$('input[name=\'filter_name\']').autocomplete({
	'source': function(request, response) {
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
	}
});


//--></script></div>
<?php echo $footer; ?>