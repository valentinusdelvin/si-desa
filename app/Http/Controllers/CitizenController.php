<?php

namespace App\Http\Controllers;

use App\Models\Citizen;
use Illuminate\Http\Request;

class CitizenController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->isAdmin()) {
            $citizens = Citizen::with('user')->get();
        } else {
            $citizens = Citizen::where('user_id', $request->user()->id)->get();
        }

        return response()->json([
            'success' => true,
            'data' => $citizens,
        ]);
    }

    public function store(Request $request)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Hanya admin yang bisa menambah data warga.',
            ], 403);
        }

        $request->validate([
            'nik' => 'required|string|size:16|unique:citizens,nik',
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'no_hp' => 'nullable|string|max:15',
            'status_perkawinan' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $citizen = Citizen::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Data warga berhasil ditambahkan',
            'data' => $citizen,
        ], 201);
    }

    public function show(Request $request, $id)
    {
        $citizen = Citizen::with(['dues', 'complaints', 'mails'])->find($id);

        if (!$citizen) {
            return response()->json([
                'success' => false,
                'message' => 'Data warga tidak ditemukan',
            ], 404);
        }

        if (!$request->user()->isAdmin() && $citizen->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $citizen,
        ]);
    }

    public function update(Request $request, $id)
    {
        $citizen = Citizen::find($id);

        if (!$citizen) {
            return response()->json([
                'success' => false,
                'message' => 'Data warga tidak ditemukan',
            ], 404);
        }

        if (!$request->user()->isAdmin() && $citizen->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak',
            ], 403);
        }

        $request->validate([
            'nik' => 'sometimes|string|size:16|unique:citizens,nik,' . $id,
            'nama' => 'sometimes|string|max:255',
            'alamat' => 'sometimes|string',
            'jenis_kelamin' => 'sometimes|in:L,P',
            'tanggal_lahir' => 'sometimes|date',
            'no_hp' => 'nullable|string|max:15',
            'status_perkawinan' => 'nullable|string',
        ]);

        $citizen->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Data warga berhasil diperbarui',
            'data' => $citizen,
        ]);
    }

    public function destroy(Request $request, $id)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Hanya admin yang bisa menghapus data warga.',
            ], 403);
        }

        $citizen = Citizen::find($id);

        if (!$citizen) {
            return response()->json([
                'success' => false,
                'message' => 'Data warga tidak ditemukan',
            ], 404);
        }

        $citizen->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data warga berhasil dihapus',
        ]);
    }
}