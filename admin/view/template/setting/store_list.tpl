<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-store').submit() : false;"><i class="fa fa-trash-o"></i></button>
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

	 <button type="button" id="button-download" class="btn btn-primary pull-right"><i class="fa fa-download"></i> Download</button>

      </div>
      <div class="panel-body">

	<div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-end">Store Name</label>
                <div class="input-group">
                
                  <span class="input-group-btn">
                      
                  <input type="text" style="text-transform: uppercase" value="<?php echo $filter_store; ?>" placeholder="Store Name" name="filter_store" id="filter_store" class="form-control" />
                  
                
                  </span></div>
              </div>
              
            </div>
            <div class="col-sm-6">
                
              
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> Filter</button>
            </div>
          </div>
        </div>

        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-store">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-left"><?php echo $column_name; ?></td>
                    <td class="text-left">Store ID</td>
                    <td class="text-left">Store Status</td>
                    <td class="text-left">Store Type</td>
                  <td class="text-left" style="max-width: 250px;"><?php echo $column_url; ?></td>
                  <td class="text-right" style="min-width: 120px;max-width: 120px;"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($stores) { ?>
                <?php foreach ($stores as $store) { //print_r($store["store_id"]); ?>
                <tr>
                  <td class="text-center"><?php if (in_array($store['store_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $store['store_id']; ?>" />
                    <?php } ?></td>
                  <td class="text-left"><?php echo $store['name']; ?></td>
                    <td class="text-left"><?php echo $store['store_id']; ?></td>
                    <td class="text-left"><?php if($store['config_storestatus']=="0") { echo "Disable";} if($store['config_storestatus']=="1"){ echo "Enable"; } ?></td>
                    <td class="text-left"><?php echo $store['config_storetype']; ?></td>
                  <td class="text-left" style="max-width: 250px;"><?php echo $store['url']; ?></td>
                  <td class="text-right">
                      <a href="<?php echo $store['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                      <a href="<?php echo $store['document']; ?>" data-toggle="tooltip" title="View & edit document" class="btn btn-danger"><i  class="fa fa-pencil-square-o"></i></a>
                      <a href="<?php echo $store['view']; ?>" data-toggle="tooltip" title="View Details" class="btn btn-info"><i class="fa fa-eye"></i></a>
                  </td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
      </div>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"> 
             
              <?php echo $results; ?>  </div>
        </div>
    </div>
  </div>
</div>

<script type="text/javascript">
$('#button-filter').on('click', function() {
	url = 'index.php?route=setting/store&token=<?php echo $token; ?>';
	
        	var filter_store = $('#filter_store').val();
	
	if (filter_store!="") {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}

	
       
	location = url;
});

$('#button-download').on('click', function() {
	url = 'index.php?route=setting/store/download_licence&token=<?php echo $token; ?>';
	
        	var filter_store = $('#filter_store').val();
	
	if (filter_store!="") {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}

	
       
	location = url;
});
</script> 

<script type="text/javascript">
$('input[name=\'filter_store\']').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=setting/store/autocomplete&token=<?php echo $token; ?>&filter_store=' +  encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        label: item['name'],
                        value: item['store_id']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        $('input[name=\'filter_store\']').val(item['label']);
                //$('input[name=\'filter_name_id\']').val(item['value']);
    }
});
</script>
<?php echo $footer; ?> 