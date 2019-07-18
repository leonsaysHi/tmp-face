<?php

namespace Tests\Unit;

use App\Traits\FilterTrait;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class FilterTraitTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @covers App\Traits\FilterTrait::getCachedData
     */
    public function testGetCachedData()
    {
        $user = factory(\App\User::class)->create();
        $user->id = 1;
        Auth::setUser($user);
        $this->be($user);

        $mockData = "{
            \"errorCode\": \"200\",
            \"files\": null,
            \"projects\": [
              {
                 \"BAINotification\": null,
                 \"CEPNumber\": null,
                 \"TagBUStakeholder\": null,
                 \"TagClientTactic\": \"clientTactic2\",
                 \"TagMarket\": \"market1\",
                 \"TagMethodology\": \"methodology1\",
                 \"TagProduct\": \"product1\",
                 \"TagResearchType\": \"researchtype1\",
                 \"TagRespondentType\": \"respondenttype1;respondenttype2\",
                 \"TagTherapeuticArea\": \"terapeuticarea2;terapeuticarea3\",
                 \"costCenter\": null,
                 \"costCenters\": null,
                 \"createDate\": \"2019-04-29T15:18:59.000Z\",
                 \"delegates\": null,
                 \"estimatedPfizerPercentageOfSpend\": null,
                 \"estimatedProjectSpendInUSD\": null,
                 \"files\": null,
                 \"flexibleDueDate\": null,
                 \"hashtag\": null,
                 \"isConfidential\": false,
                 \"isDelegate\": false,
                 \"isOwner\": true,
                 \"isRecuring\": null,
                 \"isReportDelegate\": null,
                 \"isReportOwn\": null,
                 \"isUrgent\": null,
                 \"links\": null,
                 \"lovs\": null,
                 \"owner\": null,
                 \"ownerName\": \"Fatih Gunes\",
                 \"parentProjectName\": null,
                 \"parentProjectSfdcId\": null,
                 \"plannedDate\": null,
                 \"plannedEndDate\": null,
                 \"plannedExecutionDate\": null,
                 \"plannedStartDate\": null,
                 \"projectDescription\": null,
                 \"projectDueDate\": null,
                 \"projectId\": \"PMR000018\",
                 \"projectName\": null,
                 \"projectSfdcId\": \"a4M5C000000CysXUAS\",
                 \"projectSpendLocalCurrencyAmount\": null,
                 \"projectSpendLocalCurrencyType\": null,
                 \"projectTimeZone\": null,
                 \"projectType\": \"PMR-US Funded\",
                 \"saps\": null,
                 \"status\": \"Draft\"
              }
            ],
            \"totalFile\": null,
            \"totalProject\": 100
        }";

        Cache::shouldReceive('get')
            ->once()
            ->andReturn($mockData);

        $trait = \Mockery::mock(FilterTrait::class);
        $trait->shouldReceive('getCachedData');
        $this->assertJson($trait->getCachedData("project-list", $user->id));
    }

    /**
     * @covers App\Traits\FilterTrait::makeSalesForceCall
     * @covers App\Traits\FilterTrait::removeNullFiltersAndAttachToResponse
     * @covers App\Traits\FilterTrait::generateFilters
     * @covers App\Traits\FilterTrait::explodeAndReturnUnique
     */
    public function testMakeSalesForceCall()
    {
        $user = factory(\App\User::class)->create();
        Auth::setUser($user);
        $this->be($user);

        $payload = [
            'filters' => [
                "TagProduct" => ["product1"],
                "TagClientTactic" => ["clientTactic2"],
                "TagMethodology" => ["methodology1"],
                "projectType" => ["PMR-US Funded"],
                "TagMarket" => ["market2"],
                "TagTherapeuticArea" => ["terapeuticarea1"]
            ],
            'includeTeam' => true,
            'includeCancelled' => true,
            'includeCompleted ' => true,
            'includeDraft' => true,
            'includePending' => true,
            'includeActive' => true,
            'includeActiveFuture' => true,
            'useCached' => false,
            'projectStartYearSearchText' => "2019",
        ];

        $mockData = "{
            \"errorCode\": \"200\",
            \"files\": null,
            \"projects\": [
              {
                 \"BAINotification\": null,
                 \"CEPNumber\": null,
                 \"TagBUStakeholder\": null,
                 \"TagClientTactic\": \"clientTactic2\",
                 \"TagMarket\": \"market1\",
                 \"TagMethodology\": \"methodology1\",
                 \"TagProduct\": \"product1\",
                 \"TagResearchType\": \"researchtype1\",
                 \"TagRespondentType\": \"respondenttype1;respondenttype2\",
                 \"TagTherapeuticArea\": \"terapeuticarea2;terapeuticarea3\",
                 \"costCenter\": null,
                 \"costCenters\": null,
                 \"createDate\": \"2019-04-29T15:18:59.000Z\",
                 \"delegates\": null,
                 \"estimatedPfizerPercentageOfSpend\": null,
                 \"estimatedProjectSpendInUSD\": null,
                 \"files\": null,
                 \"flexibleDueDate\": null,
                 \"hashtag\": null,
                 \"isConfidential\": false,
                 \"isDelegate\": false,
                 \"isOwner\": true,
                 \"isRecuring\": null,
                 \"isReportDelegate\": null,
                 \"isReportOwn\": null,
                 \"isUrgent\": null,
                 \"links\": null,
                 \"lovs\": null,
                 \"owner\": null,
                 \"ownerName\": \"Fatih Gunes\",
                 \"parentProjectName\": null,
                 \"parentProjectSfdcId\": null,
                 \"plannedDate\": null,
                 \"plannedEndDate\": null,
                 \"plannedExecutionDate\": null,
                 \"plannedStartDate\": null,
                 \"projectDescription\": null,
                 \"projectDueDate\": null,
                 \"projectId\": \"PMR000018\",
                 \"projectName\": null,
                 \"projectSfdcId\": \"a4M5C000000CysXUAS\",
                 \"projectSpendLocalCurrencyAmount\": null,
                 \"projectSpendLocalCurrencyType\": null,
                 \"projectTimeZone\": null,
                 \"projectType\": \"PMR-US Funded\",
                 \"saps\": null,
                 \"status\": \"Draft\"
              },
              {
                 \"BAINotification\": null,
                 \"CEPNumber\": null,
                 \"TagBUStakeholder\": null,
                 \"TagClientTactic\": \"clientTactic2\",
                 \"TagMarket\": \"market2\",
                 \"TagMethodology\": \"methodology1\",
                 \"TagProduct\": \"product1\",
                 \"TagResearchType\": \"researchtype1\",
                 \"TagRespondentType\": \"respondenttype2;respondenttype3\",
                 \"TagTherapeuticArea\": \"terapeuticarea1;terapeuticarea3\",
                 \"costCenter\": null,
                 \"costCenters\": null,
                 \"createDate\": \"2019-04-29T15:18:59.000Z\",
                 \"delegates\": null,
                 \"estimatedPfizerPercentageOfSpend\": null,
                 \"estimatedProjectSpendInUSD\": null,
                 \"files\": null,
                 \"flexibleDueDate\": null,
                 \"hashtag\": null,
                 \"isConfidential\": false,
                 \"isDelegate\": false,
                 \"isOwner\": true,
                 \"isRecuring\": null,
                 \"isReportDelegate\": null,
                 \"isReportOwn\": null,
                 \"isUrgent\": null,
                 \"links\": null,
                 \"lovs\": null,
                 \"owner\": null,
                 \"ownerName\": \"Fatih Gunes\",
                 \"parentProjectName\": null,
                 \"parentProjectSfdcId\": null,
                 \"plannedDate\": null,
                 \"plannedEndDate\": null,
                 \"plannedExecutionDate\": null,
                 \"plannedStartDate\": null,
                 \"projectDescription\": null,
                 \"projectDueDate\": null,
                 \"projectId\": \"PMR000018\",
                 \"projectName\": null,
                 \"projectSfdcId\": \"a4M5C000000CysXUAS\",
                 \"projectSpendLocalCurrencyAmount\": null,
                 \"projectSpendLocalCurrencyType\": null,
                 \"projectTimeZone\": null,
                 \"projectType\": \"PMR-US Funded\",
                 \"saps\": null,
                 \"status\": \"Draft\"
              }
            ],
            \"totalFile\": null,
            \"totalProject\": 100
        }";

        $mock = new MockHandler([
            new Response(200, [], "{\"access_token\":\"fsXvbrrtsRUUZ2JYvrkx1PRGpcvNJPXy6T7G63g8CIAsiV7mwY2CDTOAxZ08\",\"instance_url\":\"https://pfizerbt--Haigang.cs62.my.salesforce.com\",\"id\":\"https://test.salesforce.com/id/00D5C000000LOtIUAW/0055C000000ooQHQAY\",\"token_type\":\"Bearer\",\"issued_at\":\"1543873716850\",\"signature\":\"A+i8lz+UTUTDwF9lk28LIB11oyPJAcPMVkdl5ANbL7E=\"}"),
            new Response(200, [], $mockData),
        ]);

        $handler = HandlerStack::create($mock);
        $mock = new Client(['handler' => $handler]);

        $this->app->instance(Client::class, $mock);
        $this->post('/api/get-project-list', $payload)
            ->assertStatus(200);

        $trait = \Mockery::mock(FilterTrait::class);
        $mockData = json_decode($mockData, true);

        $trait->shouldReceive('filterRecords');

        $filteredData = $trait->filterRecords($mockData['projects'], $payload['filters']);

        $this->assertEquals($filteredData->all()[1], $mockData['projects'][1]);

        $filteredData = $trait->filterRecords([], $payload['filters'])->toArray();
        $this->assertEquals([], $filteredData);
    }

    /**
     * @covers App\Traits\FilterTrait::filterRecords
     * @covers App\Traits\FilterTrait::filterConditionsMatched
     */
    public function testFilterRecords()
    {
        $user = factory(\App\User::class)->create();
        $user->id = 1;
        Auth::setUser($user);
        $this->be($user);

        $mockData = "{
            \"errorCode\": \"200\",
            \"files\": null,
            \"projects\": [
              {
                 \"BAINotification\": null,
                 \"CEPNumber\": null,
                 \"TagBUStakeholder\": null,
                 \"TagClientTactic\": \"clientTactic2\",
                 \"TagMarket\": \"market1\",
                 \"TagMethodology\": \"methodology1\",
                 \"TagProduct\": \"product1\",
                 \"TagResearchType\": \"researchtype1\",
                 \"TagRespondentType\": \"respondenttype1;respondenttype2\",
                 \"TagTherapeuticArea\": \"terapeuticarea2;terapeuticarea3\",
                 \"costCenter\": null,
                 \"costCenters\": null,
                 \"createDate\": \"2019-04-29T15:18:59.000Z\",
                 \"delegates\": null,
                 \"estimatedPfizerPercentageOfSpend\": null,
                 \"estimatedProjectSpendInUSD\": null,
                 \"files\": null,
                 \"flexibleDueDate\": null,
                 \"hashtag\": null,
                 \"isConfidential\": false,
                 \"isDelegate\": false,
                 \"isOwner\": true,
                 \"isRecuring\": null,
                 \"isReportDelegate\": null,
                 \"isReportOwn\": null,
                 \"isUrgent\": null,
                 \"links\": null,
                 \"lovs\": null,
                 \"owner\": null,
                 \"ownerName\": \"Fatih Gunes\",
                 \"parentProjectName\": null,
                 \"parentProjectSfdcId\": null,
                 \"plannedDate\": null,
                 \"plannedEndDate\": null,
                 \"plannedExecutionDate\": null,
                 \"plannedStartDate\": null,
                 \"projectDescription\": null,
                 \"projectDueDate\": null,
                 \"projectId\": \"PMR000018\",
                 \"projectName\": null,
                 \"projectSfdcId\": \"a4M5C000000CysXUAS\",
                 \"projectSpendLocalCurrencyAmount\": null,
                 \"projectSpendLocalCurrencyType\": null,
                 \"projectTimeZone\": null,
                 \"projectType\": \"PMR-US Funded\",
                 \"saps\": null,
                 \"status\": \"Draft\"
              },
              {
                 \"BAINotification\": null,
                 \"CEPNumber\": null,
                 \"TagBUStakeholder\": null,
                 \"TagClientTactic\": \"clientTactic2\",
                 \"TagMarket\": \"market2\",
                 \"TagMethodology\": \"methodology1\",
                 \"TagProduct\": \"product1\",
                 \"TagResearchType\": \"researchtype1\",
                 \"TagRespondentType\": \"respondenttype2;respondenttype3\",
                 \"TagTherapeuticArea\": \"terapeuticarea1;terapeuticarea3\",
                 \"costCenter\": null,
                 \"costCenters\": null,
                 \"createDate\": \"2019-04-29T15:18:59.000Z\",
                 \"delegates\": null,
                 \"estimatedPfizerPercentageOfSpend\": null,
                 \"estimatedProjectSpendInUSD\": null,
                 \"files\": null,
                 \"flexibleDueDate\": null,
                 \"hashtag\": null,
                 \"isConfidential\": false,
                 \"isDelegate\": false,
                 \"isOwner\": true,
                 \"isRecuring\": null,
                 \"isReportDelegate\": null,
                 \"isReportOwn\": null,
                 \"isUrgent\": null,
                 \"links\": null,
                 \"lovs\": null,
                 \"owner\": null,
                 \"ownerName\": \"Fatih Gunes\",
                 \"parentProjectName\": null,
                 \"parentProjectSfdcId\": null,
                 \"plannedDate\": null,
                 \"plannedEndDate\": null,
                 \"plannedExecutionDate\": null,
                 \"plannedStartDate\": null,
                 \"projectDescription\": null,
                 \"projectDueDate\": null,
                 \"projectId\": \"PMR000018\",
                 \"projectName\": null,
                 \"projectSfdcId\": \"a4M5C000000CysXUAS\",
                 \"projectSpendLocalCurrencyAmount\": null,
                 \"projectSpendLocalCurrencyType\": null,
                 \"projectTimeZone\": null,
                 \"projectType\": \"PMR-US Funded\",
                 \"saps\": null,
                 \"status\": \"Draft\"
              }
            ],
            \"totalFile\": null,
            \"totalProject\": 100
        }";

        $mockFilters = [
            "TagProduct" => ["product1"],
            "TagClientTactic" => ["clientTactic2"],
            "TagMethodology" => ["methodology1"],
            "projectType" => ["PMR-US Funded"],
            "TagMarket" => ["market2"],
            "TagTherapeuticArea" => ["terapeuticarea1"]
        ];

        $trait = \Mockery::mock(FilterTrait::class);
        $mockData = json_decode($mockData, true);

        $trait->shouldReceive('filterRecords');
        $filteredData = $trait->filterRecords($mockData['projects'], $mockFilters);

        $this->assertEquals($filteredData->all()[1], $mockData['projects'][1]);
    }
}
