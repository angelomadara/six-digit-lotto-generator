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
        $isPredict = $request->predict;

        $lotto_numbers = [];

        $lotto_numbers = $this->lotto->generateThreeCombinations($isPredict);

        return view('index', [
            'lotto_numbers' => $lotto_numbers
        ]);
    }
}
