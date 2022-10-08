<?php

/**
 * 
 */
class Hiding
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

	public function hide()
	{
		$result = "";
		if ($this->scanText() > 0) {
			$matrixEncrypt = $this->createMatrixEncrypt();
			$keyTranspos = $this->createKeyTranspos();
			$encryptTranspos = $this->createEncryptTranspos($keyTranspos, $matrixEncrypt);
			$resultTranspos = $this->resultTranspos($encryptTranspos);
			$keyVigenere = $this->createKeyVigenere($resultTranspos);
			$plainVigenere = $this->createPlainVigenere($resultTranspos);
			$resultVigenere = $this->createEncryptVigenere($plainVigenere, $keyVigenere);
			$result = $resultVigenere;
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

	private function createMatrixEncrypt()
	{
		$matrix = array();
		$hold = 0;
		for ($i=0; $i < $this->row; $i++) { 
			for ($j=0; $j < $this->col; $j++) { 
				if ($hold < strlen($this->text)) {
					$matrix[$i][$j] = $this->text[$hold];
					$hold++;
				} else {
					$matrix[$i][$j] = " ";
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

	private function createEncryptTranspos($keyTranspos, $matrixEncrypt)
	{
		$encryptValue = array();
		for ($i=0; $i < $this->col; $i++) { 
			for ($j=0; $j < $this->col; $j++) { 
				if ($this->pass[$i] == $keyTranspos[$j]) {
					for ($k=0; $k < $this->row; $k++) {
						$encryptValue[$k][$j] = $matrixEncrypt[$k][$i];
					}
				}
			}
		}
		return $encryptValue;
	}

	private function resultTranspos($encryptTranspos)
	{
		$result = "";
		for ($i=0; $i < $this->col; $i++) { 
			for ($j=0; $j < $this->row; $j++) { 
				$result .= $encryptTranspos[$j][$i];
			}
		}
		return $result;
	}

	private function createKeyVigenere($resultTranspos)
	{
		$amountKey = 0;
		while (strlen($this->pass) < strlen($resultTranspos)) {
			$this->pass .= $this->pass[$amountKey];
			$amountKey++;
		}

		$keyPosition = array();
		for ($i=0; $i < strlen($this->pass); $i++) { 
			for ($j=0; $j < count(Hiding::$ascii); $j++) { 
				if (ord($this->pass[$i]) == Hiding::$ascii[$j]) {
					$keyPosition[$i] = $j;
				}
			}
		}
		return $keyPosition;
	}

	private function createPlainVigenere($resultTranspos)
	{
		$plainPosition = array();
		for ($i=0; $i < strlen($resultTranspos); $i++) { 
			for ($j=0; $j < count(Hiding::$ascii); $j++) { 
				if (ord($resultTranspos[$i]) == Hiding::$ascii[$j]) {
					$plainPosition[$i] = $j;
				}
			}
		}
		return $plainPosition;
	}

	private function createEncryptVigenere($plainVigenere, $keyVigenere)
	{
		$result = "";
		for ($i=0; $i < count($plainVigenere); $i++) { 
			$sum = $plainVigenere[$i] + $keyVigenere[$i];
			$mod = $sum % 95;
			$result .= chr(Hiding::$ascii[$mod]);
		}
		return $result;
	}

}

?>