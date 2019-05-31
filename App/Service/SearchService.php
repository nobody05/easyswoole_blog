<?php

namespace App\Service;

use Elasticsearch\ClientBuilder;

class SearchService
{
    protected $index;
    protected $type;
    protected $id;
    protected $body;
    protected $client;

    public function __construct()
    {
        $this->client = ClientBuilder::create()->build();
    }

    public function searchByKeyword($title)
    {
        return $this->client->search([
            'index' => $this->getIndex(),
            'type' => $this->getType(),
            'body' => [
                'query' => [
                    'multi_match' => [
                        'query' => $title,
                        'fields' => ['title', 'content']
                    ]
                ]
            ]
        ]);

    }

    public function add($id, $body)
    {
        try {
            return $this->client->index([
                'index' => $this->getIndex(),
                'type' => $this->getType(),
                'id' => $id,
                'body' => $body
            ]);
        } catch (\Exception $e) {
            print_r($e);
        }


    }

    public function update()
    {

    }

    public function delete()
    {

    }

    /**
     * @return mixed
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @param mixed $index
     */
    public function setIndex($index): void
    {
        $this->index = $index;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body): void
    {
        $this->body = $body;
    }

    public function __destruct()
    {

    }
}