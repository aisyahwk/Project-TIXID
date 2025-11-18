<?php

namespace App\Http\Controllers;

use App\Exports\UserExport;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function store(Request $request)
    {
        // (Request $request) : class yang digunakan untuk mengambil value request, form get/post
        //validasi
        $request->validate([
            //'name_input' => 'validasi'
            //required : wajib di isi, min : minimal 3 character
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',
            //email:dns -> memastikan email valid, @gmail, dll
            'email' => 'required|email:dns',
            'password' => 'required|min:3',
        ], [
            //custom tulisan error
            'first_name.required' => 'Nama depan tidak boleh kosong',
            'first_name.min' => 'Nama depan harus diisi minimal 3 karakter',
            'last_name.required' => 'Nama belakang tidak boleh kosong',
            'last_name.min' => 'Nama belakang harus diisi minimal 3 karakter',
            'email.required' => 'Email tidak boleh kosong',
            'email.email' => 'Email harus diisi dengan data yang valid',
            'password.required' => 'Password tidak boleh kosong',
            'password.min' => 'Password harus diisi minimal 8 karakter'
            //'name_input.validasi' => 'pesan'
        ]);
        //proses kirim data
        // create(): membuat data baru
        $createUser = User::create([
            // 'colum' => $request->name_input
            'name' => $request->first_name . " " . $request->last_name,
            'email' => $request->email,
            //hash : enskripsi data agar yg tersimpan di db karakter acak,untuk menghindari kebocoran data pasword
            'password' => hash::make($request->password),
            'role' => 'user'
        ]);

        //menentukan perpindahan halaman
        if ($createUser) {
            return redirect()->route('login')->with('ok', 'Berhasil membuat akun ! Silahkan login');
        } else {
            //back() : kembali ke halaman sebelumnnya
            return redirect()->back()->with('error', 'Gagal ! silahkan coba lagi ');
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ], [
            'email.required' => 'Email Harus diisi',
            'password.required' => 'Password Harus diisi'
        ]);
        //mengambil data yang akan di cek kecocokannya : email pw , username-pw
        $data = $request->only(['email', 'password']);
        //auth -> class authentication pd laravel yang menyimpan data sesion yang berhubungan dengan pengguna
        //attempt -> melakukan pengecekan data jika sesuai maka data pengguna di simpan pada session auth
        if (Auth::attempt($data)) {
            //kalau admin ke dashboard, selain itu home
            if (Auth::user()->role == 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Login
                berhasil dilakukan!');
            } elseif (Auth::user()->role == 'staff') {
                return redirect()->route('staff.promos.index')->with('login', 'Berhasil Login!');
            } else {
                return redirect()->route('home')->with('success', 'login berhasil dilakukan!');
            }
        } else {
            return redirect()->back()->with('error', 'Gagal login coba lagi');
        }
    }

    public function logout()
    {
        //menghapus sesi login
        Auth::logout();
        return redirect()->route('home')->with('logout', 'Berhasil Logout! Silahkan login kembali Untuk akses lengkap');
    }



    // tampilkan semua user/admin/staff
    public function index()
    {
        $users = User::whereIn('role', ['admin', 'staff'])->get();
        return view('admin.user.index', compact('users'));
    }

    // form tambah petugas
    public function create()
    {
        return view('admin.user.create');
    }

    // simpan petugas baru
    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'staff'
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Staff berhasil ditambahkan');
    }

    // form edit petugas
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user.edit', compact('user'));
    }

    // update petugas
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,' . $user->id
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Data Staff berhasil diperbarui');
    }

    // hapus petugas
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Data staff berhasil dihapus');
    }

    public function exportExcel()
    {
        $file_name = 'user-file.xlsx';
        return Excel::download(new UserExport, $file_name);
    }

    public function trash()
    {
        $userTrash = User::onlyTrashed()->get();
        return view('admin.user.trash', compact('userTrash'));
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->find($id);
        $user->restore();
        return redirect()->route('admin.users.index')->with('success', 'Berhasil mengembalikan data!');
    }

    public function deletePermanent($id)
    {
        $user = User::onlyTrashed()->find($id);
        $user->forceDelete();
        return redirect()->back()->with('success', 'Berhasil menghapus seutuhnya!');
    }

    public function dataForDatatables()
    {
        $users = User::query();
        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('buttons', function ($data) {
                $btnEdit = '<a href="' . route('admin.users.edit', $data['id']) . '" class="btn btn-primary me-2">Edit</a>';
                $btnDelete = '<form class="me-2" action="' . route('admin.users.delete', $data['id']) . '" method="POST">' .
                    csrf_field() .
                    method_field('DELETE') .
                    '<button type="submit" class="btn btn-danger">Hapus</button>
                    </form>';

                return '<div class="d-flex justify-content-center">' . $btnEdit . $btnDelete . '</div>';
            })
            ->rawColumns(['buttons'])
            ->make(true); //mengubah query menjadi JSON (format yang dibaca datat
    }
}
