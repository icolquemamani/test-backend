<?php
namespace App\Helper;
use Exception;

class Problem3 {
  private static $instances = [];

  protected function __construct() { }

  public static function getInstance(): Problem3
  {
      $cls = static::class;
      if (!isset(self::$instances[$cls])) {
          self::$instances[$cls] = new static();
      }
      return self::$instances[$cls];
  }

  public function solve($input) {
    $this->validateInput($input);
    return [ 'maximum_occurs' => $this->getMaximumTimeOccurs($input)];
  }
  
  private function getMaximumTimeOccurs($input) {
    $length = strlen($input);
    $max = 0;

    for($i = 1; $i<= $length; $i++ ){
      $str = substr($input, 0, $i);
      $times = $this->getTimeOccurs($str, $input);
      if($times > $max ) $max = $times;
    }

    return $max;
  }

  private function validateInput($input) {
    $english_alphabet = ['a','b','c','d','e','f','g','h','i','j','k','l','m','n','p','q','r','s','t','u','v','w','x','y','z'];
    $input_split = str_split($input);
    $length = strlen($input);

    if( $length <= 1 || $length > pow(10, 5) ) {
      throw new Exception("Invalid input size");
    }

    foreach ($input_split as $letter) {
      if(!in_array($letter, $english_alphabet)) {
        throw new Exception("Invalid char entry");        
      }
    }
  }

  private function getTimeOccurs($s, $t) {
    $length = strlen($s);
    return $length * $this->countOccurs($s, $t);
  }

  private function countOccurs($s, $t) {
    $count = strlen($s);
    $length = strlen($t);
    $sum = 0;

    for($i = 0; $i <= $length; $i++ ){
      $str = substr($t, $i, $count);
      if($str == $s) $sum++;
    }

    return $sum; 
  }
}