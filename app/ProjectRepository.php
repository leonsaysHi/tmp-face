<?php

namespace App;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class ProjectRepository extends SalesForceRepository
{
    /**
     * @param array $project
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function createOrEdit($project = [])
    {
        $guid = Auth::user()->guid;
        return $this->post(config('endpoints.create_edit_project'), compact('guid', 'project'));
    }

    /**
     * @param string $projectId
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get($projectId = '')
    {
        $project = [
            "projectSfdcId" => $projectId
        ];
        $guid = Auth::user()->guid;
        $response = $this->post(config('endpoints.get_project'), compact('guid', 'project'));

        return $this->filterThroughConditions($response);
    }

    /**
     * @param array $payload
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function cloneRecord($payload = [])
    {
        $cloneTimes = Arr::get($payload, 'cloneTimes', '');
        $projectSfdcIds = Arr::get($payload, 'projectSfdcIds', []);
        $guid = Auth::user()->guid;
        return $this->post(
            config('endpoints.clone_project'),
            compact('guid', 'projectSfdcIds', 'cloneTimes')
        );
    }

    /**
     * Filter the project through the states of the project
     * and apply the permission logic, returning error or the project.
     *
     * @param $response
     * @return array|\Illuminate\Http\JsonResponse|mixed
     */
    private function filterThroughConditions($response)
    {
        if (isset($response['projects'])) {
            switch ($response['projects'][0]['status']) {
                case 'Draft':
                    return $this->draft($response);
                    break;
                case 'Pending':
                    return $this->pending($response);
                    break;
                case 'Active':
                    return $this->active($response);
                    break;
                case 'Completed':
                    return $this->completed($response);
                    break;
                case 'Cancelled':
                    return $this->canceled($response);
                    break;
                default:
                    return [];
            }
        }

        return [];
    }

    /**
     * Return the correct response for given conditions on draft projects.
     *
     * @param $response
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    private function draft($response)
    {
        $project = $response['projects'][0];

        if ($this->isReadOnlyUser()) {
            return $this->returnUnauthorisedError();
        };

        if ($project['isOwner']) {
            return $this->returnProject($response);
        }

        return $this->returnUnauthorisedError();
    }

    /**
     * Return the correct response for given conditions on pending projects.
     *
     * @param $response
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    private function pending($response)
    {
        if ($this->isReadOnlyUser()) {
            return $this->returnUnauthorisedError();
        };
        $project = $response['projects'][0];

        if ($this->isConfidential($project)) {
            if ($project['isOwner'] || $project['isDelegate']) {
                return $this->returnProject($response);
            } else {
                return$this->returnUnauthorisedError();
            }
        }

        return $this->returnProject($response);
    }

    /**
     * Return the correct response for given conditions on active projects.
     *
     * @param $response
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    private function active($response)
    {
        $project = $response['projects'][0];

        if ($this->isConfidential($project)) {
            if ($this->isReadOnlyUser()) {
                return $this->returnUnauthorisedError();
            };

            if ($project['isOwner'] || $project['isDelegate']) {
                return $this->returnProject($response);
            } else {
                return$this->returnUnauthorisedError();
            }
        }
        return $this->returnProject($response);
    }

    /**
     * Return the correct response for given conditions on completed projects.
     *
     * @param $response
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    private function completed($response)
    {
        $project = $response['projects'][0];

        if ($this->isConfidential($project)) {
            if ($this->isReadOnlyUser()) {
                return $this->returnUnauthorisedError();
            }

            if ($project['isOwner'] || $project['isDelegate']) {
                return $this->returnProject($response);
            } else {
                return$this->returnUnauthorisedError();
            }
        }
        return $this->returnProject($response);
    }

    /**
     * Return the correct response for given conditions on canceled projects.
     *
     * @param $response
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    private function canceled($response)
    {
        if ($this->isReadOnlyUser()) {
            return $this->returnUnauthorisedError();
        };

        $project = $response['projects'][0];

        if ($project['isOwner'] || $project['isDelegate']) {
            return $this->returnProject($response);
        } else {
            return$this->returnUnauthorisedError();
        }
    }

    /**
     * Return the confidentiality status of the project.
     *
     * @param $project
     * @return bool
     */
    protected function isConfidential($project)
    {
        return $project['isConfidential'];
    }

    /**
     *  Check if the currently logged in user is a Read Only user.
     *
     * @return bool
     */
    protected function isReadOnlyUser()
    {
        return Auth::user()->user_type === 'Read Only';
    }

    /**
     * Return the given project.
     *
     * @param $project
     * @return mixed
     */
    protected function returnProject($project)
    {
        return $project;
    }

    /**
     * Return an unauthorised error message for frontend to handle.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function returnUnauthorisedError()
    {
        return response()->json(['message' => 'Unauthorised']);
    }
}
