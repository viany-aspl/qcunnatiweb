<!DOCTYPE html>
<html>
<head>
    <title><?= $storename; ?> POS</title>
    <link rel="stylesheet" href="view/stylesheet/pos/style.css">
    <link rel="stylesheet" href="view/stylesheet/pos/iconFont.css">
    <link rel="stylesheet" href="view/stylesheet/pos/metro-bootstrap.css">
    <link rel="stylesheet" href="view/stylesheet/pos/jquery.bxslider.css">
    <link rel="stylesheet" href="view/stylesheet/pos/themes/ui-lightness/jquery-ui-1.8.16.custom.css">
    <link rel="stylesheet" href="view/javascript/pos/tinyscrollbar/tinyscrollbar.css">
    <link rel="stylesheet" href="view/javascript/pos/fancybox/jquery.fancybox.css">
    <script type="text/javascript" src="view/javascript/pos/jquery.min.js"></script>
    <script type="text/javascript" src="view/javascript/pos/print/printThis.js"></script>
    <script type="text/javascript" src="view/javascript/pos/tinyscrollbar/jquery.tinyscrollbar.min.js"></script>    
    <script type="text/javascript" src="view/javascript/pos/jquery.bxslider.js"></script>
    <script type="text/javascript" src="view/javascript/pos/jquery.keyboard.min.js"></script>
    <script type="text/javascript" src="view/javascript/pos/jquery-ui-1.8.16.custom.min.js"></script>
    <script type="text/javascript" src="view/javascript/pos/jquery-ui-timepicker-addon.js"></script> 
    <script type="text/javascript" src="view/javascript/pos/fancybox/jquery.fancybox.pack.js"></script>   
    <script type="text/javascript" src="view/javascript/pos/jquery.maskedinput-1.3.js"></script>       
</head>
<body class="metro page-pos-home">
 <div class="container">    
  <div class="grid">
    <div class="row">
        
        <!--=========================== top category list ==========================-->
        
        <div class="category_container span2 bg-black">
            <span class="category_top_title">Category</span>
            <ul class="top_category_list">
                
                <?php 
                
                $color_array = array('bg-lightBlue','bg-darkViolet','bg-darkCyan','bg-violet','bg-indigo','bg-magenta');
                $i = 0;
                $length = sizeof($color_array);
                
                foreach($categories as $category){ ?>
                
                <li data-category-id="<?= $category['category_id'] ?>" class="<?= $color_array[$i] ?>">
                    <center><img style="display: none;" src="<?= $category['image'] ?>" width="70" /></center>
                    <span><?= $category['name'] ?></span>
                </li>
                
                <?php $i++; if($i == $length) $i=0; } ?>
                
            </ul>
            
        </div>        
        <!-- END .span1 -->
        
        <!---=========================  product list ============================-->
        <div class="product_container">
          <div class="search_bar_wrapper"> 
            <div class="logged pull-left">
                <div class="label info" style='display:none;'><?= $logged; ?></div>
            </div>    
            <div class="search_bar pull-right">
                <div class="input-control text size3 margin10 nrm">
                    <input type="text" placeholder="Search..." name="q" id="q" />
                    <button type="button" class="btn-search"></button>
                </div>
            </div>            
            <div class="logo pull-right">
                <h3><span style="font-size: 15px;"><?= $storename; ?> POS</span></h3>
            </div>  
            <div class='clear'></div>  
          </div>
          <!-- END .search_bar_wrapper -->          
          
          <div class="product_list">  
            <div class="scrollbar_wrapper" id="scrollbar1">  
              <div class="scrollbar">
                <div class="track">
                    <div class="thumb">
                        <div class="end"></div>
                    </div>
                </div>
              </div>
              <!-- scrollbar -->
              <div class="viewport">
                  <div class="overview" >                      
                  </div>
              </div>
            </div>  
          </div>
          <!-- END .product_list -->
          
          <div class="product_pager hide">
              <button class="button info large pull-right">Load more...</button>
          </div>
          <div class="footer_timer">
              <span></span>
          </div>
          <div class="clear"></div>
          <div class="product-info product_list_bottom hide">
              <input type="hidden" name="product_id" class="product_id" />
              <div id="option"></div>              
          </div>
        </div>
        <!-- END .span6 -->
        
        <div class="span7">
            <div class="top_menu_wrapper">  
            <div class="pull-left">
                <div class="balance">  
                    Cash : <?= $cash; ?><br>
                  <!--  Card : <?= $card; ?> -->
                </div>  
            </div>    
            <div class="pull-right">
              <div class="top_menu">  
                  <ul> 
                      
                      <li>
                          <a href="index.php?route=common/dashboard&token=<?= $token ?>">
                            <i class="icon-arrow-left-3"></i><br>
                            <span>Home</span> 
                          </a>                          
                      </li> 
			<!--                                               
                      <li>
                          <a href="#order_wrapper" id="order">
                            <i class="icon-dollar"></i><br>
                            <span>Place order</span> 
                          </a>                          
                      </li>  
                      -->
                       <li>
                          <a class="fancybox.ajax" href="index.php?route=pos/pos/customer&token=<?= $token ?>" id="pos_customer">
                            <i class="icon-plus-2"></i><br>
                            <span>Customer</span> 
                          </a>                          
                      </li>    
                      <li>
                          <a class="fancybox.ajax" href="index.php?route=pos/pos/orders&token=<?= $token ?>" id="order_list">
                            <i class="icon-list"></i><br>
                            <span>Order list</span> 
                          </a>                          
                      </li> 
			<!--                       
                      <li>
                          <a onclick="print_link(); return false;" href="#">
                            <i class="icon-printer"></i><br>
                            <span>Print</span> 
                          </a>                          
                      </li> 
			-->                     
                      <li>
                          <a href="index.php?route=pos/pos/index&token=<?= $token ?>">
                            <i class="icon-cycle"></i><br>
                            <span>Refresh</span> 
                          </a>                          
                      </li>                      
                      <li>
                          <a href="index.php?route=pos/pos/logout&token=<?= $token ?>">
                            <i class="icon-user-2"></i><br>
                            <span>Logout</span>
                          </a>
                      </li>
                      <!--
                      <li>
                          <a>
                            <i class="icon-keyboard"></i><br>
                            <span>Discount</span> 
                          </a>                          
                      </li>     
                      -->
                  </ul> 
              </div>
              <!-- END .top_menu --> 
            </div>
            <div class="clear"></div>     
            </div>
            <!-- END .top_menu_wrapper -->
            
            <div class='input_wrapper'>
                <input class='input-element' placeholder="Enter Barcode" type="text" name="barcode" id="barcode" />                  
                <button href="#hold_carts_wrapper" class="pull-right btn_cart_hold_count">HOLDS: <?= sizeof($hold_carts); ?></button>
                <button href="#hold_wrapper" class="pull-right btn_cart_hold_add">+ HOLD</button>
            </div>
            
            <div class="scrollbar_wrapper" id="scrollbar2">  
              <div class="scrollbar">
                <div class="track">
                    <div class="thumb">
                        <div class="end"></div>
                    </div>
                </div>
              </div>
              <!-- scrollbar -->
              <div class="viewport">
                  <div class="overview" style="padding: 0px 4px;">  
                    <div class="order_head">
			<div class="">
			<h1 style="text-align: center;">UNNATI</h1>
		<h5 style="text-align: center; margin:0px; padding:0px;line-height:0px;">A Unit of Akshamaala Solutions Pvt Ltd</h5>
		<hr />
		Address:<?= $storeadd; ?>

			</div>

                      <div class="stor_logo pull-left">
                          <?= $storename; ?><br />
			TIN: 09965726393<br />
			CIN: U72200DL2010PTC209266<br />
			Phone Number: 0120 4040160<br />
			Url: https://www.unnati.world<br />
			<?= $logged; ?>
                      </div>
                      <div class="order_id pull-right">
                          Order: Order ID
                      </div>
                      <div class="clear"></div>
                      <hr />
                      <div class="order_customer_name">Customer name</div>
                      <hr />
                    </div>  
                      
                    <table class='table table-bordered cart_table'>
                        <thead>
                          <tr>
                            <th>Name</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Tax</th>
                            <th>Total</th>
                            <th></th>
                          </tr>  
                        </thead>  
                        <tbody>
                          <!--
                           <tr>
                               <td>MacBook<br></td>
                               <td><span class="minus">-</span><span class="qty">1</span><span class="plus">+</span></td>
                               <td>$500.00</td>
                               <td>$587.50</td>
                               <td><a data-key="43::" class="cart_remove"><i class="icon-cancel-2"></i></a></td>
                           </tr>
                           -->
                        </tbody>
                    </table>
                  </div>
              </div>
            </div>
            <!-- END #scrollbar2 -->
            
            <div class="total_wrapper">
                <div class="pull-right">
                <div>
                    <b>Sub total</b><br>
                    <span id="cart-total"><?= $default_amount; ?></span>
                </div>
                <div>
                    <b>TAX</b><br>
                    <span id="cart-total"><?= $default_amount; ?></span>
                </div>
                <div>
                    <b>Order Totals</b><br>
                    <span id="cart-total"><?= $default_amount; ?></span>
                </div>		
                </div> 

            </div>
            
            
            <div class="input_wrapper discount_wrapper">
               <button id="order" href="#order_wrapper" class="command-button">
                      <center>
                        <i class="icon-dollar"></i>
                        <span>Place Order</span>
                      </center>
               </button>  
                <div class="clear"></div>
            </div>  
            
        </div>
        <!-- END .span4 -->
        
        
    </div>
  </div>   
  <!-- END .grid -->
</div>
<!-- END .page -->   

<!--========================================= hold cart list pop up ============================================-->
<div class="hide">    
<div id="hold_carts_wrapper">
    <h3>Holded Cart</h3><hr>
    <table class="table striped">
      <thead>  
        <tr>
            <th>Name</th>
            <th>Date created</th>
            <th>Action</th>
        </tr>
      </thead>  
      <tbody>
        <?php foreach($hold_carts as $cart){ ?>  
          <tr>
              <td><?= $cart['name'] ?></td>
              <td align="center"><?= $cart['date_created'] ?></td>
              <td align="center">
                  [<a data_cart_holder_id='<?= $cart["cart_holder_id"] ?>' href="#" class="select">Select</a>]&nbsp;
                  [<a data_cart_holder_id='<?= $cart["cart_holder_id"] ?>' href="#" class="delete">Delete</a>]
              </td>
          </tr>
        <?php } ?>  
      </tbody>
    </table>
</div>
<!-- END .hold_wrapper -->   

<!--========================== cart to hold pop up =========================================-->
<div id="hold_wrapper">
    <div class="hold_form">
        <h3>Put Current Cart to Hold</h3><hr>        
        <div class="message_wrapper"></div>        
        <div class="grid">
            <div class="row">                
                <div class="span4">
                    <div data-role="input-control" class="input-control text">
                        <input id="hold_name" type="text" name="hold_name" placeholder="Enter Hold Name">
                        <button id="hold_confirm" class="button">Apply</button>
                    </div>
                </div>
                <!-- END .span4 -->
            </div>
            <!-- END .row -->
        </div>
        <!-- END .grid -->
    </div>
    <!-- END .hold_form -->
</div>
<!-- END .hold_wrapper -->    
    
<!--========================== order pop up =========================================-->
<div id="order_wrapper">
    <div class="order_form">
        <h3>Place New Order</h3><hr>        
        <div class="message_wrapper"></div>        
        <div class="grid">
            <div class="row">                
                <div class="span3">
                    <span class="label2">Select Customer</span>
                </div>
                <div class="span4">
                    <div class="input-control checkbox" style="display: none;">
                        <label>
                            <input name="is_guest" value="1" type="checkbox" />
                            <span class="check"></span>
                            Guest Customer
                        </label>                                                
                        <span class="sep_or">OR</span>
                    </div>                    
                    
                    <div data-role="input-control" class="input-control text">
                        <input id="customer_name" type="text" name="customer_name" placeholder="Type and Select Customer ">
                        
                        <input type="hidden" name="customer_id" />
                        <input type="hidden" name="order_id" />
                        
                        <span>  
                            <a title="Add Customer"  href="#" id="pos_customer_cart">
                            <i class="icon-plus-2"></i><br>                            
                          </a>                          
                        </span>
                       
                    </div>
                    
                </div>
                <!-- END .span4 -->
            </div>
            <div id="popup" style="display: none;" class="row">  
                <div class="span2">
                <div data-role="input-control" class="input-control select">
                          <select name="customer_group_id" id="customer_group_id" class="form-control">
                            <?php foreach ($customer_groups as $customer_group) { ?>
                            <?php if ($customer_group['customer_group_id'] == $customer_group_id) { ?>
                            <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                          </select>
                        </div>
                </div>
                <div class="span2">
                <input  type="text" class="input-control text" name="cart_name" value="" placeholder="Name" id="cart_name"  />                        
                </div>
                <div class="span2">
                    
                    <input type="text" maxlength="10" class="input-control text"  name="cart_telephone" value="" placeholder="Telephone" id="cart_telephone"  />                        
                </div>
                <div class="span" style="margin-left:0px;">
            <input  type="text" class="input-control text" name="cart_card" value="" placeholder="Unnati Membership Card" id="cart_card"  />                        
                </div>
                <div class="span">
            <input  type="text" class="input-control text" name="cart_village" value="" placeholder="Village" id="cart_village"  />                        
                </div>
                <div class="span">
                    <img src="../../image/pos/bx_loader.gif" alt="Please wait" id="waitimage" name="waitimage" style="display: none;"/>
                </div>
            &nbsp;<button id="pos_cancel" name ="pos_cancel" class="pull-right" onclick="canceluser()" >Cancel</button> 
            
            &nbsp;<button id="pos_add" name="pos_add" class="pull-right" >Add</button> 
            
                        </div>
            <div class="row">
                <div class="span">
                    <span class="label2">Select Affiliate</span>
                </div>
                <div class="span2">
                <div data-role="input-control" class="input-control text">
                        <input id="affiliate_name" type="text" name="affiliate_name" placeholder="Type and Select Affiliate ">
                        <input type="hidden" name="affiliate_id" id="affiliate_id" />                        
                    </div>
                </div>
                
<div class="span">
                    <span class="label2">Payment method</span>
                </div>
                <div class="span2">
                    <div style="float: left !important" class="css3-metro-dropdown">
                        <select name="payment_method">
                            <option>Cash</option>                            
                        </select>
                    </div>                        
                    <span  style="display: none;float: left !important" class="label2">&nbsp;&nbsp;&nbsp;Card last 4 digits&nbsp;</span>
                    <input style="display: none;" class="mask-card" type="text" name="card" />
                </div>
            </div>
            <!-- END .row -->
            <!--<div class="row"></div>-->
                <!-- END .span4 -->
            
            <!-- END .row -->
            <div class="row">
                <div class="span">
                    <span class="label2">Calculate change</span>
                </div>
                <div class="span1">
                   <div data-role="input-control" class="input-control text">
                      <input type="text" name="paid" placeholder="paid" />                      
                   </div>                          
                </div>
                <div class="span2">
                    <span class="label2 change_amount"> - Total = 0.00</span>
                </div>
                <!-- END .span2 -->
<div class="span2">
                    <span style="display: none;" class="label2">Comment</span>
                </div>
                <div class="span2">
                   <div data-role="input-control" class="textarea">
                       <textarea name="order_comment" style="display: none;" ></textarea>
                   </div>                          
                   <button class="button" id="order_confirm">Order Now</button>                   
                </div>
            </div>
            <!-- END .row -->
        <!--    <div class="row"> </div>-->
                
                <!-- END .span4 -->
           
            <!-- END .row -->
        </div>
        <!-- END .grid --> 
    </div>
</div>
<!-- END order_wrapper -->
</div>
<!-- END .hide -->
</body>
</html>

<script type="text/javascript">

var x = new Date();

var total_hold = '<?= sizeof($hold_carts); ?>';

var total = 0;

$('input[name="paid"]').keyup(function(){    
    $.get('index.php?route=pos/pos/get_total&token=<?php echo $token; ?>',function(data){

        var paid = parseFloat($('input[name="paid"]').val()) || 0;     
        var total = data;     
        var change = (paid - total).toFixed(2);
        $('.change_amount').html('- Total = '+ change);
    });
});

//put cart to hold on 
$('#hold_carts_wrapper .select').live('click',function(){  
  $this = $(this);  
  $.post('index.php?route=pos/pos/hold_cart_select&token=<?php echo $token; ?>',{ cart_holder_id: $this.attr('data_cart_holder_id') }, function(data){
     var json = JSON.parse(data);
     
     //delete from db
     $.post('index.php?route=pos/pos/hold_cart_delete&token=<?php echo $token; ?>',{ cart_holder_id: $this.attr('data_cart_holder_id') }, function(data){
        $this.parent().parent().remove();
        $('.btn_cart_hold_count').html('HOLD: '+ --total_hold);
     });     
   
     //update cart from hold
     update_cart(json['products'], json['total_data']);      
     
     //close fancybox 
     $('.fancybox-close').trigger('click');
  });    
});

$('#hold_carts_wrapper .delete').live('click',function(){
  $this = $(this);  
  $.post('index.php?route=pos/pos/hold_cart_delete&token=<?php echo $token; ?>',{ cart_holder_id: $this.attr('data_cart_holder_id') }, function(data){
     $this.parent().parent().remove();
     $('.btn_cart_hold_count').html('HOLD: '+ --total_hold);
   });     
});




$('#hold_confirm').click(function(){
 $.post('index.php?route=pos/pos/hold_cart&token=<?php echo $token; ?>',{ name: $('#hold_name').val() }, function(data){
     var data = JSON.parse(data);
     
     if(data['error']){
         $('.message_wrapper').html('<div class="warning">'+data['error']+'</div>');
     }
     
     if(data['success']){
         $('.fancybox-close').trigger('click');
         $('#hold_carts_wrapper table tr').last().after(data['html']);
         total_hold++;
         $('.btn_cart_hold_count').html('HOLD: '+total_hold);
     }
     
     $('#hold_name').val('');
 });    
});

$(".btn_cart_hold_add").fancybox({
    maxWidth	: 370,
    maxHeight	: 420,
    autoSize	: true,
});

$(".btn_cart_hold_count").fancybox({
    maxWidth	: 470,
    maxHeight	: 420,
    autoSize	: false,
});

//autocomplete affiliate attribute name 
$("#affiliate_name").autocomplete({
    source: function(request, response) {
            $.ajax({
                    url: 'index.php?route=pos/pos/searchAffiliate&token=<?php echo $token; ?>&q=' +  encodeURIComponent(request.term),
                    dataType: 'json',
                    success: function(json) {	
                            response($.map(json, function(item) {
                                    return {
                                       label: item.firstname +' '+item.lastname,
                                       value: item.affiliate_id
                                    }
                            }));
                    }
            });
    }, 
    select: function(event, ui) {
            $('input[name=\'affiliate_name\']').attr('value', ui.item.label);
            $('input[name=\'affiliate_id\']').attr('value', ui.item.value);

            return false;
    },
    focus: function(event, ui) {
            return false;
    }
});


//autocomplete attribute name 
$("#customer_name").autocomplete({
    source: function(request, response) {
            $.ajax({
                    url: 'index.php?route=pos/pos/searchCustomer&token=<?php echo $token; ?>&q=' +  encodeURIComponent(request.term),
                    dataType: 'json',
                    success: function(json) {	
                            response($.map(json, function(item) {
                                    return {
                                       label: item.firstname +' '+item.lastname,
                                       value: item.customer_id
                                    }
                            }));
                    }
            });
    }, 
    select: function(event, ui) {
            $('input[name=\'customer_name\']').attr('value', ui.item.label);
            $('input[name=\'customer_id\']').attr('value', ui.item.value);

            return false;
    },
    focus: function(event, ui) {
            return false;
    }
});
     
$(".mask-card").mask("9999");     

$('#order').click(function(){
    $('input[name="paid"]').val('');
    $('.change_amount').html('- Total = '+ 0);
    $('.message_wrapper').html('');
    $('input[name="card"]').val('');
});

$('#order_confirm').live('click',function(){
    $(this).val('Sending data...');
    
    $.post('index.php?route=pos/pos/addOrder&token=<?php echo $token; ?>', 
      { card_no: $('.mask-card').val(),affiliate_id: $('input[name="affiliate_id"]').val(), customer_id: $('input[name="customer_id"]').val(), is_guest: $('input[name="is_guest"]').is(':checked') , payment_method: $('select[name="payment_method"]').val(), comment: $('textarea[name="order_comment"]').val() }, function(data){
         var data = JSON.parse(data);
         var html = '';
         
         if(data['errors']){
             $('.message_wrapper').html("<div class='warning'>"+data['errors']+"</div>");             
         }
         
         if(data['success']){
            //$('.message_wrapper').html("<div class='success'>"+data['success']+"</div>");             
             $('.fancybox-close').trigger('click');
            //alert('New order placed with ID: '+data.order_id);
            $('input[name=\'customer_name\']').attr('value', '');
            $('input[name=\'customer_id\']').attr('value', '');
            
            $('input[name=\'affiliate_name\']').attr('value', '');
            $('input[name=\'affiliate_id\']').attr('value', '');
            
            $('#order_confirm').val('Done');            
            $('textarea[name="order_comment"]').val('').html('');
            $('.order_head .order_id').html('Order: '+data['order_id']);
            $('.balance').html('Cash : '+data['cash']);//+'<br>Card : '+data['card']);
            $('.order_customer_name').html(data['customer_name']+"/"+data['customer_mobile']+'<span class="pull-right">'+x.toDateString() + ', ' +  x.toLocaleTimeString()+'</span>');
            print();            
         }
    });
});

$('#order_update').live('click',function(){
    $(this).val('Sending data...');
    $.post('index.php?route=pos/pos/editOrder&token=<?php echo $token; ?>', 
      { card_no: $('.mask-card').val(), order_id: $('input[name="order_id"]').val(), customer_id: $('input[name="customer_id"]').val(), is_guest: $('input[name="is_guest"]').is(':checked') , payment_method: $('select[name="payment_method"]').val(), comment: $('textarea[name="order_comment"]').val() }, function(data){
         var data = JSON.parse(data);
         var html = '';
         
         if(data['errors']){
             $('.message_wrapper').html("<div class='warning'>"+data['errors']+"</div>");             
         }
         
         if(data['success']){
            //$('.message_wrapper').html("<div class='success'>"+data['success']+"</div>");             
            $('.fancybox-close').trigger('click');
            //alert('New Order Placed with ID: '+data.order_id);
            $('#order_update').val('Done');   
            $('.order_head .order_id').html('Order: '+data['order_id']);
            $('.order_customer_name').html(data['customer_name']+'<span class="pull-right">'+x.toDateString() + ', ' +  x.toLocaleTimeString()+'</span>');
            $('.balance').html('Cash : '+data['cash']);//+'<br>Card : '+data['card']);            
            print();   
            
            //change to new order mode 
            $('textarea[name="order_comment"]').val('').html('');
            $('input[name="order_id"]').val('');
            $('#order_update').attr('id','order_confirm').html('Order Now');
            $('.order_form h3').html('Place New Order');
         }
    });
});

function cleardata(){    
    //update total 
    $html  = '<div class="pull-right"><div><b>Sub total</b><br><span id="cart-total">'; 
    $html += '<?= $default_amount; ?>';
    $html += '</span></div><div><b>Order Totals</b><br><span id="cart-total">';
    $html += '<?= $default_amount; ?>';
    $html += '</span></div></div>';    
    $('.total_wrapper').html($html);
    
    //remove order data
    $('.order_customer_name').html('Customer name');
    $('.order_head .order_id').html('Order: Order ID');

    //remove cart
    $('.cart_table tbody tr').remove();
}

var oScrollbar1, oScrollbar2 = null;

//$(".scrollbar_wrapper").tinyscrollbar();
//oScrollbar1.tinyscrollbar_update();

$(document).ready(function(){
 

 oScrollbar1 = $("#scrollbar1");
 oScrollbar1.tinyscrollbar();
 
 oScrollbar2 = $("#scrollbar2");
 oScrollbar2.tinyscrollbar();

  $('.top_category_list').bxSlider({
      mode: 'vertical',
      minSlides: 6,
      infiniteLoop: false, 
      pager: false,     
  });
});


getItems("<?= $categories[0]['category_id'] ?>",1);
    
$("#order").fancybox({
        maxWidth	: 620,
        maxHeight	: 485,
        fitToView	: false,
        width		: '70%',
        height		: '70%',
        autoSize	: false,
        closeClick	: false,
        openEffect	: 'none',
        closeEffect	: 'none'
});
 
$("#order_list").fancybox({
        maxWidth	: 720,
        maxHeight	: 620,
        fitToView	: false,
        autoSize	: true,
        closeClick	: false,
        openEffect	: 'none',
        closeEffect	: 'none'
});

$("#pos_customer").fancybox({
        maxWidth	: 720,
        maxHeight	: 620,
        fitToView	: false,
        autoSize	: true,
        closeClick	: false,
        openEffect	: 'none',
        closeEffect	: 'none'
});


//list category products 
$('.product_list .product').live('click',function(){
    
    $('.product_list .selected').removeClass('selected');
    $(this).find('.tile').addClass('selected');        
    $('.product-info .product_id').val($(this).attr('data-product-id'));        
    
    var has_option = $(this).attr('data-has-option');//getProductOptions
    
    if(has_option==1){
        $('.product_list_bottom').removeClass('hide');
        get_option($(this).attr('data-product-id'));
    }else{        
        $('.product_list_bottom').addClass('hide');
        addToCart();
    }
    
});

//
$("#pos_customer_cart").live('click',function(){
    
$("#popup").show("slow");
});
//
$("#pos_cancel").live('click',function(){
    $('.message_wrapper').html('');
 $('#cart_telephone').val('');
 
 $('#cart_village').val('');
 $('#cart_name').val('');
 $('#cart_card').val('');
$("#popup").hide("slow");
});

$("#pos_add").live('click',function(){
    
 
 if( $('#cart_telephone').val()!=='' &&  !$.isNumeric($('#cart_telephone').val()))  
 {
      $('.message_wrapper').html('<div class="warning">'+"Telephone number must be numeric"+'</div>');        
      return false;
 }
if($('#cart_telephone').val().length <10)  
 {
      $('.message_wrapper').html('<div class="warning">'+"Telephone number must be numeric 10 digit"+'</div>');        
      return false;
 }
//add customer
$("#waitimage").show("slow");
 $.post('index.php?route=pos/pos/addcustomer&token=<?php echo $token; ?>',{ firstname: $('#cart_name').val(), lastname: 'Singh' , telephone: $('#cart_telephone').val() , village: $('#cart_village').val(),customer_group_id:$('#customer_group_id').val(),card:$('#cart_card').val()}, function(data){ 
        $("#waitimage").hide("slow");
        var data = JSON.parse(data);            
     if(data['error']){
         $('.message_wrapper').html('<div class="warning">'+data['error']+'</div>');        
     }
     
     if(data['success']){
         
         $('input[name=\'customer_name\']').attr('value', $('#cart_name').val());
            $('input[name=\'customer_id\']').attr('value', data['id']);
 $('#cart_telephone').val('');
 $('#cart_village').val('');
 $('#cart_name').val('');
 $('#cart_card').val('');
 $('.message_wrapper').html('');
 $("#popup").hide("slow");
     }
     
     
 });
 });
//list category products 
$('.top_category_list li, .product_list .category').live('click',function(){
    getItems($(this).attr('data-category-id'),1);
});

//cart qty update 
$('.cart_table .minus').live('click',function(){
    $qty = $(this).parent().find('.qty');
    $qty_value = parseInt($qty.html());
    $key = $qty.attr('data-key');
    
    if($qty_value == 1) return false;
    
    $qty.html($qty_value--);
    
    $.post('index.php?route=pos/pos/updateCart&token=<?php echo $token; ?>',{ key: $key , quantity: $qty_value }, function(data){
        var json = JSON.parse(data);
        update_cart(json['products'], json['total_data']); 
    });
});

$('.cart_table .plus').live('click',function(){
    $qty = $(this).parent().find('.qty');
    $qty_value = parseInt($qty.html());
    $key = $qty.attr('data-key');
    $qty.html($qty_value++);
    
    $.post('index.php?route=pos/pos/updateCart&token=<?php echo $token; ?>',{ key: $key , quantity: $qty_value }, function(data){
        var json = JSON.parse(data);
        update_cart(json['products'], json['total_data']);        
    });

});

function get_option($id){
  $.post('index.php?route=pos/pos/getProductOptions&token=<?php echo $token; ?>',{ product_id: $id }, function(data){
    var html = '';
    var data= JSON.parse(data);
    var product_option = data['option_data'];
    
    for (var i = 0; i < product_option.length; i++) {
            var option = product_option[i];

            if (option['type'] == 'select') {
                    html += '<div id="option-' + option['product_option_id'] + '">';

                    if (option['required']) {
                            html += '<span class="required">*</span> ';
                    }

                    html += option['name'] + '<br />';
                    html += '<div class="css3-metro-dropdown">';
                    html += '<select name="option[' + option['product_option_id'] + ']">';
                    // html += '<option value=""><?php echo $text_select; ?></option>';

                    for (j = 0; j < option['option_value'].length; j++) {
                            option_value = option['option_value'][j];

                            html += '<option value="' + option_value['product_option_value_id'] + '">' + option_value['name'];

                            if (option_value['price']) {
                                    html += ' (' + option_value['price_prefix'] + option_value['price'] + ')';
                            }

                            html += '</option>';
                    }

                    html += '</select>';
                    html += '</div></div>';
                    html += '<br />';
            }

            if (option['type'] == 'radio') {
                    html += '<div id="option-' + option['product_option_id'] + '">';

                    if (option['required']) {
                            html += '<span class="required">*</span> ';
                    }

                    html += option['name'] + '<br />';
                    html += '<div class="css3-metro-dropdown">';
                    html += '<select name="option[' + option['product_option_id'] + ']">';
                    //html += '<option value=""><?php echo $text_select; ?></option>';

                    for (j = 0; j < option['option_value'].length; j++) {
                            option_value = option['option_value'][j];

                            html += '<option value="' + option_value['product_option_value_id'] + '">' + option_value['name'];

                            if (option_value['price']) {
                                    html += ' (' + option_value['price_prefix'] + option_value['price'] + ')';
                            }

                            html += '</option>';
                    }

                    html += '</select>';
                    html += '</div></div>';
                    html += '<br />';
            }

            if (option['type'] == 'checkbox') {
                    html += '<div id="option-' + option['product_option_id'] + '">';

                    if (option['required']) {
                            html += '<span class="required">*</span> ';
                    }

                    html += option['name'] + '<br />';

                    for (j = 0; j < option['option_value'].length; j++) {
                            option_value = option['option_value'][j];
                            
                            html += '<div data-role="input-control" class="input-control checkbox"><label>';
                            html += '<input type="checkbox" name="option[' + option['product_option_id'] + '][]" value="' + option_value['product_option_value_id'] + '" id="option-value-' + option_value['product_option_value_id'] + '" />';    
                            html += '<span class="check"></span>';
                            html += option_value['name'];
                            
                            if (option_value['price']) {
                                    html += ' (' + option_value['price_prefix'] + option_value['price'] + ')';
                            }
                            
                            html += '</label></div>';
                            html += '<br />';
                    }

                    html += '</div>';
                    html += '<br />';
            }

            if (option['type'] == 'image') {
                    html += '<div id="option-' + option['product_option_id'] + '">';

                    if (option['required']) {
                            html += '<span class="required">*</span> ';
                    }

                    html += option['name'] + '<br />';
                    html += '<select name="option[' + option['product_option_id'] + ']">';
                    // html += '<option value=""><?php echo $text_select; ?></option>';

                    for (j = 0; j < option['option_value'].length; j++) {
                            option_value = option['option_value'][j];

                            html += '<option value="' + option_value['product_option_value_id'] + '">' + option_value['name'];

                            if (option_value['price']) {
                                    html += ' (' + option_value['price_prefix'] + option_value['price'] + ')';
                            }

                            html += '</option>';
                    }

                    html += '</select>';
                    html += '</div>';
                    html += '<br />';
            }

            if (option['type'] == 'text') {
                    html += '<div id="option-' + option['product_option_id'] + '">';

                    if (option['required']) {
                            html += '<span class="required">*</span> ';
                    }

                    html += option['name'] + '<br />';
                    html += '<input type="text" name="option[' + option['product_option_id'] + ']" value="' + option['option_value'] + '" />';
                    html += '</div>';
                    html += '<br />';
            }

            if (option['type'] == 'textarea') {
                    html += '<div id="option-' + option['product_option_id'] + '">';

                    if (option['required']) {
                            html += '<span class="required">*</span> ';
                    }

                    html += option['name'] + '<br />';
                    html += '<textarea name="option[' + option['product_option_id'] + ']" cols="40" rows="5">' + option['option_value'] + '</textarea>';
                    html += '</div>';
                    html += '<br />';
            }


            if (option['type'] == 'date') {
                    html += '<div id="option-' + option['product_option_id'] + '">';

                    if (option['required']) {
                            html += '<span class="required">*</span> ';
                    }

                    html += option['name'] + '<br />';
                    html += '<input type="text" name="option[' + option['product_option_id'] + ']" value="' + option['option_value'] + '" class="date" />';
                    html += '</div>';
                    html += '<br />';
            }

            if (option['type'] == 'datetime') {
                    html += '<div id="option-' + option['product_option_id'] + '">';

                    if (option['required']) {
                            html += '<span class="required">*</span> ';
                    }

                    html += option['name'] + '<br />';
                    html += '<input type="text" name="option[' + option['product_option_id'] + ']" value="' + option['option_value'] + '" class="datetime" />';
                    html += '</div>';
                    html += '<br />';						
            }

            if (option['type'] == 'time') {
                    html += '<div id="option-' + option['product_option_id'] + '">';

                    if (option['required']) {
                            html += '<span class="required">*</span> ';
                    }

                    html += option['name'] + '<br />';
                    html += '<input type="text" name="option[' + option['product_option_id'] + ']" value="' + option['option_value'] + '" class="time" />';
                    html += '</div>';
                    html += '<br />';						
            }
            
    }//foreach option
    
    html += '<button onclick="addToCart();"class="button">Add to cart<span class="icon-cart on-right"></span></button>';

    $('#option').html(html);
    
    $('.date').datepicker({dateFormat: 'yy-mm-dd'});
    $('.datetime').datetimepicker({
            dateFormat: 'yy-mm-dd',
            timeFormat: 'h:m'
    });
    $('.time').timepicker({timeFormat: 'h:m'});	
                
  });//end $.post 
}

//remove items from cart   
$('.cart_remove').live('click',function(){
    removeFromCart($(this).attr('data-key'));
    $(this).parentsUntil('tbody').remove();    
    oScrollbar2.tinyscrollbar_update('top');
});

$( "#barcode" ).on( "keydown", function(event) {
  if(event.which == 13 && $(this).val().length > 0) {
     $.post('index.php?route=pos/pos/getProductByBarcode&token=<?php echo $token; ?>',{ barcode: $(this).val() }, function(data){
        
        var data = JSON.parse(data);
        
        $('.product-info .product_id').val(data.product_id);        
    
        if(data.has_option==1){
            $('.product_list_bottom').removeClass('hide');
            get_option(data.product_id);
        }else{        
            $('.product_list_bottom').addClass('hide');
            addToCart();
        }
        
        $("#barcode").val('');
        
    });
  }
});
      
$( "#q" ).on( "keydown", function(event) {
  if(event.which == 13) 
    search($(this).val(),1);
});
    
$('.btn-search').click(function(){
    search($('#q').val(),1);
});

function update_cart($products, $total_data){
    var html  = '';

    for(var i=0; i< $products.length; i++){
        html += '<tr><td>'+$products[i]['name']+'<br />';
        //option
        for(var j=0; j < $products[i]['option'].length; j++) {
            html += '- <small>'+$products[i]['option'][j]['name']+' '+ $products[i]['option'][j]['value']+ '</small><br />';
        }
        html += '</td><td class="qty"><span class="minus">-</span><span data-key="'+$products[i]['key']+'" class="qty">'+$products[i]['quantity']+'</span><span class="plus">+</span></td>';
        html += '<td>'+$products[i]['price'].slice(0,-3)+'</td>';
        html += '<td>'+$products[i]['tax'].slice(0,-3)+'</td>';
        html += '<td>'+$products[i]['total'].slice(0,-3)+'</td>';
        html += '<td><a class="cart_remove" data-key="'+$products[i]['key']+'"><i class=" icon-cancel-2"></i></a></td>';
        html += '</tr>';
    }

    $('.cart_table tbody').html(html);


    //total data
var ttax=0;
var subtotal=0;
var total=0;
	var lottax=0;
    var html = '<div class="pull-right">';
    for(var i=0; i < $total_data.length; i++){
	if($total_data[i].code=='tax')
	{
var rsstr=$total_data[i].text.substring(0,3);
		var str=($total_data[i].text.slice(3)).slice(0,-3);
		var ft=parseFloat(str);
	 lottax+=ft;

ttax = '<div><b>'+'Tax' +'</b><br><span id="cart-total">'+ rsstr+lottax+'</span></div>';

}else if($total_data[i].code=='sub_total')
 {
			
        subtotal= '<div><b>'+$total_data[i].title +'</b><br><span id="cart-total">'+$total_data[i].text.slice(0,-3)+'</span></div>';
	}
else if($total_data[i].code=='total')
 {
			
        total = '<div><b>'+$total_data[i].title +'</b><br><span id="cart-total">'+$total_data[i].text.slice(0,-3)+'</span></div>';
	}

    }
html+=subtotal;
html+=ttax;
html+=total;


    html += '</div>';
ttax=0;
    $('.total_wrapper').html(html);
}

//load next page 
$('.product_pager button').click(function(){
    $q = $(this).attr('data-q');
    $category_id = $(this).attr('data-category-id');
    $is_search = $(this).attr('data-is-search');
    $page = $(this).attr('data-page');
    
    if($is_search == 'true'){
        search($q,$page); 
    }else{
        getItems($category_id,$page);
    }
});


//autocomplete affiliate attribute name 
$("#q").autocomplete({
    source: function(request, response) {
            $.ajax({
                    url: 'index.php?route=pos/pos/searchProductsAu&token=<?php echo $token; ?>&q=' +  encodeURIComponent(request.term)+'&page=' + encodeURIComponent('1'),
                    dataType: 'json',
                    success: function(json) {	

                            response($.map(json, function(item) {

                                    return {

                                       label: item.name ,
                                       value: item.product_id
                                    }
                            }));
                    }
            });
    }, 
    select: function(event, ui) {
            $('input[name=\'q\']').attr('value', ui.item.label);
            //$('input[name=\'q\']').attr('value', ui.item.value);
	search(ui.item.label, 1);

            return false;
    },
    focus: function(event, ui) {
            return false;
    }
});



function search($q, $page){
    var html = '';
    
    //get category list
    $.post('index.php?route=pos/pos/searchProducts&token=<?php echo $token; ?>',{ q: $q, page: $page }, function(data){
        


        var data = JSON.parse(data);
        
        for(var i = 0; i < data.products.length; i++){
           html += '<div data-product-id="'+data.products[i]['id']+'"  data-has-option="'+data.products[i]['hasOptions']+'"  class="product">';
           html += '<div class="tile" data-title="'+data.products[i]['name']+'" data-price="'+data.products[i]['price_text']+'"><div class="tile-content image">';
           html += '<img style="display: none;" src="'+data.products[i]['image']+'">';
           html += '</div><div class="brand bg-dark opacity"><span class="text">';                                                   
           html += data.products[i]['name']+" "+data.products[i]['store_price_text'].slice(0, -3);
           html += '</span></div></div></div>'; 
        }
        
        $page++;
        
        if(data['has_more']){
            //set attribute 
            $button = $('.product_pager button');
            $button.attr('data-q',$q);
            $button.attr('data-category-id','');
            $button.attr('data-is-search',true);
            $button.attr('data-page',$page);
            $('.product_pager').removeClass('hide');
        }else{
            $('.product_pager').addClass('hide');
        }
        
        //check is start page 
        if($page == 2){
            $('.product_list .overview').html(html);
            oScrollbar1.tinyscrollbar_update('top');
        }else{
            $('.product_list .overview').append(html);
            oScrollbar1.tinyscrollbar_update('bottom');
        }        

    });
}

function getItems($id, $page){

    
    var html = '';

    //get category list
    $.post('index.php?route=pos/pos/getCategoryItems&token=<?php echo $token; ?>',{ category_id: $id, page: $page }, function(data){
        

        var data = JSON.parse(data);
        
        for(var i = 0; i < data.categories.length; i++){
           html += '<div data-category-id="'+data.categories[i]['id']+'" class="category"><div class="tile"><div class="tile-content image">';
           html += '<img style="display: none;" src="'+data.categories[i]['image']+'">';
           html += '</div><div class="brand bg-dark opacity"><span class="text">'; 
           html += data.categories[i]['name'];
           html += '</span></div></div></div>'; 
        }
        
        for(var i = 0; i < data.products.length; i++){
           html += '<div data-product-id="'+data.products[i]['id']+'"  data-has-option="'+data.products[i]['hasOptions']+'"  class="product">';
           html += '<div class="tile" data-title="'+data.products[i]['name']+'" data-price="'+data.products[i]['price_text']+'"><div class="tile-content image">';
           html += '<img style="display: none;" src="'+data.products[i]['image']+'">';
           html += '</div><div class="brand bg-dark opacity"><span class="text">'; 
           html += data.products[i]['name']+" "+data.products[i]['store_price_text'].slice(0, -3);
           html += '</span></div></div></div>'; 
        }
        
        $page++;
        
        if(data['has_more']){
            //set attribute 
            $button = $('.product_pager button');
            $button.attr('data-q','');
            $button.attr('data-category-id',$id);
            $button.attr('data-is-search',false);
            $button.attr('data-page',$page);
            $('.product_pager').removeClass('hide');
        }else{
            $('.product_pager').addClass('hide');
        }
        
        //check is start page 
        if($page == 2){
            $('.product_list .overview').html(html);
            oScrollbar1.tinyscrollbar_update('top');
        }else{
            $('.product_list .overview').append(html);
            oScrollbar1.tinyscrollbar_update('bottom');
        }        
    });
}

function clearCart(){    
    $.ajax({
        url: 'index.php?route=pos/pos/clearCart&token=<?php echo $token; ?>',
        type: 'post'
    });
}

function removeFromCart($key){
    $.ajax({
        url: 'index.php?route=pos/pos/removeFromCart&token=<?php echo $token; ?>',
        type: 'post',
        data: { remove: $key },
        dataType: 'json',
        success: function(json) {
            //total data
            var html = '<div class="pull-right">';
            $total_data = json['total_data'];
            for(var i=0; i < $total_data.length; i++){
                html += '<div><b>'+$total_data[i].title +'</b><br><span id="cart-total">'+$total_data[i].text+'</span></div>';
            }
            html += '</div>';
            $('.total_wrapper').html(html);
        }
    });
}
   
function addToCart(){
    $.ajax({
            url: 'index.php?route=pos/pos/addToCart&token=<?php echo $token; ?>',
            type: 'post',
            data: $('.product-info input[type=\'text\'], .product-info input[type=\'hidden\'], .product-info input[type=\'radio\']:checked, .product-info input[type=\'checkbox\']:checked, .product-info select, .product-info textarea'),
            dataType: 'json',
            success: function(json) {
                $('.success, .warning, .attention, information, .error').remove();

                if (json['error']) {
                        if (json['error']['option']) {
                                for (i in json['error']['option']) {
                                        $('#option-' + i).after('<span class="error">' + json['error']['option'][i] + '</span>');
                                }
                        }
                }

                if (json['success']) {                            
                    update_cart(json['products'], json['total_data']); 
                    
                    oScrollbar2.tinyscrollbar_update('bottom');
                    $('.total_wrapper .pull-right div').fadeOut().delay(50).fadeIn('slow');
                    $('.product_list_bottom').addClass('hide');
                }	                        
            }
        });
}

function print_link() {    
      $(".order_head,.cart_table, .total_wrapper").printThis({
       debug: false, // show the iframe for debugging
       importCSS: true, // import parent page css
       printContainer: true, // print outer container/$.selector
       //loadCSS: "view/javascript/pos/print/print.css", // load an additional css file
       pageTitle: "INVOICE", // add title to print page
       removeInline: false, // remove all inline styles
       cleardata: false
   });
}

function print() {    
      $(".order_head,.cart_table, .total_wrapper").printThis({
       debug: false, // show the iframe for debugging
       importCSS: true, // import parent page css
       printContainer: true, // print outer container/$.selector
       loadCSS: "view/javascript/pos/print/print.css", // load an additional css file
       pageTitle: "INVOICE", // add title to print page
       removeInline: false, // remove all inline styles
       cleardata: true
   });
 

 $(".order_head,.cart_table, .total_wrapper").printThis({
       debug: false, // show the iframe for debugging
       importCSS: true, // import parent page css
       printContainer: true, // print outer container/$.selector
       loadCSS: "view/javascript/pos/print/print.css", // load an additional css file
       pageTitle: "INVOICE", // add title to print page
       removeInline: false, // remove all inline styles
       cleardata: true
   });


   clearCart();
   
   return true;
}
</script> 

<script type="text/javascript"><!--
 
$('.pagination a').live('click',function(){
    get_orders($(this).attr('href'));
    return false;
}); 

$('.order_list .edit').live('click', function(){
    $.get('index.php?route=pos/pos/getOrder&order_id='+$(this).attr('data-order-id')+'&token=<?php echo $token; ?>',function(data){
        var data = JSON.parse(data);
        update_cart(data['products'], data['total_data']);
        
        //change pop up to order edit mode 
        $('.order_form h3').html('Update Order');
        $('input[name="order_id"]').val(data['order_id']);
        $('textarea[name="order_comment"]').val(data['comment']);
        $('#order_confirm').attr('id','order_update').html('Submit');
        if(data['customre']){
            $('input[name="customer_name"]').val(data['customer']['customer_name']);
            $('input[name="customer_id"]').val(data['customer']['customer_id']);
        }else{
            $('input[name="is_guest"]').prop('checked', true);
        }
        $('.fancybox-close').trigger('click');        
    });    
});

function get_orders($url){
    $.get($url, function(data){
        var data = JSON.parse(data);
        var html = '';
        
        if(data['rows'].length ==0){
            html += '<tr><td colspan="7">No order(s) found!</td></tr>';            
        }
        
        for($i = 0; $i < data['rows'].length; $i++){
            html += "<tr class='data_row'>";
            html += "<td align='right'>"+data['rows'][$i]['order_id']+"</td>";
            html += "<td>"+data['rows'][$i]['customer']+"</td>"; 
            html += "<td>"+data['rows'][$i]['status']+"</td>"; 
            html += "<td align='right' class='td_total'>"+data['rows'][$i]['total']+"</td>"; 
            html += "<td>"+data['rows'][$i]['date_added']+"</td>";
            html += "<td>"+data['rows'][$i]['date_modified']+"</td>";
            html += "<td align='center'> [<a class='edit' data-order-id="+data['rows'][$i]['order_id']+" href='#'>Edit</a>]</td>";
            html += "</tr>";
        }
        
        $('.pagination').html(data['pagination']);
        $('.data_row').remove();
        $('.order_list table .filter').after(html);
    });
}

function filter($page) {
	url = 'index.php?route=pos/pos/ordersAJAX&token=<?php echo $token; ?>';
        
	var filter_order_id = $('input[name=\'filter_order_id\']').attr('value');
	
	if (filter_order_id) {
		url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
	}
	
	var filter_customer = $('input[name=\'filter_customer\']').attr('value');
	
	if (filter_customer) {
		url += '&filter_customer=' + encodeURIComponent(filter_customer);
	}
	
	var filter_order_status_id = $('select[name=\'filter_order_status_id\']').attr('value');
	
	if (filter_order_status_id != '*') {
		url += '&filter_order_status_id=' + encodeURIComponent(filter_order_status_id);
	}	

	var filter_total = $('input[name=\'filter_total\']').attr('value');

	if (filter_total) {
		url += '&filter_total=' + encodeURIComponent(filter_total);
	}	
	
	var filter_date_added = $('input[name=\'filter_date_added\']').attr('value');
	
	if (filter_date_added) {
		url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
	}
	
	var filter_date_modified = $('input[name=\'filter_date_modified\']').attr('value');
	
	if (filter_date_modified) {
		url += '&filter_date_modified=' + encodeURIComponent(filter_date_modified);
	}
				
	get_orders(url);
}


//--></script>  
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script> 
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});

//timer 
function display_cf() {
    var refresh = 1000; // Refresh rate in milli seconds
    mytime = setTimeout('display_ctf()', refresh)
}


function display_ctf() {
    var x = new Date();
    $('.footer_timer span').html(x.toDateString() + ', ' +  x.toLocaleTimeString());
    tt = display_cf();
}

display_ctf();

//--></script> 