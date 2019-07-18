<?php

namespace App;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class GetLovRepository extends SalesForceRepository
{
    /**
     * @param array $payload
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getLov($payload = [])
    {
        $guid = Auth::user()->guid;
        $includeInactiveLov = Arr::get($payload, 'includeInactiveLov', 'sss');
        $response = $this->post(
            config('endpoints.get_lov'),
            compact(
                'guid',
                'includeInactiveLov'
            )
        );
        return $this->mapListOfValues($response);
    }

    public function mapListOfValues($data)
    {

        $data = Arr::get($data, 'lovs', []);

        if (empty($data)) {
            return $data;
        }

        $data = collect($data);
        $groups = $data->groupBy('lovType');

        $result = $groups->map(function ($group) {
            $parents = $group->filter(function ($item) {
                return $this->checkIfItsAParent($item);
            });

            $allChildren = [];
            $others = [];

            $parents = $parents->map(function ($parent) use ($group, &$others, &$allChildren) {
                $children = $group->filter(function ($child) use ($parent) {
                    return $child['parentID'] == $parent['myworkId'];
                });
                $allChildren = array_merge($allChildren, $children->all());

                $parent['children'] = $children->reverse()->all();

                return $parent;
            });

            $others = $group->filter(function ($item) use ($allChildren, $parents) {
                return !collect($allChildren)->first(function ($child) use ($item) {
                        return $item['sfdcId'] == $child['sfdcId'];
                }) && !$parents->first(function ($parent) use ($item) {
                        return $parent['sfdcId'] == $item['sfdcId'];
                });
            })->all();
            if ($parents->count()) {
                return array_merge($parents->all(), $others);
            }
            return $group;
        });

        return $result->toArray();
    }


    /**
     * Determine if given item is a parent
     *
     * It's a parent when:
     * parentID is null or "0"
     * AND myworkId is not null and has less characters than 5
     *
     * @param $item
     * @return bool
     */
    protected function checkIfItsAParent($item)
    {
        return (is_null($item['parentID']) || $item['parentID'] == '0')
            && (!is_null($item['myworkId']));
    }
}
