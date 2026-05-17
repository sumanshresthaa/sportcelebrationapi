<?php

namespace App\Http\Controllers;

use App\Models\Point;
use App\Models\User;
use Illuminate\Http\Request;

class PointController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'points'    => ['required', 'integer', 'min:0', 'max:100000'],
            'source'    => ['nullable', 'string', 'max:50'],
            'trivia_id' => ['nullable', 'string', 'max:100'],
        ]);

        // 1️⃣ Create history row (append-only)
        $point = Point::create([
            'user_id'   => $request->user()->id,
            'points'    => $data['points'],
            'source'    => $data['source'] ?? 'trivia',
            'trivia_id' => $data['trivia_id'] ?? null,
        ]);

        // 2️⃣ Update user's total points
        $request->user()->increment('total_points', $data['points']);

        return response()->json([
            'message'       => 'Points saved.',
            'points_added'  => $data['points'],
            'total_points' => $request->user()->fresh()->total_points,
            'history'       => $point,
        ], 201);
    }

    // optional - see point history
    public function index(Request $request)
    {
        return Point::where('user_id', $request->user()->id)
            ->latest()
            ->paginate(20);
    }

    public function globalRank(Request $request)
    {
        $perPage = min((int) $request->query('per_page', 20), 100);

        $users = User::select([
                'id',
                'first_name',
                'last_name',
                'nickname',
                'country_of_origin',
                'support_country',
                'total_points',
            ])
            ->orderByDesc('total_points')
            ->orderBy('id')
            ->paginate($perPage);

        $rankStart = ($users->currentPage() - 1) * $users->perPage();

        $users->getCollection()->transform(function ($user, $index) use ($rankStart) {
            $user->rank = $rankStart + $index + 1;

            return $user;
        });

        return response()->json($users);
    }
}
