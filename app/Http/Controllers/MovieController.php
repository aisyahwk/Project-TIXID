<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MovieExport;
use function PHPUnit\Framework\returnArgument;
use Yajra\DataTables\Facades\DataTables;

class MovieController extends Controller
{
    public function home()
    {
        //format pencarian data : where('column', 'operator', 'value')
        //jika operator ==/= operator BISA TIDAK DITULIS
        //operator yang digunakan : < kurang dari | > lebih dari | <> tidak sama dengan
        //format mengurutkan data : orderBy?('column', 'DESC/ASC') -> DESC z-a/0-0 = terbaru ke terlama , ASC a-z/0-9 = lama ke terbaru
        //get() : mengambil seluruh data HASIL FILTER
        //limit(angka) : mengambil data dengan jumlah data
        $movies = Movie::where('actived', 1)->orderBy('created_at', 'DESC')->limit(4)->get();
        return view('home', compact('movies'));
    }

    public function homeAllMovie(Request $request)
    {
        //ambil data dari input name="search_movie"
        $title = $request->search_movie;
        //kalau search_movie ga kosong, cari data
        if ($title != "") {
            // operator LIKE : mencari data yang mirip/mengandung kata tertentu
            // % digunakan untuk mengaktifkan LIKE
            // %kata : mencari kata belakang
            // kata% : mencari kata depan
            // %kata$ : mencari kata depan, tengah, belakang
            $movies = Movie::where('title', 'LIKE', '%' . $title . '%')->where('actived', 1)->orderBy('created_at', 'DESC')->get();
        } else {
            $movies = Movie::where('actived', 1)->orderBy('created_at', 'DESC')->get();
        }
        return view('movies', compact('movies'));
    }

    public function movieSchedules($movie_id, Request $request)
    {
        //request $request : mengambil data dari form atau href=?
        $sortPrice = $request['sort-price'];
        if ($sortPrice) {
            //karena mau mengurutkan berdasarkan price yang ada di scheudles, maka sorting (orderBy) disimpan di relasi with schedules
            $movie = Movie::where('id', $movie_id)->with([
                'schedules' => function ($q) use ($sortPrice): void {
                    // $q : mewakilkan model Schedule
                    // 'schedules' => function($q) {...} : melakukan filter/menjalankan eloquent didalam relasi
                    $q->orderBy('price', $sortPrice);
                },
                'schedules.cinema'
            ])->first();
        } else {
            //mengambil relasi didalam relasi
            //relasi cinema ada di schedule -> schedules.cinema (.)
            $movie = Movie::where('id', $movie_id)->with(['schedules', 'schedules.cinema'])->first();
            //first() : karena 1 data film,, diambilnya satu
        }

        $sortAlfabet = $request['sort-alfabet'];
        if ($sortAlfabet == 'ASC') {
            //ambil collection, colection : hasil dari get, first, all
            //$movie->schedules mengacu ke data relasi schedules
            //sortBy : mengurutkan collection, orderBy : mengurutkan query eloquent
            $movie->schedules = $movie->schedules->sortBy(function ($schedule) {
                return $schedule->cinema->name; //mengurutkan berdasarkan name dari relasi cinema, data yang dikembalikan yang sudah diurutkan ASC dari name ke cinema
            })->values();
        } elseif ($sortAlfabet == 'DESC') {
            //kalau sortAlfabet bukan ASC, berarti DESC, gunakan sortByDesc (untuk mengurutkan secara DESC)
            $movie->schedules = $movie->schedules->sortByDesc(function ($schedule) {
                return $schedule->cinema->name;
            })->values();
            //value() : ambil satu
            //values() : ambil semua data
        }

        $searchCinema = $request['search-cinema'];
        if ($searchCinema) {
            //filter collection, ambil relasi schedules hanya yang cinema _id nya sesuai dengan search-cinema
            $movie->schedules = $movie->schedules->where('cinema_id', $searchCinema)->values();
        }

        //list untuk dropdown bioskop , data murni yang tidak terfilter / sort apapun
        $listCinema = Movie::where('id', $movie_id)->with(['schedules', 'schedules.cinema'])->first();

        return view('schedule.detail-film', compact('movie', 'listCinema'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $movies = Movie::all();
        return view('admin.movie.index', compact('movies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.movie.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //cek seluruh request (data dr input)
        // dd($request->all());
        $request->validate([
            'title' => 'required',
            'duration' => 'required',
            'genre' => 'required',
            'director' => 'required',
            'age_rating' => 'required|numeric',
            //mimes => jenis file yg boleh diupload
            'poster' => 'required|mimes:jpg,jpeg,png,svg,webp',
            'description' => 'required|min:10'
        ], [
            'title.required' => 'Judul film harus diisi',
            'duration.required' => 'Durasi film harus diisi',
            'genre.required' => 'Genre film harus diisi',
            'director.required' => 'Sutradara harus diisi',
            'age_rating.required' => 'Usia minimal harus diisi',
            'age_rating.numberic' => 'Usia minimal harus diisi dengan angka',
            'poster.required' => 'Poster file harus diisi',
            'poster.mimes' => 'Poster file harus berupa JPG/JPEG/SVG/PNG/WEBP',
            'description' => 'Sinopsis harus diisi'
        ]);
        //$request -> file ('name_input) : ambil file yg diupload
        $gambar = $request->file('poster');
        //buat nama baru, nama acak untuk membedakan tiap file, akan menjadi: abcde poster.jpg
        //getClientOriginalExtention() : ambil extensi file
        $namaGambar = Str::random(5) . "-poster." . $gambar->getClientOriginalExtension();
        //storeAs -> menyimpan file, format storeAs(namafolder, namafile, visability)
        //hasil storeAs() berupa alamat file, visability, public/private
        $path = $gambar->storeAs("poster", $namaGambar, "public");


        $createData = Movie::create([
            'title' => $request->title,
            'duration' => $request->duration,
            'genre' => $request->genre,
            'director' => $request->director,
            'age_rating' => $request->age_rating,
            //yg disimpan di db lokasi fileny dari storeAs() -> $path
            'poster' => $path,
            'description' => $request->description,
            'actived' => 1
        ]);
        if ($createData) {
            return redirect()->route('admin.movies.index')->with('success', 'Berhasil tambah data!');
        } else {
            return redirect()->back()->with('error', 'Gagal! Silahkan coba lagi');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Movie $movie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $movie = Movie::find($id);
        return view('admin.movie.edit', compact('movie'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //cek seluruh request (data dr input)
        // dd($request->all());
        $request->validate([
            'title' => 'required',
            'duration' => 'required',
            'genre' => 'required',
            'director' => 'required',
            'age_rating' => 'required|numeric',
            //mimes => jenis file yg boleh diupload
            'poster' => 'mimes:jpg,jpeg,png,svg,webp',
            'description' => 'required|min:10'
        ], [
            'title.required' => 'Judul film harus diisi',
            'duration.required' => 'Durasi film harus diisi',
            'genre.required' => 'Genre film harus diisi',
            'director.required' => 'Sutradara harus diisi',
            'age_rating.required' => 'Usia minimal harus diisi',
            'age_rating.numberic' => 'Usia minimal harus diisi dengan angka',
            'poster.mimes' => 'Poster file harus berupa JPG/JPEG/SVG/PNG/WEBP',
            'description' => 'Sinopsis harus diisi'
        ]);
        //data sebelumnya
        $movie = Movie::find($id);
        //jika ada file poster baru
        if ($request->file('poster')) {
            $fileSebelumnya = storage_path("app/public/" . $movie['poster']);
            //file_exists() : cek apakah file ada di storage/app/public/poster/nama.jpg
            if (file_exists($fileSebelumnya)) {
                //unlink() : hapus
                unlink($fileSebelumnya);
            }
            //$request -> file ('name_input) : ambil file yg diupload
            $gambar = $request->file('poster');
            //buat nama baru, nama acak untuk membedakan tiap file, akan menjadi: abcde poster.jpg
            //getClientOriginalExtention() : ambil extensi file
            $namaGambar = Str::random(5) . "-poster." . $gambar->getClientOriginalExtension();
            //storeAs -> menyimpan file, format storeAs(namafolder, namafile, visability)
            //hasil storeAs() berupa alamat file, visability, public/private
            $path = $gambar->storeAs("poster", $namaGambar, "public");
        }

        $updateData = Movie::where('id', $id)->update([
            'title' => $request->title,
            'duration' => $request->duration,
            'genre' => $request->genre,
            'director' => $request->director,
            'age_rating' => $request->age_rating,
            //
            'poster' => $path ?? $movie['poster'],
            'description' => $request->description,
            'actived' => 1
        ]);
        if ($updateData) {
            return redirect()->route('admin.movies.index')->with('success', 'Berhasil tambah data!');
        } else {
            return redirect()->back()->with('error', 'Gagal! Silahkan coba lagi');
        }
    }

    public function dataChart()
    {
        $movieActive = Movie::where('actived', 1)->count();
        $movieNonActive = Movie::where('actived', 0)->count();

        $labels = ['Film Aktif', 'Film Non-Aktif'];
        $data = [$movieActive, $movieNonActive];

        return response()->json([
            'labels' => $labels,
            'data' => $data
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $deleteData = Movie::where('id', $id)->delete();
        if ($deleteData) {
            return redirect()->route('admin.movies.index')->with('success', 'Berhasil menghapus data');
        } else {
            return redirect()->back()->with('failed', 'Gagal! Silahkan coba lagi');
        }
    }

    public function nonAktif($id)
    {
        $movie = Movie::findOrFail($id);
        $movie->actived = 0;
        $movie->save();

        return redirect()->route('admin.movies.index')->with('success', 'Film berhasil di nonaktifkan');
    }

    public function exportExcel()
    {
        $file_name = 'date-film.xlsx';
        return Excel::download(new MovieExport, $file_name);
    }

    public function trash()
    {
        $movies = Movie::onlyTrashed()->get();
        return view('admin.movie.trash', compact('movies'));
    }

    public function restore($id)
    {
        $movies = Movie::onlyTrashed()->find($id);
        $movies->restore();
        return redirect()->route('admin.movies.index')->with('success', 'Berhasil mengembalikan data!');
    }

    public function deletePermanent($id)
    {
        $movie = Movie::onlyTrashed()->find($id);
        $movie->forceDelete();
        return redirect()->back()->with('success', 'Berhasil menghapus seutuhnya!');
    }

    public function dataForDatatables()
    {
        //siapkan query eloquent dari model Movie
        $movies = Movie::query();
        //DataTables::of($movies) : menyiapkan data untuk DataTables, data diambil dari $movie
        return DataTables::of($movies)
            ->addIndexColumn() //memberikan nomor 1, 2, dst di column table
            //addColumn : menambahkan data selain dari table movies, digunakan untuk button aksi dan data yang perlu di manipulasi
            ->addColumn('imgPoster', function ($data) {
                $urlImage = asset('storage') . "/" . $data['poster'];
                //menambahkan data baru bernama imgPoster dengan hasil tag img yang link nya udah nyambung ke storage "' untuk konten ke variable
                return '<img src="' . $urlImage . '" width="200px">';
            })
            ->addColumn('activedBagde', function ($data) {
                //membuat data activedBadge yang akan mengembalikan badge warna sesuai status
                if ($data->actived == 1) {
                    return '<span class="badge badge-success">Aktif</span>';
                } else {
                    return '<span class="badge badge-secondary">Non-Aktif</span';
                }
            })
            ->addColumn('buttons', function ($data) {
                $btnDetail = '<button class="btn btn-secondary me-2" onclick=\'showModal(' . json_encode($data) . ')\'>Detail</button>';
                $btnEdit = '<a href="' . route('admin.movies.edit', $data['id']) . '" class="btn btn-primary me-2">Edit</a>';
                $btnDelete = '<form class="me-2" action="' . route('admin.movies.delete', $data['id']) . '" method="POST">' .
                    csrf_field() .
                    method_field('DELETE') .
                    '<button type="submit" class="btn btn-danger">Hapus</button>
                    </form>';

                $btnNonAktif = '';
                if ($data->actived == 1) {
                    $btnNonAktif = '<form class="me-2" action="' . route('admin.movies.nonaktif', $data['id']) . '" method="POST">' .
                        csrf_field() .
                        method_field('PATCH') .
                        '<button type="submit" class="btn btn-warning">Non-Aktif</button>
                    </form>';
                }
                return '<div class="d-flex justify-content-center">' . $btnDetail . $btnEdit . $btnDelete . $btnNonAktif . '</div>';
            })
            //rawColumns([]) : mendaftarkan column yang dibuat di addCOlumn
            ->rawColumns(['imgPoster', 'activedBagde', 'buttons'])
            ->make(true); //mengubah query menjadi JSON (format yang dibaca datatables)

    }
}
