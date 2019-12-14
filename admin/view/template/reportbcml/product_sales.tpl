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
        <button type="button" id="button-download" class="btn btn-primary pull-right" style="margin-top: -8px !important;margin-left: 5px;">
            Download EXcel</button> 
			<button type="button" id="button-download-csv" class="btn btn-primary pull-right" style="margin-top: -8px !important;margin-left: 5px;">
            Download CSV</button>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-start"><?php echo $entry_date_start; ?></label>
                <div class="input-group date" id="date_from">
                  <input type="text" name="filter_date_start"  value="<?php echo $filter_date_start; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
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
                <!--<div class="form-group">
                <label class="control-label" for="input-date-end">Select Store</label>
                <div class="input-group date">
                  <?php //echo $filter_store; print_r($stores);//exit; ?>
                  <span class="input-group-btn">
                      
                  <select name="filter_store" id="input-store" class="form-control">
                  <?php foreach ($stores as $store) { ?>
                  <?php if ($store['store_id'] == $filter_store) { ?>
                  <option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                  </span></div>
              </div>-->
            </div>
            <div class="col-sm-6">
              <!--<div class="form-group">
                <label class="control-label" for="input-group"><?php echo $entry_group; ?></label>
                <select name="filter_group" id="input-group" class="form-control">
                  <?php foreach ($groups as $group) { ?>
                  <?php if ($group['value'] == $filter_group) { ?>
                  <option value="<?php echo $group['value']; ?>" selected="selected"><?php echo $group['text']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $group['value']; ?>"><?php echo $group['text']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>-->
              <div class="form-group">
                <label class="control-label" for="input-name">Product Name</label>
                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
		<input type="hidden" name="filter_name_id"  value="<?php echo $filter_name_id; ?>" id="filter_name_id"/>
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
                <td class="text-left">Sale Date</td>
                <td class="text-left">Store Name</td>
                <td class="text-left">Store ID</td>
                <td class="text-left">Product Name</td>
                
                <!--<td class="text-left">No of Orders</td>-->
                <td class="text-left">Quantity</td>
                <td class="text-left">Rate(without tax)</td>
                <td class="text-left">Tax title</td>
                <td class="text-left">Tax rate</td>
                <td class="text-left">Total (Sales + Tax)</td>
                
              </tr>
            </thead>
            <tbody>
              <?php if ($orders) { $total_sales=0; ?>
              <?php foreach ($orders as $order) { ?>
              <tr>
                <td class="text-left"><?php echo $order['dats']; ?></td>
                <td class="text-left"><?php echo $order['store_name']; ?></td>
                <td class="text-left"><?php echo $order['store_id']; ?></td>
                <td class="text-left"><?php echo $order['name']; ?></td>
                
               <!-- <td class="text-left"><?php echo $order['No_of_orders']; ?></td>-->
                <td class="text-left"><?php echo $order['qnty']; ?></td>
                <td class="text-left"><?php echo number_format((float)($order['Total_sales']/$order['qnty']), 2, '.', ''); ?></td>
                <td class="text-left"><?php echo $order['tax_title']; ?></td>
                <td class="text-left"><?php echo number_format((float)$order['Total_tax'], 2, '.', ''); ?></td>
                <td class="text-left"><?php echo number_format((float)($order['qnty']*(($order['Total_sales']/$order['qnty'])+$order['Total_tax'])), 2, '.', ''); ?></td>
                
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
              <span style="font-weight: bold;">
                 Page Total ::  
                  Total tax : <?php echo number_format((float)$Total_tax, 2, '.', ''); ?> | 
                  Total (Sales + Tax) : <?php echo number_format((float)$Total, 2, '.', ''); ?> 
              </span> 
              
              <br/>
              <?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript">
$('#button-filter').on('click', function() {
	url = 'index.php?route=reportbcml/product_sales&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) { 
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
		
	var filter_group = $('select[name=\'filter_group\']').val();
	
		
        var filter_name_id = $('input[name=\'filter_name_id\']').val();
        var filter_name = $('input[name=\'filter_name\']').val();

	if (filter_name_id) {
                if(filter_name!="")
                {
		url += '&filter_name_id=' + encodeURIComponent(filter_name_id);
                }
	}
        
        
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}

        
	location = url;
});
//--></script> 
<script type="text/javascript">
$('#button-download-csv').on('click', function() {
    url = 'index.php?route=reportbcml/product_sales/download_csv&token=<?php echo $token; ?>';
   
    var filter_date_start = $('input[name=\'filter_date_start\']').val();
   
    if (filter_date_start) {
        url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
    }

    var filter_date_end = $('input[name=\'filter_date_end\']').val();
   
    if (filter_date_end) {
        url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
    }
       
var filter_store = $('select[name=\'filter_store\']').val();
	
	if (filter_store != 0) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}

    //var filter_group = $('select[name=\'filter_group\']').val();
   
       
        var filter_name_id = $('input[name=\'filter_name_id\']').val();
        var filter_name = $('input[name=\'filter_name\']').val();

    if (filter_name_id) {
                if(filter_name!="")
                {
        url += '&filter_name_id=' + encodeURIComponent(filter_name_id);
                }
    }
       

    if (filter_name) {
        url += '&filter_name=' + encodeURIComponent(filter_name);
    }
	

    //location = url;
    window.open(url, '_blank');
});
$('#button-download').on('click', function() {
    url = 'index.php?route=reportbcml/product_sales/download_excel&token=<?php echo $token; ?>';
   
    var filter_date_start = $('input[name=\'filter_date_start\']').val();
   
    if (filter_date_start) {
        url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
    }

    var filter_date_end = $('input[name=\'filter_date_end\']').val();
   
    if (filter_date_end) {
        url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
    }
       
    var filter_group = $('select[name=\'filter_group\']').val();
   
       
        var filter_name_id = $('input[name=\'filter_name_id\']').val();
        var filter_name = $('input[name=\'filter_name\']').val();

    if (filter_name_id) {
                if(filter_name!="")
                {
        url += '&filter_name_id=' + encodeURIComponent(filter_name_id);
                }
    }
       

    if (filter_name) {
        url += '&filter_name=' + encodeURIComponent(filter_name);
    }

       
    //location = url;
    window.open(url, '_blank');
});
//--></script>

<script type="text/javascript">
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
</script>
       <script type="text/javascript">
$('input[name=\'filter_name\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['product_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_name\']').val(item['label']);
                $('input[name=\'filter_name_id\']').val(item['value']);
	}
});
</script>
      </div>
<?php echo $footer; ?>