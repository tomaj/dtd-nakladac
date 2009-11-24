<?php
/**************************************************************
 *	
 *		Zadanie na predmet prekladace
 *
 * 	zadaklny index subor
 *  Autori: Tomas Majer && Michal Lulco
 *
 **************************************************************/

// nainicilizujeme autoload
define('BASE_DIR', dirname(__FILE__));
require_once(BASE_DIR . '/misc/Autoload.php');

// inicializujeme si logger
$logger = Logger::getInstance();
$logger->addLogger(new ConsoleLogger());

Logger::log('');
Logger::log('Starting...');
Logger::log('');

$input = 'yxyxxzxx';

$gramatika = new Gramatika(AppConfig::get('terminaly'), AppConfig::get('empty_symbol'), AppConfig::get('gramatika'));
$table = $gramatika->getTable();

$kram = new Kram($table);
$result = $kram->validateInput($input, new Symbol('S', Symbol::NETERMINAL));
Logger::log($result);



Logger::log('BYE!');
?>