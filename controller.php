<?php

namespace Concrete\Package\Concrete5StorageLocations;

use Concrete\Core\Package\Package;
use Concrete\Core\Entity\Package as PackageEntity;
use Concrete\Core\File\StorageLocation\Type\Type as StorageLocationType;
use Concrete\Core\Package\PackageService;
use Concrete\Core\Support\Facade\Application as ApplicationFacade;

/**
 * Package Controller - Concrete5 Storage Locations
 */
class Controller extends Package
{
    protected $pkgHandle = 'concrete5_storage_locations';
    protected $appVersionRequired = '8.0.0';
    protected $pkgVersion = '0.3';

    protected $pkgAutoloaderMapCoreExtensions = true;

    public function getPackageDescription()
    {
        return t('Package adds new storage locations on AWS S3 & Google Cloud Storage');
    }

    public function getPackageName()
    {
        return t('Concrete5 storage locations');
    }

    public function install()
    {
        $pkg = parent::install();

        $this->installStorageLocations($pkg);
    }

    public function upgrade()
    {
        parent::upgrade();

        $app = ApplicationFacade::getFacadeApplication();
        $pkg = $app->make(PackageService::class)->getByHandle($this->pkgHandle);

        $this->installStorageLocations($pkg);
    }

    /**
     * Install supported storage locations
     *
     * @param PackageEntity $pkg
     */
    protected function installStorageLocations(PackageEntity $pkg)
    {
        $storageLocations = $this->getSupportedStorageLocations();

        if(is_array($storageLocations) && count($storageLocations) > 0){
            foreach($storageLocations as $key => $label){

                $strglctn = StorageLocationType::getByHandle($key);

                if(!is_object($strglctn)){
                    StorageLocationType::add($key, $label, $pkg);
                }
            }
        }
    }

    /**
     * Array with supported storage locations
     *
     * @return array
     */
    protected function getSupportedStorageLocations()
    {
        return [
            'aws_s3' => 'Amazon S3',
            'google_cloud_storage' => 'Google Cloud Storage'
        ];
    }
}