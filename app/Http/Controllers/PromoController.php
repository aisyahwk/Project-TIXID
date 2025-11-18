<?php

namespace App\Http\Controllers;

use App\Exports\PromoExport;
use App\Models\Promo;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class PromoController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $promos = Promo::all();
        return view('staff.promos.index', compact('promos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('staff.promos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'promo_code' => 'required',
            'discount' => 'required',
            'type' => 'required|in:percent,rupiah',
        ], [
            'promo_code.required' => 'Kode Promo harus diisi!',
            'discount.required' => 'Diskon harus diisi!',
            'type.required' => 'Tipe diskon harus diisi!',
        ]);

        if ($request->type === 'percent' && $request->discount > 100) {
            return redirect()->back()->withInput()->with('error', 'Discount persen tidak boleh lebih dari 100');
        }
        if ($request->type === 'rupiah' && $request->discount < 1000) {
            return redirect()->back()->withInput()->with('error', 'Discount rupiah tidak boleh kurang dari 1000');
        }

        $createData = Promo::create([
            'promo_code' => $request->promo_code,
            'discount' => $request->discount,
            'type' => $request->type,
        ]);
        if ($createData) {
            return redirect()->route('staff.promos.index')->with('success', 'Berhasil menambahkan data!');
        } else {
            return redirect()->back()->with('error', 'Gagal, silahkan coba lagi!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Promo $promo)
    {
        return redirect()->route('staff.promos.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $promo = Promo::findOrFail($id);
        return view('staff.promos.edit', compact('promo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'promo_code' => 'required|unique:promos,promo_code,' . $id,
            'discount' => 'required|integer|min:1',
            'type' => 'required|in:percent,rupiah',
        ], [
            'promo_code.required' => 'Kode Promo harus diisi!',
            'discount.required' => 'Diskon harus diisi!',
            'type.required' => 'Tipe diskon harus diisi!',
        ]);

        if ($request->type === 'percent' && $request->discount > 100) {
            return redirect()->back()->withInput()->with('error', 'Discount persen tidak boleh lebih dari 100');
        }
        if ($request->type === 'rupiah' && $request->discount < 1000) {
            return redirect()->back()->withInput()->with('error', 'Discount rupiah tidak boleh kurang dari 1000');
        }


        // update data promo
        $promo = Promo::findOrFail($id); // ambil model, bukan string
        $promo->promo_code = $request->promo_code;
        $promo->discount = $request->discount;
        $promo->type = $request->type;
        $promo->actived = 1;
        $promo->save();

        return redirect()->route('staff.promos.index')
            ->with('success', `Promo berhasil diperbarui!`);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Promo $id)
    {
        $id->delete();

        return redirect()->route('staff.promos.index')->with('success', 'Berhasil menghapus data!');
    }

    public function trash()
    {
        $promoTrash = Promo::onlyTrashed()->get();
        return view('staff.promos.trash', compact('promoTrash'));
    }

    public function restore($id)
    {
        $promos = Promo::onlyTrashed()->find($id);
        $promos->restore();
        return redirect()->route('staff.promos.index')->with('success', 'Berhasil mengembalikan data!');
    }

    public function deletePermanent($id)
    {
        $promos = Promo::onlyTrashed()->find($id);
        $promos->forceDelete();
        return redirect()->back()->with('success', "Berhasil menghapus data selamanya!");
    }
    public function exportExcel()
    {
        $file_name = 'promo-file.xlsx';
        return Excel::download(new PromoExport, $file_name);
    }

    public function dataForDataTables()
    {
        $promos = Promo::query()->get();

        return DataTables::of($promos)
            ->addIndexColumn()
            ->addColumn('discount_display', function ($data) {
                if ($data->type == 'percent') {
                    return $data->discount . '%';
                } else {
                    return 'Rp ' . number_format($data->discount, 0, ',', '.');
                }
            })
            ->addColumn('buttons', function ($data) {
                $btnEdit = '<a href="' . route('staff.promos.edit', $data['id']) . '" class="btn btn-primary me-2">Edit</a>';
                $btnDelete = '<form class="me-2" action="' . route('staff.promos.delete', $data['id']) . '" method="POST">' .
                    csrf_field() .
                    method_field('DELETE') .
                    '<button type="submit" class="btn btn-danger">Hapus</button>
                    </form>';

                return '<div class="d-flex justify-content-center">' . $btnEdit . $btnDelete . '</div>';
            })
            ->rawColumns(['buttons'])
            ->make(true);
    }

}
