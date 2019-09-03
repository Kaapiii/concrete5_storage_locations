<?php

$form = Loader::helper('form');

/** @var $configuration \Concrete\Package\Concrete5StorageLocations\Src\File\StorageLocation\Configuration\AwsS3Configuration */
if (is_object($configuration)) {

    $accessKey = $configuration->getAccessKey();
    $secret = $configuration->getSecret();
    $bucketName = $configuration->getBucketName();
    $region = $configuration->getRegion();
    $version = $configuration->getVersion();

    $regions = $configuration->getRegions();
    $versions = $configuration->getApiVersions();
} else {
    $accessKey = '';
    $secret = '';
    $bucketName = '';
    $region = '';
    $version = '';

    $regions = '';
    $versions = '';
}
?>
<fieldset>
    <div class="form-group">
        <label for="accesskey"><?= t('Access key');?></label>
        <div class="input-group">
            <?= $form->text('storageLocationConfig[accessKey]', $accessKey, array('placeholder' => t('Access key')));?>
            <span class="input-group-addon"><i class="fa fa-asterisk"></i></span>
        </div>
    </div>
    <div class="form-group">
        <label for="secret"><?= t('Secret key');?></label>
        <div class="input-group">
            <?= $form->text('storageLocationConfig[secret]', $secret, array('placeholder' => t('Secret key')));?>
            <span class="input-group-addon"><i class="fa fa-asterisk"></i></span>
        </div>
    </div>
    <div class="form-group">
        <label for="bucket-name"><?=t('Bucket name')?></label>
        <div class="input-group">
            <?= $form->text('storageLocationConfig[bucketName]', $bucketName, array('placeholder' => t('Bucket name')))?>
            <span class="input-group-addon"><i class="fa fa-asterisk"></i></span>
        </div>
    </div>
</fieldset>
<fieldset>
    <legend><?= t('Optional settings') ;?></legend>
    <div class="form-group">
        <label for="region"><?= t('Set AWS S3 region');?></label>
        <?= $form->select('storageLocationConfig[region]', $regions, $region);?>
    </div>
    <div class="form-group">
        <label for="version"><?= t('AWS S3 API Version');?></label>
        <?= $form->select('storageLocationConfig[region]', $versions, $version);?>
    </div>
</fieldset>
