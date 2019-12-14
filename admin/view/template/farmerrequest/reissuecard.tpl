<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid"> 
      <h1>Reissue  Card</h1>
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
        <h3 class="panel-title"><i class="fa fa-list"></i>Grower Details</h3>
       
      </div>
           <div class="row">
          <div class="col-lg-6 col-sm-6 col-xs-12">
           
            <div class="widget">
                <div class="widget-header bordered-bottom bordered-lightred">
                    
                </div><br/>
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
                                <label for="inputEmail3" class="col-sm-3 control-label no-padding-right">Grower ID</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="grower_id" value="" placeholder="Grower ID " id="input-grower_id" class="form-control" />
                                      
                                    </div>
                            </div>
                              <div class="form-group">
<label for="" class="col-sm-3 control-label no-padding-right">Village ID</label>
<div class="col-sm-9">
<input type="text" name="village_id" value="" placeholder=" Village ID " id="input-village_id" class="form-control" />

</div>
</div>                      
                                                        
                                                    
                            <div class="form-group ">
                                <div class="col-sm-offset-2 col-sm-10 ">
                                     <span><i id="gif" class="fa fa-spinner fa-spin fa-3x fa-fw pull-right" style="font-size:24px; display:none"></i>
                                         <button type="button" id="button-filter" class="btn btn-primary pull-right" onclick="chekgrowerid();" >Submit</button></span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>  
              <div class="col-lg-12 ">
           <style>
    .form-group {
    margin-bottom: 5px !important;}
    
</style>
            <div class="widget" id="statusdiv" style="display:none">
                <div class="widget-header bordered-bottom bordered-lightred">
                    <span class="widget-caption">The current status of card is :</span>
                </div>
                <div class="widget-body" >
                    <div id="horizontal-form">
                        <form action="" method="post" enctype="multipart/form-data" id="form-coupon" class="form-horizontal">
                           <div class="form-group">
                                <label for="inputEmail3" class="col-sm-3 control-label no-padding-right">Status</label>
                                    <div class="col-sm-9">
                                        <input type="text" style="outline:none;" name="cardstatus"  value=""  id="cardstatus"  class="form-control" />
                                    </div>
                           </div>
                        </form>
                    </div>
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
                                                                    <input type="text" class="form-control" id="fname" name="fname" placeholder="Name" disabled="disabled">
                                                                    
                                                                    <input type="hidden" class="form-control" id="card_id" name="card_id" value="">
                                                                   
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="exampleInputName2">Mobile No</label>
                                                                <span class="input-icon icon-right">
                                                                   <input type="text" readonly="readonly" class="form-control" id="farmermobno" name="farmermobno" placeholder="Mobile">
<input type="hidden" readonly="readonly" class="form-control" id="farmermob" name="farmermob" placeholder="Mobile"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row"> 
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="exampleInputName2"> Father Name</label>
                                                                <span class="input-icon icon-right">
                                                                    <input type="text" disabled="disabled" class="form-control" id="fathername" name="fathername" placeholder="Father Name">
                                                                    
                                                                </span>
                                                            </div>
                                                        </div> 
                                                        
                                                        <!--<div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="Aadhar Card">AADHAR CARD</label>
                                                                <input type="text" class="form-control" id="addharno" name="addharno" placeholder="AADHAR CARD NO">
                                                            </div>
                                                        </div>--->
														<div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="Unit">Unit</label>
																<input type="text" disabled="disabled"  class="form-control" id="unitname" name="unitname" placeholder="Unit">
                                                                <input type="hidden" class="form-control" id="unitno" name="unitno" placeholder="Unit">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!---<div class="row"> 
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="ID Proof">Any Other ID Proof</label>
                                                                    <select class="form-control col-xs-12 col-md-12" id="anotherproof">
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
                                                    </div>-->
                                                    <div class="row"> 
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="Unit">Village</label>
                                                                <input type="hidden" class="form-control" id="village" name="village" placeholder="Village">
																<input type="text" disabled="disabled"  name="village_name" value="" placeholder=" Village Name" id="input-village_name" class="form-control" />
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
                                                                <label for="Zone">Zone</label>
                                                                <input type="text" class="form-control" id="zone" name="zone" placeholder="Zone">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="Circle">Circle</label>
                                                                <input type="text" class="form-control" id="circle" name="circle" placeholder="Circle">
                                                            </div>
                                                        </div>

                                                        

                                                   </div>
                                                 <div class="row"> 
                                                 
                                                      <div class="col-sm-6">
                                                 <label for="Address" style="color:red;">Grower ID</label>
                                                 <input type="text" class="form-control" disabled="disabled" id="growerid" name="growerid" value="">
                                                 </div>
                                                    <div class="col-sm-6" style="margin-top:20px;" >
                                                      <button style=" float:left;" id="cancelbtn"  type="button" onclick="reloadpage();" class="btn btn-red">Cancel</button>
                                                      
                                                      <button  id="cardreqst"  type="submit" id="button-filter" class="btn btn-primary pull-right"  >Reissue card</button>   
                                                 </div>
                                                  
                                                
                                                 </div> 
                                               
        
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
 
</div></div>
      
          
<script type="text/javascript"> 
 function chekgrowerid()
 {
     
     $('.alert-success').hide();
   
     //$('#gif').show(); 
     try{
    var grower_id = document.getElementById('input-grower_id').value;
    var mobile = document.getElementById('input-mobile').value;
	var village_id = document.getElementById('input-village_id').value;
    if(mobile!='')
    {
    var r= mobile.toString().length;  
    }
    else
    {
        var r=0;
    }
    //alert(r);
    
  
  if(village_id!="" && grower_id=="" )
{
alert("Please enter grower id"); 
return false;
} 
if(grower_id!="" && village_id=="")
{
alert("Enter Village ID");
return false;
}  
    
    //mobile no check
    if(r==10 || grower_id!="")
    {
    //mobile no or grower id empty check
    if(mobile=="" && grower_id=="" )
    {
        alert("Please enter mobile or grower id");
        
    }     
    else
    {
    //alert(grower_id);
     $.ajax({
		url: 'index.php?route=farmerrequest/reissuecard/check&token=<?php echo $token; ?>&grower_id=' +  encodeURIComponent(grower_id)+"&mobile="+encodeURIComponent(mobile)+'&unitid='+encodeURIComponent("2"),
		dataType: 'json',			
		success: function(json) {
               // alert(JSON.stringify(json));
               
                
               //Grower Id or mobile exist or not 
                if(json=="")
                {
                        alert("Grower Id or Mobile no does not exist");
                         location.reload();
                         //document.getElementById("detailform").style.display = "block";
                        return;
                }
                //Grower Id or mobile exist or not end
                
         //Status Div check
if(json.SITE=="MY")
{
$('#statusdiv').show();

}
else
{
$('#statusdiv').hide();
document.getElementById("cardreqst").style.display = "block";
document.getElementById("cancelbtn").style.display = "block";
} 
if(json.CARD_STATUS_DESC=='CARD ACTIVATED')
{
document.getElementById("cardreqst").style.display = "block";
document.getElementById("cancelbtn").style.display = "block";
}
else
{

document.getElementById("cardreqst").style.display = "none";
document.getElementById("cancelbtn").style.display = "none";
} 
                //Status Div End
                
		
                    document.getElementById("fname").value=json.GROWER_NAME;
                   
                    document.getElementById("fathername").value=json.FATHER_NAME;
              
                    document.getElementById("unitno").value=json.UNIT_ID;
					document.getElementById("unitname").value=json.UNIT_NAME;
					
                    var str=json.MOB;
					var mobileno= 'XXXXXX'+str.substring(6,10);
					document.getElementById("farmermobno").value=mobileno; 
					
                    document.getElementById("farmermob").value=json.MOB;
                    
                    document.getElementById("growerid").value=json.GROWER_ID;
                    
                    document.getElementById("village").value='';//json.VILLAGE_CODE;
					document.getElementById("input-village_name").value=json.VILLAGE_NAME;
                    
                    document.getElementById("card_id").value=json.SID;
                    console.log(json);
                    
                    document.getElementById("cardstatus").value=json.CARD_STATUS_DESC;
                    
                     document.getElementById("cardserialno").value=json.CARD_SERIAL_NUMBER;
                    
                    document.getElementById("detailform").style.display = "block";
                    var cno=document.getElementById("cardserialno").value=json.CARD_SERIAL_NUMBER;
                    // alert(cno);   
                    
//                    if(cno!="" && cno!="0" && cno!=null)
//                    { //alert('if');
//                     document.getElementById("issuedbtn").style.display = "block";  
//                     document.getElementById("blockbtn").style.display = "block";
//                     document.getElementById("cancelbtn").style.display = "none";
//                     document.getElementById("cardreqst").style.display = "none";
//                    }
//                    else
//                    {
//                     //alert('else');
//                    document.getElementById("issuedbtn").style.display = "none";  
//                     document.getElementById("blockbtn").style.display = "none";
//                     document.getElementById("cancelbtn").style.display = "block";
//                     if(json.CARD_STATUS==null)                     
//                     {
//                      document.getElementById("cardreqst").style.display = "block";
//                     }
//                    }
                  //After Dispaying Data Input box null validation 
                  //var mo= json.MOB.toString().length;    
                  //alert(mo);
                                 
                  
                     $('#input-grower_id').val('');
                     $('#input-mobile').val('');
					 $('#input-village_id').val('');
			},
                error:function (json){
                    //alert(JSON.stringify( json));
                }
                
	});
        
    
   
    }
    }
    else{
    alert("Please Enter 10 digit mobile number");
    location.reload();
    
    }
    }
        catch(e)
        {
        alert(e);
        }
     
    
 }
 function reloadpage()
 {
     location.reload();
 }
 function blockedbtn()
 { //alert("bnvhjd");
    var grower_id = document.getElementById('input-grower_id').value;
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