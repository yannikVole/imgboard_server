<?php

class pThread{
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

        foreach($threads as $thread){
            $thread->comments = $this->populateComments($thread->id);
        }
        
        return $threads;
    }

    public function getThreadById($id){
        $stmt = $this->db->prepare("SELECT * FROM threads WHERE id = :id");
        $stmt->bindValue(':id',$id);
        if(!$stmt->execute()){
            return false;
        }
        $thread = $stmt->fetch();

        $thread->comments = $this->populateComments($thread->id);

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

    public function getThreadsByUserId($user_id){
        $stmt = $this->db->prepare('SELECT * FROM threads WHERE user_id = :user_id');
        $stmt->bindValue(':user_id',$user_id);

        if(!$stmt->execute()){
            return false;
        }
        $threads = $stmt->fetchAll();
        return $threads;
    }

    public function addCommentToThread($threadId,$comment){
        $stmt = $this->db->prepare('INSERT INTO comments (thread_id,body) VALUES(:thread_id, :body)');
        $stmt->bindValue(':thread_id', $threadId);
        $stmt->bindValue(':body',$comment);

        if(!$stmt->execute()){
            return false;
        }
        return true;
    }

    private function populateComments($id){
        $stmt = $this->db->prepare('SELECT * FROM comments WHERE thread_id = :thread_id');
        $stmt->bindValue(':thread_id',$id);

        $stmt->execute();
        $comments = $stmt->fetchAll();
        if($comments){
            return $comments;
        } else {
            return false;
        }
    }

    



    
}