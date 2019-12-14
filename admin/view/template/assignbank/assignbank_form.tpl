<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-store" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1>Update Bank</h1>
      
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i>Update Bank</h3>
      </div>
      <div class="panel-body">
        <form action="" method="post" enctype="multipart/form-data" id="form-store" class="form-horizontal">

	<div class="form-group required">
                <label class="col-sm-2 control-label" for="input-meta-title">Company</label>
                <div class="col-sm-10">

	   <select class="select2" id="funit" required="required" name="filter_company[]" multiple="multiple"  class="form-control" style="width: 100%">
                     
                    <?php //print_r($config_unit);
                    foreach ($companies as $company) 
                    {
                      
                          
                      
                    ?>
                        <option value="<?php echo $company['company_id']; ?>"
                               <?php 
                               foreach($filter_companies as $filter_company)
                                {
                                    if (in_array($company['company_id'], $filter_company)) 
                                    { 
                                     ?>
                                        selected="selected"
                                <?php 
                                    }
                                }
                                ?>
                                ><?php echo $company['company_name']; ?></option>
                    <?php
                    }
                    ?>
                        </select>
 
                    
                  <?php if ($error_meta_title) { ?>
                  <div class="text-danger"><?php echo $error_meta_title; ?></div>
                  <?php } ?>
                </div>
            </div>


            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-meta-title">Bank Name</label>
                <div class="col-sm-10"><?php //print_r($Bank_values); ?>
                    <input type="text" name="bank_name" value="<?php echo $Bank_values[0]['bank_name']; ?>"  id="bank_name" class="form-control"  />
                    <input type="hidden" name="id" value="<?php echo $Bank_values[0]['bank_id']; ?>"  id="id" class="form-control"/>
                  <?php if ($error_meta_title) { ?>
                  <div class="text-danger"><?php echo $error_meta_title; ?></div>
                  <?php } ?>
                </div>
            </div>

	<div class="form-group required">
                <label class="col-sm-2 control-label" for="input-meta-title">Bank Account Name</label>
                <div class="col-sm-10">
                    <input type="text" name="bank_account_name"  placeholder="Bank Account Name" id="bank_account_name" class="form-control" value="<?php echo $Bank_values[0]['bank_account_name']; ?>"  required="required" />
                  <?php if ($error_meta_title) { ?>
                  <div class="text-danger"><?php echo $error_meta_title; ?></div>
                  <?php } ?>
                </div>
            </div> 
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-meta-title">Bank Account Number</label>
                <div class="col-sm-10">
                    <input type="text" name="bank_account_number" placeholder="Bank Account Number" id="bank_account_number" class="form-control" value="<?php echo $Bank_values[0]['bank_account_number']; ?>"  required="required" />
                  <?php if ($error_meta_title) { ?>
                  <div class="text-danger"><?php echo $error_meta_title; ?></div>
                  <?php } ?>
                </div>
            </div> 
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-meta-title">Account Type</label>
                <div class="col-sm-10">
                    <input type="text" name="bank_account_type"  placeholder="Account Type" id="bank_account_type" class="form-control" value="<?php echo $Bank_values[0]['bank_account_type']; ?>" required="required" />
                  <?php if ($error_meta_title) { ?>
                  <div class="text-danger"><?php echo $error_meta_title; ?></div>
                  <?php } ?>
                </div>
            </div> 
           <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-meta-title">IFSC Code</label>
                <div class="col-sm-10">
                    <input type="text" name="bank_ifsc_code"  placeholder="IFSC Code" id="bank_ifsc_code" class="form-control" value="<?php echo $Bank_values[0]['bank_ifsc_code']; ?>"  required="required" />
                  <?php if ($error_meta_title) { ?>
                  <div class="text-danger"><?php echo $error_meta_title; ?></div>
                  <?php } ?>
                </div>
            </div>
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-meta-title">Branch</label>
                <div class="col-sm-10">
                    <input type="text" name="bank_branch"  placeholder="Branch" id="bank_branch" class="form-control" value="<?php echo $Bank_values[0]['bank_branch']; ?>" required="required" />
                  <?php if ($error_meta_title) { ?>
                  <div class="text-danger"><?php echo $error_meta_title; ?></div>
                  <?php } ?>
                </div>
            </div>
	<div class="form-group required">
                <label class="col-sm-2 control-label" for="input-meta-title">Active</label>
                <div class="col-sm-10">
                   <select name="IsActive"  id="input-IsActive" class="form-control" required="required">
                   <option value="1" <?php if($Bank_values[0]['IsActive']=="1"){ ?> selected="selected" <?php } ?>>ACTIVE</option>
                  <option value="0" <?php if($Bank_values[0]['IsActive']=="0"){  ?> selected="selected" <?php } ?>>DE-ACTIVE</option>
                </select>
                </div>
            </div>

        </form>
      </div>
    </div>
  </div>
  </div>
<script type="text/javascript">
$("#funit").select2();  
</script>
<?php echo $footer; ?>