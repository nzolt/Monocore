<?php

namespace Monoco;

// Set the full path to the docroot
define('DOCROOT', realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR);

require_once(__DIR__ . '/vendor/autoload.php');

use Monoco;
Use Psr\Log\LoggerInterface;
Use Psr\Log\LogLevel;
Use Monoco\LogTime;

$config = new Config(__DIR__ . '/etc/config.json');
$logger = new BufferLog(__DIR__ . '/log/email.log');

$routes = [
    //'/path'           => ['type', 'controller', 'function'],
    '/'                 => ['get',  'site', 'index'],
    '/user'             => ['get',  'user',  'index'],
    '/user/register'    => ['get',  'user',  'register'],
    '/user/add'         => ['get',  'user',  'add'],
    '/user/list'        => ['get',  'user',  'user'],
    '/user:id'          => ['get',  'user',  'user'],
    '/blog/list'        => ['get',  'blog',  'index'],
    '/blog:id'          => ['get',  'blog',  'show'],
    '/blog/new'         => ['get',  'blog',  'new'],
    '/blog/delete'      => ['post', 'blog',  'delete'],
    '/blog/edit:id'     => ['post', 'blog',  'edit'],
    '/blog/add'         => ['get',  'blog',  'new'],
    '/blog/save'        => ['post', 'blog',  'create'],
    '/blog/edit:id'     => ['post', 'blog',  'edit'],
    '/blog/update'      => ['put',  'blog',  'update'],
];
$app = new App($routes, $config, $logger);
echo 'xxx';