@extends('layouts.master')

@section('content')

<div class="form-horizontal" role="form">
    <h1 class="form-signin-heading">Регистрация</h1>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
        <div class="col-sm-10">
            <input type="email"
                   class="form-control"
                   id="inputEmail3"
                   name="email"
                   data-validation="notempty;isemail"
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
    <div class="form-group">
        <label for="inputPassword4" class="col-sm-2 control-label">Повторите пароль</label>
        <div class="col-sm-10">
            <input type="password"
                   class="form-control"
                   id="inputPassword4"
                   name="password_retry"
                   data-validation="notempty;passretry"
                   placeholder="Повторите пароль, вдруг ошиблись при вводе? Мы проверим это.">
        </div>
    </div>
    <div class="form-group">
        <p>Дополнительная информация:</p>
    </div>
    <div class="form-group">
        <label for="inputName" class="col-sm-2 control-label">Имя</label>
        <div class="col-sm-10">
            <input type="text"
                   class="form-control"
                   id="inputName"
                   name="name"
                   data-validation="notempty"
                   placeholder="Ваше имя в игре. Не обязательно реальное.">
        </div>
    </div>
    <div class="form-group">
        <label for="inputSex" class="col-sm-2 control-label">Пол</label>
        <div class="col-sm-10">
            <div class="radio">
                <label>
                    <input type="radio" name="sex" id="inputSex" value="1" checked> Мужской
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="sex" id="inputSex" value="2"> Женский
                </label>
            </div>
        </div>
    </div>

    <?=Form::token()?>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <div data-input=".form-horizontal"
                 data-link="<?=URL::to('auth/register')?>"
                 data-clearinputs="true"
                 class="btn btn-default senddata">Зарегистрироваться</div>
        </div>
    </div>
</div>

@stop