<?php

use PrettyForms\LaravelValidatorTrait;

class Comments_Rate extends Eloquent {

	use LaravelValidatorTrait;

	protected $table = 'comments_rates';
    private $rules = [
        'user_id'    => 'required|exists:users',
        'article_id' => 'required'
    ];

}