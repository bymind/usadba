<td class="order-id"><?php echo $order['id']; ?></td>
<td class="order-stat order-<?php echo $order['stat_label']; ?>-row"><?php echo $order['stat_text']; ?></td>
<td class="order-time"><?php echo $order['datetime']; ?></td>
<td class="order-phone"><nobr><?php echo $order['phone']; ?></nobr></td>
<td class="order-name"><?php echo $order['name']; ?></td>
<td class="order-price ta-r"><nobr><b><?php echo number_format($order['prod_list']['sumPrice'],0,'.',' '); ?> руб.</b></nobr></td>