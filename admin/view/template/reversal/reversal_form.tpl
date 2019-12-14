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
      <?php if ($success) {  ?>
    <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default"> 
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">

        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-user" class="form-horizontal">
            <input type="hidden" name="logged_user" value="<?php echo $logged_user; ?>" />

                    
          <div class="form-group required">
                <label class="col-sm-2 control-label" style="float: left;" for="input-username">Select Store</label>
            <div class="col-sm-10">
                <div class="input-group col-sm-12">
              
                <select required name="filter_store" id="input-store" class="form-control" onchange="return get_stores_data();">
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
          

         

            <div class="form-group required" >
            <label class="col-sm-2 control-label" for="input-remarks ">Product Name </label>
            <div class="col-sm-10">
              <input type="text"  required name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
	 <input type="hidden" name="filter_name_id"  value="<?php echo $filter_name_id; ?>" id="filter_name_id"/>

<div class="col-sm-8" style="font-weight: bold;padding-top: 9px;font-size: 18px;" id="for_current_quantity" ></div>
            </div>
          </div>
          <div class="form-group required" >
            <label class="col-sm-2 control-label" for="input-remarks ">Quantity</label>
            <div class="col-sm-10">
              <input type="text"  required name="filter_quantity" value="<?php echo $filter_quantity; ?>" placeholder="Quantity" id="input-filter_quantity" class="form-control" />
	
            </div>
          </div>

         
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-remarks ">Remarks </label>
            <div class="col-sm-10">
              <textarea name="remarks" rows="5" placeholder="Remarks" id="input-remarks " class="form-control"><?php echo $remarks; ?></textarea>
            </div>
          </div>
            
          
           
        </form>
      </div>
    </div>
  </div>
</div>

//--></script> 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script>
<script type="text/javascript">


function get_stores_data(store_id)
{ /*
        $.ajax({
            url: 'index.php?route=reversal/reversal/get_store_product_data&token=<?php echo $token; ?>&store_id=' +  encodeURIComponent(store_id),
            //dataType: 'json',
            success: function(json) 
            {

             document.getElementById("for_current_credit").innerHTML='Current Quantity : -  '+json; 
            }
        });
    */
    return false;
}


</script>
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
	get_stores_data();
    }
});


function get_stores_data()
{ 
var store_id=document.getElementById("input-store").value;
var product_id=document.getElementById("filter_name_id").value;

//alert("before "+store_id+" ,"+product_id);
if((store_id!="") && (product_id!="") )
{
//alert(store_id+" ,"+product_id);

        $.ajax({
            url: 'index.php?route=reversal/reversal/get_store_product_data&token=<?php echo $token; ?>&store_id=' +  encodeURIComponent(store_id)+'&product_id=' +  encodeURIComponent(product_id),
            //dataType: 'json',
            success: function(json) 
            {
             //alert(json);
             document.getElementById("for_current_quantity").innerHTML='Current Quantity : -  '+json; 
            }
        });
   
      //document.getElementById("for_current_quantity").innerHTML='Current Quantity : -  '+store_id+" ,"+product_id; 
}
    return false;
}

</script>
<?php echo $footer; ?> 