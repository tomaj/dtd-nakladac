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
C9D7F1
$input = '<!DOCTYPE NEWSPAPER [
<!ELEMENT NEWSPAPER (ARTICLE*,HEADLINE+,ASD?)>
]>';

$codeAnalyzer = new CodeAnalyzer($input);
$analyzedCode = $codeAnalyzer->getAnalyzedCode();
print_r($analyzedCode);


$gramatika = new Gramatika(AppConfig::get('terminaly'), AppConfig::get('empty_symbol'), AppConfig::get('gramatika'));
$table = $gramatika->getTable();
//print_r($table);
/*
echo "<pre>";
$firsts = $gramatika->getFirsts();
print_r($firsts);
echo "</pre>";
*/
//die();


echo $table->getHTMLTable();
//die();

//print_r($table);
//die();

echo "<pre>";
$kram = new Kram($table);
$result = $kram->validateInput($codeAnalyzer->getAnalyzedCode(), new Symbol('S', Symbol::NETERMINAL));
Logger::log($result);
echo "</pre>";



Logger::log('BYE!');
?>