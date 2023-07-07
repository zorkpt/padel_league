<?php

class NotificationController {
    public static function create($user_id, $content, $link = null) {
        $conn = dbConnect();
        $stmt = $conn->prepare('INSERT INTO notifications (user_id, content, link) VALUES (:user_id, :content, :link)');
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':link', $link);
        $stmt->execute();
    }

    public static function getUnreadByUser($user_id) {
        // "unread" if "read_at" column is NULL
        $conn = dbConnect();
        $stmt = $conn->prepare('SELECT * FROM notifications WHERE user_id = :user_id AND read_at IS NULL ORDER BY created_at DESC');
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function markAsRead($notification_id) {
        $conn = dbConnect();
        $stmt = $conn->prepare('UPDATE notifications SET read_at = NOW() WHERE id = :id');
        $stmt->bindParam(':id', $notification_id);
        $stmt->execute();
    }

    public static function handleReadNotification() {
        $notification_id = $_GET['id'];

        self::markAsRead($notification_id);

        $notification = self::getById($notification_id);


        header('Location: ' . $notification['link']);
    }


    public static function getById($id) {
        $conn = dbConnect();
        $stmt = $conn->prepare("SELECT * FROM notifications WHERE id = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function notifyUsers($users, $content, $link)
    {
        foreach ($users as $user) {
            self::create($user, $content, $link);
        }
    }




}

