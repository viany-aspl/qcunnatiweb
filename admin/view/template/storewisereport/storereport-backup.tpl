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
            <!--<li class="<?php echo $tab5; ?>"><a  onclick="return active_tab(5); " href="#tab-area" data-toggle="tab">Store Expense</a></li>-->
            <li class="<?php echo $tab6; ?>"><a onclick="return active_tab(6); " href="#tab-territory" data-toggle="tab">Current Inventory</a></li>
            <!--<li class="<?php echo $tab7; ?>"><a onclick="return active_tab(7); " href="#tab-district" data-toggle="tab">Monthly Carry Forward</a></li>-->
            
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
                <div class="input-group">
                  <?php //echo $filter_store; print_r($stores);//exit; ?>
                  <span class="input-group-btn">
                      
                  <select name="filter_store" id="input-store" class="form-control">
                  <?php foreach ($stores as $store) { ?>
                  <?php if ($store['store_id'] == $filter_store) { ?>
                  <option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                  </span></div>
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
                  <button type="button" id="button-download" class="btn btn-primary pull-right" style="margin-left:24px">
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
            <?php if ($orders1) { ?>
             <?php foreach ($orders1 as $order) { ?>
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
          <div class="col-sm-6 text-left"><?php echo $pagination1; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results1; ?></div>
        </div>  
        </div>
 </div>
 <!--*************************End Stock Transfer***********************-->     
 
 
 
 
<!--*****************************************Sale*****************************************-->  
              
 <div class="tab-pane <?php echo $tab3; ?>" id="tab-region">
                
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
              <span style="font-weight: bold;">Total Cash : <?php echo number_format((float)$total_cash_all, 2, '.', '');; ?></span> 
                  &nbsp; | &nbsp;
              <span style="font-weight: bold;">Total Tagged : <?php echo number_format((float)$total_tagged_all, 2, '.', '');; ?></span> 
                  &nbsp; | &nbsp;
              <span style="font-weight: bold;">Total Subsidy : <?php echo number_format((float)$total_subsidy_all, 2, '.', '');; ?></span> 
                  &nbsp; | &nbsp;
	      <span style="font-weight: bold;">Total C-Tagged : <?php echo number_format((float)$total_cash_tagged_all, 2, '.', '');; ?></span> 
                  &nbsp; | &nbsp;  
              <span style="font-weight: bold;">Total : <?php $total=number_format((float)$total_cash_all, 2, '.', '')+number_format((float)$total_tagged_all, 2, '.', '')+
                  number_format((float)$total_subsidy_all, 2, '.', '')+number_format((float)$total_cash_tagged_all, 2, '.', ''); echo $total; ?></span> 

              <span style="float: right;font-weight: bold;color: #933B3B;">Note : C-Tagged=>Cash Tagged</span>
           <br/><br/>
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left">S. No.</td>
                <td class="text-right">Store name</td>
                <!--<td class="text-right">Store id</td>-->
                <td class="text-right">Store Credit Limit</td>
	        <td class="text-right">Current Credit</td>
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
              <?php $total=0; if ($orders2) {  
                  if($_GET["page"]=="") 
                   {
                      $aa=1;
                     
                   } 
                  else if($_GET["page"]=="1") 
                     {$aa=1;}
              else{ 
                  $aa=(($_GET["page"]-1)*20)+1; 
                  
              } ?>
                
              <?php foreach ($orders2 as $order) { //print_r($order); ?>
              <tr>
                <td class="text-left"><?php echo $aa; ?></td> 
                <td class="text-right"><?php echo $order['store_name2']; ?></td>
                <!--<td class="text-right"><?php echo $order['store_id2']; ?></td>-->
                <td class="text-right"><?php echo $order['creditlimit2']; ?></td>
                <td class="text-right"><?php echo $order['currentcredit2']; ?></td>
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
              
              <?php $tarr=explode('Rs.',$order['total2']);$total=$total+$tarr[1];  $aa++; } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="13"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
       
        
        <div class="row">
          <div class="col-sm-4 text-left"><?php echo $pagination2; ?></div>
          <div class="col-sm-8 text-right">
	   <span style="font-weight: bold;">Page Total  :: </span> 
           &nbsp;  
           <span style="font-weight: bold;">Total Cash : <?php echo number_format((float)$total_cash, 2, '.', '');; ?></span> 
           &nbsp; | &nbsp;
           <span style="font-weight: bold;">Total Tagged : <?php echo number_format((float)$total_tagged, 2, '.', '');; ?></span> 
           &nbsp; | &nbsp;
           <span style="font-weight: bold;">Total Subsidy : <?php echo number_format((float)$total_subsidy, 2, '.', '');; ?></span> 
           &nbsp; | &nbsp; 
	   <span style="font-weight: bold;">Total C-Tagged : <?php echo number_format((float)$total_Cash_Tagged, 2, '.', '');; ?></span> 
           &nbsp; | &nbsp; 
           <span style="font-weight: bold;">Total : <?php $total=number_format((float)$total_cash, 2, '.', '')+number_format((float)$total_tagged, 2, '.', '')+number_format((float)$total_subsidy, 2, '.', '')+number_format((float)$total_Cash_Tagged, 2, '.', ''); echo $total; ?></span> 
           <br/>

         <?php echo $results2; ?></div>
        </div>
            
            
        </div>       
</div>
 
<!--*****************************************End Sale*****************************************--> 
       

              
 <!--*************************AMOUNT DEPOSIT ***********************-->               
<div class="tab-pane <?php echo $tab4; ?>" id="tab-state">
                  
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
              <?php if ($orders3) { $total3=0; ?>
              <?php foreach ($orders3 as $order) { ?>
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
          <div class="col-sm-6 text-left"><?php echo $pagination3; ?></div>
          <div class="col-sm-6 text-right"> 
              <span style="font-weight: bold;">Pgae Total ::  Amount : <?php echo $total3; ?></span> <br/>
              <?php echo $results3; ?>  </div>
        </div> 
    </div>        
</div>
<!--******************************* END AMOUNT DEPOSIT *********************************--> 
 

 <!--*************************Territory***********************-->    
              
        <div class="tab-pane <?php echo $tab5; ?>" id="tab-area">
                  
          
                  
            <div class="panel-body">
        
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-left">
                    <a><?php echo $column_sid; ?></a>
                  </td>
                  
                  <td class="text-left">
                    <a><?php echo $column_nation; ?></a>
                  </td>
                  <td class="text-left">
                    <a><?php echo $column_zone; ?></a>
                  </td>
                   <td class="text-left">
                    <a><?php echo $column_state; ?></a>
                  </td>
                  <td class="text-left">
                    <a><?php echo $column_region; ?></a>
                  </td>
                  <td class="text-left">
                    <a><?php echo $column_territory; ?></a>
                  </td>
                  <td class="text-left">
                    <a><?php echo $column_act; ?></a>
                  </td>
                   <?php if ($editareamodify or $deleteareamodify) { ?>
                  <td class="text-left"><a><?php echo $column_action; ?></a></td>
                   <?php }?>
                </tr>
              </thead>
              <tbody>
                <?php if ($area) { ?>
                <?php foreach ($area as $areas) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($areas['SID'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $nations['order_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $nations['order_id']; ?>" />
                    <?php } ?>
                    <input type="hidden" name="shipping_code[]" value="<?php echo $nations['shipping_code']; ?>" /></td>
                  <td class="text-left"><?php echo $areas['SID']; ?></td>
                  <td class="text-left"><?php echo ucwords($areas['NATION']); ?></td>
                  <td class="text-left"><?php echo ucwords($areas['ZONE']); ?></td>
                  <td class="text-left"><?php echo ucwords($areas['STATE']); ?></td>
                  <td class="text-left"><?php echo ucwords($areas['REGION']); ?></td>
                  <td class="text-left"><?php echo ucwords($areas['AREA']); ?></td>
                  <td class="text-left"><?php echo $areas['ACT']; ?></td>
                  <?php if (!empty($editareamodify)|| !empty($deleteareamodify))
                  {?> 
                  <td class="text-right">
                      <?php if ($editareamodify) { ?>
                      <a href="<?php echo $editterritory;?>&id=<?php echo $areas['SID'];?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a> 
                       <?php }?>
                      <?php if ($deleteareamodify) { ?>
                      <a href="<?php echo $deleteterritory;?>&id=<?php echo $areas['SID'];?>" id="button-delete<?php echo $order['order_id']; ?>" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i></a>
                      <?php }?>
                  </td>
                  <?php }?>
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
          <div class="col-sm-6 text-left"><?php echo $pagination_area; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results_area; ?></div>
        </div>  
        </div>

</div>
 
<!--*************************end Territory***********************--> 
              



<!--*************************current inventory***********************--> 
    <div class="tab-pane <?php echo $tab6; ?>" id="tab-territory">
     
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
              <?php if ($orders5) {  if($_GET["page"]=="") {$aa=1;} else if($_GET["page"]=="1") {$aa=1;}
              else{ $aa=(($_GET["page"]-1)*20)+1; }?>
              <?php foreach ($orders5 as $order) { ?>
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
          <div class="col-sm-6 text-left"><?php echo $pagination5; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results5; ?></div>
        </div>  
        </div>      
    </div>
              
 <!--*************************end current inventory***********************-->               
             
 <!--*************************Area***********************-->                  
 <div class="tab-pane <?php echo $tab7; ?>" id="tab-district">
     
    
                  
        <div class="panel-body">
         
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-left">
                    <a><?php echo $column_sid; ?></a>
                  </td>
                  <td class="text-left">
                    <a><?php echo $column_nation; ?></a>
                  </td>
                  <td class="text-left">
                    <a><?php echo $column_zone; ?></a>
                  </td>
                   <td class="text-left">
                    <a><?php echo $column_state; ?></a>
                  </td>
                  <td class="text-left">
                    <a><?php echo $column_region; ?></a>
                  </td>
                  
                  <td class="text-left">
                    <a><?php echo $column_territory; ?></a>
                  </td>
                  <td class="text-left">
                    <a><?php echo $column_district; ?></a>
                  </td>
                  <td class="text-left">
                    <a><?php echo $column_area; ?></a>
                  </td>
                  <td class="text-left">
                    <a><?php echo $column_act; ?></a>
                  </td>
                   <?php if ($editdistrictmodify or $deletedistrictmodify) { ?>
                  <td class="text-left"><a><?php echo $column_action; ?></a></td>
                   <?php }?>
                </tr>
              </thead>
              <tbody>
                <?php if ($district) { ?>
                <?php foreach ($district as $districts) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($districts['SID'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $nations['order_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $nations['order_id']; ?>" />
                    <?php } ?>
                    <input type="hidden" name="shipping_code[]" value="<?php echo $nations['shipping_code']; ?>" /></td>
                  <td class="text-left"><?php echo $districts['SID']; ?></td>
                  <td class="text-left"><?php echo ucwords($districts['NATION']); ?></td>
                  <td class="text-left"><?php echo ucwords($districts['ZONE']); ?></td>
                  <td class="text-left"><?php echo ucwords($districts['STATE']); ?></td>
                  <td class="text-left"><?php echo ucwords($districts['REGION']); ?></td>
                  <td class="text-left"><?php echo ucwords($districts['DISTRICT']); ?></td>
                  <td class="text-left"><?php echo ucwords($districts['TERRITORY']); ?></td>
                  <td class="text-left"><?php echo ucwords($districts['AREA']); ?></td>
                  <td class="text-left"><?php echo $districts['ACT']; ?></td>
                  <?php if (!empty($editdistrictmodify)|| !empty($deletedistrictmodify))
                  {?>  
                  <td class="text-right">
                      <?php if ($editdistrictmodify) { ?>
                       <a href="<?php echo $editarea;?>&id=<?php echo $districts['SID'];?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                      <?php }?>
                      <?php if ($deletedistrictmodify) { ?> 
                      <a href="<?php echo $deletearea;?>&id=<?php echo $districts['SID'];?>" id="button-delete<?php echo $order['order_id']; ?>" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i></a>
                      <?php }?>
                  </td>
                  <?php }?>
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
          <div class="col-sm-6 text-left"><?php echo $pagination_district; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results_district; ?></div>
        </div>  
        </div>     
  </div>
 <!--*************************end area***********************-->         
              
          </div> 

</div>
</div>
</div>
</div>

<script type="text/javascript">
function active_tab(tab)
{
//alert(tab);
$('#tab_active_').html(tab);
//document.getElementById("tab_active_").value="text";
//document.getElementById("tab_active_").value=tab;
return false;
}
</script>
<input type="text" name="tab_active_" id="tab_active_" value="tab1" />
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
    $('#button-download').on('click', function() {
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

$('#button-download1').on('click', function() {
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
$('#button-download2').on('click', function() {
    url = 'index.php?route=report/sale_summary/download_excel_category&token=<?php echo $token; ?>';
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
   $('#button-download3').on('click', function() {
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
    </script> 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?>
