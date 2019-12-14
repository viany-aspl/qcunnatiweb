<!DOCTYPE html>
<html>
<head>
    <title>POS | Login</title>
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
<body class="metro page-pos-login">
 <div class="container">    
  <div class="grid">
    <div class="row">
        
       <div class="login_wrapper">
          <div class="message_wrapper"> 
            <?php if ($error_warning) { ?>
              <div class="warning"><?php echo $error_warning; ?></div>
            <?php } ?>      
          </div>
          <div class="form_wrapper">          
          <div class="panel-header bg-lightBlue fg-white"><h2>Login e-POS</h2></div>      
          <form action="index.php?route=common/login" method="post" enctype="multipart/form-data" id="form">            
            <input type="hidden" name="is_pos" value="1" />
            <table style="width: 100%;">
              <tr>
                <td style="text-align: center;" rowspan="4">
                    <img src="view/image/login.png" alt="<?php echo $text_login; ?>" />
                </td>
              </tr>
              <tr>
                <td><?php echo $entry_username; ?><br />
                  <div class="input_wrapper">
                        <div class="css3-metro-dropdown">
                          <select name="username">
                            <?php foreach($users as $user){ ?>  
                              <option><?= $user['username'] ?></option>
                            <?php } ?>  
                          </select>
                        </div>  
                      <br />
                      <br />
                      <?php echo $entry_password; ?><br />
                      <input type="password" name="password" style="margin-top: 4px;" />
                  </div>
                  <!-- END .input_wrapper -->
                  </td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                  <td style="text-align: right;">
                      <button type="submit" class="pull-right large info button"><?php echo $button_login; ?></button>
                  </td>
              </tr>
            </table>
            <?php if ($redirect) { ?>
            <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
            <?php } ?>
          </form>
          </div>
          <!-- END .form-wrapper
        </div>
        <!-- END .login_wrapper -->        
    </div>
    <!-- END .row -->
  </div>
  <!-- END .grid -->
 </div>
 <!-- END .container -->
</body>
</html>
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
	//	login();
	}
});

function login(){
    $.post('index.php?route=common/login/validate_ajax', $('#form input,#form select'),function(data){
         var data = JSON.parse(data);

         if(data['error']){
            $('.message_wrapper').html('<div class="warning">Invalid username or password.</div>'); 
         }

         if(data['success']){
             location = 'index.php?route=pos/pos&token='+data['token'];              
         }   
    });
}
//--></script> 