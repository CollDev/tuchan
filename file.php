<?php
    if(isset($_POST['mimetype'])){
        $type = $_POST['mimetype']; 
    }else{
        $type= '';
    }
    $xhr = $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'; 
    if ($type == 'xml') { 
        header('Content-type: text/xml'); 
?> 
        <address attr1="value1" attr2="value2"> 
            <street attr="value">A &amp; B</street> 
            <city>Palmyra</city> 
        </address> 
<?php 
    } 
    else if ($type == 'json') { 
        // wrap json in a textarea if the request did not come from xhr 
        if (!$xhr) echo '<textarea>'; 
?> 

{ 
    "library": "jQuery", 
    "plugin":  "form", 
    "hello":   "goodbye", 
    "tomato":  "tomoto" 
} 

<?php 
        if (!$xhr) echo '</textarea>'; 
    } 
    else if ($type == 'script') { 
        // wrap script in a textarea if the request did not come from xhr 
        if (!$xhr) echo '<textarea>'; 
?> 

for (var i=0; i < 2; i++) 
    alert('Script evaluated!'); 

<?php 
        if (!$xhr) echo '</textarea>'; 
    } 
    else {
        
        $idUniq = uniqid();
        $ext = @end(explode('.', $_FILES['video']['name']));
        $nameVideo = $idUniq . '.' . $ext;
        umask(0);
        move_uploaded_file($_FILES["video"]["tmp_name"],"uploads/videos/" . $nameVideo);
        //move_uploaded_file($_FILES["video"]["tmp_name"],"uploads/videos/" . $_FILES["video"]["name"]);
        echo '<input type="hidden" id="name_file_upload" name="name_file_upload" value="'.$nameVideo.'" />';
        // return text var_dump for the html request 
        /*echo "VAR DUMP:<p />"; 
        var_dump($_POST); 
        var_dump($_FILES); 
        foreach($_FILES as $file) { 
            $n = $file['name']; 
            $s = $file['size']; 
            if (!$n) continue; 
            echo "File: $n ($s bytes)";*/ 
        //} 
    } 