<?php

namespace App\Repository;

use App\Models\LottoResults;

class LottoResultsRepository extends RepositoryAbstract
{
    private $max_numbers = 6; // the maximum number of lotto numbers

    public function create()
    {
        $lotto_numbers = [];

        $x = 1;
        // loop until the maximum number of lotto numbers is met
        do {
            // filter if the number is already in the array
            if (!in_array($this->randomNumber(), $lotto_numbers)) {
                $lotto_numbers[] = $this->randomNumber();
                $x++;
            }
        } while ($x <= $this->max_numbers);

        sort($lotto_numbers);

        $json_string = json_encode($lotto_numbers);
        // check if the combination already exist
        if (!$this->checkIfCombinationsExist($json_string)) {
            LottoResults::create([
                'result' => $json_string
            ]);

            return $lotto_numbers;
        }
        return false;
    }

    public function update($model, array $post)
    {
    }

    public function generateThreeCombinations($isPredict)
    {
        if ($isPredict) {
            $count = 0;
            $max = 3;
            // check if the create method returns false then loop again
            // this allows us to check if the combination exist or not on the database
            do {
                $combinations = $this->create();
                if ($combinations != false) {
                    $lotto_numbers[] = $combinations;
                    $count++;
                }
            } while ($count <= $max);

            return $lotto_numbers;
        }
        return false;
    }

    private function checkIfCombinationsExist($combination)
    {
        return LottoResults::where(['result' => $combination])->first();
    }

    private function randomNumber()
    {
        return rand(1, 59);
    }
}
