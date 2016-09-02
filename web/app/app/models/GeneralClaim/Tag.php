<?php

class GeneralClaim__Tag extends Eloquent
{
    protected $table = 'general_claim_tags';

    protected $fillable = ['name', 'enabled'];

    public static $rules = [
        'name' => 'required',
    ];
}
