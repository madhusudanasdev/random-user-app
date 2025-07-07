<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'gender' => 'nullable|in:male,female',
            'page' => 'nullable|integer|min:1',
        ]);
        $gender = $request->query('gender');
        $page = $request->query('page', 1);

        $cacheKey = "users.page{$page}.gender{$gender}";

        $users = Cache::remember($cacheKey, 600, function () use ($gender) {
            $response = Http::get('https://randomuser.me/api/', [
                'results' => 50,
            ]);

            if ($response->failed()) {
                return null;
            }

            $data = $response->json();

            $users = collect($data['results']);

            if ($gender) {
                $users = $users->where('gender', $gender);
            }

            return $users->values();
        });

        if ($users === null) {
            return view('users', ['error' => 'Unable to fetch user data.']);
        }

        $paginated = $users->forPage($page, 10);
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginated,
            $users->count(),
            10,
            $page,
            ['path' => url('/users'), 'query' => $request->query()]
        );

        return view('users', ['users' => $paginator]);
    }

    public function export(Request $request)
    {
        $validated = $request->validate([
            'gender' => 'nullable|in:male,female',
            'page' => 'nullable|integer|min:1',
        ]);
        $gender = $request->query('gender');
        $page = $request->query('page', 1);
        $cacheKey = "users.page{$page}.gender{$gender}";

        $users = Cache::get($cacheKey);

        if (!$users) {
            return redirect('/users')->with('error', 'Nothing to export.');
        }

        $csvHeaders = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=users.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($users) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Name', 'Email', 'Gender', 'Nationality']);

            foreach ($users as $user) {
                fputcsv($handle, [
                    $user['name']['first'] . ' ' . $user['name']['last'],
                    $user['email'],
                    ucfirst($user['gender']),
                    $user['nat'],
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $csvHeaders);
    }

}
