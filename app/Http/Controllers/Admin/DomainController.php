<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Domain;
use App\Models\notification;
use Carbon\Carbon;

class DomainController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:xem domain')->only(['index', 'show']);
        $this->middleware('can:thêm domain')->only(['create', 'store']);
        $this->middleware('can:sửa domain')->only(['edit', 'update']);
        $this->middleware('can:xóa domain')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $domains = Domain::where('user_id', auth()->id())->paginate(10);

        // Kiểm tra domain sắp hết hạn
        foreach ($domains as $domain) {
            $this->checkExpiry($domain);
        }

        return view('admin.domains.index', compact('domains'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.domains.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'domain_name' => 'required|string|max:255|unique:domains',
            'registrar' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'expiry_date' => 'required|date',
            'status' => 'required|string|in:active,inactive',
        ]);

        $domain = Domain::create([
            'user_id' => auth()->id(),
            'domain_name' => $request->domain_name,
            'registrar' => $request->registrar,
            'start_date' => $request->start_date,
            'expiry_date' => $request->expiry_date,
            'status' => $request->status,
        ]);

        // Kiểm tra ngay sau khi tạo
        $this->checkExpiry($domain);

        return redirect()->route('domains.index')->with('success', 'Domain đã được thêm.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $domain = Domain::where('user_id', auth()->id())->findOrFail($id);
        $this->checkExpiry($domain);
        return view('admin.domains.show', compact('domain'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $domain = Domain::where('user_id', auth()->id())->findOrFail($id);
        return view('admin.domains.edit', compact('domain'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $domain = Domain::where('user_id', auth()->id())->findOrFail($id);

        $request->validate([
            'domain_name' => 'required|string|max:255|unique:domains,domain_name,' . $id,
            'registrar' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'expiry_date' => 'required|date',
            'status' => 'required|string|in:active,inactive',
        ]);

        $domain->update($request->all());

        // Kiểm tra sau khi cập nhật
        $this->checkExpiry($domain);

        return redirect()->route('domains.index')->with('success', 'Domain đã được cập nhật.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $domain = Domain::where('user_id', auth()->id())->findOrFail($id);
        $domain->delete();

        return redirect()->route('domains.index')->with('success', 'Domain đã bị xóa.');
    }

    public function search(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $query = Domain::where('user_id', auth()->id());

        if ($request->filled('start_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $query->where('expiry_date', '>=', $startDate);
        }

        if ($request->filled('end_date')) {
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->where('expiry_date', '<=', $endDate);
        }

        $domains = $query->paginate(10);
        $domains->appends($request->query());

        return view('admin.domains.index', compact('domains'));
    }


    /**
     * Kiểm tra domain sắp hết hạn và tạo thông báo
     */
    protected function checkExpiry(Domain $domain)
    {
        if (!$domain->expiry_date || $domain->status === 'inactive') {
            return;
        }

        $expiryDate = Carbon::parse($domain->expiry_date);
        $today = Carbon::today();
        $daysUntilExpiry = $today->diffInDays($expiryDate, false);

        // Nếu còn 15 ngày hoặc ít hơn
        if ($daysUntilExpiry <= 15 && $daysUntilExpiry >= 0) {
            $notificationMessage = "Domain {$domain->domain_name} sẽ hết hạn vào {$domain->expiry_date} (còn {$daysUntilExpiry} ngày).";

            // Kiểm tra xem thông báo đã tồn tại chưa
            $existingNotification = notification::where('user_id', auth()->id())
                ->where('title', 'Cảnh báo hết hạn domain')
                ->where('message', $notificationMessage)
                ->where('is_read', false)
                ->first();

            if (!$existingNotification) {
                notification::create([
                    'user_id' => auth()->id(),
                    'title' => 'Cảnh báo hết hạn domain',
                    'message' => $notificationMessage,
                    'is_read' => false,
                ]);
            }
        }
    }
}