<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">
   <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        </div>
      <h2>Tag POS</h2>
      
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
        <h3 class="panel-title"><i class="fa fa-search"></i> Search Indent</h3>
      </div>
      <div class="panel-body" style="min-height: 345px;">
        
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-meta-title" style="margin-top: 10px;">Indent Number</label>
                <div class="col-sm-6">
                    <input type="text" style="margin-top: 5px;" name="indent_number" value="" placeholder="Indent Number" id="indent_number" class="form-control"  required="required" />
                  <?php if ($error_meta_title) { ?>
                  <div class="text-danger"><?php echo $error_meta_title; ?></div>
                  <?php } ?>
                </div>
	<div class="col-sm-4">
						<img id="search_img" src="view/image/processing_image.gif" style="height: 48px;width: 48px;display: none;" />
                    <input id="search_btn" type="button" style="margin-top: 5px;" name="indent_search" value="Seacrh" onclick="return search_indent();" class="btn btn-primary"   />
                  
                </div>
            </div>
           
       
      <br/> <br/>
        <div class="table-responsive">

<select name="filter_fm" id="input-fm" class="form-control"  style="display: none;">
                         <option value="" >Field Motivator</option>
			<?php foreach($fmlist as $fm) {  ?>
				<option value="<?php echo $fm['id']; ?>" ><?php echo $fm['name']; ?></option> 
			<?php } ?>
			
                </select>
<br/>
          <table class="table table-bordered" id="tbl_header_main" style="display: none;">
            <thead>
              <tr>
                <td class="text-left">ORDER NUMBER</td>
	  <td class="text-left">GROWER MOBILE NUMBER</td>
                <td class="text-left">GROWER NAME</td>
                <td class="text-left">TOTAL</td>	 
              </tr>
            </thead>
            <tbody>
	<tr>                
	  <td class="text-left" id="header_order_id"></td>             
                <td class="text-left"  id="header_mobile_number"></td>
                <td class="text-left" id="header_name"></td>
                <td class="text-left" id="header_order_total"></td>	             
              </tr>
			  
			  </tbody>
          </table>
		  
		  <table class="table table-bordered" id="tbl_detail_main" style="display: none;">
            <thead>
              <tr>
                <td class="text-left">PRODUCT NAME</td>
	<td class="text-left">QUANTITY</td>
	<td class="text-left">PRODUCT PRICE</td>
	<td class="text-left">TAX</td>
                
                <td class="text-left">TOTAL</td>
	 
              </tr>
            </thead>
            <tbody id="detail_body">
			
			  
			 
			  
			  </tbody>
          </table>

	<table class="table table-bordered" id="tbl_total_main" style="display: none;">
            <thead>
               <tr>
                <td class="text-left">SUB TOTAL : <span id="det_sub_total"> </span></td>             
                <td class="text-left">TOTAL TAX :  <span id="det_tax"> </span></td>
                <td class="text-left">TAGGED AMOUNT :  <span id="det_tagged"> </span></td>
                <td class="text-left">TOTAL :  <span id="det_total"> </span></td>
	  
              </tr>
            </thead>
</table>
					<img id="submit_img" src="view/image/processing_image.gif" style="height: 48px;width: 48px;display: none;float: right;" />
                  <input id="submit_btn" type="button" style="margin-top: 5px;float: right;display: none;" name="indent_submit" value="Pay Now" onclick="return submit_order();" class="btn btn-primary"   />
                  
        </div>
         
      
		</div>
    </div>
  </div>
  <input type="hidden" id="farmer_name" value="" />
  <input type="hidden" id="father_name" value="" />
  <input type="hidden" id="customer_mob" value="" />
  
  <input type="hidden" id="G_Code" value="" />
  <input type="hidden" id="village_name" value="" />
  <input type="hidden" id="comment" value="" />
  <input type="hidden" id="totp" value="" />
  <input type="hidden" id="order_total" value="" />
  <input type="hidden" id="allow_tagged_total" value="" />
  <input type="hidden" id="glimit" value="" />
  <textarea style="display: none;" name="prd_dtl" id="prd_dtl"></textarea>
  
  <script type="text/javascript" src="view/javascript/pos/print/printThis.js"></script>
  
  
  <div id="" class="for_print" style="display: none;" >
  <div class="scrollbar_wrapper" id="scrollbar2" style="">  
              <div class="scrollbar">
                <div class="track">
                    <div class="thumb">
                        <div class="end"></div>
                    </div>
                </div>
              </div>
             
              <div class="viewport">
                  <div class="overview" style="padding: 0px 4px;">  
                    <div class="order_head">
			<div class="">
			<h1 style="text-align: center;">UNNATI</h1>
		<h4 style="text-align: center; margin:0px; padding:0px;line-height:0px;">Farmer Copy</h4>
		<hr />
		<?php echo $store_address; ?>

			</div>

                      
					  <hr />
					  Retail Invoice 
					  <hr />
					  <div class="order_id pull-left">
                          Date - <span id="prnt_dt"></span>
                      </div>
					  <br />
					  <div class="order_id pull-left">
                          Invoice Number - <span id="prnt_invoice"></span>
                      </div>
					   <br />
                      <div class="order_id pull-left">
                          Order ID - <span id="prnt_oid"></span>
                      </div>
		<br />
                      <div class="order_id pull-left">
                         Refrence Number - <span id="prnt_ref_n"></span>
                      </div>

					   <br />
					  <div class="order_id pull-left">
                          Retail OP - <span id="prnt_retailop"><?php echo $store_op_name; ?></span>
                      </div>
					   <br />
					  <div class="order_id pull-left">
                          Office Name - <span id="prnt_officename"><?php echo $store_name; ?></span>
                      </div>
					   <br />
					  <div class="order_id pull-left">
                          GSTN -  <span id="prnt_gstn"><?php echo $store_gstn; ?></span>
                      </div>
					   <br />
					  <div class="order_id pull-left">
                          Customer Mobile -  <span id="prnt_cmobile"></span>
                      </div>
					   <br />
					  <div class="order_id pull-left">
                          Customer ID - <span id="prnt_cid"></span>
                      </div>
					   <br />
					  <div class="order_id pull-left">
                          Farmer Name - <span id="prnt_fname"></span>
                      </div>
					   <br />
					  <div class="order_id pull-left">
                          Village Name -  <span id="prnt_vname"></span>
                      </div>
					   <br />
					  <div class="order_id pull-left">
                          Delivery Type -  <span id="prnt_deltype">Field Delivery</span>
                      </div>
					   <br />
					  <div class="order_id pull-left">
                          Field Motivator - <span id="prnt_fm"></span>
                      </div>
					   <br />
					  <div class="order_id pull-left">
                          Payment Mode -  <span id="prnt_pm">Tagged</span>
                      </div>
					   <br />
                      <div class="clear"></div>
                      
                      <hr />
                    </div>  
                      <hr />
                    <table class='table table-bordered cart_table' style="font-size: 90%;">
                        <thead style="border: 2px solid black;">
                          <tr style="border: 2px solid black;">
								<th style="border-right: 2px solid black;">S.N. </th>
                            <th style="border-right: 2px solid black;">PRODUCT NAME  </th>
                            <th style="border-right: 2px solid black;">QTY  </th>
                            <th style="border-right: 2px solid black;">RATE  </th>
                            <th>AMOUNT </th>
                           
                          </tr>  
                        </thead>  
                        <tbody id="print_tbl_prd">
                        
                        </tbody>
							
                    </table>
					<hr/><br />
					<table class='table table-bordered cart_table' style="font-size: 90%;">
                        
                        <tbody>
                        
                       
							<tr>
								
                          
                            <td style="text-align: right;">Sub Total : <span id="prnt_sb_total">34785347858</span></td>
                           
                           
                         </tr>
							<tr>
								
                          
                            <td style="text-align: right;">Tax : <span id="prnt_total_tax">457665756</span></td>
                           
                         </tr>
						  </tbody>
                    </table>
					<hr/><br />
					<div class="stor_logo pull-left"  >
                         
					Tax Description <br />
					<span id="print_tax_desc"></span>
                  </div>
					  
					<br /><br />
                  <div style="text-align: center;">
							<h1 style="font-size: 17px;">TOTAL AMOUNT (Rs.) - 
								<span id="print_order_total"></span>
								</h1>
					</div> 
			
                    
					<br /><br />
					<div class="stor_logo pull-left">
                          
					* Tagged Amount : <span id="print_tagged_amount"></span><br />
					* Cash Amount : 0
					<br /><br />
                  </div>
					  <br /><br />
					<div class="stor_logo pull-left"  >
                      <div style="text-align: center;">Disclaimer</div> 
					  
						* Goods once sold will not be returned.<br />
						* This invoice can't be used for Tax Credit Input.
						<br /><br />
                  </div>
					<br /><br /><br />
					<div class="stor_logo pull-center" style="text-align: center;" >
                          
						(Akshamaala Solutions Pvt. Ltd.)<br />
						CIN: U72200DL2010PTC209266<br />
						For any queries call - 0120 4040160<br />
						Website - www.unnati.world<br />
						// Have a good Crop //
						<br />
                  </div>
               </div>
              </div>
            </div>
  
  </div>
  
<script type="text/javascript">
	//print();
	function print() {   
		//alert('kk');
		//scrollbar_wrapper
		//$(".order_head,.cart_table, .total_wrapper").printThis({
      $(".scrollbar_wrapper").printThis({
       debug: false, // show the iframe for debugging
       importCSS: true, // import parent page css
       printContainer: true, // print outer container/$.selector
       loadCSS: "view/javascript/pos/print/print.css", // load an additional css file
       pageTitle: "INVOICE", // add title to print page
       removeInline: false, // remove all inline styles
       cleardata: true
   });
	}
	function submit_order()
	{
		
		//$("#submit_btn").hide();
		//$("#submit_img").show();
		
		var farmer_name=$("#farmer_name").val();
		var father_name=$("#father_name").val();
		var customer_mob=$("#customer_mob").val();
		
		var G_Code=$("#G_Code").val();
		var village_name=$("#village_name").val();
		var comment=$("#comment").val();
		var totp=$("#totp").val();
		var fmname=$("#input-fm option:selected").text();
		
		var fmcode=$("#input-fm option:selected").val();
		var prd_dtl=$("#prd_dtl").html();
		var order_total=parseFloat($("#order_total").val());
		var allow_tagged_total=parseFloat($("#allow_tagged_total").val());
		var glimit=	$("#glimit").val();
		//alert(Math.round(1656.4));
		//alert(parseFloat(order_total)+' '+parseFloat(allow_tagged_total));
		//alert(Math.round(parseFloat(order_total)));
		//alert(Math.round(parseFloat(allow_tagged_total)));
		if(Math.round(parseFloat(order_total))>Math.round(parseFloat(allow_tagged_total)))
		{
			alert('Allow Tagged amount is less then Order Total');
			alertify.error('Allow Tagged amount is less then Order Total');
			return false;
		}
		//return false;
		//alert(prd_dtl); 
		if(fmcode=='')
		{
			alert('Please select Field Motivator');
			alertify.error('Please select Field Motivator');
			return false;
		}
		if(fmcode!='')
		{
		$.ajax({
		url: 'index.php?route=tagpos/tagpos/submit_order&token=<?php echo $token; ?>',
		dataType: 'json',
		method: 'POST',
		data: {prd_dtl:encodeURIComponent(prd_dtl),order_total:encodeURIComponent(order_total),glimit:encodeURIComponent(glimit),farmer_name:encodeURIComponent(farmer_name),father_name:encodeURIComponent(father_name),customer_mob:encodeURIComponent(customer_mob),G_Code:encodeURIComponent(G_Code),village_name:encodeURIComponent(village_name),comment:encodeURIComponent(comment),fmname:encodeURIComponent(fmname),fmcode:encodeURIComponent(fmcode),totp:encodeURIComponent(totp)}, 
		beforeSend: function() {
			$("#submit_btn").hide();
		    $("#submit_img").show();
		
		},
		complete: function() {
			
		},
		success: function(html) {
			//alert(JSON.stringify(html));
			if(html['success']=='')
			{
				alert(html['error']);
				alertify.error(html['error']);
				$("#submit_btn").show();
		    		$("#submit_img").hide();
				return false;
			}
			else
			{
				$("#submit_btn").hide();
				$("#submit_img").hide();
				$("#prnt_dt").html(html['orddate']);
				$("#prnt_invoice").html(html['invoice_no']);
				var indent_number=$("#indent_number").val();
				$("#prnt_oid").html(indent_number);
				$("#prnt_ref_n").html(html['order_id']);
				$("#prnt_fm").html(fmname);
				//alert(html['tax_return']);
				$("#print_tax_desc").html(html['gtax']);
				
				$("#submit_btn").hide();
		    		$("#submit_img").hide();
					
				$("#input-fm").hide();
				$("#tbl_header_main").hide();
				$("#tbl_detail_main").hide();
				$("#tbl_total_main").hide();

				alert(html['success']);
				alertify.success(html['success']);
				print();
				return false;
			}
			//alert(html);
			
		},
		error: function(xhr, ajaxOptions, thrownError) {

			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			alertify.error(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
		}	
		
		return false;
	}

  function search_indent()
  {
	  var indent_number=$("#indent_number").val();
	  if(indent_number!='')
	  {
	try{
	  $.ajax({
		url: 'index.php?route=tagpos/tagpos/search_indent&token=<?php echo $token; ?>&indent_number=' + encodeURIComponent(indent_number),
		dataType: 'json',
		beforeSend: function() {
			$("#search_btn").hide();
			$("#search_img").show();
			$("#submit_img").hide();
			$("#submit_btn").hide();
			$("#detail_body").html('');
			$("#prd_dtl").html('');
			$("#print_tbl_prd").html('');
			
			$("#input-fm").hide();
			$("#tbl_header_main").hide();
			$("#tbl_detail_main").hide();
			$("#tbl_total_main").hide();
			$('#input-fm').val('');
		},
		complete: function() {
			$("#search_btn").show();
			$("#search_img").hide();
			
		},
		success: function(html) {
			//alert(JSON.stringify(html));
			//return false;
			
			if((html['error']!='') && (html['error']!=undefined))
			{
				alert(html['error']);
				alertify.error(html['error']);
				return false;
			}
			if(html['detail']['subtotal']=='0')
			{
				alert('Indent data mismatch');
				alertify.error('Indent data mismatch');
				return false;
			}
			//if(html['tblheader'][0]['telephone']!='')
			//{
			$("#header_order_id").html(html['tblheader'][0]['order_id']);
			$("#header_mobile_number").html(html['tblheader'][0]['telephone']);
			$("#header_name").html(html['tblheader'][0]['lastname']);
			
			var prd_array=[];
			for(var icount=0;icount<(html['detail']['products']).length;icount++ )
			{
				
				var detail_body_var='';
				detail_body_var='<tr><td class="text-left">'+html['detail']['products'][icount]['name']+'</td><td class="text-left">'+html['detail']['products'][icount]['quantity']+'</td><td class="text-left">'+html['detail']['products'][icount]['price']+'</td><td class="text-left">'+html['detail']['products'][icount]['tax']+'</td><td class="text-left">'+html['detail']['products'][icount]['product_total']+'</td></tr>';
				$("#detail_body").append(detail_body_var);
				
				
				
				var obj=new Object();
				obj.product_tax=html['detail']['products'][icount]['tax'];
				obj.product_quantity=html['detail']['products'][icount]['quantity'];
				obj.product_id=html['detail']['products'][icount]['product_id'];
				obj.total=html['detail']['products'][icount]['total'];
				obj.product_name=html['detail']['products'][icount]['name'];
				obj.SN=icount+1;
				obj.product_price=html['detail']['products'][icount]['price'];
				obj.product_hstn=html['detail']['products'][icount]['hstn'];
				
				prd_array.push(obj); 
				//alert(JSON.stringify(prd_array));
				var nextt = icount + 1;
				var print_tbl_prd_var='';
				print_tbl_prd_var='<tr><td class="text-left">'+nextt+'</td><td class="text-left">'+html['detail']['products'][icount]['name']+' HSN - '+html['detail']['products'][icount]['hstn']+'</td><td class="text-left">'+html['detail']['products'][icount]['quantity']+'</td><td class="text-left">'+html['detail']['products'][icount]['price']+'</td><td class="text-left">'+html['detail']['products'][icount]['product_total']+'</td></tr>';
				$("#print_tbl_prd").append(print_tbl_prd_var);
				
			}
			$("#prd_dtl").html(JSON.stringify(prd_array));
			
			$("#det_sub_total").html(html['detail']['subtotal']);
			$("#det_tax").html(html['detail']['all_product_tax']);
			$("#det_tagged").html(html['detail']['total']);
			$("#allow_tagged_total").val(html['detail']['total']);
			var subtotal = parseFloat(html['detail']['subtotal']);
			var tax = parseFloat(html['detail']['all_product_tax']);
			var ttotal = subtotal + tax; 
			ttotal=parseFloat(ttotal).toFixed(2);			
			$("#det_total").html(ttotal);
			
			/////////////////////////////
			$("#header_order_total").html(html['tblheader'][0]['total']);//html['tblheader'][0]['total']
			
			///////////////////////////////////////////////
			$("#farmer_name").val(html['detail']['fname']);
			$("#father_name").val(html['detail']['lname']);
			$("#customer_mob").val(html['tblheader'][0]['telephone']);
			
			$("#G_Code").val(html['detail']['products'][0]['G_Code']);
			$("#village_name").val(html['detail']['vname']);
			$("#comment").val(indent_number);
			$("#totp").val(html['detail']['totp']);
			$("#order_total").val(ttotal);
			$("#glimit").val(html['detail']['receivelimit']);
			//////////////////////////////////////////////////
			$("#input-fm").show();
			$("#tbl_header_main").show();
			$("#tbl_detail_main").show();
			$("#tbl_total_main").show();
			$("#submit_img").hide();
			
			$("#det_tagged").html(html['detail']['total']);
			$("#det_tagged").html(html['detail']['total']);//det_total
			//////////////add data in print div///////////////
				$("#prnt_cmobile").html(html['tblheader'][0]['telephone']);
				$("#prnt_cid").html(html['detail']['products'][0]['G_Code']);
				$("#prnt_fname").html(html['detail']['fname']);
				$("#prnt_vname").html(html['detail']['vname']);
				$("#prnt_sb_total").html(subtotal);
				$("#prnt_total_tax").html(tax);
				$("#print_order_total").html(ttotal);
				$("#print_tagged_amount").html(html['detail']['total']);
			//////////////////////////
			//print();
			if(html['detail']['total']!='0')
			{
				$("#submit_btn").show();
			}
			//}
			//else
			//{
			//	alert('No record found');
			//}
			
		},
		error: function(xhr, ajaxOptions, thrownError) {

			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			alertify.error(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
	  }
	catch(e)
	{
		alert(e);
		alertify.error(e);
		$("#search_btn").show();
		$("#search_img").hide();
		return false;
	}
	}
	
	  else
	  {
		  alert('Please enter Indent Number');
		alertify.error('Please enter Indent Number');
	  }
	  return false;
  }
  
  </script> 
  </div>
<?php echo $footer; ?>