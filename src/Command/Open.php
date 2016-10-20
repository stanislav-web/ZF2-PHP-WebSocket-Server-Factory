<?php
namespace App\Command;

use ZF\Console\Route;
use Zend\Console\Adapter\AdapterInterface;

class Open
{
	public static function run(Route $route, AdapterInterface $console)
	{
		$name = $route->getMatchedParam("name", "@gianarb");
		$console->writeLine("Hi {$name}, you have call me. Now this is an awesome day!");
	}
}