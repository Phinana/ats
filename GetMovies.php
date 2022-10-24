<?php

    include "config.php";

    $limit = $_POST['limit'];
    $off_set = $_POST['off_set'];


    $pdo = create_conn("localhost", "ATS", "root", "");
    $query = "SELECT * FROM table_movie ORDER BY title";
    $query_result = $pdo->query($query, PDO::FETCH_ASSOC)->fetchAll();

    echo json_encode($query_result);
    