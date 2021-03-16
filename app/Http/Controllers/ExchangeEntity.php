<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\User;
use App\Entity;
use App\Rate;

class ExchangeEntity extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

        $validation = Validator::make($requestData, [
            'currencyCode' => 'required|max:3',
            'value' => 'required|numeric',
            'recvAmount' => 'required|numeric',
            'FinalValue' => 'required',
            'user' => 'required',
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors());
        }

        preg_match_all('/\d+\.?\d+/', $requestData['FinalValue'], $matches);
        if (
            count($matches, true) !== 3 || 
            ($matches[0][0] != $requestData['recvAmount'] || $matches[0][1] != $requestData['value'])
        ) {
            return response()->json(['status'=> false, 'message'=>'Invalid the final value field']);
        }

        $user = User::where('name', $requestData['user'])->first();

        if (empty($user)) {
            return response()->json(['status'=> false, 'message'=>'Sorry, user not found, please create user or contact the administrator']);
        }
        
        $rate = Rate::where('currency_code', $requestData['currencyCode'])
            ->where('value', $requestData['value'])
            ->first()
            ->toArray();

        if (empty($rate)) {
            return response()->json(['status'=> false, 'message'=>'Sorry, but no rate were found, please update your rates or contact the administrator']);
        }

        $entityStatus = Entity::create([
            'id_user'     => $user['id'],
            'id_rate'     => $rate['id'],
            'recv_amount' => $requestData['recvAmount'],
            'final_value' => $requestData['FinalValue'],
        ]);

        dd($requestData, $rate, $entityStatus);
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
