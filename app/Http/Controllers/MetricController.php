<?php

namespace App\Http\Controllers;

use App\PeerLog;
use Illuminate\Http\Request;

class MetricController extends Controller
{
    public function getPeerMetrics()
    {
        return response()->json(PeerLog::all());
    }
}
