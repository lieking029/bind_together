<?php

namespace App\Http\Controllers;

use App\Enums\ActivityType;
use App\Models\ActivityRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditionListController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user = Auth::user()->load('organization');

        $status = $request->query('status') ?? 0;

        $type   = null;

        $isDeleted = 0;

        if ($request->has('isArchived')) {
            $isDeleted = $request->query('isArchived');
        }

        if ($request->has('type')) {
            $type = $request->query('type');
        }

        $auditions = ActivityRegistration::query()
            ->with([
                'activity.user.roles',
                'activity.user.organization',
                'user.roles'
            ])
            ->whereIn('status',  $type == null ? [$status] : [$status, 1, 2])
            ->where('is_deleted', $isDeleted);

        $auditions->whereHas('activity', function ($query) use ($user) {
            if ($user->hasRole('adviser')) {
                $query->where('user_id', $user->id);
            }
        });

        $auditions = $auditions->whereHas('activity', function ($query) use ($type, $user) {
            if ($type == '3') {
                $query->where('type', ActivityType::Competition);
            } else {
                $query->where('type', ActivityType::Audition);
            }
        });

        $auditions = $auditions->get();

        return view('adviser.performer-record.index', ['auditions' => $auditions, 'status' => $status]);
    }

    public function permanentDelete($id)
    {
        $act = ActivityRegistration::find($id);

        if (!$act) {
            alert()->error('Record not found.');
            return redirect()->back();
        }

        $act->update(['is_deleted' => 2]);

        alert()->success('Deleted');

        return redirect()->back();
    }

    public function unarchive($id)
    {
        $act = ActivityRegistration::find($id);

        if (!$act) {
            alert()->error('Record not found.');
            return redirect()->back();
        }

        $act->update(['is_deleted' => 0]);

        alert()->success('Unarchived');

        return redirect()->back();
    }

    public function archive($id)
    {
        $act = ActivityRegistration::find($id);

        if (!$act) {
            alert()->error('Record not found.');
            return redirect()->back();
        }

        $act->update(['is_deleted' => 1]);

        alert()->success('Archived');

        return redirect()->back();
    }
}
