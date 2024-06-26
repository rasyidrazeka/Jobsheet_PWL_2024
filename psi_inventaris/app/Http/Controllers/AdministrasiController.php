<?php

namespace App\Http\Controllers;

use App\Models\AdministrasiModel;
use App\Models\BarangModel;
use App\Models\LevelModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class AdministrasiController extends Controller
{
    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Daftar Administrasi',
            'list' => ['Home', 'Administrasi']
        ];
        $page = (object)[
            'title' => 'Berikut ini merupakan data administrasi dari sistem informasi inventaris BHP JTI POLINEMA'
        ];
        $activeMenu = 'administrasi';
        $level = LevelModel::all();
        $notifBarang = BarangModel::all();
        $stokKurang = BarangModel::where('volume', '<=', 1)->get();
        return view('administrasi.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'notifBarang' => $notifBarang, 'stokKurang' => $stokKurang, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request)
    {
        $users = AdministrasiModel::select('user_id', 'nama', 'nik', 'jabatan'); 
        if($request->level_id) {
            $users->where('level_id', $request->level_id);
        }
        return DataTables::of($users)
            ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex) 
            ->addColumn('aksi', function ($user) { // menambahkan kolom aksi  
                if (auth()->user()->level_id==1) {
                    $btn = '<a href="'.url('/administrasi/' . $user->user_id).'" class="btn btn-info btn-sm" id="btn-detail"><i class="fa-solid fa-circle-info"></i>&nbsp;&nbsp;Detail</a> '; 
                    $btn .= '<a href="'.url('/administrasi/' . $user->user_id . '/edit').'" class="btn btn-warning btn-sm" style="color: white" id="btn-edit"><i class="fa-solid fa-pen-to-square"></i>&nbsp;&nbsp;Edit</a> '; 
                    $btn .= '<form class="d-inline-block" id="btn-hapus" method="POST" action="'. url('/administrasi/'.$user->user_id).'">' . csrf_field() . method_field('DELETE') . '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakit menghapus data ini?\');"><i class="fa-solid fa-trash-can"></i>&nbsp;&nbsp;Hapus</button></form>'; 
                }elseif (auth()->user()->level_id==2) {
                    $btn = '<a href="'.url('/administrasi/' . $user->user_id).'" class="btn btn-info btn-sm"><i class="fa-solid fa-circle-info"></i>&nbsp;&nbsp;Detail</a> '; 
                }
            return $btn; 
        })
        ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html 
        ->make(true);
    }

    public function create()
    {
        $breadcrumb = (object)[
            'title' => 'Formulir Data Administrasi',
            'list' => ['Home', 'Administrasi', 'Create']
        ];

        $page = (object)[
            'title' => "Tambah data administrasi baru"
        ];

        $level = LevelModel::all();

        $activeMenu = 'administrasi';

        $notifBarang = BarangModel::all();

        $stokKurang = BarangModel::where('volume', '<=', 1)->get();

        return view('administrasi.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'notifBarang' => $notifBarang, 'stokKurang' => $stokKurang, 'activeMenu' => $activeMenu]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|min:3|unique:user,username',
            'nama' => 'required|string|max:100',
            'nik' => 'required|integer',
            'jabatan' => 'required|string|max:100',
            'password' => 'required|min:5',
            'level_id' => 'required|integer'
        ]);

        AdministrasiModel::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'nik' => $request->nik,
            'jabatan' => $request->jabatan,
            'password' => bcrypt($request->password),
            'level_id' => $request->level_id
        ]);

        Alert::toast('Data administrasi berhasil ditambahkan', 'success');
        return redirect('/administrasi');
    }

    public function show(string $id){
        $user = AdministrasiModel::with('level')->find($id);

        $breadcrumb = (object) [
            'title' => 'Detail Administrasi',
            'list' =>['Home', 'Administrasi', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail Administrasi'
        ];

        $activeMenu = 'administrasi';

        $notifBarang = BarangModel::all();

        $stokKurang = BarangModel::where('volume', '<=', 1)->get();

        return view('administrasi.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'stokKurang' => $stokKurang, 'user' => $user, 'notifBarang' => $notifBarang, 'activeMenu' => $activeMenu]);
    }

    public function edit(string $id)
    {
        $user = AdministrasiModel::find($id);

        $level = LevelModel::all();

        $breadcrumb = (object)[
            'title' => 'Edit Administrasi',
            'list' => ['Home', 'Administrasi', 'Edit']
        ];

        $page = (object)[
            'title' => 'Edit administrasi'
        ];

        $activeMenu = 'administrasi';

        $notifBarang = BarangModel::all();

        $stokKurang = BarangModel::where('volume', '<=', 1)->get();

        return view('administrasi.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'stokKurang' => $stokKurang, 'user' => $user, 'level' => $level, 'notifBarang' => $notifBarang, 'activeMenu' => $activeMenu]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'username' => 'required|string|min:3|unique:user,username,'.$id.',user_id',
            'nama' => 'required|string|max:100',
            'nik' => 'required|integer',
            'jabatan' => 'required|string|max:100',
            'password' => 'nullable|min:5',
            'level_id' => 'required|integer'
        ]);

        AdministrasiModel::find($id)->update([
            'username' => $request->username,
            'nama' => $request->nama,
            'nik' => $request->nik,
            'jabatan' => $request->jabatan,
            'password' => $request->password ? bcrypt($request->password) : AdministrasiModel::find($id)->password,
            'level_id' => $request->level_id
        ]);
        Alert::toast('Data administrasi berhasil diubah', 'success');
        return redirect('/administrasi');
    }

    public function destroy(string $id)
    {
        $check = AdministrasiModel::find($id);
        if(!$check){
            Alert::toast('Data administrasi tidak ditemukan', 'error');
            return redirect('/administrasi');
        }
        try{
            AdministrasiModel::destroy($id);
            Alert::toast('Data administrasi berhasil dihapus', 'success');
            return redirect('/administrasi');
        }catch(\Illuminate\Database\QueryException $e){
            Alert::toast('Data administrasi gagal dihapus', 'error');
            return redirect('/administrasi');
        }
    }
}
