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
                <label class="control-label" for="input-date-start">Date</label>
                <div class="input-group date" id="date_from">
                  <input type="text" name="filter_date"  value="<?php echo $filter_date; ?>" placeholder="Date" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              
            </div>
            <div class="col-sm-6">
              
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
                <td class="text-center" colspan="2">To Date (Jan, 01 &nbsp; <?php echo date('Y'); ?> till Date)</td> 
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
	url = 'index.php?route=reportbcml/report&token=<?php echo $token; ?>';
	
	var filter_date = $('input[name=\'filter_date\']').val();
	
	if (filter_date) { 
		url += '&filter_date=' + encodeURIComponent(filter_date);
	}

	location = url;
});
//--></script> 
<script type="text/javascript">
$('#button-download').on('click', function() {
    url = 'index.php?route=reportbcml/report/download_bcml_sales_report&token=<?php echo $token; ?>';
   
    var filter_date = $('input[name=\'filter_date\']').val();
   
    if (filter_date) {
        url += '&filter_date=' + encodeURIComponent(filter_date);
    }

    //location = url;
    window.open(url, '_blank');
});
//--></script>

  <script type="text/javascript">

$('.date').datetimepicker({
	pickTime: false
});

</script>
      
      </div>
<?php echo $footer; ?>