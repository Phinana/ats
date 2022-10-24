<?php

    include "config.php";

    $pdo = create_conn("localhost", "ATS", "root", "");
    $query = "INSERT INTO `table_movie`(`title`, `episodes`, `progress`, `image`) VALUES ('Fate Izle',0 ,0 , 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/65/No-Image-Placeholder.svg/1665px-No-Image-Placeholder.svg.png')";
    $query_prepared = $pdo->prepare($query);
    $query_prepared->execute();