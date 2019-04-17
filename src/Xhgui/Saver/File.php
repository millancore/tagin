<?php

class Xhgui_Saver_File implements Xhgui_Saver_Interface
{
    private $_file;

    public function __construct($file)
    {
        $this->_file = $file;
    }

    public function save(array $data)
    {
        $json = json_encode($data);
        return file_put_contents($this->_file, print_r($data, true).PHP_EOL, FILE_APPEND);
    }
}
