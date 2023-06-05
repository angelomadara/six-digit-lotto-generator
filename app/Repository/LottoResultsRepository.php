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
                        'is_selected_before' => NULL,
                        'date_selected' => NULL,
                        'duplicate_in_combination' => false
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
        $saveData = [];

        // check if the combination has the same combination in the array
        $combinations = array_unique($combinations);

        // check if the combination exist in the database
        foreach ($combinations as $combination) {
            $save = true;
            $duplicate_in_combination = false;
            $message = "";

            $array_combination = str_replace(['"', "\\"], '', json_encode(arrayCombination($combination)));
            // \Log::info($array_combination);
            $doesExist = LottoResults::where(['result' => $array_combination])->first();

            if ($doesExist) {
                // \Log::info($doesExist);
                $message = "This combination(s) has been selected before - " . date("d/M/Y", strtotime($doesExist->created_at));
            } else {
                $message = "This combination is new - " . date("d/M/Y");
            }

            $_arr_combination = explode(" - ", $combination); // tranform the string into a array
            $unique_combinations = array_unique($_arr_combination);
            \Log::info(['duplicate', count($_arr_combination), $unique_combinations]);
            // check if there is a duplicate number in the combination
            if (count($unique_combinations) < 6) {

                $save = false;
                $duplicate_in_combination = true;
                $message = "There is a duplicate number in this combination. ";
            }

            $data[] = [
                'combination' => arrayCombination($combination),
                'is_selected_before' => $doesExist ? true : false,
                'duplicate_in_combination' => $duplicate_in_combination,
                'date_selected' => $doesExist ? $doesExist->created_at : null,
                'message' => $message,
            ];

            if (!$doesExist && $duplicate_in_combination == false) {
                $saveData[] = ['result' => $array_combination, 'created_at' => date("Y-m-d h:i:s"), 'updated_at' => date("Y-m-d h:i:s")];
            }
        }
        // \Log::info([
        //     'save' => $saveData
        // ]);

        // if ($save) {
        $this->create($saveData);
        // }

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
