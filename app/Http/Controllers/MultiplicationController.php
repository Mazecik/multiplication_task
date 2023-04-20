<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MultiplicationCache;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Request;

class MultiplicationController extends Controller
{
    public function index(Request $request) {
        if($request->size === null) {
            return view('welcome');
        }
        $limit = $request->size;
        $model = new MultiplicationCache;
        try {
            $res = $model->getMultiplication($limit);
        } catch (ValidationException $e) {
            return redirect()->route('index')->withErrors($e->getMessage());
        }
        return view('welcome', ['res' => $res, 'size' => $limit]);
    }
}
