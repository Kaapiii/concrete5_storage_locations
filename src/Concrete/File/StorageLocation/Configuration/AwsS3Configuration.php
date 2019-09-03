<?php

namespace Concrete\Package\Concrete5StorageLocations\File\StorageLocation\Configuration;

use Aws\S3\S3Client;
use Concrete\Core\File\StorageLocation\Configuration\ConfigurationInterface;
use Concrete\Core\File\StorageLocation\Configuration\Configuration;
use Concrete\Core\Error\ErrorList\ErrorList;
use Concrete\Core\Error\ErrorList\Error\Error;
use Concrete\Core\Http\Request;
use League\Flysystem\AdapterInterface;
use League\Flysystem\AwsS3v3\AwsS3Adapter;

/**
 * Class AwsS3Configuration
 */
class AwsS3Configuration extends Configuration implements ConfigurationInterface
{

    /**
     * S3 access key
     *
     * @var string
     */
    private $accessKey;

    /**
     * S3 secret key
     *
     * @var string
     */
    private $secret;

    /**
     * S3 bucket name
     *
     * @var string
     */
    private $bucketName;

    /**
     * S3 bucket region
     *
     * @var string
     */
    private $region;

    /**
     * S3 API version
     *
     * @var string
     */
    private $version;

    /**
     * Get the AWS S3 Adapter
     *
     * @return AdapterInterface|AwsS3Adapter
     */
    public function getAdapter()
    {
        $client = new S3Client([
            'credentials' => [
                'key'    => $this->accessKey,
                'secret' => $this->secret,
            ],
            'region' => $this->region,
            'version' => $this->version, // or "latest"
            //'profile' => 'default', #if enabled - the library searches for credentials in {webserver_root}/.aws/credentials
        ]);

        $bucket = 'mbt-public-media';
        return new AwsS3Adapter($client, $bucket);
    }

    public function hasPublicURL()
    {
        return true;
    }

    public function getPublicURLToFile($file)
    {
        return $this->getBucketUrl().$file;
    }

    public function hasRelativePath()
    {
        return false;
    }

    public function getRelativePathToFile($file)
    {
        return $this->getBucketUrl().$file;
    }

    /**
     * Used on the add and update view
     *
     * @param Request $req
     */
    public function loadFromRequest(Request $req)
    {
        $config = $req->get('storageLocationConfig');

        $this->bucketName = $config['bucketName'];
        $this->accessKey = $config['accessKey'];
        $this->secret = $config['secret'];

        $this->region = $config['region'];
        $this->version = $config['version'];
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

        $this->bucketName = $config['bucketName'];
        $this->accessKey = $config['accessKey'];
        $this->secret = $config['secret'];

        $this->region = $config['region'];
        $this->version = $config['version'];

        // Check for required settings
        if(!$this->bucketName) {
            $error->add(t('Please provide an AWS S3 bucket name.'));
        } else if(!$this->accessKey) {
            $error->add(t('Please provide a AWS S3 access key'));
        } else if(!$this->secret) {
            $error->add(t('Please provide a AWS S3 secret key.'));
        } else if(!$this->version) {
            $error->add(t('Please provide a AWS S3 API version.'));
        }
    }


    public function getAccessKey()
    {
        return $this->accessKey;
    }

    public function getSecret()
    {
        return $this->secret;
    }

    public function getBucketName()
    {
        return $this->bucketName;
    }

    public function getRegion()
    {
        return $this->region;
    }

    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Get supported AWS S3 Regions
     * Up to date regions: https://docs.aws.amazon.com/en_en/general/latest/gr/rande.html
     *
     * @return array
     */
    public function getRegions()
    {

        $regions = array(
            ""                  => "Default (empty)",
            "us-east-2"			=> "US East (Ohio)",
            "us-east-1"			=> "US East (N. Virginia)",
            "us-west-1"			=> "US West (N. California)",
            "us-west-2"			=> "US West (Oregon)",
            //"ap-east-1"         => "Asia Pacific (Hong Kong)***",
            "ap-south-1"		=> "Asia Pacific (Mumbai)",
            //"ap-northeast-3"	=> "Asia Pacific (Osaka-Local)****",
            "ap-northeast-2"	=> "Asia Pacific (Seoul)",
            "ap-southeast-1"	=> "Asia Pacific (Singapore)",
            "ap-southeast-2"	=> "Asia Pacific (Sydney)",
            "ap-northeast-1"	=> "Asia Pacific (Tokyo)",
            "ca-central-1"		=> "Canada (Central)",
            "cn-north-1"	    => "China (Beijing)",
            "cn-northwest-1"	=> "China (Ningxia)",
            "eu-central-1"		=> "EU (Frankfurt)",
            "eu-west-1"		    => "EU (Ireland)",
            "eu-west-2"		    => "EU (London)",
            "eu-west-3"		    => "EU (Paris)",
            "eu-north-1"		=> "EU (Stockholm)",
            "sa-east-1"		    => "South America (São Paulo)",
            "me-south-1"		=> "Middle East (Bahrain)",
        );
        // ***You must enable this Region before you can use it.
        // ****You can use the Asia Pacific (Osaka-Local) Region only in conjunction with the Asia Pacific
        // (Tokyo) Region. To request access to the Asia Pacific (Osaka-Local) Region, contact your sales representative.

        return $regions;
    }

    /**
     * Get the Rest API Version
     *
     * @return array
     */
    public function getApiVersions()
    {
        $version = array(
            "2006-03-01"    => "2006-03-01 (default)",
            "latest"        => "latest",
        );
        return $version;
    }

    /**
     * Build the bucket URL
     *
     * @return string
     */
    protected function getBucketUrl()
    {

        //https://mbt-public-media.s3.eu-central-1.amazonaws.com/3815/6754/1588/berg_card_img1.jpg
        //https://mbt-public-media.s3.eu-central-1.amazonaws.com/3815/6754/1588/berg_card_img1.jpg

        return 'https://'.$this->bucketName.'.s3.'.($this->region ? $this->region : '').'.amazonaws.com';
    }
}
