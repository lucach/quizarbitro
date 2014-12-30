<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use SammyK\LaravelFacebookSdk\FacebookableTrait;


class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait, FacebookableTrait;

	protected $table = 'qa_users';
	protected $hidden = array('password', 'remember_token', 'access_token');

    protected static $facebook_field_aliases = [
        'facebook_field_name' => 'database_column_name',
        'id' => 'facebook_user_id',
        'name' => 'name',
        'email' => 'mail'
    ];


	public function getReminderEmail()
    {
        return $this->mail;
    }

    public function category()
    {
    	return $this->hasOne('Category', 'id', 'category_id');
    }

    public function title()
    {
    	return $this->hasOne('Title', 'id', 'title_id');
    }

    public function section()
    {
    	return $this->hasOne('Section', 'id', 'section_id');
    }

}
