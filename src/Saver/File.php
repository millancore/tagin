<?php

namespace Tagin\Saver;

use Tagin\Contract\SaverContract;

class File implements SaverContract
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
