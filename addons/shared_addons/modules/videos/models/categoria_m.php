<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Modelo categoria
 *
 * metodos para obtener data de la tabla categorias
 *
 * @author		Johnny Huamani <jhuamani@idigital.pe>
 * @author		PyroCMS Dev Team
 * @package		Modules\videos\Models
 */
class Categoria_m extends MY_Model {

    /**
     *  nombre de la tabla
     * @var string 
     */
    protected $_table = 'default_cms_categorias';

    /**
     * listado de categorias en orden ascendente alfabeticamente
     * @param array $where
     * @param string $order
     * @return array
     */
    public function getCategory($where = array(), $order = NULL) {
        $this->db->select("*");
        $this->db->from($this->_table);
        $this->db->where($where);
        if ($order != NULL) {
            $this->db->order_by($order);
        }
        
        return $this->db->get()->result();
    }
    /**
     * 
     * @param type $where
     * @param type $order
     * @return type
     */
    public function getCategoryDropDown($where = array(), $order = NULL) {
        $returnValue = array();
        $arrayData = $this->getCategory($where, $order);
        if (count($arrayData) > 0) {
            foreach ($arrayData as $index => $objTipo) {
                if($this->isParent($objTipo->id)){
                    $returnValue[$objTipo->nombre] = $this->getChildrenCategories($objTipo->id);
                }else{
                    $returnValue[$objTipo->id] = $objTipo->nombre;
                }
            }
        }

        return $returnValue;
    }
    
    public function getChildrenCategories($category_id){
        $returnValue = array();
        $arrayData = $this->getCategory(array("categorias_id"=>$category_id));
        if (count($arrayData) > 0) {
            foreach ($arrayData as $index => $objTipo) {
                    $returnValue[$objTipo->id] = $objTipo->nombre;
            }
        }
        return $returnValue;
    }
    
    public function isParent($category_id)
    {
        $returnValue = false;
        $query = "SELECT * FROM " . $this->_table . " WHERE categorias_id = '" . $category_id . "'";
        $result = $this->db->query($query)->result();
        if (count($result) > 0) {
            $returnValue = true;
        }
        
        return $returnValue;         
    }

    public function insert($post)
    {
        $post['id'] = parent::insert(
            array(
                'nombre' => $post['nombre'],
                'alias' => $this->sanitize($post['nombre']),
                'estado' => 1,
                'fecha_registro' => $post['fecha_registro'],
                'usuario_registro' => $post['user_id'],
                'categorias_id' => $post['categoria']
            )
        );
        
        return $post;
    }
    
    public function sanitize($string)
    {
        //"̆Ｉ";
        $aes = array("á", "à", "â", "ǎ", "ă", "ã", "ả", "ȧ", "ạ", "ä", "å", "ḁ",
                     "ā", "ą", "ᶏ", "ⱥ", "ȁ", "ấ", "ầ", "ẫ", "ẩ", "ậ", "ắ", "ằ",
                     "ẵ", "ẳ", "ặ", "ǻ", "ǡ", "ǟ", "ȁ", "ȃ", "ɑ", "ᴀ", "ɐ", "ɒ",
                     "ａ", "æ", "ǽ", "ǣ", "ꜳ", "ꜵ", "ꜷ", "ꜹ", "ꜻ");
        $string = str_replace($aes, "a", $string);
        $ees = array("é", "è", "ê", "ḙ", "ě", "ĕ", "ẽ", "ḛ", "ẻ", "ė", "ë", "ē",
                     "ȩ", "ę", "ᶒ", "ɇ", "ȅ", "ế", "ề", "ễ", "ể", "ḝ", "ḗ", "ḕ",
                     "ȇ", "ẹ", "ệ", "ⱸ", "ᴇ", "ｅ", "&", "œ", "ᵫ");
        $string = str_replace($ees, "e", $string);
        $ies = array("í", "ì", "ĭ", "î", "ǐ", "ï", "ḯ", "ĩ", "į", "ī", "ỉ", "ȉ",
                     "ȋ", "ị", "ḭ", "ɨ", "ɨ", "ᵻ", "ᶖ", "i", "ı", "ｉ", "ﬁ", "ﬃ",
                     "ĳ");
        $string = str_replace($ies, "i", $string);
        $oes = array("ó", "ò", "ŏ", "ô", "ố", "ồ", "ỗ", "ổ", "ǒ", "ö", "ȫ", "ő",
                     "õ", "ṍ", "ṏ", "ȭ", "ȯ", "ȱ", "ø", "ǿ", "ǫ", "ǭ", "ō", "ṓ",
                     "ṑ", "ỏ", "ȍ", "ȏ", "ơ", "ớ", "ờ", "ỡ", "ở", "ợ", "ọ", "ộ",
                     "ɵ", "ⱺ", "ᴏ", "ｏ", "ꜵ", "ꝏ", "ꝍ", "ȣ");
        $string = str_replace($oes, "o", $string);
        $ues = array("ú", "ù", "ŭ", "û", "ǔ", "ů", "ü", "ǘ", "ǜ", "ǚ", "ǖ", "ű",
                     "ũ", "ṹ", "ų", "ū", "ṻ", "ủ", "ȕ", "ȗ", "ư", "ứ", "ừ", "ữ",
                     "ử", "ự", "ụ", "ṳ", "ṷ", "ṵ", "ʉ", "ᵾ", "ᶙ", "ᴜ", "ｕ", "ꜷ",
                     "ȣ", "ᵫ");
        $string = str_replace($ues, "u", $string);
        $AES = array("Á", "À", "Â", "Ǎ", "Ă", "Ã", "Ả", "Ȧ", "Ạ", "Ä", "Å", "Ḁ",
                     "Ā", "Ą", "Ⱥ", "Ȁ", "Ấ", "Ầ", "Ẫ", "Ẩ", "Ậ", "Ắ", "Ằ", "Ẵ",
                     "Ẳ", "Ặ", "Ǻ", "Ǡ", "Ǟ", "Ȁ", "Ȃ", "Ɑ", "Ɐ", "Ａ", "Æ", "Ǽ",
                     "Ǣ", "Ꜳ", "Ꜵ", "Ꜷ", "Ꜹ", "Ꜻ");
        $string = str_replace($AES, "A", $string);
        $EES = array("É", "È", "Ê", "Ḙ", "Ě", "Ĕ", "Ẽ", "Ḛ", "Ẻ", "Ė", "Ë", "Ē",
                     "Ȩ", "Ę", "Ɇ", "Ȅ", "Ế", "Ề", "Ễ", "Ể", "Ḝ", "Ḗ", "Ḕ", "Ȇ",
                     "Ẹ", "Ệ", "Ｅ", "Œ");
        $string = str_replace($EES, "E", $string);
        $IES = array("Í", "Ì", "Ĭ", "Î", "Ǐ", "Ï", "Ḯ", "Ĩ", "Į", "Ī", "Ỉ", "Ȉ",
                     "Ȋ", "Ị", "Ḭ", "Ɨ", "İ", "I", "ɪ", "Ĳ");
        $string = str_replace($IES, "I", $string);
        $OES = array("Ó", "Ò", "Ŏ", "Ô", "Ố", "Ồ", "Ỗ", "Ổ", "Ǒ", "Ö", "Ȫ", "Ő",
                     "Õ", "Ṍ", "Ṏ", "Ȭ", "Ȯ", "Ȱ", "Ø", "Ǿ", "Ǫ", "Ǭ", "Ō", "Ṓ",
                     "Ṑ", "Ỏ", "Ȍ", "Ȏ", "Ơ", "Ớ", "Ờ", "Ỡ", "Ở", "Ợ", "Ọ", "Ộ",
                     "Ɵ", "Ｏ", "Ꜵ", "Ꝏ", "Ꝍ", "Ȣ");
        $string = str_replace($OES, "O", $string);
        $UES = array("Ú", "Ù", "Ŭ", "Û", "Ǔ", "Ů", "Ü", "Ǘ", "Ǜ", "Ǚ", "Ǖ", "Ű",
                     "Ũ", "Ṹ", "Ų", "Ū", "Ṻ", "Ủ", "Ȕ", "Ȗ", "Ư", "Ứ", "Ừ", "Ữ",
                     "Ử", "Ự", "Ụ", "Ṳ", "Ṷ", "Ṵ", "Ʉ", "Ｕ", "Ꜷ", "Ȣ");
        $string = str_replace($UES, "U", $string);
        $string = str_replace("ñ", "n", $string);
        $string = str_replace("Ñ", "N", $string);
        $string = str_replace("ç", "z", $string);
        $string = str_replace("Ç", "Z", $string);
        
        $symbols = array("[", "]", "{", "}");
        $string = str_replace($symbols, "", $string);
        
        $spaces = array(" ", ";", ":", ",", ".", "_");
        $string = str_replace($spaces, "-", $string);
        
        return strtolower($string);
    }
    
    public function set_deleted($categoria_id)
    {
        return parent::update($categoria_id, array('estado' => 0));
    }
    
    public function set_restored($categoria_id)
    {
        return parent::update($categoria_id, array('estado' => 1));
    }
    
    public function set_purged($categoria_id)
    {
        return parent::delete($categoria_id);
    }
}