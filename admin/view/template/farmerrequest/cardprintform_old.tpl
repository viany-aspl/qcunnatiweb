<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid"> 
      <h1> Card Print Form</h1>
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
        <h3 class="panel-title"><i class="fa fa-list"></i> Card Print Form</h3>
       
      </div>
      <div class="panel-body">

	 <div class="well">
          <div class="row">
		  <div class="col-sm-6">
<div class="form-group">
<label class="control-label" for="input-date-start">Create Date From</label>
<div class="input-group date">
<input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
<span class="input-group-btn">
<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
</span></div>
</div>
</div>
<div class="col-sm-6">
<div class="form-group">
<label class="control-label" for="input-date-end">Create Date To</label>
<div class="input-group date">
<input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
<span class="input-group-btn">
<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
</span></div>
</div>
</div>

			
<div class="col-sm-6">
 <div class="form-group">
 <label class="control-label" for="input-date-end">Company</label>
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
 <label class="control-label" for="input-date-end">Unit</label>
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
 <div class="form-group">
 <label class="control-label"  for="input-date-end">Zone</label>
 <div class="input-group date">

 <span class="input-group-btn">
<input type="text" id="filter_zone" name="filter_zone"  class="form-control" value="" />
 </span></div>
 </div>

 </div>
 		<div class="col-sm-6">
 <div class="form-group">
 <label class="control-label" for="input-date-end">Circle</label>
 <div class="input-group date">

 <span class="input-group-btn">
 <input type="text" id="filter_circle" name="filter_circle" class="form-control" value="" />
 </span></div>
 </div>

 </div>
 
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
 <label class="control-label" for="input-date-end">Association</label>
 <div class="input-group date">
 
 <span class="input-group-btn">

 <select name="filter_asoc" id="input-asoc" class="form-control">
 <option selected="selected" value="">SELECT ASSOCIATION</option>
 <option  value="mosiac">MOSIAC</option>
 <option  value="willowood">WILLOWOOD</option>
 </select>
 </span></div>
 </div>

 </div>
 <div class="col-sm-6 ">
 </div>

            <div class="col-sm-6 ">
                
              
              <button type="button" style="margin-top:23px;" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> Filter</button>
			  <?php
			  $ocunt=COUNT($orders);
			  if($ocunt>0)
			  {
			  ?>
             <button type="button" style="margin-top:23px; margin-right:20px; "  id="button-download" class="btn btn-primary pull-right"  >
            Print All</button>
			<?php
			}
			?>
            </div>
          </div>
        </div>

        <div class="col-md-12" >                                   
            <div class="widget-body">
            <div class="table-responsive">
            <table class="table table-bordered">
            <thead>
              <tr>
			  
				

                <td class="text-left">Sno</td>
                <td class="text-left">Village</td>
				
                <td class="text-left">Grower ID</td>
                <td class="text-left">Grower Name</td>
                <td class="text-left">Card Number</td>
                <td class="text-left">View</td>
				<td class="text-left"><input <?php if($_SESSION['all_selected']=='true'){ echo 'checked="checked"'; } ?>
				title="Click here to Select/Un-Select All Bills" type="checkbox" id="check_box_all" onclick="return add_all_to_array();" name="checkd_bill_all" /> 
				</td>
               
                
              </tr>
            </thead>
            <tbody id="checkboxes">
			<?php //print_r($orders); ?>
			 <strong>Company :  </strong><?php echo $orders[0]['COMPANY_NAME']; ?>&nbsp;&nbsp;&nbsp;&nbsp;<strong>Unit :  </strong><?php echo $orders[0]['UNIT_ID']; ?>
              <?php $total=0; if ($orders) {  if($_GET["page"]=="") {$aa=1;} else if($_GET["page"]=="1") {$aa=1;}
              else{ $aa=(($_GET["page"]-1)*20)+1; } ?>
              <?php foreach ($orders as $order) { //print_r($order); ?>
              <tr>
			  <td class="text-left"><?php echo $aa; ?></td>
                <td class="text-left"><?php echo $order['VILLAGE_NAME']; ?></td>
				
                <td class="text-left"><?php echo $order['GROWER_ID']; ?></td>
                <td class="text-left"><?php echo $order['GROWER_NAME']; ?></td>
                <td class="text-left"><?php echo $order['CARD_SERIAL_NUMBER']; ?></td>
                <td class="text-left">
                    <button  id="viewbtn"  type="button" data-toggle="modal" data-target="#myModal"  class="btn btn-primary " 
                             onclick="cardview('<?php echo $order['GROWER_ID']; ?>','<?php echo $order['CARD_SERIAL_NUMBER']; ?>','<?php echo $order['GROWER_NAME']; ?>','<?php echo $order['FTH_HUS_NAME']; ?>',' <?php echo $order['VILLAGE']; ?>','<?php echo $order['UNIT_ID']; ?>','<?php echo $order['CARD_QR_IMG']; ?>','<?php echo $order['CNAME']; ?>')">
                     
                        View
                    </button> 
					<span id="span_<?php echo $order['GROWER_ID']; ?>" style="display: none;">
					 <button  id="button_pdf"  type="button" style="margin-right:20px; margin-bottom:5px" data-toggle="modal" data-target="#myModal"  class="btn btn-primary " 
                             onclick="download_pdf('<?php echo $order['GROWER_ID']; ?>','<?php echo $order['CARD_SERIAL_NUMBER']; ?>','<?php echo $order['GROWER_NAME']; ?>','<?php echo $order['FTH_HUS_NAME']; ?>',' <?php echo $order['VILLAGE']; ?>','<?php echo $order['UNIT_ID']; ?>','<?php echo $order['CARD_QR_IMG']; ?>','<?php echo $order['CNAME']; ?>')">
                     
                        Print
                    </button> 
					</span>
                    <!----<button  id="issuedbtn"  type="button" class="btn btn-primary " onclick="updataPrintStatus(<?php echo $order['GROWER_ID']; ?>,<?php echo $order['UNIT_ID']; ?>)">Print</button>   ---->
                </td>
				<td class="text-left"><input 
<?php if($order['selected']=='true'){ echo 'checked="checked"'; } ?>

type="checkbox" id="check_box_<?php echo $order['GROWER_ID']; ?>" onclick="return add_to_array('<?php echo $order['GROWER_ID']; ?>','check_box_<?php echo $order['GROWER_ID']; ?>');" name="checkd_bill" /></td>
                
                
              </tr>
            <?php $total=$total+$order['total']; $aa++; } ?>
              <?php } ?>
            </tbody>
          </table>
        </div>
                                      <!---  </div>
                                        <p class="widget-caption mt10">* Display all Card which are un-verified</p>
                                    </div>---->
                                </div>
       <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right">
           <!--<span style="font-weight: bold;">Total Amount : <?php echo $total; ?></span> <br/>-->

         <?php echo $results; ?></div>
        </div>
                    </div>

                    
                </div></div></div>
            <link href="view/javascript/ca_Style.css" rel="stylesheet" /> 
 <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
 <link href="https://fonts.googleapis.com/css?family=Jura" rel="stylesheet">
                     
 <div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog" >

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="height:60px;">
	    <!--<span id="btn_html"></span>-->
       <!--- <button type="button" class="close pull-right" data-dismiss="modal">&times;</button>--->
        
      </div>
      <div class="modal-body" id="cmd">
     
<div class="centerbox">
 <?php //print_r($data); ?>
 <div class="grey" style="height:100%">

 <div class="dalmia_logo center">
 <img class="mt10" id="cname" src="">
 </div>

 <div class="Qr_code center" id="qr_img_div">
 
  <img class="qr_image" id="qr_img" src="<?php echo '../system/upload/'.$data['card_qr_img']; ?>">
 
 </div>

 </div>

 <div class="white">
 <div class="logo_icon">
 <img src="view/image/logoicon.png">
 </div>
 <div class="logo_text">
 <img src="view/image/unnati.png">
 </div>

 <div class="container">

 <label for="Name" class="input"  id="Grower_Name_level"><?php echo $data['Grower_Name']; ?></label>
 <label class=" small" for="name">Farmer Name</label>

 <label for="Name" class="input"  id="Father_Name_level"><?php echo $data['Father_Name']; ?></label>
 <label class=" small" for="name">Father Name</label>

 
 <label class="input" id="Grower_Code_level"   for="name"><?php echo $data['Grower_Code']; ?></label>
 <label class=" small" for="name">Grower Id</label>
 </div>

 <div class=semibox>
 <label for="Name" class="input"  id="Village_level"><?php echo $data['Village']; ?></label>
 <label class=" small" for="name">Village</label>
 </div>
 <div class=semibox>
 <label for="Name" class="input" id="Unit_level"><?php echo $data['Unit']; ?></label>
 <label class=" small" for="name">Unit</label>
 </div>

 <div class="serial_no">
 <h1  id="Card_Serial_Number_level"><?php echo $data['Card_Serial_Number']; ?></h1>
 </div>

 </div>
</div> 

	
	
	
	
      </div>
     
    </div>

  </div>
</div>                                   
                <!-- /Page Body -->
			<script type="text/javascript">  
     function download_pdf(grower_id,card_number,farmer_name,father_name,village,unit,CARD_QR_IMG,cname) {  

	url = 'index.php?route=farmerrequest/cardprint/download_pdf&token=<?php echo $token; ?>&grower_id='+grower_id+'&card_number='+card_number+'&farmer_name='+farmer_name+'&father_name='+father_name+'&village='+village+'&unit='+unit+'&CARD_QR_IMG='+CARD_QR_IMG+'&cname='+cname;
	
	
        window.open(url, '_blank');
	//location = url;
}
      
   </script>

<script type="text/javascript"> 
$('.date').datetimepicker({
pickTime: false
});




function add_to_array(order_id,check_box_id)
{ //alert(order_id);
if($('#'+check_box_id).is(':checked'))
{
//alert('checked');/var/www/html/shop/admin/controller/farmerrequest/cardprint.php
url = 'index.php?route=farmerrequest/cardprint/add_orders_to_array&token=<?php echo $token; ?>&order_id='+order_id+'&action=add';

$.ajax({
url: url,
// dataType: 'json',
success: function(json) 
{
//alert(json); 

}

});
}
else
{
//alert('unchecked');
url = 'index.php?route=farmerrequest/cardprint/add_orders_to_array&token=<?php echo $token; ?>&order_id='+order_id+'&action=remove';
$.ajax({
url: url,
// dataType: 'json',
success: function(json) 
{
//alert(json); 

}

});
}
return true;
}
function add_all_to_array()
{
if($('#check_box_all').is(':checked'))
{
//alert('checked');
url = 'index.php?route=farmerrequest/cardprint/select_all_bills&token=<?php echo $token; ?>&action=add';
var filter_growerid = $('#filter_growerid').val();

if (filter_growerid!="") {
url += '&filter_growerid=' + encodeURIComponent(filter_growerid);
}

var filter_date_start = $('input[name=\'filter_date_start\']').val();

if (filter_date_start) {
url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
}

var filter_date_end = $('input[name=\'filter_date_end\']').val();

if (filter_date_end) {
url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
}

var filter_unit = $('select[name=\'filter_unit\']').val();
if (filter_unit!="") {
url += '&filter_unit=' + encodeURIComponent(filter_unit);
}
var filter_company = $('select[name=\'filter_company\']').val();

if (filter_company!="") {
url += '&filter_company=' + encodeURIComponent(filter_company);
}
$.ajax({
url: url,
// dataType: 'json',
success: function(json) 
{
//alert(json); 
$('#checkboxes').find('input:checkbox').prop('checked', true); 
alertify.success('All card print are Selected');
}

});
}
else
{
//alert('unchecked');
url = 'index.php?route=farmerrequest/cardprint/select_all_bills&token=<?php echo $token; ?>&action=remove';
$.ajax({
url: url,
// dataType: 'json',
success: function(json) 
{
//alert(json);
$('#checkboxes').find('input:checkbox').prop('checked', false); 
alertify.error('All card print are un selected');
}

});
}
return true;


}



function clear_company(data) {
//alert(data);
 $('#button-download').hide();
//document.getElementById('button-download').style.display=none;

 var companyid=data;
 $.ajax({ 
 type: 'post',
 url: 'index.php?route=farmerrequest/cardprint/getUnitbyCompany&token='+getURLVar('token'),
 data: 'companyid='+companyid,
 //dataType: 'json',
 cache: false,

success: function(data) {

//alert(data);
 $("#input-unit").html(data);
 }
 });
 }



function cardview(grower_id,card_number,farmer_name,father_name,village,unit,CARD_QR_IMG,cname)
{
var btn_html_data=$("#span_"+grower_id).html();
$("#btn_html").html(btn_html_data);
    //alert(father_name);
        $("#Grower_Name_level").html(farmer_name);
            $("#Father_Name_level").html(father_name);
			$("#cname").attr("src",cname);
                $("#Village_level").html(village);
                    $("#Unit_level").html(unit);
    $("#Grower_Code_level").html(grower_id);
	card_number1=card_number;
	card_number2=card_number;
	card_number3=card_number;
	
	var cardno1 =card_number1.substring(0,4);
	var cardno2 =card_number2.substring(4,8);
	var cardno3 =card_number3.substring(8,12);
	var cardno=cardno1+' '+cardno2+' '+cardno3;
	//alert(card_number1+'-'+cardno1);
	//alert(card_number2+'-'+cardno2);
	//alert(card_number3+'-'+cardno3);
	
	//alert(cardno);
	
    $("#Card_Serial_Number_level").html(cardno);
	//alert(CARD_QR_IMG);
    //$("#qr_img_div").html('<img src=');
	if(CARD_QR_IMG!="")
	{
    $("#qr_img").attr("src",CARD_QR_IMG);
	}
	else
	{
	$("#qr_img").attr("src","../system/upload/defaultqrimage.png");
	}
}
    
    
    
function updataPrintStatus(gid,uid)
{
   
    
 //alert(gid);
      $.ajax({
		url: 'index.php?route=farmerrequest/cardprint/printdata&token=<?php echo $token; ?>&gid='+gid+'&uid='+uid,
		dataType: 'json',			
		success: function(json) {
                 //alert(JSON.stringify(json));
                  
              location.reload();
		
	        },
                error:function (json){
                    alert(JSON.stringify( json));
                }
                
	});
 }
 </script>  
 <script type="text/javascript"><!--
$('#button-download').on('click', function() {
 var filter_unit = $('select[name=\'filter_unit\']').val();
  var filter_asoc = $('select[name=\'filter_asoc\']').val();
 var filter_company = $('select[name=\'filter_company\']').val();
if(filter_company=="")
{
alertify.error("Please select company");
}
if(filter_asoc=="")
{
alertify.error("Please select association");
}
else if(filter_unit=="")
{
alertify.error("Please select unit");
}
else
{
alertify.confirm("Are you sure want to print all !", function (e) {
    if (e) {
        // user clicked "ok"
    


	var url = 'index.php?route=farmerrequest/cardprint/download_excel&token=<?php echo $token; ?>';
	
//        	var filter_growerid = $('#filter_growerid').val();
//	
//	if (filter_growerid!="") {
//		url += '&filter_growerid=' + encodeURIComponent(filter_growerid);
//	}
var filter_unit = $('select[name=\'filter_unit\']').val();

 if (filter_unit!="") {
 url += '&filter_unit=' + encodeURIComponent(filter_unit);
 }
 var filter_company = $('select[name=\'filter_company\']').val();

 if (filter_unit!="") {
 url += '&filter_company=' + encodeURIComponent(filter_company);
 }
 if (filter_asoc!="") {
 url += '&filter_asoc=' + encodeURIComponent(filter_asoc);
 }

	
	//location = url;
	var filter_date_start = $('input[name=\'filter_date_start\']').val();

if (filter_date_start) {
url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
}

var filter_date_end = $('input[name=\'filter_date_end\']').val();

if (filter_date_end) {
url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
}
var filter_zone = $('input[name=\'filter_zone\']').val();

if (filter_zone!="") {
url += '&filter_zone=' + encodeURIComponent(filter_zone);
}
var filter_circle = $('input[name=\'filter_circle\']').val();

if (filter_circle!="") {
url += '&filter_circle=' + encodeURIComponent(filter_circle);
}
if (filter_asoc!="") {
url += '&filter_asoc=' + encodeURIComponent(filter_asoc);
}
var url2='index.php?route=farmerrequest/cardprint/checknotzero&token=<?php echo $token; ?>&filter_unit='+encodeURIComponent(filter_unit)+'&filter_date_start='+encodeURIComponent(filter_date_start)+'&filter_date_end='+encodeURIComponent(filter_date_end)+'&filter_zone='+encodeURIComponent(filter_zone)+'&filter_circle='+encodeURIComponent(filter_circle)+'&filter_asoc='+encodeURIComponent(filter_asoc);
 //alert(url2);
  $.ajax({
            url: url2,
            //dataType: 'text',
            success: function(json) {
                //alert(json);
					if(json=='3')
					{
					
					alertify.error("Please select atleast 1 Card");
					return false;
					}
                    if(json=='1')
					{
					
					alertify.error("Some error occured");return false;
					}
					else if(json=='2')
					{					
					alertify.error("Some error occured");return false;
					}
					else
					{
					//alert("ghdfksh");
					window.open(url, '_blank');
					location.reload();
					}
					
					}
            
        });


        //window.open(url, '_blank');
} else {
        // user clicked "cancel"
    }
	//location.reload();
});

}

});
//--></script>
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


 var filter_unit = $('select[name=\'filter_unit\']').val();
 var filter_company = $('select[name=\'filter_company\']').val();
 if(filter_company=="")
{
alertify.error("Please select company");
}
else if(filter_unit=="")
{
alertify.error("Please select unit");
}
else
{

var url = 'index.php?route=farmerrequest/cardprint&token=<?php echo $token; ?>';
	
        	var filter_growerid = $('#filter_growerid').val();
	
	if (filter_growerid!="") {
		url += '&filter_growerid=' + encodeURIComponent(filter_growerid);
	}

	var filter_date_start = $('input[name=\'filter_date_start\']').val();

if (filter_date_start) {
url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
}

var filter_date_end = $('input[name=\'filter_date_end\']').val();

if (filter_date_end) {
url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
}
  

 if (filter_unit!="") {
 url += '&filter_unit=' + encodeURIComponent(filter_unit);
 }
 var filter_company = $('select[name=\'filter_company\']').val();

 if (filter_company!="") {
 url += '&filter_company=' + encodeURIComponent(filter_company);
 }
    var filter_zone = $('input[name=\'filter_zone\']').val();

if (filter_zone!="") {
//alert(filter_zone);
url += '&filter_zone=' + encodeURIComponent(filter_zone);
}
var filter_circle = $('#filter_circle').val();
//alert(filter_circle);
if (filter_circle!="") {
url += '&filter_circle=' + encodeURIComponent(filter_circle);
}

	location = url;
}
	
});
//--></script> 
      
<?php echo $footer; ?>