<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\DriverGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DriverGroupController extends Controller
{
    public function index()
    {
        $groups = DriverGroup::with(['driver1', 'driver2', 'driver3', 'driver4'])->get();
        $drivers = User::role('chauffeur')->get();
        
        return view('driver-groups.index', compact('groups', 'drivers'));
    }

    public function create()
    {
        $drivers = User::role('chauffeur')->get();
        return view('driver-groups.create', compact('drivers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'group_name' => 'required|string|max:255',
            'driver_1_id' => 'required|exists:users,id',
            'driver_2_id' => 'required|exists:users,id|different:driver_1_id',
            'driver_3_id' => 'required|exists:users,id|different:driver_1_id|different:driver_2_id',
            'driver_4_id' => 'required|exists:users,id|different:driver_1_id|different:driver_2_id|different:driver_3_id',
        ]);

        DriverGroup::create($request->all());

        return redirect()->route('driver-groups.index')
            ->with('success', 'Groupe de chauffeurs créé avec succès.');
    }

    public function show(DriverGroup $driverGroup)
    {
        $driverGroup->load(['driver1', 'driver2', 'driver3', 'driver4']);
        
        // Obtenir le planning de la semaine actuelle
        $weeklySchedule = $driverGroup->getWeeklySchedule();
        
        // Obtenir le planning des 4 prochaines semaines
        $nextWeeksSchedule = [];
        for ($i = 1; $i <= 4; $i++) {
            $startDate = Carbon::now()->addWeeks($i)->startOfWeek();
            $nextWeeksSchedule[$i] = $driverGroup->getWeeklySchedule($startDate);
        }

        return view('driver-groups.show', compact('driverGroup', 'weeklySchedule', 'nextWeeksSchedule'));
    }

    public function edit(DriverGroup $driverGroup)
    {
        $drivers = User::role('chauffeur')->get();
        return view('driver-groups.edit', compact('driverGroup', 'drivers'));
    }

    public function update(Request $request, DriverGroup $driverGroup)
    {
        $request->validate([
            'group_name' => 'required|string|max:255',
            'driver_1_id' => 'required|exists:users,id',
            'driver_2_id' => 'required|exists:users,id|different:driver_1_id',
            'driver_3_id' => 'required|exists:users,id|different:driver_1_id|different:driver_2_id',
            'driver_4_id' => 'required|exists:users,id|different:driver_1_id|different:driver_2_id|different:driver_3_id',
        ]);

        $driverGroup->update($request->all());

        return redirect()->route('driver-groups.index')
            ->with('success', 'Groupe de chauffeurs mis à jour avec succès.');
    }

    public function destroy(DriverGroup $driverGroup)
    {
        $driverGroup->delete();

        return redirect()->route('driver-groups.index')
            ->with('success', 'Groupe de chauffeurs supprimé avec succès.');
    }

    public function schedule()
    {
        $groups = DriverGroup::with(['driver1', 'driver2', 'driver3', 'driver4'])->where('is_active', true)->get();
        
        $weeklySchedules = [];
        foreach ($groups as $group) {
            $weeklySchedules[$group->id] = $group->getWeeklySchedule();
        }

        return view('driver-groups.schedule', compact('groups', 'weeklySchedules'));
    }

    public function advanceRotation(DriverGroup $driverGroup)
    {
        $driverGroup->advanceRotation();
        
        return redirect()->back()->with('success', 'Rotation avancée avec succès.');
    }

    public function reverseRotation(DriverGroup $driverGroup)
    {
        $driverGroup->reverseRotation();
        
        return redirect()->back()->with('success', 'Rotation reculée avec succès.');
    }

    public function resetRotation(DriverGroup $driverGroup)
    {
        $driverGroup->resetRotation();
        
        return redirect()->back()->with('success', 'Rotation réinitialisée avec succès.');
    }

    public function getAvailableDrivers(Request $request)
    {
        $date = $request->get('date', Carbon::now()->format('Y-m-d'));
        $groups = DriverGroup::where('is_active', true)->get();
        
        $availableDrivers = [];
        foreach ($groups as $group) {
            $availableDriverIds = $group->getAvailableDriversForDate($date);
            $drivers = User::whereIn('id', $availableDriverIds)->get();
            $availableDrivers = array_merge($availableDrivers, $drivers->toArray());
        }
        
        return response()->json($availableDrivers);
    }

    public function autoAssignGroups()
    {
        $drivers = User::role('chauffeur')->get();
        
        if ($drivers->count() < 4) {
            return redirect()->back()->with('error', 'Il faut au moins 4 chauffeurs pour créer un groupe.');
        }

        // Supprimer les groupes existants
        DriverGroup::truncate();

        // Diviser les chauffeurs en groupes de 4
        $driverChunks = $drivers->chunk(4);
        
        foreach ($driverChunks as $index => $chunk) {
            $driversArray = $chunk->toArray();
            
            DriverGroup::create([
                'group_name' => 'Groupe ' . ($index + 1),
                'driver_1_id' => $driversArray[0]['id'] ?? null,
                'driver_2_id' => $driversArray[1]['id'] ?? null,
                'driver_3_id' => $driversArray[2]['id'] ?? null,
                'driver_4_id' => $driversArray[3]['id'] ?? null,
                'current_rotation_day' => 0,
                'is_active' => true
            ]);
        }

        return redirect()->route('driver-groups.index')
            ->with('success', 'Groupes de chauffeurs créés automatiquement avec succès.');
    }
} 