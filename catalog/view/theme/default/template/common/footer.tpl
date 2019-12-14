<footer>
  <div class="container">
    <div class="row">
    
    <div class="col-sm-3">
        <h5><?php echo $text_information; ?></h5>
        <ul class="list-unstyled">
          
          <li><a href="https://unnati.world/#about">About Us</a></li>
          <li><a href="#">Experience Center</a></li>
          <li><a href="https://unnati.world/index.php?id=3">Privacy Policy</a></li>
          
        </ul>
      </div>    
    
      <!--<?php if ($informations) { ?>
      <div class="col-sm-3">
        <h5><?php echo $text_information; ?></h5>
        <ul class="list-unstyled">
          <?php foreach ($informations as $information) { ?>
          <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
          <?php } ?>
        </ul>
      </div>
      <?php } ?>
      -->
      <div class="col-sm-3">
        <h5><?php echo $text_service; ?></h5>
        
        <ul class="list-unstyled">
          <li><a href="https://unnati.world/#Contact"><?php echo $text_contact; ?></a></li>
          <li><a href="#" onclick="return regQuery_partner();"><?php echo 'Partner connect'; ?></a></li>
          <!--
          <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
          <li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
          
          <li><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>-->
        </ul>
      </div>
     <!--
     <div class="col-sm-3">
        <h5><?php echo $text_service; ?></h5>
        <ul class="list-unstyled">
          <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
          <!--
          <li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
          
          <li><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
        </ul>
      </div>
    
    <div class="col-sm-3">
        <h5><?php echo $text_extra; ?></h5>
        <ul class="list-unstyled">
          <li><a href="<?php echo $manufacturer; ?>"><?php echo $text_manufacturer; ?></a></li>
          <li><a href="<?php echo $voucher; ?>"><?php echo $text_voucher; ?></a></li>
          <li><a href="<?php echo $affiliate; ?>"><?php echo $text_affiliate; ?></a></li>
          <li><a href="<?php echo $special; ?>"><?php echo $text_special; ?></a></li>
        </ul>
      </div>
    -->
      <!--<div class="col-sm-3">
        <h5><?php echo $text_account; ?></h5>
        <ul class="list-unstyled">
          <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
          <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
          <li><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></li>
          <li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
        </ul>
      </div>-->
    </div>
    <hr>
    <p>An Unit of Akshamaala Solutions Pvt Ltd. @ 2016<!--<?php echo $powered; ?>--></p> 
  </div>
</footer>


<!-- Modal -->
  <div class="modal fade" id="myModal_partner" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" id="partner_cncl_btn2" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">If you have a quality Agri product and want to reach out to farmers via our network, please get in touch with us.</h4>
        </div>
        <div class="modal-body">
        <form action="#" method="post" enctype="multipart/form-data">
            <div class="form-group">
            <label for="input-username">Name</label>
            <div class="input-group"><span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                
                <input type="text" name="Name" value="" id="Name" placeholder="Name" required="required" class="form-control" />

            </div>
            </div>
            <div class="form-group">
            <label for="input-username">Firm Name</label>
            <div class="input-group"><span class="input-group-addon"><i class="fa fa-building-o" aria-hidden="true"></i></span>
                
                <input type="text" name="Firm_Name" value="" id="Firm_Name" placeholder="Firm Name" class="form-control" />

            </div>
            </div>
            <div class="form-group">
            <label for="input-username">Mobile Number</label>
            <div class="input-group"><span class="input-group-addon"><i class="fa fa-mobile"></i></span>
                <input onkeypress="return validate_to_number(event)" required="required" maxlength="10" type="text" name="Mobile_Number" value="" placeholder="Mobile Number" id="Mobile_Number" class="form-control">
            </div>
            </div>
            <div class="form-group">
            <label for="input-username">Email ID</label>
            <div class="input-group"><span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                <input type="email" name="Email_ID" value="" placeholder="Email ID" id="Email_ID" class="form-control">
            </div>
            </div>
            <div class="form-group">
            <label for="input-username">Message </label>
            <div class="input-group"><span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                <textarea name="Message" id="Message" class="form-control" placeholder="Message"></textarea>
            </div>
            </div>
            <!--<div class="form-group">
            <label for="input-username">Message</label>
            <div class="input-group"><span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                <textarea class="form-control col-xs-12" id="Qrymessage" name="Qrymessage"></textarea>
            </div>
            </div> onclick="regQuery('<?php echo $product['product_id']; ?>','<?php echo $product['name']; ?>');"-->
            <div class="text-right">
                <button type="button" id="partner_sbmt_btn" onclick="SubData_partner();" class="btn btn-primary">Submit</button>
                <button type="button" id="partner_cncl_btn" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </form>
        </div>
        <!--<div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>-->
      </div>
      
    </div>
  </div>


</body></html>
