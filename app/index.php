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
//Logger::log('Starting...');
Logger::log('');


$input = '<!DOCTYPE NEWSPAPER [ 
<!ELEMENT NEWSPAPER (ARTICLE+)> 
]>';
/*
// <!ELEMENT ARTICLE (HEADLINE,BYLINE,LEAD,BODY,NOTES)>
<!ELEMENT NEWSPAPER (ARTICLE+)>
<!ELEMENT HEADLINE (#PCDATA)>
<!ELEMENT BYLINE (#PCDATA)>
<!ELEMENT LEAD (#PCDATA)>
<!ELEMENT BODY (#PCDATA)>
<!ELEMENT NOTES (#PCDATA)> 
<!ATTLIST ARTICLE DATE CDATA #IMPLIED>
<!ATTLIST ARTICLE EDITION CDATA #IMPLIED>
<!ATTLIST ARTICLE EDITOR CDATA #IMPLIED>
<!ATTLIST ARTICLE DATE CDATA #IMPLIED>
*/

$input = '<!DOCTYPE NEWSPAPER [
<!ELEMENT ARTICLE (HEADLINE,BYLINE,LEAD,BODY,NOTES)>
]>';

$codeAnalyzer = new CodeAnalyzer($input);
$analyzedCode = $codeAnalyzer->getAnalyzedCode();
print_r($analyzedCode);

$gramatika = new Gramatika(AppConfig::get('terminaly'), AppConfig::get('empty_symbol'), AppConfig::get('gramatika'));
$table = $gramatika->getTable();
//print_r($table);
echo $table->getHTMLTable();
die();
//print_r($table);
//die();


$kram = new Kram($table);
$result = $kram->validateInput($codeAnalyzer->getAnalyzedCode(), new Symbol('S', Symbol::NETERMINAL));
Logger::log($result);



Logger::log('BYE!');
?>