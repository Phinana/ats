<?php
    
    function create_conn($host, $dbname, $user, $password){
        $dsn = "mysql:host=".$host.";dbname=".$dbname;
        $pdo = new PDO($dsn, $user, $password);

        return $pdo;
    }