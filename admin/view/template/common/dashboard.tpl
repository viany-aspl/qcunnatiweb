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
	  <div id="daterange" class="selectbox pull-right" style="cursor: pointer;">
			<i class="fa fa-calendar"></i>
			<span><?php echo date('M d, Y',strtotime($start_date)); ?> - <?php echo date('M d, Y',strtotime($end_date)); ?></span> <b class="caret"></b>
		</div>
    </div>
  </div>
  <script type="text/javascript" src="view/javascript/moment.min.js"></script>
  <script type="text/javascript" src="view/javascript/daterangepicker.js"></script>

    <link rel="stylesheet" type="text/css" href="view/stylesheet/daterangepicker.css" />


  <div class="container-fluid">
    <?php if ($error_install) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_install; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="row">
      <div class="col-lg-4 col-md-3 col-sm-6"><?php echo $order; ?></div>
      <div class="col-lg-4 col-md-3 col-sm-6"><?php echo $sale; ?></div>
      <div class="col-lg-4 col-md-3 col-sm-6"><?php echo $customer; ?></div>
      <!--<div class="col-lg-3 col-md-3 col-sm-6" style='display:none;' ><?php echo $online; ?></div>-->
    </div>
    <div <?php if($group!="1") { echo "style='display:none;'"; } ?>  class="row">
      <div class="col-lg-6 col-md-12 col-sx-12 col-sm-12" style="display: none;"><?php echo $map; ?></div>
      <div class="col-lg-12 col-md-12 col-sx-12 col-sm-12"><?php echo $chart; ?></div>
    </div>
    <div class="row">

      <div <?php  if($group!="1") { echo "style='display:none;'"; } ?>  class="col-lg-6 col-md-12 col-sm-12 col-sx-12"><?php echo $activity; ?></div>
      <div class="col-lg-6 col-md-12 col-sm-12 col-sx-12"> <?php echo $recent; ?> </div>
    </div>
  </div>
</div>
<script type="text/javascript">
var start = new Date('<?php echo $start_date; ?>');
var end = new Date('<?php echo $end_date; ?>');
var till='<?php echo date('M d, Y',strtotime('2016-01-01'));//echo date('D M Y',strtotime('2017-01-01')).' 00:00:00 GMT+0530'; ?>';
//alert(till);
//$('#daterange span').html(start.toLocalDateString('MMMM D, YYYY') + ' - ' + end.toLocalDateString('MMMM D, YYYY'));
  
	$('#daterange').daterangepicker(
	{startDate: start,
        endDate: end,
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
		   'Till Date':[till, moment()],
},
    opens: 'left'
  }, function(start, end, label) 
  {
	   
	url = 'index.php?route=common/dashboard&a=1&token=<?php echo $token; ?>';
	url += '&start_date=' + encodeURIComponent(start.format('YYYY-MM-DD'));
    url += '&end_date=' + encodeURIComponent(end.format('YYYY-MM-DD'));
    
	location = url;
    //console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
  }
	);

</script>
<script type="text/javascript">

$('#button-filter').on('click', function() {
    url = 'index.php?route=common/dashboard&token=<?php echo $token; ?>';
   
    var filter_date_start = $('input[name=\'filter_date_start\']').val();
   
    if (filter_date_start) {
        url += '&start_date=' + encodeURIComponent(filter_date_start);
    }

    var filter_date_end = $('input[name=\'filter_date_end\']').val();
   
    if (filter_date_end) {
        url += '&end_date=' + encodeURIComponent(filter_date_end);
    }
	location = url;
});

    var maxDate =  new Date();
    //var minDate = new Date(maxDate.getFullYear(), maxDate.getMonth(), +1); //one day next before month
  $("#date_from").datetimepicker({
  timepicker: false,
  pickTime: false,
  maxDate: maxDate,
  closeOnDateSelect: true
}).on('dp.change', function (ev) {
   
   //change_to_date();
});
$("#date_to").datetimepicker({
  showClear: true,
  timepicker: false,
  pickTime: false,
  
  maxDate: maxDate,
  closeOnDateSelect: true
}).on('dp.change', function (ev) {
   
   //change_to_equal_date();
});
</script>

<?php echo $footer; ?>