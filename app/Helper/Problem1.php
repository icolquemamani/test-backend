<?php
namespace App\Helper;
use Exception;

class Problem1 {
  private static $instances = [];

  protected function __construct() { }

  public static function getInstance(): Problem1
  {
      $cls = static::class;
      if (!isset(self::$instances[$cls])) {
          self::$instances[$cls] = new static();
      }
      return self::$instances[$cls];
  }

  public function solve($input) {
    $categories = $this->parseInputDataCategories($input);
    $result = [];

    foreach ($categories as $category => $data) {
      $category_result = $this->getCategoryResult($data);
      $result[$category] = $category_result;
    }
    return $result;
  }

  /**
   * Create object by categories
  */
  public function parseInputDataCategories($input) {
    $array_input = explode("\n", $input);
    $array_split = [];  
    foreach ($array_input as $item) {
      $values = explode(" ", trim($item));
      array_push($array_split, $values);
    }

    $categories = [];
    $currentCategory = '';
    $length_array = count($array_split);

    foreach ($array_split as $key => $item) {
      $first = $item[0];
      $count = count($item);
      $index = 0;

      if($key == $length_array - 1 && $first !== 'FIN') {
        throw new Exception('Invalid input');
      }
      

      if( $count == 1 && $first !== 'FIN') {
        $this->validMaxLength($first, 16);        
        $categories[$first] = [];
        $currentCategory = $first;
      } else if ($count == 1 && $first === 'FIN') {
        $index = 0;
      } else if( $count != 4) {
        throw new Exception('Invalid input');
      } else {
        array_push($categories[$currentCategory], $item);
      }
    }
    return $categories;
  }

  /**
   * Validate if string have max lenght
   */
  private function validMaxLength($str, $max) {
    if(strlen($str) > $max ) throw new Exception("Invalid legth text: "."'$str'" );        
  }

  private function getCategoryResult($data_category) {
    $data = $this->parseArrayData($data_category);
    $teams = $this->getTeams($data);

    $no_games = $this->getNoPlayedGames($teams, $data);
    $winnerTeam = $this->getWinnerTeamOrTie($teams, $data);

    return [
      'winner' => $winnerTeam,
      'no_games' => $no_games
    ];
  }

  private function parseArrayData($data) {
    $parse_data = [];
    $index = 0;

    foreach ($data as $item) {
      $this->validMaxLength($item[0], 16);        
      $this->validMaxLength($item[2], 16);        

      $parse_data[$index] = [
        'local' => $item[0],
        'local_score' => intval($item[1]),
        'visitor' => $item[2],
        'visitor_score' => intval($item[3]),
      ];
      $index++;
    }
    return $parse_data;
  }

  private function getTeams($array_data) {
    $teams = [];
    $index = 0;
    foreach ($array_data as $item) {
      $localTeam = $item["local"];
      $visitorTeam = $item["visitor"];

      if(!in_array($localTeam, $teams)) {
        $teams[$localTeam] = $localTeam;
      }

      if(!in_array($visitorTeam, $teams)) {
        $teams[$visitorTeam] = $visitorTeam;
      }
    }
    return $teams;
  }

  private function getWinnerTeamOrTie($teams, $data) {
    $score = [];
    $max = -1;
    $max_count = 0;
    $winnerTeam = '';

    foreach ($teams as $clave => $valor) {
      $score[$clave] = 0;
    }
    
    foreach ($data as $item) {
      $localTeam = &$score[$item['local']];
      $visitorTeam = &$score[$item['visitor']];

      if($item['local_score'] > $item['visitor_score']) {
        $localTeam = $localTeam + 2;
        $visitorTeam = $visitorTeam + 1;
      } else if($item['local_score'] < $item['visitor_score']){
        $localTeam = $localTeam + 1;
        $visitorTeam = $visitorTeam + 2;
      } else {
        throw new Exception('Invalid sets points, can not be equal');
      }
    }

    foreach ($score as $clave => $points) {
      if($points == $max) {
        $max_count++;
        $winnerTeam = 'EMPATE';
      } else if($points > $max) {
        $max = $points;
        $max_count = 1;
        $winnerTeam = $clave;
      }
    }

    return $winnerTeam;
  }

  private function getNoPlayedGames ($teams, $data) {
    $total_teams = count($teams);
    $total_games = $total_teams * ($total_teams - 1);

    return $total_games - count($data);
  }
}