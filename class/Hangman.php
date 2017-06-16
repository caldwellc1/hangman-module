<?php


class Hangman {
    private $word;
    private $numWrongGuesses;
    private $usedLetter;
    private $numCount;

    public function __construct($word, $numWrongGuesses, Array $usedLetter){
        //Save params in member variables
        $this->word = $word;
        $this->numWrongGuesses = $numWrongGuesses;
        $this->usedLetter = $usedLetter;
        $amt = iconv_strlen($this->getWord());
        $this->setCount($amt);
    }

    public function chooseLetter($letter)
    {
        // check if the letter is in the word. If not, increment num wrong guesses
        if(!$this->checkLetterInWord($letter)){
          $this->numWrongGuesses++;
          $_SESSION['numWrongGuesses'] = $this->numWrongGuesses;
        }
        // Always add the letter to $usedLetter array
        $this->usedLetter[] = $letter;
        $_SESSION['usedLetter'] = $this->usedLetter;
    }

    public function checkLetterInWord($letter){
      $num=0;
      $nowWord = $this->getWord();
      while($num < $this->getCounts()){
        if($letter == $nowWord[$num]){
          return true;
        }
        else{
          $num++;
        }
      }
      return false;
    }
    public function checkLetterInList($letter, Array $usedLetter){
      $num=0;
      while($num < count($usedLetter)){
        if($letter == $usedLetter[$num]){
          return true;
        }
        else{
          $num++;
        }
      }
      return false;
    }

    //Getter/setter methods
    public function getWord(){
      return $this->word;
    }
    public function getWordList(){
      return $this->wordList;
    }
    public function getNumWrongGuesses(){
      return $this->numWrongGuesses;
    }
    public function getUsedLetter(){
      return $this->usedLetter;
    }
    private function setCount($amt){
      $this->numCount = $amt;
    }
    public function getCounts(){
      return $this->numCount;
    }

}
