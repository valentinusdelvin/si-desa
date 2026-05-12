<?php

namespace App\Http\Controllers;

use App\Models\Mail;
use App\Models\Citizen;
use Illuminate\Http\Request;

class MailController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->isAdmin()) {
            $mails = Mail::with('citizen')->get();
        } else {
            $citizen = Citizen::where('user_id', $request->user()->id)->first();

            if (!$citizen) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data warga untuk akun ini belum terdaftar',
                ], 404);
            }

            $mails = Mail::where('citizen_id', $citizen->id)->get();
        }

        return response()->json([
            'success' => true,
            'data' => $mails,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_surat' => 'required|string|max:255',
            'keperluan' => 'required|string',
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

        $mail = Mail::create([
            'citizen_id' => $citizenId,
            'jenis_surat' => $request->jenis_surat,
            'keperluan' => $request->keperluan,
            'status' => 'menunggu',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permohonan surat berhasil diajukan',
            'data' => $mail->load('citizen'),
        ], 201);
    }

    public function show(Request $request, $id)
    {
        $mail = Mail::with('citizen')->find($id);

        if (!$mail) {
            return response()->json([
                'success' => false,
                'message' => 'Data surat tidak ditemukan',
            ], 404);
        }

        if (!$request->user()->isAdmin()) {
            $citizen = Citizen::where('user_id', $request->user()->id)->first();
            if (!$citizen || $mail->citizen_id !== $citizen->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak',
                ], 403);
            }
        }

        return response()->json([
            'success' => true,
            'data' => $mail,
        ]);
    }

    public function update(Request $request, $id)
    {
        $mail = Mail::find($id);

        if (!$mail) {
            return response()->json([
                'success' => false,
                'message' => 'Data surat tidak ditemukan',
            ], 404);
        }

        if ($request->user()->isAdmin()) {
            $request->validate([
                'status' => 'sometimes|in:menunggu,diproses,selesai,ditolak',
                'nomor_surat' => 'nullable|string',
                'catatan' => 'nullable|string',
                'jenis_surat' => 'sometimes|string|max:255',
                'keperluan' => 'sometimes|string',
            ]);

            $mail->update($request->all());
        } else {
            $citizen = Citizen::where('user_id', $request->user()->id)->first();
            if (!$citizen || $mail->citizen_id !== $citizen->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak',
                ], 403);
            }

            if ($mail->status !== 'menunggu') {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengajuan surat yang sudah diproses tidak bisa diedit',
                ], 422);
            }

            $request->validate([
                'jenis_surat' => 'sometimes|string|max:255',
                'keperluan' => 'sometimes|string',
            ]);

            $mail->update($request->only(['jenis_surat', 'keperluan']));
        }

        return response()->json([
            'success' => true,
            'message' => 'Data surat berhasil diperbarui',
            'data' => $mail,
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $mail = Mail::find($id);

        if (!$mail) {
            return response()->json([
                'success' => false,
                'message' => 'Data surat tidak ditemukan',
            ], 404);
        }

        if (!$request->user()->isAdmin()) {
            $citizen = Citizen::where('user_id', $request->user()->id)->first();
            if (!$citizen || $mail->citizen_id !== $citizen->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak',
                ], 403);
            }

            if ($mail->status !== 'menunggu') {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengajuan surat yang sudah diproses tidak bisa dihapus',
                ], 422);
            }
        }

        $mail->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan surat berhasil dihapus',
        ]);
    }
}