<?php

use PrettyForms\LaravelValidatorTrait;

class Comment extends Eloquent {

	use LaravelValidatorTrait;

	protected $table = 'comments';
    protected $visible = ['id','user','rating','text','date','time'];
    private $rules = [
        'user_id'    => 'exists:users,id',
        'text'       => 'required',
        'article_id' => 'required'
    ];

    public function toArray()
    {
        $array = parent::toArray();
        $array['date'] = $this->date();
        $array['time'] = $this->time();
        return $array;
    }

    function date() {
        return $this->created_at->format('d.m.Y');
    }

    function time() {
        return $this->created_at->format('G:i');
    }

    public function user()
    {
        return $this->belongsTo('User');
    }

    function rates()
    {
        return $this->hasMany('Comments_Rate');
    }

}