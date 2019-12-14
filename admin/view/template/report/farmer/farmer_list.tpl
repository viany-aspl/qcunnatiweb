<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
	<i class="<?php echo $tool_tip_class; ?> " data-toggle="tooltip" style="<?php echo $tool_tip_style; ?>" title="<?php echo $tool_tip; ?>"></i>
	
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading"> 
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i> <?php echo $text_list; ?></h3>
        <button type="button" id="button-download" class="btn btn-primary pull-right" style="margin-top: -8px;"> Download</button>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-start">Start Date</label>
                <div class="input-group date">
                  <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="Start Date" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-date-end">End Date</label>
                <div class="input-group date">
                  <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="End Date" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
             
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-end">Scheme</label>
                <div class="input-group date">
                  <?php //print_r($schemes);//exit; ?>
                  <span class="input-group-btn">
                      
                  <select name="filter_scheme" id="input-store" class="form-control">
	    <option value="">Select Scheme</option>
                  <?php foreach ($schemes as $scheme) { ?>
                  <?php if ($scheme['name'] == $filter_scheme) { ?>
                  <option value="<?php echo $scheme['name']; ?>" selected="selected"><?php echo $scheme['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $scheme['name']; ?>"><?php echo $scheme['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                  </span></div>
              </div>
             
            </div>
              
              <div class="col-sm-6">
                   <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
                  
              </div>
          </div>
        </div>
        <div class="table-responsive">
        
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left">Farmer Name</td>
                <td class="text-left">Mobile Number</td>
                <td class="text-left">Scheme</td>
                <td class="text-left">Registration date</td>
               
                
              </tr>
            </thead>
            <tbody>
              <?php if ($orders) {  ?>
              <?php foreach ($orders as $order) { ?>
              <tr>
               
                <td class="text-left"><?php echo $order['firstname']; ?></td>
                <td class="text-left"><?php echo $order['telephone']; ?></td>
                
                <td class="text-left"><?php echo $order['scheme']; ?></td>
                <td class="text-left"><?php echo $order['date_added']; ?></td>
                
              </tr>
              <?php 
              $aa++;
              } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="5"><?php echo $text_no_results; ?></td>
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
  <script type="text/javascript">
$('#button-filter').on('click', function() {
	url = 'index.php?route=report/farmer&token=<?php echo $token; ?>';
	
        var filter_date_start = $('#input-date-start').val();
	
	if (filter_date_start!="") {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}
        var filter_date_end = $('#input-date-end').val();
	
	if (filter_date_end!="") {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
        var filter_scheme = $('select[name=\'filter_scheme\']').val();
	
	if (filter_scheme!="") {
		url += '&filter_scheme=' + encodeURIComponent(filter_scheme);
	}
       	//alert(url);
	location = url;
});
</script> 
<script type="text/javascript">
$('#button-download').on('click', function() {
    
    url = 'index.php?route=report/farmer/download_excel&token=<?php echo $token; ?>';
	
       var filter_date_start = $('#input-date-start').val();
	
	if (filter_date_start!="") {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}
        var filter_date_end = $('#input-date-end').val();
	
	if (filter_date_end!="") {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
        var filter_scheme = $('select[name=\'filter_scheme\']').val();
	
	if (filter_scheme!="") {
		url += '&filter_scheme=' + encodeURIComponent(filter_scheme); 
	}

   
    //location = url;
        window.open(url, '_blank');
});


</script> 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?>