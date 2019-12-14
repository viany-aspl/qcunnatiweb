<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-user" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1>Set subsidy</h1>
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
      <?php if ($success) {  ?>
    <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> Set subsidy</h3>
      </div>
      <div class="panel-body">

        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-user" class="form-horizontal">
            <input type="hidden" name="logged_user" value="<?php echo $logged_user; ?>" />
        
          <div class="form-group required">
                <label class="col-sm-2 control-label" style="float: left;" for="input-username">Store</label>
            <div class="col-sm-10">
                <div class="input-group col-sm-12">
              
                <select style="width: 100%;" required name="filter_store" id="input-store" class="form-control">
                      <option selected="selected" value="">SELECT STORE</option>
                  <?php foreach ($stores as $store) {   ?>
                  <?php if ($store['store_id'] == $filter_store) {
                      if($filter_store!=""){
                      ?>
                  <option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
                      <?php }} else { ?>
                  <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>

                </div>

             </div>
          </div>            
          <div class="form-group required">
                <label class="col-sm-2 control-label" style="float: left;" for="input-username">Category</label>
            <div class="col-sm-10"><?php //echo $filter_category; ?>
                <div class="input-group col-sm-12">
              
                <select required name="filter_category" id="input-store" class="form-control">
                      <option selected="selected" value="">SELECT CATEGORY</option>
                  <?php foreach ($categories as $category) {   //print_r($categories);  ?>
                  <?php if ($category['category_id'] == $filter_category) {
                     
                      ?>
                  <option value="<?php echo $category['category_id']; ?>" selected="selected"><?php echo $category['category_name']; ?></option>
                      <?php } else { ?>
                  <option value="<?php echo $category['category_id']; ?>"><?php echo $category['category_name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>

                </div>

             </div>
          </div>  
           <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-transaction_number">Product Name</label>
            <div class="col-sm-10">
              <input type="text" required name="filter_name" value="<?php echo $filter_name; ?>" placeholder="Product Name" id="input-name" class="form-control" />
              <input type="hidden" name="filter_name_id"  value="<?php echo $filter_name_id; ?>" id="filter_name_id"/> 
            <br/>
            </div>
            <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-transaction_number">Subsidy</label>
            <div class="col-sm-10">
              <input type="text" style="width: 97%;margin-left: 10px;" required name="subsidy"  placeholder="Subsidy" id="input-subsidy" class="form-control" />
              
            </div>
          
            
        </form>
      </div>
    </div>
  </div>
</div>

  <script type="text/javascript">
  $("#input-store").select2();
  <!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script>
<script type="text/javascript">
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
                $('input[name=\'filter_name_id\']').val(item['value']);
    }
});
</script>
<?php echo $footer; ?> 