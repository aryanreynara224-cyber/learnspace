<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\LessonNote;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    /**
     * Dapatkan daftar catatan user untuk lesson ini
     */
    public function index(Lesson $lesson)
    {
        $user = auth()->user();

        $notes = LessonNote::where('user_id', $user->id)
            ->where('lesson_id', $lesson->id)
            ->orderByRaw('timestamp_seconds IS NULL, timestamp_seconds ASC')
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'notes'  => $notes->map(function ($note) {
                return [
                    'id'                  => $note->id,
                    'content'             => $note->content,
                    'timestamp_seconds'   => $note->timestamp_seconds,
                    'formatted_timestamp' => $note->formatted_timestamp,
                    'created_at'          => $note->created_at->format('d M Y, H:i'),
                ];
            }),
        ]);
    }

    /**
     * Simpan catatan baru
     */
    public function store(Request $request, Lesson $lesson)
    {
        $user = auth()->user();

        $request->validate([
            'content' => ['required', 'string', 'max:5000'],
        ]);

        // Parsing waktu yang fleksibel: mendukung integer detik (90) atau format menit (01:30)
        $timestampSeconds = null;
        $rawTime = $request->input('timestamp') ?? $request->input('timestamp_seconds');

        if (!empty($rawTime)) {
            $rawTime = trim((string) $rawTime);
            if (str_contains($rawTime, ':')) {
                $parts = explode(':', $rawTime);
                if (count($parts) === 2) {
                    $timestampSeconds = ((int)$parts[0] * 60) + (int)$parts[1];
                } elseif (count($parts) === 3) {
                    $timestampSeconds = ((int)$parts[0] * 3600) + ((int)$parts[1] * 60) + (int)$parts[2];
                }
            } elseif (is_numeric($rawTime)) {
                $timestampSeconds = (int)$rawTime;
            }
        }

        try {
            $note = LessonNote::create([
                'user_id'           => $user->id,
                'lesson_id'         => $lesson->id,
                'content'           => trim($request->input('content')),
                'timestamp_seconds' => $timestampSeconds,
            ]);

            $payload = [
                'status'  => 'success',
                'message' => 'Catatan berhasil disimpan!',
                'note'    => [
                    'id'                  => $note->id,
                    'content'             => $note->content,
                    'timestamp_seconds'   => $note->timestamp_seconds,
                    'formatted_timestamp' => $note->formatted_timestamp,
                    'created_at'          => $note->created_at->format('d M Y, H:i'),
                ],
            ];

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json($payload);
            }

            return back()->with('success', 'Catatan berhasil disimpan!');
        } catch (\Throwable $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Gagal menyimpan catatan: ' . $e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Gagal menyimpan catatan.');
        }
    }

    /**
     * Hapus catatan
     */
    public function destroy(LessonNote $note)
    {
        $user = auth()->user();
        abort_if($note->user_id !== $user->id, 403);

        $note->delete();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'status'  => 'success',
                'message' => 'Catatan berhasil dihapus!',
            ]);
        }

        return back()->with('success', 'Catatan berhasil dihapus!');
    }
}
