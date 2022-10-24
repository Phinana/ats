<?php

    include "config.php";

    $id = $_POST['movie_id'];
    $title = $_POST['movie_title'];
    $episodes = $_POST['movie_episodes'];
    $progress = $_POST['movie_progress'];
    $image = $_POST['movie_image'];
    $result['image_link'] = "";

    $pdo = create_conn("localhost", "ATS", "root", "");

    if (!filter_var($image, FILTER_VALIDATE_URL) === false) {
        $headers = get_headers($image, 1);
        if (strpos($headers['Content-Type'], 'image/') === false) {
            $image = "";
            $result['image_link'] = "https://upload.wikimedia.org/wikipedia/commons/thumb/6/65/No-Image-Placeholder.svg/1665px-No-Image-Placeholder.svg.png";
        }
        else{
            $result['image_link'] = $image;
        }
    } 
    else {
        $image = "";
        $result['image_link'] = "https://upload.wikimedia.org/wikipedia/commons/thumb/6/65/No-Image-Placeholder.svg/1665px-No-Image-Placeholder.svg.png";
    }

    if($image == "" || $image == null){
        $image = "https://upload.wikimedia.org/wikipedia/commons/thumb/6/65/No-Image-Placeholder.svg/1665px-No-Image-Placeholder.svg.png";
        $query = "UPDATE `table_movie` SET `title`='". $title ."',`episodes`='". $episodes ."',`progress`='". $progress ."',`image`='". $image ."' WHERE id=". $id;
    }else if($image != "" || $image != null){
        $query = "UPDATE `table_movie` SET `title`='". $title ."',`episodes`='". $episodes ."',`progress`='". $progress ."',`image`='". $image ."' WHERE id=". $id;
    }
    $query_prepared = $pdo->prepare($query);
    $query_prepared->execute();

    echo json_encode($result['image_link']);