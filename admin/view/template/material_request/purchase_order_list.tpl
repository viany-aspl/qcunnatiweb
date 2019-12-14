<?php

echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo "Add New"; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
                <button type="button" data-toggle="tooltip" style="display:none;" title="<?php echo "Delete"; ?>" class="btn btn-danger" onclick="confirm('<?php echo "Do you realy want to delete the order?"; ?>') ? $('#form-order').submit() : false;"><i class="fa fa-trash-o"></i></button>
            </div>
            <h1><?php echo "Purchase Request Process/Inventory"; ?></h1>
            <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
    <?php if (isset($error_warning)) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php } ?>
	<?php if (isset($_SESSION['receive_success_message'])) {		?>
        <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $_SESSION['receive_success_message']; unset($_SESSION['receive_success_message']);?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php } ?>
	<?php if (isset($_SESSION['delete_unsuccess_message'])) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $_SESSION['delete_unsuccess_message']; unset($_SESSION['delete_unsuccess_message']);?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php } ?>
	<?php if (isset($_SESSION['nothing_found_error'])) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $_SESSION['nothing_found_error']; unset($_SESSION['nothing_found_error']);?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php } ?>
	<?php if (isset($_SESSION['input_error'])) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $_SESSION['input_error']; unset($_SESSION['input_error']);?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php } ?>
	<?php if (isset($_SESSION['delete_success_message'])) {		?>
        <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $_SESSION['delete_success_message']; unset($_SESSION['delete_success_message']);?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php } ?>
    <?php if (isset($_SESSION['success_order_message'])) {		?>
        <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $_SESSION['success_order_message']; unset($_SESSION['success_order_message']);?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php } ?>
        <div class="panel panel-default">
            <!--      <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo "Purchase Request List"; ?></h3>
                  </div>-->
            <div class="panel-body">
                <form action="<?php echo $filter;?>" method="post" enctype="multipart/form-data" id="form-filter">
                    <div class="well">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label" for="input-date-added"><?php echo "Order ID:"; ?></label>
                                    <input type="text" name="filter_id" value="<?php if(isset($filter_id)){ echo $filter_id; }?>" placeholder="order id" id="input-id" class="form-control" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label" for="status"><?php echo "Status"; ?></label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <select class="form-control" name="status">
                                                <option value="">--Status--</option>
                                                <option value = "0" <?php if(isset($status)){ if($status=="0"){ ?>selected<?php }} ?>>Pending</option>
                                                <option value="1" <?php if(isset($status)){if($status=="1"){ ?>selected<?php }} ?>>Received</option>

<!--                                                <option value = "0" <?php if(isset($status)){ if($status=="0"){ ?>selected<?php }} ?>>Pending</option>
<option value="1"   <?php if(isset($status)){ if($status=="1"){ ?>selected<?php }} ?>>Approved</option>
<option value = "2" <?php if(isset($status)){ if($status=="2"){ ?>selected<?php }} ?>>Dispatch</option>
<option value = "3" <?php if(isset($status)){ if($status=="3"){ ?>selected<?php }} ?>>Delivered</option>-->
                                            </select>
                                        </span></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label" for="input-date-start"><?php echo "From"; ?></label>
                                    <div class="input-group date">
                                        <input onkeypress="return false" type="text" name="from" value="<?php if(isset($from)) { echo $from; }?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                        </span></div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label" for="input-date-end"><?php echo "To"; ?></label>
                                    <div class="input-group date">
                                        <input onkeypress="return false;" type="text" name="to" value="<?php if(isset($to)) { echo $to; }?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                        </span></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <button class="btn btn-primary pull-right" id="clear-filter" onclick="reset_form();" type="button"> Clear</button>
                                <button class="btn btn-primary pull-right" id="button-filter" style="margin-right:10px;" type="submit"><i class="fa fa-search"></i> Filter</button>
                            </div>
                        </div>
                    </div>
                </form>
                <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-order">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                                    <td class="text-left"><a href="" class="">Store Name</a></td>
                                    <td class="text-left"><a href="" class="">ZI Name</a></td>
                                    <td class="text-left"><a href="" class="">Order Id</a></td>
                                    <td class="text-left"><a href="" class="">Product</td>
                                    <td class="text-left"><a href="" class="">Quantity</a></td>
                                    <td class="text-left"><a href="" class="">Status</a></td>
                                    <td class="text-left"><a href="" class="">Action</a></td>
                                </tr>
                            </thead>
                            <tbody>
                <?php if($order_list){
					foreach($order_list as $order)                                        
					{
                                           // $order_status = '0';
                                            switch($order['order_status_id']){
                                                case 2: $order_status='Approved';
                                                    break;
                                                case 3: $order_status='In-Transit';
                                                    break;
                                                 case 4: $order_status='Dispatch';
                                                    break;
                                                case 5: $order_status='Delivered';
                                                    break;
                                                case 6: $order_status='Cancelled';
                                                    break;
                                                default : 
                                                    $order_status=$order['order_status_id']=='1'?'Pending':'Approved';
                                                    break;
                                            }
				?>
                                <tr>
                                    <td class="text-left"><input type="checkbox" name="selected[]" value="<?php echo $order['id']; ?>" /></td>
                                    <td class="text-left"><?php echo $order['name']; ?></td>
                                    <td class="text-left"><?php echo $order['firstname'].' '.$order['lastname']; ?></td>
                                    <td class="text-left"><?php echo  $order['id'];?></td>
<!--							<td class="text-left"><?php if($order['pre_supplier_bit'] == 1) echo $order['first_name'] . " " . $order['last_name']; else echo "Multiple"; ?></td>-->
                                    <td class="text-left"><?php echo $order['model']  ?></td>
                                    <td class="text-left"><?php echo  $order['quantity'];?></td>
                                    <td class="text-left"><?php echo $order_status;?></td>
                                    <td class="text-left">
                                        <a class="btn btn-info" href="<?php echo $view . '&order_id='.$order['id']; ?>" data-toggle="tooltip" title="view" class="btn btn-primary"><i class="fa fa-eye"></i></a>
                                        <a href="javascript:void(0);" data-toggle="modal" data-zi-name="<?php echo $order['firstname'].' '.$order['lastname'];?>" title="Dispatch Process( <?php echo $order['id'];?> )" data-target="#myModal_delivery" data-id="<?php echo $order['id'];?>" data-title="Dispatch Detail( <?php echo $order['id'];?> )" style="width:40px; margin-left: 5px;
                                            <?php if($order['order_status_id']=='2') { echo 'display:block;'; } 
                                                    elseif ( $order['order_status_id']=='3') {echo 'display:none;';}
                                            else { echo 'display:none;'; } ?>" class="btn btn-info"><i class="fa fa-truck"></i></a></td>
                                </tr>
				<?php
					}
				}?>
                            </tbody>
                        </table>
                    </div>
                </form>
                <div class="row">
                    <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
                    <div class="col-sm-6 text-right"><?php echo $results; ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal_delivery" role="dialog">
    <div class="modal-dialog" style="width: 80%; height: 80%;">    
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="partner_cncl_btn2" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Dispatch Detail</h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" name="send" class="btn btn-info btn-delivery">Generate E-Way Bill</button>
<!--                <button type="button" name="send2" class="btn btn-info btn-delivery">Dispatch Item</button>-->
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>      
    </div>
</div>
<script type="text/javascript">
    jQuery('#myModal_delivery').on('show.bs.modal', function (e) {
        var inv_id = jQuery(e.relatedTarget).data('id');
        var status = jQuery(e.relatedTarget).data('stats');
        var zi_name = jQuery(e.relatedTarget).data('zi-name');
        // var store = jQuery(e.relatedTarget).data('store');
        var url = 'index.php?route=material_request/purchase_order/dispatchDetail&token=<?php echo $token; ?>';
        jQuery(e.currentTarget).find('.modal-title').text(jQuery(e.relatedTarget).data('title'));
        jQuery.post(url, {invoice_no: inv_id,zi_name:zi_name}, function (dta) {
            if (dta.status == 'error') {
                jQuery(e.currentTarget).find('.btn-info').hide();
            } else {
                jQuery('.btn-SendSMS').attr('disabled', false);
                jQuery(e.currentTarget).find('.btn-info').data({'status': status, 'id': inv_id, 'mobile': dta.customer_mobile});
            }
            jQuery(e.currentTarget).find('.modal-body').html(dta.responce);
        }, 'json');
    });

    jQuery('#myModal_delivery').on('click', '.btn-delivery', function (e) {
        e.preventDefault();
        var frm = $('#form-drvrDtl');
        if (frm.find('select').val() == '') {
            alertify.error('Please select vehicle type.');
            return false;
        }

        if (frm.find('select').val() != '6') {
            var alpha = /^[a-zA-Z ]*$/;
            var num = /^[0-9]{10}$/;
            if (jQuery('#input-driver_name').val() == '') {
                alertify.error('Please enter driver name.');
                jQuery('#input-driver_name').focus();
                return false;
            }
            if (!alpha.test(jQuery('#input-driver_name').val())) {
                alertify.error('Driver name should be alphabet only.');
                jQuery('#input-driver_name').focus();
                return false;
            }
            if (jQuery('#mobile').val() == '') {
                alertify.error('Please enter mobile no.');
                jQuery('#mobile').focus();
                return false;
            }
            if (!num.test(jQuery('#mobile').val())) {
                alertify.error('Please enter 10 digit mobile no.');
                jQuery('#mobile').focus();
                return false;
            }
            if (jQuery('#input-vehicle_number').val() == '') {
                alertify.error('Please enter vehicle registration no.');
                jQuery('#input-vehicle_number').focus();
                return false;
            }
        }
        jQuery.post('index.php?route=material_request/purchase_order/process_order&token=<?php echo $token; ?>',
                jQuery('#form-drvrDtl').serialize(), function (data) {
            if (data.status == 'success') {
                alertify.success(data.responce);
                jQuery('.btn-delivery').attr('disabled', 'disabled');
                 
               // jQuery('.btn-delivery').hide();
            } else {
                alertify.error(data.responce);
            }
        }, 'json');
    });


    $('.date').datetimepicker({
        pickTime: false
    });

    function reset_form()
    {
        $('[name=from]').val('');
        $('[name=to]').val('');
        $('[name=filter_id]').val('');
        $('[name=status]').prop('selectedIndex', 0);
    }
</script>
<?php echo $footer; ?> 
