<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css"></script>
    </head>
    <body class="antialiased">

        <div class="container">
            <div class="block">
                <form action="/" method="get" id="predict-form" style="position: relative;height:100px;">
                    {{-- <input type="hidden" name="predict" value="true"> --}}
                    <button type="submit" class="btn btn-primary position-absolute top-50 start-50 translate-middle">
                        Generate new combinations
                    </button>
                </form>
            </div>
            <div class="block">
                <form action="/" class="container">

                    @forelse ($lotto_numbers as $key => $numbers)
                    <div class="row justify-content-center">
                        <div class="col-6 mb-4">
                            <input type="{{ isset($numbers['is_selected_before']) ? 'hidden' : 'text' }}" name="{{ "combination_".$key }}" value="{{ readableCombination($numbers['combination']) }}" class="form-control">

                            @if($numbers['is_selected_before'] == true)
                                <h3 class="">
                                    {{ readableCombination($numbers['combination']) }}
                                </h3>
                                <div class="alert alert-danger" role="alert">
                                    &#9746; this combination(s) has been selected before ({{ date("d/M/Y",strtotime($numbers['date_selected'])) }})
                                </div>
                            @elseif($numbers['is_selected_before'] == false)
                                <h3 class="">
                                    {{ readableCombination($numbers['combination']) }}
                                </h3>
                                <div class="alert alert-success" role="alert">
                                    &#9745; This combination is new - {{ date("d/M/Y") }}
                                </div>
                            @endif
                        </div>
                    </div>
                    @empty
                    @endforelse

                    <div style="position:relative;height:50px;">
                        <input type="hidden" name="checkCombinations" value="true">
                        <button type="submit" class="btn btn-primary position-absolute top-50 start-50 translate-middle">Submit and check combinations</button>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>
