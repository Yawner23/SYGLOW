<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Models\Customer;
use App\Models\Distributor;
use App\Models\ReferralCode;
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
        } elseif (strtolower($roleName) === 'distributor' || $request->role === 'distributor') {

            $request->validate([
                'distributor_type' => ['required', 'string'],
                'region' => ['required', 'string', 'max:255'],
                'province' => ['required', 'string', 'max:255'],
                'city' => ['required', 'string', 'max:255'],
                'brgy' => ['required', 'string', 'max:255'],
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'max:255'],
                'contact_number' => ['required', 'string', 'max:255'],
                'code' => ['required', 'string', 'max:255'],
            ]);

            // Validate referral code based on distributor_type
            if ($request->distributor_type == 1) {
                $codeExists = ReferralCode::where('referral_code', $request->code)->exists();
                if (!$codeExists) {
                    return redirect()->back()->withInput()->withErrors(['code' => 'Referral Code does not exist']);
                }
            } elseif (in_array($request->distributor_type, [2, 3])) {
                $codeExists = Distributor::where('distributor_type', '1')->where('user_id', $request->code)->exists();
                if (!$codeExists) {
                    return redirect()->back()->withInput()->withErrors(['code' => 'Code does not match any existing distributor user_id']);
                }
            } else {
                $codeExists = Distributor::where('distributor_type', '3')->where('user_id', $request->code)->exists();
                if (!$codeExists) {
                    return redirect()->back()->withInput()->withErrors(['code' => 'Code does not match any existing City Distributor']);
                }
            }

            // Create Distributor record
            $distributor = Distributor::create([
                'user_id' => $user->id,
                'distributor_type' => $request->distributor_type,
                'region' => $request->region,
                'province' => $request->province,
                'city' => $request->city,
                'brgy' => $request->brgy,
                'contact_number' => $request->contact_number,
                'name' => $request->name,
                'code' => $request->code,
            ]);

            Log::info('Distributor profile created.', ['distributor_id' => $distributor->id]);
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
