
<link href="view/javascript/ca_Style.css" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Jura" rel="stylesheet">

<div id="myModal" class="modal fade" data-backdrop="static"  role="dialog">
<div class="modal-dialog" >

<!-- Modal content-->
<div class="modal-content">
<div class="modal-header" style="height:60px;">
<!--<span id="btn_html"></span>

<button type="button" class="close pull-right" onclick="qrimagedelete();"  data-dismiss="modal">&times;</button>
-->
</div>
<div class="modal-body" id="printarea">

<div class="centerbox">
<?php //print_r($data); ?>
<div class="grey" style="height:100%">

<div class="dalmia_logo center">
<img class="mt10" id="cname" src="view/image/DSCL.png">
</div>

<div class="Qr_code center" id="qr_img_div">
<img id="pimage" class="pull-right" src="view/image/processing_image.gif" style="height:60px; margin-right:40px; display:none;" />	
<img class="qr_image" id="qr_img" src="<?php echo $qr_img; ?>">

</div>

</div>

<div class="white">
<div class="logo_icon">
<img src="view/image/logoicon.png">
</div>
<div class="logo_text">
<img src="view/image/unnati.png">
</div>

<div class="container -pl-15">
<label style="display:none;" for="qimage" class="input"  id="qimage"></label>
<label for="Name" class="input" id="Grower_Name_level"><?php echo $farmer_name; ?></label>
<label class=" small" for="name">Farmer Name</label>

<label for="Name" class="input" id="Father_Name_level"><?php echo $father_name; ?></label>
<label class=" small" for="name">Father Name</label>


<label class="input" id="Grower_Code_level" for="name"><?php echo $Grower_Code_level; ?></label>
<label class=" small" for="name">Grower Id</label>
</div>

<div class=semibox>
<label for="Name" class="input" id="Village_level"><?php echo $Village_level; ?></label>
<label class=" small" for="name">Village</label>
</div>
<div class=semibox>
<label for="Name" class="input" id="Unit_level"><?php echo $Unit_level; ?></label>
<label class=" small" for="name">Unit</label>
</div>

<div class="serial_no">
<h1 id="Card_Serial_Number_level"><?php echo $Card_Serial_Number_level; ?></h1>
</div>

</div>
</div>





</div>

</div>

</div>
</div>
 