<?php
namespace Dajangter;

class Route
{
	public static $GET = [];
    public static $POST = [];
    
    public static function route($url)
    {   
        foreach( self::${$_SERVER['REQUEST_METHOD']} as $req)
        {
            if($req[0] === $url)
            {
                $actions = explode("@" , $req[1]);
                $cName = "\\Dajangter\\Controller\\" . $actions[0];
                $cInstance = new $cName();
                $cInstance->{$actions[1]}();
                return;
            }

        }

        echo "404 NOT FOUND";
		exit;
    }

    public static function post($url, $action)
    {
        self::$POST[] = [$url, $action];   
    }

    public static function get($url, $action)
    {
        self::$GET[] = [$url, $action];
    }
}
