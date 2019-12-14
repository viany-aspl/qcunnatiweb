<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid"> 
      <h1>Review Form</h1>
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
        <h3 class="panel-title"><i class="fa fa-list"></i>Review Form</h3>
       
      </div><style>
    .form-group {
    margin-bottom: 5px !important;}
    
</style>
      <div class="panel-body">

	 <div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-end">Grower ID</label>
                <div class="input-group date">
                  <?php //echo $filter_store; //print_r($stores);//exit; ?>
                  <span class="input-group-btn">
                      
                  <input autocomplete="off" type="text" style="text-transform: uppercase; ;" value="<?php echo $filter_growerid; ?>" placeholder="GROWER ID" name="filter_growerid" id="filter_growerid" class="form-control" />
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
			  <button type="button"  style="margin-top:23px;"   id="button-filter656" onclick="return updataReviewStatusAll();" class="btn btn-primary pull-right">Approve All</button>
			<img id="cr_img2" src="view/image/processing_image.gif" style="margin-top: 27px;float: right;height: 60px;display : none;"/>
            
			 </div>
          </div>
        </div>


        <div class="widget-body">
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left">Sno</td>
                <td class="text-left">Unit</td>
				<td class="text-left">Village</td>
                <td class="text-left">Grower ID</td>
                <td class="text-left">Grower Name</td>
                <td class="text-left">Action</td>
                <td class="text-left" style="width: 10px;" class="text-center">
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
                
                <td class="text-left"><button type="button" class="btn btn-primary "  data-toggle="modal" onclick="openreviewmodal(<?php echo $order['GROWER_ID']; ?>);" data-target="#myModal">Review</button></td>
<!--                <button  id="issuedbtn"  type="submit" id="button-filter" class="btn btn-primary " onclick="updataReviewStatus(<?php echo $order['GROWER_ID']; ?>)">Approve</button>-->
                <td style="width: 10px;" class="text-center"><input value="<?php echo $order['GROWER_ID'].'-'.$order['UNIT_ID']; ?>" type="checkbox" name="checkbox_grower[]"  /></td>
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

                    
                </div>
                                </div>                    
                  
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Review Form</h4>
      </div>
        <style>
               .modal-body form-group{ margin-bottom: 3px;}
            
        </style>
      <div class="modal-body">                                          
        <form action="" method="post" enctype="multipart/form-data" id="form-dtl" class="form">   
                                                    <div class="row">  
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="exampleInputName2">Name</label>
                                                                <span class="input-icon icon-right">
                                                                    <input type="text" class="form-control" id="fname" name="fname" placeholder="Name" readonly="readonly">
                                                                    
                                                                    <input type="hidden" class="form-control" id="card_id" name="card_id" value="">
                                                                   
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="exampleInputName2">Mobile No</label>
                                                                <span class="input-icon icon-right">
                                                                    <input type="text" readonly="readonly" class="form-control" id="farmermobno" name="farmermobno" placeholder="Mobile">
<input type="hidden" class="form-control" id="farmermob" name="farmermob" placeholder="Mobile" readonly="readonly">
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row"> 
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="exampleInputName2"> Father Name</label>
                                                                <span class="input-icon icon-right">
                                                                    <input type="text" class="form-control" id="fathername" name="fathername" placeholder="Father Name" readonly="readonly" value="">
                                                                </span>
                                                            </div>
                                                        </div> 
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="Unit">Village</label>
                                                                <input type="hidden" class="form-control" id="village" name="village" placeholder="Village">
																<input type="text" readonly="readonly"  name="village_name" value="" placeholder=" Village ID " id="input-village_name" class="form-control" />
                                                            </div>
                                                        </div>
                                                          
                                                    </div>
                                                    
                                                    <div class="row"> 
                                                        
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="Unit">Unit</label>
                                                                 <input type="text" readonly="readonly"  class="form-control" id="unitname" name="unitname" placeholder="Unit">
																<input type="hidden" class="form-control" id="unitno" name="unitno" placeholder="Unit">
                                                            </div>
                                                        </div>
														<div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="Address">Card Serial No</label>
                                                                <input type="text" class="form-control" id="cardserialno" name="cardserialno" placeholder="Card Serial Number" readonly="readonly">
                                                            </div>
                                                        </div>
                                                       
                                                    </div>
                                                    <div class="row"> 
                                                         
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="Circle">Circle</label>
                                                                <input type="text" class="form-control" id="circle" name="circle" placeholder="Circle">
                                                            </div>
                                                        </div>
														<div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="Zone">Zone</label>
                                                                <input type="text" class="form-control" id="zone" name="zone" placeholder="Zone">
                                                            </div>
                                                        </div> 
                                                        

                                                   </div>
                                                 <div class="row"> 
                                                
                                                      <div class="col-sm-6">
                                                 <label for="Address" style="color:red;">Grower ID</label>
                                                 <input type="text" class="form-control" id="groweid" name="groweid" value="" readonly="readonly">
                                                 </div>
                                                     <div class="col-sm-6"  >
                                                      <button id="button-filter3"  style="background-color: #EA5B19;border-color: #EA5B19;color: white;margin-top: 27px;"  type="button" class="btn btn-red" data-dismiss="modal">Close</button>
                                                      <button style="margin-top: 27px;margin-left: 10px; float:right; " type="button" id="button-filter4" class="btn btn-primary " onclick="return updataReviewRejectStatus()">Reject</button> 
                                                     <button style="margin-top: 27px; float:right; " type="button" id="button-filter2" class="btn btn-primary " onclick="return updataReviewStatus()">Approve</button>                                                      
                                                      <img id="cr_img" src="view/image/processing_image.gif" style="margin-top: 27px;float: right;height: 60px;display : none;"/>
                                                 </div>
                                                
                                                 </div> 
                                                 
                                                  
        
                                                </form>
                                          </div></div>
                 
      </div>

  </div>






                <!-- /Page Body -->
<script type="text/javascript">

function clear_company(data) {
//alert(data);
 $('#button-download').hide();
//document.getElementById('button-download').style.display=none;

 var companyid=data;
 $.ajax({ 
 type: 'post',
 url: 'index.php?route=farmerrequest/reviewform/getUnitbyCompany&token='+getURLVar('token'),
 data: 'companyid='+companyid,
 //dataType: 'json',
 cache: false,

success: function(data) {

//alert(data);
 $("#input-unit").html(data);
 }
 });
 }
    $('#checkall').click(function() {//alert("xdjs");
    var checked = $(this).prop('checked');
    $('#checkboxes').find('input:checkbox').prop('checked', checked);
  });                  
 function updataReviewStatusAll()                    
 {
     
   var data = $('input[name="checkbox_grower[]"]').serialize();
  
    //alert(data);
  //console.log(data);
  if(data=="")
  {
  alertify.error('Please select atleast 1 Card request');
  return false;
  }
  if(data!="")
  {
  //alert(data);
  //return;
  $.ajax({
            url: 'index.php?route=farmerrequest/reviewform/reviewAll&token=<?php echo $token; ?>',
            //dataType: 'json',
            data:data,	
		beforeSend:function()
			{
			
			$("#button-filter656").hide();
			$("#cr_img2").show();
			
			},
            success: function(json) {
               // alert(json);
              //  return false;
              location.reload();
            },
            error: function(json)
            {
                //alert(json);
				alertify.error(JSON.stringify( json));
				$("#button-filter656").show();
			$("#cr_img2").hide();
                return false;
            }
        });
		}
   return false;
 }                     
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
  <script type="text/javascript">
 function openreviewmodal(growerid)
 {
     
      $.ajax({
		url: 'index.php?route=farmerrequest/reviewform/reviewmodaldata&token=<?php echo $token; ?>&growerid='+growerid,
		dataType: 'json',			
		success: function(json) {
              //  alert(JSON.stringify(json));
                 
                    document.getElementById("fname").value=json[0].GROWER_NAME;
                   
                    document.getElementById("fathername").value=json[0].FTH_HUS_NAME;
              
                    document.getElementById("unitno").value=json[0].UNIT_ID;
					document.getElementById("unitname").value=json[0].UNIT_NAME;
     
                   // document.getElementById("farmermob").value=json[0].MOB;
				   document.getElementById("farmermob").value=json[0].MOB;
var str=json[0].MOB;
var mobileno= 'XXXXXX'+str.substring(6,10);
document.getElementById("farmermobno").value=mobileno;




                    
                    document.getElementById("groweid").value=json[0].GROWER_ID;
                    if(json[0].VILLAGE_ID!=undefined)
					{
                    document.getElementById("village").value=json[0].VILLAGE_ID;
					document.getElementById("input-village_name").value=json[0].VILLAGE_NAME;
					}
                    document.getElementById("card_id").value=json[0].SID;
                    
                    document.getElementById("cardstatus").value=json[0].CARD_STATUS_DESC;
                    
                     document.getElementById("cardserialno").value=json[0].CARD_SERIAL_NUMBER;
                 
             // location.reload();
		
	        },
                error:function (json){
                    alert(JSON.stringify( json));
                }
                
	});
 }
 </script>
<script type="text/javascript"> 
function updataReviewStatus()
{
   
    var gid= document.getElementById('groweid').value;
	  var unit= document.getElementById('unitno').value;
    //var card_sid= document.getElementById('sid').value;
    //alert(card_sid);
    if(gid!=="")
    {
       // alert(gid);
      $.ajax({
		url: 'index.php?route=farmerrequest/reviewform/reviewdata&token=<?php echo $token; ?>&gid='+gid+'&unit='+unit,
		dataType: 'json',	
		beforeSend:function()
			{
			$("#button-filter4").hide();
			$("#button-filter2").hide();
			$("#button-filter3").hide();
			$("#cr_img").show();
			
			},			
		success: function(json) {
		//alert(JSON.stringify(json));
		if(JSON.stringify(json)==0)
		{
		 alertify.error("Opps Some Error Occur ! Please try again");
		}
		else if(JSON.stringify(json)=='0')
		{
		 alertify.error("Opps Some Error Occur ! Please try again");
		}
		else if(!JSON.stringify(json))
		{
		 alertify.error("Opps Some Error Occur ! Please try again");
		}
		else{
		   location.reload();
		}
                 
                  
              
		
	        },
                error:function (json){
                   // alertify.error(JSON.stringify( json));
				   $("#button-filter4").show();
					$("#button-filter2").show();
					$("#button-filter3").show();
					$("#cr_img").hide();
                }
                
	});
        }
 }
 function updataReviewRejectStatus()
{
   
    var gid= document.getElementById('groweid').value;
    if(gid!="")
    {
        //alert(gid);
		//return false;
		//alert('here');
      $.ajax({
		url: 'index.php?route=farmerrequest/reviewform/reviewrejectdata&token=<?php echo $token; ?>&gid='+gid,
		dataType: 'json',	
		beforeSend:function()
			{
			$("#button-filter4").hide();
			$("#button-filter2").hide();
			$("#button-filter3").hide();
			$("#cr_img").show();
			//alert('kkk');return false;
			},		
		success: function(json) {
                 //alertify.success(JSON.stringify(json));
                  
                 location.reload();
				 
		
	        },
                error:function (json){
                    alertify.error(JSON.stringify( json));
					$("#button-filter4").show();
					$("#button-filter2").show();
					$("#button-filter3").show();
					$("#cr_img").hide();
                }
                
	});
	
        }
		return false;
 }
 
 </script> 
 
 <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=farmerrequest/reviewform&token=<?php echo $token; ?>';
	
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