<?php
namespace App\Kernel;

class Auth {
    private function check() {
        if(isset($_SESSION['username'])) {
            return $_SESSION['username'] != "";
        }
        return false;
    }

    private function auth(string $username) {
        $_SESSION['username'] = $username;
    }

    private function get(): string {
        return $_SESSION['username'];
    }

    private function logout() {
        unset($_SESSION['username']);
    }

    private function getUser(): array {
        if($this->check()) {
            $result = Database::init()->query("SELECT id, username FROM users WHERE username='{$this->get()}' LIMIT 1");

            if($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                unset($row['password']);
                return $row;
            } else {
                throw new \Exception("User not found");
            }
        } else {
            throw new \Exception("Unauthorized");
        }
    }

    public static function __callStatic($name, $arguments)
    {
        $r = new self;
        return $r->$name(...$arguments);
    }
}
?>