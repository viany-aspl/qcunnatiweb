<?php echo $header; ?><?php echo $column_left;//print_r($results_n); ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1>Sale Summary</h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i> Sale Summary</h3>
        <button type="button" id="button-download" class="btn btn-primary pull-right" style="margin-top: -8px !important;">
            Download</button>
      </div>
      <div class="panel-body">
        

        <div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-start"><?php echo $entry_date_start; ?></label>
                <div class="input-group date" id="date_from">
                  <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-date-end"><?php echo $entry_date_end; ?></label>
                <div class="input-group date" id="date_to">
                  <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-end">Select Store</label>
               
                  <select name="filter_store" style="width: 100%" id="input-store" class="form-control">
                   <option selected="selected" value="">SELECT STORE</option>
                  <?php foreach ($stores as $store) { //echo $store['store_id'];  ?>
                  <?php if ($store['store_id'] == $filter_store) {
                      if($filter_store!=""){
                      ?>
                  <option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
                      <?php }} else { ?>
                  <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                  
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> Search</button>
            </div>
          </div>
        </div>


        <div class="table-responsive">
<span style="font-weight: bold;">Total Cash : <?php echo number_format((float)$total_cash_all, 2, '.', ''); ?></span> 
           &nbsp; | &nbsp;
<span style="font-weight: bold;">Total C-Tagged : <?php echo number_format((float)$total_cash_tagged_all, 2, '.', ''); ?></span> 
	&nbsp; | &nbsp;
	<span style="font-weight: bold;">Total C-Subsidy : <?php echo number_format((float)$total_subsidy_all, 2, '.', ''); ?></span> 
          &nbsp; | &nbsp;
 <span style="font-weight: bold;">Total Tagged : <?php echo number_format((float)$total_tagged_all, 2, '.', ''); ?></span> 
           &nbsp; | &nbsp;
           <span style="font-weight: bold;">Total Subsidy (Company ) : <?php echo number_format((float)$total_cash_subsidy_all, 2, '.', ''); ?></span> 
           
           &nbsp; | &nbsp;  
           <span style="font-weight: bold;">Total : <?php $total=number_format((float)$total_cash_all, 2, '.', '')+number_format((float)$total_cash_tagged_all, 2, '.', '')+number_format((float)$total_subsidy_all, 2, '.', '')+number_format((float)$total_tagged_all, 2, '.', '')+
number_format((float)$total_cash_subsidy_all, 2, '.', ''); echo $total; // ?></span> 
<br/>
<span style="float: right;font-weight: bold;color: #933B3B;">Note : C-Tagged=>Cash Tagged, C-Subsidy=>Cash Taken by store incharge for subsidy order</span>
           <br/><br/>
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left">S. No.</td>
                <td class="text-right">Store name</td>
                <!--<td class="text-right">Store id</td>
                <td class="text-right">Store Credit Limit</td>
	 <td class="text-right">Current Credit</td>-->
	<td class="text-right">Store Cash</td>
          	<td class="text-right">Store C-Tagged</td>
              <td class="text-right">Store C-Subsidy</td>

                <td class="text-right">Store Tagged</td> 
	<td class="text-right">BCML Tagged</td> 
	<td class="text-right">Subsidy(By Company )</td>
	
                <td class="text-right">No. Order (Cash)</td>
                <td class="text-right">No. Order (Tagged)</td>
	<td class="text-right">No. Order (C-Tagged)</td>
	<td class="text-right">No. Order (Subsidy)</td>
	
                <td class="text-left">Total</td>
                
              </tr>
            </thead>
            <tbody>
              <?php $total=0; if ($results) {  if($_GET["page"]=="") {$aa=1;} else if($_GET["page"]=="1") {$aa=1;}
              else{ $aa=(($_GET["page"]-1)*20)+1; } ?>
              <?php foreach ($orders as $order) { //print_r($order); ?>
              <tr>
               <td class="text-left"><?php echo $aa; ?></td>
                
              <td class="text-right"><?php echo $order['store_name']; ?></td>
              <!--<td class="text-right"><?php echo $order['store_id']; ?></td>
              <td class="text-right"><?php echo $order['creditlimit']; ?></td>
              <td class="text-right"><?php echo $order['currentcredit']; ?></td>-->
              <td class="text-right"><?php echo $order['cash']; ?></td> 
	<td class="text-right"><?php echo $order['Cash_Tagged']; ?></td>
	<td class="text-right"><?php echo $order['Cash_subsidy']; ?></td>
	<td class="text-right"><?php echo $order['tagged']; ?></td>
	<td class="text-right" style="color: navy;"><?php echo $order['bcml_tagged']; ?></td>
	<td class="text-right"><?php echo $order['subsidy']; ?></td>
	
              <td class="text-right"><?php echo $order['cash_order']; ?></td>
              <td class="text-right"><?php echo $order['tagged_order']; ?></td>
	<td class="text-right"><?php echo $order['Cash_tagged_order']; ?></td>
	<td class="text-right"><?php echo $order['subsidy_order']; ?></td>
              <td class="text-left"><?php echo $order['total']; ?></td>
                
              </tr>
              <?php $tarr=explode('Rs.',$order['total']);$total=$total+$tarr[1];  $aa++; } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
        <div class="row">
          <div class="col-sm-4 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-8 text-right">
	<span style="font-weight: bold;">Page Total  :: </span> 
           &nbsp;  
           <span style="font-weight: bold;">Total Cash : <?php echo number_format((float)$total_cash, 2, '.', '');; ?></span> 
	 &nbsp; | &nbsp; 
	 <span style="font-weight: bold;">Total C-Tagged : <?php echo number_format((float)$total_Cash_Tagged, 2, '.', '');; ?></span>
	 &nbsp; | &nbsp; 
	 <span style="font-weight: bold;">Total C-Subsidy : <?php echo number_format((float)$total_Cash_subsidy, 2, '.', '');; ?></span>
           &nbsp; | &nbsp;
           <span style="font-weight: bold;">Total Tagged : <?php echo number_format((float)$total_tagged, 2, '.', '');; ?></span> 
           &nbsp; | &nbsp;
           <span style="font-weight: bold;">Total Subsidy (By Company): <?php echo number_format((float)$total_subsidy, 2, '.', '');; ?></span> 
           
           &nbsp; | &nbsp; 
           <span style="font-weight: bold;">Total : <?php $total=number_format((float)$total_cash, 2, '.', '')+number_format((float)$total_tagged, 2, '.', '')+number_format((float)$total_subsidy, 2, '.', '')+number_format((float)$total_Cash_Tagged, 2, '.', '')+$total_Cash_subsidy; echo $total; ?></span> 
           <br/>

         <?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
 </div>
<script type="text/javascript">
$("#input-store").select2();
    var maxDate =  new Date();
    //var minDate = new Date(maxDate.getFullYear(), maxDate.getMonth(), +1); //one day next before month
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

</script>
  <script type="text/javascript">

/*
$('.date').datetimepicker({
	pickTime: false
});
*/
$('#button-filter').on('click', function() {
	url = 'index.php?route=reportbcml/sale_summary/subsidycash&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
		
	var filter_store = $('select[name=\'filter_store\']').val();
	
	if (filter_store) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}			
	location = url;
});

$('#button-download').on('click', function() {
    url = 'index.php?route=reportbcml/sale_summary/download_excel_subsidycash&token=<?php echo $token; ?>';
    	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
		
	var filter_store = $('select[name=\'filter_store\']').val();
	
	if (filter_store) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}
    
    //location = url;
        window.open(url, '_blank');
});
</script>
<?php echo $footer; ?>