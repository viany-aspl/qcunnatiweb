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
         <button type="button" id="button-download" class="btn btn-primary pull-right" style="margin-top: -8px;"> Download</button>

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

              
              
            </div>
            <div class="col-sm-6">

             <div class="form-group">
                <label class="control-label" for="input-date-end">Product Sub Category</label>
               
                     
                  <select name="filter_category"  id="input-category" style="width: 100%;" class="select2 form-control">
                      <option value="">SELECT</option>
                  <?php foreach ($sub_categories as $sub_category) { ?>
                  <?php if ($sub_category['sid'] == $filter_category) { ?>
                  <option value="<?php echo $sub_category['sid']; ?>" selected="selected"><?php echo $sub_category['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $sub_category['sid']; ?>"><?php echo $sub_category['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                 
              </div>
              <div class="form-group">
                <label class="control-label" for="input-date-end">Store</label>
               
                     
                  <select name="filter_store"  id="input-store" style="width: 100%;" class="select2 form-control">
                      <option value="">SELECT</option>
                  <?php foreach ($stores as $store) { ?>
                  <?php if ($store['store_id'] == $filter_store) { ?>
                  <option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                 
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <div class="table-responsive">
	<span style="font-weight: bold;">
                 
                  Total  : <?php echo number_format((float)$total_amount_all, 2, '.', ''); ?> 
              </span> 
                           
                           <br/><br/>
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left">Store Name</td>
                <td class="text-left">Product Name</td>
                <!--<td class="text-left">Product ID</td>-->
                <td class="text-left">Sub Category</td>
                <td class="text-right">Sale Quantity</td>
                <td class="text-right">Total</td>
		

              </tr>
            </thead>
            <tbody>
              <?php if ($products) { ?>
              <?php foreach ($products as $product) { ?> 
              <tr>
                <td class="text-left"><?php echo $product['store_name']; ?></td>
                <td class="text-left"><?php echo $product['name']; ?></td>
                <!--<td class="text-left"><?php echo $product['product_id']; ?></td>-->
                <td class="text-left"><?php echo $product['sub_cat_name']; ?></td>
                <td class="text-right"><?php echo $product['quantity']; ?></td>
                <td class="text-right"><?php echo $product['total'] ; ?></td>
              

              </tr>
              <?php } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right">
	<span style="font-weight: bold;">
                 Page Total ::  Total  : <?php echo number_format((float)$total_amount, 2, '.', ''); ?>  
              </span><br/>
	<?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript">
$("#input-store").select2();
$("#input-category").select2();
<!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=report/product_cat_sales&token=<?php echo $token; ?>';
	
	
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
	var filter_category = $('select[name=\'filter_category\']').val();
	
	if (filter_category) {
		url += '&filter_category=' + encodeURIComponent(filter_category);
	}	

	location = url;
});
//--></script> 

 <script type="text/javascript"><!--
$('#button-download').on('click', function() {
	url = 'index.php?route=report/product_cat_sales/download_excel&token=<?php echo $token; ?>';
	
	
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
	var filter_category = $('select[name=\'filter_category\']').val();
	
	if (filter_category) {
		url += '&filter_category=' + encodeURIComponent(filter_category);
	}
	//location = url;
        window.open(url, '_blank');
});
//--></script> 

  <script type="text/javascript">
/*
$('.date').datetimepicker({
	pickTime: false
});
*/
</script> 
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