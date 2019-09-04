<?php

namespace Concrete\Package\Concrete5StorageLocations\File\StorageLocation\Configuration;

use Concrete\Core\Error\ErrorList\Error\Error;
use Concrete\Core\Error\ErrorList\ErrorList;
use Concrete\Core\File\StorageLocation\Configuration\ConfigurationInterface;
use Concrete\Core\File\StorageLocation\Configuration\Configuration;
use Concrete\Core\Http\Request;
use Google\Cloud\Storage\StorageClient;
use League\Flysystem\AdapterInterface;
use Superbalist\Flysystem\GoogleStorage\GoogleStorageAdapter;

class GoogleCloudStorageConfiguration extends Configuration implements ConfigurationInterface
{

    /**
     * Id of the project containing the bucket
     *
     * @var string
     */
    private $projectId;

    /**
     * Bucket name
     *
     * @var string
     */
    private $bucketName;

    /**
     * Json key file
     * Key file of a service account
     * See under: GCP -> IAM -> Service accounts
     *
     * @var string
     */
    private $keyFile;

    /**
     * Custom domain for file urls
     *
     * @var string
     */
    private $customDomain;


    /**
     * Get the google storage adapter
     *
     * @return AdapterInterface|GoogleStorageAdapter
     */
    public function getAdapter()
    {
        $storageClient = new StorageClient([
            'projectId' => $this->getProjectId(),
            'keyFile' => json_decode($this->getKeyFile(), true),
        ]);
        $bucket = $storageClient->bucket($this->getBucketName());

        $adapter = new GoogleStorageAdapter($storageClient, $bucket);

        return $adapter;
    }

    /**
     * @return bool
     */
    public function hasPublicURL()
    {
        return true;
    }

    /**
     * @param string $file
     * @return string
     */
    public function getPublicURLToFile($file)
    {
        return $this->buildPublicFileUrl($file);
    }

    /**
     * Needs to be true, otherwise the thumbnails won't show up in the file manager
     *
     * @return bool
     */
    public function hasRelativePath()
    {
        return true;
    }

    /**
     * Relative paths needs to be prefixed with external url
     *
     * @param string $file
     * @return string
     */
    public function getRelativePathToFile($file)
    {
        return $this->buildPublicFileUrl($file);
    }

    /**
     * Used on the add and update view
     *
     * @param Request $req
     */
    public function loadFromRequest(Request $req)
    {
        $config = $req->get('storageLocationConfig');

        $this->projectId = $config['projectId'];
        $this->bucketName = $config['bucketName'];
        $this->keyFile = $config['keyFile'];
        $this->customDomain = $config['customDomain'];
    }

    /**
     * Validate a request, this is used during saving
     *
     * @param Request $req
     * @return Error|void
     */
    public function validateRequest(Request $req)
    {
        $error = new ErrorList();

        $config = $req->get('storageLocationConfig');

        $this->projectId = $config['projectId'];
        $this->bucketName = $config['bucketName'];
        $this->keyFile = $config['keyFile'];
        $this->customDomain = $config['customDomain'];

        // Check for required settings
        if(!$this->projectId) {
            $error->add(t('Please provide the project id.'));
        } else if(!$this->bucketName) {
            $error->add(t('Please provide the bucket name.'));
        } else if(!$this->keyFile) {
            $error->add(t('Please provide the content of the keyfile.json from a GCP service account.'));
        }
    }

    public function buildPublicFileUrl($file)
    {
        if($this->getCustomDomain()){
            # Custom domain URL
            $cleanCustomUrl = trim(preg_replace('(^https?://)','', $this->getCustomDomain()), '/');

            $url = 'https://' . $cleanCustomUrl . $file;
        } else {
            # Default URL
            $url = 'https://storage.cloud.google.com/'.$this->getBucketName().$file;
        }
        return $url;
    }

    /**
     * Get project id
     *
     * @return string
     */
    public function getProjectId()
    {
        return $this->projectId;
    }

    /**
     * Get bucket name
     *
     * @return string
     */
    public function getBucketName()
    {
        return $this->bucketName;
    }

    /**
     * Get key file content
     *
     * @return string
     */
    public function getKeyFile()
    {
        return $this->keyFile;
    }

    /**
     * Get custom domain
     *
     * @return string
     */
    public function getCustomDomain()
    {
        return $this->customDomain;
    }
}