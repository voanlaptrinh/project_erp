<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Domain;
use App\Models\notification;
use Carbon\Carbon;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;

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

        Log::create([
            'message' => Auth::user()->name . 'vừa xem danh sách domain',
        ]);

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
        ], [
            'domain_name.required' => 'Vui lòng nhập tên domain.',
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

        // Thêm thông báo cho người tạo
        // Notification::create([
        //     'user_id' => auth()->id(),
        //     'title' => 'Domain mới đã được thêm',
        //     'message' => auth()->user()->name . 'vừa thêm domain mới"' . $domain->domain_name . '" với ngày hết hạn ' . $domain->expiry_date . '.',
        //     'is_read' => false
        // ]);

        // Ghi log hệ thống
        Log::create([
            'message' => auth()->user()->name . 'vừa thêm domain mới"' . $domain->domain_name . '" với ngày hết hạn ' . $domain->expiry_date . '.',
        ]);

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
            'status' => 'required|string|in:active,inactive,pending,expired',
        ], [
            'domain_name.required' => 'Vui lòng nhập tên domain.',
        ]);

        $domain->update($request->all());

        // Thêm thông báo cho người tạo
        // Notification::create([
        //     'user_id' => auth()->id(),
        //     'title' => 'Domain ' . $domain->domain_name . ' đã được cập nhật',
        //     'message' => auth()->user()->name . 'vừa sửa domain"' . $domain->domain_name . '.',
        //     'is_read' => false
        // ]);

        // Ghi log hệ thống
        Log::create([
            'message' => auth()->user()->name . 'vừa cập nhật"' . $domain->domain_name . '.',
        ]);

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

        // Thêm thông báo cho người tạo
        // Notification::create([
        //     'user_id' => auth()->id(),
        //     'title' => 'Domain ' . $domain->domain_name . ' đã bị xóa',
        //     'message' => auth()->user()->name . 'vừa xóa domain"' . $domain->domain_name . '.',
        //     'is_read' => false
        // ]);

        // Ghi log hệ thống
        Log::create([
            'message' => auth()->user()->name . 'vừa xóa"' . $domain->domain_name . '.',
        ]);
        return redirect()->route('domains.index')->with('success', 'Domain đã bị xóa.');
    }

    public function search(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:active,inactive',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $query = Domain::where('user_id', auth()->id());

        if ($request->filled('search')) {
            $query->where('domain_name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $query->where('expiry_date', '>=', $startDate);
        }

        if ($request->filled('end_date')) {
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->where('expiry_date', '<=', $endDate);
        }

        $domains = $query->orderBy('expiry_date', 'asc')->paginate(10);
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