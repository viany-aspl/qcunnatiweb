<?php echo $header; ?><?php echo $column_left; ?>
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
    <div class="row">
        <div class="col-lg-12">
         <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <td class="right"><?php echo $column_username; ?></td>
                <td class="left"><?php echo $column_name; ?></td>
                <td class="left"><?php echo $column_cash; ?></td>
                <td class="left"><?php echo $column_card; ?></td>
                <td>Today's Cash</td>
                <td>Today's Card</td>
                <td class="right"><?php echo $column_action; ?></td>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($rows as $row) { ?>
              <tr>
                <td class="right"><?php echo $row['username']; ?></td>
                <td class="left"><?php echo $row['firstname'].' '.$row['lastname']; ?></td>
                <td class="left"><?php echo $row['cash']; ?></td>
                <td class="left"><?php echo $row['card']; ?></td>
                <td><?php echo $row['total_cash']; ?></td>
                <td><?php echo $row['total_card']; ?></td>
                <td class="right">     
                    [ <a href='index.php?route=pos/dashboard/orderHistory&user_id=<?= $row["user_id"]."&token=".$token; ?>'>Orders</a> ]
                    &nbsp;&nbsp;
                    [ <a href='index.php?route=pos/dashboard/history&user_id=<?= $row["user_id"]."&token=".$token; ?>'>History</a> ]
                    &nbsp;&nbsp;
                    [ <a data-user-id="<?php echo $row['user_id']; ?>" class='withdraw' href='#withdraw_wrapper'>Withdraw</a> ]
                </td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
    </div><!-- END .row -->
  </div><!-- END .container-fluid -->
</div><!-- END .content -->
          
<!--========================================== withdraw pop up ============================================-->
<div class="hide">
<div id="withdraw_wrapper">
    <div class="withdraw_form">
        <h3>Withdraw cash from user</h3><hr>
        
        <div class="message_wrapper"></div>
        
        <table>
            <tr>
                <td><span class="label2">Amount( <?= $currency_left.' '.$currency_right; ?> )</span></td>
                <td>
                    <div class="input-control text">
                        <input class="amount" type="text" name="amount" />                     
                    </div> 
                    <input type="hidden" name="user_id" />                    
                </td>
            </tr>
            <tr>
                <td></td>
                <td><button name="withdraw" value="Withdraw" class='btn_withdraw button'>Withdraw</button></td>
            </tr>
        </table>
        <div class="bottom">*refresh page after withdraw to show changes</div>
    </div>
</div>
<!-- END order_wrapper -->
</div>
<!-- END .hide -->
<?php echo $footer; ?>

<script>

$("a.withdraw").click(function(){
    $('input[name="user_id"]').val($(this).attr('data-user-id'));
    $('.message_wrapper').html('');
});     
      
$("a.withdraw").fancybox({
        maxWidth	: 450,
        maxHeight	: 190,
        fitToView	: false,
        autoSize	: false,
        closeClick	: false,
        openEffect	: 'none',
        closeEffect	: 'none'
});

$('.btn_withdraw').click(function(){
    $.post("index.php?route=pos/dashboard/withdraw&token=<?= $token ?>",{ user_id: $('input[name="user_id"]').val(), amount: $('.amount').val() }, function(data){
        var data = JSON.parse(data);
        
        if(data['success']){
            $('.message_wrapper').html('<div class="success">'+data['success']+'</div>');
        }else if(data['error']){
            $('.message_wrapper').html('<div class="warning">'+data['error']+'</div>');
        }               
    });
});
</script>