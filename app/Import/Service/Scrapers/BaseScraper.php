<?php namespace App\Import\Service\Scrapers;

use DOMXPath;
use DOMDocument;
use Guzzle\Http\Client;

use Guzzle\Plugin\Cookie\Cookie;
use Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar;
use Guzzle\Plugin\Cookie\CookiePlugin;

class BaseScraper {


	/**
	 * @var Client
	 */
	private $client;

	/**
	 * @var \Guzzle\Http\Message\Request
	 */
	private $request;

	/**
	 * @var DOMDocument
	 */
	protected $dom;

	/**
	 * @var DOMXpath
	 */
	protected $xPath;

	private $userAgent = 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.87 Safari/537.36y';

	public function __construct($baseUrl,  $defaultOptions = [])
	{
		$this->client = new Client($baseUrl, [
				'request.options' => [
				'exceptions' => true
				]
			]);

		$this->setDefaultOptions($defaultOptions);
		$this->client->setUserAgent($this->userAgent);
	}

	/**
	 * Sets the default options to the clients.
	 *
	 * @param array $defaultOptions
	 *
	 */
	public function setDefaultOptions($defaultOptions)
	{
		foreach ($defaultOptions as $key => $value) {
			$this->client->setDefaultOption($key, $value);
		}
	}

	/**
	 * Sets the headers to each of the requests.
	 *
	 * @param array $headersOptions
	 *
	 */

	public function setRequestHeaders($headersOptions)
	{

		foreach ($headersOptions as $key => $value) {
			$this->request->setHeader($key, $value);
		}
	}

	/**
	 * HTTP GET request.
	 *
	 * @param $resource
	 * @param $headersOptions
	 * @return \Guzzle\Http\Message\Response
	 */
	public function fetchGetResource($resource, $headersOptions)
	{

	    $this->request = $this->client->get($resource);
		$this->setRequestHeaders($headersOptions);

		$response = $this->request->send();
		if($response->getStatusCode() === 200) {
			return $response;
		}
	}

	/**
	 * HTTP POST request.
	 *
	 * @param String $resource
	 * @param array $postData
	 * @param array $headersOptions
	 * @return mixed \Guzzle\Http\Message\Response | null
	 */
	public function fetchPostResource($resource, $postData, $headersOptions)
	{

		$this->request = $this->client->post($resource, [], $postData);
		$this->setRequestHeaders($headersOptions);
		$response = $this->request->send();


		// They have some kind of redirection which returns the 302 response.
		if($response->getStatusCode() == 200 || $response->getStatusCode() === 302) {
			return $response;
		}
	}

    /**
     * HTTP POST request with the action attribute as the URL resource and
     * the combined post data with the hidden fields
     *
     * @param \DOMNode $formNode
     * @param array $mainPostData
     * @param array $headersOptions
     * @return \Guzzle\Http\Message\Response | null
     */
    public function fetchFormPostResource(
        \DOMNode $formNode,
        array $mainPostData,
        array $headersOptions
    ) {
        $postData = array_merge(
            $this->getFormNodeHiddenFields($formNode),
            $mainPostData
        );

        return $this->fetchPostResource(
            $formNode->getAttribute('action'),
            $postData,
            $headersOptions
        );
    }

	/**
	 * Using xpath queries on DOMDocument.
	 * @param String $query
	 * @param String|null $contextNode
	 * @return \DOMNodeList $mixed;
	 */
	public function xPathQuery($query, $contextNode = null)
	{
		$result = $this->xPath->query($query, $contextNode);
		return $result;
	}

	public function setDom($html)
	{
		$this->dom = new DOMDocument();
		@$this->dom->LoadHTML($html);
		$this->xPath = new DOMXPath($this->dom);
	}

	public function getDom()
	{
		return $this->dom;
	}


	public function getXPath()
	{
		return $this->xPath;
	}

	public function getRequest()
	{
		return $this->request;
	}

    /**
     * Record cookies for all requests
     */
    public function addCookiePlugin()
    {
        $cookiePlugin = new \Guzzle\Plugin\Cookie\CookiePlugin(new \Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar());

        $this->client->addSubscriber($cookiePlugin);
    }

    /**
     * Get xpath node
     *
     * @param string $xpath
     * @param DOMNode $contextNode
     * @return DOMNode | null
     */
    public function getXpathNode($xpath, $contextNode = null)
    {
        $nodeList = $this->xPathQuery($xpath, $contextNode);

        if ($nodeList->length > 0) {
            return $nodeList->item(0);
        }
    }

    /**
     * Get Node Dom Document
     *
     * @param type $xpath
     * @return \DOMDocument | null
     */
    public function getNodeDomDocument($xpath)
    {
        $node = $this->getXpathNode($xpath);

        if (!$node) {
            return;
        }

        $html = $this->dom->saveHTML($node);

        $newdoc = new DOMDocument();
        $newdoc->encoding = 'UTF-8';
        $newdoc->formatOutput = false;
        $newdoc->preserveWhiteSpace = true;
        @$newdoc->LoadHTML($html);

        return $newdoc;
    }

    /**
     * Get hidden fields inside a form node
     *
     * @param \DOMNode
     * @return array
     */
    public function getFormNodeHiddenFields(\DOMNode $formNode)
    {
        $fields = [];

        $nodelist = $this->xPathQuery('.//input[@type="hidden"]', $formNode);

        foreach ($nodelist as $node) {
            $key = $node->getAttribute('name');
            $value = $node->getAttribute('value');
            $fields[$key] = $value;
        }

        return $fields;
    }

    public function setSessCookie ($sessCookie, $sessCookieName, $domain)
    {

        $cookie = new Cookie();
        $cookie->setName($sessCookieName);
        $cookie->setValue($sessCookie);
        $cookie->setDomain($domain);

        $jar = new ArrayCookieJar();
        $jar->add($cookie);

        $this->client->addSubscriber(new CookiePlugin($jar));

    }
}
