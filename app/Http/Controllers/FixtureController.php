<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Fixture;

class FixtureController extends Controller
{
    public function validateSM($code)
    {
        return $this->validateColumn("sm", $code);
    }

    public function validateRM($code)
    {
        return $this->validateColumn("rm", $code);
    }

    public function validateFM($code)
    {
        return $this->validateColumn("fm", $code);
    }

    public function validateColumn($column, $code)
    {
        if (Fixture::where($column, '=', $code)->exists()) {
            return response()->json([
                'data' => 'This code has already been scanned.'
            ]);
        } else {
            return response()->json([
                'data' => ''
            ]);
        }
    }
}
