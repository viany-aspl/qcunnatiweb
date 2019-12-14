<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">
  <div class="page-header">
    <div class="container-fluid"> 
      <h1>Card Detail</h1>
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
        <h3 class="panel-title"><i class="fa fa-list"></i>Card  Detail</h3>
       
      </div>
     
      <div class="row">
          <div class="col-lg-6 col-sm-6 col-xs-12">
           
            <div class="widget">
                <div class="widget-header bordered-bottom bordered-lightred">
                  
                </div>
                <br/><style>
    .form-group {
    margin-bottom: 5px !important;}
    
</style>
                <div class="widget-body">
                    <div id="horizontal-form">
                        <form action="" method="post" enctype="multipart/form-data" id="form-coupon" class="form-horizontal">
                            <div class="form-group">
                                <label for="phone" class="col-sm-3 control-label no-padding-right">Mobile</label>
                                    <div class="col-sm-9">
                                       <input type="text" name="mobile" onkeypress='return event.charCode >= 48 &amp;&amp; event.charCode <= 57 || event.keyCode==8 || event.keyCode==46' maxlength='10' minlength='10' value="" placeholder="Mobile Number " id="input-mobile" class="form-control" />
                                    </div>
                            </div>
							 <div class="form-group">
                                <label for="inputEmail3" class="col-sm-3 control-label no-padding-right">Company</label>
                                    <div class="col-sm-9">
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
                                      
                                    </div>
                            </div>
							 <div class="form-group">
                                <label for="inputEmail3" class="col-sm-3 control-label no-padding-right">Unit</label>
                                    <div class="col-sm-9">
                                        <select name="filter_unit" id="input-unit" class="form-control" onchange="clear_unit();">
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
                                      
                                    </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-3 control-label no-padding-right">Grower Id</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="grower_id" value="" placeholder=" Grower ID " id="input-grower_id" class="form-control" />
                                      
                                    </div>
                            </div>
                             <!---- <div class="form-group">
<label for="" class="col-sm-3 control-label no-padding-right">Village ID</label>
<div class="col-sm-9">
<input type="text" name="village_id" value="" placeholder=" Village ID " id="input-village_id" class="form-control" />

</div>
</div>     ---->      
<div class="form-group">
<label for="" class="col-sm-3 control-label no-padding-right">Card Serial No</label>
<div class="col-sm-9">
<input type="text" name="cardserialno" value="" placeholder="Card Serial No" id="input-card-sno" class="form-control" />

</div>
</div>                      
           
                                                    
                            <div class="form-group ">
                                <div class="col-sm-offset-2 col-sm-10 ">
                                     <span><i id="gif" class="fa fa-spinner fa-spin fa-3x fa-fw pull-right" style="font-size:24px; display:none"></i>
                                         <button type="button" id="button-filter" class="btn btn-primary pull-right" onclick="chekgrowerid();" >Submit</button>
			<button type="button" id="button-reset"  style="margin-right:10px;" class="btn btn-primary pull-right" onclick="resetbtn();" >Reset</button>
										 <img id="cr_img" src="view/image/processing_image.gif" style="float: right;height: 60px;display : none;"/>
                        
										 </span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>  
              <div class="col-lg-12 ">
           
            <div class="widget" id="statusdiv" style="display:none">
                <div class="widget-header bordered-bottom bordered-lightred">
                    <span class="widget-caption">The current status of card is :</span>
                </div><br/>
                <div class="widget-body" >
                    <div id="horizontal-form">
                        <form action="" method="post" enctype="multipart/form-data" id="form-coupon" class="form-horizontal">
                           <div class="form-group">
                                <label for="inputEmail3" style="color:red;" class="col-sm-3 control-label no-padding-right">Status</label>
                                    <div class="col-sm-6">
                                        <input type="text" style="outline:none;" name="cardstatus"  value=""  id="cardstatus"  class="form-control" />
                                    </div>
										<div class="col-sm-3"  >
                                                      <button style="background-color: #EA5B19;border-color: #EA5B19;display:none; float:left;" id="cancelbtn"  type="button" onclick="reloadpage();" class="btn btn-info">Cancel</button>
                                                      
                                                      <input style=" display:none;" id="cardreqst"  type="submit" class="btn btn-primary pull-right"  value="Card Request" />  
                                                      <button style=" display:none;"  type="button" onclick="rejectstusremove();" id="issbtn" class="btn btn-primary pull-right"> Card Request</button> 
                                                     	<button id="viewbtn" type="button" data-toggle="modal" data-target="#myModal" class="btn btn-primary pull-right" onclick="cardview()">
																Card View
															</button>
                                    </div>  
                           </div>
                        </form>
                    </div><br/><br/><br/>
                </div>
				
            </div>    
            </div>
            </div>
          
           <div class="col-lg-6 col-sm-6 col-xs-12" id="detailform" style="display:none">
                                    <div class="widget">
                                        <div class="widget-header bordered-bottom bordered-blue">
                                         
                                        </div>
                                        <div class="widget-body">
                                            <div>
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
<input type="hidden" readonly="readonly" class="form-control" id="farmermob" name="farmermob" placeholder="Mobile">
																	
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row"> 
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="exampleInputName2"> Father Name</label>
                                                                <span class="input-icon icon-right">
                                                                    <input type="text" readonly="readonly" class="form-control" id="fathername" name="fathername" placeholder="Father Name">
                                                                </span>
                                                            </div>
                                                        </div> 
                                                        
                                                           <!--<div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="Aadhar Card">AADHAR CARD</label>
                                                                <input type="text" class="form-control" id="addharno" name="addharno" placeholder="AADHAR CARD NO">
                                                            </div>
                                                        </div>-->
														<div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="Unit">Unit</label>
																  <input type="text" readonly="readonly" class="form-control" id="unitname" name="unitname" placeholder="Unit">
                                                                <input type="hidden" class="form-control" id="unitno" name="unitno" placeholder="Unit">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--<div class="row"> 
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="ID Proof">Any Other ID Proof</label>
                                                                    <select class="form-control col-xs-12 col-md-12" id="anotherproof" >
                                                                        <option value="volvo">Address proof </option>
                                                                        <option value="saab">Election ID</option>
                                                                        <option value="mercedes">Phone bill</option>
                                                                        <option value="audi">Bank passport.</option>
                                                                    </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="Aadhar Card">Enter ID No</label>
                                                                <input type="text" class="form-control" id="idno" name="idno" placeholder="Enter ID No">
                                                            </div>
                                                        </div>
                                                    </div>---->
                                                    <div class="row"> 
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="Unit">Village</label>
                                                                <input type="hidden" class="form-control" id="village" name="village" placeholder="Village">
																<input type="text" readonly="readonly" name="village_name" value="" placeholder=" Village Name" id="input-village_name" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="Address">Card Serial No</label>
                                                                <input type="text" class="form-control" id="cardserialno" name="cardserialno" placeholder="Card Serial Number" readonly="readonly">
                                                            </div>
                                                        </div>
                                                       
                                                    </div>
                                                    <!-----<div class="row"> 
                                                         <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="Zone">Zone</label>
                                                                <input type="text"  readonly="readonly" class="form-control" id="zone" name="zone" placeholder="Zone">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="Circle">Circle</label>
                                                                <input type="text" readonly="readonly" class="form-control" id="circle" name="circle" placeholder="Circle">
                                                            </div>
                                                        </div>

                                                        

                                                   </div>----->
                                                 <div class="row"> 
                                                 
                                                      <div class="col-sm-6">
                                                 <label for="Address" style="color:red;">Grower ID</label>
                                                 <input type="text" class="form-control" readonly="readonly" id="growerid" name="growerid" value="">
<input type="hidden" class="form-control" id="qrimage" name="qrimage" >
                                                 </div>
												  <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="exampleInputName2">Pin No</label>
                                                                <span class="input-icon icon-right">
                                                                   <input type="text" readonly="readonly" class="form-control" id="farmercardpin" name="farmercardpin" placeholder="Card Pin">

																	
                                                                </span>
                                                            </div>
                                                        </div>
                                                   
                                                
                                                 </div> 
                                                 <div class="row"> 
                                                     <div class="col-sm-6"  ></div>   
                                                
                                                
                                                 <div class="col-sm-6" >
                                                     <button style="margin-top: 27px; display:none; float:left;" id="blockbtn"  type="button" class="btn btn-red" onclick="blockedbtn();">Blocked</button>                                                      
                                                     <button style="margin-top: 27px; display:none;" id="issuedbtn"  type="submit" class="btn btn-primary pull-right " >Re Issued Card</button>   
                                                    
                                                      
                                                 </div>
                                                 </div>
                                                  
        
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div><br/><br/>
							 <div class="col-lg-12" id="hist" style="display:none;">	
								 <div class="col-lg-6 ">
           
            <div class="widget" id="statusdiv" >
                <div class="widget-header bordered-bottom bordered-lightred">
                    <span class="widget-caption">Card Status history is :</span>
                </div><br/>
				  <div class="table-responsive">
					<table class="table table-bordered">
					<thead>
					  <tr>
					   <td class="text-left">Status</td>
				       <td class="text-left">Date</td>
			
                   </tr>
				   
                  </thead>
				    <tbody id='history_tbody'>
				  
				    </tbody> 
					
					</table>			
					</div>    
		    </div></div>
			<div class="col-lg-6 ">
			<div class="widget" id="statusdiv" >
                <div class="widget-header bordered-bottom bordered-lightred">
                    <span class="widget-caption">Card Transaction history is :</span>
                </div><br/>
				  <div class="table-responsive">
					<table class="table table-bordered">
					<thead>
					  <tr>
					   <td class="text-left">S no</td>
				       <td class="text-left">Store</td>
						<td class="text-left">Date</td>
				       <td class="text-left">Invoice No</td>
					   <td class="text-left">Tagged Amount</td>
				       <td class="text-left">Cash Amount </td>
                   </tr>
                  </thead>
				   
				     
					<tbody id="transhistory_tbody"> 
					 
					</tbody>
					</table>			
					</div>    
		    </div></div></div>
								
								
								
								
 
</div><br/><br/><br/>
      
 </div>      
   </div>          
 </div>      

<link href="view/javascript/ca_Style.css" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Jura" rel="stylesheet">

<div id="myModal" class="modal fade" role="dialog">
<div class="modal-dialog" >

<!-- Modal content-->
<div class="modal-content">
<div class="modal-header" style="height:60px;">
<!--<span id="btn_html"></span>-->

<button type="button" class="close pull-right" data-dismiss="modal">&times;</button>

</div>
<div class="modal-body" id="printarea">

<div class="centerbox">
<?php //print_r($data); ?>
<div class="grey" style="height:100%">

<div class="dalmia_logo center">
<img class="mt10" id="cname" src="view/image/DSCL.png">
</div>

<!----<div class="Qr_code center" id="qr_img_div">

<img class="qr_image" id="qr_img" src="<?php echo '../system/upload/'.$data['card_qr_img']; ?>">

</div>---->

</div>

<div class="white">
<div class="logo_icon">
<img src="view/image/logoicon.png">
</div>
<div class="logo_text">
<img src="view/image/unnati.png">
</div>

<div class="container -pl-15">

<label for="Name" class="input" id="Grower_Name_level"><?php echo $data['Grower_Name']; ?></label>
<label class=" small" for="name">Farmer Name</label>

<label for="Name" class="input" id="Father_Name_level"><?php echo $data['Father_Name']; ?></label>
<label class=" small" for="name">Father Name</label>


<label class="input" id="Grower_Code_level" for="name"><?php echo $data['Grower_Code']; ?></label>
<label class=" small" for="name">Grower Id</label>
</div>

<div class=semibox>
<label for="Name" class="input" id="Village_level"><?php echo $data['Village']; ?></label>
<label class=" small" for="name">Village</label>
</div>
<div class=semibox>
<label for="Name" class="input" id="Unit_level"><?php echo $data['Unit']; ?></label>
<label class=" small" for="name">Unit</label>
</div>

<div class="serial_no">
<h1 id="Card_Serial_Number_level"><?php echo $data['Card_Serial_Number']; ?></h1>
</div>

</div>
</div>





</div>

</div>

</div>
</div>

<div id="myModal2" class="modal fade" role="dialog">
<div class="modal-dialog" >
<div class="modal-content">
	<div class="modal-header" style="height:60px;">
	<button type="button" class="close pull-right" data-dismiss="modal">&times;</button>
	</div>
	<div class="modal-body" id="printarea">
	<div class="table-responsive">
			<table class="table table-bordered">
			<thead>
				<tr>
					<td class="text-left">S no</td>
				    <td class="text-left">Product Name</td>
				    <td class="text-left">Quantity</td>
				    <td class="text-left">Price</td>
					<td class="text-left">Tax</td>
					<td class="text-left">Total</td>
					
               </tr>
           </thead>
			<tbody id="productdtl_body"> 
					 
			</tbody>
			</table>			
	</div>
	</div>





</div>

</div>

</div>
</div>

<script type="text/javascript">
  function cardview()
{

var farmer_name=document.getElementById('fname').value;
var father_name=document.getElementById('fathername').value;
var village=document.getElementById('input-village_name').value;
var unit=document.getElementById('unitname').value;
var grower_id=document.getElementById('growerid').value;
var card_number=document.getElementById('cardserialno').value;
var CARD_QR_IMG =document.getElementById('qrimage').value;

//alert(fathername);
var btn_html_data=$("#span_"+grower_id).html();
$("#btn_html").html(btn_html_data);
//alert(father_name);
$("#Grower_Name_level").html(farmer_name);
$("#Father_Name_level").html(father_name);
$("#cname").attr("src","view/image/DSCL.png");
$("#Village_level").html(village);
$("#Unit_level").html(unit);
$("#Grower_Code_level").html(grower_id);
card_number1=card_number;
card_number2=card_number;
card_number3=card_number;
card_number4=card_number;
var cardno1 =card_number1.substring(0,4);
var cardno2 =card_number2.substring(4,8);
var cardno3 =card_number3.substring(8,12);
var cardno4=card_number4.substring(12,20);
var cardno=cardno1+' '+cardno2+' '+cardno3+' '+cardno4;
//alert(card_number1+'-'+cardno1);
//alert(card_number2+'-'+cardno2);
//alert(card_number3+'-'+cardno3);

//alert(cardno);

$("#Card_Serial_Number_level").html(cardno);
//alert(CARD_QR_IMG);
//$("#qr_img_div").html('<img src=');
if(CARD_QR_IMG!="")
{
$("#qr_img").attr("src","../system/upload/qrimages/"+CARD_QR_IMG+".png");
}
else
{
$("#qr_img").attr("src","../system/upload/defaultqrimage.png");
}
}

function resetbtn()
{
window.location.reload();
}

function clear_unit()
{
document.getElementById("detailform").style.display = "none";
 document.getElementById("statusdiv").style.display = "none";
}
 
function clear_company(data) {
//alert(data);
 $('#button-download').hide();
//document.getElementById('button-download').style.display=none;
 document.getElementById("detailform").style.display = "none";
 document.getElementById("statusdiv").style.display = "none";
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

 function chekgrowerid()
 {
     
     $('#cr_img').show(); 
	 $('#button-filter').hide();
    var mobile = document.getElementById('input-mobile').value;
    var grower_id = document.getElementById('input-grower_id').value;
    var card_serial_no = document.getElementById('input-card-sno').value;
	var company_id = document.getElementById('input-company').value;
    var unit_id = document.getElementById('input-unit').value;
	
    if((card_serial_no=="") && (mobile=="") )
    {
	
		if((company_id=="") && (unit_id=="") && (grower_id==""))
	   {
        document.getElementById("detailform").style.display = "none";
		document.getElementById("statusdiv").style.display = "none";
        alertify.error("Please enter mobile  or cardserial no");
		return false; 
		}
		else
		{
	   if((company_id=="") || (unit_id=="") || (grower_id==""))
	   {  
	    document.getElementById("detailform").style.display = "none";
		document.getElementById("statusdiv").style.display = "none";
	alertify.error("Please select Company and Unit  or enter Grower id");
		return false;
	    
        } 
		}	  
    }
	
	
	
	
    
   
     $.ajax({
		url: 'index.php?route=farmerrequest/carddetail/check&token=<?php echo $token; ?>&grower_id=' +  encodeURIComponent(grower_id)+"&mobile="+encodeURIComponent(mobile)+'&unit_id='+encodeURIComponent(unit_id)+'&company_id='+encodeURIComponent(company_id)+'&card_serial_no='+encodeURIComponent(card_serial_no),
		dataType: 'json',
			
		success: function(json) {
			$('#cr_img').hide(); 
			 $('#button-filter').hide();
             //alert(JSON.stringify(json));
                $('#statusdiv').hide(); 
				if(json.cardhistory!="")
				{
				$('#hist').show(); 
				}
				//document.getElementById("cardstatus").value=json.CARD_STATUS_DESC;
				if(!json.GROWER_ID)
				{
					document.getElementById("detailform").style.display = "none";
					document.getElementById("statusdiv").style.display = "none";
					alert("Oops! no Data found");
					return false;
				}
					
				
				document.getElementById("detailform").style.display = "block";
				 document.getElementById("fname").value=json.GROWER_NAME;
                   
                    document.getElementById("fathername").value=json.FATHER_NAME;
              
                    document.getElementById("unitno").value=json.UNIT_ID;
					document.getElementById("unitname").value=json.UNIT_NAME;
     
                    //document.getElementById("farmermob").value=json.MOB;
					var str=json.MOB;
					var mobileno= 'XXXXXX'+str.substring(6,10);
					document.getElementById("farmermobno").value=mobileno; 
					document.getElementById("farmermob").value=json.MOB;
					
					if(json.CARD_PIN!='0')
					{
					document.getElementById("farmercardpin").value='XXXXXX';//json.CARD_PIN;
					}
					else
					{
						document.getElementById("farmercardpin").value='NA';
					}
                    document.getElementById("qrimage").value=json.CARD_SERIAL_NUMBER;
                    document.getElementById("growerid").value=json.GROWER_ID;
					
                   // document.getElementById("deliverydate").value=json.CARD_SERIAL_NUMBER;
                    //document.getElementById("dispatchdate").value=json.GROWER_ID;
                    $("#deliverydate").html(json.DELIVERY_DATE);
					$("#dipachdate").html(json.DISPATCH_DATE);
					
                    document.getElementById("village").value=json.VILLAGE_CODE;
					document.getElementById("input-village_name").value=json.VILLAGE_NAME;
                    document.getElementById("card_id").value=json.SID;
                    
                    document.getElementById("cardstatus").value=json.CARD_STATUS_DESC;
                    
                    document.getElementById("cardserialno").value=json.CARD_SERIAL_NUMBER;
					
					var json_obj = json.cardhistory;
					$('#history_tbody').html('');
					for (i=0; i < json_obj.length; i++)
					{
						var payment = json_obj[i];
						//alert(JSON.stringify(json_obj[i]['statusdate']));
						//alert(JSON.stringify(json_obj[i]['CARD_STATUS_NAME']));
						$('#history_tbody').append('<tr><td>'+json_obj[i]['CARD_STATUS_NAME']+'</td><td>'+json_obj[i]['statusdate']+'</td></tr>');
					}
					
					
					var json_obj = json.cardtranshistory;
					//alert((json_obj));
					var r=1;
					var tagged=0;
					var cash=0;
					$('#transhistory_tbody').html('');
					//alert(JSON.stringify(json_obj));
					for (i=0; i < json_obj.length; i++)
					{
						tagged=0;
						cash=0;
						
						//alert(i);
						var payment = json_obj[i];
					    tagged=parseFloat(json_obj[i]['tagged']);
						cash=parseFloat(json_obj[i]['cash']);
						//alert(JSON.stringify(json_obj[i]['statusdate']));
						//alert(JSON.stringify(json_obj[i]['CARD_STATUS_NAME']));
						console.log(r);
						$('#transhistory_tbody').append('<tr><td>'+r+'</td><td>'+json_obj[i]['storename']+'</td><td>'+json_obj[i]['datea']+'</td><td  data-toggle="modal" data-target="#myModal2" onclick="productdtl('+json_obj[i]['order_id']+');"><a style="cursor: pointer; " >'+json_obj[i]['order_id']+'</a></td><td>'+tagged+'</td><td>'+cash+'</td></tr>');
					r=r+1;
					}
					
					 
					
	
	                
       
			},
                error:function (json){
                    alert(JSON.stringify( json));
					
                }
                
	});
    
    }
 function productdtl(orderid)
 {
	// alert(orderid);   
	 $.ajax({
		url: 'index.php?route=farmerrequest/carddetail/productdtlbyorderid&token=<?php echo $token; ?>&orderid=' +  encodeURIComponent(orderid),
		dataType: 'json',			
		success: function(json) {
                //alert(JSON.stringify(json));
				
             var json_obj = json;
			 var r=1;
			 var prd_total=0;
			 $('#productdtl_body').html('');
					for (i=0; i < json_obj.length; i++)
					{
						prd_total=0;
						
						//alert(JSON.stringify(json_obj[i]['name']));
						 prd_total=parseFloat(json_obj[i]['price'])+parseFloat(json_obj[i]['tax']);
						var  price=parseFloat(json_obj[i]['price']).toFixed(2);
						var  tax=parseFloat(json_obj[i]['tax']).toFixed(2);
						$('#productdtl_body').append('<tr><td>'+r+'</td><td>'+json_obj[i]['name']+'</td><td>'+json_obj[i]['quantity']+'</td><td>'+price+'</td><td>'+tax+'</td><td>'+prd_total+'</td></tr>');
					r=r+1;
					}
					
		
	        },
                error:function (json){
                    //alert(JSON.stringify( json));
                }
                
	});
	 
 }
 
 function reloadpage()
 {
     location.reload();
 }
 function blockedbtn()
 { //alert("bnvhjd");
    var grower_id = document.getElementById('growerid').value;
    var mobile = document.getElementById('input-mobile').value;
      $.ajax({
		url: 'index.php?route=farmerrequest/farmerrequest/blocked&token=<?php echo $token; ?>&grower_id=' +  encodeURIComponent(grower_id)+"&mobile="+encodeURIComponent(mobile)+'&unitid='+encodeURIComponent("2"),
		dataType: 'json',			
		success: function(json) {
                 //alert(JSON.stringify(json));
                  
              location.reload();
		
	        },
                error:function (json){
                    //alert(JSON.stringify( json));
                }
                
	});
 }
  function rejectstusremove()
 {  //alert("bnvhjd");
    var grower_id = document.getElementById('growerid').value;
    //alert(grower_id);
   // var mobile = document.getElementById('input-mobile').value;
      $.ajax({
		url: 'index.php?route=farmerrequest/farmerrequest/rejectstatusremove&token=<?php echo $token; ?>&grower_id=' +  encodeURIComponent(grower_id),
		dataType: 'json',			
		success: function(json) {
                 //alert(JSON.stringify(json));
                  
              location.reload();
		
	        },
                error:function (json){
                    //alert(JSON.stringify( json));
                }
                
	});
 }
 
 
 /*function submitdtl()
 {
    
    var grower_id = document.getElementById('growerid').value;
    var mobile = document.getElementById('mobileno').value;
    var firstname = document.getElementById('fname').value;
    var lastname = document.getElementById('lname').value;
    var add = document.getElementById('address').value; 
    var zoneid = document.getElementById('zone').value; 
    var cir = document.getElementById('circle').value; 
    var fathernam = document.getElementById('fathername').value;
    var addharnum = document.getElementById('addharno').value;
    var unitid = document.getElementById('unitno').value;
    var idnum = document.getElementById('zone').value;  
    var anotherpr= document.getElementById('anotherproof').value;
    try{
     $.ajax({
		url: 'index.php?route=farmerrequest/farmerrequest/adddetail&token=<?php echo $token; ?>&grower_id=' +  encodeURIComponent(grower_id)+'&unitid='+encodeURIComponent(unitid)+'mobile='+encodeURIComponent(mobile)+'firstname='+encodeURIComponent(firstname)+'lastname='+encodeURIComponent(lastname)+'add='+encodeURIComponent(add)+'zoneid='+encodeURIComponent(zoneid)+'cir='+encodeURIComponent(cir)+'fathernam='+encodeURIComponent(fathernam)+'addharnum='+encodeURIComponent(addharnum)+'idnum='+encodeURIComponent(idnum)+'anotherpr='+encodeURIComponent(anotherpr),
		dataType: 'json',			
		success: function(json) {
                 alert(JSON.stringify(json));
		
			},
                error:function (json){
                   // alert(JSON.stringify( json));
                }
                
	});
        }
        catch(e)
        {
        alert(e);
        }
    

    
 }*/
 </script>     
      <?php echo $footer; ?>