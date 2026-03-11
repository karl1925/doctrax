<?php

namespace App\Http\Controllers;

use App\Models\ExternalHistory;
use Illuminate\Http\Request;

class ExternalHistoryController extends Controller
{
    public static function log($userId, $externalId, $action, $remarks = null)
    {
        ExternalHistory::create([
            'user_id'     => $userId,
            'external_id' => $externalId,
            'action'      => $action,
            'remarks'     => $remarks,
        ]);
    }
}
