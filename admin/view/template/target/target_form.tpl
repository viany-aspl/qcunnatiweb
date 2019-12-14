<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-user" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1>Set target</h1>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> Set target</h3>
      </div>
      <div class="panel-body">

        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-user" class="form-horizontal">
            <input type="hidden" name="logged_user" value="<?php echo $logged_user; ?>" />
        
          <div class="form-group required">
                <label class="col-sm-2 control-label" style="float: left;" for="input-username">Select Store</label>
            <div class="col-sm-10">
                <div class="input-group col-sm-12">
              
                <select required name="filter_store" id="input-store" class="form-control">
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
                <label class="col-sm-2 control-label" style="float: left;" for="input-filter_month">Select month</label>
            <div class="col-sm-10">
                <div class="input-group col-sm-12">
              
                <select required name="filter_month"  class="form-control" >
                  <option selected="selected" value="">SELECT MONTH</option>  
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
          </div>
	 <div class="form-group required">
                <label class="col-sm-2 control-label" style="float: left;" for="input-filter_month">Select month</label>
            <div class="col-sm-10">
                <div class="input-group col-sm-12">
              
                <select required name="filter_year"  class="form-control" >
                  <option selected="selected" value="">SELECT YEAR</option>  
                  <option value="2017">2017</option>
                  <option value="2018">2018</option>
                  <option value="2019">2019</option>
                  <option value="2020">2020</option>
                  <option value="2021">2021</option>
                  
                  
                </select>

                </div>
             </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-transaction_number">Fertilizer</label>
            <div class="col-sm-10">
              <input type="text" required name="Fertilizer"  placeholder="Fertilizer" id="input-Fertilizer" class="form-control" />
              
            </div>
          </div>
            <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-transaction_number">Crop Protection</label>
            <div class="col-sm-10">
              <input type="text" required name="Crop_Protection"  placeholder="Crop Protection" id="input-Crop_Protection" class="form-control" />
              
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-branch_code">Crop Care</label>
            <div class="col-sm-10">
              <input type="text" required name="Crop_Care"  placeholder="Crop Care" id="input-Crop_Care" class="form-control" />
              
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-branch_location">Seeds</label>
            <div class="col-sm-10">
              <input type="text" required name="Seeds" placeholder="Seeds" id="input-Seeds" class="form-control" />
              
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-remarks ">Remarks </label>
            <div class="col-sm-10">
              <textarea name="remarks" rows="5" placeholder="Remarks" id="input-remarks " class="form-control"><?php echo $remarks; ?></textarea>
            </div>
          </div>
            
            <input type="hidden" name="verified_by" id="verified_by" value="<?php ?>" />
           
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

<?php echo $footer; ?> 