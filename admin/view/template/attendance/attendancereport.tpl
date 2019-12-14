<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1>Attendance Report</h1>
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
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i> </h3>
        <button type="button" id="button-download" class="btn btn-primary pull-right" style="margin-top: -8px;"> Download</button>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-name">User Name <div style="font-size: 10px;">(First name Only)</div></label>
                <input type="text" name="filter_username" value="<?php echo $filter_username; ?>" placeholder="User Name" id="input-name" class="form-control" />
                <input type="hidden" name="filter_userid"  value="<?php echo $filter_userid; ?>" id="filter_userid"/>
              </div>
			  
               <div class="form-group">
                <label class="control-label" for="input-date-start">End Date</label>
                <div class="input-group date">
                  <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="End Date" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div> 
            </div>
            <div class="col-sm-6">
              
              <div class="form-group">
                <label class="control-label" for="input-date-start">Start Date<br/><br/></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="Start Date" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left">USER NAME</td>
                <!--<td class="text-left">USER ID</td>-->
				<td class="text-left">STORE NAME </td>
                <td class="text-left">IN TIME</td>
                <td class="text-left">OUT TIME</td>
	 <td class="text-left">IN LOCATION</td>
	 <td class="text-left">OUT LOCATION</td>
                <!--<td class="text-left">View ON MAP</td>-->
                 
                
              </tr>
            </thead>
            <tbody>
              <?php if ($activities) {  ?>
              <?php foreach ($activities as $order) { ?>
              <tr>
                
				<td class="text-left"><?php echo $order['username']; ?></td>
                <!--<td class="text-left"><?php echo $order['user_id']; ?></td>-->
                <td class="text-left"><?php echo $order['store_name']; ?></td>
                <td class="text-left"><?php echo $order['in_time']; ?></td>
                <td class="text-left"><?php echo $order['out_time']; ?></td>
	  <td class="text-left"><?php echo $order['location_in']; ?></td>
	  <td class="text-left"><?php echo $order['location_out']; ?></td>
                <!--<td class="text-left"><div style="max-width: 200px;">View</div></td>-->
              </tr>
              <?php 
              $aa++;
              } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="5"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"> 
             
              <?php echo $results; ?>  </div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=attendance/attendance&token=<?php echo $token; ?>';
	
    var filter_userid = $('#filter_userid').val();
	
	if (filter_userid!="") {
		url += '&filter_userid=' + encodeURIComponent(filter_userid);
	}
	var filter_username = $('#input-name').val();
	
	if (filter_username!="") {
		url += '&filter_username=' + encodeURIComponent(filter_username);
	}
	var filter_date_start = $('#input-date-start').val();
	
	if (filter_date_start!="") {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}
	var filter_date_end = $('#input-date-end').val();
	
	if (filter_date_end!="") {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
       //alert(url);
	location = url;
});
//--></script> 
<script type="text/javascript"><!--
$('#button-download').on('click', function() {
    url = 'index.php?route=attendance/attendance/download_report&token=<?php echo $token; ?>';
	
    var filter_userid = $('#filter_userid').val();
	
	if (filter_userid!="") {
		url += '&filter_userid=' + encodeURIComponent(filter_userid);
	}
	var filter_username = $('#input-name').val();
	
	if (filter_username!="") {
		url += '&filter_username=' + encodeURIComponent(filter_username);
	}
	var filter_date_start = $('#input-date-start').val();
	
	if (filter_date_start!="") {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}
	var filter_date_end = $('#input-date-end').val();
	
	if (filter_date_end!="") {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
    
       //alert(url);
    //location = url;
       window.open(url, '_blank');
});
//-->

</script> 

<script type="text/javascript">
$('input[name=\'filter_username\']').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=attendance/attendance/autocomplete&token=<?php echo $token; ?>&filter_username=' +  encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        label: item['name'],
                        value: item['user_id']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        $('input[name=\'filter_username\']').val(item['label']);
                $('input[name=\'filter_userid\']').val(item['value']);
    }
});
</script>

  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?>