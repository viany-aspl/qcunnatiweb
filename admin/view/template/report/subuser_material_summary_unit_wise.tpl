<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo "Sub User Material Summary (Unit Wise)"; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo "Sub User Material Summary (Unit Wise)"; ?>"><?php "Material Summary"; ?></a></li>
        <?php } ?>
      </ul>


    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i> <?php echo "Material Summary List"; ?></h3>
       <button type="button" id="button-download" class="btn btn-primary pull-right" style="margin-top: -8px !important; margin-right:10px !important">
            Download</button>
			
      </div>

      <div class="panel-body">
        <div class="well">
          <div class="row">
           <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-start"><?php echo $entry_date_start; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              
            </div>
			<div class="col-sm-6">
               
              <div class="form-group">
                <label class="control-label" >Select Unit</label>
              
                      
                  <select name="filter_unit" id="filter_unit" style="width:100%" class="select2 form-control">
                   <option selected="selected" value="">SELECT UNIT</option>
					<?php foreach($getuser as $user){ ?>
						<option value="<?php echo $user['user_id']; ?>" <?php if($filter_user==$user['user_id']){ ?> selected="selected" <?php } ?> ><?php echo $user['firstname']."  ".$user['lastname']; ?></option>
					<?php } ?>
                                  
                </select>
             
              </div>
              </div>
			
			
			</div>
			<div class="row">
			
			
			<div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-name">Product Name</label>
                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
                <input type="hidden" name="filter_name_id"  value="<?php echo $filter_name_id; ?>" id="filter_name_id"/>
              </div>
              
            </div>
			
			<div class="col-sm-6" style="display: none;">
              <div class="form-group">
                <label class="control-label" for="input-date-end"><?php echo $entry_date_end; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              
            </div>
			
            <div class="col-sm-6">
			  <button type="button" id="button-filter" class="btn btn-primary pull-right">
			<i class="fa fa-search"></i> <?php echo $button_filter; ?>
			</button>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left">S NO.</td>
                <td class="text-left">Unit Name</td>  
				 
                <td class="text-left">Product Name</td>
				  <td class="text-left">Material Issued</td>
                <td class="text-left">Material Billed</td>
				<td class="text-left">Balance Qty</td>
               
	
              </tr>
            </thead>
            <tbody>
              <?php if ($orders) { $total=0; $a=1;?>
			  
              <?php foreach ($orders as $order) {  //print_r($order); ?>
              <tr>
                <td class="text-left"><?php echo $a; ?></td>
                <td class="text-left"><?php echo $order['unitname']; ?></td>
				
				<td class="text-left"><?php echo $order['name']; ?></td>
              
                <td class="text-left"><?php echo $order['ms']; ?></td>
                <td class="text-left"><?php echo $order['billed']; ?></td>
				<td class="text-left"><?php echo $order['bal']; ?></td>
                
              </tr>
              <?php $total=$total+$order['amount'];
              
              $a++;} ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="7"><?php echo $text_no_results; ?></td>
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
  <script type="text/javascript">
  $("#filter_user").select2();
  $("#filter_store").select2();
  $('.date').datetimepicker({
	pickTime: false
});
$('#button-filter').on('click', function() {
	url = 'index.php?route=report/subuser/material_summary_unit_wise&token=<?php echo $token; ?>';
	
	var filter_unit = $('#filter_unit').val();
	var filter_name_id = $('input[name=\'filter_name_id\']').val();
    var filter_name = $('input[name=\'filter_name\']').val();
	
	if (filter_unit) {
		url += '&filter_unit=' + encodeURIComponent(filter_unit);
	}	
    
    if((filter_unit=="")  && (filter_name_id==""))
	{
	alertify.error("Please Select Unit OR Product");
	return false;
	}
	
    if (filter_name_id) 
	{
		if(filter_name!="")
        {
			url += '&filter_name_id=' + encodeURIComponent(filter_name_id);
        }
    }
    if (filter_name) 
	{
        url += '&filter_name=' + encodeURIComponent(filter_name);
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
 
</script> 
<script type="text/javascript">
$('#button-download').on('click', function() {
    url = 'index.php?route=report/subuser/download_excel_material_summary_unit_wise&token=<?php echo $token; ?>';
   var filter_unit = $('#filter_unit').val();
	var filter_name_id = $('input[name=\'filter_name_id\']').val();
    var filter_name = $('input[name=\'filter_name\']').val();
	
	if (filter_unit) {
		url += '&filter_unit=' + encodeURIComponent(filter_unit);
	}	
    
    if((filter_unit=="")  && (filter_name_id==""))
	{
	alertify.error("Please Select Unit OR Product");
	return false;
	}
	

    if (filter_name_id) 
	{
		if(filter_name!="")
        {
			url += '&filter_name_id=' + encodeURIComponent(filter_name_id);
        }
    }
    if (filter_name) 
	{
        url += '&filter_name=' + encodeURIComponent(filter_name);
    }
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
		
    //location = url;
        window.open(url, '_blank');
});
</script> 

 <script type="text/javascript">
$('input[name=\'filter_name\']').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=report/subuser/product_autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
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
  <script type="text/javascript">
  
  <!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?>