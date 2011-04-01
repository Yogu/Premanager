<?php

class Dwoo_Processor_compress extends Dwoo_Processor {
	public function process($input) {
		/*return preg_replace_callback('/\?\>(?P<content>.*)\<\?php/',
			function($matches) {
				return '?>'.toLower($matches['content']).'<?php';
			}, $input);*/
			
		/*return preg_replace('/([><"/])\s([><"/])/', '$1$2',
			preg_replace('/[\s]+/', ' ',
			$input));*/    
			
		if (strpos($input, '<?php') !== false)
			return $input;
		else
			return preg_replace('/([^a-zA-Z0-9_()])\s([^a-zA-Z0-9_()])/', '$1$2',
				preg_replace('/[\s]+/', ' ',
				$input));
	}
}

?>
