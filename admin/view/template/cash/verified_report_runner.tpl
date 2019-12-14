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
    </div>
  </div>
  <div class="container-fluid">
      <?php if ($success) {  ?>
    <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
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
                <label class="control-label" for="input-date-start"><?php echo $entry_date_start; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              		 <div class="form-group">
                <label class="control-label" >Select CE</label>
               
                 
               
                      
                  <select name="filter_user" id="filter_user" class="form-control select2" style="width: 100%;">
                   <option selected="selected" value="">SELECT CE</option>
	
	<?php foreach($runner_list as $runner){ ?>
		<option value="<?php echo $runner['user_id']; ?>" <?php if($filter_user==$runner['user_id']){ ?> selected="selected" <?php } ?> ><?php echo $runner['firstname']." ".$runner['lastname']; ?></option>
	<?php } ?>
	
                </select>
                
              </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                <label class="control-label" for="input-date-end"><?php echo $entry_date_end; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              

	 <div class="form-group">
                <label class="control-label" >Status</label>
               
                 
               
                      
                  <select name="filter_status" id="filter_status" class="form-control">
                   <option selected="selected" value="">ALL</option>
	
	
		<option value="0" <?php if($filter_status=='0'){ ?> selected="selected" <?php } ?> >Deposit by Runner</option>
		<option value="1" <?php if($filter_status=='1'){ ?> selected="selected" <?php } ?> >Accepted</option>
		<option value="2" <?php if($filter_status=='2'){ ?> selected="selected" <?php } ?> >Rejected</option>
	
                </select>
                
              </div>

              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <span style="font-weight: bold;"> Total Amount : <?php echo $total_amount; ?></span> <br/> <br/>
          <table class="table table-bordered">
            <thead>
              <tr>
	<td class="text-left">SID</td>
	<td class="text-left">Trans ID</td>
	<td class="text-left">Deposit Amount</td>
                <td class="text-left">Bank</td>
                <!--<td class="text-right">Branch</td>-->
                <td class="text-left">Deposit Date</td>
                <td class="text-left">Deposit by</td>
	  <td class="text-left">Attcahed Slip</td>
               	<!--<td class="text-left">Filled by</td>-->
	 <!--
                <td class="text-left">Remarks</td>
	<td class="text-left">Store</td>-->
	<td class="text-left">Transaction Number</td>
	 <td class="text-left">Status</td>
	 <td class="text-left">Action</td>
              </tr>
            </thead>
            <tbody>
              <?php if ($orders) { $total=0; if($_GET["page"]=="1"){ $aa=1; }elseif($_GET["page"]==""){$aa=1;} else { $aa=@$_GET["page"]+20-1; } ?>
              <?php foreach ($orders as $order) { ?>
                
              <tr>
                	<td class="text-left"><?php echo $aa; ?></td>
                <td class="text-left"><?php echo $order['SID']; ?></td>
                <td class="text-left"><?php echo $order['amount']; ?></td>
	<td class="text-left"><?php echo $order['bank']; ?></td>
                <!--<td class="text-right"><?php echo $order['branch']; ?></td>--> 
                <!--<td class="text-right"><?php echo $order['deposit_date']; ?></td>
                <td class="text-right"><?php echo $order['deposited_by']; ?></td>-->
                <td class="text-left"><?php echo $order['submit_date']; ?></td>
                <td class="text-left"><?php echo $order['filled_user']; ?></td>
                 <td class="text-left"><?php if($order['uploded_file']!=""){ ?><a href="<?php echo $download_link.'&filename='.$order['uploded_file']; ?>" >View</a><?php } ?></td>
                <!--
	<td class="text-left"><?php echo $order['remarks']; ?></td>
	<td class="text-left"><?php echo $order['store_name']; ?></td>-->
	<td class="text-left"><?php echo $order['transaction_number']; ?></td>
	<td class="text-left"><?php 
		 if($order['status']=="0") 
		{ 
			echo "<spna style='color: orange;'>Deposit by Runner</span>"; 

		} 
		else if($order['status']=="1") 
		{ 
			echo "<spna style='color: green;'>Accepted</span>"; 

		} 
		else if($order['status']=="2") 
		{ 
			echo "<spna style='color: red;'>Rejected</span>"; 
		} 

		?></td>


               <td class="text-right"><?php if($order['status']=="0"){ ?><a class="btn btn-primary" onclick="return open_model('<?php echo $order['tr_id']; ?>','<?php echo $logged_user; ?>','<?php echo $order['amount']; ?>','<?php echo $order['runner_id']; ?>');" href="#">Verify</a> 
                       <br/>  <br/>
                        <a class="btn btn-primary" style="width: 61px;background-color: #B36330;" onclick="return confirm('Are you sure ? You want to reject the amount !');" href="index.php?route=cash/verify/reject_cash_by_account&token=<?php echo $token; ?>&tr_id=<?php echo $order['tr_id']; ?>&logged_user=<?php echo $logged_user; ?>">Reject</a> <?php } else {  } ?></td>
             
              </tr>
              <?php $total=$total+$order['amount'];
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
              <span style="font-weight: bold;">Page Total Amount : <?php echo $total; ?></span> <br/>
              <?php echo $results; ?>  </div>
        </div>
      </div>
    </div>
  </div>


<!-- Modal -->
  <div class="modal fade" id="myModal_create_bill" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" id="partner_cncl_btn2" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Verify Cash Deposit</h4>
        </div>
        <div class="modal-body">
        <form action="#" method="post" enctype="multipart/form-data"  >
		<input required type="hidden"  id="tr_id"  />
		<input required type="hidden"  id="logged_user"  />
		<input required type="hidden"  id="amount"  />
		<input required type="hidden"  id="runner_id"  />
            <div class="form-group">
                <label class="control-label" for="input-date">Transaction Number</label>
                <div class="input-group">
                  <input required type="text" name="transaction_number"  placeholder="Transaction Number"  id="input-transaction_number" class="form-control" />
                  </div>
              </div>
            
            
            <div class="text-left">
	<img src="view/image/processing_image.gif" style="height: 50px;display: none;" id="pr_image"  />
	<span style="display: none;" id="please_wait">Please Wait..</span>
                <input type="button" id="partner_sbmt_btn" onclick="return submit_accept_by();"  class="btn btn-primary" value="Submit" />
                <button type="button" id="partner_cncl_btn" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </form>
        </div>
        <!--<div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>-->
      </div>
      
    </div>
  </div>
  <script type="text/javascript">

$("#filter_user").select2();
function submit_accept_by()
{
var tr_id=$('#tr_id').val();
var logged_user=$('#logged_user').val();
var amount=$('#amount').val();
var runner_id=$('#runner_id').val();
var bank_tr_number=$("#input-transaction_number").val();
if(bank_tr_number)
{

var url1 = 'index.php?route=cash/verify/check_bank_tr_number&token=<?php echo $token; ?>&tr_id='+tr_id+'&bank_tr_number='+bank_tr_number;
$.ajax({
			url: url1,
			beforeSend: function()
			{
				$("#partner_sbmt_btn").hide();
				$("#partner_cncl_btn").hide();

				$("#please_wait").show();
				$("#pr_image").show();
			},
			success: function(json) {
				if(json)
				{
					//alert(json);
					alertify.error('This Bank Transaction Number is already exist ! ');
					$("#partner_sbmt_btn").show();
					$("#partner_cncl_btn").show();

					$("#please_wait").hide();
					$("#pr_image").hide();
					return false;
				}
				else
				{
					//alert('ok');
					//return false;	
					url = 'index.php?route=cash/verify/accept_cash_by_account&token=<?php echo $token; ?>&tr_id='+tr_id+'&logged_user='+logged_user+'&amount='+amount+'&runner_id='+runner_id+'&bank_tr_number='+bank_tr_number;

					$("#partner_sbmt_btn").hide();
					$("#partner_cncl_btn").hide();

					$("#please_wait").show();
					$("#pr_image").show();

					location = url; 
				}
				
			}
		});
		



}
else
{
alertify.error('Please Enter Bank Transaction Number');
}
return false;

}
function open_model(tr_id,logged_user,amount,runner_id)
{

$('#myModal_create_bill').modal('show');
$('#tr_id').val(tr_id);
$('#logged_user').val(logged_user);
$('#amount').val(amount);
$('#runner_id').val(runner_id);
$("#input-transaction_number").val('');
$("#please_wait").hide();
$("#pr_image").hide();
return false; 

}
 
<!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=cash/verify/verify_runner_deposit&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
		
	var filter_store = $('#filter_store').val();
	
	if (filter_store) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}
              var filter_user = $('#filter_user').val();
	
	if (filter_user) {
		url += '&filter_user=' + encodeURIComponent(filter_user);
	}
	var filter_status = $('#filter_status').val();
	
	if (filter_status) {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}
	
	location = url;
});
//--></script> 
    <script type="text/javascript"><!--
$('#button-download').on('click', function() {
	url = 'index.php?route=cash/verify/verify_runner_deposit_download&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
		
	var filter_store = $('#filter_store').val();
	
	if (filter_store) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}
              var filter_user = $('#filter_user').val();
	
	if (filter_user) {
		url += '&filter_user=' + encodeURIComponent(filter_user);
	}
	var filter_status = $('#filter_status').val();
	
	if (filter_status) {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}
        window.open(url, '_blank');
});
//--></script> 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?>