  <div class="grid">
    <div class="row">
            <!-- order list -->
            <div class="order_list">
              <table class="table striped">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Date added</th>
                        <th>Date modified</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                     <tr class="filter">
                      <td align="right"><input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" size="4" style="text-align: right;" /></td>
                      <td><input type="text" name="filter_customer" value="<?php echo $filter_customer; ?>" /></td>
                      <td>
                        <div class="css3-metro-dropdown">
                           <select name="filter_order_status_id">
                              <option value="*">All</option>
                              <?php if ($filter_order_status_id == '0') { ?>
                                <option value="0" selected="selected"><?php echo $text_missing; ?></option>
                              <?php } else { ?>
                                <option value="0"><?php echo $text_missing; ?></option>
                              <?php } ?>
                              <?php foreach ($order_statuses as $order_status) { ?>
                                  <?php if ($order_status['order_status_id'] == $filter_order_status_id) { ?>
                                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                  <?php } else { ?>
                                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                  <?php } ?>
                              <?php } ?>
                           </select>
                        </div>
                      </td>
                      <td align="right"><input type="text" name="filter_total" value="<?php echo $filter_total; ?>" size="4" style="text-align: right;" /></td>
                      <td><input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" size="12" class="date" /></td>
                      <td><input type="text" name="filter_date_modified" value="<?php echo $filter_date_modified; ?>" size="12" class="date" /></td>
                      <td align="center"><a onclick="filter();" class="btn_filter">Filter</a></td>
                    </tr>
                    <?php foreach($rows as $row){ ?>
                        <tr class="data_row">
                            <td align='right'><?= $row['order_id'] ?></td>
                            <td><?= $row['customer'] ?></td>
                            <td><?= $row['status'] ?></td>
                            <td align='right' class='td_total'><?= $row['total'] ?></td>
                            <td><?= $row['date_added'] ?></td>
                            <td><?= $row['date_modified'] ?></td>
                            <td align="center">
                                [<a class="edit" data-order-id="<?= $row['order_id']; ?>" href="#">Edit</a>]
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
              </table>  
            </div>
            <!-- END .order_list -->
            
            <div class="pagination">
                <?php echo $pagination; ?>
            </div>
            <!-- END .pagination -->
            
    </div>
  </div>   
  <!-- END .grid -->
