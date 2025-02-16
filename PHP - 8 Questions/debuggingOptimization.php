<?php

function getUserPosts($userId, PDO $db) {
    $stmt = $db->prepare("SELECT * FROM posts WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$db = new PDO('mysql:host=localhost;dbname=test', 'root', '');
$userPosts = getUserPosts(1, $db);

echo '<pre>';
print_r($userPosts);
echo '</pre>';

?>