<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 6/22/16
 * Time: 3:06 PM
 */


function getManagerEmployees($companyId, $managerId){

    global $app;

    $month = $app->request()->get('month');

    $sql = "SELECT p.`user_id` ,u.name, u.md5_id, sum( time ) AS time
            FROM `usages` as p inner join users as u
            WHERE
              p.instance_id not in (select instance_id FROM p_i_maps WHERE type='black')
              AND p.`user_id` = u.id
              and MONTH(p.creation) = :month
              and u.company_id = (select id from companies where name = :companyId)
              and p.`user_id` in (select `user_employee_id` from manager_employee_mappings where user_manager_id = (select id from users where md5_id = :managerId))
            GROUP BY p.`user_id`";

    $getActiveUsersSql = "SELECT DISTINCT user_id
                FROM `usages`
                WHERE `creation`
                BETWEEN DATE_SUB( NOW( ) , INTERVAL 30
                MINUTE )
                AND NOW( )
                and user_id in
                    (select `user_employee_id`
                        from manager_employee_mappings
                        where user_manager_id =
                                (select id
                                from users
                                  where md5_id = :managerId))";



    try {
        $db = getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindParam("month", $month);
        $stmt->bindParam("managerId", $managerId);
        $stmt->bindParam("companyId", $companyId);

        $stmt->execute();
        $employees = $stmt->fetchAll(PDO::FETCH_OBJ);

        //geting active users
        $stmt = $db->prepare($getActiveUsersSql);


        $stmt->bindParam("managerId", $managerId);

        $stmt->execute();
        $activeUsers = $stmt->fetchAll(PDO::FETCH_OBJ);

        foreach($activeUsers as $user){
            foreach($employees as $employee) {
                if ($user->user_id == $employee->user_id) {
                    $employee->status = "active";

                }
            }
        }

        echo '{"employees": ' . json_encode($employees) . '}';

        $db = null;




    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}