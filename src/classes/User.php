<?php
class User{
    private $db;

    public function __construct($newDB){
        $this->db = $newDB;
    }

    public function getUsers(){
        $stmt = $this->db->prepare("SELECT * FROM users ORDER BY created_at DESC");
        $stmt->execute();
        $users = $stmt->fetchAll();

        return $users;
    }

    public function getUserById(int $id){
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id=:id');
        $stmt->bindValue(":id",$id);
        $stmt->execute();
        $user = $stmt->fetchAll();

        return $user;
    }

    public function addNewUser(array $user){
        $stmt = $this->db->prepare('INSERT INTO users(username,password,email) VALUES(:username,:password,:email)');
        $stmt->bindValue(':username',$user['username']);
        $stmt->bindValue(':password',$user['password']);
        $stmt->bindValue(':email',$user['email']);

        if($stmt->execute()){
            return true;
        } else {
            return false;
        }
    }

    public function updateUserById(array $user,$id){
        $stmt = $this->db->prepare('UPDATE users SET username = :username, email = :email, password = :password, is_active = :is_active WHERE id = :id');
        $stmt->bindValue(':username',$user['username']);
        $stmt->bindValue(':password',$user['password']);
        $stmt->bindValue(':email',$user['email']);
        $stmt->bindValue(':is_active',$user['is_active']);
        $stmt->bindValue(':id',$id);

        if($stmt->execute()){
            return true;
        } else {
            return false;
        }
    }

    public function deleteUserById($id){
        $stmt = $this->db->prepare('DELETE FROM users WHERE id = :id');
        $stmt->bindValue(':id',$id);

        if($stmt->execute()){
            return true;
        } else {
            return false;
        }
    }

    public function getUserByUsername($username){
        $stmt = $this->db->prepare('SELECT id,password FROM users WHERE username = :username');
        $stmt->bindValue(':username',$username);
        $stmt->execute();
        $user = $stmt->fetch();
        if(!$user){
            return false;
        }
        return $user;
    }


}