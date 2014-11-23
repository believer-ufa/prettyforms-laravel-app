@extends('layouts.master')

@section('content')

<form class="form-horizontal" role="form" method="POST">
    <h1 class="form-signin-heading">Авторизация</h1>
    <? if (isset($_GET['fail'])) { ?>
        <div style="" class="alert alert-danger">
            Указанные вами логин и пароль не подошли. Пожалуйста, попробуйте еще раз.
        </div>
    <? } ?>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
        <div class="col-sm-10">
            <input type="email"
                   class="form-control"
                   id="inputEmail3"
                   name="email"
                   data-validation="notempty;isemail"
                   value="<?=Input::old('email')?>"
                   placeholder="Введите ваш email, чтобы позже иметь возможность восстановить пароль.">
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword3" class="col-sm-2 control-label">Пароль</label>
        <div class="col-sm-10">
            <input
                type="password"
                class="form-control"
                id="inputPassword3"
                name="password"
                data-validation="notempty;minlength:6"
                placeholder="Ваш пароль">
        </div>
    </div>

    <?=Form::token()?>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit"
                    data-input=".form-horizontal"
                    data-link="<?=URL::to('auth/login')?>"
                    data-clearinputs="true"
                    class="btn btn-default">Войти</button>
        </div>
    </div>
</form>

@stop