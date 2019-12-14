<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $purchase_chart_text; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if (isset($error_warning)) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if (isset($_SESSION['success_message'])) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
	<?php if (isset($_SESSION['delete_success_message'])) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $_SESSION['delete_success_message']; unset($_SESSION['delete_success_message']); ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
	<?php if (isset($_SESSION['update_success_message'])) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $_SESSION['update_success_message']; unset($_SESSION['update_success_message']); ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $purchase_chart_text; ?></h3>
      </div>
      <div class="panel-body">
	  <form action = "<?php echo $filter; ?>" method="post" enctype="multipart/form-data" id= "filter_form">
        <div class="well">
          <div class="row">
           <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-added"><?php echo $start_date_text; ?></label>
                <div class="input-group date">
                  <input type="text" onkeypress="return false;" value="<?php if(isset($date_start)){ echo $date_start; }?>" name="date_start" placeholder="<?php echo $start_date_text; ?>" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
					<span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span>
				</div>
              </div>
            </div>
			<div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-added"><?php echo $end_date_text; ?></label>
                <div class="input-group date">
                  <input type="text" onkeypress="return false;" value="<?php if(isset($date_end)){ echo $date_end; }?>" name="date_end" placeholder="<?php echo $end_date_text; ?>" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
					<span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span>
				</div>
              </div>
            </div>
			<div class="col-sm-12">
			  <button type="button" onclick="resetForm()" id="clear-filter" class="btn btn-primary pull-right"> <?php echo $button_clear; ?></button>
			  <button style="margin-right:10px;" type="submit" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo "Filter"; ?></button></div>
            </div>
          </div>
        </div>
		</form>
		</div>
    </div>
		<!--<div id="legendPlaceholder" ></div>
		<div id="flotcontainer" style="width:600px;height:400px;text-align:left; margin:auto;"></div>-->
		<div id="piechart" style="width: 600px; height: 400px;margin: 0 auto"></div>
	</div>
<!--<script src="http://static.pureexample.com/js/flot/excanvas.min.js"></script>
<script src="http://static.pureexample.com/js/flot/jquery.flot.min.js"></script>
<script src="http://static.pureexample.com/js/flot/jquery.flot.pie.min.js"></script>-->
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
 <script type="text/javascript">
/*$(function () {
	var data = [
		<?php
			foreach($chart_data as $data)
			{
		?>
			{label: "<?php echo $data['name']?>", data:<?php echo $data['purchase_quantity']; ?>},
		<?php } ?>
    ];

    var options = {
            series: {
                pie: {show: true,innerRadius:0.5,
				//label:{show: true}
				}
		    },
            legend: {
                show: false
            }
         };

    $.plot($("#flotcontainer"), data, options);  
});*/

$(function () {
    $('#piechart').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Purchase Comparison Chart'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            }
        },
        series: [{
            name: 'Brands',
            colorByPoint: true,
            data: [
			<?php
			foreach($chart_data as $data)
			{
		?>
			{
                name: '<?php echo $data['name']?>',
                y: <?php echo $data['purchase_quantity']; ?>
            },
			<?php } ?>
			]
        }]
    });
});

</script>

  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});

function resetForm()
{
	$('[name=date_start]').val('');
	$('[name=date_end]').val('');
}
//--></script></div>
<?php echo $footer; ?> 
