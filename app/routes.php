<?php

Route::get('/', function()
{
    if (Auth::check())
        return Redirect::route('home');
    else
        return View::make('slideshow');
});

Route::pattern('id', '[0-9]+');

Route::get('quiz/all', array('before' => 'auth', function()
{
    $all_questions = json_decode(Session::get('questions'));
    $questions_text = array();
    foreach ($all_questions as $index => $question) {
        $questions_text[$index]["question"] = $question[0]->question;
        $questions_text[$index]["law"] = $question[0]->law;
    }
    return json_encode($questions_text);
}));

Route::post('quiz/{id}', array('before' => 'auth', function($id)
{
    /** TODO This can be really a CPU-intensive task
     *    - reads a file
     *    - decodes JSON
     *    - encodes JSON
     *    - writes a file
     *    A better approach may be good.
     */
    $answers = json_decode(Session::get('answers'));
    $answers[$id] = Input::get('answer');
    Session::put('answers', json_encode($answers));
}));

/* Stores in PHP session the questions for the current quiz with
requested difficulty (default easy). */
Route::get('newquiz/{difficulty?}', array('before' => 'auth', function($difficulty = 0) {

    // The following is, as agreed, the way we choose which questions
    // should appear in a quiz.
    $laws = array (1, 1, 2, 3, 3, 3, 4, 5, 5, 6, 6, 7, 8, 11, 11,
        12, 12, 12, 13, 14, 15, array(9, 10, 16, 17), 14, 18, 13);

    $hard_difficulties = array (0, 1, 0, 0, 0, 1, 0, 0, 1, 0, 1, 0, 0, 0,
        1, 0, 0, 1, 0, 0, 0, 0, 1, 0, 1);

    // Hard questions are limited, so mix again them
    if (rand()%2)
        $hard_difficulties[2] = $hard_difficulties[6] = $hard_difficulties[11] = 1;
    else
        $hard_difficulties[12] = $hard_difficulties[20] = $hard_difficulties[21] = 1;
 
    // Random sort the array to mix the questions. Be careful: they have to be mixed
    // exactly the same to not lost their parallelism.

    $tmp = array();
    for ($i=0; $i<25; $i++) {
      $tmp[$i] = array($laws[$i], $hard_difficulties[$i]);
    }
    shuffle($tmp);
    for ($i=0; $i<25; $i++) {
      $laws[$i] = $tmp[$i][0];
      $hard_difficulties[$i] = $tmp[$i][1];
    }

    // Extract each one from the database and put in new session
    Session::forget('questions');
    $questions = array();
    $answers = array_fill(0, 25, -1);
    $id_to_be_avoided = array(-1); // dummy ID to make it working with the first query
    for ($i = 0; $i < 25; $i++)
    {
        $question = Question::orderBy(DB::raw('RAND()'))
                        ->whereIn('law', (is_int($laws[$i])) ? array($laws[$i]) : $laws[$i]) // convert to one-element array iff it's not already in that way
                        ->whereNotIn('_id', $id_to_be_avoided)
                        ->where('isHard', ($difficulty == 0) ? 0 : $hard_difficulties[$i])
                        ->take(1)
                        ->get();
        array_push($questions, $question);
        array_push($id_to_be_avoided, intval($question->first()->_id));

        // If there are some question releated, push them in the array too
        if (isset($related_id[$question->first()->_id]))
            foreach ($related_id[$question->first()->_id] as $id)
                array_push($id_to_be_avoided, $id);

    }
    Session::put('questions', json_encode($questions));
    Session::put('answers', json_encode($answers));

    // Display the first question
    return View::make('questions')
        ->with('question_text', $questions[0][0]->question)
        ->with('question_law', $questions[0][0]->law);

}));

Route::post('login', array('uses' => 'HomeController@doLogin'));

Route::get('logout', array('uses' => 'HomeController@doLogout'));

Route::get('home', array('before' => 'auth', 'as' => 'home', function()
{
	$last_quiz = History::where('userId', Auth::user()->id)
                    ->where('created_at', History::where('userId', Auth::user()->id)
                                            ->max('created_at'))
                    ->get();

    return View::make('home')->with('last_quiz_datetime', 
        (count($last_quiz) > 0) ? $last_quiz[0]->created_at : "");
})); 

Route::get('end', array('before' => 'auth', function()
{
    $all_questions = json_decode(Session::get('questions'));
    $all_answers = json_decode(Session::get('answers'));
    $all_results = array();
    $points = 0;
    foreach ($all_questions as $index => $question) {
        if ($question[0]->isTrue == $all_answers[$index])
        {
            array_push($all_results, 1);
            $points++;
        }
        else
            array_push($all_results, 0);
    }

    $percentage = ($points*100) / 25;
    if ($percentage == 100)
        $outcome_str = "Eccellente! Per te il Regolamento non ha segreti! Complimenti!";
    else
        if ($percentage >= 80)
            $outcome_str = "Qualche errorino qua e là , ma dopo una ripassata al Regolamento, raggiungerai il top!";
        else
            if ($percentage >= 60)
                $outcome_str = "Qualche errore di troppo. La tua conoscenza del Regolamento è un po' limitata. Ripassa bene!";
            else
                if ($percentage >= 40)
                    $outcome_str = "Maluccio! La tua conoscenza del Regolamento è essenziale e presenta qualche lacuna. Riprenditi il Regolamento e studia!";
                else
                    $outcome_str = "Molto male! In queste condizioni non puoi arbitrare nemmeno i pulcini! Studia il Regolamento!";

    Session::put('points', $points);

    return View::make('end')
                ->with('points', $points)
                ->with('outcome_str', $outcome_str)
                ->with('questions', $all_questions)
                ->with('results', $all_results)
                ->with('answers', $all_answers);
}));

Route::get('savequiz', array('before' => 'auth', function()
{
    // TODO We're not checking errors at all... Mind to update AJAX accordingly.
    $row = new History;
    $row->userId = Auth::user()->id;
    $row->points = Session::get('points');
    $row->answers = Session::get('answers');
    $id = array();
    $questions = json_decode(Session::get('questions'));
    foreach ($questions as $question) {
        array_push($id, $question[0]->_id);
    }
    $row->questions = json_encode($id);
    $row->save();
}));

Route::get('profile', array('before' => 'auth', function()
{
    return View::make('profile')
        ->with('mail', Auth::user()->mail)
        ->with('name', Auth::user()->name);
}));

Route::get('history', array('before' => 'auth', function()
{
    $num = History::where('userId', Auth::user()->id)->count();
    $rows = History::where('userId', Auth::user()->id)
                    ->orderBy('created_at', 'DESC')->get();
    return View::make('history')
        ->with('num', $num)
        ->with('rows', $rows);
}));

Route::post('result', array('before' => 'auth', function()
{
    $all_id_questions = json_decode(str_replace('\"', '"', Input::get('questions')));
    $all_answers = json_decode(str_replace('\"', '"', Input::get('answers')));
    $all_results = array();
    $all_questions = array();
    $points = 0;
    foreach ($all_id_questions as $index => $question) {
        $current_question = Question::where('_id', $question)->get()->first();
        if ($current_question->isTrue == $all_answers[$index])
        {
            array_push($all_results, 1);
            $points++;
        }
        else
            array_push($all_results, 0);
        array_push($all_questions, $current_question);
    }

    return View::make('results')
                    ->with('questions', $all_questions)
                    ->with('answers', $all_answers)
                    ->with('results', $all_results);
}));

Route::get('history/json', array('before' => 'auth', function()
{
    $rows = History::where('userId', Auth::user()->id)
                        ->orderBy('created_at', 'DESC')->get();

    $array = array();

    foreach ($rows as $row) {
        $date = date_create_from_format('Y-m-d H:i:s', $row->created_at);
        $timestamp = $date->format('U')*1000;
        $y_value = $row->points;
        array_push($array, array($timestamp, $y_value));
    }

    return json_encode($array, JSON_NUMERIC_CHECK);

}));

Route::get('password/reset', array( function()
{
    if (Auth::check()) // do not ask the mail if the user is logged in (i.e. he wants to change his password)
        App::make('PasswordController')->request();
    else
        return App::make('PasswordController')->remind();
}));

Route::post('password/reset', array(
  'uses' => 'PasswordController@request',
  'as' => 'password.request'
));

Route::get('password/reset/{token}', array(
  'uses' => 'PasswordController@reset',
  'as' => 'password.reset'
));

Route::post('password/reset/{token}', array(
  'uses' => 'PasswordController@update',
  'as' => 'password.update'
));

Route::get('registration', function()
{
    return View::make('registration');
});

Route::post('registration', array('uses' => 'HomeController@newUser'));