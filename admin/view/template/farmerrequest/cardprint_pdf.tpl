
<style>
@font-face {
font-family: 'poppinsregular';
src: url('poppins-regular-webfont.woff2') format('woff2'),
url('poppins-regular-webfont.woff') format('woff');
font-weight: normal;
font-style: normal;

}

@font-face {
font-family: 'jura';
src: url(fonts/jura-regular-webfont.ttf),
font-weight: normal;
font-style: normal;
}


body {
background: #efefef;
}

.mt-10{
margin-top:10px;
}
.mt-30{
margin-top:30px;
}

.mt-40{
margin-top:40px;
}
.centerbox {
    position: absolute;
    left: 30%;
    top: 20%;
    width: 40%;
    height: 328px;
    background: #fff;
    color: white;
    text-align: center;
    box-shadow: 0 0 30px rgba(0, 0, 0, 0.2);
    border-bottom: 5px solid #e67e22;
    border-radius: 5px;
    transform: scale(0.6);
}
.grey{
width:35%;
height:auto;
float:left;
padding:10px;
background-color:#f0f0ef;
}
.white{
width:55%;
height:auto;
float:left;
padding:10px;
}

.dalmia_logo{
width:100%;
margin:10px auto;
}

.Qr_code{
border-image: url(border.png);
margin:-10px 0 5px -3px;
}
.logo_icon{
width:35%;
float:left;
margin:5px -20px; 0px 0px;
}
.logo_text{
width:55%;
float:right;
margin:30px 15px;
}

.container{
width:100%;
float:left;
}


.input {
    border: 0px solid #fff;
    font: 400 16px/22px 'Poppins';
    float: left;
    color: #000;
    width: 100%;
    text-align: left;
}


.lable{
margin:10px 0px;
color:#656565 ;
}
.small{
border:0px solid #fff;
font:300 12px/16px 'Poppins';
float:left;
width:100%;
text-align:left;
margin-top: -1px;
color:#656565;
}
.semibox{
float:left;
width:50%;
}

.serial_no{
width:100%;
float:left;
}
.serial_no h1{
font:500 28px/0px 'jura';
float: left;
color:#e67e22;
}


</style>
	<?php// print_r($data); 
//echo $data['farmer_name'];
	?>
   <div class="centerbox" style=" position: absolute;
    left: 30%;
    top: 20%;
    width: 40%;
    height: 328px;
    background: #fff;
    color: white;
    text-align: center;
    box-shadow: 0 0 30px rgba(0, 0, 0, 0.2);
    border-bottom: 5px solid #e67e22;
    border-radius: 5px;
    transform: scale(0.6);">
 <div class="grey" style="width:35%;
height:auto;
float:left;
padding:10px;
background-color:#f0f0ef;">

 <div class="dalmia_logo" style="width:100%;
margin:10px auto;">
 <img class="mt10" style="margin-top:10px;" src="<?php echo $data['cname']; ?>">
 </div>

 <div class="Qr_code" style="border-image: url(border.png) !important;
margin:-10px 0 5px -3px !important;">
 <img class="image_size" src="<?php echo $data['CARD_QR_IMG']; ?>">
 </div>

 </div>

<div class="white" style="width:55%;
height:auto;
float:left;
padding:10px;">
 <div class="logo_icon" style="width:35%;
float:left;
margin:5px -20px; 0px 0px;">
 <img src="view/image/logoicon.png">
 </div>
 <div class="logo_text" style="width:55%;
float:right;
margin:30px 15px;">
 <img src="view/image/unnati.png">
 </div>
 <div class="container mt-10" style="width:100%;
float:left;">
 
 <label for="Name" class="input"  style="border: 0px solid #fff;
    font: 400 16px/22px 'Poppins';
    float: left;
    color: #000;
    width: 100%;
    text-align: left;" id="Grower_Name_level"><?php echo $data['farmer_name']; ?></label>
 <label class=" small"  style="border:0px solid #fff;
font:300 12px/16px 'Poppins';
float:left;
width:100%;
text-align:left;
margin-top: -1px;
color:#656565;" for="name">Farmer Name</label>

 <label for="Name" class="input"   style="border: 0px solid #fff;
    font: 400 16px/22px 'Poppins';
    float: left;
    color: #000;
    width: 100%;
    text-align: left;"  id="Father_Name_level"><?php echo $data['father_name']; ?></label>
 <label class=" small" style="border:0px solid #fff;
font:300 12px/16px 'Poppins';
float:left;
width:100%;
text-align:left;
margin-top: -1px;
color:#656565;" for="name">Father Name</label>

 
 <label class="input" id="Grower_Code_level"   for="name"><?php echo $data['grower_id']; ?></label>
 <label class=" small" style="border:0px solid #fff;
font:300 12px/16px 'Poppins';
float:left;
width:100%;
text-align:left;
margin-top: -1px;
color:#656565;" for="name">Grower Id</label>
 </div>

 <div class=semibox>
 <label for="Name" class="input" style="border: 0px solid #fff;
    font: 400 16px/22px 'Poppins';
    float: left;
    color: #000;
    width: 100%;
    text-align: left;" id="Village_level"><?php echo $data['village']; ?></label>
 <label class=" small"  style="border:0px solid #fff;
font:300 12px/16px 'Poppins';
float:left;
width:100%;
text-align:left;
margin-top: -1px;
color:#656565;" for="name">Village</label>
 </div>
 <div class="semibox" style="float:left;
width:50%;">
 <label for="Name" style="border: 0px solid #fff;
    font: 400 16px/22px 'Poppins';
    float: left;
    color: #000;
    width: 100%;
    text-align: left;" class="input" id="Unit_level"><?php echo $data['unit']; ?></label>
 <label class=" small" style="border:0px solid #fff;
font:300 12px/16px 'Poppins';
float:left;
width:100%;
text-align:left;
margin-top: -1px;
color:#656565;" for="name">Unit</label>
 </div>

 <div class="serial_no" style="width:100%;
float:left;">
 <h1  id="Card_Serial_Number_level"><?php echo $data['card_number']; ?></h1>
 </div>


 </div>


</div>