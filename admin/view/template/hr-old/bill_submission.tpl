<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
          <img id="processing_image" src="https://unnati.world/shop/admin/view/image/processing_image.gif" style="height: 50px;width: 50px;display: none;" />
        <button type="submit" form="form-user" id="submit_button" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1>Expense Book</h1>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> Expense Book</h3>
      </div>
      <div class="panel-body">

        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-user" class="form-horizontal">
            <input type="hidden" name="logged_user" value="<?php echo $logged_user; ?>" />
           
           
            <div class="form-group required">
                <label class="col-sm-2 control-label" style="float: left;" for="input-username">Select Store</label>
            <div class="col-sm-10">
                <div class="input-group col-sm-12">
              
                <select required name="filter_store" id="input-store" class="form-control" onchange="return get_stores_data(this.value);">
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
            <label class="col-sm-2 control-label" style="float: left;" for="input-username">Start Date</label>
            <div class="col-sm-10">
                <div class="input-group date">
              <input type="text" name="period_date_start" required data-date-format="YYYY-MM-DD"  placeholder="Start Date " id="input-period_date_start" class="form-control" />
              <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button> 
                  </span>
                </div>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" style="float: left;" for="input-username">End Date </label>
            <div class="col-sm-10">
                <div class="input-group date">
              <input type="text" name="period_date_end" required data-date-format="YYYY-MM-DD"  placeholder="End Date " id="input-period_date_end" class="form-control" />
              <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span>
                </div>
            </div>
          </div>
          <div class="form-group required">
                <label class="col-sm-2 control-label" style="float: left;" for="input-username">Select Month/Year</label>
            <div class="col-sm-10">
                <div class="input-group col-sm-6" style="float: left;padding-left: 0px;padding-right: 8px;">
              
                <select required name="filter_month" id="input-month" class="form-control" >
                      <option selected="selected" value="">SELECT MONTH</option>
                      <option value="1" >January</option>
                      <option value="2" >February</option>
		<option value="3" >March</option>
		<option value="4" >April </option>
		<option value="5" >May</option>
		<option value="6" >June </option>
		<option value="7" >July</option>
		<option value="8" >August </option>
		<option value="9" >September</option>
		<option value="10" >October</option>
		<option value="11" >November</option>
		<option value="12" >December</option>
                </select>

                </div>
	<div class="input-group col-sm-6">
              
                <select required name="filter_year" id="input-year" class="form-control"  >
                      <option selected="selected" value="">SELECT YEAR</option>
                      <option <?php if(date('Y')=='2016'){  ?> selected="selected" <?php } ?> value="2016" >2016</option>
                      <option <?php if(date('Y')=='2017'){  ?> selected="selected" <?php } ?> value="2017" >2017</option>
                      <option  <?php if(date('Y')=='2018'){  ?> selected="selected" <?php } ?>value="2018" >2018</option>
                      <option <?php if(date('Y')=='2019'){  ?> selected="selected" <?php } ?> value="2019" >2019</option>
	        <option <?php if(date('Y')=='2020'){  ?> selected="selected" <?php } ?>value="2020" >2020</option>
                </select>

                </div> 
             </div>
             </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-transaction_number">Amount</label>
            <div class="col-sm-10">
              <input type="text" required name="amount"  placeholder="Amount" id="input-amount" class="form-control" />
            </div>
          </div>
	<div class="form-group required">
            <label class="col-sm-2 control-label" for="input-transaction_number">Submit By</label>
            <div class="col-sm-10">
              <input type="text" required name="submitby"  placeholder="Submit By (Name)" id="input-sbumitby" class="form-control" />
            </div>
          </div>
	<div class="form-group required">
            <label class="col-sm-2 control-label" for="input-transaction_number">Approved By</label>
            <div class="col-sm-10">
              <input type="text" required name="approvedby"  placeholder="Approved By (Name)" id="input-approvedby" class="form-control" />
            </div>
          </div>
          <div class="form-group" > 
            <label class="col-sm-2 control-label" for="input-transaction_number">File</label>
            <div class="col-sm-10">
              <input type="file" name="file" style="padding: 0px;" id="input-amount" class="form-control" />
            </div>
          </div>
	<!--
	<div class="form-group required">
                <label class="col-sm-2 control-label" style="float: left;" for="input-username">Select Month/Year</label>
            <div class="col-sm-10">
                <div class="input-group col-sm-12" style="float: left;padding-left: 0px;padding-right: 8px;">
              
                <select required name="filter_month" id="input-month" class="form-control" multiple>
                      <option selected="selected" value="">SELECT MONTH</option>
                      <option value="1" >January</option>
                      <option value="2" >February</option>
		<option value="3" >March</option>
		<option value="4" >April </option>
		<option value="5" >May</option>
		<option value="6" >June </option>
		<option value="7" >July</option>
		<option value="8" >August </option>
		<option value="9" >September</option>
		<option value="10" >October</option>
		<option value="11" >November</option>
		<option value="12" >December</option>
                </select>

                </div>
	
             </div>
             </div>
	-->
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

  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script> 

 <script>
    /*$(document).ready(function(){
    $('#form-user').on('submit', function(e){
        
      //alert('ytyhghh');
      var submission_date=$("#input-submission_date").val();
      var filter_unit=$("#filter_unit").val();
      var store=$("#input-store").val();
      var period_date_start=$("#input-period_date_start").val();
      var period_date_end=$("#input-period_date_end").val();
      var amount=$("#input-amount").val();
      if((submission_date!="") && (filter_unit!="") && (store!="") && (period_date_start!="") && (period_date_end!="") && (amount!=""))
      {
       
      }

      $("#submit_button").hide();  
       $("#processing_image").show();
    });
});
*/
 </script>
<?php echo $footer; ?> 