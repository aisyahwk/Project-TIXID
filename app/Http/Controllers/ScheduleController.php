<?php

namespace App\Http\Controllers;

use App\Models\Cinema;
use App\Models\Movie;
use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Exports\ScheduleExport;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cinemas = Cinema::all();
        $movies = Movie::all();

        //with() -> mengambil data detail dari relasi, tidak hanya id nya
        //isi di dalam with diambil dari nama fungsi relasi di model
        $schedules = Schedule::with(['cinema', 'movie'])->get();
        return view('staff.schedule.index', compact('cinemas', 'movies', 'schedules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'cinema_id' => 'required',
            'movie_id' => 'required',
            'price' => 'required|numeric',
            //validasi item array (.) validasi index ke berapa pun (*)
            'hours.*' => 'required|date_format:H:i'
        ], [
            'cinema_id.required' => 'Bioskop harus dipilih',
            'movie_id.required' => 'Film harus dipilih',
            'price.required' => 'Harga harus diisi',
            'price.numeric' => 'Harga harus diisi dengan angka',
            'hours.*.required' => 'Jam tayang harus diisi minimal 1 data',
            'hours.*.date_format' => 'Jam tayang harus diisi dengan jam:menit'
        ]);

        //pengecekan data berdasarkan cinema_id dan movie_id lalu ambil hours nya
        //value('hours') : hanya mengambil hours, ga perlu data lain
        $hours = Schedule::where('cinema_id', $request->cinema_id)->where(
            'movie_id',
            $request->movie_id
        )->value('hours');
        //jika data belum ada $hours akan NULL, agar tetap array gunakan ternary
        //jika $hours ada isinya ambil, kalau NULL buat area kosong
        $hoursBefore = $hours ?? [];
        //gabungkan hours sebelumnya dengan yang baru ditambahkan
        $mergeHours = array_merge($hoursBefore, $request->hours);
        //hilangkan jam yang duplikat, gunakan array ini untuk database
        $newHours = array_unique($mergeHours);

        //updateOrCreate() : jika cinema_id dan movie_id udah ada di schedule (UPDATE) kalau gaada (CREATE)
        $createData = Schedule::updateOrCreate([
            'cinema_id' => $request->cinema_id,
            'movie_id' => $request->movie_id,
        ], [
            'price' => $request->price,
            'hours' => $newHours,
        ]);
        // dd($createData);
        if ($createData) {
            return redirect()->route('staff.schedules.index')->with('success', 'Berhasil menambahkan data');
        } else {
            return redirect()->back()->with('error', 'Gagal, coba lagi');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Schedule $schedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //first() = mengambil satu data
        $schedule = Schedule::where('id', $id)->with(['cinema', 'movie'])->first();
        return view('staff.schedule.edit', compact('schedule'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'price' => 'required|numeric',
            'hours.*' => 'required|date_format:H:i',
        ], [
            'price.required' => 'Harga harus diisi!',
            'price.numeric' => 'Harga harus diisi dengan angka',
            'hours.*.required' => 'Jam tayang harus diisi minimal 1 data',
            'hours.*.date_format' => 'Jam tayang harus diisi dengan jam:menit',
        ]);

        $updateData = Schedule::where('id', $id)->update([
            'price' => $request->price,
            'hours' => array_unique($request->hours),
        ]);

        if ($updateData) {
            return redirect()->route('staff.schedules.index')->with('success', 'Berhasil mengubah data');
        } else {
            return redirect()->back()->with('error', 'Gagal, coba lagi!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Schedule::where('id', $id)->delete();
        return redirect()->route('staff.schedules.index')->with('succes', 'Berhasil menghapus data');
    }

    public function trash()
    {
        //onlyTrashed() = filter data yang sudah dihappus, yabg deleted_at di phpmyadmin nya ada isi tanggal, hanya filter tetap digunakan get()/first() untuk ambilnya
        $schedules = Schedule::onlyTrashed()->with(['cinema', 'movie'])->get();
        return view('staff.schedule.trash', compact('schedules'));
    }

    public function restore($id)
    {
        //sebelum dicari, difilter dulu di akses hanya yang sudah di hapus
        $schedule = Schedule::onlyTrashed()->find($id);
        //restore() : mengembalikan data ke belum di hapus
        $schedule->restore();
        return redirect()->route('staff.schedules.index')->with('success', 'Berhasil mengembalikan data!');
    }

    public function deletePermanent($id)
    {
        $schedule = Schedule::onlyTrashed()->find($id);
        //forceDelete() : hapus selamanya dari database
        $schedule->forceDelete();
        return redirect()->back()->with('success', 'Berhasil menghapus data selamanya!');
    }

    public function exportExcel()
    {
        $file_name = 'schedule_file.xlsx';
        return Excel::download(new ScheduleExport, $file_name);
    }

    public function dataForDataTables()
    {
        $schedules = Schedule::with(['cinema', 'movie'])->get();

        return dataTables::of($schedules)
            ->addIndexColumn()
            ->addColumn('cinema_name', function ($data) {
                return $data->cinema->name ?? '-';
            })
            ->addColumn('movie', function ($data) {
                return $data->movie->title ?? '-';
            })
            ->addColumn('price', function ($data) {
                return 'Rp ' . number_format($data->price, 0, ',', '.');
            })
            ->addColumn('hours', function ($data) {
                $list = '<ul>';
                foreach ($data->hours as $hour) {
                    $list .= '<li>' . ($hour) . '</li>';
                }
                $list .= '</ul>';
                return $list;
            })
            ->addColumn('buttons', function ($data) {
                $btnEdit = '<a href="' . route('staff.schedules.edit', $data['id']) . '" class="btn btn-primary me-2">Edit</a>';
                $btnDelete = '<form class="me-2" action="' . route('staff.schedules.delete', $data['id']) . '" method="POST" style="display: inline-block; margin: left:6px">' .
                    csrf_field() .
                    method_field('DELETE') .
                    '<button type="submit" class="btn btn-danger">Hapus</button>
                    </form>';

                return $btnEdit . ' ' . $btnDelete;
            })
            ->rawColumns(['hours', 'buttons']) //mendaftarkan column yang dibuat di addCOlumn
            ->make(true); //mengubah query jadi JSON
    }

}


