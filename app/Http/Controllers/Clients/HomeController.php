<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Domain;
use App\Models\Hosting;
use App\Models\Server;
use App\Models\Notification;


class HomeController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $domains = Domain::where('user_id', $userId)->latest()->take(5)->get();
        $hostings = Hosting::where('user_id', $userId)->latest()->take(5)->get();
        $servers = Server::where('user_id', $userId)->latest()->take(5)->get();

        $notifications = Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->latest()
            ->take(5)
            ->get();

        return view('home.index', compact(
            'domains',
            'hostings',
            'servers',
            'notifications'
        ));
    }
}