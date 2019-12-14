<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1>ASE Summary Report</h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>">ASE Summary Report</a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i>ASE Summary Count</h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-12">
         
             <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-customer">ASE</label>
                   <select name="user_id" id="user_id" class="form-control "  >
                    <option value=''>SELECT ASE</option> 
                 
                  <?php foreach ($listUserId as $listUser) { ?>
                   
                  <option value="<?php echo $listUser['user_id']; ?>" <?php if($listUser['user_id']==$filter_userid) { echo 'selected'; } ?>><?php echo strtoupper($listUser['firstname'].' '.$listUser['lastname']); ?></option>
                    
                  <?php } ?>
                </select>
              </div>
             </div>
                <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-date-start"><?php echo $entry_date_start; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
             </div>
              <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-date-end"><?php echo $entry_date_end; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              </div>
                <div class="col-sm-3" >
              <button type="button" id="button-filter" style="margin-top:10% " class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                  
                <td class="text-left">ASE name</td>
	 <td class="text-left">Total Booked Order</td>
                <td class="text-left">Order Pending</td>
                <td class="text-left">Order Completed</td>
                
                <td class="text-left">Customer Added</td>
                
                 
              </tr>
            </thead>
            <tbody>
              <?php foreach($returndata as $result){ ?>
              <tr>
                <td class="text-left"><?php echo $result['firstname']." ".$result['lastname']; ?></td>
	  <td class="text-left"><?php echo $result['totalconvertedorder']+$result['totalbookedorder']; ?></td>
                <td class="text-left"><?php echo $result['totalbookedorder']; ?></td>
                <td class="text-left"><?php echo $result['totalconvertedorder']; ?></td>
                <td class="text-left"><?php echo $result['totaladdedcustomer']; ?></td>
                
                <td class="text-left"></td>
              </tr>
              <?php } ?>        
            </tbody>
          </table>
        </div>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=ase/asereports/summary&token=<?php echo $token; ?>';
	
	var filter_userid = $('#user_id').val();
	
	if (filter_userid) {
		url += '&filter_userid=' + encodeURIComponent(filter_userid);
	}
	
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}

	location = url;
});
//--></script> 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?>