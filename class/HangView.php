<?php


class HangView {
    private $word;
    private $numWrongGuesses;
    private $usedLetter;
    private $game;
    public function __construct(Hangman $game)
    {
        // TODO: save a reference to the game in a member variable
        $this->game = $game;
        $this->numWrongGuesses = $game->getNumWrongGuesses();
        $this->word = $game->getWord();
        $this->usedLetter = $game->getUsedLetter();
    }

    public function show()
    {
        // TODO return HTML from PHPWS_Tempate::process(...)
        // Template is in templates/hangmangame.tpl
        $tpl['TITLE'] = 'Hangman Game';
        //adds pic of hangman based on number of wrong guesses
        $tpl['IMGURL'] = PHPWS_SOURCE_HTTP . 'mod/hangman/img/hang' . $this->numWrongGuesses . '.gif';
        //adds letters to display if not already selected
        foreach (range('A', 'Z') as $char) {
          if(!$this->game->checkLetterInList($char, $this->usedLetter)){
            $tpl['LINKS'][]= array ('NAME' => $char, 'URL' => PHPWS_SOURCE_HTTP . 'index.php?module=hangman&action=chooseLetter&letter=' . $char);
          }
          else{
            $tpl['LINKS'][]= array ('NAME' => '&nbsp&nbsp');
          }
        }

        //adds dashes to display for word length
        $dashList = '';
        $checkWord = '';
        for($i = 0; $i < $this->game->getCounts(); $i++){
          $dashList.= '_ ';
          $checkWord .= $this->word[$i] . ' ';
        }
        $tpl['DASH'] = $dashList;

        //shows letters guessed correctly
        $showList = '';
        for($i = 0; $i < $this->game->getCounts(); $i++){
          $letter = $this->word[$i];
          if($this->game->checkLetterInList($letter, $this->usedLetter)){
            $showList .= $letter . ' ';
          }
          else{
            $showList .= '  ';
          }
        }

        $tpl['WORD'] = $showList;

        if($showList == $checkWord){
          $tpl['WL'] = ': You Won!';
          unset($tpl['LINKS']);
          $tpl['NEW'][]= array('BUTTON' => 'New Game', 'URLS' => PHPWS_SOURCE_HTTP . 'index.php?module=hangman&action=newgame');
        }
        elseif($this->numWrongGuesses == 6){
          $tpl['WL'] = ': You lost.';
          unset($tpl['LINKS']);
          $tpl['NEW'][]= array('BUTTON' => 'New Game', 'URLS' => PHPWS_SOURCE_HTTP . 'index.php?module=hangman&action=newgame');
          $tpl['SHOWWORD'] = $this->word;
        }

        return PHPWS_Template::process($tpl, 'hangman', 'hangmangame.tpl');
    }
}
