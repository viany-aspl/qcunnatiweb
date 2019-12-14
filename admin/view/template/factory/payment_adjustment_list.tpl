<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      
      <h1><?php echo "Bill Submission and Adjustment"; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if (!empty($error_warning)) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
	<?php if (!empty($success)) {		?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
	
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo "Payment List"; ?></h3>
<button type="button" id="button-download" class="btn btn-primary pull-right" style="margin-top: -8px !important;">
 Download</button>
      </div>
      <div class="panel-body">
	  
        <div class="well">
         
	<div class="row">   
<div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-status">Company</label>
                <select name="filter_company" id="input-company" class="form-control" onchange="clear_company(this.value)">
                  <option value="0">Select Company</option>
                 
                  <?php foreach ($order_company as $company) { ?>
                  <?php if ($company['company_id'] == $filter_company) { ?>
                  <option value="<?php echo $company['company_id']; ?>" selected="selected"><?php echo $company['company_name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $company['company_id']; ?>"><?php echo $company['company_name']; ?></option>
                  <?php } ?>
                  <?php } ?>                   
                </select>
              </div>            
            </div>
			<div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-status">Unit</label>
                <select name="filter_unit" id="input-unit" onchange="currentunitbalance(this.value);" class="form-control">
                  <option value="0">Select Unit</option>
                     <?php foreach ($units as $unit) { ?>
                  <?php if ($unit['unit_id'] == $filter_unit) { ?>
                  <option value="<?php echo $unit['unit_id']; ?>" selected="selected"><?php echo $unit['unit_name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $unit['unit_id']; ?>"><?php echo $unit['unit_name']; ?></option>
                  <?php } ?>
                  <?php } ?>                
                </select>
              </div>            
            </div>			
            
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-end">Store</label>
                <div class="input-group">
                  <?php //echo $filter_store; //print_r($stores);//exit; ?>
                  <span class="input-group-btn">
                      
                  <select name="filter_store" id="input-store" class="form-control">
<option selected="selected" value="">SELECT STORE</option>
<?php foreach ($storess as $store) { //echo $store['store_id']; ?>
<?php if ($store['store_id'] == $filter_store) {
if($filter_store!=""){
?>
<option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
<?php }} else { ?>
<option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
<?php } ?>
<?php } ?>
</select>
                  </span></div>
              </div>
	        </div>             
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-end">Letter No</label>
                <div class="input-group">
                  <?php //echo $filter_store; //print_r($stores);//exit; ?>
                  <span class="input-group-btn">
                     <input type="text" name="filter_letterno"  value="" placeholder="Letter No" id="input-letter-no" class="form-control" />
                  </span></div>
              </div>
            </div>
	<!------		<div class="col-sm-6">
            <div class="form-group">
                <label class="control-label" for="input-date-start">Date Start</label>
                <div class="input-group date" id="date_from">
                  <input type="text" name="filter_date_start"  value="<?php echo $filter_date_start; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div></div>
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-end">Date End</label>
                <div class="input-group date" id="date_to">
                  <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
            </div>------>
           
            
        </div>
            
		  <div class="row">
                        <div class="col-sm-6">
             
			</div>
				<div class="col-sm-6">
					<button class="btn btn-primary pull-right" id="clear-filter" onclick="reset_form();" type="button"> Clear</button>
					<button class="btn btn-primary pull-right" id="button-filter" style="margin-right:10px;" type="button"><i class="fa fa-search"></i> Filter</button>
				</div>
		  </div>
        </div>
	
          <div class="row" style="font-weight: bold;font-size: 15px;margin-bottom: 23px;color: rgba(48, 33, 33, 0.68);">
             
              <div class="col-sm-6">
                 Current Unit Balance :
                 <span id="current_balance"  ><?php echo $unit_balance; ?></span>
              </div>
          </div>    
	
         <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td class="text-left">Sno</td>
                  <td class="text-left">Company</td>
                  <td class="text-left">Unit</td>
		       <td class="text-left">Store Name</td>
                  
                  <td class="text-left">Letter No</td>
                  <td class="text-left">Date Of Generation</td>
                  <td class="text-left">Total Amount</td>
                  <td class="text-left">Bill Included From</td>
                  <td class="text-left">Status</td>
		  <td class="text-left" style="max-width: 100px;">Action</td>
                </tr>
              </thead>
              <tbody>
                <?php if($order_list){
                    $a=1;
		      foreach($order_list as $order)
		      { //print_r($order);
		?>
			<tr>
                            <td class="text-left"><?php echo $a; ?></td> 
                            <td class="text-left"><?php echo $order['company_name']; ?></td>
                            <td class="text-left"><?php echo $order['unit_name']; ?></td>
                            <td class="text-left"><?php echo $order['store_name']; ?></td> 
                            <td class="text-left"><?php echo "ASPL/BB/".$order['sid']; ?></td>
                            
                            <td class="text-left"><?php echo $order['create_date']; ?></td>
                            <td class="text-left"><?php echo number_format((float)$order['total_amount'], 2, '.', ''); ?></td> 
                            <td class="text-left"><?php echo $order['date_start']; ?></td>
                            <td class="text-left" id='paid_status_td_<?php echo $order['sid']; ?>'><?php
                                                        if($order['status']=='0')
                                                        {
                                                            echo "Unpaid";
                                                        }
                                                        if($order['status']=='1')
                                                        {
                                                            echo "Paid";
                                                        }
                                                  
                                                   ?>
                            </td>
                            <td class="text-left" id='paid_action_td_<?php echo $order['sid']; ?>'>

		<img id="cr_img<?php echo $order['sid']; ?>" src="http://www.danubis-dcm.org/Content/Images/processing.gif" style="float: right;height: 60px;display: none;">

		                        <?php if($order['status']=='0')
				{ ?>
                                <a id="cr_paid<?php echo $order['sid']; ?>" href="" onclick="return paid_confirmation('<?php echo $order['sid']; ?>','<?php echo $order['company_id']; ?>','<?php echo $order['unit_id']; ?>','<?php echo number_format((float)$order['total_amount'], 2, '.', ''); ?>','<?php echo $order['store_id']; ?>');" data-toggle="tooltip" title="Pay Now" style="margin-left: 5px;width: 92px;" 
                                   class="btn btn-info"> Pay Now
                                    
                                </a>
                                <?php  }      ?>    
			<br/>

		 <?php if(($user_group_id=='1') || ($user_group_id=='27') || ($user_group_id=='22'))
				{ ?>
			<br/>
                                <a href=""  id="cr_download<?php echo $order['sid']; ?>" onclick="return download_pdf('<?php echo $order['store_id']; ?>','<?php echo $order['date_start']; ?>','<?php echo $filter_unit; ?>','<?php echo $order['sid']; ?>');" data-toggle="tooltip" title="Download PDF File" style="margin-left: 5px;" 
                                   class="btn btn-info"><i class="fa fa-download"></i> 
                                    
                                </a>
			<?php if($order['submission_date']=='')
				{ ?>
			&nbsp; 
			 <a href="" id="cr_save<?php echo $order['sid']; ?>" onclick="return save_submission_date('<?php echo $order['sid']; ?>','<?php echo $order['company_id']; ?>','<?php echo $order['unit_id']; ?>','<?php echo number_format((float)$order['total_amount'], 2, '.', ''); ?>','<?php echo $order['store_id']; ?>');" data-toggle="tooltip" title="Save Submission Date" style="margin-left: 5px;" 
                                   class="btn btn-info"> <i class="fa fa-upload"></i> 
                                    
                                </a>
		 <?php  }      ?> 
                                <?php  }      ?>   
              
                            </td>
			</tr>
		<?php
                    $a++;}
		}?>
              </tbody>
            </table>
          </div>
        
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
  </div>
  <script type="text/javascript">
function download_pdf(store_id,date_start,unit_id,sid)
{
//alert(store_id+date_start+unit_id);
url = 'index.php?route=report/reconciliation/create_bill&token=<?php echo $token; ?>';
	
        
	if (date_start) {
		url += '&filter_date=' + encodeURIComponent(date_start);
	}
        
        if (store_id) {
		url += '&filter_store_2=' + encodeURIComponent(store_id);
	}
       
        if (unit_id) {
		url += '&filter_unit_2=' + encodeURIComponent(unit_id);
	}
         
	
$("#cr_img"+sid).show();
$("#cr_paid"+sid).hide();
$("#cr_download"+sid).hide();
$("#cr_save"+sid).hide();

       
	//location = url;
	window.open(url, '_blank');
	location.reload();
	return false;

}
///////////////////////////////////////////
function save_submission_date(sid,company,unit,tamount1,store_id)
{
    $.ajax({ 
type: 'post',
url: 'index.php?route=factory/adjustment/save_submission_date&token='+getURLVar('token')+'&sid='+sid+'&store_id='+store_id,

//dataType: 'json',
cache: false,
beforeSend: function()
{
$("#cr_img"+sid).show();
$("#cr_paid"+sid).hide();
$("#cr_download"+sid).hide();
$("#cr_save"+sid).hide();
},
success: function(data) {

alertify.success('Submission date Saved Successfully');

location.reload();
}
});
return false;


}
  function currentunitbalance(unit)
{
var unitid=unit;
$.ajax({ 
type: 'post',
url: 'index.php?route=factory/adjustment/getstorebyunit&token='+getURLVar('token')+'&unitid='+unitid,
//data: 'companyid='+companyid,
//dataType: 'json',
cache: false,

success: function(data) {

//alert(data);
$("#input-store").html(data);
}
});




var company=$('#input-company').val();
//alert(unit);
//alert(company);
$.ajax({ 
type: 'post',
url: 'index.php?route=factory/adjustment/getUnitbalace&token='+getURLVar('token')+'&unit='+unit+'&company='+company,

//dataType: 'json',
cache: false,
success: function(data) {

//alert(data);
$("#current_balance").html(data);
}
});
}  


function clear_company(data) {
//alert(data);
//$('#select_state_P').hide();


var companyid=data;
$.ajax({ 
type: 'post',
url: 'index.php?route=factory/adjustment/getUnitbyCompany&token='+getURLVar('token')+'&companyid='+companyid,
//data: 'companyid='+companyid,
//dataType: 'json',
cache: false,

success: function(data) {

//alert(data);
$("#input-unit").html(data);
}
});
}    
function paid_confirmation(sid,company,unit,tamount1,store_id)
{
var currentbalance=parseInt($("#current_balance").html());
var tamount=parseInt(tamount1);
tamount=parseInt(tamount);
//alert(currentbalance+'----'+tamount);
if(currentbalance!='')
{
if(currentbalance>tamount)
{

    $.ajax({ 
type: 'post',
url: 'index.php?route=factory/adjustment/paidconfirmation&token='+getURLVar('token')+'&sid='+sid+'&company='+company+'&unit='+unit+'&tamount='+tamount1+'&store_id='+store_id,

//dataType: 'json',
cache: false,
beforeSend: function()
{
$("#cr_img"+sid).show();
$("#cr_paid"+sid).hide();
$("#cr_download"+sid).hide();
$("#cr_save"+sid).hide();
},
success: function(data) {
$("#paid_action_td_"+sid).html('');
$("#paid_status_td_"+sid).html('Paid');
var updated_balance=currentbalance-tamount;
$("#current_balance").html(updated_balance);
alertify.success('Adjusted Successfully');

//location.reload();
}
});
return false;
}
else
{
alertify.error('Current wallet balance is insufficient !');
return false;
}
}
else
{
alertify.error('Please select Factory Unit');
return false;
}
}
$('#button-filter').on('click', function() {
	url = 'index.php?route=factory/adjustment&token=<?php echo $token; ?>';
	
        
		
	var filter_letterno = $('#input-letter-no').val();
	
	if (filter_letterno) {
		url += '&filter_letterno=' + encodeURIComponent(filter_letterno);
	}
        
        var filter_store = $('#input-store').val();
        if (filter_store) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}
        var filter_unit = $('#input-unit').val();
        if (filter_unit) {
		url += '&filter_unit=' + encodeURIComponent(filter_unit);
	}
         var filter_company = $('#input-company').val();
        if (filter_company) {
		url += '&filter_company=' + encodeURIComponent(filter_company);
	}
     var filter_date_start = $('input[name=\'filter_date_start\']').val();
	//alert(filter_date_start);
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	
       
	location = url;
});



$('#button-download').on('click', function() {
    //alert("bjfdsbn");
 url = 'index.php?route=factory/adjustment/download_excel&token=<?php echo $token; ?>';
  
 var filter_letterno = $('#input-letter-no').val();
 
 if (filter_letterno) {
  url += '&filter_letterno=' + encodeURIComponent(filter_letterno);
 }
       
        var filter_store = $('#input-store').val();
        if (filter_store) {
  url += '&filter_store=' + encodeURIComponent(filter_store);
 }
        var filter_unit = $('#input-unit').val();
        if (filter_unit) {
  url += '&filter_unit=' + encodeURIComponent(filter_unit);
 }
         var filter_company = $('#input-company').val();
        if (filter_company) {
  url += '&filter_company=' + encodeURIComponent(filter_company);
 }
     var filter_date_start = $('input[name=\'filter_date_start\']').val();
 //alert(filter_date_start);
 if (filter_date_start) {
  url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
 }

 var filter_date_end = $('input[name=\'filter_date_end\']').val();
 
 if (filter_date_end) {
  url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
 }
 window.open(url, '_blank');
 //location = url;
});
</script> 
  <script type="text/javascript">
	$('.date').datetimepicker({
		pickTime: false
	});
	
	function reset_form()
	{
		$('[name=from]').val('');
		$('[name=to]').val('');
		$('[name=filter_id]').val('');
		$('[name=status]').prop('selectedIndex', 0);
	}
  </script>
<?php echo $footer; ?> 
