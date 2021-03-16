<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

use App\Rate;

class ExchangeRate extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getData(Request $request) {
        $validation = Validator::make($request->all(), [
            'date' => 'date_format:"d.m.Y"',
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors());
        }

        $date = $request->input('date');
        if (empty($date)) {
            $date = Carbon::today();
        } else {
            $date = Carbon::create($date);
        }

        $rows = Rate::whereDate('date', '=', $date->toDateString())->get();

        $output = array_map(function ($item) {
            return [
                'currencyCode'  => $item['currency_code'],
                'ammount'       => $item['ammount'],
                'value'         => round($item['value'], 2),
                'dateRate'      => date('Y-m-d', strtotime($item['date'])),
            ];
        }, $rows->toArray());
        return response()->json($output);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $requestData = json_decode($request->getContent(), true);
        if (empty($requestData)) {
            return response()->json(['status' => false, 'message' => 'Error data format!']);
        }

        if (count($requestData) == count($requestData, COUNT_RECURSIVE)) {
            $requestData = [$requestData]; 
        }

        $validation = Validator::make($requestData, [
            '*.currencyCode' => 'required|max:3',
            '*.ammount' => 'required|integer',
            '*.value' => 'required|numeric',
            '*.date' => 'date_format:"Y-m-d"',
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors());
        }

        $carbonDateTime = Carbon::now();
        $currentDate = $carbonDateTime->toDateString();
        $currentDateTime = $carbonDateTime->toDateTimeString();

        $dataInsert = array_map(function ($item) use ($currentDate, $currentDateTime) {
            return [
                'currency_code' => $item['currencyCode'],
                'ammount'       => $item['ammount'],
                'value'         => $item['value'],
                'date'          => !empty($item['date']) ? $item['date'] : $currentDate,
                'created_at'    => $currentDateTime
            ];
        }, $requestData);

        try {
            Rate::insert($dataInsert);
        } catch (\Illuminate\Database\QueryException $ex) {
            // dd($ex->getMessage());
            return response()->json(['status' => false, 'message' => 'Error on insert data!']);
        }
 
        return response()->json($dataInsert);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
