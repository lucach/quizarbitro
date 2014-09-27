<?php

class EveryTest extends TestCase {

    public function setUp()
    {
        $_ENV += include __DIR__.'/../../.env.local.php';
        parent::setUp();
        Session::start();
        Route::enableFilters();
    }

    public function testHome()
    {
        $this->call('GET', '/');
        $this->assertResponseOk();
    }

    public function testRegisterPage()
    {
        $this->call('GET', '/registration');
        $this->assertResponseOk();
        $this->assertViewHas('categories');
        $this->assertViewHas('titles');
        $this->assertViewHas('sections');
    }

    public function testEmptyLogin()
    {
        $response = $this->action('POST', 'HomeController@doLogin');
        $this->assertRedirectedTo('/');
    }

    public function testWrongLogin()
    {
        $credentials = array(
            'email'=> 'username@example.com',
            'password'=> 'password',
            'csrf_token' => csrf_token()
        );

        $response = $this->action('POST', 'HomeController@doLogin');
        $this->assertRedirectedTo('/');
    }


    public function testCorrectLogin()
    {
        $credentials = array(
            'email'=> $_ENV['ADMIN_MAIL'],
            'password'=> $_ENV['ADMIN_PASSWORD'],
            'csrf_token' => csrf_token()
        );

        $response = $this->action('POST', 'HomeController@doLogin', null, $credentials);
        $this->assertRedirectedTo('home');
    }

}
