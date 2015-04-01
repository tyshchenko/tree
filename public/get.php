<?php
require_once(dirname(__FILE__) . '../../app/tree.php');

if(isset($_GET['operation'])) {
	try {
		$rslt = null;
    $tree = NEW Tree();
		$node = isset($_GET['id']) && $_GET['id'] !== '#' ? $_GET['id'] : 0;
		switch($_GET['operation']) {
			case 'get_node':
        if ($node === 0) {
          $temp = $tree->GetRoot();
          $rslt = array();
        } else {
          $node  = explode('_', $node);
          $level =  (int) $node[0];
          $type  =  (int) $node[1];
          $first =  (int) $node[2];
          $category =  (int) $node[3];
          $temp = $tree->GetChild($level,  $type,  $first, $category );
          $rslt = array();
        }
				foreach($temp as $v) {
					$rslt[] = array('id' => $v['id'], 'text' => $v['name'], 'children' => true, 'type' => $v['type']);
				}
				break;

			default:
				throw new Exception('Unsupported operation: ' . $_GET['operation']);
				break;
		}
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($rslt);
	}
	catch (Exception $e) {
		header($_SERVER["SERVER_PROTOCOL"] . ' 500 Server Error');
		header('Status:  500 Server Error');
		echo $e->getMessage();
	}
	die();
}
?>
