<?php

use PrettyForms\Commands;

class CommentsController extends BaseController {

    public function __construct()
    {
        // Все запросы к контроллеру будут отфильтрованы с помощью фильтра защиты от CSRF-атак
        $this->beforeFilter('csrf');
    }

    // Обработка голосований за комментарий
    function postVote($id,$opinion)
    {
        if (Auth::guest()) {
            // Гости не имеют права голосовать
            return Commands::generate([
                'message' => 'Пожалуйста, авторизуйтесь перед тем, как голосовать за комментарии'
            ]);
        } else {
            $comment = Comment::with('user')->find($id);
            if ($comment->count()) {

                // Самому себе голосовать нельзя
                if ($comment->user->id === Auth::user()->id) {
                    return Commands::generate([
                        'message' => 'Увы, но вы не имеете права голосовать за собственный комментарий'
                    ]);
                }

                // Если пользователь еще не голосовал за этот коммент, так уж и быть: дадим ему это сделать
                // Но после этого сохраним его действие в таблице "comments_rates"
                if ($comment->rates()->where('user_id','=',Auth::user()->id)->count() === 0) {
                    $comment_rate = new Comments_Rate;
                    $comment_rate->user_id = Auth::user()->id;
                    $comment_rate->comment_id = $comment->id;
                    $comment_rate->save();

                    if ($opinion === 'up') {
                        $comment->rating = $comment->rating + 1;
                    } else {
                        $comment->rating = $comment->rating - 1;
                    }
                    $comment->save();

                    // Возвратим клиенту команду, благодаря которой рейтинг к комментарию на странице динамически обновится
                    return Commands::generate([
                        'set_comment_rating' => [
                            'id'     => $comment->id,
                            'rating' => $comment->rating
                        ]
                    ]);
                } else {
                    return Commands::generate([
                        'message' => 'Вы уже голосовали за данный комментарий, больше нельзя.'
                    ]);
                }
            }
        }
    }

    // Запрос удаления комментария
    function postDelete($id)
    {
        if (Auth::guest()) {
            return Commands::generate([
                'message' => 'Вы не можете удалить комментарий, так как вы не авторизованы'
            ]);
        } else {
            $comment = Comment::with('user')->find($id); /* @var $comment Comment */
            if ($comment->count()) {
                // Удалять мы можем только свои комментарии
                if ($comment->user->id !== Auth::user()->id) {
                    return Commands::generate([
                        'message' => 'Увы, но вы не имеете права удалять чужие комментарии'
                    ]);
                }

                $comment_id = $comment->id;
                $comment->delete();

                // Возвратим клиенту команду, благодаря которой комментарий на клиентской машине удалится из DOM
                return Commands::generate([
                    'delete_comment' => $comment_id
                ]);
            }
        }
    }

    // Запрос написания нового комментария
    function postWrite()
    {
        if (Auth::guest()) {
            return Commands::generate([
                'validation_errors' => 'Пожалуйста, <a href="/auth/login">авторизуйтесь</a> перед написанием комментария'
            ]);
        } else {
            $comment = new Comment;
            $comment->user_id = Auth::user()->id;
            $comment->article_id = Input::get('article_id');
            $comment->text = Input::get('text');
            $result = $comment->validateAndSave();
            if (is_array($result)) {
                // Если при валидации были возвращены ошибки - отправим их клиенту
                return $result;
            }
            return Commands::generate([
                'add_comment' => Comment::with('user')->find($comment->id)->toArray(),
            ]);
        }
    }

    // Запрос редактирования
    function postEdit($id)
    {
        if (Auth::guest()) {
            return Commands::generate([
                'validation_errors' => 'Пожалуйста, <a href="/auth/login">авторизуйтесь</a> перед написанием комментария'
            ]);
        } else {
            $comment = Comment::with('user')->find($id); /* @var $comment Comment */
            if ($comment->count()) {
                // Изменять мы можем только свои комментарии
                if ($comment->user->id !== Auth::user()->id) {
                    return Commands::generate([
                        'message' => 'Увы, но вы не имеете права редактировать чужие комментарии'
                    ]);
                }

                $comment->text = Input::get('text');
                $comment->save();

                return Commands::generate([
                    'edit_comment' => Comment::with('user')->find($comment->id)->toArray(),
                ]);
            }
        }
    }

}