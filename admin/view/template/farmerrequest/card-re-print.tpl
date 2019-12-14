
<link rel="stylesheet" href="view/cardcss/css/reset.css" type="text/css">

    <link rel="stylesheet" href="view/cardcss/css/screen.css" type="text/css" media="screen, projection">

    <link type="text/css" rel="stylesheet" href="view/cardcss/css/jquery.rte.css" />

	<link type="text/css" rel="stylesheet" href="view/cardcss/css/style1.css" />

    <!--[if lt IE 8]><link rel="stylesheet" href="css/ie.css" type="text/css" media="screen, projection"><![endif]-->

    <!-- Import fancy-type plugin for the sample page. -->

    <link rel="stylesheet" href="view/cardcss/css/plugins/fancy-type/screen.css" type="text/css" media="screen, projection">

    <link rel="stylesheet" href="view/cardcss/css/labelgrid.css" type="text/css">



    <link type="text/css" href="view/cardcss/css/themes/base/jquery.ui.base.css" rel="stylesheet" />

    <link type="text/css" href="view/cardcss/css/themes/base/jquery.ui.core.css" rel="stylesheet" />

    <link type="text/css" href="view/cardcss/css/themes/base/jquery.ui.dialog.css" rel="stylesheet" />

    <link type="text/css" href="view/cardcss/css/themes/base/jquery.ui.button.css" rel="stylesheet" />

    <link type="text/css" href="view/cardcss/css/themes/base/jquery.ui.theme.css" rel="stylesheet" />

	<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet"> 
	
	<script type="text/javascript" src="view/cardcss/js/external/jquery-1.4.2.min.js"></script>

    <script type="text/javascript" src="view/cardcss/js/external/jquery.bgiframe.min.js"></script>

    <script type="text/javascript" src="view/cardcss/js/external/jquery.rte.js"></script>

    <script type="text/javascript" src="view/cardcss/js/external/jquery.rte.tb.js"></script>

    <script type="text/javascript" src="view/cardcss/js/external/jquery.ocupload-1.1.4.js"></script>

    <script type="text/javascript" src="view/cardcss/js/external/jquery.jstore-all-min.js"></script>  

    <script type="text/javascript" src="view/cardcss/js/external/json2.js"></script>



    <script type="text/javascript" src="view/cardcss/js/ui/jquery-ui-1.8.1.custom.js"></script>



    

    <script type="text/javascript" src="view/cardcss/js/labelgrid.min.js"></script>
	<style type="text/css">

		#createlabel { text-decoration: none; }

		#custom { text-decoration: none; }

		#create { text-decoration: none; }

		#delete { text-decoration: none; }

		#calibratelink { text-decoration: none; }

		#template { text-decoration: none; }

 		#print { text-decoration: none; }

 		#save { text-decoration: none; }

 		#load { text-decoration: none; }

		.validateTips { border: 1px solid transparent; padding: 0.3em; }



    </style>
	
<style type="text/css" media="print">

/* @page {size:7.5cm 5cm}  */  


body {
    page-break-before: avoid;
    width:100%;
    height:100%;
margin:0px;
    <!--webkit-transform: rotate(-90deg) scale(.68,.68);
    -moz-transform:rotate(-90deg) scale(.58,.58);-->}



@media print {
    body {transform: scale(.9);}
    table {page-break-inside: avoid;}
	margin:0px;
	
  #Non-printableArea{

    visibility: hidden;

  }

 
}

</style>


<?php //echo $header; ?><?php //echo $column_left; ?>

<div id="content">
  <div class="page-header">
    <div class="container-fluid"> 
      <!--<h1>Card Status</h1>--->
  </div>
  <div class="container-fluid">
   

      <div class="span-7 colborder">

	  

		 <div class="left_side">

	 

			<div class="logo_area">

				<img class="mt_10" src="view/cardcss/images/DSCL-Suger.jpg">

			</div>

			

			

			<div class="details">

				
              <label class=" small" for="name"><?php echo $farmer_name;?></label>
				<!--<label class=" small" for="name">Farmer Name</label>--->
				<div class="serial_no">
					<h5>Farmer Name</h5>
				</div>

				

				<!--<input type="text" id="name" class="input" name="name" type="text"  placeholder="Enter Father Name"></input>--->
               <label class=" small" for="name"><?php echo $father_name;?></label>
				<!--<label class=" small" for="name">Father Name</label>-->
				<div class="serial_no">
					<h5>Father Name</h5>
				</div>

				

				<!---<input type="text" id="name" class="input" name="name" type="text"  placeholder="123/2544"></input>--->
           <label class=" small" for="name"><?php echo $grower_id;?></label>
			<!--<label class="  small" for="name">Grower Id</label>--->
				<div class="serial_no">
					<h5>Grower Id</h5>
				</div>
				

				

				<!---<input type="text" id="name" class="input"name="name" type="text"  placeholder="Barabandi"></input>--->
<label class=" small" for="name"><?php echo $village;?></label>
				<!--<label class=" small" for="name">Village</label>-->
				
				<div class="serial_no">
					<h5>Village</h5>
				</div>

		

				<!---<input type="text" id="name" class="input" name="name" type="text"  placeholder="Jawaharpuhdhr"></input>--->
<label class=" small" for="name"><?php echo $unit;?></label>
				<!---<label class=" small" for="name">Unit</label>--->
				
				<div class="serial_no">
					<h5>Unit</h5>
				</div>
				

				

				<div class="serial_no">

					<h5 id="card_no_h5"><?php echo $card_number;?></h5>

				</div>

		

			</div>

		</div> 

        

				

				

		<div class="right_side">

		

			<div class="logo_icon">

				<img src="view/cardcss/images/unnati2.png">

			</div>

			

			<div class="Qr_code">
				<?php //echo $CARD_QR_IMG;//view/cardcss/images/qrcode.png ?>
				<img class="image_size" src="<?php echo $CARD_QR_IMG; ?>">

			</div>
<div class="Qr_code" id="Non-printableArea">
				<!--- <button type="button" data-toggle="modal"  id="phide"   class="btn btn-primary" onclick="printcards()">
				Print
				</button>--->

				
				
				
			</div>
		
		</div>		
		
		<div class="full_width">
			<input id="printpagebutton" type="button" value="Print" onclick="printpage()"/>
		</div>

     </div>

  
      
 </div>      
   </div>          
 </div>      

	
	<script type="text/javascript">

	var labelactive=0;

	var calibratenow=0;

	var storageready=0;

	var templatemode=1;

      var gLeft=0,gTop=0;

	var gTemplates={"Templates": [

						["Standard Address Label 10x2 (Letter)",8.5,11.00,0.156,0.5,4.0,1.00,10,2,0.189,0.0],

						["Standard Address Label 7x2 (Letter)",8.5,11.00,0.156,0.833,4.0,1.333,7,2,0.188,0.0],

						["Standard Address Label 3x2 (Letter)",8.5,11.00,0.156,0.5,4.0,3.333,3,2,0.188,0.0],

						["Standard 5x2 Business Card (Letter)",8.5,11.00,0.75,0.5,3.5,2.0,5,2,0.0,0.0],

						["Standard 5x2 Business Card (A4)",8.268,11.693,0.634,0.846,3.5,2.0,5,2,0.0,0.0],

						["Standard Letter Paper",8.5,11.00,0.0,0.0,8.5,11.00,1,1,0.0,0.0],

						["Standard A4 Paper",8.268,11.693,0.0,0.0,8.268,11.693,1,1,0.0,0.0],

						["Avery 5160 Laser Address Label 10x3 (Letter)",8.5,11.00,0.189,0.50,2.625,1.00,10,3,0.125,0.0],

						["Avery 8160 Inkjet Address Label 10x3 (Letter)",8.5,11.00,0.189,0.50,2.625,1.00,10,3,0.125,0.0],

						["Avery J8365 Address Label 4x2 (A4)",8.268,11.693,0.184,0.542,3.9,2.667,4,2,0.11,0.0],

						["Avery C2160 Address Label 7x3 (A4)",8.268,11.693,0.283,0.598,2.5,1.5,7,3,0.1,0.0]

						]};

	var gLabelIndex=0;

	var gObjectsList = new Array();

      var gObjectID = 0;

	var gObjectsListCounter = 0;

      var gSelectedObjectID ='';

	var arr =new Array();

	var gTextClick = 0;

	var browserselected='';

	var gTemplate=new Array();

	var internallabelid=0;

	var gNumberOfPages=1;		

	var gVertiSpace=0;

	var gHoriSpace=0;



	$(function() {

		jQuery.jStore.ready(function(engine){ 

		engine.ready(function(ev,engine){

			updateCalibrate();

			loadFilelist();

		});

		});

	});



	$(document).ready(function() {

                  jQuery.jStore.load();  



			LoadTemplates();

			CreateTemplateView(8.5,11.00,0.156,0.5,4.0,3.333,3,2,0.188,0.0);

			gLabelIndex=2;

			InitTemplate();

			$("#tabs").tabs();

			templatemode=1;

			gNumberOfPages=1;		



	});







    </script>
	
	<!---<style type="text/css" media="print">

 

/* @page {size:7.5cm 5cm}  */   





body {

    page-break-before: avoid;

    width:100%;

    height:100%;

	margin:0px;

    <!--webkit-transform: rotate(-90deg) scale(.68,.68); 

    -moz-transform:rotate(-90deg) scale(.58,.58);}

	

	

	

@media print {

    body {transform: scale(1);}

    table {page-break-inside: avoid;}

}



</style>----->

<script type="text/javascript">

function cardstatusdscl(data)
{
 $("#dsclcardstatus").val('');
 $('#pimage').show(); 
 var grower_id=document.getElementById('growerid').value;
 var card_number=document.getElementById('cardserialno').value;
 var unit=document.getElementById('unitno').value;
 //alert(grower_id+','+card_number+','+unit);
 url="index.php?route=farmerrequest/cardstatus/getCardStatusFromDscl&token=<?php echo $token; ?>&card_number="+card_number+"&grower_id="+grower_id+"&unit="+unit;
 //alert(url);
 $.ajax({ 
 type: 'post',
 url: url,
 
 //dataType: 'json',
 cache: false,

success: function(json) {
	$('#pimage').hide(); 
//alert(json);
if(json=='' || json=='0' || json=='false')
{
	$('#myModal2').modal('hide');
	alertify.error("Opps ! Server Error ");
	
	return false;
}
else
{
	
	if(json=='"1"')
	{
		json="CARD REQUEST";
	}
	if(json=='"2"')
	{
		json="CARD VERIFIED";
	}
	if(json=='"3"')
	{
		json="CARD APPROVED";
	}
	if(json=='"4"')
	{
		json="CARD REQUEST REJECTED";
	}
	if(json=='"5"')
	{
		json="CARD SEND PRINTING";
	}
	if(json=='"6"')
	{
		json="CARD PRINTED";
	}
	if(json=='"7"')
	{
		json="CARD DISPATCHED";
	}
	if(json=='"8"')
	{
		json="CARD RECIEVED GROWER";
	}
	if(json=='"9"')
	{
		json="CARD ACTIVATED";
	}
	if(json=='"10"')
	{
		json="CARD LOST DAMAGED";
	}
	if(json=='"11"')
	{
		json="CARD DEACTIVATED";
	}
	if(json=='"12"')
	{
		json="CARD REISSUE";
	}
	//alert(json);
 $("#dsclcardstatus").val(json);
} 
 }
 
 });
	
}

function printpage() 
{ 
	var card_no_h5=$("#card_no_h5").html();
	//alert(card_no_h5);
	card_no_h5=card_no_h5.trim();
	$.ajax({ 
			type: 'post',
			url: 'index.php?route=farmerrequest/managercardprint/reprintcardupdatestatus&token=<?php echo $token; ?>&CardSerialNo='+card_no_h5,
			cache: false,
			success: function(data) 
			{
				//Get the print button and put it into a variable
				var printButton = document.getElementById("printpagebutton");
				//Set the print button visibility to 'hidden' 
				printButton.style.visibility = 'hidden';
				//Print the page content
				window.print()
				printButton.style.visibility = 'visible';
			},
			error: function(data)
			{
				alert('Oops Some error occur. Please try again');
			}
		});
        
    }

function qrimagedelete()
{
var cardno = document.getElementById('qimage').textContent;
//alert(cardno);
 $.ajax({ 
 type: 'post',
 url: 'index.php?route=farmerrequest/cardprinted/deleteqr&token=<?php echo $token; ?>&CardSerialNo='+cardno,

 cache: false,

success: function(data) {
//alert(data);

 }

 });
 

}






  function cardview()
{
$('#pimage').show(); 
var card_number=document.getElementById('cardserialno').value;
var CARD_QR_IMG =document.getElementById('qrimage').value;
	//alert(card_number);
	//alert(CARD_QR_IMG);
	if(CARD_QR_IMG)
	{
		$.ajax({ 
		 type: 'post',
		 url: 'index.php?route=farmerrequest/cardstatus/deleteqr&token=<?php echo $token; ?>&CardSerialNo='+card_number,

		 cache: false,

		success: function(data) {
		//alert(data);

		 }

		 });	
	}
	$.ajax({ 
 type: 'post',
 url: 'index.php?route=farmerrequest/cardstatus/generateqr&token=<?php echo $token; ?>&CardSerialNo='+card_number,
 //data: 'CardSerialNo='+CardSerialNo,
 //dataType: 'json',
 cache: false,

success: function(data) {
	var printurl='index.php?route=farmerrequest/cardstatus/cardviewprint&token=<?php echo $token; ?>'; 
	$('#pimage').hide(); 
	 CARD_QR_IMG=data;
var farmer_name=document.getElementById('fname').value;
var father_name=document.getElementById('fathername').value;
var village=document.getElementById('input-village_name').value;
var unit=document.getElementById('unitname').value;
var grower_id=document.getElementById('growerid').value;
//var card_number=document.getElementById('cardserialno').value;
	


//alert(fathername);
var btn_html_data=$("#span_"+grower_id).html();
$("#btn_html").html(btn_html_data);
printurl=printurl+'&btn_html='+btn_html_data;
//alert(father_name);
$("#Grower_Name_level").html(farmer_name);
printurl=printurl+'&farmer_name='+farmer_name;

$("#Father_Name_level").html(father_name);
printurl=printurl+'&father_name='+father_name;

 $("#qimage").html(CARD_QR_IMG);
 printurl=printurl+'&qimage='+CARD_QR_IMG;
 
$("#cname").attr("src","view/image/DSCL.png");
printurl=printurl+'&cname=view/image/DSCL.png';

$("#Village_level").html(village);
printurl=printurl+'&Village_level='+village;

$("#Unit_level").html(unit);
printurl=printurl+'&Unit_level='+unit;

$("#Grower_Code_level").html(grower_id);
printurl=printurl+'&Grower_Code_level='+grower_id;

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
printurl=printurl+'&Card_Serial_Number_level='+cardno;

//alert(CARD_QR_IMG);
//$("#qr_img_div").html('<img src=');
printurl=printurl+'&qr_img='+CARD_QR_IMG;

if(CARD_QR_IMG!="")
{
$("#qr_img").attr("src",CARD_QR_IMG);
}
else
{
$("#qr_img").attr("src","../system/upload/defaultqrimage.png");
}


//alert(printurl);
}
});
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
		url: 'index.php?route=farmerrequest/cardstatus/check&token=<?php echo $token; ?>&grower_id=' +  encodeURIComponent(grower_id)+"&mobile="+encodeURIComponent(mobile)+'&unit_id='+encodeURIComponent(unit_id)+'&company_id='+encodeURIComponent(company_id)+'&card_serial_no='+encodeURIComponent(card_serial_no),
		dataType: 'json',
			
		success: function(json) {
            // alert(JSON.stringify(json));
                $('#statusdiv').show(); 
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
                    
                    document.getElementById("village").value=json.VILLAGE_CODE;
					document.getElementById("input-village_name").value=json.VILLAGE_NAME;
                    document.getElementById("card_id").value=json.SID;
                    
                    document.getElementById("cardstatus").value=json.CARD_STATUS_DESC;
                    
                     document.getElementById("cardserialno").value=json.CARD_SERIAL_NUMBER;
                    
       
			},
                error:function (json){
                    alert(JSON.stringify( json));
					
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
      <?php //echo $footer; ?>