<?php
namespace App\Helper;
use Exception;

class Problem2 {
  private static $instances = [];

  protected function __construct() { }

  public static function getInstance(): Problem2
  {
      $cls = static::class;
      if (!isset(self::$instances[$cls])) {
          self::$instances[$cls] = new static();
      }
      return self::$instances[$cls];
  }

  public function solve($input) {
      $array_input = explode("\n", $input);
      $array_split = [];  
      foreach ($array_input as $item) {
        $values = explode(" ", $item);
        $length = count($values);
        if( $length != 2 ) {
          throw new Exception('Invalid input');
        } else {
          array_push($array_split, $values);
        }
      }

      $n = $array_split[0][0];
      $this->validateLength($n, 0);
      $num_obstacules = $array_split[0][1];
      $this->validateLength($num_obstacules, -1);
      
      $rq = $array_split[1][0] - 1;
      $cq = $array_split[1][1] - 1;

      $obstacles = array_slice($array_split, 2);

      return $this->queensAttack($n, $num_obstacules, $rq, $cq, $obstacles);
  }

  private function queensAttack($n, $k, $rq, $cq, $obstacles) {

      if($k > count($obstacles)) {
        throw new Exception("Invalid size of obstacles");
      }
      $slice_obstacules =array_slice($obstacles, 0, $k);

      $board = $this->buildChessBoard($n);

      $this->setPositionQueen($rq, $cq, $board);
      $this->setObstacles($slice_obstacules, $board, $rq, $cq);

      return ['attacks' => $this->countQueensAttack($board, $rq, $cq, $n)]; 
  }

  private function validateLength($num, $min) {
    if($num < $min || $num > pow(10, 5)) throw new Exception("Invalid size of the board or obstacles");
  }

  private function buildChessBoard($n) {
    $board = [];

    for($i=0; $i<$n; $i++) {
      for($j=0; $j<$n; $j++) {
        $board[$i][$j] = ' ';
      }
    }

    return $board;
  } 

  private function setPositionQueen($rq, $cq, &$board) {
    $board[$rq][$cq] = 'Q';
  }

  private function setObstacles($obstacles, &$board, $rq, $cq) {
    foreach ($obstacles as $obstacle) {
      $r = $obstacle[0] - 1;
      $c = $obstacle[1] - 1;

      if($r == $rq && $c == $cq) {
        throw new Exception("Invalid obstacle position, can not be equalqueen position");
      }
      $board[$r][$c] = 'X';
    }
  }

  private function countQueensAttack($board, $rq, $cq, $n) {
    $countV =  $this->verticalCount($board, $rq, $cq, $n);
    $countH =  $this->horizontalCount($board, $rq, $cq, $n);
    $countDL = $this->leftDiagonalCount($board, $rq, $cq, $n);
    $countDR = $this->rightDiagonalCount($board, $rq, $cq, $n);
    return  $countV + $countH + $countDL + $countDR;
  }

  private function verticalCount($board, $rq, $cq, $n) {
    $sum = 0;
    for($i = $rq - 1; $i >=0; $i--) {
      $value = $board[$i][$cq];

      if($value == 'X' ) break;
      $sum++;
    }

    for($i = $rq + 1; $i < $n; $i++) {
      $value = $board[$i][$cq];

      if($value == 'X' ) break;
      $sum++;
    }
    return $sum;
  }

  private function horizontalCount($board, $rq, $cq, $n) {
    $sum = 0;
    for($i = $cq - 1; $i >=0; $i--) {
      $value = $board[$rq][$i];
      if($value == 'X' ) break;
      $sum++;
    }

    for($i = $cq + 1; $i < $n; $i++) {
      $value = $board[$rq][$i];
      if($value == 'X' ) break;
      $sum++;
    }
    return $sum;
  }

  private function leftDiagonalCount($board, $rq, $cq, $n) {
    $sum = 0;
    $aux = 1;
    for($i = $rq - 1; $i >=0; $i--) {
      $j = $cq-$aux;
      $value = $j >= 0 ? $board[$i][$cq-$aux]: 'X';
      if($value == 'X' ) break;
      $sum++;
      $aux++;
    }

    $aux = 1;
    for($i = $rq + 1; $i < $n; $i++) {
      $value = 'X';

      if($cq + $aux < $n ) {
        $value = $board[$i][$cq+$aux];
      }
      
      if($value == 'X' ) break;
      $sum++;
      $aux++;
    }
    return $sum;
  }

  private function rightDiagonalCount($board, $rq, $cq, $n) {
    $sum = 0;
    $aux = 1;
    for($i = $rq - 1; $i >=0; $i--) {
      $j = $cq+$aux;
      $value = $j < $n ? $board[$i][$cq+$aux]: 'X';
      if($value == 'X' ) break;
      $sum++;
      $aux++;
    }

    $aux = 1;
    for($i = $rq + 1; $i < $n; $i++) {
      $value = 'X';
      if($cq - $aux >= 0 ) {
        $value = $board[$i][$cq-$aux];
      }
      
      if($value == 'X' ) break;
      $sum++;
      $aux++;
    }
    return $sum;
  }
}