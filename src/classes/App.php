<?php

namespace Monoco;

use Monoco\Config;
use Monoco\BufferLog;

class App
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';

    public function __construct(array $routes, Config $config, BufferLog $logger)
    {
        $path = $this->parseUrl();var_dump($path, 'path');
        $routesClean = $this->parseRoutes($routes);
        $realPath = $this->findRoute($routesClean, $path);
        //$match[] = array_walk($routes, [$this, 'findRoute']);
        var_dump($routesClean);
//        $path =
//            ^\/blog\/edit\/[1-9]+$

    }

    public function findRoute($routesClean, $path)
    {
        $realPath = null;
        $id = null;
//        $key = str_replace('/', '\/', $key);
//        $key = preg_replace('/{[\S\d]+}$/', '[1-9]+$', $key);
        foreach($routesClean as $routeClean => $sets)
        {
            if($routeClean['id'] !== null)
            {
                $pathArray = explode('/', $path['path']);
                array_shift ($pathArray);
                if(end($pathArray) == '')
                {
                    array_pop($pathArray);
                }
                $id = end($pathArray);
                array_pop($pathArray);
                $realPath = '/' . implode('/', $pathArray) . '/';
            }
            else{
                $realPath = $path;
            }
        }

        var_dump($path, 'path', $realPath, 'rp', $id, 'id');
        return [$routesClean[$realPath], $id];
    }

    protected function parseUrl()
    {
        $path = parse_url($_SERVER['PATH_INFO']);

        return $path;
    }

    /**
     * @param array $routes
     * @return array
     */
    public function parseRoutes(array $routes)
    {
        $routesClean = [];
        foreach($routes as $route => $resource)
        {
            if(strpos($route, ':'))
            {
                $routeArray = explode(':', $route);
                $routesClean[$routeArray[0]] = $resource;
                $routesClean[$routeArray[0]]['id'] = end($routeArray);
            }
            else
            {
                $routesClean[$route] = $resource;
                $routesClean[$route]['id'] = null;
            }
        }

        return $routesClean;
    }

    /**
     * Get HTTP method
     * @return string
     */
    public function getMethod()
    {
        return $this->env['REQUEST_METHOD'];
    }

    /**
     * Is this a GET request?
     * @return bool
     */
    public function isGet()
    {
        return $this->getMethod() === self::METHOD_GET;
    }

    /**
     * Is this a POST request?
     * @return bool
     */
    public function isPost()
    {
        return $this->getMethod() === self::METHOD_POST;
    }

    /**
     * Is this a PUT request?
     * @return bool
     */
    public function isPut()
    {
        return $this->getMethod() === self::METHOD_PUT;
    }
}