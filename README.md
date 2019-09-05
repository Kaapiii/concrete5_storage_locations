
Concrete5 package v8 - Storage locations on AWS S3 and Google Cloud Storage
======

Installation
------------------


1. Add the following line to line to the 'require' section of the concrete5 composer.json.

   ```
   "kaapiii/concrete5_storage_locations": "^0.1"
   ```
        
   or...
   
   ```
   composer require kaapiii/concrete5_storage_locations
   ```
   
2. Run the following command from the installation {root} folder
   
   ``` 
   composer install
   ```

3. Install the package
4. Navigate to **System & Settings -> File Storage Locations** and add a new storage location.


Cloud Storage preparations
---------------------------

### Google Cloud Storage

Make the following steps either in the web console or copy and paste the commands below to your cloud shell.

#### Create a bucket

Go to your project on gcp and create a public bucket with enabled ACL.

```
gsutil mb -p [PROJECT_NAME] -c [STORAGE_CLASS] -l [BUCKET_LOCATION] on gs://[BUCKET_NAME]/
```

Example:
```
gsutil mb -p my-gcp-project -c regional -l europe-west3 -b on gs://my-public-c5-bucket
```

#### Make the bucket public

Make all objects in a bucket publicly readable.
```
gsutil iam ch allUsers:objectViewer gs://[BUCKET_NAME]
```


#### Create a service account

Create a service account through the web console with the following minimal IAM role:

(Role title / [role key])

- **Storage Object Admin** / [roles/storage.objectAdmin]

Create a key in the json format and download the key file.
