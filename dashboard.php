<?php

include(dirname(__FILE__) . '/config.php');

echo "<pre>";

// $params = array();
// $params = array(
//     'first_name' => 'Dipak',
//     'last_name' => 'Andraskar',
//     'email' => 'dipak@gmail.com',
//     'user_name' => 'dipak@gmail.com',
//     'password' => 'dipak@1234',
//     // 'datetime' => data_formate("d-m-Y","H:i:s"),
//     'updatedatetime' => data_formate("d-m-Y","H:i:s")
// );
// $param = array(
//     'first_name' => 'Dipak',
//     'last_name' => 'Andraskar',
//     // 'datetime' => data_formate("d-m-Y","H:i:s")
//     'updatedatetime' => data_formate("d-m-Y","H:i:s")
// );
//for delete
// $db->executeQuery("DELETE FROM users WHERE id IN (5,6) ");
// $db->executeQuery("DELETE FROM users");

// for update
// $db->updateRecord("users", $param, array("id" => 4));

// for insert
// $obj->insertRecord("users",$params);

// for fetch
// $users = $db->getRecords("SELECT * FROM users ",$params,false,true);
// print_r($users);
print_r($obj->getUsers());

?>