<h3><?php echo $heading_title; ?></h3>
<div class="row">
<style>
.caption{
padding: 0px 10px;
}
</style>
  <?php foreach ($products as $product) { ?>
  <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
    <div class="product-thumb transition">
      <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
      <div class="caption">
        <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
        <p><?php echo $product['description']; ?></p>
        <?php if ($product['rating']) { ?>
        <div class="rating">
          <?php for ($i = 1; $i <= 5; $i++) { ?>
          <?php if ($product['rating'] < $i) { ?>
          <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
          <?php } else { ?>
          <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
          <?php } ?>
          <?php } ?>
        </div>
        <?php } ?>
        <?php if ($product['price']) { ?>
        <!--<p class="price">
          <?php if (!$product['special']) { ?>
          <?php echo $product['price']; ?>
          <?php } else { ?>
          <span class="price-new"><?php echo $product['special']; ?></span> <span class="price-old"><?php echo $product['price']; ?></span>
          <?php } ?>
          <?php if ($product['tax']) { ?>
          <span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
          <?php } ?>
        </p>--> 
        <?php } ?>
      </div>
      <div class="button-group">
          <button type="button" onclick="regQuery('<?php echo $product['product_id']; ?>','<?php echo $product['name']; ?>');"><i class="fa fa-envelope" aria-hidden="true"></i> <span class="hidden-xs hidden-sm hidden-md">&nbsp;&nbsp;&nbsp;<?php echo $button_enquiry; ?></span></button>
        
      </div>
     <!-- <div class="button-group">
        <button type="button" onclick="cart.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $button_cart; ?></span></button>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart"></i></button>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-exchange"></i></button>
      </div>-->
    </div>
  </div>
  <?php } ?>
</div>
<div class="container">
  <!--  <h2>Modal Example</h2>
 Trigger the modal with a button 
  <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button>
-->
  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Query Form </h4>
        </div>
        <div class="modal-body">
        <form id="enqdata" name="enqdata">
            <div class="form-group">
            <label for="input-username">Product</label>
            <div class="input-group"><span class="input-group-addon"><i class="fa fa-shopping-cart" aria-hidden="true"></i></span>
                <input type="hidden" name="QryProdId" value="" id="QryProdId" class="form-control" readonly="readonly">
                <input type="text" name="QryProdName" value="" id="QryProdName" class="form-control" readonly="readonly">
            </div>
            </div>
            <div class="form-group">
            <label for="input-username">Mobile Number</label>
            <div class="input-group"><span class="input-group-addon"><i class="fa fa-mobile"></i></span>
                <input onkeypress="return validate_to_number(event)" maxlength="10" type="text" name="Qrymobile" value="" placeholder="Mobile Number" id="Qrymobile" class="form-control">
            </div>
            </div>
            <!--<div class="form-group">
            <label for="input-username">Message</label>
            <div class="input-group"><span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                <textarea class="form-control col-xs-12" id="Qrymessage" name="Qrymessage"></textarea>
            </div>
            </div>-->
            <div class="text-right">
                <button type="button" class="btn btn-primary" onclick="SubData();">Submit</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </form>
        </div>
        <!--<div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>-->
      </div>
      
    </div>
  </div>
  
</div>
