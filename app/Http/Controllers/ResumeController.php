<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ResumeRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ResumeResource;
use Illuminate\Support\Arr;

class ResumeController extends Controller
{
    // Afficher tous les CVs d'un user
    public function index()
    {
        $cvs = DB::table('cvs')->where('user_id', Auth::id())->orWhere('user_email', Auth::user()->email)->get();
        return ResumeResource::collection($cvs);
    }

    // Afficher un CV
    public function getResume($resumeId)
    {
        $cv = DB::table('cvs')->where('resume_id', $resumeId)->firstOrFail();
        return new ResumeResource($cv);
    }

    // Créer un nouveau CV
    public function store(ResumeRequest $request): JsonResponse
    {
        $request->validated();

        try {
            $cv = null;
            DB::transaction(function () use ($request, &$cv) {
                $cvId = DB::table('cvs')->insertGetId([
                    'user_id' => $request->user()->id,
                    'user_email' =>  $request->user()->email,
                    'resume_id' => Str::uuid(),
                    'title' => $request->title,
                    'theme_color' => $request->theme_color,
                    'sections' => $request->sections,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Récupérer les données complètes du CV nouvellement créé
                $cv = DB::table('cvs')->where('id', $cvId)->first();
            });

            return response()->json([
                'success' => 'CV créé avec succès.',
                'data' => new ResumeResource($cv),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Mettre à jour un CV
    public function update(ResumeRequest $request,  $resumeId): JsonResponse
    {
        $validated = $request->validated();

        return DB::transaction(function () use ($request, $resumeId, $validated) {
            // Récupérer le CV en fonction du `resume_id`
            $cv = DB::table('cvs')->where('resume_id', $resumeId)->first();

            if (!$cv) {
                return response()->json(['error' => 'CV non trouvé.'], 404);
            }

            // Mettre à jour ou insérer les informations du CV
            DB::table('cv_infos')->updateOrInsert(
                ['cv_id' => $cv->id],
                Arr::except($validated, ['file_path', 'theme_color'])
            );

            // Mettre à jour `file_path` et `theme_color` si présents
            $updateData = Arr::only($validated, ['file_path', 'theme_color']);
            if (!empty($updateData)) {
                DB::table('cvs')->where('resume_id', $resumeId)->update($updateData);
            }

            $resource = new ResumeResource($cv);

            return response()->json([
                'success' => 'CV mis à jour avec succès.',
                ...$resource->toArray($request),
            ]);
        });
    }

    // Supprimer un CV
    public function destroy($resumeId): JsonResponse
    {
        if (!DB::table('cvs')->where('resume_id', $resumeId)->first()) {
            return response()->json(['error' => 'CV non trouvé.'], 404);
        }

        DB::table('cvs')->where('resume_id', $resumeId)->delete();

        return response()->json(['success' => 'CV supprimé avec succès.']);
    }
}
