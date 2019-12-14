<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
	  
      </div>
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
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" form="form-backup" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-exchange"></i> <?php echo $heading_title; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
		  <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-start">Date</label>
                <div class="input-group date" id="date_from">
                  <input type="text" name="filter_date_start"  value="<?php echo $filter_date_start; ?>" placeholder="Date" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              
                
            </div>
            <div class="col-sm-6">
              <div class="form-group">
					<label class="control-label" for="input-date-end">File Name</label>
					<input type="text" name="filter_file" value="<?php echo $filter_file; ?>" placeholder="File Name" id="filter_file" class="form-control" />
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i>Search</button>
            </div>
            
          </div>
        </div>
        <div class="table-responsive">
         
          <table class="table table-bordered">
            <thead>
				<tr>
					<td class="text-left">SID </td>
					<td class="text-left">File Name</td>
					<td class="text-left">Date</td>
					<td class="text-right">Download</td>
				</tr>
            </thead>
            <tbody>
              <?php if ($orders) 
				{  
				if($_GET["page"]=="") 
				{
					$aa=1;
				} 
				else if($_GET["page"]=="1") 
				{
					$aa=1;
				}
				else
				{ 
					$aa=(($_GET["page"]-1)*20)+1; 
				}
				?>
              <?php foreach ($orders as $order) 
				{ ?>
				<tr>
					<td class="text-left"><?php echo $aa; ?></td>
					<td class="text-left"><?php echo $order; ?></td>
					<td class="text-left"><?php echo $filter_date_start; ?></td>
					<td class="text-right"><a download href="<?php echo $file_url.$order; ?>">Download</a></td>
              </tr>
              <?php 
              $aa++;
              } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="4">No Result found</td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"> 
             
              <?php echo $results; ?>  </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">

$('.date').datetimepicker({
	pickTime: false
});

</script>
 <script type="text/javascript">
$('#button-filter').on('click', function() {
	url = 'index.php?route=tool/logfile&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) 
	{ 
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}
	var filter_file = $('input[name=\'filter_file\']').val();
	
	if (filter_file) 
	{
		url += '&filter_file=' + encodeURIComponent(filter_file);
	}	
	location = url;
});
//--></script> 
<?php echo $footer; ?>