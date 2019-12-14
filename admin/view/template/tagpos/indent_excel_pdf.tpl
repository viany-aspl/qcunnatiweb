<div id="content">
 <script src="https://unnati.world/shop/admin/view/javascript/jquery/jquery-2.1.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/jsbarcode/3.3.20/JsBarcode.all.min.js"></script>
 
  <script type="text/javascript">
              $(document).ready(function () {
              JsBarcode(".barcode").init();
              });
              </script>
              <style type="text/css">
              .barcode
              {
              	float:right;
              }
              </style>
  <div class="container-fluid">
    <div class="panel panel-default">
	<br/>
      <h3 class="panel-title" style="text-align:center;"><?php echo $main_heading; ?> </h3>
		<h4 class="panel-title" style="text-align:center;"><?php echo $sub_heading; ?> </h4>
	  <br/>
      <div class="panel-body">
       

          <style>
             td,th{border: 1px solid silver;text-align: center; }
              </style>
        <div class="table-responsive">
            <table border="0" cellspacing="0" cellpadding="0" class="table table-bordered" style="width: 95%;">
            <thead>
			 
              <tr>
               
               <th style="border-right: none;" class="text-left">SlD</th>
                <th style="border-right: none;" class="text-right">INDENT NO</th>
              
				<th style="border-right: none;"  class="text-right">OFC</th>
                <th style="border-right: none;" class="text-right">OFFICER</th>
                <th style="border-right: none;" class="text-right">MOC</th>
                <th style="border-right: none;" class="text-right">MOTIVATOR</th>
                
				<th style="border-right: none;" class="text-right">VCD</th>
                <th style="border-right: none;" class="text-right">GCD</th>
				<th style="border-right: none;" class="text-right">NAME</th>
				<th style="border-right: none;" class="text-right">FATHER</th>
				<th style="border-right: none;" class="text-right">Item</th>
				<th style="border-right: none;" class="text-right">Qty</th>
				<th style="border-right: none;" class="text-right">Rate</th>
				<th style="border-right: none;" class="text-right">ADV.AMT</th>
				<th style="border-right: none;" class="text-right">HELD. AMT</th>
				<th style="border-right: none;" class="text-right">ACTI.AMT</th>
				<th style="border-right: none;" class="text-right">RET.AMT</th>
				<th style="border-right: none;" class="text-right">CP AMT</th>
				
                
              </tr>            </thead>
            <tbody>
              <?php if ($orders) { $aa=1; $total=0; ?>
              <?php foreach ($orders as $un) { //print_r($order); exit;?>
			  
              <tr>
					<td class="text-left"><?php echo $aa; ?></td>
					
					<td class="text-left">
					<img src='https://unnati.world/shop/admin/barcode.php?inv=<?php echo $un['INDENT_NO']; ?>' />
					
					
					</td>
					
					<td class="text-left"><?php echo $un['OFC']; ?></td>
                  <td class="text-left"><?php echo $un['OFFICER']; ?></td>
					<td class="text-left"><?php echo $un['MOC']; ?></td>
                  <td class="text-left"><?php echo $un['MOTIVATOR']; ?></td>
					<td class="text-left"><?php echo $un['VCD']; ?></td>
                  <td class="text-left"><?php echo $un['GCD']; ?></td>
					<td class="text-left"><?php echo $un['NAME']; ?></td>
                  <td class="text-left"><?php echo $un['FATHER']; ?></td>
					
                  <td class="text-left"><?php echo $un['Item']; ?></td>
					<td class="text-left"><?php echo $un['Qty']; ?></td>
                  <td class="text-left"><?php echo $un['Rate']; ?></td>
					<td class="text-left"><?php echo $un['ADV_AMT']; ?></td>
                  <td class="text-left"><?php echo $un['HELD_AMT']; ?></td>
					<td class="text-left"><?php echo $un['ACTI_AMT']; ?></td>
                  <td class="text-left"><?php echo $un['RET_AMT']; ?></td>
					<td class="text-left"><?php echo $un['CP_AMT']; ?></td>
					</tr>
					
					
              <?php $aa++; } }?>
            </tbody>
          </table>
        </div>
		<br/>
		<br/>
		

      </div>
    </div>
  </div>
  </div> 
 