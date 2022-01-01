<?php

namespace App\Views\Student;

class StudentCreate {
    public function html() {
        return file_get_contents('studentcreate.html', true);
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
            $html = str_replace("{{\$" . $key . "}}", $d, $html);
        }
        return $html;
    }
}
?>