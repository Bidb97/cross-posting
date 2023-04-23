<?php

namespace Bidb97\CrossPosting\Observers;

use Bidb97\CrossPosting\Contracts\CrossPosting;
use Bidb97\CrossPosting\Models;
use Bidb97\CrossPosting\Services\Posting;
use Illuminate\Support\Facades\DB;

class ClientModelObserver
{
    /**
     * @var CrossPosting
     */
    private $clientModel;

    /**
     * @param CrossPosting $clientModel
     */
    public function __construct(CrossPosting $clientModel)
    {
        $this->clientModel = $clientModel;
    }

    /**
     * @param CrossPosting $model
     */
    public function created()
    {
        $postingData = $this->getPostingData();
        $publishDate = $this->getPublishDate();
        $shortUri = substr(md5(uniqid(null, true)), 0, config('cross-posting.short_link_length'));

        $crossPosting = DB::transaction(function () use ($postingData, $publishDate, $shortUri) {

            $crossPosting = Models\CrossPosting::where('short_uri', $shortUri)
                ->limit(1)
                ->lockForUpdate()
                ->first();

            if (!empty($crossPosting)) {
                $this->created();
                return;
            }

            $data = [
                'model' => get_class($this->clientModel),
                'model_id' => $this->clientModel->{$this->clientModel->getKeyName()},
                'posting_data' => $postingData,
                'resource_uri' => $this->resourceUri(),
                'short_uri' => $shortUri,
            ];

            if (!empty($publishDate)) {
                $data['publish_date'] = $publishDate;
            }

            return Models\CrossPosting::create($data);
        });

        if (empty($publishDate)) {
            (new Posting())->run($crossPosting);
            /*dispatch(function () use ($crossPosting) {

            })->afterResponse();*/
        }
    }

    public function updated()
    {
        $crossPosting = Models\CrossPosting::where([
                'model' => get_class($this->clientModel),
                'model_id' => $this->clientModel->{$this->clientModel->getKeyName()}
            ])
            ->limit(1)
            ->first();

        if (empty($crossPosting)) {
            $this->created();
            return;
        }

        if ($this->clientModel->is_posted) {
            return;
        }

        $postingData = $this->getPostingData();
    }

    /**
     * @return string
     */
    public function getPublishDate(): string
    {
        $dataMapping = $this->clientModel->getDataMapping();
        $attributes = $this->clientModel->getAttributes();

        return (string) $attributes[$dataMapping['publish_date']];
    }

    /**
     * @return array
     */
    private function getPostingData(): array
    {
        $dataMapping = $this->clientModel->getDataMapping();
        $attributes = $this->clientModel->getAttributes();

        return [
            'title' => $attributes[$dataMapping['title']],
            'content' => $attributes[$dataMapping['content']],
            'image' => $attributes[$dataMapping['image']]
        ];
    }

    /**
     * @return string
     */
    private function resourceUri(): string
    {
        $parseUrl = parse_url($this->clientModel->getResourceUri());

        $resourceUri = $parseUrl['path'];

        if (!empty($parseUrl['query'])) {
            $resourceUri .= '?' . $parseUrl['query'];
        }

        return $resourceUri;
    }


}