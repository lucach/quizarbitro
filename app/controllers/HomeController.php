<?php
class HomeController extends BaseController
{
    public function showWelcome()
    {
        return View::make('hello');
    }

    public function doLogin()
    {

        $rules = array(
            'email' => 'required|email', // make sure the email is an actual email
            'password' => 'required'
        );

        // Run the validation rules on the inputs from the form
        $validator = Validator::make(Input::all() , $rules);

        // If the validator fails, redirect back to the form
        if ($validator->fails())
        {
            return Redirect::to('/')->withErrors($validator) // send back all errors to the login form
            ->withInput(Input::except('password')); // send back the input (not the password) so that we can repopulate the form
        }
        else
        {

            // Create our user data for the authentication
            $userdata = array(
                'mail' => Input::get('email') ,
                'password' => Input::get('password')
            );

            // Attempt to do the login, remembering the user if he requested that.
            if (Auth::attempt($userdata, (Input::get('remember_me') == '1') ? true : false))
            {
                return Redirect::to('home');
            }
            else
            {
                return Redirect::to('/')->withErrors('Combinazione email/password errata.');
            }
        }
    }

    public function doLogout()
    {
        Auth::logout(); // log the user out of our application
        return Redirect::to('/'); // redirect the user to the login screen
    }

    public function newUser()
    {
        $rules = array(
            'mail' => array(
                'required',
                'email',
                'unique:qa_users'
            ) ,
            'password' => array(
                'required',
                'min:6'
            ) ,
            'username' => array('required', 'unique:qa_users')
        );

        $validation = Validator::make(Input::all() , $rules);
        if ($validation->fails())
        {
            return Redirect::to('registration')->withInput()->withErrors($validation);
        }

        $user = new User;
        $user->mail = Input::get('mail');
        $user->password = Hash::make(Input::get('password'));
        $user->name = Input::get('name');
        $user->username = Input::get('username');
        $user->title_id = Input::get('titles');
        $user->section_id = Input::get('sections');
        $user->category_id = Input::get('categories');
        $user->save();

        // Send a welcome mail
        $id = $user->id;
        Mail::queue('emails.welcome', array('username' => $user->username), function($message) use ($id)
        {
            $user = User::find($id);
            $message->to($user->mail, $user->name)->subject('Benvenuto in QuizRef!');
        });

        return Redirect::to('/')->with('success', 'Registrazione effettuata con successo!');
    }

    public function newFacebookUser()
    {
        $rules = array(
            'username' => array('required', 'unique:qa_users')
        );

        $validation = Validator::make(Input::all() , $rules);
        if ($validation->fails())
        {
            return Redirect::to('facebook/registration')->withInput()->withErrors($validation);
        }

        // If Facebook has been selected as auth method, the user is already 
        // logged in.
        $user = Auth::user();

        // Set fields that could not be retrieved during Facebook login.
        $user->username = Input::get('username');
        $user->title_id = Input::get('titles');
        $user->section_id = Input::get('sections');
        $user->category_id = Input::get('categories');
        $user->save();

        // Send a welcome mail.
        $id = $user->id;
        Mail::queue('emails.welcome', array('username' => $user->username), function($message) use ($id)
        {
            $user = User::find($id);
            $message->to($user->mail, $user->name)->subject('Benvenuto in QuizRef!');
        });

        return Redirect::to('/')->with('success', 'Registrazione effettuata con successo!');
    }

    public function updateUser()
    {
        $user = Auth::user();
        $user->title_id = Input::get('titles');
        $user->section_id = Input::get('sections');
        $user->category_id = Input::get('categories');
        $user->save();
        return Redirect::to('/profile')->with('success', 'Informazioni aggiornate con successo!');
    }


}
