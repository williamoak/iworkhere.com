<?

    $servername = $_SERVER["SERVER_NAME"];
    $webname    = $_SERVER["PHP_SELF"];
    $query      = $_SERVER["QUERY_STRING"];

    echo "Place holder for $webname at $servername with $query<br/>\n";