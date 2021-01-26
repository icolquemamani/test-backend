<?php
namespace App\Helper;
use App\Helper\Problem1;
use App\Helper\Problem2;
use App\Helper\Problem3;

class Problem {
  private static $instances = [];

  protected function __construct() { }

  public static function getInstance(): Problem
  {
      $cls = static::class;
      if (!isset(self::$instances[$cls])) {
          self::$instances[$cls] = new static();
      }

      return self::$instances[$cls];
  }

  public function solve($input, $id) {

    switch ($id) {
      case 1:{
        $problem1 = Problem1::getInstance();
        return  $problem1->solve($input);
      }
      break;
      case 2:{
        $problem2 = Problem2::getInstance();
        return  $problem2->solve($input);
      }
      case 3:{
        $problem3 = Problem3::getInstance();
        return  $problem3->solve($input);
      }
      break;      
      default:
        return response()->json(['error'=> true, 'message' => 'Invalid id problem'], 400);
    }
    
  }
}