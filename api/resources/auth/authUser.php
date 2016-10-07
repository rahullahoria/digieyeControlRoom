<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 8/16/16
 * Time: 9:57 PM
 */

function authUser(){


    $request = \Slim\Slim::getInstance()->request();
    $user = json_decode($request->getBody());


    $sql = "SELECT u.name,u.email,u.password,u.type,u.company_id,u.md5_id,u.profession,c.name as company_name,c.address
                FROM `users` as u INNER JOIN companies as c
                WHERE u.company_id = c.id and u.email=:email and c.name =:company and u.password=:password;";

    try {
        $db = getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindParam("company", $user->company);
        $stmt->bindParam("password", $user->password);
        $stmt->bindParam("email", $user->email);

        $stmt->execute();
        $user = $stmt->fetchAll(PDO::FETCH_OBJ);


        $db = null;

        if(count($user) == 1)
            echo '{"auth": "true", "user": ' . json_encode($user) . '}';
        else
            echo '{"auth": "false"}';




    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}