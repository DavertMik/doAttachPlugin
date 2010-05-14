doAttachPlugin
--------------
A basic Symfony plugin that adds new behavior to Doctrine objects - Attachable. By this behavior you can assign one file for every row in your table. 

Principles
----------
* Adds a 'filename' field to table
* File is stored accotring to object's Model class and Id. Thus, files with the same name will not be replaced in dir.
* Provides methods to retrieve filename, file path, and file url.
* Files are stored in /web/uploads/-model-name-/

Use cases
---------
* store user avatars
* attach files to forum posts
* save product and company logos
* etc..

API
---------	  
Adds several methods to object

* attach($file) - assigns file as attached. A previously attached file removed
* getFilename() - gets a name of file as it was uploaded by user
* getFilePath() - gets a full path to file
* getFileUrl() - a url to download a file

Example
-------
* Define and build a model

       Contractor:
         actAs: [Timestampable, Attachable]
         columns:
           user_id:    { type: integer(4), notnull: true, primary: true }
           company: {type: string(255) }
           contact_email: {type: string(255)}
           phone: {type: string(255)}

* Recieve uploaded file in controller

       [php]
        $contractor = new Contractor();
        $contractor->attach($request->getFile('file');
	    $contractor->save();
	  
* Use file access API in templates

        [php] 
        link_to($contractor->getFilename(),$contractor->getFileUrl()); 

In Forms
--------
For not to run attach() method for all objects that are saved in form, you can just update your form class.

In /lib/form/doctrine write this:

      [php]
      abstract class BaseFormDoctrine extends sfFormDoctrine
      {
        protected function processUploadedFile($field, $filename = null, $values = null) {
          if ($this->validatorSchema[$field] instanceof sfValidatorFile && $field == 'filename' && $this->getObject()->getTable()->hasTemplate('Attachable')) {
            $this->getObject()->attach($this->getValue('filename'));
            unset($this->values['filename']);
          } else {
          parent::processUploadedFile($field,$filename,$values);
        }
      }
    }

Now all uploaded 'Filename' files are automatically attached to your models.