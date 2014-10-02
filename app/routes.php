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
        $questions_text[$index]["id"] = $question->_id;
        $questions_text[$index]["question"] = $question->question;
        $questions_text[$index]["law"] = $question->law;
    }
    return json_encode($questions_text);
}));


Route::post('answers', array('before' => 'auth', function()
{
    $user_answers = Input::get('answers');

    // Assert that we have exactly 30 answers.
    if (strlen($user_answers) != 30)
        App::abort(500);

    // Assert that every answer is valid ('2' means unanswered).
    if (strpos($user_answers, '2') !== false)
        App::abort(500);

    $answers = array();
    for ($i = 0; $i < 30; $i++)
        array_push($answers, $user_answers[$i]);
    Session::put('answers', json_encode($answers));
}));


/* Stores in PHP session the questions for the current quiz with
requested difficulty (default easy). */
Route::get('newquiz/{difficulty?}', array('before' => 'auth', function($difficulty = 0) {

    // The following is, as agreed, the way we choose which questions
    // should appear in a quiz.
    $laws = array (1, 1, 2, 3, 3, 3, 4, 5, 5, 6, 6, 7, 8, 11, 11,
        12, 12, 12, 13, 14, 15, array(9, 10, 16, 17), 14, 18, 13,
        12, 4, 19, 19, array(9, 10, 16, 17));

    $hard_difficulties = array (0, 1, 0, 0, 0, 1, 0, 0, 1, 0, 1, 0, 0, 0,
        1, 0, 0, 1, 0, 0, 0, 0, 1, 0, 1, 1, 1, 0, 0, 0);

    // For certain laws, we choose to display hard questions only in 50% of cases.
    if (rand()%2)
        $hard_difficulties[2] = $hard_difficulties[11] = 1;
    else
        $hard_difficulties[12] = $hard_difficulties[20] = $hard_difficulties[21] = 1;
 
    // Random sort the array to mix the questions. Be careful: they have to be mixed
    // exactly the same to not lost their parallelism.

    $tmp = array();
    for ($i=0; $i<30; $i++) {
      $tmp[$i] = array($laws[$i], $hard_difficulties[$i]);
    }
    shuffle($tmp);
    for ($i=0; $i<30; $i++) {
      $laws[$i] = $tmp[$i][0];
      $hard_difficulties[$i] = $tmp[$i][1];
    }

    // Extract each one from the database and put in new session
    Session::forget('questions');
    $questions = array();
    $answers = array_fill(0, 30, -1);
    $id_to_be_avoided = array(-1); // dummy ID to make it working with the first query
    for ($i = 0; $i < 30; $i++)
    {
        $question = Question::orderBy(DB::raw('RAND()'))
                        ->whereIn('law', (is_int($laws[$i])) ? array($laws[$i]) : $laws[$i]) // convert to one-element array iff it's not already in that way
                        ->whereNotIn('_id', $id_to_be_avoided)
                        ->where('isHard', ($difficulty == 0) ? 0 : $hard_difficulties[$i])
                        ->take(1)
                        ->get()
                        ->first();
        array_push($questions, $question);
        array_push($id_to_be_avoided, intval($question->first()->_id));

        // If there are some question releated, push them in the array too
        if (isset($related_id[$question->first()->_id]))
            foreach ($related_id[$question->first()->_id] as $id)
                array_push($id_to_be_avoided, $id);

    }
    Session::put('questions', json_encode($questions));
    Session::put('difficulty', $difficulty);
    Session::put('store', true);

    // Display the first question
    return View::make('questions')
        ->with('question_text', $questions[0]->question)
        ->with('question_law', $questions[0]->law);

}));


Route::post('login', array('uses' => 'HomeController@doLogin'));


Route::get('logout', array('uses' => 'HomeController@doLogout'));


Route::get('home', array('before' => 'auth', 'as' => 'home', function()
{
	$last_quiz = History::where('userId', Auth::user()->id)
        ->where('created_at', History::where('userId', Auth::user()->id)->
            max('created_at'))
        ->take(1)->get()->first();

    return View::make('home')->with('last_quiz_datetime', 
        ($last_quiz) ? $last_quiz->created_at : "");
})); 


Route::get('end', array('before' => 'auth', function()
{
    // We refuse to give a response if this is not called from a quiz.
    if (substr(URL::previous(), 0, -2) != URL::to('/newquiz'))
        return Redirect::to('home')->with('error', 'La risorsa richiesta non '.
            'è accessibile.');

    $all_questions = json_decode(Session::get('questions'));
    $all_answers = json_decode(Session::get('answers'));
    $all_results = array();
    $good_answers = 0;
    foreach ($all_questions as $index => $question) {
        // Correction for law 18 and 19
        if ($question->law == 18) $question->law = 'PROC';
        if ($question->law == 19) $question->law = 'ASS';
        if ($question->isTrue == $all_answers[$index])
        {
            array_push($all_results, 1);
            $good_answers++;
        }
        else
            array_push($all_results, 0);
    }

    $percentage = ($good_answers * 100) / 30;
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

    // Save the quiz only once
    // (i.e. do not save if the user has reloaded the page).
    if (Session::has('store'))
    {
        Session::forget('store');

        $points = $good_answers;

        // A 20% bonus is given if the quiz was difficult.
        if (Session::get('difficulty') == '1')
            $points *= 1.2;

        // Update user table: increment the number of test done and update his/
        // her average score.
        $total_score = (Auth::user()->average_score * Auth::user()->tests_done) + (int)$points;
        Auth::user()->average_score = $total_score / (Auth::user()->tests_done + 1);
        Auth::user()->increment('tests_done');
        Auth::user()->save();

        // Save the quiz in history table.
        $row = new History;
        $row->userId = Auth::user()->id;
        $row->good_answers = $good_answers;
        $row->points = $points;
        $row->answers = Session::get('answers');
        $row->difficulty = Session::get('difficulty');
        $id = array();
        $questions = $all_questions;
        foreach ($questions as $question) {
            array_push($id, $question->_id);
        }
        $row->questions = json_encode($id);
        $row->save();
    }

    return View::make('results')
                ->with('good_answers', $good_answers)
                ->with('outcome_str', $outcome_str)
                ->with('questions', $all_questions)
                ->with('results', $all_results)
                ->with('answers', $all_answers);
}));


Route::get('profile', array('before' => 'auth', function()
{
    // Retrieve data to fill <select> fields.
    // The first item is the current option for auth user.

    $titles = Title::where('id', Auth::user()->title->id)->lists('name', 'id')
        + Title::where('id', '!=', Auth::user()->title->id)->orderBy('id', 'ASC')->lists('name', 'id');
    $categories = Category::where('id', Auth::user()->category->id)->lists('name', 'id')
        + Category::where('id', '!=', Auth::user()->category->id)->orderBy('id', 'ASC')->lists('name', 'id');
    $sections = Section::where('id', Auth::user()->section->id)->lists('name', 'id')
        + Section::where('id', '!=', Auth::user()->section->id)->orderBy('id', 'ASC')->lists('name', 'id');

    $profile_image_path;
    if (File::exists(public_path().'/profile-images/'.md5(Auth::user()->mail)))
        $profile_image_path = md5(Auth::user()->mail);
    else
        $profile_image_path = 'default';

    return View::make('profile')
        ->with('mail', Auth::user()->mail)
        ->with('name', Auth::user()->name)
        ->with('username', Auth::user()->username)
        ->with('sections', $sections)
        ->with('categories', $categories)
        ->with('titles', $titles)
        ->with('profile_image_path', $profile_image_path);
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
    foreach ($all_id_questions as $index => $question) {
        $current_question = Question::where('_id', $question)->get()->first();
        // Correction for law 18 and 19
        if ($current_question->law == 18) $current_question->law = 'PROC';
        if ($current_question->law == 19) $current_question->law = 'ASS';
        if ($current_question->isTrue == $all_answers[$index])
            array_push($all_results, 1);
        else
            array_push($all_results, 0);
        array_push($all_questions, $current_question);
    }

    return View::make('results')
                    ->with('questions', $all_questions)
                    ->with('answers', $all_answers)
                    ->with('results', $all_results);
}));


Route::post('profile/images/{hash}', array('before' => 'auth', function($hash)
{
    if (md5(Auth::user()->mail) != $hash)
        return Response::make("", 400); // Bad Request

    $file = Input::file('files')[0];

    if ($file->move(public_path().'/profile-images', md5(Auth::user()->mail)))
        return Response::make("", 200); // OK
    else
        return Response::make("", 500); // Internal Server Error

}));


Route::get('history/json', array('before' => 'auth', function()
{
    $rows = History::where('userId', Auth::user()->id)
                        ->orderBy('created_at', 'DESC')->get();

    $array = array();

    foreach ($rows as $row) {
        $date = date_create_from_format('Y-m-d H:i:s', $row->created_at);
        $timestamp = $date->format('U') * 1000;
        $y_value = $row->good_answers;
        array_push($array, array($timestamp, $y_value));
    }

    return json_encode($array, JSON_NUMERIC_CHECK);

}));


Route::get('ranking', array('before' => 'auth', function()
{
    $rows = User::where('tests_done', '>', '0')
        ->where('admin', false)
        ->orderBy('average_score', 'DESC')
        ->get(array('id', 'username', 'average_score'));

    $thirty_days = new DateTime('today');
    $thirty_days->modify('-30 day');


    foreach ($rows as $row)
    {
        // Each user is awarded with 0.1 point for each quiz he/she has done
        // in the last thirty days. This value can be a maximum of 2 points.
        $recent_tests = History::where('userId', $row->id)
                            ->where('created_at', '>', $thirty_days)
                            ->count();
        $bonus_value = min(2, $recent_tests * 0.1);
        $row->average_score += $bonus_value;

        // Round to the second decimal digit.
        $row->average_score = round($row->average_score, 2);
    }

    return View::make('ranking')->with('data', $rows);
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
    // Retrieve data to fill <select> fields.
    $titles = Title::orderBy('id', 'ASC')->lists('name', 'id');
    $categories = Category::orderBy('id', 'ASC')->lists('name', 'id');
    $sections = Section::orderBy('id', 'ASC')->lists('name', 'id');

    return View::make('registration', array('titles' => $titles,
                                            'categories' => $categories,
                                            'sections' => $sections));
});


Route::post('registration', array('uses' => 'HomeController@newUser'));


Route::post('profile', array('before' => 'auth', 'uses' => 'HomeController@updateUser'));
