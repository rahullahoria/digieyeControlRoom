<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 9/6/16
 * Time: 3:53 PM
 */

/*
 * SELECT HOUR(`creation`),sum(time)/60 as time FROM `usages` WHERE user_id = 6 and DATE(`creation`) = CURDATE() group by HOUR(`creation`)
 * */

function getTodayUsage($employee){



    $sql = "SELECT HOUR(`creation`) as hour,sum(time)/60 as mins
              FROM `usages`
              WHERE user_id = (select id from users where md5_id = :md5Id)
              and DATE(`creation`) = CURDATE() group by HOUR(`creation`);";

    try {
        $db = getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindParam("md5Id", $employee);
        $stmt->execute();

        $employees = $stmt->fetchAll(PDO::FETCH_OBJ);

        echo '{"employee": ' . json_encode($employees) . '}';

        $db = null;




    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}