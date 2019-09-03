<?php

$form = Loader::helper('form');

/** @var $configuration \Concrete\Package\Concrete5StorageLocations\Src\File\StorageLocation\Configuration\GoogleCloudStorageConfiguration */
if (is_object($configuration)) {

    $projectId = $configuration->getProjectId();
    $bucketName = $configuration->getBucketName();
    $keyfile= $configuration->getKeyFile();

} else {
    $projectId = '';
    $bucketName = '';
    $keyfile = '';

}
?>
<fieldset>
    <div class="form-group">
        <label for="accesskey"><?= t('ProjectId');?></label>
        <div class="input-group">
            <?= $form->text('storageLocationConfig[projectId]', $projectId, array('placeholder' => t('Google project id')));?>
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
    <div class="form-group">
        <label for="bucket-name"><?=t('Keyfile.json')?></label>
        <div class="input-group">
            <?= $form->textarea('storageLocationConfig[keyFile]', $keyfile,
                array(
                    'placeholder' => t('Content of the keyfile.json'),
                    'style' => 'height: 200px;',
                )
            )?>
            <span class="input-group-addon"><i class="fa fa-asterisk"></i></span>
        </div>
    </div>
</fieldset>
