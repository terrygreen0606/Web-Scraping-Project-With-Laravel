<?php

namespace App\Http\Controllers;

use App\Campaign;
use App\CampaignLink;
use App\OhiBatchState;
use App\OHILinksAddPerday;
use App\Setting;
use App\Http\Controllers\DropboxController as Dropbox;
use App\Http\Controllers\OneHourIndexingController;
use Carbon\Carbon;

class CronjobController extends Controller
{
    
    private $OHIAPI;
    private $DropBoxClient;
    private $maxOHILimitPerDay;
    private $OHILinksPerDay;
    private $cronPriodTime;
    private $today;
    private $totalAddedToday;
    private $eachCronLinksCount;
    private $OHILinksAddPerday;
    private $notProcessedOHIState;
    private $inprogressOHIState;
    private $completedOHIState;


    public function __construct()
    {
        
        $settings = Setting::first();

        $this->notProcessedOHIState = OhiBatchState::where('title', 'Not Processed')->first()->id;
        $this->inprogressOHIState   = OhiBatchState::where('title', 'In Progress')->first()->id;
        $this->completedOHIState    = OhiBatchState::where('title', 'Completed')->first()->id;

        $this->maxOHILimitPerDay    = $settings->ohi_max_links;
        $this->cronPriodTime        = $settings->cron_period_time; // Minute
        $this->OHILinksPerDay       = $settings->ohi_links_per_day;
        $this->eachCronLinksCount   = $this->calculateLinkCount($this->maxOHILimitPerDay, $this->cronPriodTime);
        $this->today                = Carbon::now()->toDateString();
        $this->OHIAPI               = new OneHourIndexingController($settings->ohi_app_key, $settings->ohi_api_key);
        $this->DropBoxClient        = new Dropbox($settings->dropbox_access_token);


        // Begin get total links added to OHI for today
        $this->OHILinksAddPerday = OHILinksAddPerday::where('date', $this->today)->first();

        if($this->OHILinksAddPerday == null) {

            $this->totalAddedToday              = 0;
            $this->OHILinksAddPerday            = new OHILinksAddPerday();
            $this->OHILinksAddPerday->date      = $this->today;
            $this->OHILinksAddPerday->save();

        } else {
            $this->totalAddedToday = $this->OHILinksAddPerday->total;
        }
        // End get total links added to OHI for today

    }


    /**
     * Add links on One Hour Indexing
     * 
     * @return JSON
     */
    public function addLinksToOHI() {
        
        // Begin check daily quota exceeded
        if ($this->maxOHILimitPerDay <= $this->totalAddedToday) {

            // Return response and exit cron
            $result['success']      = true;
            $result['messages'][]   = 'The daily quota has been exceeded.';
            $result['data']         = [];

            $response = response(
                        json_encode($result),
                        200
                    )
                    ->header('Content-Type', 'application/json');

            return $response;

        }
        // End check daily quota exceeded


        // Begin set remained links count
        $remainedLinkCount = 0;

        if (($this->maxOHILimitPerDay - $this->totalAddedToday) < $this->eachCronLinksCount) {

            $remainedLinkCount = $this->maxOHILimitPerDay - $this->totalAddedToday;

        } else {

            $remainedLinkCount  = $this->eachCronLinksCount;

        }
        // End set remained links count


        // Begin adding links to OHI
        $campaigns = $this->getCampaignForAddToOHI($remainedLinkCount);

        foreach ($campaigns as $key => $campaign) {
            
            $links      = $this->getLinksToAddOHI ($campaign, $remainedLinkCount);
            $campaign   = $this->setCampaignBatchID ($campaign);
            $linksArray = $this->convertLinksSTDToArray($links);

            $this->OHIAPI->uploadLinks ($campaign->batch_id, $linksArray, $this->OHILinksPerDay);
            $this->updateLinksOHIStatus ($links);
            
            $campaign = $this->updateCampaignOHIStatus ($campaign);

            $this->updateCampaignDropboxFiles($campaign);
            $this->updateOHILinksAddPerday(count($linksArray));

            $campaign->save();

            $remainedLinkCount -= count($linksArray);

        }


        // TODO: log cron status


        // Return response and exit cron
        $result['success']      = true;
        $result['messages'][]   = 'Cron has been run successfully';
        $result['data']         = [];

        $response = response(
                    json_encode($result),
                    200
                )
                ->header('Content-Type', 'application/json');

        return $response;

    }


    public function monitoring() {
        
    }


    /**
     * convert links std class to array
     * 
     * @param Array $stdClassLinks
     * @param Int $count
     * @return Array
     */
    private function convertLinksSTDToArray ($stdClassLinks) {

        $links = [];

        foreach ($stdClassLinks as $key => $stdClassLink) {
            $links[] = $stdClassLink->url;
        }

        return $links;

    }


    /**
     * Calculate count of links should add to OHI
     * 
     * @param Int $maxLinksLimit
     * @param Int $cronPriodTime
     * @return Int
     */
    private function calculateLinkCount ($maxLinksLimit, $cronPriodTime) {

        $oneDayMinutes      = 24 * 60;
        $countOfCronsPerDay = $oneDayMinutes / $cronPriodTime;
        $eachCronLinksCount = $maxLinksLimit / $countOfCronsPerDay;
        $eachCronLinksCount = ceil($eachCronLinksCount);

        return $eachCronLinksCount;

    }


    /**
     * Get campaigns for add to OHI, according to links count limit
     * 
     * @param Int $linksCount
     * @return Array
     */
    private function getCampaignForAddToOHI ($linksCount) {

        $campaigns  = [];
        $links      = CampaignLink::select('campaign_id')
                                        ->where('added_to_ohi_at', null)
                                        ->limit($linksCount)
                                        ->groupby('campaign_id')
                                        ->get();

        foreach ($links as $key => $link) {
            $campaigns[] = $link->campaign;
        }
        
        return $campaigns;

    }


    /**
     * Get campaign links that should add to OHI
     * 
     * 
     * @param Campagin $campaign
     * @param Int $count
     * @return Array
     */
    private function getLinksToAddOHI($campaign, $count) {

        $links = CampaignLink::where([
                                        ['campaign_id', '=', $campaign->id],
                                        ['added_to_ohi_at', '=', null]
                                    ])
                                    ->limit($count)
                                    ->get();

        return $links;

    }


    /**
     * Get or create batch_id for or from a campaign
     * 
     * @param Campaign $campaign
     * @return Campaign
     */
    private function setCampaignBatchID ($campaign) {

        if ($campaign->batch_id == null) {
            $campaign->batch_id = $this->OHIAPI->createBatch($campaign->title, []);
        }

        return $campaign;

    }


    /**
     * Update link status. set the link's added_to_ohi_at attribute.
     * 
     * @param Array $links
     * @return Void
     */
    private function updateLinksOHIStatus($links) {

        $wherein = [];

        foreach ($links as $key => $link) {
            
            $wherein[] = $link->id;

        }

        CampaignLink::whereIn('id', $wherein)
                        ->update([
                            'added_to_ohi_at' => Carbon::now()
                        ]);
    }


    /**
     * Update the campaign status according to its links status
     * 
     * @param Campaign $campaign
     * @return Campaign
     */
    private function updateCampaignOHIStatus($campaign) {

        $remainedLinks          = CampaignLink::where([
                                                            ['campaign_id', '=', $campaign->id],
                                                            ['added_to_ohi_at', '=', null]
                                                        ])
                                                        ->get()
                                                        ->count();

        if($remainedLinks == 0) {
            $campaign->ohi_batch_state = $this->completedOHIState;
        } else {
            $campaign->ohi_batch_state = $this->inprogressOHIState;
        }

        return $campaign;

    }


    /**
     * Update campaign dropbox files
     * 
     * 
     * @param Campaign $campaign
     * @return Void
     */
    private function updateCampaignDropboxFiles ($campaign) {

        if ($campaign->ohi_batch_state == $this->completedOHIState) {

            $links = $this->convertLinksSTDToArray($campaign->Links);

            $fileContent            = implode("\n", $links);
            $dropboxRemoveFilePath  = '/campaigns/' . $campaign->title . '.txt';
            $dropboxUploadFilePath  = '/campaigns/Added To One Hour Indexing/' 
                                        . $campaign->title 
                                        . '.txt';

            $this->DropBoxClient->uploadFile($dropboxUploadFilePath, $fileContent);
            $this->DropBoxClient->deleteFile($dropboxRemoveFilePath);

        }

    }


    /**
     * Update OHI total links per day
     * 
     * @param Int $count
     * @return Void
     */
    private function updateOHILinksAddPerday ($count) {

        $this->totalAddedToday += $count;
        $this->OHILinksAddPerday->total = $this->totalAddedToday;

        $this->OHILinksAddPerday->save();

    }

}
