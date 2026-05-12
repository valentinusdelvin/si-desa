<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Citizen;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->isAdmin()) {
            $complaints = Complaint::with('citizen')->get();
        } else {
            $citizen = Citizen::where('user_id', $request->user()->id)->first();

            if (!$citizen) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data warga untuk akun ini belum terdaftar',
                ], 404);
            }

            $complaints = Complaint::where('citizen_id', $citizen->id)->get();
        }

        return response()->json([
            'success' => true,
            'data' => $complaints,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi_aduan' => 'required|string',
            'kategori' => 'required|in:infrastruktur,keamanan,kebersihan,sosial,lainnya',
            'citizen_id' => 'nullable|exists:citizens,id',
        ]);

        if (!$request->user()->isAdmin()) {
            $citizen = Citizen::where('user_id', $request->user()->id)->first();

            if (!$citizen) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun kamu belum terhubung ke data warga. Hubungi admin.',
                ], 403);
            }

            $citizenId = $citizen->id;
        } else {
            $request->validate(['citizen_id' => 'required|exists:citizens,id']);
            $citizenId = $request->citizen_id;
        }

        $complaint = Complaint::create([
            'citizen_id' => $citizenId,
            'judul' => $request->judul,
            'isi_aduan' => $request->isi_aduan,
            'kategori' => $request->kategori,
            'status' => 'menunggu',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Aduan berhasil dikirim',
            'data' => $complaint->load('citizen'),
        ], 201);
    }

    public function show(Request $request, $id)
    {
        $complaint = Complaint::with('citizen')->find($id);

        if (!$complaint) {
            return response()->json([
                'success' => false,
                'message' => 'Aduan tidak ditemukan',
            ], 404);
        }

        if (!$request->user()->isAdmin()) {
            $citizen = Citizen::where('user_id', $request->user()->id)->first();
            if (!$citizen || $complaint->citizen_id !== $citizen->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak',
                ], 403);
            }
        }

        return response()->json([
            'success' => true,
            'data' => $complaint,
        ]);
    }


    public function update(Request $request, $id)
    {
        $complaint = Complaint::find($id);

        if (!$complaint) {
            return response()->json([
                'success' => false,
                'message' => 'Aduan tidak ditemukan',
            ], 404);
        }

        if ($request->user()->isAdmin()) {
            $request->validate([
                'status' => 'sometimes|in:menunggu,diproses,selesai,ditolak',
                'catatan_admin' => 'nullable|string',
                'judul' => 'sometimes|string|max:255',
                'isi_aduan' => 'sometimes|string',
                'kategori' => 'sometimes|in:infrastruktur,keamanan,kebersihan,sosial,lainnya',
            ]);

            $complaint->update($request->all());
        } else {
            $citizen = Citizen::where('user_id', $request->user()->id)->first();
            if (!$citizen || $complaint->citizen_id !== $citizen->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak',
                ], 403);
            }

            if ($complaint->status !== 'menunggu') {
                return response()->json([
                    'success' => false,
                    'message' => 'Aduan yang sudah diproses tidak bisa diedit',
                ], 422);
            }

            $request->validate([
                'judul' => 'sometimes|string|max:255',
                'isi_aduan' => 'sometimes|string',
                'kategori' => 'sometimes|in:infrastruktur,keamanan,kebersihan,sosial,lainnya',
            ]);

            $complaint->update($request->only(['judul', 'isi_aduan', 'kategori']));
        }

        return response()->json([
            'success' => true,
            'message' => 'Aduan berhasil diperbarui',
            'data' => $complaint,
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $complaint = Complaint::find($id);

        if (!$complaint) {
            return response()->json([
                'success' => false,
                'message' => 'Aduan tidak ditemukan',
            ], 404);
        }

        if (!$request->user()->isAdmin()) {
            $citizen = Citizen::where('user_id', $request->user()->id)->first();
            if (!$citizen || $complaint->citizen_id !== $citizen->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak',
                ], 403);
            }

            if ($complaint->status !== 'menunggu') {
                return response()->json([
                    'success' => false,
                    'message' => 'Aduan yang sudah diproses tidak bisa dihapus',
                ], 422);
            }
        }

        $complaint->delete();

        return response()->json([
            'success' => true,
            'message' => 'Aduan berhasil dihapus',
        ]);
    }
}