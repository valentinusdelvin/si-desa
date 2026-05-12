<?php

namespace App\Http\Controllers;

use App\Models\Due;
use App\Models\Citizen;
use Illuminate\Http\Request;

class DueController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->isAdmin()) {
            $dues = Due::with('citizen')->get();
        } else {
            $citizen = Citizen::where('user_id', $request->user()->id)->first();

            if (!$citizen) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data warga untuk akun ini belum terdaftar',
                ], 404);
            }

            $dues = Due::where('citizen_id', $citizen->id)->get();
        }

        return response()->json([
            'success' => true,
            'data' => $dues,
        ]);
    }

    public function store(Request $request)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Hanya admin yang bisa mencatat iuran.',
            ], 403);
        }

        $request->validate([
            'citizen_id' => 'required|exists:citizens,id',
            'keterangan' => 'required|string',
            'nominal' => 'required|numeric|min:0',
            'status' => 'required|in:lunas,belum_lunas',
            'tanggal_bayar' => 'nullable|date',
            'bulan' => 'required|string',
        ]);

        $due = Due::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Data iuran berhasil dicatat',
            'data' => $due->load('citizen'),
        ], 201);
    }

    public function show(Request $request, $id)
    {
        $due = Due::with('citizen')->find($id);

        if (!$due) {
            return response()->json([
                'success' => false,
                'message' => 'Data iuran tidak ditemukan',
            ], 404);
        }

        // Warga hanya boleh lihat iuran milik sendiri
        if (!$request->user()->isAdmin()) {
            $citizen = Citizen::where('user_id', $request->user()->id)->first();
            if (!$citizen || $due->citizen_id !== $citizen->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak',
                ], 403);
            }
        }

        return response()->json([
            'success' => true,
            'data' => $due,
        ]);
    }

    public function update(Request $request, $id)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Hanya admin yang bisa mengubah data iuran.',
            ], 403);
        }

        $due = Due::find($id);

        if (!$due) {
            return response()->json([
                'success' => false,
                'message' => 'Data iuran tidak ditemukan',
            ], 404);
        }

        $request->validate([
            'keterangan' => 'sometimes|string',
            'nominal' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|in:lunas,belum_lunas',
            'tanggal_bayar' => 'nullable|date',
            'bulan' => 'sometimes|string',
        ]);

        $due->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Data iuran berhasil diperbarui',
            'data' => $due,
        ]);
    }

    public function destroy(Request $request, $id)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Hanya admin yang bisa menghapus data iuran.',
            ], 403);
        }

        $due = Due::find($id);

        if (!$due) {
            return response()->json([
                'success' => false,
                'message' => 'Data iuran tidak ditemukan',
            ], 404);
        }

        $due->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data iuran berhasil dihapus',
        ]);
    }
}