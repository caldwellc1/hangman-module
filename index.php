<?php

if (!defined('PHPWS_SOURCE_DIR')) {
    include '../../core/conf/404.html';
    exit();
}


\phpws\PHPWS_Core::initModClass('hangman', 'Hangman.php');

if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'newgame'){
  session_unset();
}
//Determine if there's a game in progress
if(isset($_SESSION['start'])){
  //Load the previous state of the in-progress game
  $game = new Hangman($_SESSION['word'], $_SESSION['numWrongGuesses'], $_SESSION['usedLetter']);
}
//Otherwise, create a new game
else{
  if(is_readable(PHPWS_SOURCE_DIR . 'mod/hangman/inc/hangwords.txt')){
    $wordList = file(PHPWS_SOURCE_DIR . 'mod/hangman/inc/hangwords.txt');
  }
  else{
    echo 'File not readable.';
    exit;
  }
  $ranNum = rand(1, 45424);
  $word = $wordList[$ranNum];
  $word = strtoupper(trim($word));
  $numWrongGuesses = 0;
  $usedLetter = array();

  $_SESSION['start'] = 'yes';
  $_SESSION['word'] = $word;
  $_SESSION['numWrongGuesses'] = $numWrongGuesses;
  $_SESSION['usedLetter'] = $usedLetter;
  $game = new Hangman($word, $numWrongGuesses, $usedLetter);
}

// TODO: Handle the requested action (for example, choosing a letter)
if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'chooseLetter') {
  if(!$game->checkLetterInList($_REQUEST['letter'], $_SESSION['usedLetter'])){
    $game->chooseLetter($_REQUEST['letter']);
  }
  else{
    \phpws\PHPWS_CORE::reroute('index.php?module=hangman');
    return;
  }
}

//Show the game by rendering it using a View
$view = new HangView($game);
\Layout::add($view->show());
