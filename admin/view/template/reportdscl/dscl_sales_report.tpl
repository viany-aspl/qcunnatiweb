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
                <label class="control-label" for="input-date-start">Financial Year</label>
                
					<select onchange="return set_date(this.value);" class="select2 form-control" style="width: 100%"  id='year' name='year' required>
						<?php for($a=2016;$a<(date('Y'));$a++){ ?>
						<option <?php if(!empty($year)){  if($year==$a){?> selected='selected' <?php } } else { if(($a+1)==(date('Y'))){ ?> selected='selected' <?php }} ?>value='<?php echo $a.'-'.($a+1); ?>'><?php echo $a.'-'.($a+1);  ?></option>
						<?php } ?>
					</select>
                
              </div>
			  <div class="form-group">
                <label class="control-label" for="input-date-end">Select Unit</label>
                <div class="input-group">
                  <span class="input-group-btn">
                      
                  <select name="filter_unit" id="input-unit" class="form-control">
                         <option value="" >SELECT UNIT</option>
			<?php foreach($units as $unit) {  ?>
				<option value="<?php echo $unit['unit_id']; ?>" <?php if ($unit['unit_id'] == $filter_unit) { ?> selected="selected" <?php } ?>><?php echo $unit['unit_name']; ?></option> 
			<?php } ?>
			<!--
                                          <option value="01" <?php if ('01' == $filter_unit) { ?> selected="selected" <?php } ?>>AJBAPUR</option>
			<option value="02" <?php if ('02' == $filter_unit) { ?> selected="selected" <?php } ?>>RUPAPUR</option>
			<option value="03" <?php if ('03' == $filter_unit) { ?> selected="selected" <?php } ?>>HARIYAWAN</option>
			<option value="04" <?php if ('04' == $filter_unit) { ?> selected="selected" <?php } ?>>LONI</option>
                                   	-->
                </select>
                  </span></div>
              </div>
              
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-start">Date</label>
                <div class="input-group date" id="date_to">
                  <input type="text" name="filter_date"  value="<?php echo $filter_date; ?>" placeholder="Date" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
            
          </div>
        </div>
        <div class="table-responsive">
	<span style="font-weight: bold;">
                  <!--Total Sales : <?php //echo number_format((float)$total_sales_all, 2, '.', ''); ?> | 
                  Total tax : <?php //echo number_format((float)$Total_tax_all, 2, '.', ''); ?> | 
                  Total (Sales + Tax) : <?php //echo number_format((float)$Total_all, 2, '.', ''); ?> -->
              </span> 
                           
                           <br/><br/>
          <table class="table table-bordered">
            <thead>
	<tr>
	   <td class="text-left" colspan="2"></td>
                <td class="text-center" colspan="2"><?php echo $filter_date; ?></td>
                <td class="text-center" colspan="2">To Date (<?php echo date('M,d-Y',strtotime($filter_year_start)); echo ' to '; echo date('M,d-Y',strtotime($filter_year_end)); ?>)</td> 
              </tr>
              <tr>
                <td class="text-left">Factory Unit</td>
                <td class="text-left">Store Name</td>
                <td class="text-left">Cash</td>
                <td class="text-left">Tagging</td>
          
                <td class="text-left">Cash</td>
                <td class="text-left">Tagging</td>
                
                
              </tr>
            </thead>
            <tbody>
              <?php if ($orders) { $total_sales=0; ?>
              <?php foreach ($orders as $order) { ?>
              <tr>
                <td class="text-left"><?php echo $order['unit_name']; ?></td>
                <td class="text-left"><?php echo $order['store_name']; ?></td>
          
                <td class="text-left"><?php echo number_format((float)$order['today_cash'], 2, '.', ''); ?></td>
	  <td class="text-left"><?php echo number_format((float)$order['today_tagged'], 2, '.', ''); ?></td>
                <td class="text-left"><?php echo number_format((float)$order['till_date_cash'], 2, '.', ''); ?></td>
                <td class="text-left"><?php echo number_format((float)$order['till_date_tagged'], 2, '.', ''); ?></td>
                
              </tr>
              <?php 
              $total_sales=$total_sales+$order['Total_sales'];
              $Total_tax=$Total_tax+($order['qnty']*$order['Total_tax']);
              $Total=$Total+number_format((float)($order['qnty']*(($order['Total_sales']/$order['qnty'])+$order['Total_tax'])), 2, '.', '');
              } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right">
              
              
              <br/>
              <?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript">
$('#button-filter').on('click', function() {
	url = 'index.php?route=reportdscl/report&token=<?php echo $token; ?>';
	
	var filter_date = $('input[name=\'filter_date\']').val();
	
	if (filter_date) { 
		url += '&filter_date=' + encodeURIComponent(filter_date);
	}
	var filter_year = $('#year').val();
	
	if (filter_year) { 
		url += '&filter_year=' + encodeURIComponent(filter_year);
	}
	var filter_unit = $('#input-unit').val();
	
	if (filter_unit) { 
		url += '&filter_unit=' + encodeURIComponent(filter_unit);
	}
	
	location = url;
});
//--></script> 
<script type="text/javascript">
$('#button-download').on('click', function() {
    url = 'index.php?route=reportdscl/report/download_dscl_sales_report&token=<?php echo $token; ?>';
   
    var filter_date = $('input[name=\'filter_date\']').val();
   
    if (filter_date) {
        url += '&filter_date=' + encodeURIComponent(filter_date);
    }
	var filter_year = $('#year').val();
	
	if (filter_year) { 
		url += '&filter_year=' + encodeURIComponent(filter_year);
	}
	var filter_unit = $('#input-unit').val();
	
	if (filter_unit) { 
		url += '&filter_unit=' + encodeURIComponent(filter_unit);
	}
    //location = url;
    window.open(url, '_blank');
});
//--></script>

  <script type="text/javascript">
function set_date(cur_year)
{
	//alert(cur_year);
	f_year=cur_year.split('-');
	change_to_date(f_year[0]+'-10-01');
	return false;
}
function change_to_date(frm)
{
var fromTime = new Date(frm);
var minDate = new Date(fromTime.getFullYear(), fromTime.getMonth(), +1); //one day next before month
var maxDate =  new Date(fromTime.getFullYear(), fromTime.getMonth() +12, +0); // one day before next month
var date_to=convert(maxDate);

$("input[name=\'filter_date\']").val(date_to);

$("#date_to").removeClass("date");
//$("#date_to").addClass("date");
$("#date_to").datetimepicker("destroy");

$(".date").datetimepicker({
  showClear: true,
  timepicker: false,
  pickTime: false,
  minDate: minDate, 
  maxDate: maxDate,
  closeOnDateSelect: true
});
    
}
function convert(str) {
    var date = new Date(str),
        mnth = ("0" + (date.getMonth()+1)).slice(-2),
        day  = ("0" + date.getDate()).slice(-2);
    return [ date.getFullYear(), mnth, day ].join("-");
}

var frm=((new Date().getFullYear())-1)+'-10-01';
var fromTime = new Date(frm);
var minDate = new Date(fromTime.getFullYear(), fromTime.getMonth(), +1); //one day next before month
var maxDate =  new Date(fromTime.getFullYear(), fromTime.getMonth() +12, +0); // one day before next month
var date_to=convert(maxDate);

$('.date').datetimepicker({
	showClear: true,
  timepicker: false,
  pickTime: false,
  minDate: minDate, 
  maxDate: maxDate,
  closeOnDateSelect: true
});

</script>
      
      </div>
<?php echo $footer; ?>