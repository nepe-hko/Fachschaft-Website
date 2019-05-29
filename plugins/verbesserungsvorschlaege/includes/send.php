<?php
require_once("../../../../wp-load.php");

# get data from frontend
// TODO: validate user input
$user = "username!!!";
$title = $_POST['title'];
$content = $_POST['content'];


# create new improvement-post
$new_post = array(
    'post_title' => $title,
    'post_content' => $content,
    'post_status' => 'pending',
    'post_votes' => 0,
    'post_user' => $user,
    'post_type' => 'improvement'
);
$post_id = wp_insert_post($new_post);

echo "vielen Dank. <br>Dein Vorschlag wurde eingereicht und wird nach einer Prüfung veröffentlicht";