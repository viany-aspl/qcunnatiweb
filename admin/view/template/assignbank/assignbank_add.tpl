<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-store" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1>Add Bank</h1>
      
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i>Add Bank</h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-store" class="form-horizontal">

	<div class="form-group required">
                <label class="col-sm-2 control-label" for="input-meta-title">Company</label>
                <div class="col-sm-10">
                    <select name="filter_company[]" multiple="multiple" id="input-comapny" class="form-control" required="required">
                   <option selected="selected" value="">SELECT COMPANY</option>
                  <?php foreach ($companies as $company) {   ?>
                  <?php if ($company['company_id'] == $filter_company) {
                      if($filter_company!=""){
                      ?>
                  <option value="<?php echo $company['company_id']; ?>" selected="selected"><?php echo $company['company_name']; ?></option>
                      <?php }} else { ?>
                  <option value="<?php echo $company['company_id']; ?>"><?php echo $company['company_name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                  <?php if ($error_meta_title) { ?>
                  <div class="text-danger"><?php echo $error_meta_title; ?></div>
                  <?php } ?>
                </div>
            </div>


            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-meta-title">Bank Name</label>
                <div class="col-sm-10">
                    <input type="text" name="bank_name" value="" placeholder="Bank Name" id="bank_name" class="form-control"  required="required" />
                  <?php if ($error_meta_title) { ?>
                  <div class="text-danger"><?php echo $error_meta_title; ?></div>
                  <?php } ?>
                </div>
            </div>           

            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-meta-title">Bank Account Name</label>
                <div class="col-sm-10">
                    <input type="text" name="bank_account_name" value="" placeholder="Bank Account Name" id="bank_account_name" class="form-control"  required="required" />
                  <?php if ($error_meta_title) { ?>
                  <div class="text-danger"><?php echo $error_meta_title; ?></div>
                  <?php } ?>
                </div>
            </div> 
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-meta-title">Bank Account Number</label>
                <div class="col-sm-10">
                    <input type="text" name="bank_account_number" value="" placeholder="Bank Account Number" id="bank_account_number" class="form-control"  required="required" />
                  <?php if ($error_meta_title) { ?>
                  <div class="text-danger"><?php echo $error_meta_title; ?></div>
                  <?php } ?>
                </div>
            </div> 
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-meta-title">Account Type</label>
                <div class="col-sm-10">
                    <input type="text" name="bank_account_type" value="" placeholder="Account Type" id="bank_account_type" class="form-control"  required="required" />
                  <?php if ($error_meta_title) { ?>
                  <div class="text-danger"><?php echo $error_meta_title; ?></div>
                  <?php } ?>
                </div>
            </div> 
           <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-meta-title">IFSC Code</label>
                <div class="col-sm-10">
                    <input type="text" name="bank_ifsc_code" value="" placeholder="IFSC Code" id="bank_ifsc_code" class="form-control"  required="required" />
                  <?php if ($error_meta_title) { ?>
                  <div class="text-danger"><?php echo $error_meta_title; ?></div>
                  <?php } ?>
                </div>
            </div>
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-meta-title">Branch</label>
                <div class="col-sm-10">
                    <input type="text" name="bank_branch" value="" placeholder="Branch" id="bank_branch" class="form-control"  required="required" />
                  <?php if ($error_meta_title) { ?>
                  <div class="text-danger"><?php echo $error_meta_title; ?></div>
                  <?php } ?>
                </div>
            </div>
        </form>
      </div>
    </div>
  </div>
  </div>
<?php echo $footer; ?>