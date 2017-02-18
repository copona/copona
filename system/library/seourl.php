<?php
class Seourl {

    public function __construct($registry) {

        $this->config = $registry->get('config');
        $this->db = $registry->get('db');
    }

    /**
     * Replaces special characters in a string with their "non-special" counterpart.
     *
     * Useful for friendly URLs.
     *
     * @access public
     * @param string
     * @return string
     */
    public function convertAccentsAndSpecialToNormal($string) {
        $table = array(
            'À'      => 'A', 'Á'      => 'A', 'Â'      => 'A', 'Ã'      => 'A', 'Ä'      => 'A',
            'Å'      => 'A',
            'Ă'      => 'A', 'Ā'      => 'A', 'Ą'      => 'A', 'Æ'      => 'A', 'Ǽ'      => 'A',
            'à'      => 'a', 'á'      => 'a', 'â'      => 'a', 'ã'      => 'a', 'ä'      => 'a',
            'å'      => 'a',
            'ă'      => 'a', 'ā'      => 'a', 'ą'      => 'a', 'æ'      => 'a', 'ǽ'      => 'a',
            'Þ'      => 'B', 'þ'      => 'b', 'ß'      => 'Ss',
            'Ç'      => 'C', 'Č'      => 'C', 'Ć'      => 'C', 'Ĉ'      => 'C', 'Ċ'      => 'C',
            'ç'      => 'c', 'č'      => 'c', 'ć'      => 'c', 'ĉ'      => 'c', 'ċ'      => 'c',
            'Đ'      => 'Dj', 'Ď'      => 'D', 'Đ'      => 'D',
            'đ'      => 'dj', 'ď'      => 'd',
            'È'      => 'E', 'É'      => 'E', 'Ê'      => 'E', 'Ë'      => 'E', 'Ĕ'      => 'E',
            'Ē'      => 'E',
            'Ę'      => 'E', 'Ė'      => 'E',
            'è'      => 'e', 'é'      => 'e', 'ê'      => 'e', 'ë'      => 'e', 'ĕ'      => 'e',
            'ē'      => 'e',
            'ę'      => 'e', 'ė'      => 'e',
            'Ĝ'      => 'G', 'Ğ'      => 'G', 'Ġ'      => 'G', 'Ģ'      => 'G',
            'ĝ'      => 'g', 'ğ'      => 'g', 'ġ'      => 'g', 'ģ'      => 'g',
            'Ĥ'      => 'H', 'Ħ'      => 'H',
            'ĥ'      => 'h', 'ħ'      => 'h',
            'Ì'      => 'I', 'Í'      => 'I', 'Î'      => 'I', 'Ï'      => 'I', 'İ'      => 'I',
            'Ĩ'      => 'I',
            'Ī'      => 'I', 'Ĭ'      => 'I', 'Į'      => 'I',
            'ì'      => 'i', 'í'      => 'i', 'î'      => 'i', 'ï'      => 'i', 'į'      => 'i',
            'ĩ'      => 'i',
            'ī'      => 'i', 'ĭ'      => 'i', 'ı'      => 'i',
            'Ĵ'      => 'J',
            'ĵ'      => 'j',
            'Ķ'      => 'K',
            'ķ'      => 'k', 'ĸ'      => 'k',
            'Ĺ'      => 'L', 'Ļ'      => 'L', 'Ľ'      => 'L', 'Ŀ'      => 'L', 'Ł'      => 'L',
            'ĺ'      => 'l', 'ļ'      => 'l', 'ľ'      => 'l', 'ŀ'      => 'l', 'ł'      => 'l',
            'Ñ'      => 'N', 'Ń'      => 'N', 'Ň'      => 'N', 'Ņ'      => 'N', 'Ŋ'      => 'N',
            'ñ'      => 'n', 'ń'      => 'n', 'ň'      => 'n', 'ņ'      => 'n', 'ŋ'      => 'n',
            'ŉ'      => 'n',
            'Ò'      => 'O', 'Ó'      => 'O', 'Ô'      => 'O', 'Õ'      => 'O', 'Ö'      => 'O',
            'Ø'      => 'O',
            'Ō'      => 'O', 'Ŏ'      => 'O', 'Ő'      => 'O', 'Œ'      => 'O',
            'ò'      => 'o', 'ó'      => 'o', 'ô'      => 'o', 'õ'      => 'o', 'ö'      => 'o',
            'ø'      => 'o',
            'ō'      => 'o', 'ŏ'      => 'o', 'ő'      => 'o', 'œ'      => 'o', 'ð'      => 'o',
            'Ŕ'      => 'R', 'Ř'      => 'R',
            'ŕ'      => 'r', 'ř'      => 'r', 'ŗ'      => 'r',
            'Š'      => 'S', 'Ŝ'      => 'S', 'Ś'      => 'S', 'Ş'      => 'S',
            'š'      => 's', 'ŝ'      => 's', 'ś'      => 's', 'ş'      => 's',
            'Ŧ'      => 'T', 'Ţ'      => 'T', 'Ť'      => 'T',
            'ŧ'      => 't', 'ţ'      => 't', 'ť'      => 't',
            'Ù'      => 'U', 'Ú'      => 'U', 'Û'      => 'U', 'Ü'      => 'U', 'Ũ'      => 'U',
            'Ū'      => 'U',
            'Ŭ'      => 'U', 'Ů'      => 'U', 'Ű'      => 'U', 'Ų'      => 'U',
            'ù'      => 'u', 'ú'      => 'u', 'û'      => 'u', 'ü'      => 'u', 'ũ'      => 'u',
            'ū'      => 'u',
            'ŭ'      => 'u', 'ů'      => 'u', 'ű'      => 'u', 'ų'      => 'u',
            'Ŵ'      => 'W', 'Ẁ'      => 'W', 'Ẃ'      => 'W', 'Ẅ'      => 'W',
            'ŵ'      => 'w', 'ẁ'      => 'w', 'ẃ'      => 'w', 'ẅ'      => 'w',
            'Ý'      => 'Y', 'Ÿ'      => 'Y', 'Ŷ'      => 'Y',
            'ý'      => 'y', 'ÿ'      => 'y', 'ŷ'      => 'y',
            'Ž'      => 'Z', 'Ź'      => 'Z', 'Ż'      => 'Z', 'Ž'      => 'Z',
            'ž'      => 'z', 'ź'      => 'z', 'ż'      => 'z', 'ž'      => 'z',
            'а'      => 'a', /* RUSSIAN Transliteration start */
            'б'      => 'b', 'в'      => 'v', 'г'      => 'g', 'д'      => 'd', 'е'      => 'e',
            'ж'      => 'zh', 'з'      => 'z', 'и'      => 'i', 'й'      => 'y',
            'к'      => 'k',
            'л'      => 'l', 'м'      => 'm', 'н'      => 'n', 'о'      => 'o', 'п'      => 'p',
            'р'      => 'r', 'с'      => 's', 'т'      => 't', 'у'      => 'u', 'ф'      => 'f',
            'х'      => 'h', 'ц'      => 'ts', 'ч'      => 'ch', 'ш'      => 'sh',
            'щ'      => 'sht',
            'ы'      => 'y',
            'ъ'      => 'a', 'ь'      => 'y', 'ю'      => 'yu', 'я'      => 'ya',
            'А'      => 'A',
            'Б'      => 'B', 'В'      => 'V', 'Г'      => 'G', 'Д'      => 'D', 'Е'      => 'E',
            'Ж'      => 'Zh',
            'З'      => 'Z',
            'И'      => 'I', 'Й'      => 'Y', 'К'      => 'K', 'Л'      => 'L', 'М'      => 'M',
            'Н'      => 'N',
            'О'      => 'O',
            'П'      => 'P', 'Р'      => 'R', 'С'      => 'S', 'Т'      => 'T', 'У'      => 'U',
            'Ф'      => 'F',
            'Х'      => 'H',
            'Ц'      => 'Ts', 'Ч'      => 'Ch', 'Ш'      => 'Sh', 'Щ'      => 'Sht',
            'Ъ'      => 'A',
            'Ь'      => 'Y', 'Ю'      => 'Yu',
            'Я'      => 'Ya', /* RUSSIAN Transliteration END */
            'Ы'      => 'Y',
            'э'      => 'e',
            'Э'      => 'E',
            '“'      => '"', '”'      => '"', '‘'      => "'", '’'      => "'", '•'      => '-',
            '…'      => '...',
            '—'      => '-', '–'      => '-', '¿'      => '?', '¡'      => '!', '°'      => '-',
            '¼'      => ' 1/4 ', '½'      => ' 1/2 ', '¾'      => ' 3/4 ', '⅓'      => ' 1/3 ',
            '⅔'      => ' 2/3 ',
            '⅛'      => ' 1/8 ', '⅜'      => ' 3/8 ', '⅝'      => ' 5/8 ', '⅞'      => ' 7/8 ',
            '÷'      => 'div', '×'      => '*', '±'      => '+-', '√'      => '-',
            '∞'      => '-',
            '≈'      => ' almost equal to ', '≠'      => ' not equal to ', '≡'      => '-',
            '≤'      => '-',
            '≥'      => '-',
            '←'      => '-', '→'      => '-', '↑'      => '-', '↓'      => '-', '↔'      => '-',
            '↕'      => '-',
            '℅'      => ' care of ', '℮'      => ' estimated ',
            'Ω'      => ' ohm ',
            '♀'      => '-', '♂'      => '-',
            '©'      => '(c)', '®'      => 'reg', '™'      => 'tm',
            ' - '    => '-', '- '     => '-', ' -'     => '-',
            ' '      => '-', ','      => '-', ' , '    => '-', ', '     => '-', ' ,'     => '-',
            '&amp;'  => '-', '&quot;' => '-',
            '&'      => '-', '/'      => '-', '`'      => '-', '\''     => '-',
            '"'      => '',
            '%'      => '-', // todo: this must be tested on spec symbols
            '+'      => '-',
            '--'     => '-',
        );

        $new_string = strtr($string, $table);

        // recursive replace of "-"
        if ($string != $new_string) {
            $new_string = $this->convertAccentsAndSpecialToNormal($new_string);
        }


        // Currency symbols: £¤¥€  - we dont bother with them for now
        $new_string = preg_replace("/[^\x9\xA\xD\x20-\x7F]/u", "", $new_string);

        return strtolower($new_string);
    }

    public function seoURL($str) {

        $str = $this->convertAccentsAndSpecialToNormal($str);
        $str = preg_replace('/[^a-zA-Z0-9()]+/', '-', $str);
        $str = trim($str, '-');
        $str = strtolower($str);
        return $str;
    }

    public function uniqueSeoKeyword($key = '', $language_id = '', $query = '') {
        //check for duplicate seo link:
        $key = $this->convertAccentsAndSpecialToNormal(trim($key));

        $nextKeyword = $key;
        if ($query == 'skip') {
            return $nextKeyword;
        }

        if ($query) { // If is set query, search only for direct match
            $query_sql = " query like '%" . $this->db->escape($query) . "%' AND ";
        } else {
            $query_sql = '';
        }

        if ($language_id) { // If is set query, search only for direct match
            $language_sql = " language_id = '" . (int)$language_id . "' AND ";
        } else {
            $language_sql = '';
        }

        $result = $this->db->query("select * FROM " . DB_PREFIX . "url_alias WHERE $language_sql $query_sql keyword = '" . $this->db->escape($key) . "'");
        $i = 0;
        while ($result->num_rows > 0) {
            $i++;
            $nextKeyword = $key . "-" . $i;
            $result = $this->db->query("select * FROM " . DB_PREFIX . "url_alias WHERE $language_sql $query_sql keyword = '" . $this->db->escape($nextKeyword) . "'");
            // change keyword, to be unique
        }
        return strtolower($nextKeyword);
    }

    public function getSeoUrls($query = '', $language_id = '') {

        $seo_keywords = array();

        if ($language_id) { // If is set query, search only for direct match
            $language_sql = " language_id = '" . (int)$language_id . "' AND ";
        } else {
            $language_sql = '';
        }

        $result = $this->db->query("SELECT keyword, language_id FROM " . DB_PREFIX . "url_alias WHERE $language_sql query='" . $this->db->escape($query) . "' ");

        foreach ($result->rows as $row) {
            $seo_keywords[$row['language_id']] = $row['keyword'];
        }

        return $seo_keywords;
    }

}