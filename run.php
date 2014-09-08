<?php

$path = 'tst.csv';

if (file_exists($path)) {

    $fp = fopen($path, 'r');
    while ($row = fgetcsv($fp, 1000, ',')) {
        $data = array();

        foreach ($row as $k => $v) {
            $data[$k] = $v;
        }

        if ($data[3] != 'ConnectorLayout_120') {
            $dir = dirname(__FILE__) . '/contents/' . $data[3];

            if (! is_dir($dir)) {
                mkdir($dir);
                chmod($dir, 0777);

                mkdir($dir . '/HTML');
                chmod($dir . '/HTML', 0777);
            }

            copy(dirname(__FILE__) . '/data/.mod', $dir . '/.mod');
            copy(dirname(__FILE__) . '/data/skipindex', $dir . '/skipindex');

            $dom = new DOMDocument('1.0', 'UTF-8');
            $dom->formatOutput = true;

            $book = $dom->createElement('Book');
            $dom->appendChild($book);

            $attr = $dom->createAttribute('ja_title');
            $attr->value = $data[3];
            $book->appendChild($attr);

            $id_c = $dom->createElement('ID_CATEGORY', '31_OM');
            $book->appendChild($id_c);

            var_dump($dom->saveXML());
            exit();
        }
    }

} else {
    echo 'csv is not found!' . "\n";
}
