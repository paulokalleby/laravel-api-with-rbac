<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Services\ProjectService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct(protected ProjectService $project) {}

    public function index(Request $request)
    {
        return ProjectResource::collection(
            $this->project->getAllProjects((array) $request->all())
        );
    }

    public function store(ProjectRequest $request)
    {
        return new ProjectResource(
            $this->project->createProject(
                (array) $request->validated()
            )
        );
    }

    public function show(string $id)
    {
        return new ProjectResource(
            $this->project->findProjectById($id)
        );
    }

    public function update(ProjectRequest $request, string $id)
    {
        return new ProjectResource(
            $this->project->updateProject(
                (array) $request->validated(),
                $id
            )
        );
    }

    public function destroy(string $id)
    {
        $this->project->deleteProject($id);

        return response()->noContent();
    }
}
