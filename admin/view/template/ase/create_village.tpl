<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
          <img id="processing_image" src="https://unnati.world/shop/admin/view/image/processing_image.gif" style="height: 50px;width: 50px;display: none;" />
        
          <button type="submit" id="submit_button" form="form-user" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1>Create Village</h1>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i></h3>
      </div>
      <div class="panel-body">

        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-user" class="form-horizontal">
            <input type="hidden" name="logged_user" value="<?php echo $logged_user; ?>" />

                    
          <div class="form-group required">
                <label class="col-sm-2 control-label" style="float: left;" for="input-username">Select Store</label>
            <div class="col-sm-10">
                <div class="input-group col-sm-12">
              
                <select required name="filter_store" id="input-store" class="form-control" >
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
<div class="col-sm-8" style="font-weight: bold;padding-top: 9px;font-size: 18px;" id="for_current_credit" ></div>
             </div>
          </div>            
          
          

          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-transaction_number">Village name</label>
            <div class="col-sm-10">
              <input type="text" required name="village_name" value="<?php echo $village_name; ?>" placeholder="Village name" id="input-village_name" class="form-control" />
             
            </div>
          </div>
            <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-transaction_number">District</label>
            <div class="col-sm-10">
              <input type="text" required name="district" value="" placeholder="District" id="district" class="form-control" />
              
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-branch_code">Pin code</label>
            <div class="col-sm-10">
              <input type="text" required name="pincode" value="<?php echo $branch_code; ?>" placeholder="Pin code" id="input-pincode" class="form-control" />
              
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

</script>
 <script>
    $(document).ready(function(){
    $('#form-user1').on('submit', function(e){
        
      var store=$("#input-store").val();
      var filter_trans_type=$("#filter_trans_type").val();
      var deposit_date=$("#input-deposit_date").val();
      var transaction_number=$("#input-transaction_number").val();
      var deposit_amount=$("#deposit_amount").val();
      var branch_code=$("#input-branch_code").val();
      var branch_location=$("#input-branch_location").val();
      
      if((store!="") && (filter_trans_type!="") && (deposit_date!="") && (transaction_number!="") && (deposit_amount!="") && (branch_code!="") && (branch_location!=""))
      {
       $("#submit_button").hide();  
       $("#processing_image").show();
      }
      
    });
});
 </script>
<?php echo $footer; ?> 