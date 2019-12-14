<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo "Sub User Sale Summary"; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo "Sub User Sale Summary"; ?>"><?php "Sub User Sale Summary"; ?></a></li>
        <?php } ?>
      </ul>


    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i> <?php echo "Sub User Sale Summary List"; ?></h3>
       <button type="button" id="button-download" class="btn btn-primary pull-right" style="margin-top: -8px !important;">
            Download</button>
      </div>
	<ul class="nav nav-tabs">
		  
            <li class="active"><a href="#tab-general" data-toggle="tab">Sale Sumamry</a></li>
            <li><a href="#tab-data" data-toggle="tab">Sub User Cash Detail</a></li>
                
    </ul>
    <div class="tab-content">
    <div class="tab-pane active" id="tab-general">
      <div class="panel-body">
        <div class="well">
          <div class="row">
            
            <div class="col-sm-6">
               
              <div class="form-group">
                <label class="control-label" >Select User</label>
              
                      
                  <select name="filter_user" id="filter_user" style="width:100%" class="select2 form-control">
                   <option selected="selected" value="">SELECT USER</option>
					<?php foreach($getuser as $user){ ?>
						<option value="<?php echo $user['user_id']; ?>" <?php if($filter_user==$user['user_id']){ ?> selected="selected" <?php } ?> ><?php echo $user['firstname']."  ".$user['lastname']; ?></option>
					<?php } ?>
                                  
                </select>
                
              </div>
              
            </div>
			<div class="col-sm-6">
              
             <div class="form-group">
                <label class="control-label" for="input-date-start">Start Date</label>
                <div class="input-group date">
                  <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="Start Date" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
			  <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <div class="table-responsive">
		<!----<label class="pull-right">Cash In Hand :<label><?php echo $user_cash[0];?>--->
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left">S NO.</td>
                <td class="text-left">User Name</td> 
				<td class="text-left">Store Name</td>				
                <td class="text-left">Cash Sales</td>
				  <td class="text-left">Tagged Sales</td>
                
				<td class="text-left">Subsidy Sales</td>
				
				<td class="text-left">Cash In Hand</td>
               
	
              </tr>
            </thead>
            <tbody>
              <?php if ($orders) { $total=0; $a=1;?>
			  
              <?php foreach ($orders as $order) {  //print_r($order); ?>
              <tr>
                <td class="text-left"><?php echo $a; ?></td>
                <td class="text-left"><?php echo $order['subusername']; ?></td>
				<td class="text-left"><?php echo $order['store_name']; ?></td>
              
                <td class="text-left"><?php echo round($order['Cash_Sales'],2); ?></td>
                <td class="text-left"><?php echo round($order['Tagged_Sales'],2); ?></td>
				
				<td class="text-left"><?php echo round($order['Cash_Subsidy'],2); ?></td>
				
				<td class="text-left"><?php echo round($order['cash_inhand'],2); ?></td>
                
              </tr>
              <?php $total=$total+$order['amount'];
              
              $a++;} ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="7"><?php echo $text_no_results; ?></td>
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
	<!----------------Detail Tab------------->
	<div class="tab-pane" id="tab-data">
  <div class="panel-body">
        <div class="well">
          <div class="row">
            
            <div class="col-sm-6">
               
              <div class="form-group">
                <label class="control-label" >Select User</label>
                
                  <select name="filter_subuser" style="width:100%" id="filter_subuser" class="select2 form-control">
                   <option selected="selected" value="">SELECT USER</option>
					<?php foreach($getuser as $user){ ?>
						<option value="<?php echo $user['user_id']; ?>" <?php if($filter_subuser==$user['user_id']){ ?> selected="selected" <?php } ?> ><?php echo $user['firstname']."  ".$user['lastname']; ?></option>
					<?php } ?>
                                  
                </select>
                
              </div>
              
            </div>
			<div class="col-sm-6">
              
             <div class="form-group">
                <label class="control-label" for="input-date-start">Start Date</label>
                <div class="input-group date">
                  <input type="text" name="filter_date_start2" value="<?php echo $filter_date_start2; ?>" placeholder="Start Date" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
			  <button type="button" id="button-subfilter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <div class="table-responsive">
       <?php if($subcash!='') {?>
		<span style="font-weight: bold;" class="pull-right">Cash Deposited :&nbsp;&nbsp;&nbsp; <?php echo $subcash; ?></span>
	   <?php } ?>
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left">S NO.</td>
                <td class="text-left">Date</td> 
				  <td class="text-left">Cash</td>	
              </tr>
            </thead>
            <tbody>
              <?php if ($cashorders) { $total=0; $a=1;?>
			  
              <?php foreach ($cashorders as $cashorder) { // print_r($cashorder); ?>
              <tr>
                <td class="text-left"><?php echo $a; ?></td>
                <td class="text-left"><?php echo $cashorder['dat']; ?></td>
				<td class="text-left"><?php echo $cashorder['cash']; ?></td>
              
          
                
              </tr>
              <?php $total=$total+$order['amount'];
              
              $a++;} ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="7"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination1; ?></div>
          <div class="col-sm-6 text-right"> 
             
              <?php echo $results1; ?>  </div>
        </div>
      </div>
  </div>
  <!-------------------------------->
	
  </div>
  </div>
  </div>
  <script type="text/javascript">
   $("#filter_user").select2();
    $("#filter_subuser").select2();
 
$('#button-filter').on('click', function() {
	url = 'index.php?route=report/subuser/SubUserSaleCash&token=<?php echo $token; ?>';
	
	
	var filter_user = $('#filter_user').val();
	if(filter_user=="")
	{
	//alertify.error("Please Select User");
	//return false;
	}
	if (filter_user) {
		url += '&filter_user=' + encodeURIComponent(filter_user);
	}	
    var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}    
	location = url;
});
</script> 
 <script type="text/javascript"><!--
$('#button-subfilter').on('click', function() {
	url = 'index.php?route=report/subuser/SubUserSaleCash&token=<?php echo $token; ?>';
	
	
	var filter_subuser = $('#filter_subuser').val();
	if(filter_subuser=="")
	{
	//alertify.error("Please Select User");
	//return false;
	}
	if (filter_subuser) {
		url += '&filter_subuser=' + encodeURIComponent(filter_subuser);
	}	
    var filter_date_start2 = $('input[name=\'filter_date_start2\']').val();
	
	if (filter_date_start2) {
		url += '&filter_date_start2=' + encodeURIComponent(filter_date_start2);
	}    
	location = url;
});
//--></script> 
<script type="text/javascript"><!--
$('#button-download').on('click', function() {
    url = 'index.php?route=report/subuser/download_excel_SubUserSaleCash&token=<?php echo $token; ?>';
  
   var filter_user = $('#filter_user').val();
	
	if (filter_user) {
		url += '&filter_user=' + encodeURIComponent(filter_user);
	}
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
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