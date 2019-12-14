<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><!--<button onclick ="print_order()" data-toggle="tooltip" title="<?php echo "Print Order"; ?>" class="btn btn-info"><i class="fa fa-print"></i></button>--><!--<a href="<?php echo $shipping; ?>" target="_blank" data-toggle="tooltip" title="<?php echo $button_shipping_print; ?>" class="btn btn-info"><i class="fa fa-truck"></i></a> <a href="<?php echo $edit; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>--> <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo "Cancel Button"; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo "Orders"; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="panel panel-default" id = "print_div">
	<div class="panel-heading">
		<h3 class="panel-title">
			<i class="fa fa-info-circle"></i>
			<?php echo "Order # " . $order_information['order_info']['id']; ?>
		</h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-lg-3">
				<label>Ordered By:</label>
			</div>
			<div class="col-lg-9">
				<?php echo $order_information['order_info']['firstname'] ." ".$order_information['order_info']['lastname']; ?>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-3">
			<label>
				Purchase order date:
			</label>
			</div>
			<div class="col-lg-9">
				<?php echo $order_information['order_info']['order_date']; ?>
			</div>
		</div>
		<?php if($order_information['order_info']['receive_date'] != '0000-00-00'){?>
		<div class="row">
			<div class="col-lg-3">
				<label>Received On:</label>
			</div>
			<div class="col-lg-9">
				<?php echo $order_information['order_info']['receive_date']; ?>
			</div>
		</div>
		<?php } ?>
		<table class="table table-bordered" id="print_table" border="1">
          <thead>
            <tr>
              <td class="text-left" style="width: 11.11%;">Product Name</td>
              <!--<td class="text-left" style="width: 25%; visibility:hidden;">Attribute Group</td>-->
			  <td class="text-left" style="width: 11.11%;">Option Values</td>
			  <td class="text-left" style="width: 11.11%;">Demand</td>
			  <td class="text-left" style="width: 11.11%;">Total Received Quantity</td>
			  <td class="text-left remaining_quantity" style="width: 11.11%;">Remaining Quantity</td>
			  <td class="text-left" style="width: 11.11%;">Supplier</td>
			  <td class="text-left" style="width: 11.11%;">Quantity from supplier</td>
			  <td class="text-left" style="width: 11.11%;">Price</td>
			  <td class="text-left" style="width: 11.11%;">Total Price</td>
			</tr>
          </thead>
          <tbody>
		  <?php
			$grand_total = 0;
		    $start_loop = 0;
			foreach($order_information['products'] as $product)
			{
		  ?>
            <tr>
              <td class="text-left"><?php echo  $product['name'];?></td>
			  <!--<td class="text-left" style="visibility:hidden;">
			  <?php for($i=0; $i<count($product['attribute_groups']);$i++){
				echo  $product['attribute_groups'][$i] . "<br />";
			  }?>
			  </td>-->
			  <td class="text-left">
			  <?php for($j=$start_loop; $j<($start_loop + count($product['attribute_category']));$j++){
				if($product['attribute_category'][$j] != 'optionvalue')
				{
					echo  $product['attribute_category'][$j] . "<br />";
				}
			  } 
			  $start_loop = $j;
			  ?>
			  </td>
			  <td class="text-left"><?php echo  $product['quantity'];?></td>
			  <td class="text-left"><?php if($product['received_products'] == 0) echo ''; else echo $product['received_products'];?></td>
			  <td class="text-left remaining_quantity"><?php if($product['received_products'] == 0) echo $product['quantity'] - $product['received_products']; else echo $product['quantity'] - $product['received_products'];?></td>
			  <td class="text-left">
			  <?php if(isset($product['supplier_names'])){
				for($i=0; $i<count($product['supplier_names']); $i++)
				{					
			  ?>
			  <?php if($product['supplier_names'][$i] != "default ") echo $product['supplier_names'][$i] . "<br />"; else echo ""; ?>
			  <?php }} ?>
			  </td>
			  <td class="text-left">
			  <?php if(isset($product['quantities'])){
				for($i=0; $i<count($product['quantities']); $i++)
				{					
			  ?>
			  <?php if($product['quantities'][$i] == 0) echo ''; else echo $product['quantities'][$i] . "<br />"; ?>
			  <?php }} ?>
			  </td>
			  <td class="text-left">
			  <?php if(isset($product['prices'])){
				for($i=0; $i<count($product['prices']); $i++)
				{					
			  ?>
			  <?php if($product['prices'][$i] == 0) echo ''; else echo $product['prices'][$i] . "<br />"; ?>
			  <?php }} ?>
			  </td>
			  <td class="text-left">
			  <?php if(isset($product['prices'])){
				for($i=0; $i<count($product['prices']); $i++)
				{
					$grand_total += intval($product['prices'][$i]) * intval($product['quantities'][$i]);
			  ?>
			  <?php if((intval($product['prices'][$i]) * intval($product['quantities'][$i])) == 0) echo ''; else echo (intval($product['prices'][$i]) * intval($product['quantities'][$i])) . "<br />"; ?>
			  <?php }} ?>
			  </td>
			</tr>
		<?php
			}
		?>
			<tr>
				<td class="text-right" id="set_colspan" colspan="8"><b>Grand Total:</b></td>
				<td class ="text-left"><?php if($grand_total == 0) echo ''; else echo $grand_total; ?></td>
			</tr>
			<!--<tr>
				<td class="text-right" colspan="4"><b>Ordered By:</b></td>
				<td class="text-left"><?php echo $order_information['order_info']['firstname'] ." ".$order_information['order_info']['lastname']; ?></td>
			</tr>
			<tr>
				<td class="text-right" colspan="4"><b>Purchase order date:</b></td>
				<td class="text-left"><?php echo $order_information['order_info']['order_date']; ?></td>
			</tr>
			<tr>
				<td class="text-right" colspan="4"><b>Purchase order receive date:</b></td>
				<td class="text-left"><?php echo $order_information['order_info']['receive_date']; ?></td>
			</tr>-->
			</tbody>
        </table>
		<a id="download_pdf" href="<?php echo $pdf_export . '&export=1'; ?>" target="_blank"><span class="input-group-btn">
			<button type="button" class="btn btn-primary pull-right"><?php echo "Download as pdf"; ?></button>
		</span></a>
		<!--<div class="row" align="right">
			<div class="col-lg-10">
				<label>Grand Total:</label>
			</div>
			<div class="col-lg-2">
				<?php echo $grand_total; ?>
			</div>
		</div>-->
	</div>
	
  </div>
  
</div>
<script type="text/javascript">
	function print_order()
	{
		var prtContent = document.getElementById("print_div");
		var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
		
		WinPrint.document.writeln('<!DOCTYPE html>');
        WinPrint.document.writeln('<html><head><title></title>');
        WinPrint.document.writeln('<style>table{border:1px; border-collapse:collapse;}');
        WinPrint.document.writeln('table, td, th {border: 1px solid black;}');
		WinPrint.document.writeln('label{font-weight:bold}');
		WinPrint.document.writeln('.text-right{text-align:right;}');
		WinPrint.document.writeln('.remaining_quantity{display:none;}');
		document.getElementById('set_colspan').setAttribute('colspan','7');
		WinPrint.document.writeln('</style></head><body>');
		WinPrint.document.write(prtContent.innerHTML);
		WinPrint.document.writeln('</body></html>');
		WinPrint.document.close();
		WinPrint.focus();
		WinPrint.print();
		WinPrint.close();
	}
	
	function download_pdf()
	{
		var doc = new jsPDF();
		doc.fromHTML($('#print_div').get(0),20,20,{
			'width':5000
		});
		doc.save('test.pdf');
	}
	
	function print_order()
	{
		document.getElementById("download_pdf").style.display = "none";
        var printContents = document.getElementById('print_div').innerHTML;
        var originalContents = document.body.innerHTML;
		document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
		document.getElementById("download_pdf").style.display = "block";
    }
	
	
	function demoFromHTML() {
    var pdf = new jsPDF('l', 'pt', 'letter',true);
	//pdf.setFontSize(8);
	source = $('#print_div')[0];
	specialElementHandlers = {
        '#bypassme': function (element, renderer) {
            return true
        }
    };
    margins = {
        top: 100,
        bottom: 80,
        left: 80,
        width:1000
    };
    pdf.fromHTML(
    source,
    margins.left,
    margins.top, {
        'width': margins.width,
        'elementHandlers': specialElementHandlers
    },

    function (dispose) {
        pdf.save('Test.pdf');
    }
	);
}
</script>
<?php echo $footer; ?>