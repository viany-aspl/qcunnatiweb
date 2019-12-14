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
        <button type="button" id="button-download" class="btn btn-primary pull-right" style="margin-top: -8px !important;">
            Download</button>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-6">
            
              	<div class="form-group">
                <label class="control-label" for="input-date-end">Runner </label>
                 <select name="filter_user" id="filter_user" class="form-control select2" style="width: 100%;">
                   <option selected="selected" value="">SELECT CE</option>
	
	<?php foreach($runner_list as $runner){ ?>
		<option value="<?php echo $runner['user_id']; ?>" <?php if($filter_user==$runner['user_id']){ ?> selected="selected" <?php } ?> ><?php echo $runner['firstname']." ".$runner['lastname']; ?></option>
	<?php } ?>
	
                </select>
              </div>
            </div>
            <div class="col-sm-6">
              <br/>
              
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>

	<span style="font-weight: bold;font-size: 14px;"> Total : <?php echo $total_amount; ?> </span> 
        </div>
        <div class="table-responsive">
		

          <table class="table table-bordered">
            <thead>
              <tr>
                
                <td class="text-left">User</td>
                <td class="text-right">Current Balance</td>
                <td class="text-right">Mobile Number</td>
                <td class="text-right">Unit</td>
				  <td class="text-right">Not Accepted Entries</td>
              </tr>
            </thead>
            <tbody>
              <?php if ($orders) { $total=0; ?>
              <?php foreach ($orders as $order) { ?>
              <tr>
                
                <td class="text-left"><?php echo $order['user_name']; ?></td>
                <td class="text-right"><?php echo $order['amount']; ?></td>
                <td class="text-right"><?php echo $order['mobile_number']; ?></td>
					<td class="text-right"><?php echo $order['unit_name']; ?></td>
					<td class="text-right"><a data-toggle="modal" data-target="#myModal" 
					onclick="return get_not_accepted_entries('<?php echo $order['user_id']; ?>','<?php echo $order['user_name']; ?>');" href="#" title="Click here to view Not Accepted Entries" >View <a></td>
              </tr>
              <?php $total=$total+$order['amount'];
              
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
  
  <div id="myModal" class="modal fade" role="dialog">
<div class="modal-dialog" >
<div class="modal-content">

	<div class="modal-header" style="height:60px;">
	<button type="button" class="close pull-right" data-dismiss="modal">&times;</button>
	<label id="runnername"></label>'s - <label>Not Accepted entries by account</label>
	</div>
	<div class="modal-body" id="printarea">
	<div class="table-responsive">
	<img id="cr_img" src="view/image/processing_image.gif" style="float: right; margin-right:40%; height: 60px;display : none;"/>
			<table class="table table-bordered" id="prd_table">
			<thead>
				<tr>
					<td class="text-left">Trans ID</td>
				    <td class="text-left">Deposit Amount</td>
				    <td class="text-left">Bank</td>
				    <td class="text-left">Deposit Date</td>
					
				
					
               </tr>
           </thead>
			<tbody id="productdtl_body"> 
				 	 
			</tbody>
			</table>			
	</div>
	</div>
  </div>
	</div>
	</div>
  <script type="text/javascript">
function get_not_accepted_entries(user_id,name)
 {
	$("#runnername").html(name);
	$('#cr_img').show(); 
	var url= 'index.php?route=report/cash_report/get_not_accepted_entries&token=<?php echo $token; ?>&user_id=' +  encodeURIComponent(user_id);
	$.ajax({
		url:url,
				
		success: function(json) 
		{
            //alert(JSON.stringify(json));
			$('#cr_img').hide(); 	
            
			$("#productdtl_body").html(json);
		
	    },
        error:function (json)
		{
			$('#cr_img').hide(); 	
			alertify.error("Opps some error occurred !");
        }
               
	});
	 
 }  
$("#filter_user").select2();

$('#button-filter').on('click', function() {
	url = 'index.php?route=reportbcml/cash_report/runner_cash_position&token=<?php echo $token; ?>';
	
	var filter_user = $('#filter_user').val();
	
	if (filter_user) {
		url += '&filter_user=' + encodeURIComponent(filter_user);
	}	

	location = url;
});
</script> 
<script type="text/javascript">
$('#button-download').on('click', function() {
    url = 'index.php?route=reportbcml/cash_report/download_runner_current_postion_excel&token=<?php echo $token; ?>';
    
    var filter_user = $('#filter_user').val();
	
	if (filter_user) {
		url += '&filter_user=' + encodeURIComponent(filter_user);
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