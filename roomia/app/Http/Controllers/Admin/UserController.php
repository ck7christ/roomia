<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $roles = Role::query()->orderBy('name')->pluck('name');

        $query = User::query()->with('roles');

        if ($request->filled('q')) {
            $kw = trim($request->q);
            $query->where(function ($q) use ($kw) {
                $q->where('name', 'like', "%{$kw}%")
                    ->orWhere('email', 'like', "%{$kw}%");
            });
        }

        if ($request->filled('role')) {
            $role = $request->role;
            $query->whereHas('roles', function ($q) use ($role) {
                $q->where('name', $role);
            });
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $roles = Role::query()->orderBy('name')->pluck('name');

        // form dùng $user để old() + value
        $user = new User();

        return view('admin.users.create', compact('user', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $data = $this->validateData($request);

        $user = User::create([
            'name' => $data['name'],
            'email' => strtolower(trim($data['email'])),
            'password' => Hash::make($data['password']),
        ]);

        // Gán role (chọn 1 role)
        if (!empty($data['role'])) {
            $user->syncRoles([$data['role']]);
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Tạo user thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
        $roles = Role::query()->orderBy('name')->pluck('name');
        $user->load('roles');

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
        $data = $this->validateData($request, $user);

        $user->name = $data['name'];
        $user->email = strtolower(trim($data['email']));

        // password optional khi update
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        if (!empty($data['role'])) {
            $user->syncRoles([$data['role']]);
        }

        return redirect()
            ->route('admin.users.edit', $user)
            ->with('success', 'Cập nhật user thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
        // Chặn tự xoá chính mình
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Bạn không thể xoá chính mình.');
        }

        // Nếu bạn muốn chặn xoá admin cuối cùng thì nói mình thêm rule
        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Đã xoá user.');
    }
    private function validateData(Request $request, ?User $user = null): array
    {
        $id = $user?->id;

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($id)],
            // chọn 1 role (đúng theo spatie roles.name)
            'role' => ['required', 'string', Rule::exists('roles', 'name')],
        ];

        // create: password bắt buộc | update: password optional
        if ($user) {
            $rules['password'] = ['nullable', 'string', 'min:8', 'confirmed'];
        } else {
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
        }

        return $request->validate($rules);
    }
}
