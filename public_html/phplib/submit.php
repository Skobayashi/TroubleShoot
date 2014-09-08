<?php

set_time_limit(0);
require_once dirname(__FILE__) . '/publisher.php';

$request = $_POST;
$file = $_FILES['file'];

try {
    if ($file['name'] == '') {
        throw new Exception('file is not found!');
    }

    if ($file['name'] != 'tst.csv') {
        throw new Exception('file name is not tst.csv');
    }

    if ($file['type'] !== 'text/csv') {
        throw new Exception('file is not csv!');
    }

    $pub = new Publisher($request, $file);

    switch ($request['type']) {
        case 'connector':
            $pub->buildConnectorInfo();
            break;

        case 'code':
            $pub->buildCodeInfo();
            break;
    }

    echo 'Success!';
    exit();

} catch (Exception $e) {
    throw $e;
}

