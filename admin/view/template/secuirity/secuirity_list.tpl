<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">      
        <a href="<?php echo $redirect; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a></div>
      <h1>Security Deposit</h1>
      
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
        <h3 class="panel-title"><i class="fa fa-list"></i>Security Deposit List</h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-end">Store</label>
                
                      
                 <select name="filter_store"  id="input-store" style="width: 100%;" class="select2 form-control" required="required">
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
            <div class="col-sm-6">
                
              <br/>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> Search</button>
            </div>
          </div>
        </div>
	
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-store">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td class="text-left">SID</td>
	          <td class="text-left">Store Name</td>
                  <td class="text-left">Bank Name</td>
                  <td class="text-left">IFSC Code</td>
                  <td class="text-left">Cheque no</td>
                  <td class="text-left">Amount</td>
                  <td class="text-left">Cheque Issue Date</td>
            	 <td class="text-left">Remarks</td>
                </tr>
              </thead>
              <tbody>
                <?php if ($secuirity) { $a=1; ?>
                <?php foreach ($secuirity as $seq) { ?>
                <tr>
                  <td class="text-left"><?php echo $a; ?></td>
	          <td class="text-left"><?php echo $seq['store_name']; ?></td>
                  <td class="text-left"><?php echo $seq['bank_name']; ?></td>
                  <td class="text-left"><?php echo $seq['ifsc_code']; ?></td>
                  <td class="text-left"><?php echo $seq['checkno']; ?></td>
                  <td class="text-left"><?php echo $seq['amount']; ?></td>
                  <td class="text-left"><?php echo $seq['chequeissuedate']; ?></td>
                <td class="text-left" style="max-width: 200px;"><?php echo $seq['remarks']; ?></td>
                
                </tr>
                <?php $a++;} ?>
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
$("#input-store").select2();
<!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=secuirity/secuirity&token=<?php echo $token; ?>';
	
        var filter_store = $('select[name=\'filter_store\']').val();
	
	if (filter_store!="") {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}

		
       
	location = url;
});
//--></script> 
<?php echo $footer; ?> 