<?php

namespace App\Http\Controllers;

use App\Repository\LottoResultsRepository;
use Illuminate\Http\Request;

class GenerateController extends Controller
{
    public function __construct(public LottoResultsRepository $lotto)
    {
    }

    public function index(Request $request)
    {
        $isCheckCombinations = $request->checkCombinations;

        $lotto_numbers = [];

        if ($isCheckCombinations) {
            // this has to be validated check if the combinations contains only numbers and dont have any random characters
            $lotto_numbers = $this->lotto->checkIfCombinationsExist($request->only('combination_0', 'combination_1', 'combination_2'));
        } else {
            $lotto_numbers = $this->lotto->generateThreeCombinations(true);
        }
        // return $lotto_numbers;

        return view('index', [
            'lotto_numbers' => $lotto_numbers
        ]);
    }
}
