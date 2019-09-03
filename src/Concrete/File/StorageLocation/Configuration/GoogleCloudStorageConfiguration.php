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
    protected $projectId;

    /**
     * Bucket name
     *
     * @var string
     */
    protected $bucketName;

    /**
     * Json key file
     * Key file of a service account
     * See under: GCP -> IAM -> Service accounts
     *
     * @var string
     */
    protected $keyfile;

    /**
     * Get the google storage adapter
     *
     * @return AdapterInterface|GoogleStorageAdapter
     */
    public function getAdapter()
    {
        $storageClient = new StorageClient([
            'projectId' => $this->getProjectId(),
            'keyFile' => json_decode($this->getKeyFile()),
        ]);
        $bucket = $storageClient->bucket($this->getBucketName());

        $adapter = new GoogleStorageAdapter($storageClient, $bucket);

        return $adapter;
    }

    public function hasPublicURL()
    {
        // TODO: Implement hasPublicURL() method.
    }

    public function getPublicURLToFile($file)
    {
        // TODO: Implement getPublicURLToFile() method.
    }

    public function hasRelativePath()
    {
        // TODO: Implement hasRelativePath() method.
    }

    public function getRelativePathToFile($file)
    {
        // TODO: Implement getRelativePathToFile() method.
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
        $this->keyfile = $config['keyFile'];
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
        $this->keyfile = $config['keyFile'];

        // Check for required settings
        if(!$this->projectId) {
            $error->add(t('Please provide the project id.'));
        } else if(!$this->bucketName) {
            $error->add(t('Please provide the bucket name.'));
        } else if(!$this->keyfile) {
            $error->add(t('Please provide the content of the keyfile.json from the GCP service account.'));
        }
    }

    public function getProjectId()
    {
        return $this->projectId;
    }

    public function getBucketName()
    {
        return $this->bucketName;
    }

    public function getKeyFile()
    {
        return $this->keyfile;
    }
}