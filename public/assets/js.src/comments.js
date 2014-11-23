var CommentsListView = Backbone.View.extend({
    initialize: function (options) {
        this.$el = $(options.id);
        this.el = this.$el.get(0);

        // Текущий ID пользователя, либо пустая строка, если зашел гость
        var current_user_id = options.current_user_id;

        // Сохраним в переменную шаблон комментария,
        // который мы сгенерировали ранее с помощью анонимной функции
        var comment_template = _.template($('#article-comment-template').html());

        // Backbone-приложение единичного комментария
        this.CommentView = Backbone.View.extend({
            events: {
                'click .edit': function(event) {
                    this.model.set('edit',true);
                },
                'click .edit-cancel': function(event) {
                    this.model.set('edit',false);
                },
            },
            initialize: function(options) {
                if (options.container) {
                    // Свяжем вьюшку с указанным контейнером
                    // если он был передан
                    this.$el = options.container;
                    this.el = options.container.get(0);
                }
                this.model.bind('change', this.render, this); // При изменении модели заново отрендерим вьюшку
                this.model.bind('remove', this.remove, this); // Удалим вьюшку из DOM-дерева при удалении модели
            },
            render: function() {
                // Отрендерим внешний вид комментария
                var tmpl_data = this.model.attributes;
                tmpl_data.current_user_id = current_user_id;
                this.$el.html(
                    comment_template(tmpl_data)
                );
                return this;
            }
        });

        // Модель и коллекция комментариев
        var CommentModel = Backbone.Model.extend();
        var CommentsCollection = Backbone.Collection.extend({
            model: CommentModel
        });

        this.collection = new CommentsCollection;
        this.collection.reset(options.comments);

        // Свяжем данные в коллекции с присутствующими на странице DOM-элементами,
        // теми, которые изначально были сгенерированы сервером
        this.collection.each(function(comment){
            // Свойство "edit" мы добавляем каждой модели вручную. Это - флаг редактирования комментария,
            // если он равен true, то js-шаблон отобразит форму редактирования комментария
            comment.set('edit',false);
            new this.CommentView({
                model: comment,
                container: this.$el.find('#article-comments-'+comment.get('id'))
            });
        },this);

        // При добавлении нового элемента в коллекцию, добавим его на страницу
        this.collection.bind('add',function(comment) {
            var view = new this.CommentView({model: comment});
            this.$el.append(view.render().el);
        }, this);
    },

    // Установить рейтинг одному из комментариев
    setCommentRating: function(id,rating) {
        this.collection.findWhere({id:id}).set('rating',rating);
    }

});

// А теперь - самое интересное. Добавим новые обработчики команд с сервера,
// относящиеся к комментариям.

// Добавление нового комментария на страницу
PrettyForms.Commands.registerHandler('add_comment',function(comment){
    comment.edit = false;
    CommentsList.collection.add(comment);
});

// Изменения какого-то из комментариев
PrettyForms.Commands.registerHandler('edit_comment',function(comment){
    comment.edit = false;
    CommentsList.collection.findWhere({id:comment.id}).set(comment);
});

// Удаление комментария
PrettyForms.Commands.registerHandler('delete_comment',function(comment_id){
    CommentsList.collection.remove(comment_id);
});

// Установка нового значения рейтинга
PrettyForms.Commands.registerHandler('set_comment_rating',function(data){
    CommentsList.setCommentRating(data.id,data.rating);
});

// Отобразим какое-то сообщение, пришедшее с сервера (например, о какой-то ошибке)
// Здесь, вместо алерта, можно написать любой код для красивого оповещения
PrettyForms.Commands.registerHandler('message',function(message){
    alert(message);
});