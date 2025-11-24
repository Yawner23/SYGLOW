<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Models\Customer;
use App\Models\Distributor;
use App\Models\Role;
use App\Models\User;
use App\Models\RoleUser;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;


class UserManagementController extends Controller
{
    public function updateStatus(Request $request)
    {
        $user = User::find($request->id);
        if ($user) {
            $user->status = $request->status;
            $user->save();
        }

        return response()->json(['success' => true]);
    }
    public function index()
    {
        if (request()->ajax()) {
            $role = request()->get('role');

            $user_management = User::with('roles')
                ->when($role == 'other', function ($query) {
                    $query->whereDoesntHave('roles', function ($query) {
                        $query->whereIn('name', ['customer', 'distributor']);
                    });
                })
                ->when($role && $role != 'other', function ($query) use ($role) {
                    $query->whereHas('roles', function ($query) use ($role) {
                        $query->where('name', $role);
                    });
                })
                ->leftJoin('customers', 'users.id', '=', 'customers.user_id')
                ->leftJoin('distributors', 'users.id', '=', 'distributors.user_id') // Join distributors
                ->select([
                    'users.*',
                    'customers.referral_code',
                    'customers.first_name',
                    'customers.last_name',
                    'distributors.distributor_type' // Add distributor_type
                ]);

            return DataTables::of($user_management)
                ->addIndexColumn()
                ->addColumn('role', function ($user) {
                    return $user->roles->pluck('name')->join(', ');
                })
                ->addColumn('status', function ($row) {
                    $checked = $row->status === 'active' ? 'checked' : '';
                    return '<input type="checkbox" class="status-checkbox" data-id="' . $row->id . '" ' . $checked . '> ' . ucfirst($row->status);
                })
                ->addColumn('distributor_type', function ($row) {
                    $types = [
                        1 => 'Regional Distributor',
                        2 => 'Provincial Distributor',
                        3 => 'City Distributor',
                        4 => 'Reseller Distributor'
                    ];
                    return $types[$row->distributor_type] ?? 'N/A';
                })
                ->addColumn('action', function ($row) {
                    return view('admin.user_management.partials.actions', ['row' => $row]);
                })
                ->addColumn('referral_code', function ($row) {
                    return $row->referral_code ?? 'N/A';
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('admin.user_management.index');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user_management.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id); // Find the user or fail if not found
        $roles = Role::all(); // Get all roles for the dropdown

        return view('admin.user_management.edit', compact('user', 'roles'));
    }

    // Update the specified user in storage
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id'
        ]);

        $user = User::findOrFail($id);

        $user->name = $request->input('name');
        $user->email = $request->input('email');

        if ($request->has('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        $user->save();

        // Sync the user's roles to ensure that only the specified role_id is assigned
        $user->roles()->sync([$request->input('role_id')]);

        return redirect()->route('user_management.index')->with('success', 'User updated successfully.');
    }



    public function create()
    {
        $roles = Role::all();
        return view('admin.user_management.create', compact('roles'));
    }

    public function store(Request $request)
    {
        Log::info('Store User request received.', $request->all());

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        // Create user
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'status' => 'active',
            'password' => Hash::make($request->input('password')),
        ]);

        Log::info('User created successfully.', ['user_id' => $user->id]);

        // Attach role
        $user->roles()->attach($request->input('role_id'));
        $roleName = Role::find($request->input('role_id'))->name;
        Log::info('Role attached to user.', ['user_id' => $user->id, 'role' => $roleName]);

        // Check role and create related model
        if (strtolower($roleName) === 'customer') {
            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'contact_number' => 'required|string|max:20',
            ]);

            $customer = Customer::create([
                'user_id' => $user->id,
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'contact_number' => $request->input('contact_number'),
                'date_of_birth' => $request->input('date_of_birth'),
                'profile_picture' => $request->input('profile_picture'),
                'referral_code' => $request->input('referral_code'),
            ]);

            Log::info('Customer profile created.', ['customer_id' => $customer->id]);
        } elseif (strtolower($roleName) === 'distributor') {
            $request->validate([
                'name' => 'required|string|max:255',
                'distributor_type' => 'required|string',
                'region' => 'required|string',
                'province' => 'required|string',
                'city' => 'required|string',
                'brgy' => 'required|string',
                'contact_number' => 'required|string',
            ]);

            $distributor = Distributor::create([
                'user_id' => $user->id,
                'name' => $request->input('name'),
                'distributor_type' => $request->input('distributor_type'),
                'region' => $request->input('region'),
                'province' => $request->input('province'),
                'city' => $request->input('city'),
                'brgy' => $request->input('brgy'),
                'contact_number' => $request->input('contact_number'),
                'code' => $request->input('code'),
                'valid_id_path' => $request->input('valid_id_path'),
                'selfie_with_id_path' => $request->input('selfie_with_id_path'),
                'photo_with_background_path' => $request->input('photo_with_background_path'),
                'profile_picture' => $request->input('profile_picture'),
            ]);

            Log::info('Distributor profile created.', ['distributor_id' => $distributor->id]);
        } else {
            Log::warning('Unknown role detected.', ['role' => $roleName, 'user_id' => $user->id]);
        }

        Log::info('Store User process completed.', ['user_id' => $user->id]);

        return redirect()->route('user_management.index')->with('success', 'User created successfully.');
    }

    public function destroy(User $user_management)
    {
        $user_management->delete();
        return redirect()->route('user_management.index')->with('success', 'User deleted successfully.');
    }


    public function export(Request $request)
    {
        $role = $request->role;

        if (!in_array($role, ['customer', 'distributor'])) {
            return back()->with('error', 'Invalid role for export.');
        }

        return Excel::download(new UsersExport($role), $role . '_users.xlsx');
    }
}
