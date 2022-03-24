<?
class FaceAttr implements JsonSerializable
{

    public $title;
    public $total_count;
    public $total_percent;
    public $exponent_percent;

    public function jsonSerialize()
    {
        return [
            'T' => $this->title,
            't_c' => $this->total_count,
            't_p' => $this->total_percent,
            'e_p' => $this->exponent_percent
        ];
    }
}

class ReturnToken implements JsonSerializable
{

    public $title;
    public $generated_hash;
    public $background_attr;
    public $eye_attr;
    public $face_attr;
    public $hat_attr;
    public $jewelery_attr;
    public $lips_attr;
    public $mask_atrr;
    public $shirt_attr;
    public $small_preview_hash;
    public $mid_preview_hash;
    public $max_preview_hash;
    public $rarity;
    public $number;

    public function jsonSerialize()
    {
        return [
            'T' => $this->title,
            'g_h' => $this->generated_hash,
            'b_a' => $this->background_attr,
            'e_a' => $this->eye_attr,
            'f_a' => $this->face_attr,
            'h_a' => $this->hat_attr,
            'j_a' => $this->jewelery_attr,
            'l_a' => $this->lips_attr,
            'm_a' => $this->mask_atrr,
            's_a' => $this->shirt_attr,
            'sm_p_h' => $this->small_preview_hash,
            'md_p_h' => $this->mid_preview_hash,
            'mx_p_h' => $this->max_preview_hash,
            'rrt' => $this->rarity,
            'nbr' => $this->number,
        ];
    }
}

class GeneratedFaces implements JsonSerializable
{
    public $attr_background = array();
    public $attr_eyes = array();
    public $attr_face = array();
    public $attr_hat = array();
    public $attr_jewelery = array();
    public $attr_lips = array();
    public $attr_mask = array();
    public $attr_shirt = array();
    public $generated_faces = array();

    public function jsonSerialize()
    {
        return [
            'a_b' => $this->attr_background,
            'a_e' => $this->attr_eyes,
            'a_f' => $this->attr_face,
            'a_h' => $this->attr_hat,
            'a_j' => $this->attr_jewelery,
            'a_l' => $this->attr_lips,
            'a_m' => $this->attr_mask,
            'a_s' => $this->attr_shirt,
            'g_fs' => $this->generated_faces,
        ];
    }
}

$return = new GeneratedFaces();
$file = fopen($_SERVER['DOCUMENT_ROOT'] . "/scripts/new attributes.csv", "r");
if ($file) {
    $lineNumber = 0;
    while (($buffer = fgets($file, 4096)) !== false) {
        $lineNumber++;
        $buffer = iconv("CP1251", "UTF-8", $buffer);
        $buffer = str_replace(array("\n", "\r"), '', $buffer);

        list($type, $title, $total_count, $total_percent, $exponent_percent, $number) = explode(";", $buffer);
        $newAttribute = new FaceAttr();
        $newAttribute->title = $title;
        $newAttribute->total_count = $total_count;
        $newAttribute->total_percent = $total_percent;
        $newAttribute->exponent_percent = $exponent_percent;

        switch ($type) {
            case '1':
                array_push($return->attr_face, $newAttribute);
                break;
            case '2':
                array_push($return->attr_hat, $newAttribute);
                break;
            case '3':
                array_push($return->attr_background, $newAttribute);
                break;
            case '4':
                array_push($return->attr_mask, $newAttribute);
                break;
            case '5':
                array_push($return->attr_jewelery, $newAttribute);
                break;
            case '6':
                array_push($return->attr_eyes, $newAttribute);
                break;
            case '7':
                array_push($return->attr_lips, $newAttribute);
                break;
            case '8':
                array_push($return->attr_shirt, $newAttribute);
                break;
            default:
                echo "Error " . $type . " <br>";
                break;
        }
    }
    //echo json_encode($return, JSON_PRETTY_PRINT);
    if (!feof($file)) {
        echo "Error: unexpected fgets() fail<br>";
        //$errorMessage("file: foruploading/РІСЃРµСЃС‚СѓРґРµРЅС‚С‹.csv", 'Error: unexpected fgets() fail');
    }
    fclose($file);
}

$file = fopen($_SERVER['DOCUMENT_ROOT'] . "/scripts/updatedDataForMySql.csv", "r");
if ($file) {
    $lineNumber = 0;
    while (($buffer = fgets($file, 4096)) !== false) {
        $lineNumber++;
        $buffer = iconv("CP1251", "UTF-8", $buffer);
        $buffer = str_replace(array("\n", "\r"), '', $buffer);

        list(
            $title,
            $face_attr,
            $hat_attr,
            $background_attr,
            $mask_atrr,
            $jewelery_attr,
            $eye_attr,
            $lips_attr,
            $shirt_attr,
            $rarity,
            $generated_hash,
            $small_preview_hash,
            $mid_preview_hash,
            $max_preview_hash,
        ) = explode(";", $buffer);

        $t = explode('.', $title);
        $number = $t[0];

        $token = new ReturnToken();
        $token->title = $title;

        if (!array_key_exists($face_attr, $return -> attr_face)) {
            exit ("<h1>No Such key in face_attr array</h1>". $face_attr);
        }
        $token->face_attr = $return -> attr_face[$face_attr];

        if (!array_key_exists($hat_attr, $return -> attr_hat)) {
            exit ("<h1>No Such key in face_attr array</h1>". $hat_attr);
        }
        $token->hat_attr = $return -> attr_hat[$hat_attr];

        if (!array_key_exists($background_attr, $return -> attr_background)) {
            exit ("<h1>No Such key in face_attr array</h1>". $background_attr);
        }
        $token->background_attr = $return -> attr_background[$background_attr];

        if (!array_key_exists($mask_atrr, $return -> attr_mask)) {
            exit ("<h1>No Such key in face_attr array</h1>". $mask_atrr);
        }
        $token->mask_atrr = $return -> attr_mask[$mask_atrr];

        if (!array_key_exists($jewelery_attr, $return -> attr_jewelery)) {
            exit ("<h1>No Such key in face_attr array</h1>". $jewelery_attr);
        }
        $token->jewelery_attr = $return -> attr_jewelery[$jewelery_attr];

        if (!array_key_exists($eye_attr, $return -> attr_eyes)) {
            exit ("<h1>No Such key in face_attr array</h1>". $eye_attr);
        }
        $token->eye_attr = $return -> attr_eyes[$eye_attr];

        if (!array_key_exists($lips_attr, $return -> attr_lips)) {
            exit ("<h1>No Such key in face_attr array</h1>". $lips_attr);
        }
        $token->lips_attr = $return -> attr_lips[$lips_attr];

        if (!array_key_exists($shirt_attr, $return -> attr_shirt)) {
            exit ("<h1>No Such key in face_attr array</h1>". $shirt_attr);
        }
        $token->shirt_attr = $return -> attr_shirt[$shirt_attr];

        $token->rarity = $rarity;
        $token->generated_hash = "";
        $token->small_preview_hash = "";
        $token->mid_preview_hash = "";
        $token->max_preview_hash = "";
        $token->number = $number;

        $return -> generated_faces[$number] = $token;
        //array_push($return -> generated_faces, $token);
    }
    ksort($return -> generated_faces);
    $return -> generated_faces = array_values($return -> generated_faces);
    //echo json_encode($return -> generated_faces, JSON_PRETTY_PRINT);
    if (!feof($file)) {
        echo "Error: unexpected fgets() fail<br>";
        //$errorMessage("file: foruploading/РІСЃРµСЃС‚СѓРґРµРЅС‚С‹.csv", 'Error: unexpected fgets() fail');
    }
    fclose($file);
}


$file = fopen($_SERVER['DOCUMENT_ROOT'] . "/scripts/hashStat.csv", "r");
if ($file) {
    $lineNumber = 0;
    while (($buffer = fgets($file, 4096)) !== false) {
        $lineNumber++;
        $buffer = iconv("CP1251", "UTF-8", $buffer);
        $buffer = str_replace(array("\n", "\r"), '', $buffer);
        list($hash, $key) = explode(";", $buffer);
        $k_a = explode(".", $key);
        if (!is_numeric($k_a[0])) {
            exit ("error Key is not number ".$k[0]);
        }
        $i_k = intval($k_a[0]);
        if (!array_key_exists($i_k - 1, $return -> generated_faces)) {
            exit ("<h1>No Such key</h1>". $key);
        }
        
        ($return -> generated_faces[$i_k - 1]) -> generated_hash = $hash;
    }
    if (!feof($file)) {
        echo "Error: unexpected fgets() fail<br>";
        //$errorMessage("file: foruploading/РІСЃРµСЃС‚СѓРґРµРЅС‚С‹.csv", 'Error: unexpected fgets() fail');
    }
    fclose($file);
}
$file = fopen($_SERVER['DOCUMENT_ROOT'] . "/scripts/smallPreviewHash.csv", "r");
if ($file) {
    $lineNumber = 0;
    while (($buffer = fgets($file, 4096)) !== false) {
        $lineNumber++;
        $buffer = iconv("CP1251", "UTF-8", $buffer);
        $buffer = str_replace(array("\n", "\r"), '', $buffer);
        list($hash, $key) = explode(";", $buffer);
        $k_a = explode(".", $key);

        if (!is_numeric($k_a[0])) {
            exit ("error Key is not number ".$k[0]);
        }
        $i_k = intval($k_a[0]);

        if (!array_key_exists($i_k - 1, $return -> generated_faces)) {
            exit ("<h1>No Such key</h1>". $key);
        }
        ($return -> generated_faces[$i_k - 1]) -> small_preview_hash = $hash;
    }
    if (!feof($file)) {
        echo "Error: unexpected fgets() fail<br>";
        //$errorMessage("file: foruploading/РІСЃРµСЃС‚СѓРґРµРЅС‚С‹.csv", 'Error: unexpected fgets() fail');
    }
    fclose($file);
}
$file = fopen($_SERVER['DOCUMENT_ROOT'] . "/scripts/midPreviewHash.csv", "r");
if ($file) {
    $lineNumber = 0;
    while (($buffer = fgets($file, 4096)) !== false) {
        $lineNumber++;
        $buffer = iconv("CP1251", "UTF-8", $buffer);
        $buffer = str_replace(array("\n", "\r"), '', $buffer);
        list($hash, $key) = explode(";", $buffer);
        $k_a = explode(".", $key);

        if (!is_numeric($k_a[0])) {
            exit ("error Key is not number ".$k[0]);
        }
        $i_k = intval($k_a[0]);

        if (!array_key_exists($i_k - 1, $return -> generated_faces)) {
            exit ("<h1>No Such key</h1>". $key);
        }

        ($return -> generated_faces[$i_k - 1]) -> mid_preview_hash = $hash;
    }
    if (!feof($file)) {
        echo "Error: unexpected fgets() fail<br>";
        //$errorMessage("file: foruploading/РІСЃРµСЃС‚СѓРґРµРЅС‚С‹.csv", 'Error: unexpected fgets() fail');
    }
    fclose($file);
}
$file = fopen($_SERVER['DOCUMENT_ROOT'] . "/scripts/maxPreviewHash.csv", "r");
if ($file) {
    $lineNumber = 0;
    while (($buffer = fgets($file, 4096)) !== false) {
        $lineNumber++;
        $buffer = iconv("CP1251", "UTF-8", $buffer);
        $buffer = str_replace(array("\n", "\r"), '', $buffer);
        list($hash, $key) = explode(";", $buffer);
        $k_a = explode(".", $key);

        if (!is_numeric($k_a[0])) {
            exit ("error Key is not number ".$k[0]);
        }
        $i_k = intval($k_a[0]);

        if (!array_key_exists($i_k - 1, $return -> generated_faces)) {
            exit ("<h1>No Such key</h1>". $key);
        }

        ($return -> generated_faces[$i_k - 1]) -> max_preview_hash = $hash;
    }
    if (!feof($file)) {
        echo "Error: unexpected fgets() fail<br>";
        //$errorMessage("file: foruploading/РІСЃРµСЃС‚СѓРґРµРЅС‚С‹.csv", 'Error: unexpected fgets() fail');
    }
    fclose($file);
}

echo json_encode($return -> generated_faces, JSON_PRETTY_PRINT);