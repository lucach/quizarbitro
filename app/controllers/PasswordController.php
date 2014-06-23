<?php
class PasswordController extends BaseController
{
    
    public function remind()
    {
        return View::make('password.remind');
    }

    public function request()
    {
        if (Auth::check())
        {
            $credentials = array(
                'mail' => Auth::user()->mail
            );
            Password::remind($credentials);
        }
        else
        {
            switch ($response = Password::remind(Input::only('mail')))
            {
                case Password::INVALID_USER:
                    return Redirect::back()->with('error', Lang::get($response));
                case Password::REMINDER_SENT:
                    return Redirect::back()->with('info', Lang::get($response));
            }
        }
    }

    public function reset($token)
    {
        return View::make('password.reset')->with('token', $token);
    }

    public function update()
    {
        $credentials = array(
            'mail' => Input::get('email') ,
            'password' => Input::get('password') ,
            'password_confirmation' => Input::get('password_confirmation') ,
            'token' => Input::get('token')
        );
        $response = Password::reset($credentials, function ($user, $password)
        {
            $user->password = Hash::make($password);
            $user->save();
        });
        switch ($response)
        {
            case Password::INVALID_PASSWORD:
            case Password::INVALID_TOKEN:
            case Password::INVALID_USER:
                return Redirect::back()->with('error', Lang::get($response));
            case Password::PASSWORD_RESET:
                return Redirect::to('/')->with('info', 'La tua password è stata aggiornata');
        }
    }
}
?>
