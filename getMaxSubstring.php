<?php

class GetMaxSubstring {

    private $time = 0;
    private $stringsAmount = 0;
    private $string = null;
    private $strings = array();

    private $subStrings = array();
    private $maxMatchLen = 0;
    private $maxMatchCount = 0;
    private $maxMatchString = "";


    public function get($string) {
        $this->time = microtime();
        $this->string = $string;
        // Получаем кол-во строк
        $this->stringsAmount = substr(trim($this->string), 0, 1);
        // Вырезаем цифру в начале
        $this->string = trim(substr($this->string, 1));
        // Разбиваем текст на строки
        $this->strings = explode("\n", $this->string);
        $this->parseSubstrings();
        $this->time = microtime() - $this->time;
    }

    /**
     * Основной алгоритм поиска
     */
    private function parseSubstrings() {
        for($i = 0; $i < $this->stringsAmount; $i++) {
            $string = trim($this->strings[$i]);
            $stringLen = strlen($string);
            /*
             * Разбиваем строку на группы подстрок по кол-ву символов,
             * этим самым сумма всех значений в массиве будет равна
             * макс. кол-ву возможных подстрок в текущей строке
             * включая совпадения
             */
            $subsGroups = array_reverse(range(1, $stringLen));

            $limit = 0;
            foreach($subsGroups as $group) {
                // Кол-во символов в подстроке
                $limit += 1;
                // Позиция с которой начинается подстрока
                $offset = 0;
                for($ii = 0; $ii < $group; $ii++) {
                    // Получаем подстроку учитывая позицию и лимит
                    $sub = substr($string, $offset, $limit);
                    // Если подстрока новая, то продолжаем
                    if(!array_key_exists($sub, $this->subStrings)) {
                        // Вычитываем кол-во совпадений подстроки в строках
                        $this->subStrings[$sub] = $this->countSubstrings($sub);
                        $subLen = strlen($sub);
                        $subMatch = $this->subStrings[$sub];
                        // Проверяем выше ли текущая подстрока по релевантности
                        // и если да, то считаем ее наиболее общей
                        if(($subLen > $this->maxMatchLen && $subMatch >= $this->maxMatchCount)
                                || ($subLen == $this->maxMatchLen && $subMatch > $this->maxMatchCount)) {
                            $this->maxMatchLen = $subLen;
                            $this->maxMatchCount = $subMatch;
                            $this->maxMatchString = $sub;
                        }
                    }
                    $offset += 1;
                }
            }
        }
    }

    /**
     * Возвращает кол-во совпадений подстроки в строках
     *
     * @param string $sub подстрока
     * @return int кол-во совпадений
     */
    private function countSubstrings($sub) {
        $count = 0;
        foreach($this->strings as $string) {
            if(strpos($string, $sub) !== false) $count += 1;
        }
        return $count;
    }

    /**
     * Возвращает наибольшую общую подстроку и кол-во совпадений
     *
     * @return string
     */
    public function getResult() {
        return '"' . $this->maxMatchString . '" ' . $this->maxMatchCount . " matches";
    }

    /**
     * Возвращает время выполнения алгоритма в секундах
     *
     * @return string
     */
    public function getTime() {
        return $this->time . "s";
    }

}

#### Example ####

$string = <<<STR
3
abacaba
mycabarchive
acabistrue

STR;

$fs = new GetMaxSubstring;
$fs->get($string);

echo "Time: " . $fs->getTime() . "\n";
echo "Result: " . $fs->getResult();

?>
