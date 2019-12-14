<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">      
        <a href="<?php echo $redirect; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a></div>
      <h1>Factory Units</h1>
      
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
        <h3 class="panel-title"><i class="fa fa-list"></i>Unit List</h3>
      </div>
      <div class="panel-body">

	 <div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-end">Company</label>
                <div class="input-group date">
                  <?php //echo $filter_store; //print_r($stores);//exit; ?>
                  <span class="input-group-btn">
                      
                  <select name="filter_company" id="input-company" class="form-control">
                   <option selected="selected" value="">SELECT COMPANY</option>
                  <?php foreach ($companies as $company) { //echo $store['store_id'];  ?>
                  <?php if ($company['company_id'] == $filter_company) {
                      if($filter_company!=""){
                      ?>
                  <option value="<?php echo $company['company_id']; ?>" selected="selected"><?php echo $company['company_name']; ?></option>
                      <?php }} else { ?>
                  <option value="<?php echo $company['company_id']; ?>"><?php echo $company['company_name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                  </span></div>
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
                  <td>SID</td>
	    <td>Unit ID</td>
                  <td class="text-left">Unit Name</td>
                  <td class="text-left">Action</td>
                </tr>
              </thead>
              <tbody>
                <?php if ($unit) { $a=1; ?>
                <?php foreach ($unit as $un) { ?>
                <tr>
                  <td class="text-left"><?php echo $a; ?></td>
	    <td class="text-left"><?php echo $un['unit_id']; ?></td>
                  <td class="text-left"><?php echo $un['unit_name']; ?></td>
             	    <td class="text-left">
		<a href="<?php echo $un['edit']; ?>" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="Edit"><i class="fa fa-pencil"></i></a>

	   </td>
                
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

<script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=unit/unit&token=<?php echo $token; ?>';
	
        var filter_company = $('select[name=\'filter_company\']').val();
	
	if (filter_company!="") {
		url += '&filter_company=' + encodeURIComponent(filter_company);
	}

		
       
	location = url;
});
//--></script> 
<?php echo $footer; ?> 