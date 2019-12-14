    <?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo "FM Code Update"; ?></h1>
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

<?php if ($error) {  ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>

    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i> <?php echo "FM Code Update"; ?></h3>
<!--		 <button type="button" id="button-pdf" class="btn btn-primary pull-right" style="margin-top: -8px !important; margin-right: 10px !important;">
            Download PDF</button>-->
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
<!--              <div class="form-group">
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
                 
              
        </div>-->
			 <div class="form-group">
			  <label class="control-label" for="input-invoice">Invoice</label>
                <div class="input-group col-sm-12">
                    <input type="text" name="filter_invoice" value="<?php echo $filter_invoice!=0?$filter_invoice:''; ?>" placeholder="Invoice No." maxlength="6" id="input-invoice" class="form-control" />
                </div>
			 </div>
 

 
 
            </div> 
			<div class="col-sm-6">
			
<!--            <div class="form-group">
                
                <label class="control-label" for="input-date-start">Date</label>
                <div class="input-group date" id="date_from">
                  <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="Date" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
            </div>-->
 
			
                <div class="form-group">
              </div>
            </div>
            
			<div class="col-sm-6">
           
              
		</div>
		<div class="col-sm-6">
			<div class="form-group">
                            <button type="button" id="button-filter" class="btn-small btn btn-primary pull-right mRight10"><i class="fa fa-search"></i> Search</button>&nbsp;&nbsp;
	    <button type="button" id="button-clear" class="btn-small btn btn-primary pull-right mRight10 margin15"><i class="fa fa-eraser"></i> Clear</button>	
            </div>
                
                </div> 
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
                <td class="text-right">FM Name</td>
                 <td class="text-right">FM Code</td>
   	        <td  class="text-right">Inv no.</td> 
                <td class="text-right">Grower ID</td>
                <td class="text-right">Grower Name</td>
		<td class="text-right">Grover No </td>
		<td class="text-right">Order Date</td>
                <!--<td class="text-right">Amount</td>-->
                <td class="text-right">Tagged-Amount</td>
                <td class="text-right">Action</td>
              </tr>            </thead>
            <tbody>
              <?php if ($orders) { if($_GET["page"]=="") {$aa=1;} else if($_GET["page"]=="1") {$aa=1;}
              else{ $aa=(($_GET["page"]-1)*20)+1; } ?>
              <?php foreach ($orders as $order) { //print_r($order); ?>
              <tr>
                <td class="text-left"><?php echo $aa; ?></td>
               
                <td class="text-right"><?php echo $order['store_name']; ?></td>
                <td class="text-right"><?php echo $order['fmname']; ?></td>
                <td class="text-right"><?php echo $order['fmcode']; ?></td>
                
	            <td class="text-right"><?php echo $order['inv_no']; ?></td>
                <td class="text-right"><?php echo $order['grower_id']; ?></td>
                <td class="text-right"><?php echo $order['farmer_name']; ?></td>
                <td class="text-right"><?php echo $order['telephone']; ?></td>
                <td class="text-right"><?php echo $order['dateorder']; ?></td>    
                <td class="text-right"><?php echo number_format((float)$order['tagged'], 2, '.', ''); ?></td>
                <td class="text-right">
                    <a href="javascript:void(0);" data-toggle="modal" data-target="#myModal_update" 
                       data-store="<?php echo $filter_store;?>" data-id="<?php echo $order['inv_no'];?>"
                       data-stats="" data-title="FM Update (Invoice Number : <?php echo $order['inv_no'];?> )" class="btn btn-info btn-small">
                        <i class="fa fa-s" title="update FM Code"></i>Update FM code</a>                                        
                </td>
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
          <div class="col-sm-6 text-right">button-clear
		<span style="font-weight: bold;">Page Total :: Total Amount : <?php echo number_format((float)$total_tagged_amount, 2, '.', ''); ?></span> 
                           
                           <br/>
		<?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
    
<!-- Modal -->
  <div class="modal fade" id="myModal_update" role="dialog">
      <div class="modal-dialog" style="width: 40%; height: 80%;">    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" id="partner_cncl_btn2" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"> </h4>
        </div>
        <div class="modal-body">
           <form>
          <div class="form-group">
              <input type="hidden" id="invoice_no" value="<?php echo $order['inv_no']; ?>">
            <label for="fm-name" class="col-form-label">FM Name:</label>
            <input type="text" class="form-control" id="fm_name" for="fm-name"  value="" maxlength="40" placeholder="Enter FM Name" required>
             <label for="fm_code" class="col-form-label">FM Code:</label>
             <input type="text" class="form-control" id="fm_code" value="" maxlength="6"   placeholder="Enter FM Code" required="">
          </div>
           </form>
          
        <div class="modal-footer">
            <button type="button" name="send" data-id="send"  class="btn btn-info btn-updatefmname" >Update</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>      
    </div>
  </div>

  <script type="text/javascript">
          jQuery('#myModal_update').on('show.bs.modal',function(e){
          //var inv_id = jQuery(e.relatedTarget).data('id');
          //alert(inv_id);
          //var url = 'index.php?route=reportbcml/updatefm/updatefmname&token=<?php echo $token; ?>';
          jQuery(e.currentTarget).find('.modal-title').text(jQuery(e.relatedTarget).data('title'));
          //alert(jQuery(e.relatedTarget).data('id'));
          jQuery(e.currentTarget).find('.btn-updatefmname').data('id',jQuery(e.relatedTarget).data('id'));
      });
    jQuery('#myModal_update').on('click','.btn-updatefmname',function(e){
          //var invoice_id = jQuery(e.relatedTarget).data('id');
          //var inv_id  = jQuery(this).data('id');
          var fm_name = jQuery('#fm_name').val();
          var fm_code = jQuery('#fm_code').val();
          var invoice_no = jQuery('#invoice_no').val();
         //alert(invoice_no);
         // return false;
          jQuery.post('index.php?route=reportbcml/updatefm/updatefmname&token=<?php echo $token; ?>',
          {invoice_no:invoice_no,fm_name:fm_name,fm_code:fm_code},function(data,status){
              if(data.status == 'success'){
                 // jQuery('.btn-updatefmname').attr('disabled','disabled');
                  jQuery('#myModal_update').modal('hide');
                  alertify.success(data.responce);
                  //location.reload(true);
                  setTimeout(function () { location.reload(1); }, 1300);
              }else{                  
                  alertify.error(data.responce);
              }
          },'json'); 
      });
      
      jQuery('#button-clear').on('click',function(){
          jQuery('input#input-invoice').val('');
          jQuery('table.table-bordered').find('tbody').html('');
          
      });
      
      jQuery('input#input-invoice').on('change',function(){
          jQuery('#myModel_update').show();
      });
      
$("#input-store").select2();
$("#input-fm").select2();
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

	var filter_invoice = $('input[name=\'filter_invoice\']').val();
	if(!filter_invoice)
	{
	  alertify.error('Please Enter invoice');
	  return false;
	}

	
	url = 'index.php?route=reportbcml/updatefm/updatefmcode&token=<?php echo $token; ?>';
	
	
	
	
	if (filter_invoice != '') {
		url += '&filter_invoice=' + encodeURIComponent(filter_invoice);
	}	

        //alert(url);
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
		url += '&filter_invoice=' + encodeURIComponent(filter_invoice);
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
   
	var filter_invoice = $('input[name=\'filter_invoice\']').val	//alert(filter_fm_name);
	
	if(!filter_store)
	{
	  alertify.error('Please Select Store');
	  return false;
	}
	if(!filter_invoice)
	{
//	  alertify.error('Please Select Unit');
//	  return false;
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

	if (filter_invoice) {
		url += '&filter_invoice=' + encodeURIComponent(filter_invoice);
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
		
	var filter_invoice = $('select[name=\'filter_invoice\']').val(); 
	
	if (filter_invoice != '') {
		url += '&filter_invoice=' + encodeURIComponent(filter_invoice);
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