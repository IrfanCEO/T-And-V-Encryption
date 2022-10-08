<?php

/**
 * 
 */
class Showing
{

	private static $ascii = array(
		32, 33, 34, 35, 36, 37, 38, 39, 40, 41,
		42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 
		52, 53, 54, 55, 56, 57, 58, 59, 60, 61,
		62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 
		72, 73, 74, 75, 76, 77, 78, 79, 80, 81, 
		82, 83, 84, 85, 86, 87, 88, 89, 90, 91, 
		92, 93, 94, 95, 96, 97, 98, 99, 100, 101, 
		102, 103, 104, 105, 106, 107, 108, 109, 
		110, 111, 112, 113, 114, 115, 116, 117, 
		118, 119, 120, 121, 122, 123, 124, 125, 
		126
	);
	
	public function __construct($text)
	{
		$this->text = $text;
		$this->pass = "r!N&@Lv3";
		$this->row = strlen($this->text)/strlen($this->pass);
		$this->col = strlen($this->pass);
		if ($this->row < 4) {
			$this->row = 4;
		} else {
			if (fmod(strlen($this->text),strlen($this->pass)) != 0) {
				$this->row = floor($this->row) + 1;
			}
		}
	}

	public function show()
	{
		$result = "";
		if ($this->scanText() > 0) {
			$keyVigenere = $this->createKeyVigenere();
			$plainVigenere = $this->createPlainVigenere();
			$resultVigenere = $this->createDecryptVigenere($plainVigenere, $keyVigenere);
			$matrixDecrypt = $this->createMatrixDecrypt($resultVigenere);
			$keyTranspos = $this->createKeyTranspos();
			$decryptTranspos = $this->createDecryptTranspos($keyTranspos, $matrixDecrypt);
			$resultTranspos = $this->resultTranspos($decryptTranspos);
			$result = $resultTranspos;
		} else {
			echo "
				<script>
					alert('Karakter harus berupa huruf, angka atau simbol.');
				</script>
			";
		}
		return $result;
	}

	private function scanText()
	{
		$jumlah = 0;
		for ($i=0; $i < strlen($this->text); $i++) { 
			if (ord($this->text[$i]) >= 32 && ord($this->text[$i]) <= 126) {
				$jumlah++;
			} else {
				$jumlah = 0;
				break;
			}
		}
		return $jumlah;
	}

	private function createKeyVigenere()
	{
		$key = $this->pass;
		$amountKey = 0;
		while (strlen($key) < strlen($this->text)) {
			$key .= $key[$amountKey];
			$amountKey++;
		}

		$keyPosition = array();
		for ($i=0; $i < strlen($key); $i++) { 
			for ($j=0; $j < count(Showing::$ascii); $j++) { 
				if (ord($key[$i]) == Showing::$ascii[$j]) {
					$keyPosition[$i] = $j;
				}
			}
		}
		return $keyPosition;
	}

	private function createPlainVigenere()
	{
		$plainPosition = array();
		for ($i=0; $i < strlen($this->text); $i++) { 
			for ($j=0; $j < count(Showing::$ascii); $j++) { 
				if (ord($this->text[$i]) == Showing::$ascii[$j]) {
					$plainPosition[$i] = $j;
				}
			}
		}
		return $plainPosition;
	}

	private function createDecryptVigenere($plainVigenere, $keyVigenere)
	{
		$result = "";
		for ($i=0; $i < count($plainVigenere); $i++) { 
			$min = $plainVigenere[$i] - $keyVigenere[$i];
			if ($min < 0) {
				$min += 95;
			}
			$result .= chr(Showing::$ascii[$min]);
		}
		return $result;
	}

	private function createMatrixDecrypt($resultVigenere)
	{
		$matrix = array();
		$hold = 0;
		for ($i=0; $i < $this->col; $i++) { 
			for ($j=0; $j < $this->row; $j++) { 
				if ($hold < strlen($resultVigenere)) {
					$matrix[$j][$i] = $resultVigenere[$hold];
					$hold++;
				} else {
					$matrix[$j][$i] = " ";
				}
			}
		}
		return $matrix;
	}

	private function createKeyTranspos()
	{
		$sortKey = array();
		for ($i=0; $i < strlen($this->pass); $i++) { 
			$sortKey[$i] = $this->pass[$i];
		}
		sort($sortKey);
		return implode("", $sortKey);
	}

	private function createDecryptTranspos($keyTranspos, $matrixDecrypt)
	{
		$decryptValue = array();
		for ($i=0; $i < $this->col; $i++) { 
			for ($j=0; $j < $this->col; $j++) { 
				if ($this->pass[$j] == $keyTranspos[$i]) {
					for ($k=0; $k < $this->row; $k++) {
						$decryptValue[$k][$j] = $matrixDecrypt[$k][$i];
					}
				}
			}
		}
		return $decryptValue;
	}

	private function resultTranspos($decryptTranspos)
	{
		$result = "";
		for ($i=0; $i < $this->row; $i++) { 
			for ($j=0; $j < $this->col; $j++) { 
				$result .= $decryptTranspos[$i][$j];
			}
		}
		return $result;
	}

}

?>