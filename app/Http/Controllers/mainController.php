<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserFavorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class mainController extends Controller
{
    public function dashboard(Request $request){
        $favorites = auth()->user()->favorites; // فرض بر اینه که رابطه favorites در مدل User تعریف شده
        return view('dashboard' , compact('favorites'));
    }
    public function destroyFavorite(Request $request , $id){
        UserFavorite::destroy($id);
        return redirect('/dashboard');
    }
    public function addToFavorite(Request $request){
        // اعتبارسنجی اولیه
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $featureId = $request->input('feature_id');
        $path = public_path('Sachsen.geojson');
        $geoJson = json_decode(file_get_contents($path), true);

        $target = collect($geoJson['features'])->firstWhere('id', $featureId);
        if (!$target) {
            return response()->json([
                'success' => false,
                'message' => 'Feature not found'
            ], 404);
        }
        $usFavorite = UserFavorite::where("feature_id", $featureId)->first();
        if($usFavorite){
            return response()->json([
                'success' => false,
                'message' => 'Before Adedd'
            ], 404);
        }


        // منطق افزودن به علاقمندی‌ها
        $user = auth()->user();
        $userFavorite = new UserFavorite();
        $userFavorite->user_id = $user->id;
        $userFavorite->feature_id = $featureId;
        $userFavorite->data = json_encode($target);
        $userFavorite->save();

        return response()->json([
            'success' => true,
            'message' => 'Added to favorites'
        ]);
    }
}
