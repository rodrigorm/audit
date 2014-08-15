<?php

class Calculatrice
{
	public function somme($a, $b)
	{
		$this->checkParams($a, $b);
		sleep(2);
		return $a+$b;
	}

	public function soustraction($a, $b)
	{
		$this->checkParams($a, $b);
		sleep(5);
		return $a-$b;
	}

	public function division($a, $b)
	{
		$this->checkParams($a, $b);
		sleep(8);
		if ($b === 0) {
			throw new RuntimeException('Division by 0');
		}
		return $a/$b;
	}

	public function calculDivers($a, $b)
	{
		return $this->division($this->soustraction($a, $b), $this->somme($a, $b));
	}

	public function checkParams()
	{
		foreach (func_get_args() as $arg) {
			if (is_numeric($arg) === false) {
				throw new RuntimeException('NaN');
			}
		}
	}
}

$calculator = new Calculatrice();
echo $calculator->calculDivers(5, 10);
