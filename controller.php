<?php

namespace Concrete\Package\Concrete5StorageLocations;

use Concrete\Core\Package\Package;
use Concrete\Core\File\StorageLocation\Type\Type as StorageLocationType;

/**
 * Package Controller - Concrete5 Storage Locations
 */
class Controller extends Package
{
    protected $pkgHandle = 'concrete5_storage_locations';
    protected $appVersionRequired = '8.0.0';
    protected $pkgVersion = '0.0.1';

    protected $pkgAutoloaderMapCoreExtensions = true;

    public function getPackageDescription()
    {
        return t('Packages add new storage locations for files on AWS S3 & Google Cloud Storage');
    }

    public function getPackageName()
    {
        return t('Concrete5 storage locations');
    }

    public function install()
    {
        $pkg = parent::install();

        StorageLocationType::add('aws_s3', 'Amazon S3 (Kaapiii)', $pkg);
        //StorageLocationType::add('google_cloud_storage', 'Google Cloud Storage', $pkg);
    }
}