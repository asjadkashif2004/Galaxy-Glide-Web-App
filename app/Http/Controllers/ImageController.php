<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

class ImageController extends Controller
{
    /**
     * PUBLIC: Gallery listing
     * Accessible by everyone (users + guests)
     */
    public function index()
    {
        $images = Image::latest()->get();
        return view('images.index', compact('images'));
    }

    /**
     * ADMIN: Gallery listing (with CRUD controls)
     */
    public function adminIndex()
    {
        $this->authorizeAdmin();

        $images = Image::latest()->get();
        return view('admin.images.index', compact('images'));
    }

    /**
     * Show single image (public)
     */
    public function show(Image $image)
    {
        return view('images.show', compact('image'));
    }

    /**
     * ADMIN: Show upload form
     */
    public function create()
    {
        $this->authorizeAdmin();
        return view('admin.images.create');
    }

    /**
     * ADMIN: Store new image + process Deep Zoom
     */
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'type'        => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'mission'     => 'nullable|string|max:120',
            'nasa_id'     => 'nullable|string|max:120',
            'taken_at'    => 'nullable|date',
            'photo'       => 'required|file|mimetypes:image/jpeg|max:512000',
        ], [
            'photo.mimetypes' => 'Only JPG/JPEG images are allowed.',
        ]);

        $vips = env('VIPS_BIN', 'vips');

        // Save original
        $originalPath = $request->file('photo')->store('originals', 'public');
        $originalAbs  = Storage::disk('public')->path($originalPath);

        // Unique basename
        $basename = Str::slug($request->title ?: pathinfo($originalPath, PATHINFO_FILENAME), '_')
                  . '_' . now()->format('Ymd_His');

        Storage::disk('public')->makeDirectory('tiles');
        Storage::disk('public')->makeDirectory('thumbnails');

        $dziNoExtAbs = Storage::disk('public')->path("tiles/{$basename}");
        $dziRel      = "tiles/{$basename}.dzi";
        $thumbRel    = "thumbnails/{$basename}_thumb.jpg";
        $thumbAbs    = Storage::disk('public')->path($thumbRel);

        // Upright orientation
        $uprightAbs = Storage::disk('public')->path("originals/{$basename}_upright.jpg");
        $procFix = new Process([$vips, 'copy', $originalAbs, $uprightAbs, '--autorotate']);
        $procFix->setTimeout(0)->run();
        if (!$procFix->isSuccessful()) $uprightAbs = $originalAbs;

        // Generate DZI
        $procDzi = new Process([
            $vips,'dzsave',$uprightAbs,$dziNoExtAbs,
            '--tile-size','512','--overlap','1','--suffix','.jpg[Q=92,subsample=off,strip]'
        ]);
        $procDzi->setTimeout(0)->run();
        if (!$procDzi->isSuccessful()) {
            $fallback = new Process([$vips,'dzsave',$uprightAbs,$dziNoExtAbs,'--tile-size','256','--overlap','1','--suffix','.jpg']);
            $fallback->setTimeout(0)->run();
            if (!$fallback->isSuccessful()) {
                $err = trim($procDzi->getErrorOutput().PHP_EOL.$fallback->getErrorOutput());
                return back()->withErrors(['photo'=>"Deep Zoom conversion failed.\n{$err}"])->withInput();
            }
        }

        // Generate thumbnail
        $procThumb = new Process([$vips,'thumbnail',$uprightAbs,$thumbAbs,'1280','--crop','centre']);
        $procThumb->setTimeout(0)->run();
        $thumbPathForDb = $procThumb->isSuccessful() ? $thumbRel : null;

        // Save record
        Image::create([
            'title'          => $data['title'],
            'type'           => $data['type'] ?? null,
            'description'    => $data['description'] ?? null,
            'mission'        => $data['mission'] ?? null,
            'nasa_id'        => $data['nasa_id'] ?? null,
            'taken_at'       => $data['taken_at'] ?? null,
            'thumbnail_path' => $thumbPathForDb,
            'dzi_path'       => $dziRel,
        ]);

        return redirect()->route('admin.images.index')
            ->with('status', 'Image uploaded & converted to Deep Zoom.');
    }

    /**
     * ADMIN: Delete image
     */
    public function destroy(Image $image)
    {
        $this->authorizeAdmin();

        if ($image->dzi_path) {
            $base = pathinfo($image->dzi_path, PATHINFO_FILENAME);
            Storage::disk('public')->delete($image->dzi_path);
            Storage::disk('public')->deleteDirectory("tiles/{$base}_files");
        }
        if (!empty($image->thumbnail_path)) {
            Storage::disk('public')->delete($image->thumbnail_path);
        }

        $image->delete();

        return back()->with('status', 'Image deleted.');
    }

    /**
     * Private helper to block non-admins
     */
    private function authorizeAdmin()
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized: Admins only.');
        }
    }
}
