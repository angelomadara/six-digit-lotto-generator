<?php

namespace App\Repository;

use App\Models\LottoResults;

class LottoResultsRepository extends RepositoryAbstract
{
    private $max_numbers = 6; // the maximum number of lotto numbers

    public function create($data)
    {
        LottoResults::insert($data);
    }

    public function update($model, array $post)
    {
    }

    public function createCombination()
    {
        $lotto_number = [];

        $x = 1;
        // loop until the maximum number of lotto numbers is met
        do {
            $new_number = $this->randomNumber();
            // filter if the number is already in the array
            if (!in_array($new_number, $lotto_number)) {
                $lotto_number[] = $new_number;
                ++$x;
            }
        } while ($x <= $this->max_numbers);

        sort($lotto_number);

        return $lotto_number;
    }

    public function generateThreeCombinations($isPredict)
    {
        if ($isPredict) {
            $count = 1;
            $max = 3;
            $lotto_numbers = [];
            // check if the create method returns false then loop again
            // this allows us to check if the combination exist or not on the database
            do {
                $combinations = $this->createCombination();
                if ($combinations != false) {
                    $lotto_numbers[] = [
                        'combination' => $combinations,
                    ];
                    ++$count;
                }
            } while ($count <= $max);

            return $lotto_numbers;
        }
        return false;
    }

    public function checkIfCombinationsExist($combinations)
    {
        $data = [];
        $save = true;
        $saveData = [];

        // check if the combination exist in the database
        foreach ($combinations as $combination) {
            $array_combination = str_replace(['"', "\\"], '', json_encode(arrayCombination($combination)));
            $doesExist = LottoResults::where(['result' => $array_combination])->first();

            $data[] = [
                'combination' => arrayCombination($combination),
                'is_selected_before' => $doesExist ? true : false,
                'date_selected' => $doesExist ? $doesExist->created_at : null
            ];
            $saveData[] = ['result' => $array_combination, 'created_at' => date("Y-m-d h:i:s"), 'updated_at' => date("Y-m-d h:i:s")];
            if ($doesExist) {
                $save = false;
                break;
            }
        }

        // check if the combination has the same combination in the array
        $check_array_values = array_unique($combinations);
        if (count($check_array_values) <= 2) {
            $save = false;
        }

        if ($save) {
            $this->create($saveData);
        }

        return $data;
    }

    private function randomNumber()
    {
        $random = mt_rand(1, 59);
        // if ($random > 29) {
        //     $random = rand($random, 59);
        // } else {
        //     $random = rand(1, $random);
        // }
        $random = sprintf("%02d", $random);
        return $random;
    }
}
