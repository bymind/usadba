<?php
						foreach ($orders as $order) {
							?>
							<tr class="order-header" id="order-<?=$order['id']?>" data-orderid="<?php echo $order['id']; ?>">
							<?php include "order_header_view.php"; ?>
							</tr>
							<?php include "order_body_view.php";
						}