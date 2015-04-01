<?php

Class Tree
{
  private $Db;
  
	public function __construct() {
    require_once(dirname(__FILE__) . '/config.php');
    /**
     * подключаемся к базе
     */
    try {  
      $this->Db = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);  
    }  
    catch(PDOException $e) {  
      echo $e->getMessage();  
    }
    //по привычке для левых хостингов
    $this->Db->exec('SET names UTF8;');  
	}
  
  
	public function GetChild( $level, $type_id, $first_parent, $category_id ) {
    /**
     * смотрим что пришло и вынимаем что нужно :)
     */
    if ($level==0 && $first_parent==0 && $category_id==0) {
      //первая категория после корня
      return  $this->GetTypeCategory($type_id);
    } else {
      $output_array1 = $this->GetChildren($level, $type_id, $first_parent, $category_id);
      $output_array2 = $this->GetPosition($category_id, $type_id);
      return  array_merge($output_array1,$output_array2);
    }
     
	}


	public function GetRoot() {
    /**
     * вынимаем корневые категории, которые будут группированными типами
     */
    $Result = $this->Db->query('SELECT t.name as name, concat("0_", t.id, "_0_0") AS id, "root" as type FROM positions AS p INNER JOIN type AS t ON (p.type_id=t.id) GROUP BY p.type_id ');  
    $Result->setFetchMode(PDO::FETCH_BOTH);  
    return $Result->fetchAll(); 
	}

	public function GetTypeCategory($type_id) {
    /**
     * вынимаем категории, по определенному типу
     */
    $data = array( ':type_id' => $type_id );
    $Result = $this->Db->prepare('SELECT fc.name as name, concat(c.level, "_", "'.$type_id.'", "_", c.first_parent, "_", fc.id) AS id, "tree" as type FROM positions AS p INNER JOIN categories AS c ON (p.category_id=c.id) INNER JOIN categories AS fc ON (c.first_parent = fc.id) where type_id=:type_id group by c.first_parent');
    $Result->execute($data);
    $Result->setFetchMode(PDO::FETCH_BOTH);  
    return $Result->fetchAll(); 
	}

	public function GetChildren($level, $type_id, $first_parent, $category_id) {
    /**
     * вынимаем дочерние категории
     */
    
     
    $data = array( ':category_id' => $category_id, ':first_parent' => $first_parent, ':level' => $level, ':type_id' => $type_id );
    $Result = $this->Db->prepare('SELECT concat((c.level+1), "_", "'.$type_id.'", "_", c.first_parent, "_", c.id) AS id, c.name as name, count(*) as children FROM categories AS c LEFT JOIN positions AS p ON (p.category_id=c.id) where (parent_id IN (select parent_id from categories where first_parent = :first_parent and level>:level) or parent_id = :category_id) AND c.level=:level group by c.id');
    $Result->execute($data);
    $Result->setFetchMode(PDO::FETCH_BOTH);  
    return $Result->fetchAll(); 
	}


	public function GetPosition($category_id, $type_id) {
    /**
     * вынимаем позиции
     */
    $data = array( ':category_id' => $category_id, ':type_id' => $type_id );
    $Result = $this->Db->prepare(' SELECT name, id, "pos" as type FROM positions WHERE category_id=:category_id and type_id=:type_id ');
    $Result->execute($data);
    $Result->setFetchMode(PDO::FETCH_BOTH);  
    return $Result->fetchAll(); 
	}

}
?>
