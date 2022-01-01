<?php

namespace App\Controller;

use App\Kernel\Auth;
use App\Views\Admin\Login;
use App\Kernel\Database;
use App\Views\Student\StudentCreate;
use App\Kernel\Request;
use App\Views\Student\StudentManage;

class AdminController {
    public function studentcreate() {
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            return $this->createStudent();
        }
        echo StudentCreate::init()->generate();
    }

    public function studentManage() {
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            return $this->createStudent();
        }

        echo StudentManage::init()->setData([
            "students" => $this->studentManageData(),  
        ])->generate();
    }

    private function studentManageData() {
        $db = Database::init();
        $result = $db->query("SELECT name, username, birth_date FROM users WHERE type='student'");
        
        $students = [];

        if($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $students[] = $row;
            }
        }
        $db->close();

        return $students;
    }

    private function createStudent() {
        $request = Request::init();

        $query = "INSERT INTO users (username, password, type, nid, gender, birth_date, photo_link, name) VALUES (?, ?, ?, ?, ?, ?, ?, ?);";

        $db = Database::init();
        $stmt = $db->getConnection()->prepare($query);

        $data = [
            $request->username,
            md5($request->password),
            "student",
            $request->nid,
            $request->gender,
            $request->birth_date,
            $request->photo_link,
            $request->name,
        ];
        $stmt->bind_param("ssssssss", ...$data);

        $stmt->execute();

        header("Refresh:2; url=/studentcreate", true, 303);
        echo "Berhasil membuat student, mengarahkan...";
    }
    
    public function migrate() {
        echo "Trying to migrate...<br>";

        $queries = [];

        $queries['query_drop_user'] = "DROP TABLE IF EXISTS `users`;";
        $queries['query_create_user'] = "CREATE TABLE `users` (
            `id` int NOT NULL AUTO_INCREMENT,
            `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `gender` varchar(100) DEFAULT NULL,
            `birth_date` date DEFAULT NULL,
            `nid` varchar(100) DEFAULT NULL,
            `photo_link` varchar(100) DEFAULT NULL,
            `name` varchar(100) DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `users_UN` (`username`)
          ) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;";

        $password = md5("password");

        $queries['seed_users_admin'] = "INSERT INTO `users` (username, password, type) VALUES ('admin', '{$password}', 'admin')";
        $queries['seed_users_teacher'] = "INSERT INTO `users` (username, password, type, name) VALUES ('adi', '{$password}', 'teacher', 'Ado Setiadi')";

        $queries['query_drop_classes'] = "DROP TABLE IF EXISTS `classes`";
        $queries['query_create_classes'] = "CREATE TABLE `classes` (
          `id` int NOT NULL AUTO_INCREMENT,
          `subject` varchar(100) DEFAULT NULL,
          `name` varchar(100) DEFAULT NULL,
          `description` varchar(100) DEFAULT NULL,
          `schedule` varchar(100) DEFAULT NULL,
          `teacher_id` int DEFAULT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `classes_UN` (`subject`),
          KEY `classes_FK` (`teacher_id`),
          CONSTRAINT `classes_FK` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;";

        $conn = Database::init()->getConnection();
        foreach($queries as $key=>$query) {
            $conn->query($query);

            if($conn) {
                echo "{$key}: Success<br>";
            } else {
                echo "{$key}: Failed<br>";
            }
        }
        
    }
}