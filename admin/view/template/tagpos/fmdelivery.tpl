<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo "FM Delivery"; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>">FM Delivery</a></li>
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

<?php if ($error) {  ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>

    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i> <?php echo "FM Delivery"; ?></h3>
		 <button type="button" id="button-pdf" class="btn btn-primary pull-right" style="margin-top: -8px !important; margin-right: 10px !important;">
            Download PDF</button>
         <!--<button type="button" id="button-download" class="btn btn-primary pull-right" style="margin-top: -8px !important;">
            Download Excel</button>
        
       
       <button type="button" id="button-bill" class="btn btn-primary pull-right" onclick="open_model()" style="margin-top: -8px !important; margin-right: 10px !important;">
            Create Bill</button>
        <button type="button" id="button-details" class="btn btn-primary pull-right" style="margin-top: -8px !important; margin-right: 10px !important;">
            Download Itemized Excel</button>  -->
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-store">Select Store</label>
                
                  <?php //echo $filter_store; print_r($stores);//exit; ?>
              
                  <select name="filter_store" id="input-store" class="form-control" style="width:100%;" onchange="clear_store(this.value)" >
				  <option value="">Select Store</option>
                  <?php foreach ($stores as $store) { ?>
                  <?php if ($store['store_id'] == $filter_store) { ?>
                  <option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                 
              
        </div>
			 <div class="form-group">
			 <label class="control-label" for="filter_porder_id">FM Name:</label> <?php echo $filter_fm_name; ?>
					<select name="filter_fm_name" id="input-fm" style="width:100%;"  class="form-control"  >
						<option value="0">Select FM</option>
						 <?php if(!empty($dfm)){ 
				  foreach($dfm as $dfm2)
				  { ?>
				  <option <?php if(trim($dfm2['FM_CODE'])==$filter_fm_name){ ?> selected="selected" <?php } ?> value="<?php echo trim($dfm2['FM_CODE']); ?>"><?php echo $dfm2['FM_NAME']; ?></option>
				  <?php }
				   } ?>
					</select>
			 </div>
 

 
 
            </div> 
			<div class="col-sm-6">
			
            <div class="form-group">
                <label class="control-label" for="input-units">Select Unit</label>
				<select name="filter_unit" id="input-unit" class="form-control" onchange="clear_unit(this.value)">
                  <option value="0">Select Unit</option>
				  <?php if(!empty($dunit)){ 
				  foreach($dunit as $dunit2)
				  { ?>
				  <option <?php if($dunit2['unit_id']==$filter_unit){ ?> selected="selected" <?php } ?> value="<?php echo $dunit2['unit_id']; ?>"><?php echo $dunit2['unit_name']; ?></option>
				  <?php }
				   } ?>
                </select>
            </div>
 
			
                <div class="form-group">
                <label class="control-label" for="input-date-start">Date</label>
                <div class="input-group date" id="date_from">
                  <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="Date" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
            </div>
            
			<div class="col-sm-6">
           
              
		</div>
		<div class="col-sm-6">
			<div class="form-group">
				<label class="control-label" for="input-date-end"></label>
            <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
			
            </div></div>
          </div>
        </div>
        <div class="table-responsive">
	<!---<span style="font-weight: bold;">Total Amount : <?php echo number_format((float)$total_tagged_amount_All, 2, '.', ''); ?></span> --->
                           
                           <br/><br/>
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left">S.No.</td>

                <td class="text-right">Store Name</td>
                <td class="text-right">Store ID</td>	
   	            <td  class="text-right">Inv no.</td> 
                <td class="text-right">Grower ID</td>
                <td class="text-right">Grower Name</td>
				
                <td class="text-right">Create Date </td>
				<td class="text-right">Order Date</td>
                <!--<td class="text-right">Amount</td>-->
                <td class="text-right">Tagged-Amount</td>
				<td class="text-right">Subsidy-Amount</td>
              </tr>            </thead>
            <tbody>
              <?php if ($orders) { if($_GET["page"]=="") {$aa=1;} else if($_GET["page"]=="1") {$aa=1;}
              else{ $aa=(($_GET["page"]-1)*20)+1; } ?>
              <?php foreach ($orders as $order) { //print_r($order); ?>
              <tr>
                <td class="text-left"><?php echo $aa; ?></td>
               
                <td class="text-right"><?php echo $order['store_name']; ?></td>
                <td class="text-right"><?php echo $order['store_id']; ?></td>
                
	            <td class="text-right"><?php echo $order['inv_no']; ?></td>
                <td class="text-right"><?php echo $order['grower_id']; ?></td>
                <td class="text-right"><?php echo $order['grower_name']; ?></td>
				<td class="text-right"><?php echo $order['date']; ?></td>
				 <td class="text-right"><?php echo $order['dateorder']; ?></td>
                
                
                <td class="text-right"><?php echo number_format((float)$order['tagged'], 2, '.', ''); ?></td>
				<td class="text-right"><?php echo number_format((float)$order['subsidy'], 2, '.', ''); ?></td>
              </tr>              <?php $aa++; } ?>
              <?php  } else { ?>
              <tr>
                <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right">
		<span style="font-weight: bold;">Page Total :: Total Amount : <?php echo number_format((float)($total_tagged_amount+$total_subsidy_amount), 2, '.', ''); ?></span> 
                           
                           <br/>
		<?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>






  <script type="text/javascript">
$("#input-store").select2();
$("#input-fm").select2();

function clear_store(data) {
//alert(data);
var storeid=data;
$.ajax({ 
type: 'post',
url: 'index.php?route=tagpos/fmdelivery/getUnitbyStore&token='+getURLVar('token'),
data: 'storeid='+storeid,
//dataType: 'json',
cache: false,

success: function(data) {

//alert(data);
$("#input-unit").html(data);
}
});
} 
function clear_unit(data) {
//alert(data);
var unitid=data;
$.ajax({ 
type: 'post',
url: 'index.php?route=tagpos/fmdelivery/getfm&token='+getURLVar('token'),
data: 'unitid='+unitid,
//dataType: 'json',
cache: false,

success: function(data) {

//alert(data);
$("#input-fm").html(data);
}
});
} 


function get_unit(store_id)
{ //alert(store_id);
url = 'index.php?route=report/reconciliation/get_store_unit&token=<?php echo $token; ?>&store_id='+store_id;
$.ajax({
              url: url,
              // dataType: 'json',
               success: function(json) 
               {
                           //alert(json);	
		$("#input-unit_2").html(json);  
               }
                       
              });

}

function open_model()
{
$('#myModal_create_bill').modal('show');
$('input[name=\'filter_date\']').val('');
$('select[name=\'filter_store_2\']').val('');
$('select[name=\'filter_unit_2\']').val('');

return false;
}
function myFunction() {
    
             var filter_date = $('input[name=\'filter_date\']').val();
	
              var filter_store = $('select[name=\'filter_store_2\']').val();
	
	var filter_unit = $('select[name=\'filter_unit_2\']').val();
	
	if ( (filter_date!= '') && (filter_store != '') && (filter_unit != '') ){
		
		$('#myModal_create_bill').modal('hide');
		
		return true;
	}

   
   return false;
    
}

$('#button-filter').on('click', function() {

	var filter_fm_name = $('select[name=\'filter_fm_name\']').val();
	var filter_store = $('select[name=\'filter_store\']').val();
	var filter_unit = $('select[name=\'filter_unit\']').val();
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	//alert(filter_fmname);
	
	if(!filter_store)
	{
	  alertify.error('Please Select Store');
	  return false;
	}
	if(!filter_unit)
	{
	  alertify.error('Please Select Unit');
	  return false;
	}
	if(!filter_fm_name)
	{
	  //alertify.error('Please Select FM Name');
	  //return false;
	}
	if(!filter_date_start)
	{
	  alertify.error('Please Select Date');
	  return false;
	}
	
	url = 'index.php?route=tagpos/fmdelivery&token=<?php echo $token; ?>';
	
	
	if (filter_date_start!='0') {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	if (filter_fm_name != 0) {
		url += '&filter_fm_name=' + encodeURIComponent(filter_fm_name);
	}
		
	
	
	if (filter_unit != '') {
		url += '&filter_unit=' + encodeURIComponent(filter_unit);
	}	

	
	if (filter_store != '0') {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}

	location = url;
});
</script> 
 <script type="text/javascript"><!--
$('#button-download').on('click', function() {
	url = 'index.php?route=reportbcml/reconciliation/download_excel&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
		
	var filter_unit = $('select[name=\'filter_unit\']').val();
	
	if (filter_unit != '') {
		url += '&filter_unit=' + encodeURIComponent(filter_unit);
	}	

	
         var filter_store = $('select[name=\'filter_store\']').val();
	
	if (filter_store != 0) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}	
        window.open(url, '_blank');
	//location = url;
});
//--></script> 
        
<script type="text/javascript">
$('#button-pdf').on('click', function() {

    var filter_fm_name = $('select[name=\'filter_fm_name\']').val();
	var filter_store = $('select[name=\'filter_store\']').val();
	var filter_unit = $('select[name=\'filter_unit\']').val();
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	//alert(filter_fm_name);
	
	if(!filter_store)
	{
	  alertify.error('Please Select Store');
	  return false;
	}
	if(!filter_unit)
	{
	  alertify.error('Please Select Unit');
	  return false;
	}
	if(!filter_fm_name)
	{
	  //alertify.error('Please Select FM Name');
	  //return false;
	}
	if(!filter_date_start)
	{
	  alertify.error('Please Select Date');
	  return false;
	}
	url = 'index.php?route=tagpos/fmdelivery/download_pdf&token=<?php echo $token; ?>';
	//alert(url);
	
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	if (filter_unit) {
		url += '&filter_unit=' + encodeURIComponent(filter_unit);
	}
	if (filter_store) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}
	
    if (filter_fm_name) {
		url += '&filter_fm_name=' + encodeURIComponent(filter_fm_name);
	}	
	//alert(url);
        window.open(url, '_blank');
	//location = url;
});


$('#button-details').on('click', function() {
	url = 'index.php?route=reportbcml/reconciliation/download_item_excel&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
		
	var filter_unit = $('select[name=\'filter_unit\']').val(); 
	
	if (filter_unit != '') {
		url += '&filter_unit=' + encodeURIComponent(filter_unit);
	}	

         var filter_store = $('select[name=\'filter_store\']').val();
	
	if (filter_store != 0) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}	
        window.open(url, '_blank');
	//location = url;
});

//--></script> 
        <script type="text/javascript">
/*
    var maxDate =  new Date();
    //var minDate = new Date(maxDate.getFullYear(), maxDate.getMonth(), +1); //one day next before month
$("#date_cr").datetimepicker({
  timepicker: false,
  pickTime: false,
  maxDate: maxDate,
  closeOnDateSelect: true
}); 
  $("#date_from").datetimepicker({
  timepicker: false,
  pickTime: false,
  maxDate: maxDate,
  closeOnDateSelect: true
}).on('dp.change', function (ev) {
   
   change_to_date();
});
$("#date_to").datetimepicker({
  showClear: true,
  timepicker: false,
  pickTime: false,
  
  maxDate: maxDate,
  closeOnDateSelect: true
}).on('dp.change', function (ev) {
   
   change_to_equal_date();
});
function change_to_equal_date()
{
var frm=$("#input-date-start").val(); 
var too=$("#input-date-end").val(); 
var fromTime = new Date(frm);
var minDate = new Date(fromTime.getFullYear(), fromTime.getMonth(), +1); //one day next before month
var maxDate =  new Date(fromTime.getFullYear(), fromTime.getMonth() +1, +0); // one day before next month
var date_to=convert(maxDate);
var toTime = new Date(too);

var millisecondsPerDay = 1000 * 60 * 60 * 24;
var millisBetween = toTime.getTime()-fromTime.getTime();
var days = millisBetween / millisecondsPerDay;

//alert(fromTime+' && '+toTime+' && '+ days);

    if(new Date(frm).getTime()>new Date(too).getTime())
    {
        $("#input-date-end").val(date_to);
        alertify.error('End date can not be less then start date');
    }
    if(days>31)
    {
        $("#input-date-end").val(date_to);
        alertify.error('There can be maximum 1 month difference between start date and end date');
    }
}
function change_to_date()
{
var frm=$("#input-date-start").val();
var fromTime = new Date(frm);
var minDate = new Date(fromTime.getFullYear(), fromTime.getMonth(), +1); //one day next before month
var maxDate =  new Date(fromTime.getFullYear(), fromTime.getMonth() +1, +0); // one day before next month
var date_to=convert(maxDate);

$("#input-date-end").val(date_to);
//$("#date_to").datetimepicker('update', "2017/09/20");
//$("#date_to").removeClass("date");

$("#date_to").datetimepicker({
  showClear: true,
  timepicker: false,
  pickTime: false,
  minDate: minDate, 
  maxDate: maxDate,
  closeOnDateSelect: true
});
    
}
$('#date_from').change(function(){
   $(this).next('input.datetimepicker').destroy(); 

   $("#date_to").next('input.datetimepicker').datetimepicker({
       minDate:$(this).val()
   });
});
function convert(str) {
    var date = new Date(str),
        mnth = ("0" + (date.getMonth()+1)).slice(-2),
        day  = ("0" + date.getDate()).slice(-2);
    return [ date.getFullYear(), mnth, day ].join("-");
}
*/
</script>
  <script type="text/javascript">

$('.date').datetimepicker({
	pickTime: false,
	maxDate: new Date()
});

</script></div>
<?php echo $footer; ?>