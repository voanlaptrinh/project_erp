<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hosting;
use App\Models\Domain;
use Carbon\Carbon;
use App\Models\Notification;
use App\Models\Log;

class HostingController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:xem hosting')->only(['index', 'show']);
        $this->middleware('can:thêm hosting')->only(['create', 'store']);
        $this->middleware('can:sửa hosting')->only(['edit', 'update']);
        $this->middleware('can:xóa hosting')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Hosting::with(['domain', 'user'])->where('user_id', auth()->id());
        if ($request->has('idDomain') && is_numeric($request->idDomain)) {
            $query->where('domain_id', $request->idDomain);
        }

        // Thêm chức năng tìm kiếm
        if ($request->filled('search')) {
            $query->where('service_name', 'like', '%' . $request->search . '%');
        }

        // Thêm chức năng lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Thêm chức năng lọc theo ngày hết hạn
        if ($request->filled('expiry_date_from')) {
            $query->where('expiry_date', '>=', $request->expiry_date_from);
        }
        if ($request->filled('expiry_date_to')) {
            $query->where('expiry_date', '<=', $request->expiry_date_to);
        }

        $hostings = $query->orderBy('expiry_date', 'asc')->paginate(10);

        // Ghi log hệ thống
        Log::create([
            'message' => auth()->user()->name . 'đã xem danh sách hosting"' . '.',
        ]);

        return view('admin.hosting.index', compact('hostings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $domains = Domain::where('user_id', auth()->id())->get();
        return view('admin.hosting.create', compact('domains'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'service_name' => 'required|string|max:255',
            'domain_id' => 'required|exists:domains,id',
            'provider' => 'required|string|max:255',
            'package' => 'required|string|max:255',
            'ip_address' => 'nullable|ip',
            'start_date' => 'required|date',
            'expiry_date' => 'required|date|after:start_date',
            'control_panel' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive,expired,suspended',
        ], [
            'service_name.required' => 'Vui lòng nhập tên hosting.',
            'domain_id.required' => 'Vui lòng chọn domain.',
            'provider.required' => 'Vui lòng chọn provider.',
            'package.required' => 'Vui lòng chọn gói hosting.',
            'start_date.required' => 'Vui lòng nhập ngày bằn giao.',
            'expiry_date.required' => 'Vui gezocht ngày hết hạn.',
            'expiry_date.after' => 'Ngày hết hạn phải lớn hơn ngày bằn giao.',
            'status.required' => 'Vui lòng chọn trạng thái.',
        ]);

        $hosting =  Hosting::create([
            'user_id' => auth()->id(),
            'service_name' => $request->service_name,
            'domain_id' => $request->domain_id,
            'provider' => $request->provider,
            'package' => $request->package,
            'ip_address' => $request->ip_address,
            'start_date' => $request->start_date,
            'expiry_date' => $request->expiry_date,
            'control_panel' => $request->control_panel,
            'status' => $request->status,
        ]);

        // Ghi log hệ thống
        Log::create([
            'message' => auth()->user()->name . 'đã tạo hosting"' . $hosting->service_name . '.',
        ]);
        // Gọi hàm này sau khi tạo để kiểm tra hosting sắp hết hạn
        $this->checkExpiry($hosting);

        return redirect()->route('hostings.index')->with('success', 'Hosting đã được thêm thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $hosting = Hosting::where('user_id', auth()->id())->findOrFail($id);
        return view('admin.hosting.show', compact('hosting'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $hosting = Hosting::where('user_id', auth()->id())->findOrFail($id);
        $domains = Domain::where('user_id', auth()->id())->get();
        return view('admin.hosting.edit', compact('hosting', 'domains'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $hosting = Hosting::where('user_id', auth()->id())->findOrFail($id);

        $request->validate([
            'service_name' => 'required|string|max:255',
            'domain_id' => 'required|exists:domains,id',
            'provider' => 'required|string|max:255',
            'package' => 'required|string|max:255',
            'ip_address' => 'nullable|ip',
            'start_date' => 'required|date',
            'expiry_date' => 'required|date|after:start_date',
            'control_panel' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive,expired,suspended',
        ], [
            'service_name.required' => 'Vui lòng nhập tên hosting.',
            'domain_id.required' => 'Vui lòng chọn domain.',
            'provider.required' => 'Vui lòng chọn provider.',
            'package.required' => 'Vui lòng chọn gói hosting.',
            'start_date.required' => 'Vui lòng nhập ngày bằn giao.',
            'expiry_date.required' => 'Vui lòng chọn ngày hết hạn.',
            'expiry_date.after' => 'Ngày hết hạn phải lớn hơn ngày bằn giao.',
            'status.required' => 'Vui lòng chọn trạng thái.',
        ]);

        $hosting->update($request->all());

        // Ghi log hệ thống
        Log::create([
            'message' => auth()->user()->name . 'đã cập nhật hosting"' . $hosting->service_name . '.',
        ]);

        // Gọi hàm này sau khi cập nhật để kiểm tra ngày hết hạn
        $this->checkExpiry($hosting);

        return redirect()->route('hostings.index')->with('success', 'Hosting đã được cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $hosting = Hosting::where('user_id', auth()->id())->findOrFail($id);
        $hosting->delete();

        // Ghi log hệ thống
        Log::create([
            'message' => auth()->user()->name . 'đã xóa hosting"' . $hosting->service_name . '.',
        ]);

        return redirect()->route('hostings.index')->with('success', 'Hosting đã được xóa thành công.');
    }

    /**
     * Kiểm tra hosting sắp hết hạn
     */
    protected function checkExpiry(Hosting $hosting)
    {
        if (!$hosting->expiry_date || $hosting->status === 'inactive') {
            return;
        }

        $expiryDate = Carbon::parse($hosting->expiry_date);
        $today = Carbon::today();
        $daysUntilExpiry = $today->diffInDays($expiryDate, false);

        if ($daysUntilExpiry <= 15 && $daysUntilExpiry >= 0) {
            // Tạo thông báo nếu hosting sắp hết hạn
            $notificationMessage = "Hosting {$hosting->service_name} sẽ hết hạn vào {$hosting->expiry_date} (còn {$daysUntilExpiry} ngày).";

            // Kiểm tra xem thông báo đã tồn tại chưa
            $existingNotification = Notification::where('user_id', auth()->id())
                ->where('title', 'Cảnh báo hết hạn hosting')
                ->where('message', $notificationMessage)
                ->where('is_read', false)
                ->first();

            if (!$existingNotification) {
                Notification::create([
                    'user_id' => auth()->id(),
                    'title' => 'Cảnh báo hết hạn hosting',
                    'message' => $notificationMessage,
                    'is_read' => false,
                ]);
            }
        }
    }
}