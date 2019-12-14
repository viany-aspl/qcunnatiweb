
<body>
<title>Margin</title>
<div id="content">
<div class="page-header">
    
  </div>
	<div class="panel panel-default" id = "print_div">
	
	<div class="panel-body">
		<div class="row col-lg-12" >
			<div class="col-lg-6">
			<label></b></label>
			<?php echo $order_information['order_info']['order_date']; ?>
			</div>
			
			
		</div>
		<div class="row col-lg-12" >
		
		<div class="form-group required">
            <label class="col-sm-2 control-label" for="input-name"><?php //echo $entry_warehouse; ?></label>
            <div class="col-sm-6">
			<?php
				$Y=date('Y');
				$months_id=date('m');
				$month_id=str_replace("0","",$months_id);
				$M=$month_id."-".$Y;
				
				?>
			 <select name="filter_month" id="filter_month" class="form-control " onchange="filter_month(this.value)">
				<option value=''>Select Month</option>
				<?php
				foreach($allmonth as $key=>$value){
				?>
				<option value="<?php echo $key;?>" <?php echo ($key == $M) ? 'selected' : ''; ?>><?php echo $value.', '.$Y; ?></option>
				<?php
				}
				?>
			</select>
                
            </div>
          </div>
		  
		  
		  
		</div>
		 <div class="col-sm-12 pull-left">
       <span style="font-size: 13px;"> Efective From:<?php
				 $Y=date('Y');
			      $d=01;
				echo $edate=$d.'-'.date('M').'-'.$Y;
				
				?></span>
				<span style="font-size: 13px;">Efective To:<?php
				echo $last_day_this_month  = date('t-M-Y');
				
				?>
            </span>
        </div>
			
<div id="demo"></div>

<div class="target">
		<table class="table table-bordered table-responsive col-lg-12" border="1" >
          <thead>
            <tr>
              <td class="text-left" style="width: 11.11%;"><b>Product Name</b></td>
              <td class="text-left" style="width: 11.11%;"><b>Margin</b></td>
			  
              
			</tr>
          </thead>
          <tbody>
                <?php if ($margins) { ?>
                <?php foreach ($margins as $margin) { //print_r($product); ?>
                <tr>
                  
	           <td class="text-center"><?php echo $margin['product_name']; ?></td>
			   <td class="text-center"><?php echo $margin['margin']; ?></td>
                  
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
			 
        </table>
</div>
		<div col-lg-12>
	<?php  if($status['poco_margin_acceptance']=='0') {?>
		<a href="index.php?route=mpos/pocomargin/updateacceptance&store_id=<?php echo $status['store_id'];?>"><button type="button" class="btn btn-primary pull-right" style="margin:20px 0; display:none" >Acceptance</button></a>
		<?php } ?>
		
		<div class="row">
          <div class="col-sm-6 text-left"><?php //echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php //echo $results; ?></div>
        </div>
		</div>
		
	</div>
	 
  </div>
</div>
</body>
<script type="text/javascript" src="https://unnati.world/shop/admin/view/javascript/jquery/jquery-2.1.1.min.js"></script>
<script>



function filter_month(filter_month){

//alert(filter_month);
        $.ajax({ 
        type: 'post',
        url: 'index.php?route=mpos/pocomargin/getmargin',
        data: 'filter_month='+filter_month,
        cache: false,
        success: function(data) {
         //alert(data);  
         //$("#racks_id").html(data);
		 //alert(JSON.stringify(data));
		 //alert(JSON.stringify(data[0]['margin_id']));

		 $( ".target" ).hide();
		  var s='<table class="table table-bordered table-responsive col-lg-12" border="1">';
		        s = s + " <thead>";
				s = s + " <tr>";
				s = s + " <th>Product Name</th>";
				s = s + " <th>Margin</th>";
				
				s = s + " </tr>";
				s = s + " </thead>";
			
				 s = s + " <tbody>";
				data.forEach(function(e){
				s = s + " <tr>";
				
				s = s + " <td>"+e.product_name +"</td>";
				s = s + " <td>"+e.margin +"</td>";
				s = s + " </tr>";
				})
				s = s + " </tr>";
				s = s + " </tbody>";
                s = s + " </table>";				
				document.getElementById("demo").innerHTML = s;
				
				}
				
   });
}



    function isNumber(evt)
       {
          var charCode = (evt.which) ? evt.which : evt.keyCode;
          if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57))
             return false;

          return true;
       }
    function submit_order()
    {
        var supplier_id='<?php echo $order_information['products'][0]['supplier_id']; ?>';
        var order_id='<?php echo $order_information['products'][0]['order_id']; ?>';
        var store_id='<?php echo $order_information['products'][0]['store_id']; ?>';
        var driver_mobile=$("#driver_mobile").val();
		var product_id=$("#product_id").val();
		
         var supplier_quantity=$("#supplier_quantity").val();
		 var p_quantity=$("#p_quantity").val();
		 
		if((supplier_quantity=="") || (supplier_quantity=="0"))
		{
		    alert('Please enter sent quantity');
			$("#supplier_quantity").val('');
            $("#supplier_quantity").focus();
            return false;
		}
		if(supplier_quantity>p_quantity)
		{
		    alert('Sent quantity can not be greater then requested quantity');
            $("#supplier_quantity").focus();
            return false;
		}
        //alert(store_id);
        if(driver_mobile.length<10)
        {
            alert('Please enter 10 digit mobile number');
            $("#driver_mobile").focus();
            return false;
        }
        else
        {
		 
        $("#cr_img").show();
		$("#sbmt_btn").hide();
    $.ajax({
              url: 'index.php?route=supplier/supplier/submit_order_by_supplier&supplier_id=' +  encodeURIComponent(supplier_id)+'&order_id=' +  encodeURIComponent(order_id)+'&driver_mobile=' +  encodeURIComponent(driver_mobile)+'&store_id='+store_id+'&supplier_quantity='+supplier_quantity+'&product_id='+product_id,
              // dataType: 'json',
               success: function(json) 
               {
					//alert(json);
					 $("#cr_img").hide();
					 $("#driver_mobile").prop("readonly", true);
					 $("#supplier_quantity").prop("readonly", true);
                   alert('Successfully submited');
                  
                   //alert(json);
                   //var json2=json.split('----and----');
                   //$("#tab-order").html(json2[0]);
                   
               }
                       
              });
    return false;  
    }
    }
	
	
	
	 <!--
$('#button-filter').on('click', function() {
	var url = 'index.php?route=mpos/pocomargin';



	var filter_month = $('select[name=\'filter_month\']').val();

	if (filter_month != '*') {
		url += '&filter_month=' + encodeURIComponent(filter_month);
	}
	location = url;
});

//-->
    </script>
	<style type="text/css">
	thead tr td{
		font-weight:bold;
		font-size:10px;
	}
	.table-bordered thead td {
    background: #eeeeee none repeat scroll 0 0 !important;
    padding: 5px !important;
    text-align: center !important;
	border-bottom:2px solid #cccccc !important;
}
.table-responsive tbody td {
    padding: 5px !important;
	line-height: 1.7;
	font-size:11px;
}
.panel-heading {
    background: #eeeeee none repeat scroll 0 0;
}
.page-header{
	text-align:center;
}
table table-bordered tbody tr:nth-child(2n) {
    background-color: #eeeeee !important;
}
#content{
	
        margin-top: 50px auto;
	width:95%;
}
.header{
	width: 100%;
}
.logo{
	width: 25%;
	float:left;
	margin-top:20px !important;
}
.company{
	width: 72%;
	float:right;
	margin-top:20px !important;
}
.logo img, .company_info p{
	width:100%;
}
.company h2{
	float:right;
}
.panel-heading{
	display:none;
}
.table-bordered, .table-bordered td {
    border: 1px solid #dddddd;
	border-collapse:collapse;
}
.company_info p{
	width: 100%;
	font-weight: bold;
	margin:0px;
	font-size:11px;
	font-family:Verdana, Geneva, sans-serif;
}
.company_info p span{
	font-size:11px;
	font-weight: normal;
}
.date span{
	float:right;
}
.owner-date{
	width: 100%;
}
.owner{
	float:left;
	width: 50%;
}
.date{
	float:right;
	width: 17%;
	font-weight: normal;
}
.date span{
	float:right;
	font-size:11px;
	font-weight:normal;
}

/*mail type*/
.mail_type{
	width:100%;
}
.mail{
	float:left;
	width:50%;
}
.type{
	float:right;
	width:10%;
	font-weight:bold;
	font-size:11px;
	font-family:Verdana, Geneva, sans-serif;
}
.type span{
	float:right;
	font-size:11px;
	font-weight:normal;
}
/*mail type*/

table tr td{
	font-family:Verdana, Geneva, sans-serif;
	font-size:10px;
	text-align:center;
}
table{
	margin-top:30px;
}
.order-info{
	font-size:11px;
	font-family:Verdana, Geneva, sans-serif;
}
div.order_empty{
	width:100%;
}
.empty_div{
	float:left;
	width:50%;
}
.order-no{
	float:right;
	width:15%;
	font-weight:bold;
	font-size:11px;
}
.order-no span{
	font-family:Verdana, Geneva, sans-serif;
}
.footer{
	width:100%;
}
.address
{
	width:70%;
	float:left;
}
.pageno{
	width:4%;
	float:right;
}
.col-lg-3
{
    width:25%;
    float: left;
}
.col-lg-6
{
    width:50%;
    float: left;
}
.col-lg-12
{
    width:100%;
    float: left;
}
.form-control {
    display: block;
    width: 100%;
    height: 35px;
    padding: 8px 13px;
    font-size: 12px;
    line-height: 1.42857;
    color: #555;
    background-color: #FFF;
    background-image: none;
    border: 1px solid #CCC;
    border-radius: 3px;
    box-shadow: 0px 1px 1px rgba(0, 0, 0, 0.075) inset;
    transition: border-color 0.15s ease-in-out 0s, box-shadow 0.15s ease-in-out 0s;
}
.btn {
    display: inline-block;
    margin-bottom: 0px;
    font-weight: normal;
    text-align: center;
    vertical-align: middle;
    cursor: pointer;
    background-image: none;
    border: 1px solid transparent;
    white-space: nowrap;
    padding: 7px 13px;
    font-size: 12px;
    line-height: 1.42857;
    border-radius: 3px;
    -moz-user-select: none;
}
.btn-primary {
    color: #FFF;
    background-color: #1E91CF;
    border-color: #1978AB;
}
.pull-right {
    float: right !important;
}
</style>

