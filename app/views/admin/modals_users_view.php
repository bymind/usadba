
<!-- modals -->

<div class="modal fade new-user-modal-sm" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Создание нового аккаунта</h4>
      </div>
       <form class="form">
      <div class="modal-body">
           <div class="form-group">
            <label for="loginInput">Логин</label>
             <input type="text" required name="login" class="form-control" id="loginInput" placeholder="Логин">
           </div>
           <div class="form-group">
            <label for="emailInput">Email</label>
             <input type="email" required name="email" class="form-control" id="emailInput" placeholder="Email">
           </div>
           <div class="form-group">
            <label for="passwInput">Пароль</label>
             <input type="password" required name="passw" class="form-control" id="passwInput" placeholder="passw">
           </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
        <button type="submit" class="btn btn-primary">Добавить аккаунт</button>
      </div>
       </form>
    </div>
  </div>
</div>


<div class="modal fade user-modal-md" role="dialog" aria-labelledby="mySmallModalLabel2">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Аккаунт</h4>
      </div>
      <div class="modal-body">
        <button type="button" class="btn btn-warning btn-block btn-lg" disabled="disabled">Редактировать</button>
        <button type="button" class="btn btn-danger go-delete  btn-lg btn-block" data-id="">Удалить</button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade shure-modal-md" role="dialog" aria-labelledby="mySmallModalLabel2">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Удалить аккаунт</h4>
      </div>
      <div class="modal-body">
        <button type="button" class="btn btn-primary btn-lg btn-block" data-dismiss="modal">Отмена</button>
        <button type="button" class="btn btn-danger btn-lg btn-block" data-id="">Удалить</button>
      </div>
    </div>
  </div>
</div>


<!-- modals end-->