<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 8/16/16
 * Time: 9:57 PM
 */

function getEmployee($companyId, $managerId, $employee){

    global $app;

    $month = $app->request()->get('month');

    $sql = "SELECT date(pu.creation) as date,sum(pu.time) as time, u.name,u.profession
                FROM `usages` as pu INNER JOIN users as u
                WHERE
                    pu.instance_id not in (select instance_id FROM p_i_maps WHERE type='black')
                    AND pu.user_id=u.id
                    and u.md5_id=:employee
                    and MONTH(pu.creation) = :month
                group by `user_id`,date(pu.creation);";

    try {
        $db = getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindParam("employee", $employee);
        $stmt->bindParam("month", $month);

        $stmt->execute();
        $employees = $stmt->fetchAll(PDO::FETCH_OBJ);

        echo '{"employee": ' . json_encode($employees) . '}';

        $db = null;




    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}