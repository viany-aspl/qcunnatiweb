<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo "FM Wise Order Count"; ?></h1>
      <ul class="breadcrumb">
       
      </ul>
    </div>
  </div>
  <div class="container-fluid">
<?php if ($success) {  ?>
    <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>

<?php if ($error) {  ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>

    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i> <?php echo "FM Wise Order Count"; ?></h3>
		 
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-store">Select Store</label>
                
                  <?php //echo $filter_store; print_r($stores);//exit; ?>
              
                  <select name="filter_store" id="input-store" class="form-control" style="width:100%;" onchange="clear_store(this.value)" >
				  <option value="">Select Store</option>
                  <?php foreach ($stores as $store) { ?>
                  <?php if ($store['store_id'] == $filter_store) { ?>
                  <option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                 
              
        </div>
			 <div class="form-group">
			 <label class="control-label" for="filter_porder_id">FM Name:</label> <?php echo $filter_fm_name; ?>
					<select name="filter_fm_name" id="input-fm" style="width:100%;"  class="form-control"  >
						<option value="0">Select FM</option>
						 <?php if(!empty($dfm)){ 
				  foreach($dfm as $dfm2)
				  { ?>
				  <option <?php if(trim($dfm2['FM_CODE'])==$filter_fm_name){ ?> selected="selected" <?php } ?> value="<?php echo trim($dfm2['FM_CODE']); ?>"><?php echo $dfm2['FM_NAME']; ?></option>
				  <?php }
				   } ?>
					</select>
			 </div>
 
<div class="form-group">
                <label class="control-label" for="input-date-start">End Date</label>
                <div class="input-group date" id="date_from">
                  <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="Date" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
 
 
            </div> 
			<div class="col-sm-6">
			
            <div class="form-group">
                <label class="control-label" for="input-units">Select Unit</label>
				<select name="filter_unit" id="input-unit" class="form-control" onchange="clear_unit(this.value)">
                  <option value="0">Select Unit</option>
				  <?php if(!empty($dunit)){ 
				  foreach($dunit as $dunit2)
				  { ?>
				  <option <?php if($dunit2['unit_id']==$filter_unit){ ?> selected="selected" <?php } ?> value="<?php echo $dunit2['unit_id']; ?>"><?php echo $dunit2['unit_name']; ?></option>
				  <?php }
				   } ?>
                </select>
            </div>
 
			
                <div class="form-group">
                <label class="control-label" for="input-date-start">Start Date</label>
                <div class="input-group date" id="date_from">
                  <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="Date" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
                            <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
              
              
           
              
              
                 
              
			
             
          </div>
        </div>
        <div class="table-responsive">
	<!---<span style="font-weight: bold;">Total Amount : <?php echo number_format((float)$total_tagged_amount_All, 2, '.', ''); ?></span> --->
                           
                           <br/><br/>
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-right">Store Name</td>
                <td class="text-right">Store ID</td>	
   	            <td  class="text-right">Indent Count</td> 
                <td class="text-right">Order Type</td>
                <td class="text-right">Fm Code</td>
					<td class="text-right">Fm Name</td>
				</tr>           
			  </thead>
            <tbody>
              <?php if ($orders) { if($_GET["page"]=="") {$aa=1;} else if($_GET["page"]=="1") {$aa=1;}
              else{ $aa=(($_GET["page"]-1)*20)+1; } ?>
              <?php foreach ($orders as $order) { //print_r($order); ?>
              <tr>
                
					<td class="text-right"><?php echo $order['store_name']; ?></td>
					<td class="text-right"><?php echo $order['store_id']; ?></td>
                
					<td class="text-right"><?php echo $order['indent_no']; ?></td>
					<td class="text-right"><?php echo $order['ordertype']; ?></td>
					<td class="text-right"><?php echo $order['fmcode']; ?></td>
					<td class="text-right"><?php echo $order['fmname']; ?></td>
				 
              </tr>              <?php $aa++; } ?>
              <?php  } else { ?>
              <tr>
                <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right">
		
		<?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>






  <script type="text/javascript">
$("#input-store").select2();
$("#input-fm").select2();



$('#button-filter').on('click', function() {

	var filter_fm_code = $('select[name=\'filter_fm_name\']').val();
	var filter_store = $('select[name=\'filter_store\']').val();
	var filter_unit = $('select[name=\'filter_unit\']').val();
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	//alert(filter_fmname);
	
	if(!filter_store)
	{
	  alertify.error('Please Select Store');
	  return false;
	}
	if(!filter_unit)
	{
	  alertify.error('Please Select Unit');
	  return false;
	}
	if(!filter_fm_code)
	{
	  alertify.error('Please Select FM Name');
	  return false;
	}
	
	
	url = 'index.php?route=reportbcml/fmordercount&token=<?php echo $token; ?>';
	
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	if (filter_fm_code != 0) {
		url += '&filter_fm_code=' + encodeURIComponent(filter_fm_code);
	}
		
	
	
	if (filter_unit != '') {
		url += '&filter_unit=' + encodeURIComponent(filter_unit);
	}	

	
	if (filter_store != '0') {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}
alert(url);
	location = url;
});


function clear_store(data) {
//alert(data);
var storeid=data;
$.ajax({ 
type: 'post',
url: 'index.php?route=tagpos/fmdelivery/getUnitbyStore&token='+getURLVar('token'),
data: 'storeid='+storeid,
//dataType: 'json',
cache: false,

success: function(data) {

//alert(data);
$("#input-unit").html(data);
}
});
} 
function clear_unit(data) {
//alert(data);
var unitid=data;
$.ajax({ 
type: 'post',
url: 'index.php?route=tagpos/fmdelivery/getfm&token='+getURLVar('token'),
data: 'unitid='+unitid,
//dataType: 'json',
cache: false,

success: function(data) {

//alert(data);
$("#input-fm").html(data);
}
});
} 


function get_unit(store_id)
{ //alert(store_id);
url = 'index.php?route=report/reconciliation/get_store_unit&token=<?php echo $token; ?>&store_id='+store_id;
$.ajax({
              url: url,
              // dataType: 'json',
               success: function(json) 
               {
                           //alert(json);	
		$("#input-unit_2").html(json);  
               }
                       
              });

}
</script> 
 
        

  <script type="text/javascript">

$('.date').datetimepicker({
	pickTime: false,
	maxDate: new Date()
});

</script></div>
<?php echo $footer; ?>