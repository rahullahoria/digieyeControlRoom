<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 9/6/16
 * Time: 2:19 PM
 */

function getUserLast10Instance($md4Id){




    $sql = "SELECT distinct i.`id`,i.`instance`, p.program
                FROM usages as u
                  inner join `instances` as i
                  INNER JOIN programs as p

                WHERE  p.id = i.program_id
                    AND u.instance_id = i.id
                    and u.user_id = (select id from users where md5_id = :md4Id)
                ORDER By u.creation DESC
                LIMIT 0,5";


    try {
        $db = getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindParam("md4Id", $md4Id);


        $stmt->execute();
        $instances = $stmt->fetchAll(PDO::FETCH_OBJ);

        echo '{"instances": ' . json_encode($instances) . '}';

        $db = null;




    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}