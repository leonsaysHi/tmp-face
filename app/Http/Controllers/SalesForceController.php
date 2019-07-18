<?php

namespace App\Http\Controllers;

use App\SalesForceRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SalesForceController
 * @package App\Http\Controllers
 */
class SalesForceController extends Controller
{
    /**
     * @param Request $request
     * @param SalesForceRepository $repository
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function query(Request $request, SalesForceRepository $repository)
    {
        $input = $this->validate($request, [
            'query' => 'required|string'
        ]);

        return response()->json($repository->query($input['query']), Response::HTTP_ACCEPTED);
    }

    /**
     * @param Request $request
     * @param SalesForceRepository $repository
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request, SalesForceRepository $repository)
    {
        $input = $this->validate($request, [
            'object' => 'required|string',
            'data' => 'required',
        ]);

        return response()->json($repository->create(
            $input['object'],
            $input['data']
        ), Response::HTTP_ACCEPTED);
    }

    /**
     * @param Request $request
     * @param SalesForceRepository $repository
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, SalesForceRepository $repository)
    {
        $input = $this->validate($request, [
            'object' => 'required|string',
            'id' => 'required|string',
            'data' => 'required',
        ]);

        return response()->json($repository->update(
            $input['object'],
            $input['id'],
            $input['data']
        ), Response::HTTP_ACCEPTED);
    }

    /**
     * @param Request $request
     * @param SalesForceRepository $repository
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function delete(Request $request, SalesForceRepository $repository)
    {
        $input = $this->validate($request, [
            'object' => 'required|string',
            'id' => 'required|string',
        ]);

        return response()->json($repository->delete(
            $input['object'],
            $input['id']
        ), Response::HTTP_ACCEPTED);
    }

    /**
     * @param SalesForceRepository $repository
     * @param Request $request
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function upload(SalesForceRepository $repository, Request $request)
    {
        $input = $this->validate($request, [
            'file' => 'required|file',
            'fileTitle' => 'required|string',
            'fileType' => 'required|string',
            'projectId' => 'required'
        ]);

        $file = $input['file'];

        $fileName = normalize_string(
            basename($file->getClientOriginalName(), '.' . $file->getClientOriginalExtension())
        ) . '.' . $file->getClientOriginalExtension();

        Storage::putFileAs('public/project-attachments', $file, $fileName);

        return response()->json($repository->upload($input, $file), 200);
    }

    /**
     * @param Request $request
     * @param SalesForceRepository $repository
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function download(Request $request, SalesForceRepository $repository, $id)
    {

        $input = $this->validate($request, [
            'fileName' => 'required|string',
        ]);

        $repository->download($id, normalize_string($input['fileName']));

        return response()->download(
            storage_path(
                'app/public/project-attachments/' . normalize_string($input['fileName'])
            )
        )->deleteFileAfterSend(true);
    }

    /**
     * @param SalesForceRepository $repository
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function deleteFile(SalesForceRepository $repository, $id)
    {
        return response()->json($repository->deleteFile($id), 200);
    }
}
