<?php

namespace App\Http\Controllers;

use App\Models\InviteCode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InviteCodeController extends Controller
{
    /**
     * Generate Invite Code
     */
    public function generate()
    {
        do {
            $code = strtoupper(\Illuminate\Support\Str::random(8));
        } while (\App\Models\InviteCode::where('code', $code)->exists());

        $invite = \App\Models\InviteCode::create([
            'code' => $code,
            'is_used' => false,
        ]);

        return back()->with('generated_code', $invite->code);
    }

    /**
     * Verify Invite Code
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required'
        ]);

        $invite = InviteCode::where('code', $request->code)->first();

        if (!$invite) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid invite code'
            ], 400);
        }

        if ($invite->is_used) {
            return response()->json([
                'success' => false,
                'message' => 'Invite code already used'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Invite code is valid'
        ]);
    }

    /**
     * Mark Invite Code as Used
     */
    public function useCode(Request $request)
    {
        $request->validate([
            'code' => 'required'
        ]);

        $invite = InviteCode::where('code', $request->code)->first();

        if (!$invite) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid invite code'
            ], 400);
        }

        if ($invite->is_used) {
            return response()->json([
                'success' => false,
                'message' => 'Invite code already used'
            ], 400);
        }

        $invite->update([
            'is_used' => true,
            'used_at' => now(),
            'used_by' => auth()->id()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Invite code marked as used'
        ]);
    }
}