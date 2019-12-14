<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
     <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
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
                <label class="control-label" for="input-name">Select Store<?php //echo $entry_name; ?></label>
                             <select name="filter_store" id="filter_store" style="width: 100%;"  class="form-control">
                  <option value="0">Select Store<?php //echo $text_all_status; ?></option>
                  <?php foreach ($storess as $store) { ?>
                  <?php if ($store['store_id'] == $store_id) { ?>
                  <option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
              
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-model">Select Month<?php //echo $entry_model; ?></label>
              <select name="filter_month" class="form-control">
                  <option value="0">Select Month<?php //echo $text_all_status; ?></option>
                    <option value="1">January</option>
                      <option value="2">February</option>
                        <option value="3">March</option>
                          <option value="4">April</option>
                            <option value="5">May</option>
                              <option value="6">June</option>
                                <option value="7">July</option>
                                  <option value="8">August</option>
                                    <option value="9">September</option>
                                      <option value="10">October</option>
                                        <option value="11">November</option>
                                          <option value="12">December</option>
                                            
                                
                    
                </select>
              </div>
            </div>
              
              
               <div class="col-sm-6 pull-right">
            
              <button type="button" id="button-filter" class="btn btn-primary pull-right">Submit</button>
            </div>
         
          </div>
            
        </div>
  <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-product">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                <td class="text-center">Store Name</td>  
			  
                  <td class="text-left">Month</td>
         
                  <td class="text-left">File</td>
                  <td class="text-right">Action</td>
                </tr>
              </thead>
              <tbody>
                <?php if ($stores) { ?>
                <?php foreach ($stores as $store) { //print_r($product); ?>
                <tr>
                  <td class="text-center"><?php if (in_array($store['margin_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $store['margin_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $store['margin_id']; ?>" />
                    <?php } ?></td>
	          <td class="text-center"><?php echo $store['store_name']; ?></td>
			  <td class="text-center"><?php echo $store['month_name']; ?></td>
			  <td class="text-left"><a target="_blank" href="<?php echo $store['upload_margin']; ?>">View File</a></td>
           


                  
                  <td class="text-right">
                      <a href="<?php echo $store['edit']; ?>" style="width: 40px;margin-top: 2px;" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                      
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
	var url = 'index.php?route=margin/margin&token=<?php echo $token; ?>';



	var filter_store = $('select[name=\'filter_store\']').val();

	if (filter_store != '*') {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}
        var filter_month = $('select[name=\'filter_month\']').val();

	if (filter_month != '*') {
		url += '&filter_month=' + encodeURIComponent(filter_month);
	}

	location = url;
});

//--></script> 
  <script type="text/javascript"><!--

//--></script></div>
<?php echo $footer; ?>