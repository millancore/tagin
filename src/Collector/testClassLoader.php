<?php


/**
 * Manual Loader to Profiling from PHPUnit
 */

require_once TAJIN_HEADER . '/src/Util.php';
require_once TAJIN_HEADER . '/src/Saver.php';
require_once TAJIN_HEADER . '/src/Saver/Mongo.php';
require_once TAJIN_HEADER . '/vendor/mongodb/mongodb/src/Client.php';
require_once TAJIN_HEADER . '/vendor/mongodb/mongodb/src/Database.php';
require_once TAJIN_HEADER . '/vendor/mongodb/mongodb/src/Collection.php';
require_once TAJIN_HEADER . '/vendor/mongodb/mongodb/src/Operation/FindOne.php';
require_once TAJIN_HEADER . '/vendor/mongodb/mongodb/src/Operation/Find.php';
require_once TAJIN_HEADER . '/vendor/mongodb/mongodb/src/Model/BSONArray.php';
require_once TAJIN_HEADER . '/vendor/mongodb/mongodb/src/Model/BSONDocument.php';
require_once TAJIN_HEADER . '/vendor/mongodb/mongodb/src/Operation/InsertOne.php';
require_once TAJIN_HEADER . '/vendor/mongodb/mongodb/src/InsertOneResult.php';

require_once TAJIN_HEADER . '/vendor/alcaeus/mongo-php-adapter/lib/Alcaeus/MongoDbAdapter/TypeConverter.php';
require_once TAJIN_HEADER . '/vendor/alcaeus/mongo-php-adapter/lib/Alcaeus/MongoDbAdapter/ExceptionConverter.php';
require_once TAJIN_HEADER . '/vendor/alcaeus/mongo-php-adapter/lib/Mongo/MongoDB.php';
require_once TAJIN_HEADER . '/vendor/alcaeus/mongo-php-adapter/lib/Mongo/MongoClient.php';
require_once TAJIN_HEADER . '/vendor/alcaeus/mongo-php-adapter/lib/Mongo/MongoId.php';
require_once TAJIN_HEADER . '/vendor/alcaeus/mongo-php-adapter/lib/Mongo/MongoDate.php';
require_once TAJIN_HEADER . '/vendor/alcaeus/mongo-php-adapter/lib/Mongo/MongoException.php';
require_once TAJIN_HEADER . '/vendor/alcaeus/mongo-php-adapter/lib/Mongo/MongoCollection.php';
