<?php

    error_reporting(-1);
    require 'vendor/autoload.php';
    require("cmd-line-common.do.i");

    use Elasticsearch\ClientBuilder;

    $es_url = $_ENV['ELASTICSEARCH_URL'];
    echo "Connecting to elasticsearch: $es_url\n";
    $es_builder = ClientBuilder::create();
    $es_builder->setHosts([$es_url]);
    $client = $es_builder->build();

    $scroll_timeout = '120s';

    $params = [
        "search_type" => "scan",
        "scroll" => $scroll_timeout,
        "size" => 100,
        "index" => "logging-*",
        "body" => [
            "query" => [
                "bool" => [
                    "filter" => [[
                        "query" => [
                            "match" => [
                                "image_version" => [
                                    "query" => "stage",
                                    "type" => "phrase"
                                ]
                            ]
                        ]
                    ], [
                        "query" => [
                            "match" => [
                                "image_tag" => [
                                    "query" => "personnelink_web",
                                    "type" => "phrase"
                                ]
                            ]
                        ]
                    ], [
                        "range" => [
                            "timestamp" => [
                                "gte" => "2016-11-01",
                                "lte" => "2016-11-02",
                            ]
                        ]
                    ]]
                ]
            ]
        ]
    ];

    $docs = $client->search($params);
    $scroll_id = $docs['_scroll_id'];
    $log_data = [];

    $log_file = namedtmpfile("apache_log");
    $log_fh = fopen($log_file, 'w');
    if ($log_fh === FALSE) {
        die("Failed to open: $log_file\n");
    }
    echo "Writing logs to: $log_file\n";
    $records_count = 1;
    while (true) {
        $response = $client->scroll([
                "scroll_id" => $scroll_id,
                "scroll" => $scroll_timeout,
            ]
        );

        $rows = &$response['hits']['hits'];

        if (count($rows) > 0) {
            foreach ($rows as $row) {
                $message = [$row['_source']['timestamp'], $row['_source']['message']];
                // if (!is_array($message)) {
                    $console_text = fwrite($log_fh, $message[1] . "\n");
                    ++$records_count;

                    echo $records_count . " downloaded ... ";
                    echo "\033[" . strlen($records_count . " downloaded ... ")."D";

                    $scroll_id = $response['_scroll_id'];
                // } else {
                    // echo "array returned.\n";
                    // print_r($message);

                // }
            }
        } else {
            break;
        }
    }

    fclose($log_fh);
    echo $records_count . " total downloaded.\n";
    echo "done.\n";

    $map_file = namedtmpfile('map');
    echo "Building updated map file: $map_file\n";
    $dbhost = $_ENV['DATABASE_HOST'];
    $dbport = $_ENV['DATABASE_PORT'];
    $dbname = $_ENV['DATABASE_NAME'];
    $dbuser = $_ENV['DATABASE_USER'];
    $dbpassword = $_ENV['DATABASE_PASSWORD'];
    $dbh = pg_pconnect("host=$dbhost port=$dbport dbname=$dbname " .
                       "user=$dbuser password=$dbpassword");
    if (!$dbh) {
        die("Couldn't Open Database\n");
    }

    $sql= "SELECT user_name, web_host
            FROM cuinfo;";

    $rs = pg_query($dbh, $sql);

    if (!$rs) {
        die("DB Query on cuinfo table failed\n");
    }

    $map_lines = "";

    while ( $row = pg_fetch_array($rs) ) {
        $map_lines .= strtoupper(trim($row["user_name"])) . ":" . strtolower(trim($row["user_name"])) . chr(9) . just_the_domain(trim($row["web_host"])) . "\n";
    }

    file_put_contents($map_file, $map_lines);

    echo "done. \n\nAll finished.\n";

    // coder for alternate method using querystring without scroll

    // $url = "http://logs.infra.personnelink.io/logging-*/docker/_search?q=timestamp:%7Bnow-1d%20TO%20now%7D%20AND%20!message:HealthChecker&fields=message";

    // // Get log data from elasticsearch (JSON string)
    // print "Reading JSON log data ... ";
    // $JSON_log_data = get_data($url);

    // // Convert JSON to Array
    // print "done. \nProcessing log data ... ";
    // $logArray = json_decode($JSON_log_data, true);

    // // print_r($logArray);        // Dump all data of the Array

    // $log_file = 'apache_log.txt';
    // $logs = "";

    // foreach ($logArray["hits"]["hits"] as $log_entry) {
    //     $logs .= $log_entry["fields"]["message"]["0"]."\n"; // Access Object data
    // }

    // file_put_contents($log_file, $logs);
    // print "done. \nLog data written to " . $log_file . "\n\n";
