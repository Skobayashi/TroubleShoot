<?php

class Publisher
{
    private $request,
        $file,
        $dom,
        $base_path = '/Users/suguru/Desktop/TroubleShoot';

    public function __construct($request, $file)
    {
        $this->request = $request;
        $this->file = $file;
    }

    public function _initDom()
    {
        $this->dom = new DOMDocument('1.0', 'UTF-8');
        $this->dom->formatOutput = true;
    }

    public function _getPath($book_id)
    {
        if ($this->request['path'] != '') {
            $path = $this->request['path'];
        
        } else {
            $path = $this->base_path;
        }

        if (! is_dir($path)) {
            mkdir($path);
            chmod($path, 0777);
        }

        $path .= '/' . $book_id;
        if (! is_dir($path)) {
            mkdir($path);
            chmod($path, 0777);
        }

        $h_path = $path . '/HTML';
        if (! is_dir($h_path)) {
            mkdir($h_path);
            chmod($h_path, 0777);
        }

        return $path;
    }

    public function _buildSearchInfo($data)
    {
        /**
         * setting
         * data0 title
         * data3 book_id
         * data4 type
         * data5 model
         * data7 destination
         */

        $book = $this->dom->createElement('Book');
        $this->dom->appendChild($book);

        $attr = $this->dom->createAttribute('ja_title');
        $attr->value = $data[0];
        $book->appendChild($attr);

        $id_c = $this->dom->createElement('ID_CATEGORY', '31_OM');
        $book->appendChild($id_c);

        $ja_b = $this->dom->createElement('JA_BOOK', $data[0]);
        $book->appendChild($ja_b);

        $ja_t = $this->dom->createElement('JA_TYPE', $data[4]);
        $book->appendChild($ja_t);

        $model = $this->dom->createElement('MODEL', $data[5]);
        $book->appendChild($model);

        $ja_d = $this->dom->createElement('JA_DEALER', $data[7]);
        $book->appendChild($ja_d);

        return $book;
    }

    public function buildConnectorInfo()
    {
        $this->_initDom();

        $fp = fopen($this->file['tmp_name'], 'r');
        while ($row = fgetcsv($fp, 1000, ',')) {
            $data = array();

            foreach ($row as $k => $v) {
                $data[$k] = $v;
            }

            $pdf_path = $this->request['data_path'] . '/' . $data[3] . '.pdf';

            if (! file_exists($pdf_path)) {
                throw new Exception(sprintf('%s pdf file is not found!', $data[3]));
            }

            if (preg_match('/ConnectorLayout/', $data[3])) {
                $book_name = $data[3];
                $path = $this->_getPath($book_name);
                $book = $this->_buildSearchInfo($data);

                $chapter = $this->dom->createElement('ChapterItem');
                $book->appendChild($chapter);

                $attr = $this->dom->createAttribute('ja_title');
                $attr->value = $data[0];
                $chapter->appendChild($attr);

                $attr = $this->dom->createAttribute('id_p');
                $attr->value = $data[3] . '.pdf';
                $chapter->appendChild($attr);

            } else {
                $item = $this->dom->createElement('ChapterItem');
                $chapter->appendChild($item);

                $attr = $this->dom->createAttribute('ja_title');
                $attr->value = $data[1];
                $item->appendChild($attr);

                $attr = $this->dom->createAttribute('id_p');
                $attr->value = $data[3] . '.pdf';
                $item->appendChild($attr);
            }

            copy($pdf_path, $path . '/HTML/' . $data[3] . '.pdf');
        }


        $this->dom->save($path . '/' . $book_name . '@INDEX.xml');
        copy(dirname(__FILE__) . '/../data/.mod', $path . '/.mod');
        copy(dirname(__FILE__) . '/../data/skipindex', $path . '/skipindex');
    }

    public function buildCodeInfo()
    {
        $fp = fopen($this->file['tmp_name'], 'r');
        while ($row = fgetcsv($fp, 1000, ',')) {
            $data = array();

            foreach ($row as $k => $v) {
                $data[$k] = $v;
            }

            if (preg_match('/ConnectorLayout/', $data[3])) {
                continue;
            }

            $pdf_path = $this->request['data_path'] . '/' . $data[3] . '.pdf';

            if (! file_exists($pdf_path)) {
                throw new Exception(sprintf('%s pdf file is not found!', $data[3]));
            }

            $this->_initDom();
            $book = $this->_buildSearchInfo($data);

            $sec = $this->dom->createElement('Section');
            $book->appendChild($sec);

            $attr = $this->dom->createAttribute('ja_title');
            $attr->value = $data[1];
            $sec->appendChild($attr);

            $attr = $this->dom->createAttribute('id_p');
            $attr->value = $data[3] . '.pdf';
            $sec->appendChild($attr);

            $path = $this->_getPath($data[3]);

            $this->dom->save($path . '/' . $data[3] . '@INDEX.xml');

            copy($pdf_path, $path . '/HTML/' . $data[3] . '.pdf');
            copy(dirname(__FILE__) . '/../data/.mod', $path . '/.mod');
            copy(dirname(__FILE__) . '/../data/skipindex', $path . '/skipindex');
        }
    }
}
