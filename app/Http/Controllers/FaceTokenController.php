<?php

namespace App\Http\Controllers;

use App\Models\FaceToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FaceTokenController extends Controller
{

    public function index()
    {
        return view('mahasiswa.registrasi');
    }
    public function store(Request $request)
    {
        $request->validate([
            'face_vector' => 'required|array'
        ]);

        try {
            FaceToken::updateOrCreate(
                ['id_user' => Auth::id()],
                [
                    'descriptor' => json_encode($request->face_vector) 
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Registrasi wajah berhasil dilakukan.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data wajah: ' . $e->getMessage()
            ], 500);
        }
    }
}
