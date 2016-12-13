<div class="container">
	<div class="row">
	<div class="col-xs-12">
		<div class="crumbs mb-20">
			<div class="crumb" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
				<a href="/" itemprop="url">
					<span itemprop="title">Главная<span class='hidden-xs'> страница</span></span>
				</a>
			</div>
			<?php
				foreach ($breadsData as $key => $value) {
				?>
				<span class="crumb-divider"> → </span>
				<div class="crumb" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
					<a href="<?php echo $value; ?>" itemprop="url">
						<span itemprop="title"><?php echo $key; ?></span></span>
					</a>
				</div>
				<?php
				}

			?>
		</div>
	</div>
	</div>
</div>