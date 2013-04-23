<?php
require_once '../../vendor/autoload.php';
require_once 'simple_html_dom.php';
use Evernote\Client;
use EDAM\Error\EDAMUserException;
use EDAM\NoteStore\NoteFilter, EDAM\NoteStore\NotesMetadataResultSpec;
session_start();

$client = new Client(array(
    'token' => $_SESSION['accessToken'],
    'sandbox' => FALSE
));

//input notebookGuid
$notebookGuid = '845a4ed7-2328-4d94-984e-29fa1b374722';

$allImages = array();
$filter = new NoteFilter();
$filter->notebookGuid = $notebookGuid;
$offset = 0;
$spec = new NotesMetadataResultSpec();

do {
    $notesList = $client->getNoteStore()->findNotesMetadata($filter, $offset, 20, $spec);
    $offset = $notesList->startIndex + count($notesList->notes);
    $remain = $notesList->totalNotes - $offset;

    foreach ($notesList->notes as $note) {
        $fullNote = $client->getNoteStore()->getNote($note->guid, true, true, true, true);
        foreach ($fullNote->resources as $resource) {
            switch ($resource->mime) {
                case 'image/gif':
                case 'image/jpeg':
                case 'image/png':
                case 'image/bmp':
                    $html = str_get_html($resource->recognition->body);
                    $str = '';
                    if($html && $html->find('t')){
                        foreach ($html->find('t') as $keyword) {
                            $str = $str.$keyword->plaintext;
                            //echo $keyword->plaintext.'<br>';
                        }
                    }

                    $allImages[] = array(
                        'guid' => $resource->guid,
                        'dataUri' => 'data:' . $resource->mime . ';base64,' . base64_encode($resource->data->body),
                        'recognition' => $str
                    );
                    
                    break;
                default:
                    break;
            }
        }
    }

} while($remain>0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Image Recognition Search</title>
    <meta charset="utf-8">
    <style>
        input {
            display: block;
            width: 500px;
            height: 30px;
            font-size: 20px;
            border: 3px solid #ccc;
            padding: 0 10px;
        }
        .result a {
            float: left;
            margin: 10px 10px 0 0;
            border: 3px solid #ccc;
        }
        .result a:hover{
            border-color: red;
        }
        .result img {
            display: block;
            height: 200px;
        }
        #lightbox {
            background: #000; 
            background: rgba(0,0,0,0.7); 
            height: 100%; 
            width: 100%; 
            position: fixed; 
            top: 0;
            left: 0;
            display: none; 
            z-index: 9999; 
        } 
        #container {
            margin: 0 auto;
            width: 750px;
            height: 550px;
            border-radius: 10px;
            background: #fff;
            padding: 25px;
            text-align: center;
        } 
        #picture {
            max-width: 750px;
            max-height: 490px;
        }
        #keyword{
            width: 744px;
            height: 44px;
            border: 3px solid #ccc;
            margin: 10px 0 0 0;
        }
    </style>
</head>
<body>
    <div id="lightbox">
        <div id="container">
            <img id="picture" src="">
            <textarea id="keyword"></textarea>
        </div>
    </div>
    <input type="text" id="search" placeholder="Image Search">

    <div class="result">
<?php
//show all notebook resources
foreach ($allImages as $image) {
    echo '<a href="'.$image['dataUri'].'" data-keyword="'.$image['recognition'].'" rel="lightbox[result]"><img src="'.$image['dataUri'].'"></a>';
}
?>
    </div>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>
    $(document).ready(function(){
        var ObserveInputValue = function(keyword){
            if(keyword){
                $('a').each(function(item, index){
                    var reg = new RegExp(keyword.toLowerCase(),"g");
                    var count = $(this).attr('data-keyword').toLowerCase().match(reg);
                    if(count && count.length){
                        $(this).show();
                    }
                    else{
                        $(this).hide();
                    }
                });
            }
            else{
                $('a').show();
            }
        };
        setInterval(function(){
            ObserveInputValue($('#search').val());
        }, 100);

        // lightbox effect
        $("a").click(function(e){
            e.preventDefault(); 
            $("#lightbox").fadeIn("slow"); 
            $("#picture").attr("src", $(this).attr("href"));
            $("#keyword").val($(this).attr("data-keyword"));
            $("#container").css("margin-top", ($(window).height() - $("#container").height())/2 -30  + 'px'); 
        }); 
        $("#keyword").click(function(e){
            e.stopPropagation();
            $(this).select();
        });
        $("#lightbox").click(function(){
            $("#lightbox").fadeOut("fast");
        });
    });
    </script>
</body>
</html>