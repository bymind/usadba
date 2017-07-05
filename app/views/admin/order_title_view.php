<h3>Заказы
<span class="badge danger" title="новые"><span class="new-num"><?php echo $ordersCounter['new']; ?></span> новые</span>
<span class="badge warning" title="в пути"><span class="progress-num"><?php echo $ordersCounter['progress']; ?></span> в пути</span>
<span class="badge success" title="доставлены"><span class="done-num"><?php echo $ordersCounter['done']; ?></span> доставлено</span>
<span class="badge fail" title="отмена"><span class="fail-num"><?php echo $ordersCounter['fail']; ?></span> отмена</span>
<span class="badge info" title="всего"><span class="all-num"><?php echo $ordersCounter['all']; ?></span> всего</span>
</h3>