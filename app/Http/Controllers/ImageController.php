<?php

namespace App\Http\Controllers;



use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

class ImageController extends Controller
{
    /** -------------------- PUBLIC -------------------- */
    public function index()
    {
        $images = Image::latest()->get();
        return view('images.index', compact('images'));
    }

    public function show(Image $image)
    {
        return view('images.show', compact('image'));
    }

    /** -------------------- ADMIN -------------------- */
    public function adminIndex()
    {
        $this->authorizeAdmin();
        $images = Image::latest()->get();
        return view('admin.images.index', compact('images'));
    }

    public function create()
    {
        $this->authorizeAdmin();
        return view('admin.images.create');
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'type'        => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'photo'       => 'required|file|mimetypes:image/jpeg|max:512000',
        ], [
            'photo.mimetypes' => 'Only JPG/JPEG images are allowed.',
        ]);

        // Process and save new image
        $this->processAndSaveImage($request->file('photo'), $data, true);

        return redirect()
            ->route('admin.images.index')
            ->with('status', '🛰 Image uploaded successfully with Deep Zoom + thumbnail.');
    }

    public function edit(Image $image)
    {
        $this->authorizeAdmin();
        return view('admin.images.edit', compact('image'));
    }

    public function update(Request $request, Image $image)
    {
        $this->authorizeAdmin();

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'nullable|string|max:50',
            'image' => 'nullable|file|mimetypes:image/jpeg|max:512000',
        ], [
            'image.mimetypes' => 'Only JPG/JPEG images are allowed.',
        ]);

        // Update metadata
        $image->title = $data['title'];
        $image->description = $data['description'] ?? $image->description;
        $image->type = $data['type'] ?? $image->type;

        // Replace if new image uploaded
        if ($request->hasFile('image')) {
            if ($image->dzi_path) {
                $base = pathinfo($image->dzi_path, PATHINFO_FILENAME);
                Storage::disk('public')->delete($image->dzi_path);
                Storage::disk('public')->deleteDirectory("tiles/{$base}_files");
            }
            if ($image->thumbnail_path) {
                Storage::disk('public')->delete($image->thumbnail_path);
            }

            $processed = $this->processAndSaveImage($request->file('image'), $data, false);
            $image->thumbnail_path = $processed['thumbnail_path'];
            $image->dzi_path = $processed['dzi_path'];
        }

        $image->save();

        return redirect()
            ->route('admin.images.index')
            ->with('status', '🪐 Image updated and replaced successfully!');
    }

    public function destroy(Image $image)
    {
        $this->authorizeAdmin();

        if ($image->dzi_path) {
            $base = pathinfo($image->dzi_path, PATHINFO_FILENAME);
            Storage::disk('public')->delete($image->dzi_path);
            Storage::disk('public')->deleteDirectory("tiles/{$base}_files");
        }

        if ($image->thumbnail_path) {
            Storage::disk('public')->delete($image->thumbnail_path);
        }

        $image->delete();

        return back()->with('status', '🗑 Image deleted successfully.');
    }

    private function authorizeAdmin()
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized: Admins only.');
        }
    }

    /**
     * Common Deep Zoom + Thumbnail generation logic
     */
    private function processAndSaveImage($file, $data, $createDb = true)
    {
        $vips = env('VIPS_BIN', 'vips');

        $originalPath = $file->store('originals', 'public');
        $originalAbs  = Storage::disk('public')->path($originalPath);

        $basename = Str::slug($data['title'] ?: pathinfo($originalPath, PATHINFO_FILENAME), '_') . '_' . now()->format('Ymd_His');

        Storage::disk('public')->makeDirectory('tiles');
        Storage::disk('public')->makeDirectory('thumbnails');

        $dziNoExtAbs = Storage::disk('public')->path("tiles/{$basename}");
        $dziRel      = "tiles/{$basename}.dzi";
        $thumbRel    = "thumbnails/{$basename}_thumb.jpg";
        $thumbAbs    = Storage::disk('public')->path($thumbRel);

        // Fix rotation
        $uprightAbs = Storage::disk('public')->path("originals/{$basename}_upright.jpg");
        $procFix = new Process([$vips, 'copy', $originalAbs, $uprightAbs, '--autorotate']);
        $procFix->setTimeout(0)->run();
        if (!$procFix->isSuccessful()) $uprightAbs = $originalAbs;

        // Deep Zoom
        $procDzi = new Process([
            $vips, 'dzsave', $uprightAbs, $dziNoExtAbs,
            '--tile-size', '512', '--overlap', '1',
            '--suffix', '.jpg[Q=92,subsample=off,strip]'
        ]);
        $procDzi->setTimeout(0)->run();

        if (!$procDzi->isSuccessful()) {
            $fallback = new Process([$vips, 'dzsave', $uprightAbs, $dziNoExtAbs, '--tile-size', '256', '--overlap', '1', '--suffix', '.jpg']);
            $fallback->setTimeout(0)->run();
            if (!$fallback->isSuccessful()) {
                $err = trim($procDzi->getErrorOutput() . PHP_EOL . $fallback->getErrorOutput());
                throw new \Exception("Deep Zoom conversion failed: {$err}");
            }
        }

        // Thumbnail
        $procThumb = new Process([$vips, 'thumbnail', $uprightAbs, $thumbAbs, '600', '--crop', 'centre']);
        $procThumb->setTimeout(0)->run();
        $thumbPathForDb = $procThumb->isSuccessful() ? $thumbRel : null;

        if ($createDb) {
            Image::create([
                'title'          => $data['title'],
                'type'           => $data['type'] ?? null,
                'description'    => $data['description'] ?? null,
                'thumbnail_path' => $thumbPathForDb,
                'dzi_path'       => $dziRel,
            ]);
        }

        return [
            'thumbnail_path' => $thumbPathForDb,
            'dzi_path'       => $dziRel,
        ];
    }
}
