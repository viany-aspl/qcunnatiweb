<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid"> 
      <h1>Approve Form</h1>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i>Approve Form</h3>
       
      </div>
      <div class="panel-body">

	 <div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-end">Grower ID</label>
                <div class="input-group date">
                  <?php //echo $filter_store; //print_r($stores);//exit; ?>
                  <span class="input-group-btn">
                      
                  <input autocomplete="off" type="text" style="text-transform: uppercase; ;" value="<?php echo $filter_growerid; ?>" placeholder="GROWER ID" name="filter_growerid" id="filter_growerid" class="form-control " />
                  </span></div>
              </div>
              
            </div>
			<div class="col-sm-6">
 <div class="form-group">
 <label class="control-label"  for="input-date-end">Village</label>
 <div class="input-group date">

 <span class="input-group-btn">
<input type="text" id="filter_village" name="filter_village"   placeholder="VILLAGE" class="form-control" value="" />
 </span></div>
 </div>

 </div>
			<div class="col-sm-6">
 <div class="form-group">
 <label class="control-label" for="input-date-end">Select Company</label>
 <div class="input-group date">
 <?php //echo $filter_store; //print_r($stores);//exit; ?>
 <span class="input-group-btn">

 <select name="filter_company" id="input-company" class="form-control" onchange="clear_company(this.value);">
 <option selected="selected" value="">SELECT COMPANY</option>
 <?php foreach ($companys as $company) { //echo $store['store_id']; ?>
 <?php if ($company['company_id'] == $filter_company) {
 if($filter_company!=""){
 ?>
 <option value="<?php echo $company['company_id']; ?>" selected="selected"><?php echo $company['company_name']; ?></option>
 <?php }} else { ?>
 <option value="<?php echo $company['company_id']; ?>"><?php echo $company['company_name']; ?></option>
 <?php } ?>
 <?php } ?>
 </select>
 </span></div>
 </div>

 </div>
 <div class="col-sm-6">
 <div class="form-group">
 <label class="control-label" for="input-date-end">Select Unit</label>
 <div class="input-group date">
 <?php //echo $filter_store; //print_r($stores);//exit; ?>
 <span class="input-group-btn">

 <select name="filter_unit" id="input-unit" class="form-control">
 <option selected="selected" value="">SELECT UNIT</option>
 <?php 
 if(!empty($units2))
 {
 foreach($units2 as $dunit)
 {
 ?>
 <?php if ($dunit['unit_id'] == $filter_unit) {
 if($filter_unit!=""){
 ?>
 <option value="<?php echo $dunit['unit_id']; ?>" selected="selected"><?php echo $dunit['unit_name']; ?></option>
 <?php }} else { ?>
 <option value="<?php echo $dunit['unit_id']; ?>"><?php echo $dunit['unit_name']; ?></option>
 <?php } ?>
 
 
 <?php
 }
 }
 ?>
 </select>
 </span></div>
 </div>

 </div>
            <div class="col-sm-6">
                
              
              <button type="button" style="margin-top:23px;" id="button-filter" class="btn btn-primary pull-right "><i class="fa fa-search"></i> Filter</button>
            </div>
			<div class="col-sm-6">
			<button type="button" style="margin-top:23px;" id="button-filter656" onclick="return updataApproveStatusAll();" class="btn btn-primary pull-right">Approve All</button>
			<img id="cr_img" src="view/image/processing_image.gif" style="float: right;height: 60px;display : none;"/>
            </div>
          </div>
        </div>


        <div class="widget">
            
            <div class="widget-body">
            <div class="table-responsive">
            <table class="table table-bordered">
            <thead >
              <tr>
                <td class="text-left">Sno</td>
                <td class="text-left">Unit</td>
				<td class="text-left">Village</td>
                <td class="text-left">Grower ID</td>
                <td class="text-left">Grower Name</td>
                <td class="text-left">Card Serial No</td>
                <td class="text-left">Action</td>
                <td class="text-right" style="width: 10px;" class="text-center">
                    <input id="checkall" type="checkbox" name="checkall"   />
                </td>
               
                
              </tr>
            </thead>
            <tbody id="checkboxes">
              <?php $total=0; if ($orders) {  if($_GET["page"]=="") {$aa=1;} else if($_GET["page"]=="1") {$aa=1;}
              else{ $aa=(($_GET["page"]-1)*20)+1; } ?>
              <?php foreach ($orders as $order) { //print_r($order); ?>
              <tr>
                <td class="text-left"><?php echo $aa; ?></td>
                <td class="text-left"><?php echo $order['unit_name']; ?></td>
				<td class="text-left"><?php echo $order['VILLAGE_NAME']; ?></td>
                <td class="text-left"><?php echo $order['GROWER_ID']; ?></td>
                <td class="text-left"><?php echo $order['GROWER_NAME']; ?></td>
                <td class="text-left"><?php echo $order['CARD_SERIAL_NUMBER']; ?></td>
                <td class="text-left">
				<button  id="issuedbtn<?php echo $order['GROWER_ID']; ?>"  type="button"  class="btn btn-primary " onclick="updataApproveStatus('<?php echo $order['GROWER_ID']; ?>','<?php echo $order['UNIT_ID']; ?>','<?php echo $order['CARD_SERIAL_NUMBER']; ?>')">Approved</button>   
				<img id="cr_img<?php echo $order['GROWER_ID']; ?>" src="view/image/processing_image.gif" style="float: left;height: 60px;display : none;"/>
				</td>
                <td style="width: 10px;" class="text-center"><input value="<?php echo $order['GROWER_ID']; ?>" type="checkbox" name="checkbox_grower[]" /></td>
              </tr>
            <?php $total=$total+$order['total']; $aa++; } ?>
              <?php } ?>
            </tbody>
          </table>
        </div>
                                      <!---  </div>
                                        <p class="widget-caption mt10">* Display all Card which are un-verified</p>
                                    </div>
                                </div>---->
       <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right">
           <!--<span style="font-weight: bold;">Total Amount : <?php echo $total; ?></span> <br/>-->

         <?php echo $results; ?></div>
        </div>
                    </div>

                    
                </div></div></div>
                                    
                                    
                <!-- /Page Body -->
<script type="text/javascript"> 
      $('#checkall').click(function() {//alert("xdjs");
    var checked = $(this).prop('checked');
    $('#checkboxes').find('input:checkbox').prop('checked', checked);
  });  
   function updataApproveStatusAll()                    
 {
     
   var data = $('input[name="checkbox_grower[]"]').serialize();
//alert(data);
  console.log(data);
  if(data!="")
  {
  $.ajax({
            url: 'index.php?route=farmerrequest/approveform/approveAll&token=<?php echo $token; ?>',
            //dataType: 'json',
            data:data,
		beforeSend:function()
			{
			$("#button-filter656").hide();
			
			$("#cr_img").show();
			
			},
            success: function(json) {
               // alert(json);
              //  return false;
              location.reload();
            },
            error: function(json)
            {
                alertify.error(json);
				$("#button-filter656").show();
			
				$("#cr_img").hide();
                return false;
            }
        });
		}
		else
		{
		alertify.error('Please Select atleast 1 Card');
		}
   return false;
 }  
  
 
function clear_company(data) {
//alert(data);
 $('#button-download').hide();
//document.getElementById('button-download').style.display=none;

 var companyid=data;
 $.ajax({ 
 type: 'post',
 url: 'index.php?route=farmerrequest/approveform/getUnitbyCompany&token='+getURLVar('token'),
 data: 'companyid='+companyid,
 //dataType: 'json',
 cache: false,

success: function(data) {

//alert(data);
 $("#input-unit").html(data);
 }
 });
 }
 
  
  
function updataApproveStatus(gid,unitid,cardid)
{
   
    
 //alert(gid);
      $.ajax({
		url: 'index.php?route=farmerrequest/approveform/approvedata&token=<?php echo $token; ?>&gid='+gid+'&unitid='+unitid+'&cardid='+cardid,
		dataType: 'json',
		beforeSend:function()
			{
			$("#issuedbtn"+gid).hide();
			
			$("#cr_img"+gid).show();
			
			},		
		success: function(json) {
                 //alert(JSON.stringify(json));
                  
              location.reload();
		
	        },
                error:function (json){
                    alertify.error(JSON.stringify( json));
					$("#issuedbtn"+gid).show();
			
					$("#cr_img"+gid).hide();
                }
                
	});
	return false;
 }
 </script>  
           <script type="text/javascript">
                     //alert('here');
$('input[name=\'filter_growerid\']').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=farmerrequest/farmerrequest/autocomplete&token=<?php echo $token; ?>&filter_growerid=' +  encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        label: item['GROWER_ID']+"<br/><small style='font-size:8px;font-style: italic;'>"+item['GROWER_NAME']+"</small>",
                        value: item['GROWER_ID']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        $('input[name=\'filter_growerid\']').val(item['value']);
                //$('input[name=\'filter_name_id\']').val(item['value']);
    }
});
</script>
 <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=farmerrequest/approveform&token=<?php echo $token; ?>';
	
        	var filter_growerid = $('#filter_growerid').val();
	
	if (filter_growerid!="") {
		url += '&filter_growerid=' + encodeURIComponent(filter_growerid);
	}
 var filter_unit = $('select[name=\'filter_unit\']').val();
 if (filter_unit!="") {
 url += '&filter_unit=' + encodeURIComponent(filter_unit);
 }
 var filter_company = $('select[name=\'filter_company\']').val();

 if (filter_company!="") {
 url += '&filter_company=' + encodeURIComponent(filter_company);
 }
    var filter_village = $('input[name=\'filter_village\']').val();

if (filter_village!="") {
//alert(filter_village);
url += '&filter_village=' + encodeURIComponent(filter_village);
}
	
       
	location = url;
});
//--></script> 
      
<?php echo $footer; ?>