<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo "Sub User Material Issued Detail"; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo "Sub User Material Detail"; ?>"><?php "Material Detail"; ?></a></li>
        <?php } ?>
      </ul>


    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i> <?php echo "Material Detail List"; ?></h3>
       <button type="button" id="button-download" class="btn btn-primary pull-right" style="margin-top: -8px !important;">
            Download</button>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-6">
               
              <div class="form-group">
                <label class="control-label" >Select Store</label>
              
                      
                  <select name="filter_store" id="filter_store" style="width:100%" class="select2 form-control">
                   <option selected="selected" value="">SELECT STORE</option>
					<?php foreach($stores as $store){ ?>
						<option value="<?php echo $store['store_id']; ?>" <?php if($filter_store==$store['store_id']){ ?> selected="selected" <?php } ?> ><?php echo $store['name']; ?></option>
					<?php } ?>
                                  
                </select>
             
              </div>
             <div class="form-group">
                <label class="control-label" for="input-date-start">Start Date</label>
                <div class="input-group date">
                  <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="Start Date" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
            </div>
            <div class="col-sm-6">
               
              <div class="form-group">
                <label class="control-label" >Select User</label>
           
                      
                  <select name="filter_user" id="filter_user" style="width:100%"  class="select2 form-control">
                   <option selected="selected" value="">SELECT USER</option>
					<?php foreach($getuser as $user){ ?>
						<option value="<?php echo $user['user_id']; ?>" <?php if($filter_user==$user['user_id']){ ?> selected="selected" <?php } ?> ><?php echo $user['firstname']."  ".$user['lastname']; ?></option>
					<?php } ?>
                                  
                </select>
             
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left">S NO.</td>
				<td class="text-left">Store Name</td> 
                <td class="text-left">User Name</td>  				  
                <td class="text-left">Product Name</td>				  
                
				<td class="text-left">Material Issued Date</td>
				<td class="text-left">Quantity</td>
               
	<td class="text-left">Trans ID</td>
              </tr>
            </thead>
            <tbody>
              <?php if ($orders) { $total=0; $a=1;?>
			  
              <?php foreach ($orders as $order) {  //print_r($order); ?>
              <tr>
                <td class="text-left"><?php echo $a; ?></td>
				<td class="text-left"><?php echo $order['storename']; ?></td>
                <td class="text-left"><?php echo $order['username']; ?></td>
				  <td class="text-left"><?php echo $order['name']; ?></td>
              
                
				<td class="text-left"><?php echo $order['dat']; ?></td>
				<td class="text-left"><?php echo $order['quantity']; ?></td>
              	<td class="text-left"><?php echo $order['trans_id']; ?></td>  
              </tr>
              <?php $total=$total+$order['amount'];
              
              $a++;} ?>
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
  $("#filter_user").select2();
  $("#filter_store").select2();
  <!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=report/subuser/material_detail&token=<?php echo $token; ?>';
	var filter_user = $('#filter_user').val();
	
	if (filter_user) {
		url += '&filter_user=' + encodeURIComponent(filter_user);
	}	
    var filter_store = $('#filter_store').val();
	
	if (filter_store) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}
    if((filter_user=="") && (filter_store==""))
	{
	//alertify.error("Please Select Store OR User");
	//return false;
	}
        
	location = url;
});

//--></script> 
<script type="text/javascript"><!--
$('#button-download').on('click', function() {
    url = 'index.php?route=report/subuser/download_excel_material_detail&token=<?php echo $token; ?>';
   var filter_user = $('#filter_user').val();
	
	if (filter_user) {
		url += '&filter_user=' + encodeURIComponent(filter_user);
	}	
    var filter_store = $('#filter_store').val();
	
	if (filter_store) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}
    if((filter_user=="") && (filter_store==""))
	{
	//alertify.error("Please Select Store OR User");
	//return false;
	}
      
    //location = url;
        window.open(url, '_blank');
});
//--></script> 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?>