<?php
	//initialize parser

	include_once "JBBCode/Parser.php";

	global $parser;

	$parser = new JBBCode\Parser();
	$parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());

	//add custom codes

	$builder = new JBBCode\CodeDefinitionBuilder('quote', '<div class="well well-sm">{param}</div>');
	$parser->addCodeDefinition($builder->build());

?>