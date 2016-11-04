<?php

namespace NunoPress\Silex\WpApi;

/**
 * Class WpApi
 *
 * @package NunoPress\Silex\WpApi
 */
class WpApi
{
    /**
     * @var string
     */
    private $client;

    /**
     * WpApi constructor.
     *
     * @param WpApiClientInterface $client
     */
    public function __construct(WpApiClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $options
     *
     * @return mixed
     */
    protected function request($method, $uri, array $options = [])
    {
        $response = $this->client->request($method, $uri, $options);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param array $item
     *
     * @return array
     */
    protected function getSchema(array $item)
    {
        // Basic data
        $data = [
            'id'                => $item['id'],
            'title'             => $item['title']['rendered'],
            'type'              => $item['type'],
            'slug'              => $item['slug'],
            'date'              => $item['date'],
            'modified'          => $item['modified'],
            'content'           => $item['content']['rendered'],
            'featured_media'    => $item['featured_media'],
            'menu_order'        => $item['menu_order'],
            'acf'               => (isset($item['acf'])) ? $item['acf'] : null
        ];

        // Author data
        foreach ($item['_embedded']['author'] as $author) {
            $data['authors'][] = [
                'id'            => $author['id'],
                'name'          => $author['name'],
                'url'           => $author['url'],
                'slug'          => $author['slug'],
                'avatar_url'    => $author['avatar_urls']['96'],
                'acf'           => (isset($author['acf'])) ? $author['acf'] : null
            ];
        }

        // Get the first author from authors
        // Todo: check another way in the middle
        $authors = $data['authors'];
        $data['author'] = array_shift($authors);
        unset($authors);

        return $data;
    }

    /**
     * @param string $type
     * @param string $orderBy
     * @param string $order
     * @param int $page
     *
     * @return array
     */
    public function getItems($type = 'posts', $orderBy = 'menu_order', $order = 'desc', $page = 1)
    {
        $items = $this->request('GET', sprintf(
            'wp-json/wp/v2/%s/?_embed&filter[orderby]=%s&filter[order]=%s&page=%d',
            $type,
            $orderBy,
            $order,
            $page
        ));

        $data = [];

        foreach ($items as $item) {
            $data[] = $this->getSchema($item);
        }

        return $data;
    }

    /**
     * @param string $type
     * @param null|int $id
     *
     * @return array
     */
    public function getAcf($type, $id = null)
    {
        if (null === $id) {
            $data = $this->request('GET', sprintf(
                '%s/%s',
                'wp-json/acf/v2',
                $type
            ));
        } else {
            $data = $this->request('GET', sprintf(
                '%s/%s/%d',
                'wp-json/acf/v2',
                $type,
                $id
            ));
        }

        return (isset($data['acf'])) ? $data['acf'] : null;
    }
}