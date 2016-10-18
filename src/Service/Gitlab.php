<?php

namespace ServerStatus\Service;

use ServerStatus\AbstractService;
use GuzzleHttp\Client as Client;

class Gitlab extends AbstractService
{
    protected $options = [
        'gitlab-url'    => 'http://gitlab.com',
        'private-token' => '',
    ];

    /**
     * Constructor
     * @param array $params An array of options to the instance.
     */
    public function __construct($params = [])
    {
        $this->setOptions($params);
    }

    /**
     * Setter for the options property.
     *
     * @param array $options
     *   An array of options.
     *
     * @return \ServerStatus\Service\Gitlab
     *   Returns $this for object-chaining.
     */
    public function setOptions($options = [])
    {
        $this->options = array_merge($this->options, $options);
        return $this;
    }

    /**
     * Gets a list of projects from the GitLab instance.
     *
     * @return array
     *   An array of project information.
     */
    public function getProjects($query = [])
    {
        $client = $this->getClient();
        $url = $this->getUrl('api/v3/projects');
        $token = $this->getPrivateToken();
        $result = $client->request('GET', $url, [
            'headers' => [
                'PRIVATE-TOKEN' => $token,
            ],
            'query' => $query,
        ]);

        $body = (string)$result->getBody();

        return json_decode($body);
    }

    /**
     * Utility method to get a GuzzleHttp\Client instance.
     *
     * @return GuzzleHttp\Client
     */
    protected function getClient()
    {
        return new Client;
    }

    /**
     * Utility method to get a full url for a request, without having to know the full path.
     *
     * @param  string $path
     *   The relative path of the url.
     *
     * @return string
     *   The full path of the url.
     */
    protected function getUrl($path)
    {
        return implode('/', [$this->options['gitlab-url'], $path]);
    }

    /**
     * Utility method to get the private token option value.
     *
     * @return string
     *   The private token value.
     */
    protected function getPrivateToken()
    {
        return $this->options['private-token'];
    }

}
