<?php echo $header; ?><?php echo $column_left; ?>
 <div id="content">
   <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">      
        <a href="<?php echo $redirect; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a></div>
      <h1>Assign Bank</h1>
      
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
        <h3 class="panel-title"><i class="fa fa-list"></i>Assign Bank List</h3>
      </div>
      <div class="panel-body">

	 <div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-end">Bank</label>
                <div class="input-group">
                  
                  <input type="text" class="form-control" required name="filter_bank" id="filter_bank" value="<?php echo $filter_bank; ?>" style="text-transform: uppercase;" placeholder="Search Bank" />
                 </div>
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
                  <td>Bank ID</td>	          
                  <td class="text-left">Bank Name</td>
                  <td class="text-left">Action</td>
            
                </tr>
              </thead>
              <tbody>
                  <?php //echo $bank; ?>
                <?php if ($bank) {  ?>
                <?php foreach ($bank as $un) { ?>
                <tr>
                  <td class="text-left"><?php echo $un['bank_id']; ?></td>	         
                  <td class="text-left"><?php echo $un['bank']; ?></td>
                  <td class="text-right">
                  <a href="<?php echo $un['edit']; ?>" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="Edit"><i class="fa fa-pencil"></i></a></td>
             
                
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

<script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=assignbank/assignbank&token=<?php echo $token; ?>';
	
        var filter_bank = $('#filter_bank').val();
	
	if (filter_bank!="") {
		url += '&filter_bank=' + encodeURIComponent(filter_bank);
	}

		
       
	location = url;
});
//--></script> 
<?php echo $footer; ?> 