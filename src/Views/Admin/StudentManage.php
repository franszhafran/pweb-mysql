<?php

namespace App\Views\Student;

use DateTime;

class StudentManage {
    public function html() {
        return file_get_contents('studentmanage.html', true);
    }

    public static function init(): self {
        $s = new self;
        $s->data = [];
        return $s;
    }

    public function setData($data): self {
        $this->data = $data;
        return $this;
    }

    public function generate() {
        $html = $this->html();
        foreach($this->data as $key=>$d) {
            if(\is_string($d)) {
                $html = str_replace("{{\$" . $key . "}}", $d, $html);
            }
            
            if($key == "students") {
                $table = "";
                foreach($d as $idx=>$student) {
                    $num = $idx+1;

                    $birth_date = DateTime::createFromFormat("Y-m-d", $student['birth_date']);
                    $table .= "<tr>";
                    $table .= "<td>{$num}</td>";
                    $table .= "<td>{$student['name']}</td>";
                    $table .= "<td>{$student['username']}</td>";
                    $table .= "<td>{$birth_date->format('d F Y')}</td>";
                    $table .= "</tr>";
                }
                $html = str_replace("{{\$" . $key . "}}", $table, $html);
            }
        }
        return $html;
    }
}
?>