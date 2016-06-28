<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><i class="fa fa-shopping-cart"></i> <?php echo $heading_title; ?></h3>
  </div>
  <div class="table-responsive">
    <table class="table">
      <thead>
        <tr>
          <td class="recent-tbl-id text-right"><?php echo $column_order_id; ?></td>
          <td><?php echo $column_customer; ?></td>
          <td class="recent-tbl-status"><?php echo $column_status; ?></td>
          <td><?php echo $column_date_added; ?></td>
          <td class="text-right"><?php echo $column_total; ?></td>
          <td class="recent-tbl-action text-right"><?php echo $column_action; ?></td>
        </tr>
      </thead>
      <tbody>
        <?php if ($orders) { ?>
        <?php foreach ($orders as $order) { ?>
        <tr>
          <td class="recent-tbl-id text-right"><?php echo $order['order_id']; ?></td>
          <td><?php echo $order['customer']; ?></td>
          <td class="recent-tbl-status"><?php echo $order['status']; ?></td>
          <td><?php echo $order['date_added']; ?></td>
          <td class="text-right"><?php echo $order['total']; ?></td>
          <td class="recent-tbl-action text-right"><a href="<?php echo $order['view']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info"><i class="fa fa-eye"></i></a></td>
        </tr>
        <?php } ?>
        <?php } else { ?>
        <tr>
          <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>
