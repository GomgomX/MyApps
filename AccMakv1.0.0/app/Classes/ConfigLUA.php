<?php
namespace App\Classes;

// NOT SAFE CLASS, LUA CONFIG CAN BE EXECUTED AS PHP CODE
class ConfigLUA
{
	private $config;

	public function __construct($path = false)
	{
		if($path && strtolower(basename(getcwd()) == "public"))
			$this->loadFromFile($path);
	}

	public static function getFileContents($path)
	{
		$file = file_get_contents($path);

		if($file === false)
			throw new \RuntimeException('Cannot read from file: <b>' . htmlspecialchars($path) . '</b>');

		return $file;
	}
	
	public static function fileExists($path)
	{
		return file_exists($path);
	}
	
	public function loadFromFile($path)
	{
		if(self::fileExists($path))
		{
			$content = self::getFileContents($path);
			$this->loadFromString($content);
		}
		else
		{
			throw new \InvalidArgumentException('#C-2 LUA config file doesn\'t exist. Path: <b>' . $path . '</b>');
		}
	}

	public function loadFromString($string)
	{
		$lines = explode("\n", $string);
		if(count($lines) > 0)
			foreach($lines as $ln => $line)
			{
				$tmp_exp = explode('=', $line, 2);
				if(count($tmp_exp) >= 2)
				{
					$key = trim($tmp_exp[0]);
					if(substr($key, 0, 2) != '--')
					{
						$value = trim($tmp_exp[1]);
						if(is_numeric($value))
							$this->config[ $key ] = (float) $value;
						elseif(in_array(substr($value, 0 , 1), array("'", '"')) && in_array(substr($value, -1 , 1), array("'", '"')))
							$this->config[ $key ] = (string) substr(substr($value, 1), 0, -1);
						elseif(in_array($value, array('true', 'false')))
							$this->config[ $key ] = ($value == 'true') ? true : false;
						else
						{
							foreach($this->config as $tmp_key => $tmp_value) // load values definied by other keys, like: dailyFragsToBlackSkull = dailyFragsToRedSkull
								$value = str_replace($tmp_key, $tmp_value, $value);
							try {
                                $ret = @eval("return $value;");
                                if ((string)$ret == '') // = parser error
                                {
                                    throw new \RuntimeException(
                                        '#C-1 - Line <b>' . ($ln + 1) . '</b> of LUA config file is not valid [key: <b>' . $key . '</b>]'
                                    );
                                }
                                $this->config[$key] = $ret;
                            } catch(\ParseError $e) {
							    // skip LUA table errors
                            }
						}
					}
				}
			}
	}

	public function getValue($key)
	{
		if(isset($this->config[ $key ]))
			return $this->config[ $key ];
		else
			throw new \RuntimeException('#C-3 Config key <b>' . $key . '</b> doesn\'t exist.');
	}

	public function isSetKey($key)
	{
		return isset($this->config[ $key ]);
	}

	public function getConfig()
	{
		return $this->config;
	}
}
