<script src="/js/admin/login.js"></script>
<div class="main-content">
	<div class="login-box">
		<div class="login-box-title">Для входа в административную панель введите ваши почту и пароль или обратитесь к главному администратору</div>
<?php var_dump($_SESSION); ?>
		<div class="underline login-i"><div class="before">Email</div><input type="text"></div>
		<div class="underline passw-i"><div class="before">Пароль</div><input type="password"></div>
		<button class="login-btn">Войти</button>
		<div class="ans"></div>
	</div>
</div>