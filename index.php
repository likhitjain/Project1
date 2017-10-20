<?php

//turn on debugging messages.
ini_set('display_errors', 'On');
error_reporting(E_ALL);


//instantiate the program object

//Class to load classes it finds the file when the progrm starts to fail for calling a missing class
class Manage {
    public static function autoload($class) {
        //you can put any file name or directory here
        include $class . '.php';
    }
}

spl_autoload_register(array('Manage', 'autoload'));

//instantiate the program object
$obj = new main();


class main {

    public function __construct()
    {
        //print_r($_REQUEST);
        //set default page request when no parameters are in URL
        $pageRequest = 'uploadform';
        //check if there are parameters
        if(isset($_REQUEST['page'])) {
            //load the type of page the request wants into page request
            $pageRequest = $_REQUEST['page'];
        }

        //instantiate the class that is being requested
         $page = new $pageRequest;


        if($_SERVER['REQUEST_METHOD'] == 'GET') {
            $page->get();
        } else {
            $page->post();
        }

    }

}

abstract class page {
    protected $html;

    public function __construct()
    {
        $this->html .= '<html>';
        $this->html .= '<link rel="stylesheet" href="styles.css">';
        $this->html .= '<body>';
    }
    public function __destruct()
    {
        $this->html .= '</body></html>';
        echo $this->html;
    }

    public function get() {
        echo 'default get message';
    }

    public function post() {
        print_r($_POST);
    }
}

class uploadform extends page
{

    public function get()
    {   
        //static funtion is used because Upload Form will repeat everytime.
        $this->html.=staticfunction::createform(); 
    }

    public function post() {

      //set path of the file to be uploaded
      $target_dir = "uploads/";
      $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

      //upload the file to AFS
      if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
        header::redirect($target_file);
            
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
  }
}

class header {

    public static function redirect($target_file) {
         //forward the user to the new page in htmlTable class
         header('Location: index.php?page=htmlTable&csvFile=' . $target_file); 
         
          }
      }



class staticfunction {

    //Upload Form
    public static function createform() {
        $form = '<h1>Upload Form</h1>';
        $form.= '<form action="index.php?page=uploadform" method="post"
        enctype="multipart/form-data">';
        $form .= '<input type="file" name="fileToUpload" id="fileToUpload">';
        $form .= '<br> <hr> <input type="submit" value="Upload Image" name="submit"> </br> </hr>';
        $form .= '</form> ';
        return $form;

    }

}
 
class htmlTable extends page {


      public function get() {

        //displays the content of CSV file in a HTML Table
        $filePath = $_GET['csvFile'];
        //opens the file from header function
        $f = fopen("$filePath", "r"); 
        $x ='<table border = "1"><tr>';
        //Here fgetcsv parse CSV file as an array
        while (($line = fgetcsv($f)) !== false) { 

            for ($i=0; $i < sizeof($line) ; $i++) {             
                 $row = $line[$i];
                 $cells = explode(";",$row); 
                    foreach ($cells as $cell) {
                         $x.= "<td> $cell </td>" ;
                    }
             }
        $x.= "</tr>";
}

  $x.= '</table>';
  //closes the open file
  fclose($f); 
  $this->html.= $x;
    
 }

}

 
?>