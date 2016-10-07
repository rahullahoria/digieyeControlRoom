<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 6/22/16
 * Time: 3:06 PM
 */

function getProgramId($programName){

    $insertProgramSql = "Insert into  `programs`( `program`) VALUES (:program) ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)";

    try {


            $db = getDB();
            $stmt = $db->prepare($insertProgramSql);

            $stmt->bindParam("program",base64_encode($programName));


            $stmt->execute();
            $id = $db->lastInsertId();
            $db = null;
            return $id;




    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        //echo '{"error":{"text":' . $e->getMessage() . '}}';
        return false;
    }

}

function getInstanceId($instanceName,$programId){


    $insertProgramSql = "INSERT INTO `instances`( `program_id`, `instance`)
                            VALUES (:programId,:instance) ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)";

    try {


            $db = getDB();
            $stmt = $db->prepare($insertProgramSql);

            $stmt->bindParam("programId",$programId);
            $stmt->bindParam("instance",base64_encode($instanceName));


            $stmt->execute();
            $id = $db->lastInsertId();
            $db = null;
            return $id;




    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        //echo '{"error":{"text":' . $e->getMessage() . '}}';
        return false;
    }

}

/**
 * @param $username
 */
function saveUsageV1($username){

    $request = \Slim\Slim::getInstance()->request();
    $usage = json_decode($request->getBody());
    if(!isset($usage->pc_username)) $pc_username = "unknown";
    else $pc_username = $usage->pc_username;
    $ip = $_SERVER['REMOTE_ADDR'];

    $username = intval($username);
    $insertUsageSql = "INSERT INTO `usages`(`instance_id`, `time`, `user_id`, `pc_username`, `ip`)
                          VALUES (:instanceId,:time,:userId,:pcUsername,:ip)";

   /* $sql2 = "insert into files ( file_name, program_usage_id, time) VALUES ( :file_name, :program_usage_id, :time)
            ON DUPLICATE KEY UPDATE time=time+:newT;";*/

    try {


        $db = getDB();
        foreach ($usage as $k => $u){
            if (isset($u->program)) {
                $stmt = $db->prepare($insertUsageSql);
                $instanceId = intval(getInstanceId($k,getProgramId($u->program)));
                $ti = intval($u->time);

                $stmt->bindParam("instanceId", $instanceId, PDO::PARAM_INT);
                $stmt->bindParam("time", $ti, PDO::PARAM_INT);
                $stmt->bindParam("userId", $username, PDO::PARAM_INT);
                $stmt->bindParam("pcUsername", $pc_username);
                $stmt->bindParam("ip", $ip);
                //var_dump($instanceId,$ti,$username, $usage->pc_username,$ip);

                $stmt->execute();
                $id = $db->lastInsertId();
                /*foreach ($u->files as $k1 => $u1){

                    $stmt = $db->prepare($sql2);

                    $stmt->bindParam("program_usage_id", $id);
                    $stmt->bindParam("file_name", $k1);
                    $stmt->bindParam("time", $u1);
                    $stmt->bindParam("newT", $u1);

                    $stmt->execute();
                }*/
            }
        }
        $id = $db->lastInsertId();


        $db = null;

        echo '{"usage": '.$id.'}';


    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        //echo '{"error":{"text":"' . $e->getMessage() . '"}}';
        die ($e->getMessage() );
    }
}

function saveUsage($username){
    saveUsageV1($username);

/*    $request = \Slim\Slim::getInstance()->request();
    $usage = json_decode($request->getBody());

    //$file = json_decode("{'file_name':'','size':''}");
    //var_dump($_FILES["fileToUpload"]["name"]);die();
    //$file->file_name = $_FILES["fileToUpload"]["name"];
    //$file->size = $_FILES['fileToUpload']['size']/MB;
//var_dump($usage);die();
    $sql = "insert into program_usage ( program, instance, time, date, user_id) VALUES ( :program, :instance, :time, :date, :user_id)
            ON DUPLICATE KEY UPDATE time=time+:newT;";

    $sql2 = "insert into files ( file_name, program_usage_id, time) VALUES ( :file_name, :program_usage_id, :time)
            ON DUPLICATE KEY UPDATE time=time+:newT;";

    try {


        $db = getDB();
        foreach ($usage as $k => $u){
            if (isset($u->program)) {
                $stmt = $db->prepare($sql);
                $ti = $u->time;
                $stmt->bindParam("program", $u->program);
                $stmt->bindParam("instance", $k);
                $stmt->bindParam("time", $ti);
                $stmt->bindParam("newT", $ti);
                $stmt->bindParam("date", date('Y-m-d'));
                $stmt->bindParam("user_id", $username);



                $stmt->execute();
                $id = $db->lastInsertId();
                foreach ($u->files as $k1 => $u1){

                    $stmt = $db->prepare($sql2);

                    $stmt->bindParam("program_usage_id", $id);
                    $stmt->bindParam("file_name", $k1);
                    $stmt->bindParam("time", $u1);
                    $stmt->bindParam("newT", $u1);

                    $stmt->execute();
                }
            }
        }
        $id = $db->lastInsertId();
        $usage->id = $id;


        $db = null;

        echo '{"usage": '.json_encode($usage).'}';


    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":"' . $e->getMessage() . '"}}';
    }*/
}



?>
