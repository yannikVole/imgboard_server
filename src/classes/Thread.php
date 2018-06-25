<?php
namespace imgb;

class Thread{
    private $db;

    public function __construct($newDB){
        $this->db = $newDB;
    }

    public function getThreads(){
        $stmt = $this->db->prepare("SELECT * FROM threads ORDER BY created_at DESC");
        if(!$stmt->execute()){
            return false;
        }
        $threads = $stmt->fetchAll();
        
        return $threads;
    }

    public function getThreadById($id){
        $stmt = $this->db->prepare("SELECT * FROM threads WHERE id = :id");
        if(!$stmt->execute()){
            return false;
        }
        $thread = $stmt->fetch();

        return $thread;
    }

    public function addNewThread($thread){
        $stmt = $this->db->prepare('INSERT INTO threads (user_id, title, body) VALUES(:user_id, :title, :body)');
        $stmt->bindValue(':user_id',$thread['user_id']);
        $stmt->bindValue(':title',$thread['title']);
        $stmt->bindValue(':body',$thread['body']);

        if(!$stmt->execute()){
            return false;
        }
        return true;
    }

    public function updateThreadById($thread,$id){
        $stmt = $this->db->prepare('UPDATE threads SET title = :title, body = :body WHERE id = :id');
        $stmt->bindValue(':id',$id);
        $stmt->bindValue(':title',$thread['title']);
        $stmt->bindValue(':body',$thread['body']);

        if(!$stmt->execute()){
            return false;
        }
        return true;
    }

    public function deleteThreadById($id){
        $stmt = $this->db->prepare('DELETE FROM threads WHERE id = :id');
        $stmt->bindValue(':id',$id);

        if(!$stmt->execute()){
            return false;
        }
        return true;
    }



    
}