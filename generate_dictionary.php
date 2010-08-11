#!/usr/bin/env php
<?php
error_reporting(-1);
header('Content-type: text/plain; charset=utf-8');

$master = simplexml_load_string('<d:dictionary xmlns="http://www.w3.org/1999/xhtml" xmlns:d="http://www.apple.com/DTDs/DictionaryService-1.0.rng"></d:dictionary>');
$i = 1;

// http://docstore.mik.ua/orelly/webprog/pcook/ch04_26.htm
function pc_next_permutation($p, $size)
{
	for ($i = $size - 1; @$p[$i] >= $p[$i+1]; --$i) { }

	if ($i == -1)
	{
		return false;
	}

	for ($j = $size; $p[$j] <= $p[$i]; --$j) { }

	$tmp = $p[$i];
	$p[$i] = $p[$j];
	$p[$j] = $tmp;

	for (++$i, $j = $size; $i < $j; ++$i, --$j)
	{
		$tmp = $p[$i]; $p[$i] = $p[$j]; $p[$j] = $tmp;
	}

	return $p;
}

function get_permutations($string, $delimiter)
{
	$set = explode($delimiter, $string);
	$size = count($set) - 1;
	$perm = range(0, $size);
	$j = 0;

	do {
		foreach ($perm as $i)
		{
			$perms[$j][] = $set[$i];
		}
	}
	while ($perm = pc_next_permutation($perm, $size) and ++$j);

	$list = array();
	foreach ($perms as $p)
	{
		$list[] = join(' ', $p);
	}

	return $list;
}

// Start processing
foreach (glob(dirname(__FILE__) . '/xml/*.xml') as $file)
{
	if (stripos($file, 'dictionary.xml') === false)
	{
		$xml = simplexml_load_file($file);

		foreach ($xml->class->methods->method as $method)
		{
			if (!isset($method->inherited))
			{
				$entry = $master->addChild('d:entry');
				$entry->addAttribute('id', 'id' . $i++);
				$entry->addAttribute('d:title', (string) $xml->class->name . '::' . $method->name . '()', 'http://www.apple.com/DTDs/DictionaryService-1.0.rng');

					$index = $entry->addChild('d:index');
					$index->addAttribute('d:value', (string) $method->name, 'http://www.apple.com/DTDs/DictionaryService-1.0.rng');
					$index->addAttribute('d:title', (string) $xml->class->name . '::' . $method->name . '()', 'http://www.apple.com/DTDs/DictionaryService-1.0.rng');

					$name = (strpos((string) $method->name, '__') !== false) ? (str_replace('__', '', (string) $method->name)) : (string) $method->name;
					$name .= '_' . (string) $xml->class->name;
					if (stripos((string) $xml->class->name, 'amazon') !== false)
					{
						$name .= '_' . str_replace('Amazon', '', (string) $xml->class->name);
					}

					$list = get_permutations($name, '_');

					foreach ($list as $phrase)
					{
						$index = $entry->addChild('d:index');
						$index->addAttribute('d:value', $phrase, 'http://www.apple.com/DTDs/DictionaryService-1.0.rng');
						$index->addAttribute('d:title', (string) $xml->class->name . '::' . $method->name . '()', 'http://www.apple.com/DTDs/DictionaryService-1.0.rng');
					}

					$parameters = array();
					if (isset($method->parameters->parameter))
					{
						foreach ($method->parameters->parameter as $parameter)
						{
							if (isset($parameter->defaultValue))
							{
								$param = '[$' . (string) $parameter->name . ' = ' . (string) $parameter->defaultValue . ']';
							}
							else
							{
								$param = '$' . (string) $parameter->name;
							}

							$parameters[] = $param;
						}
					}

					$extends = isset($xml->class->summary->parentClasses->class) ? (' extends ' . (string) $xml->class->summary->parentClasses->class->name) : '';
					$span = $entry->addChild('span', '&#160;' . (string) $xml->class->name . $extends, 'http://www.w3.org/1999/xhtml');
					$span->addAttribute('class', 'syntax');

					$h1 = $entry->addChild('h1', (string) $method->name . '( ' . implode(', ', $parameters) . ' )', 'http://www.w3.org/1999/xhtml');

					$h2 = $entry->addChild('h2', 'Parameters', 'http://www.w3.org/1999/xhtml');

					$h2 = $entry->addChild('h2', 'Returns', 'http://www.w3.org/1999/xhtml');

					$h2 = $entry->addChild('h2', 'Examples', 'http://www.w3.org/1999/xhtml');
					if (isset($method->examples->example))
					{
						foreach ($method->examples->example as $example)
						{
							$entry->addChild('h3', (string) $example->title, 'http://www.w3.org/1999/xhtml');
							$entry->addChild('pre', str_replace("\t", '    ', (string) $example->code), 'http://www.w3.org/1999/xhtml');
						}
					}
			}
		}
	}
}

file_put_contents(dirname(__FILE__) . '/dictionary.xml', $master->asXML());
