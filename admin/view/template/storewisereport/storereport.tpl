<?php echo $header; ?>
<?php echo $column_left; ?>
<div id="content">
<div class="page-header">
<div class="container-fluid">

<h1><?php echo $heading_title; ?></h1>
<ul class="breadcrumb">
<?php foreach ($breadcrumbs as $breadcrumb) { ?>
<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
<?php } ?>
</ul>
<i class="<?php echo $tool_tip_class; ?> " data-toggle="tooltip" style="<?php echo $tool_tip_style; ?>" title="<?php echo $tool_tip; ?>"></i>
 
</div>
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
<h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
</div>
<div class="panel-body" style="min-height:410px">
<?php //echo $tab3; ?>

<ul id="geo" class="nav nav-tabs nav-justified">
            <li class="<?php echo $tab1; ?>"><a onclick="return active_tab(1); " href="#tab-nation" data-toggle="tab">Material received</a></li>
            <li class="<?php echo $tab2; ?>"><a onclick="return active_tab(2); " href="#tab-zone" data-toggle="tab">Stock Transfer</a></li>
            <li class="<?php echo $tab3; ?>"><a onclick="return active_tab(3); " href="#tab-region" data-toggle="tab">Sale</a></li>
            <li class="<?php echo $tab4; ?>"><a onclick="return active_tab(4); " href="#tab-state" data-toggle="tab">Amount Deposited</a></li>
            <li class="<?php echo $tab5; ?>"><a  onclick="return active_tab(5); " href="#tab-product" data-toggle="tab">Product Sales Quantity</a></li>
            <li class="<?php echo $tab6; ?>"><a onclick="return active_tab(6); " href="#tab-territory" data-toggle="tab">Current Inventory</a></li>
            <li class="<?php echo $tab7; ?>"><a onclick="return active_tab(7); " href="#tab-datewisesale" data-toggle="tab">Date Wise Sale</a></li>
            <li class="<?php echo $tab8; ?>"><a onclick="return active_tab(8); " href="#tab-storeexpense" data-toggle="tab">Store Expense</a></li>
            
        </ul>
    <div class="panel-body">

<div class="well">
<div class="row">
<div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-start"><?php echo $entry_date_start; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              	
              <div class="form-group">
                <label class="control-label" for="input-date-end">Select Store</label>
                
                      
                  <select name="filter_store" style="width: 100%;" id="input-store" class="form-control">
                  <?php foreach ($stores as $store) { ?>
                  <?php if ($store['store_id'] == $filter_store) { ?>
                  <option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                
              </div>
            </div>
<div class="col-sm-6">
                <div class="form-group">
                <label class="control-label" for="input-date-end"><?php echo $entry_date_end; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <div class="col-sm-6" style="float: right;">  
                   <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button> 
                  </div>
              
            </div>
</div>
</div>

</div>
    

          <div class="tab-content">  
              
     <!--*************************Material Receive***********************-->         
     <div class="tab-pane <?php echo $tab1; ?>" id="tab-nation">
      <div class="panel-body">
     
        <div class="row">
            <div class="col-sm-6" style="float: right;">
                  <button type="button" id="button-download1" class="btn btn-primary pull-right" style="margin-left:24px">
                  <i class="fa fa-download"></i> Download
                 </button>
                  
            </div>
        </div>
      </div>     
        <div class="panel-body">
          
          <div class="table-responsive">
           <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left">Store Name (Receiver)</td>
                <td class="text-left">Order date</td>
                <td class="text-left">Received date</td>
                <td class="text-right">Product Name</td>
                <td  class="text-right">Product ID</td>
                <td class="text-right">Transaction Type</td>
                <td  class="text-right">Qnty</td>
                <td  class="text-right">Price</td>
                <td class="text-right">Tax</td>
                <td class="text-right">Total Value</td>
                <td class="text-right">Store Name(Sender)</td>
	        <td class="text-right">Status</td>
              </tr>            </thead>
            <tbody>
               
            <?php if ($orders) { ?>
             <?php foreach ($orders as $order) { 

	      if($order['Current_status']=="Recived")
	      {
		$quantity=$order['quantity'];
	      }
	      else
	      {
	       $quantity=0;
	      }
	     ?>
              <tr>
                <td class="text-left"><?php echo $order['store_name']; ?></td>
                <td class="text-left"><?php echo $order['order_date']; ?></td>
                <td class="text-left"><?php echo $order['recive_date']; ?></td>
                <td class="text-right"><?php echo $order['product_name']; ?></td>
                <td class="text-right"><?php echo $order['product_id']; ?></td>
                <td class="text-right"><?php echo $order['Transaction_Type']; ?></td>
                <td class="text-right"><?php echo $quantity; ?></td>
                <td class="text-right"><?php echo $order['price']; ?></td>
                <td class="text-right"><?php echo $order['tax']; ?></td>
                <td class="text-right"><?php echo ($quantity*$order['total']); ?></td>
                <td class="text-right"><?php echo $order['store_transfer']; ?></td>
	        <td class="text-right"><?php echo $order['Current_status']; ?></td>
              </tr>              <?php } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="12"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
          </div>
        
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>  
        </div>
       
 </div>
 <!--*************************end Material Receive***********************-->   
 
 
 
 
 
 
 
 
 <!--*************************Stock Transfer***********************-->   
  <div class="tab-pane <?php echo $tab2; ?>" id="tab-zone">
                   
         
    <div class="panel-body">
        <div class="row">
            
              <div class="col-sm-6" style="float: right;">
                  <button type="button" id="button-download2" class="btn btn-primary pull-right" style="margin-left:24px">
                  <i class="fa fa-download"></i> Download
                 </button>
                  
              </div>
        </div>
    </div>  
      
     <div class="panel-body">
        
          <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
	              <td class="text-right">Store Name(Sender)</td>
                      <td class="text-left">Store Name (Receiver)</td>
                      <td class="text-left">Date</td>
                      <td class="text-right">Product Name</td>
                      <td  class="text-right">Product ID</td>
                      <td class="text-right">Transaction Type</td>
	              <td  class="text-right">To be received Qnty</td>
                      <td  class="text-right">Received Qnty</td>
                      <td  class="text-right">Price</td>
                      <td class="text-right">Tax</td>
                      <td class="text-right">Total Value</td>
	              <td class="text-right">Status</td>
                    </tr>            
                </thead>
            <tbody>
            <?php if ($orders2) { ?>
             <?php foreach ($orders2 as $order) { ?>
              <tr>
	        <td class="text-left"><?php echo $order['store_transfer1']; ?></td>
                <td class="text-left"><?php echo $order['store_name1']; ?></td>
                <td class="text-left"><?php echo $order['order_date1']; ?></td>
                <td class="text-right"><?php echo $order['product_name1']; ?></td>
                <td class="text-right"><?php echo $order['product_id1']; ?></td>
                <td class="text-right"><?php echo $order['Transaction_Type1']; ?></td>
	        <td class="text-right" <?php if($order['Current_status1']=="Pending") { ?> style="color: red;font-weight: bold;" <?php } ?>><?php echo $order['To_be_Recived1']; ?></td>
                <td class="text-right"><?php echo $order['quantity1']; ?></td>
                <td class="text-right"><?php echo $order['price1']; ?></td>
                <td class="text-right"><?php echo $order['tax1']; ?></td>
                <td class="text-right"><?php echo $order['total1']; ?></td>
	        <td class="text-right"><?php echo $order['Current_status1']; ?></td>
              </tr>             
             <?php } ?>
            <?php } else { ?>
              <tr>
                <td class="text-center" colspan="12"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
          </div>
        
         
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination2; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results2; ?></div>
        </div>  
        </div>
 </div>
 <!--*************************End Stock Transfer***********************-->     
 
 
 
 
<!--*****************************************Sale*****************************************-->  
              
 <div class="tab-pane <?php echo $tab3; ?>" id="tab-region">
                
    <div class="panel-body">
        
        <div class="row">
            <div class="col-sm-6" style="float: right;">
                <button type="button" id="button-download3" class="btn btn-primary pull-right" style="margin-left:24px">
                     <i class="fa fa-download"></i> Download
                </button>
            
            </div>
        </div>
    </div>         
        <div class="panel-body">
         
          <div class="table-responsive">
<span style="font-weight: bold;">Total Cash : <?php echo number_format((float)$total_cash_all, 2, '.', ''); ?></span> 
           &nbsp; | &nbsp;
<span style="font-weight: bold;">Total C-Tagged : <?php echo number_format((float)$total_cash_tagged_all, 2, '.', ''); ?></span> 
	&nbsp; | &nbsp;
	<span style="font-weight: bold;">Total C-Subsidy : <?php echo number_format((float)$total_cash_subsidy_all, 2, '.', ''); ?></span> 
          &nbsp; | &nbsp;
 <span style="font-weight: bold;">Total Tagged : <?php echo number_format((float)$total_tagged_all, 2, '.', ''); ?></span> 
           &nbsp; | &nbsp;
           <span style="font-weight: bold;">Total Subsidy (Company ) : <?php echo number_format((float)$total_cash_subsidy_all, 2, '.', ''); ?></span> 
           
           &nbsp; | &nbsp;  
           <span style="font-weight: bold;">Total : <?php $total=number_format((float)$total_cash_all, 2, '.', '')+number_format((float)$total_cash_tagged_all, 2, '.', '')+number_format((float)$total_cash_subsidy_all, 2, '.', '')+number_format((float)$total_tagged_all, 2, '.', '')+
number_format((float)$total_cash_subsidy_all, 2, '.', ''); echo $total; // ?></span> 
<br/>
<span style="float: right;font-weight: bold;color: #933B3B;">Note : C-Tagged=>Cash Tagged, C-Subsidy=>Cash Taken by store incharge for subsidy order</span>
           <br/><br/>
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left">S. No.</td>
                <td class="text-right">Store name</td>
                <!--<td class="text-right">Store id</td>
                <td class="text-right">Store Credit Limit</td>-->
	 <td class="text-right">Current Credit</td>
	<td class="text-right">Store Cash</td>
          	<td class="text-right">Store C-Tagged</td>
              <td class="text-right">Store C-Subsidy</td>

                <td class="text-right">Store Tagged</td> 
	
	<td class="text-right">Subsidy(By Company )</td>
	
                <td class="text-right">No. Order (Cash)</td>
                <td class="text-right">No. Order (Tagged)</td>
	<td class="text-right">No. Order (C-Tagged)</td>
	<td class="text-right">No. Order (Subsidy)</td>
	
                <td class="text-left">Total</td>
                
              </tr>
            </thead>
            <tbody>
              
              <?php if($orders3) { $aa=1; foreach ($orders3 as $order) { //print_r($order); ?>
              <tr>
               <td class="text-left"><?php echo $aa; ?></td>
                
              <td class="text-right"><?php echo $order['store_name']; ?></td>
              <!--<td class="text-right"><?php echo $order['store_id']; ?></td>
              <td class="text-right"><?php echo $order['creditlimit']; ?></td>-->
              <td class="text-right"><?php echo $order['currentcredit']; ?></td>
              <td class="text-right"><?php echo $order['cash']; ?></td> 
	<td class="text-right"><?php echo $order['Cash_Tagged']; ?></td>
	<td class="text-right"><?php echo $order['Cash_subsidy']; ?></td>
	<td class="text-right"><?php echo $order['tagged']; ?></td>
	
	<td class="text-right"><?php echo $order['subsidy']; ?></td>
	
              <td class="text-right"><?php echo $order['cash_order']; ?></td>
              <td class="text-right"><?php echo $order['tagged_order']; ?></td>
	<td class="text-right"><?php echo $order['Cash_tagged_order']; ?></td>
	<td class="text-right"><?php echo $order['subsidy_order']; ?></td>
              <td class="text-left"><?php echo $order['total']; ?></td>
                
              </tr>
              <?php $tarr=explode('Rs.',$order['total']);$total=$total+$tarr[1];  $aa++; } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
       
        
        <div class="row">
          <div class="col-sm-4 text-left"><?php echo $pagination3; ?></div>
          <div class="col-sm-8 text-right">
	   
         <?php echo $results3; ?></div>
        </div>
            
            
        </div>       
</div>
 
<!--*****************************************End Sale*****************************************--> 
       

              
 <!--*************************AMOUNT DEPOSIT ***********************-->               
<div class="tab-pane <?php echo $tab4; ?>" id="tab-state">
                  
    <div class="panel-body">
        <div class="row">
            
              <div class="col-sm-6" style="float: right;">
                  <button type="button" id="button-download4" class="btn btn-primary pull-right" style="margin-left:24px">
                  <i class="fa fa-download"></i> Download
                 </button>
              </div>
        </div>
    </div> 
                  
           
    <div class="panel-body">
      
          <div class="table-responsive">
	<span style="font-weight: bold;">Total  Amount :: HDFC : <?php echo $hdfc_total; ?></span> &nbsp; |  &nbsp;
	<span style="font-weight: bold;"> ICICI : <?php echo $ICICI_total; ?></span> &nbsp; | &nbsp; 
	<span style="font-weight: bold;"> SBI : <?php echo $State_Bank_of_India_total; ?></span> &nbsp;  | &nbsp; 
	
	<span style="font-weight: bold;"> TAGGED BILLS : <?php echo $TAGGED_BILLS_total; ?></span>
              <br/><br/>

          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left">SI ID <?php //echo $column_Si_Id; ?></td>
                <!--<td class="text-left">Store ID <?php //echo $column_date_end; ?></td>-->
                <td class="text-left">Store Name <?php //echo $column_title; ?></td>
                <td class="text-right">Bank <?php //echo //$column_orders; ?></td>
                <td class="text-right">Date <?php //echo $column_total; ?></td>
                <td class="text-right">Amount <?php //echo $column_total; ?></td>
	<td class="text-right">Status</td>
	<td class="text-right">By Whom</td>
              </tr>
            </thead>
            <tbody>
              <?php if ($orders4) { $total3=0; ?>
              <?php foreach ($orders4 as $order) { ?>
              <tr>
                <td class="text-left"><?php echo $order['SIID3']; ?></td>
                <!--<td class="text-left"><?php echo $order['store_id3']; ?></td>-->
                <td class="text-left"><?php echo $order['name3']; ?></td>
                <td class="text-right"><?php echo $order['bank_name3']; ?></td>
                <td class="text-right"><?php echo $order['date_added3']; ?></td>
                <td class="text-right"><?php echo $order['amount3']; ?></td>
	        <td class="text-right"><?php if($order['status3']=="0") { echo "<span style='color: #CC760F;'>Pending</span>"; } else if($order['status3']=="1") { echo "<span style='color: #2F9217;'>Accepted</span>"; } else if($order['status3']=="2") { echo "<span style='color: #C0250C;'>Rejected</span>"; } ?></td>
	        <td class="text-right"><?php echo $order['accepted_by3']; ?></td>
              </tr>
              <?php $total3=$total3+$order['amount3'];
              
              } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="7"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
       
         <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination4; ?></div>
          <div class="col-sm-6 text-right"> 
              <span style="font-weight: bold;">Pgae Total ::  Amount : <?php echo $total3; ?></span> <br/>
              <?php echo $results4; ?>  </div>
        </div> 
    </div>        
</div>
<!--******************************* END AMOUNT DEPOSIT *********************************--> 
 

 <!--*************************Start Product Sales quantity***********************-->    
       <div class="tab-pane <?php echo $tab5; ?>" id="tab-product">
                  
    <div class="panel-body">
        <div class="row">
            
              <div class="col-sm-6" style="float: right;">
                  <button type="button" id="button-download5" class="btn btn-primary pull-right" style="margin-left:24px">
                  <i class="fa fa-download"></i> Download
                 </button>
              </div>
        </div>
    </div> 
                  
           
    <div class="panel-body">
      
          <div class="table-responsive">
	<span style="font-weight: bold;">Total   :: <?php echo $total_amount_all_product_sales; ?></span> 
              <br/><br/>

          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left">Store Name</td>
                <td class="text-left">Product Name</td>
                <!--<td class="text-left">Product ID</td>-->
                
                <td class="text-right">Sale Quantity</td>
                <td class="text-right">Total</td>
		

              </tr>
            </thead>
            <tbody>
              <?php if ($orders5) { ?>
              <?php  foreach ($orders5 as $product) { ?> 
              <tr>
                <td class="text-left"><?php echo $product['store_name']; ?></td>
                <td class="text-left"><?php echo $product['name']; ?></td>
                <!--<td class="text-left"><?php echo $product['product_id']; ?></td>-->
                
                <td class="text-right"><?php echo $product['quantity']; ?></td>
                <td class="text-right"><?php echo $product['total'] ; ?></td>
              

              </tr>
              <?php } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
       
         <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination5; ?></div>
          <div class="col-sm-6 text-right"> 
              <span style="font-weight: bold;">Pgae Total ::  Amount : <?php echo $total_amount4; ?></span> <br/>
              <?php echo $results5; ?>  </div>
        </div> 
    </div>        
</div>
 
<!--*************************end product sales quantity***********************--> 
              



<!--*************************current inventory***********************--> 
    <div class="tab-pane <?php echo $tab6; ?>" id="tab-territory">
     
        <div class="panel-body">
            <div class="row">
            
              <div class="col-sm-6" style="float: right;">
                  <button type="button" id="button-download6" class="btn btn-primary pull-right" style="margin-left:24px">
                  <i class="fa fa-download"></i> Download
                 </button>
                  
              </div>
            </div>
        </div> 
        
       <div class="panel-body">
         
          <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left">SI ID <?php //echo $column_Si_Id; ?></td>
	         <td class="text-left">Store Name <?php //echo $column_title; ?></td>
                <td class="text-left">Product ID <?php //echo $column_title; ?></td>
                <td class="text-right">Product Name <?php //echo //$column_orders; ?></td>
                <td class="text-right">Qnty <?php //echo $column_total; ?></td>
                <td class="text-right">Price <?php //echo $column_total; ?></td> 
                <td class="text-right">Amount <?php //echo $column_total; ?></td>
              </tr>
            </thead>
            <tbody>
              <?php if ($orders6) {  if($_GET["page6"]=="") {$aa=1;} else if($_GET["page6"]=="1") {$aa=1;}
              else{ $aa=(($_GET["page6"]-1)*20)+1; }?>
              <?php foreach ($orders6 as $order) { ?>
              <tr>
                <td class="text-left"><?php echo $aa; ?></td>
	        <td class="text-left"><?php echo $order['store_name5']; ?></td>
                <td class="text-left"><?php echo $order['product_id5']; ?></td>
                <td class="text-right"><?php echo $order['product_name5']; ?></td>
                <td class="text-right"><?php echo $order['qnty5']; ?></td>
                <td class="text-right"><?php echo $order['price5']; ?></td>
                <td class="text-right"><?php echo ($order['price5']*$order['qnty5']); ?></td>
              </tr>
              <?php 
              $aa++;
              } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="7"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
       
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination6; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results6; ?></div>
        </div>  
        </div>      
    </div>
              
 <!--*************************end current inventory***********************-->               
             
 <!--*****************************************Sale*****************************************-->  
              
 <div class="tab-pane <?php echo $tab7; ?>" id="tab-datewisesale">
                
    <div class="panel-body">
        
        <div class="row">
            <div class="col-sm-6" style="float: right;">
                <button type="button" id="button-download7" class="btn btn-primary pull-right" style="margin-left:24px">
                     <i class="fa fa-download"></i> Download
                </button>
            
            </div>
        </div>
    </div>         
        <div class="panel-body">
         
          <div class="table-responsive">
              <span style="font-weight: bold;">Total Cash : <?php echo number_format((float)$total_cash_all7, 2, '.', '');; ?></span> 
                  &nbsp; | &nbsp;
              <span style="font-weight: bold;">Total Tagged : <?php echo number_format((float)$total_tagged_all7, 2, '.', '');; ?></span> 
                  &nbsp; | &nbsp;
              <span style="font-weight: bold;">Total Subsidy : <?php echo number_format((float)$total_subsidy_all7, 2, '.', '');; ?></span> 
                  &nbsp; | &nbsp;
	      <span style="font-weight: bold;">Total C-Tagged : <?php echo number_format((float)$total_cash_tagged_all7, 2, '.', '');; ?></span> 
                  &nbsp; | &nbsp;  
              <span style="font-weight: bold;">Total : <?php $total7=number_format((float)$total_cash_all7, 2, '.', '')+number_format((float)$total_tagged_all7, 2, '.', '')+
                  number_format((float)$total_subsidy_all7, 2, '.', '')+number_format((float)$total_cash_tagged_all7, 2, '.', ''); echo $total7; ?></span> 

              <span style="float: right;font-weight: bold;color: #933B3B;">Note : C-Tagged=>Cash Tagged</span>
           <br/><br/>
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left">S. No.</td>
                <td class="text-right">Store name</td>
                <!--<td class="text-right">Store id</td>
                <td class="text-right">Store Credit Limit</td>
	  <td class="text-right">Current Credit</td>-->
                 <td class="text-right">Sale date</td>
	   <td class="text-right">Store Cash</td>
                <td class="text-right">Store Tagged</td> 
	        <td class="text-right">Store C-Tagged</td>
	        <td class="text-right">Store Subsidy</td>
                <td class="text-right">No. Order (Cash)</td>
                <td class="text-right">No. Order (Tagged)</td>
	        <td class="text-right">No. Order (C-Tagged)</td>
	        <td class="text-right">No. Order (Subsidy)</td>
                <td class="text-left">Total</td>
              </tr>
            </thead>
            <tbody>
              <?php $total=0; if ($orders7) {  
                  if($_GET["page7"]=="") 
                   {
                      $aa=1;
                     
                   } 
                  else if($_GET["page7"]=="1") 
                     {$aa=1;}
              else{ 
                  $aa=(($_GET["page7"]-1)*20)+1; 
                  
              } ?>
                
              <?php foreach ($orders7 as $order) { //print_r($order); ?>
              <tr>
                <td class="text-left"><?php echo $aa; ?></td> 
                <td class="text-right"><?php echo $order['store_name2']; ?></td>
                <!--<td class="text-right"><?php echo $order['store_id2']; ?></td>
                <td class="text-right"><?php echo $order['creditlimit2']; ?></td>
                <td class="text-right"><?php echo $order['currentcredit2']; ?></td>-->
	  <td class="text-right"><?php echo date('d/m/Y',strtotime($order['date_added7'])); ?></td>
                <td class="text-right"><?php echo $order['cash2']; ?></td> 
	        <td class="text-right"><?php echo $order['tagged2']; ?></td>
	        <td class="text-right"><?php echo $order['Cash_Tagged2']; ?></td>
	        <td class="text-right"><?php echo $order['subsidy2']; ?></td>
                <td class="text-right"><?php echo $order['cash_order2']; ?></td>
                <td class="text-right"><?php echo $order['tagged_order2']; ?></td>
	        <td class="text-right"><?php echo $order['Cash_tagged_order2']; ?></td>
	        <td class="text-right"><?php echo $order['subsidy_order2']; ?></td>
                <td class="text-left"><?php echo $order['total2']; ?></td> 
              </tr>
              
              <?php $tarr=explode('Rs.',$order['total2']);$total72=$total72+$tarr[1];  $aa++; } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="13"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
       
        
        <div class="row">
          <div class="col-sm-4 text-left"><?php echo $pagination7; ?></div>
          <div class="col-sm-8 text-right">
	   <span style="font-weight: bold;">Page Total  :: </span> 
           &nbsp;  
           <span style="font-weight: bold;">Total Cash : <?php echo number_format((float)$total_cash7, 2, '.', '');; ?></span> 
           &nbsp; | &nbsp;
           <span style="font-weight: bold;">Total Tagged : <?php echo number_format((float)$total_tagged7, 2, '.', '');; ?></span> 
           &nbsp; | &nbsp;
           <span style="font-weight: bold;">Total Subsidy : <?php echo number_format((float)$total_subsidy7, 2, '.', '');; ?></span> 
           &nbsp; | &nbsp; 
	   <span style="font-weight: bold;">Total C-Tagged : <?php echo number_format((float)$total_Cash_Tagged7, 2, '.', '');; ?></span> 
           &nbsp; | &nbsp; 
           <span style="font-weight: bold;">Total : <?php $total72=number_format((float)$total_cash7, 2, '.', '')+number_format((float)$total_tagged7, 2, '.', '')+number_format((float)$total_subsidy7, 2, '.', '')+number_format((float)$total_Cash_Tagged7, 2, '.', ''); echo $total72; ?></span> 
           <br/>

         <?php echo $results7; ?></div>
        </div>
            
            
        </div>       
</div>
 
<!--*****************************************End Sale*****************************************--> 

<!--*************************Start store expense***********************-->    
       <div class="tab-pane <?php echo $tab8; ?>" id="tab-storeexpense">
                  
    <div class="panel-body">
        <div class="row">
            
              <div class="col-sm-6" style="float: right;">
                  <button type="button" id="button-download8" class="btn btn-primary pull-right" style="margin-left:24px">
                  <i class="fa fa-download"></i> Download
                 </button>
              </div>
        </div>
    </div> 
                  
           
    <div class="panel-body">
      
          <div class="table-responsive">
	

          <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td class="text-left">Start date</td>
                  <td class="text-left">End date</td>
                  <td class="text-left">Store</td>
	    <td class="text-left">Submit by</td>
                  <td class="text-left">Approved by</td>
                 
                  <td class="text-left">Amount</td>
	     <td class="text-left">Month/Year</td>
	    
                  <td class="text-left">Attched File</td>
                  <td class="text-left">Remarks</td>
                  <td class="text-left">Status</td>
                  
                </tr>
              </thead>
              <tbody>
                <?php if ($orders8) { ?>
                <?php foreach ($orders8 as $bill) { ?> 
                <tr>
                  <td class="text-left"><?php echo $bill['start_date']; ?></td>
                  <td class="text-left"><?php echo $bill['end_date']; ?></td>
                  <td class="text-left"><?php echo $bill['store_name']; ?></td>
	  <td class="text-left"><?php echo $bill['submitby']; ?></td>
                  <td class="text-left"><?php echo $bill['approvedby']; ?></td>
                  <td class="text-left"><?php echo $bill['amount']; ?></td>
                  <td class="text-left"><?php echo $bill['filter_month']."/".$bill['filter_year']; ?></td>
                  <td class="text-left"><?php if($bill['uploded_file']!=""){ ?><a href="../system/upload/hrexpensebill/<?php echo $bill['uploded_file']; ?>" download>View</a><?php } ?></td>
                  <td class="text-left"><div style="max-width: 200px;"><?php echo $bill['remarks']; ?></div></td>
                  <td class="text-left"><?php if($bill['status']=="0"){echo "Pending";} else if($bill['status']=="1") { echo "Accepted"; } else if($bill['status']=="2") { echo "Rejected"; } ?></td>
                 
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
       
         <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination8; ?></div>
          <div class="col-sm-6 text-right"> 
              
              <?php echo $results8; ?>  </div>
        </div> 
    </div>        
</div>
 
<!--*************************end store expense***********************--> 

          <span id="tab_active_" style="display: none;">
<?php echo @$_REQUEST["tab"]; ?>
</span>    
          </div> 

</div>
</div>
</div>
</div>

<script type="text/javascript">
$("#input-store").select2();
function active_tab(tab)
{
//alert(tab);
$('#tab_active_').html(tab);
//document.getElementById("tab_active_").value="text";
//document.getElementById("tab_active_").value=tab;
return false;
}
</script>

<input type="hidden" name="tab_active_22" id="tab_active_22" value="<?php echo @$_REQUEST["tab"]; ?>" />
<script type="text/javascript">
$('#button-filter').on('click', function() {
	 url = 'index.php?route=storewisereport/storereport&token=<?php echo $token; ?>';
	 //url += '&page='+'1';
         //url += '&tab='+'1';
         
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	var filter_name = $('input[name=\'filter_name\']').val();
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
                var filter_name_id = $('input[name=\'filter_name_id\']').val();
	
	if (filter_name_id) {
		url += '&filter_name_id=' + encodeURIComponent(filter_name_id);
                
	}
	}
         var filter_store = $('select[name=\'filter_store\']').val();
	
	if (filter_store != 0) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}	
var tabbb=$('#tab_active_').html();
              if(tabbb=="")
              {
                url += '&tab=1';
              }
              else
              {
                  url += '&tab=' + tabbb;
              }
             //alert(tabbb);//return false;
	location = url;
});

</script> 

<script type="text/javascript">
    $('#button-download1').on('click', function() {
	url = 'index.php?route=report/stock/download_recived&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	var filter_name = $('input[name=\'filter_name\']').val();
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
                var filter_name_id = $('input[name=\'filter_name_id\']').val();
	
	if (filter_name_id) {
		url += '&filter_name_id=' + encodeURIComponent(filter_name_id);
                
	}
	}
         var filter_store = $('select[name=\'filter_store\']').val();
	
	if (filter_store != 0) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}	

        window.open(url, '_blank');
	
});
    /*********************************   Stock Transfer ****************************************/ 

$('#button-download2').on('click', function() {
	url = 'index.php?route=report/stock/download_transfer&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	var filter_name = $('input[name=\'filter_name\']').val();
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
                var filter_name_id = $('input[name=\'filter_name_id\']').val();
	
	if (filter_name_id) {
		url += '&filter_name_id=' + encodeURIComponent(filter_name_id);
                
	}
	}
         var filter_store = $('select[name=\'filter_store\']').val();
	
	if (filter_store != 0) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}	
	
        window.open(url, '_blank');
	
});
/*********************************  End Stock Transfer ****************************************/ 
$('#button-download3').on('click', function() {
    url = 'index.php?route=report/sale_summary/download_excel_subsidycash&token=<?php echo $token; ?>';
    	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
		
	var filter_store = $('select[name=\'filter_store\']').val();
	
	if (filter_store) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}
    
    //location = url;
        window.open(url, '_blank');
});
   $('#button-download4').on('click', function() {
    url = 'index.php?route=report/cash_report/download_excel&token=<?php echo $token; ?>';
    
   var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	var filter_name = $('input[name=\'filter_name\']').val();
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
                var filter_name_id = $('input[name=\'filter_name_id\']').val();
	
	if (filter_name_id) {
		url += '&filter_name_id=' + encodeURIComponent(filter_name_id);
                
	}
	}
         var filter_store = $('select[name=\'filter_store\']').val();
	
	if (filter_store != 0) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}	

	location = url;
}); 

 $('#button-download5').on('click', function() {
    url = 'index.php?route=report/product_storewisesales/download_excel&token=<?php echo $token; ?>';
    
   var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	var filter_name = $('input[name=\'filter_name\']').val();
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
                var filter_name_id = $('input[name=\'filter_name_id\']').val();
	
	if (filter_name_id) {
		url += '&filter_name_id=' + encodeURIComponent(filter_name_id);
                
	}
	}
         var filter_store = $('select[name=\'filter_store\']').val();
	
	if (filter_store != 0) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}	

	location = url;
}); 
  $('#button-download6').on('click', function() {
    url = 'index.php?route=report/inventory_report/download_excel&token=<?php echo $token; ?>';
    
        var filter_store = $('select[name=\'filter_store\']').val();
    
    if (filter_group!="") {
        url += '&filter_store=' + encodeURIComponent(filter_store);
    }

    var filter_date_end = $('input[name=\'filter_date_end\']').val();
    
    if (filter_date_end) {
        //url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
    }
        
    var filter_group = $('select[name=\'filter_group\']').val();
    
    if (filter_group) {
        //url += '&filter_group=' + encodeURIComponent(filter_group);
    }
    
    var filter_order_status_id = $('select[name=\'filter_order_status_id\']').val();
    
    if (filter_order_status_id != 0) {
        //url += '&filter_order_status_id=' + encodeURIComponent(filter_order_status_id);
    }    
       
    //location = url;
        window.open(url, '_blank');
});  

$('#button-download7').on('click', function() {
    url = 'index.php?route=report/sale_summary/download_excel_category_date_wise&token=<?php echo $token; ?>';
    	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
		
	var filter_store = $('select[name=\'filter_store\']').val();
	
	if (filter_store) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}
    
    //location = url;
	if(filter_store!="")
	{
       		 window.open(url, '_blank');
	}
});
$('#button-download8').on('click', function() {
    url = 'index.php?route=hr/expenseapprove/getlist_download&token=<?php echo $token; ?>';
    	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
		
	var filter_store = $('select[name=\'filter_store\']').val();
	
	if (filter_store) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}
    
    //location = url;
        window.open(url, '_blank');
});
    </script> 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?>
