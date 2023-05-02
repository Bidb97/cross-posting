<?php

namespace Bidb97\CrossPosting\Observers;

use Bidb97\CrossPosting\Contracts\CrossPosting;
use Bidb97\CrossPosting\Models;
use Bidb97\CrossPosting\Services\Posting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ClientModel
{
    /**
     * @var CrossPosting
     */
    private $clientModel;

    /**
     * Min length short link
     */
    const SHORT_LINK_MIN_LENGTH = 8;

    /**
     * Max length short link
     */
    const SHORT_LINK_MAX_LENGTH = 16;

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
        $publishDate = $this->getPublishDate();
        $shortUri = substr(md5(uniqid(null, true)), 0, $this->getShortLinkLength());

        $crossPosting = DB::transaction(function () use ($publishDate, $shortUri) {

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
                'posting_data' => $this->getPostingData(),
                'resource_uri' => $this->getResourceUri(),
                'short_uri' => $shortUri,
                'publish_date' => $publishDate
            ];

            return Models\CrossPosting::create($data);
        });

        if ($publishDate <= now()) {
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

        $publishDate = $this->getPublishDate();

        $data = [
            'posting_data' => $this->getPostingData(),
            'resource_uri' => $this->getResourceUri(),
            'publish_date' => $publishDate
        ];

        $crossPosting->update($data);
    }

    /**
     * @return Carbon
     */
    private function getPublishDate(): Carbon
    {
        $dataMapping = $this->clientModel->getDataMapping();
        $attributes = $this->clientModel->getAttributes();

        $publishDate = $attributes[$dataMapping['publish_date']];

        return (!empty($publishDate) ? Carbon::parse($publishDate) : now());
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
    private function getResourceUri(): string
    {
        $parseUrl = parse_url($this->clientModel->getResourceUri());

        $resourceUri = $parseUrl['path'];

        if (!empty($parseUrl['query'])) {
            $resourceUri .= '?' . $parseUrl['query'];
        }

        return $resourceUri;
    }

    /**
     * @return int
     */
    private function getShortLinkLength(): int
    {
        $shortLinkLength = config('cross-posting.short_link_length');

        if ($shortLinkLength >= self::SHORT_LINK_MIN_LENGTH && $shortLinkLength <= self::SHORT_LINK_MAX_LENGTH) {
            return $shortLinkLength;
        }

        return self::SHORT_LINK_MIN_LENGTH;
    }


}