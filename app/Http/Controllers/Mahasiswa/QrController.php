<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrController extends Controller
{
    /**
     * Generate or regenerate QR Code for the student's portfolio.
     */
    public function generate()
    {
        $mahasiswa = Auth::user()->mahasiswa;

        if (!$mahasiswa) {
            abort(404, 'Profil Mahasiswa tidak ditemukan.');
        }

        // Generate URL: GET /p/{slug}?ref=qr
        $url = url('/p/' . $mahasiswa->slug . '?ref=qr');

        // File name and path
        $fileName = 'qr_' . $mahasiswa->slug . '_' . time() . '.svg';
        $path = 'qrcodes/' . $fileName;

        // Ensure directory exists
        if (!Storage::disk('public')->exists('qrcodes')) {
            Storage::disk('public')->makeDirectory('qrcodes');
        }

        // Generate QR code image (SVG doesn't require Imagick/GD extensions)
        $image = QrCode::format('svg')
            ->size(300)
            ->margin(1)
            ->generate($url);

        // Check if already exists, delete old one
        $qrPortfolio = $mahasiswa->qrPortfolio;

        if ($qrPortfolio) {
            if (Storage::disk('public')->exists($qrPortfolio->qr_path)) {
                Storage::disk('public')->delete($qrPortfolio->qr_path);
            }
            
            // Save new file
            Storage::disk('public')->put($path, $image);
            
            // Update record
            $qrPortfolio->update([
                'qr_path' => $path,
            ]);
        } else {
            // Save new file
            Storage::disk('public')->put($path, $image);

            // Create record
            $mahasiswa->qrPortfolio()->create([
                'qr_path' => $path,
            ]);
        }

        return back()->with('success', 'QR Code berhasil dibuat/diperbarui.');
    }
}
