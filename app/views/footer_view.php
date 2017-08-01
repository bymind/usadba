<footer>

	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<div class="footer-links-box shadow br-2">
					<div class="row">
						<div class="col-xxs-6 col-xas-6 col-xs-6 col-sm-3">
							<div class="footer-link">
							<?php
							$footerLinks = unserialize(CONFIG_FOOTER_LINKS);
							for ($i = 1; $i <= 5; $i++ ) {
								?>
								<span class="link"><a href="<?php echo $footerLinks[$i][1];?>"><?php echo $footerLinks[$i][0];?></a></span>
								<?
								} ?>
							</div>
						</div>
						<div class="col-xxs-6 col-xas-6 col-xs-6 col-sm-3">
							<div class="footer-link">
								<?php
								for ($i = 6; $i <= 10; $i++ ) {
									?>
									<span class="link"><a href="<?php echo $footerLinks[$i][1];?>"><?php echo $footerLinks[$i][0];?></a></span>
									<?
									} ?>
							</div>
						</div>
						<div class="col-xxs-12 col-xs-12 col-sm-4 col-sm-push-2 col-lg-3 col-lg-push-3 mt-xs-20">
							<div class="footer-contacts">
								<div class="title">
									Контактная информация
								</div>
								<div class="addr mb-10">
									<?php echo nl2br(CONFIG_SITE_ADDRESS); ?>
								</div>
								<div class="phone mb-10">
									<a href="tel:<?php echo nl2br(CONFIG_SITE_PHONE); ?>" class="footer-phone"><?php echo nl2br(CONFIG_SITE_PHONE); ?></a>
								</div>
								<div class="terms">
									<a href="/terms">Пользовательское соглашение</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="container-fluid light-grey">
		<div class="copyright-line">
		<div class="row">
			<div class="col-xs-12">
				<span class="copyright">
					<?php echo date('Y'); ?> © <?php echo nl2br(CONFIG_SITE_COPYRIGHT); ?>
				</span>
				<span class="ref">
					<a href="http://bymind.ru" title="Designed and developed ByMind.ru" target="_blank">Designed and developed</a>
					<a class="logo" href="http://bymind.ru" title="Designed and developed ByMind.ru" target="_blank"></a>
				</span>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">

			</div>
		</div>
		</div>
	</div>
</footer>