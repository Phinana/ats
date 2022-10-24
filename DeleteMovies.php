<?php

    include "config.php";

    $id = $_POST['movie_id'];

    $pdo = create_conn("localhost", "ATS", "root", "");
    $query = "DELETE FROM `table_movie` WHERE id=".$id;
    $query_prepared = $pdo->prepare($query);
    $query_prepared->execute();