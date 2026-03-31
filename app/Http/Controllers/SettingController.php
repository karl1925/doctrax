<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User, Setting, Partner};
use Laravolt\Avatar\Facade as Avatar;

class SettingController extends Controller
{
    public function organization()
    {
        $users = User::withoutTrashed()->orderBy('name')->get();
        $roles = ['receiver', 'monitorer', 'super'];
        $usersByRole = [];
        foreach ($roles as $role) {
            $usersByRole[ucfirst($role)] = User::withoutTrashed()->whereIn('id', Setting::where('setting', $role)->pluck('user_id'))
                ->orderBy('name')
                ->get();
        }
        $directorUserId = Setting::where('setting', 'director')->pluck('user_id')->first();
        $ardUserId = Setting::where('setting', 'ard')->pluck('user_id')->first();
        $afdChiefUserId = Setting::where('setting', 'afdchief')->pluck('user_id')->first();
        $todChiefUserId = Setting::where('setting', 'todchief')->pluck('user_id')->first();

        return view('settings.organization', compact(
            'users',
            'usersByRole',
            'directorUserId',
            'ardUserId',
            'afdChiefUserId',
            'todChiefUserId'
        ));
    }

    public function personnel(Request $request)
    {
        $allowedSorts = ['name', 'created_at'];
        $sortField = in_array($request->query('sort'), $allowedSorts) ? $request->query('sort') : 'name';
        $sortOrder = $request->query('direction') === 'desc' ? 'desc' : 'asc';
        $search = $request->query('search');
        $query = User::withoutTrashed();

        // Search logic
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy($sortField, $sortOrder)->paginate(10);

        return view('settings.personnel', compact('users', 'sortField', 'sortOrder'));
    }

    public function preferences() {
        return view('settings.preferences', [
            'preference' => auth()->user()->preference ?? auth()->user()->preference()->create()
        ]);
    }

    // Update leadership roles
    public function updateLeadership(Request $request)
    {
        $request->validate([
            'director_user_id' => 'nullable|exists:users,id',
            'ard_user_id' => 'nullable|exists:users,id',
            'afd_chief_user_id' => 'nullable|exists:users,id',
            'tod_chief_user_id' => 'nullable|exists:users,id',
        ]);

        // Save/update settings
        Setting::updateOrCreate(['setting' => 'director'], ['user_id' => $request->director_user_id]);
        Setting::updateOrCreate(['setting' => 'ard'], ['user_id' => $request->ard_user_id]);
        Setting::updateOrCreate(['setting' => 'afdchief'], ['user_id' => $request->afd_chief_user_id]);
        Setting::updateOrCreate(['setting' => 'todchief'], ['user_id' => $request->tod_chief_user_id]);

        return back()->with('success', 'Leadership roles updated successfully.');
    }

    // Add user to a role
    public function addUser(Request $request, $role)
    {
        $role = strtolower($role);
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        Setting::firstOrCreate([
            'setting' => $role,
            'user_id' => $request->user_id
        ]);

        return back()->with('success', ucfirst($role) . ' added successfully.');
    }

    public function removeUser($role, $userId)
    {
        $role = strtolower($role);
        Setting::where('setting', $role)
            ->where('user_id', $userId)
            ->delete();
        return back()->with('success', ucfirst($role) . ' removed successfully.');
    }

    public function updatePreferences(Request $request)
    {        
        $preference = auth()->user()->preference;
        $preference->update([
            'external_email_notify_received' => $request->has('external_email_notify_received'),
            'external_email_notify_updated' => $request->has('external_email_notify_updated'),
            'external_email_notify_completed' => $request->has('external_email_notify_completed'),
            'internal_email_notify_received' => $request->has('internal_email_notify_received'),
            'internal_email_notify_returned' => $request->has('internal_email_notify_returned'),
            'internal_email_notify_reviewed' => $request->has('internal_email_notify_reviewed'),
            'internal_email_notify_completed' => $request->has('internal_email_notify_completed'),
            'internal_email_notify_rejected' => $request->has('internal_email_notify_rejected'),
        ]); 
        return back()->with('success', 'Notification preferences updated successfully.');
    }

    public function partners(Request $request) {
        $allowedSorts = ['name', 'code', 'email', 'contactNo', 'type'];
        $sortField = in_array($request->query('sort'), $allowedSorts) ? $request->query('sort') : 'name';
        $sortOrder = $request->query('direction') === 'desc' ? 'desc' : 'asc';
        $search = $request->query('search');
        $query = Partner::withoutTrashed();
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            });
        }
        $partners = $query->orderBy($sortField, $sortOrder)->paginate(10);
        return view('settings.partners', compact('partners', 'sortField', 'sortOrder'));
    }

    public function addPartner(Request $request)
    {
        // Validate input
        $data = $request->validate([
            'name'      => 'required|string|max:255|unique:partners,name',
            'code'      => 'required|string|max:50|unique:partners,code|alpha_num',
            'email'     => 'nullable|email|max:255',
            'contactNo' => 'nullable|string|max:20',
            'type'      => 'required|in:NGA,LGU,SUC,NGO,Others',
        ]);

        // Ensure code is uppercase
        $data['code'] = strtoupper($data['code']);

        // Create partner
        $partner = Partner::create($data);

        // Return JSON response with the new partner
        return response()->json([
            'success' => true,
            'message' => 'Partner agency added successfully.',
            'partner' => $partner
        ]);
    }

    public function removePartner($id)
    {
        Partner::find($id)
            ->delete();
        return back()->with('success', 'Partner removed successfully.');
    }

    public function searchAgencies(Request $request)
    {
       $query = $request->get('q', '');

        $results = Partner::where('name', 'like', "%{$query}%")
                          ->orderBy('name')
                          ->limit(10)
                          ->pluck('name'); // returns only names

        return response()->json($results);
    }
}
