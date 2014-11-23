@extends('layouts.master')



@section('content')

<?php /* Подключим наш файл с Backbone-приложением */ ?>
<script src="/assets/js.src/comments.js"></script>

<div class="blog-header">
    <h1 class="blog-title">Некая статья</h1>
</div>

<div class="row">

    <div class="col-sm-8 blog-main">

        <div class="blog-post">
            <h2 class="blog-post-title">Заголовок</h2>
            <p class="blog-post-meta">January 1, 2014 by <a href="#">Mark</a></p>

            <p>This blog post shows a few different types of content that's supported and styled with Bootstrap. Basic typography, images, and code are all supported.</p>
            <hr>
            <p>Cum sociis natoque penatibus et magnis <a href="#">dis parturient montes</a>, nascetur ridiculus mus. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Sed posuere consectetur est at lobortis. Cras mattis consectetur purus sit amet fermentum.</p>
            <p>Etiam porta <em>sem malesuada magna</em> mollis euismod. Cras mattis consectetur purus sit amet fermentum. Aenean lacinia bibendum nulla sed consectetur.</p>
            <p>Cras mattis consectetur purus sit amet fermentum. Sed posuere consectetur est at lobortis.</p>
        </div><!-- /.blog-post -->

        <hr>
        <h4>Комментарии</h4>
        <div id='article-comments'>
            <?php
            // Для вывода комментариев мы объявляем специальную анонимную функцию,
            // которая у нас будет попутно также служить генератором их JS-шаблона. Это и есть тот
            // маленький лайвхак, о котором я говорил несколькими абзацами выше
            $comment_body = function($as_jstemplate = false, Comment $comment = null) { ?>
                <?php
                // Возвращаем содержимое переменной, либо её название для JS-шаблона
                $var = function($name) use ($comment,$as_jstemplate) {
                    if ($as_jstemplate) {
                        return "<%= {$name} %>";
                    } else {
                        return method_exists($comment, $name)
                            ? $comment->$name()
                            : $comment->$name
                        ;
                    }
                } ?>

                <?php /* Контейнер коментария. Еще одна анонимная функция $var('id') вернёт либо строку "<%= id %>" для Undescore-шаблона, либо id коммента, если это вывод комментария. И так далее везде, где используется эта функция. */ ?>
                <div id="article-comments-<?=$var('id')?>">
                    <div class="pull-right">
                        <?php /* Кнопка положительного голосования за комментарий: */ ?>
                        <span data-link='/comments/vote/<?=$var('id')?>/up'
                                   class='glyphicon glyphicon-arrow-up senddata-token'></span>
                        <?=$var('rating')?>
                        <span data-link='/comments/vote/<?=$var('id')?>/down'
                                    class='glyphicon glyphicon-arrow-down senddata-token'></span>

                        <?php /* Далее описан вывод код кнопок "удалить" и "редактировать",
                                 по отдельности для JS-шаблона и для кода генерации */ ?>
                        @if ($as_jstemplate)
                            <% if (current_user_id === user.id) { %>
                                <div data-link='/comments/delete/<?=$var('id')?>'
                                        class='btn btn-default btn-xs senddata-token really'>удалить</div>
                                <div class='btn btn-default btn-xs edit'>редактировать</div>
                            <% } %>
                        @else
                            @if (Auth::check() AND $comment->user_id === Auth::user()->id)
                                <div data-link='/comments/delete/<?=$var('id')?>'
                                        class='btn btn-default btn-xs senddata-token really'>удалить</div>
                                <div class='btn btn-default btn-xs edit'>редактировать</div>
                            @endif
                        @endif
                    </div>
                    <p><span class="label label-default"><?=$as_jstemplate ? '<%=user.name%>' : $comment->user->name?></span> написал <?=$var('date')?> в <?=$var('time')?>:</p>
                    @if ($as_jstemplate)
                        <?php /* В JS-шаблоне мы имеем возможность нажать кнопку "редактировать",
                                 и открыть форму редактирования коммента: */ ?>
                        <% if (edit === true) { %>
                            <div id='form-edit-comment-<%=id%>'>
                                <textarea style='width: 100%; min-height: 50px;' name='text'><%=text%></textarea>
                                <div data-input="#form-edit-comment-<%=id%>"
                                    data-link="/comments/edit/<%=id%>"
                                    data-clearinputs="true"
                                    class="btn btn-primary btn-xs senddata-token">Сохранить</div>
                                <div class='btn btn-default btn-xs edit-cancel'>Отмена</div>
                                <br/><br/>
                            </div>
                        <% } else { %>
                            <p><%=text%></p>
                        <% } %>
                    @else
                        <p><?=$comment->text?></p>
                    @endif
                </div>
            <?php } ?>

            <?php /* Вытаскиваем из БД все комментарии и выводим их на страницу: */ ?>
            <?php $comments = Comment::with('user')->where('article_id','=',5)->get(); ?>
            <?php foreach($comments as $comment) { ?>
                <?=$comment_body(false, $comment)?>
            <?php } ?>
        </div>
        <script type='text/template' id='article-comment-template'>
            <?php /* А здесь мы используем эту анонимную функцию уже
                     для генерации шаблона для Backbone-приложения */ ?>
            <?=$comment_body(true)?>
        </script>

        <hr>
        <div class='form-article-write-comment'>
            <?php /* Форма написания комментария */ ?>
            <h4>Написать комментарий:</h4>
            <div class="form-group">
                <textarea class="form-control"
                    name="text"
                    data-validation="notempty;minlength:6"
                    placeholder="Ваш комментарий"></textarea>
            </div>
            <?=Form::hidden('article_id',5)?>
            <div class="form-group">
                <div data-input=".form-article-write-comment"
                     data-link="/comments/write"
                     data-clearinputs="true"
                     class="btn btn-default senddata-token">Написать</div>
            </div>
        </div>

        <script type='text/javascript'>
            $(document).ready(function(){
                // Сохраним токен приложения, который будет отправляться
                // вместе со всеми запросами для защиты от CSRF-атак
                PrettyForms.token_name = '_token';
                PrettyForms.token_value = '<?=Session::token()?>';

                // Инициализируем наше Backbone-приложение, код которого я приведу чуть ниже в статье
                CommentsList = new CommentsListView({
                    id              : '#article-comments',
                    current_user_id : '<?=Auth::check() ? Auth::user()->id : ''?>',
                    comments        : <?=$comments->toJson()?>
                });
            });
        </script>

        <style>
            .senddata,.senddata-token {
                cursor: pointer;
            }
        </style>

    </div><!-- /.blog-main -->

    <div class="col-sm-3 col-sm-offset-1 blog-sidebar">
        <div class="sidebar-module sidebar-module-inset">
            <h4>About</h4>
            <p>Etiam porta <em>sem malesuada magna</em> mollis euismod. Cras mattis consectetur purus sit amet fermentum. Aenean lacinia bibendum nulla sed consectetur.</p>
        </div>
        <div class="sidebar-module">
            <h4>Archives</h4>
            <ol class="list-unstyled">
                <li><a href="#">March 2014</a></li>
                <li><a href="#">February 2014</a></li>
                <li><a href="#">January 2014</a></li>
                <li><a href="#">December 2013</a></li>
                <li><a href="#">November 2013</a></li>
                <li><a href="#">October 2013</a></li>
                <li><a href="#">September 2013</a></li>
                <li><a href="#">August 2013</a></li>
                <li><a href="#">July 2013</a></li>
                <li><a href="#">June 2013</a></li>
                <li><a href="#">May 2013</a></li>
                <li><a href="#">April 2013</a></li>
            </ol>
        </div>
        <div class="sidebar-module">
            <h4>Elsewhere</h4>
            <ol class="list-unstyled">
                <li><a href="#">GitHub</a></li>
                <li><a href="#">Twitter</a></li>
                <li><a href="#">Facebook</a></li>
            </ol>
        </div>
    </div><!-- /.blog-sidebar -->

</div><!-- /.row -->

@stop