<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    public function tagDashboard()
    {
        return view('Admin.tagDashboard');
    }

    public function tagDashboardData()
    {
        $tags = Tag::orderBy('id', 'desc')->get();
        return response()->json(['success' => true, 'data' => $tags]);
    }

    public function addTag(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'tag_name' => 'required|unique:tags,tag_name'
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'msg' => 'Duplicate Tags are not allowed']);
            }

            $tagId = Tag::insertGetId([
                'tag_name' => $request->tag_name
            ]);

            return response()->json(['success' => true, 'msg' => 'Tag successfully added']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function deleteTag(Request $request)
    {
        try {
            $questionModel = Tag::find($request->id);
            $questionModel->delete();

            return response()->json(['success' => true, 'msg' => 'Tag Deleted Successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }
}
