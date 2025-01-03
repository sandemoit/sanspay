<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class AnnouncementController extends Controller
{
    public function index()
    {
        return view('admin.announcement');
    }

    public function getData()
    {
        $product = Announcement::get();

        return DataTables::of($product)
            ->addColumn('action', function ($row) {
                return '
                            <a class="btn btn-sm btn-success" href="' . url('/announcement/' . $row->slug) . '"><ion-icon name="link-outline"></ion-icon></a>

                            <a class="btn btn-sm btn-danger delete-btn" href="' . url('/admin/announcement/delete/' . base64_encode($row->id)) . '" data-id="' . base64_encode($row->id) . '"><ion-icon name="trash-outline"></ion-icon></a>
                        ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store()
    {
        try {
            $validate = request()->validate([
                'title' => 'required|string|max:255',
                'viewer' => 'required|in:all,admin,mitra,customer',
                'type' => 'required|in:static,banner',
                'description' => 'required|string',
            ]);

            // create slug from title
            $slug = Str::slug($validate['title']);

            $announcement = Announcement::create([
                'title' => $validate['title'],
                'slug' => $slug,
                'viewer' => $validate['viewer'],
                'type' => $validate['type'],
                'description' => strip_tags($validate['description']),
            ]);

            if (request()->hasFile('image')) {
                $imagePath = request()->file('image')->store('images/announcement', 'public');
                $announcement->image = 'storage/' . $imagePath; // Path publik yang bisa diakses langsung
                $announcement->save();
            }

            return redirect()->back()->with('success', __('Data created successfully'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __($e->getMessage()));
        }
    }

    public function destroy($id)
    {
        try {
            $id = base64_decode($id);
            $announcement = Announcement::find($id);
            if ($announcement) {
                if ($announcement->image) {
                    $imagePath = public_path('storage/images/announcement/' . $announcement->image);
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
                $announcement->delete();
                return response()->json(['success' => __('Data deleted successfully')]);
            }
            return response()->json(['error' => __('Data not found')]);
        } catch (\Exception $e) {
            return response()->json(['error' => __($e->getMessage())]);
        }
    }
}
