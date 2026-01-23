<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\MediaRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Media;

class MediaController extends Controller
{
    protected $mediaRepo;
    public function __construct(MediaRepository $mediaRepository)
    {
        $this->mediaRepo = $mediaRepository;
    }
    public function index()
    {
        $medias = Media::latest('id')->paginate(12);
        return view('dashboard.media.index', compact('medias'));
    }

    public function add()
    {
        return view('dashboard.media.add');
    }

    public function store(Request $request)
    {
        // Important: Check for 'files' as an array because of your HTML input name="files[]"
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            $uploadCount = 0;

            foreach ($files as $file) {
                // Validate each file size again if needed
                if ($file->getSize() <= 2048 * 1024) {
                    $this->mediaRepo->storeByRequest($file, 'media');
                    $uploadCount++;
                }
            }

            if ($uploadCount > 0) {
                return redirect()->route('admin.media')->with('success', $uploadCount . ' media uploaded successfully.');
            }
        }

        return redirect()->back()->with('error', 'Failed to upload media. Ensure files are under 2MB.');
    }

    public function edit($id)
    {
        $media = Media::findOrFail($id);
        return view('dashboard.media.edit', compact('media'));
    }

    public function update(Request $request, $id)
    {
        $media = Media::findOrFail($id);
        $request->validate([
            'id' => 'required|integer|exists:media,id',
            'name' => 'required|string|max:100',
            'alt_text' => 'required|string|max:100',
            'description' => 'nullable|string|max:200',
        ]);

        $media = $this->mediaRepo->updateByRequest($request->all(), $media);

        if ($media) {
            return redirect()->route('admin.media.edit', ['id' => $media->id])->with('success', 'Media updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to update media.');
        }
    }

    public function destroy(Media $id)
    {
        $deleted = $id->delete();
        if ($deleted) {
            return redirect()->route('admin.media')->with('success', 'Media deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to delete media.');
        }
    }

    public function getGalleryAjax()
    {
        $allMedia = Media::latest('id')->paginate(24);
        return view('dashboard.media.partials.gallery_list', compact('allMedia'))->render();
        // return response()->json($allMedia);
    }

    public function ajaxStore(Request $request)
    {
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            $uploadCount = 0;

            foreach ($files as $file) {
                // Validate each file size again if needed
                if ($file->getSize() <= 2048 * 1024) {
                    $this->mediaRepo->storeByRequest($file, 'media');
                    $uploadCount++;
                }
            }

            if ($uploadCount > 0) {
                return response()->json(['success' => true, 'message' => $uploadCount . ' media uploaded successfully.']);
            }
        }

        return response()->json(['success' => false, 'message' => 'Failed to upload media. Ensure files are under 2MB.']);
    }
}