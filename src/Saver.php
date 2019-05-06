<?php

namespace Tagin;

use MongoClient;
use Tagin\Saver\File;
use Tagin\Saver\Mongo;
use Tagin\Saver\Upload;
use Tagin\Contract\SaverContract;

/**
 * A small factory to handle creation of the profile saver instance.
 *
 * This class only exists to handle cases where an incompatible version of pimple
 * exists in the host application.
 */
class Saver
{
    /**
     * Get a saver instance based on configuration data.
     *
     * @param array $config The configuration data.
     * @return SaverContract
     */
    public static function factory($config)
    {

        switch ($config['save.handler']) {

            case 'file':
                return new File($config['save.handler.filename']);

            case 'upload':
                $timeout = 3;
                if (isset($config['save.handler.upload.timeout'])) {
                    $timeout = $config['save.handler.upload.timeout'];
                }
                return new Upload(
                    $config['save.handler.upload.uri'],
                    $timeout
                );

            case 'mongodb':
            default:
                $mongo = new MongoClient($config['db.host'], $config['db.options']);
                $collection = $mongo->{$config['db.database']}->results;
                $collection->findOne();
                return new Mongo($collection);
        }
    }
}
