<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://joe.szalai.org
 * @since      1.0.0
 *
 * @package    Exopite_Seo_Core
 * @subpackage Exopite_Seo_Core/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Exopite_Seo_Core
 * @subpackage Exopite_Seo_Core/public
 * @author     Joe Szalai <joe@szalai.org>
 */
class Exopite_Seo_Core_Sanitize {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

    private $options;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
        $this->options = get_exopite_sof_option( $this->plugin_name );

	}

    /**
     * For demonstration purposes.
     */

    /**
     * Deal with special chars.
     *
     * @link https://stackoverflow.com/questions/3371697/replacing-accented-characters-php/33856250#33856250
     *
     * "-" => "ъьЪЬ",
     * "A" => "АĂǍĄÀÃÁÆÂÅǺĀא",
     * "B" => "БבÞ",
     * "C" => "ĈĆÇЦצĊČ©ץ",
     * "D" => "ДĎĐדÐ",
     * "E" => "ÈĘÉËÊЕĒĖĚĔЄƏע",
     * "F" => "ФƑ",
     * "G" => "ĞĠĢĜГגҐ",
     * "H" => "חĦХĤה",
     * "I" => "IÏÎÍÌĮĬIИĨǏיЇĪІ",
     * "J" => "ЙĴ",
     * "K" => "ĸכĶКך",
     * "L" => "ŁĿЛĻĹĽל",
     * "M" => "מМם",
     * "N" => "ÑŃНŅןŊנŉŇ",
     * "O" => "ØÓÒÔÕОŐŎŌǾǑƠ",
     * "P" => "פףП",
     * "Q" => "ק",
     * "R" => "ŔŘŖרР®",
     * "S" => "ŞŚȘŠСŜס",
     * "T" => "ТȚטŦתŤŢ",
     * "U" => "ÙÛÚŪУŨƯǓŲŬŮŰǕǛǙǗ",
     * "V" => "Вו",
     * "Y" => "ÝЫŶŸ",
     * "Z" => "ŹŽŻЗזS",
     * "a" => "аăǎąàãáæâåǻāא",
     * "b" => "бבþ",
     * "c" => "ĉćçцצċč©ץ",
     * "ch" => "ч",
     * "d" => "дďđדð",
     * "e" => "èęéëêеēėěĕєəע",
     * "f" => "фƒ",
     * "g" => "ğġģĝгגґ",
     * "h" => "חħхĥה",
     * "i" => "iïîíìįĭıиĩǐיїīі",
     * "j" => "йĵ",
     * "k" => "ĸכķкך",
     * "l" => "łŀлļĺľל",
     * "m" => "מмם",
     * "n" => "ñńнņןŋנŉň",
     * "o" => "øóòôõоőŏōǿǒơ",
     * "p" => "פףп",
     * "q" => "ק",
     * "r" => "ŕřŗרр®",
     * "s" => "şśșšсŝס",
     * "t" => "тțטŧתťţ",
     * "u" => "ùûúūуũưǔųŭůűǖǜǚǘ",
     * "v" => "вו",
     * "y" => "ýыŷÿ",
     * "z" => "źžżзזſ",
     * "tm" => "™",
     * "at" => "@",
     * "ae" => "ÄǼäæǽ",
     * "ch" => "Чч",
     * "ij" => "ĳĲ",
     * "j" => "йЙĴĵ",
     * "ja" => "яЯ",
     * "je" => "Ээ",
     * "jo" => "ёЁ",
     * "ju" => "юЮ",
     * "oe" => "œŒöÖ",
     * "sch" => "щЩ",
     * "sh" => "шШ",
     * "ss" => "ß",
     * "tm" => "™",
     * "ue" => "Ü",
     * "zh" => "Жж"
     */

    /**
     * Replace language-specific characters by ASCII-equivalents.
     *
     * @param string $s
     * @return string
     *
     * @link https://stackoverflow.com/questions/3371697/replacing-accented-characters-php/24984010#24984010
     */
    public static function normalize_chars( $string ) {

        $replace = array(
            '&amp;' => 'and', '@' => 'at', '©' => 'copy', '®' => 'r', 'À' => 'a',
            'Á' => 'a', 'Â' => 'a', 'Ä' => 'ae', 'Å' => 'a', 'Æ' => 'ae','Ç' => 'c',
            'È' => 'e', 'É' => 'e', 'Ë' => 'e', 'Ì' => 'i', 'Í' => 'i', 'Î' => 'i',
            'Ï' => 'i', 'Ò' => 'o', 'Ó' => 'o', 'Ô' => 'o', 'Õ' => 'o', 'Ö' => 'oe',
            'Ø' => 'o', 'Ù' => 'u', 'Ú' => 'u', 'Û' => 'u', 'Ü' => 'ue', 'Ý' => 'y',
            'ß' => 'ss','à' => 'a', 'á' => 'a', 'â' => 'a', 'ä' => 'ae', 'å' => 'a',
            'æ' => 'ae','ç' => 'c', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e',
            'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ò' => 'o', 'ó' => 'o',
            'ô' => 'o', 'õ' => 'o', 'ö' => 'oe', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u',
            'û' => 'u', 'ü' => 'ue', 'ý' => 'y', 'þ' => 'p', 'ÿ' => 'y', 'Ā' => 'a',
            'ā' => 'a', 'Ă' => 'a', 'ă' => 'a', 'Ą' => 'a', 'ą' => 'a', 'Ć' => 'c',
            'ć' => 'c', 'Ĉ' => 'c', 'ĉ' => 'c', 'Ċ' => 'c', 'ċ' => 'c', 'Č' => 'c',
            'č' => 'c', 'Ď' => 'd', 'ď' => 'd', 'Đ' => 'd', 'đ' => 'd', 'Ē' => 'e',
            'ē' => 'e', 'Ĕ' => 'e', 'ĕ' => 'e', 'Ė' => 'e', 'ė' => 'e', 'Ę' => 'e',
            'ę' => 'e', 'Ě' => 'e', 'ě' => 'e', 'Ĝ' => 'g', 'ĝ' => 'g', 'Ğ' => 'g',
            'ğ' => 'g', 'Ġ' => 'g', 'ġ' => 'g', 'Ģ' => 'g', 'ģ' => 'g', 'Ĥ' => 'h',
            'ĥ' => 'h', 'Ħ' => 'h', 'ħ' => 'h', 'Ĩ' => 'i', 'ĩ' => 'i', 'Ī' => 'i',
            'ī' => 'i', 'Ĭ' => 'i', 'ĭ' => 'i', 'Į' => 'i', 'į' => 'i', 'İ' => 'i',
            'ı' => 'i', 'Ĳ' => 'ij','ĳ' => 'ij','Ĵ' => 'j', 'ĵ' => 'j', 'Ķ' => 'k',
            'ķ' => 'k', 'ĸ' => 'k', 'Ĺ' => 'l', 'ĺ' => 'l', 'Ļ' => 'l', 'ļ' => 'l',
            'Ľ' => 'l', 'ľ' => 'l', 'Ŀ' => 'l', 'ŀ' => 'l', 'Ł' => 'l', 'ł' => 'l',
            'Ń' => 'n', 'ń' => 'n', 'Ņ' => 'n', 'ņ' => 'n', 'Ň' => 'n', 'ň' => 'n',
            'ŉ' => 'n', 'Ŋ' => 'n', 'ŋ' => 'n', 'Ō' => 'o', 'ō' => 'o', 'Ŏ' => 'o',
            'ŏ' => 'o', 'Ő' => 'o', 'ő' => 'o', 'Œ' => 'oe','œ' => 'oe','Ŕ' => 'r',
            'ŕ' => 'r', 'Ŗ' => 'r', 'ŗ' => 'r', 'Ř' => 'r', 'ř' => 'r', 'Ś' => 's',
            'ś' => 's', 'Ŝ' => 's', 'ŝ' => 's', 'Ş' => 's', 'ş' => 's', 'Š' => 's',
            'š' => 's', 'Ţ' => 't', 'ţ' => 't', 'Ť' => 't', 'ť' => 't', 'Ŧ' => 't',
            'ŧ' => 't', 'Ũ' => 'u', 'ũ' => 'u', 'Ū' => 'u', 'ū' => 'u', 'Ŭ' => 'u',
            'ŭ' => 'u', 'Ů' => 'u', 'ů' => 'u', 'Ű' => 'u', 'ű' => 'u', 'Ų' => 'u',
            'ų' => 'u', 'Ŵ' => 'w', 'ŵ' => 'w', 'Ŷ' => 'y', 'ŷ' => 'y', 'Ÿ' => 'y',
            'Ź' => 'z', 'ź' => 'z', 'Ż' => 'z', 'ż' => 'z', 'Ž' => 'z', 'ž' => 'z',
            'ſ' => 'z', 'Ə' => 'e', 'ƒ' => 'f', 'Ơ' => 'o', 'ơ' => 'o', 'Ư' => 'u',
            'ư' => 'u', 'Ǎ' => 'a', 'ǎ' => 'a', 'Ǐ' => 'i', 'ǐ' => 'i', 'Ǒ' => 'o',
            'ǒ' => 'o', 'Ǔ' => 'u', 'ǔ' => 'u', 'Ǖ' => 'u', 'ǖ' => 'u', 'Ǘ' => 'u',
            'ǘ' => 'u', 'Ǚ' => 'u', 'ǚ' => 'u', 'Ǜ' => 'u', 'ǜ' => 'u', 'Ǻ' => 'a',
            'ǻ' => 'a', 'Ǽ' => 'ae','ǽ' => 'ae','Ǿ' => 'o', 'ǿ' => 'o', 'ə' => 'e',
            'Ё' => 'jo','Є' => 'e', 'І' => 'i', 'Ї' => 'i', 'А' => 'a', 'Б' => 'b',
            'В' => 'v', 'Г' => 'g', 'Д' => 'd', 'Е' => 'e', 'Ж' => 'zh','З' => 'z',
            'И' => 'i', 'Й' => 'j', 'К' => 'k', 'Л' => 'l', 'М' => 'm', 'Н' => 'n',
            'О' => 'o', 'П' => 'p', 'Р' => 'r', 'С' => 's', 'Т' => 't', 'У' => 'u',
            'Ф' => 'f', 'Х' => 'h', 'Ц' => 'c', 'Ч' => 'ch','Ш' => 'sh','Щ' => 'sch',
            'Ъ' => '-', 'Ы' => 'y', 'Ь' => '-', 'Э' => 'je','Ю' => 'ju','Я' => 'ja',
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e',
            'ж' => 'zh','з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l',
            'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's',
            'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch',
            'ш' => 'sh','щ' => 'sch','ъ' => '-','ы' => 'y', 'ь' => '-', 'э' => 'je',
            'ю' => 'ju','я' => 'ja','ё' => 'jo','є' => 'e', 'і' => 'i', 'ї' => 'i',
            'Ґ' => 'g', 'ґ' => 'g', 'א' => 'a', 'ב' => 'b', 'ג' => 'g', 'ד' => 'd',
            'ה' => 'h', 'ו' => 'v', 'ז' => 'z', 'ח' => 'h', 'ט' => 't', 'י' => 'i',
            'ך' => 'k', 'כ' => 'k', 'ל' => 'l', 'ם' => 'm', 'מ' => 'm', 'ן' => 'n',
            'נ' => 'n', 'ס' => 's', 'ע' => 'e', 'ף' => 'p', 'פ' => 'p', 'ץ' => 'C',
            'צ' => 'c', 'ק' => 'q', 'ר' => 'r', 'ש' => 'w', 'ת' => 't', '™' => 'tm',
            '№' => 'No', 'º' => 'o', 'ª' => 'a', '℗' => 'P', '™' => 'tm', '℠' => 'sm',
            '’' => '', '_' => '-', '%20' => '-', '€'=>'Euro', 'Ã' => 'A', 'ã' => 'a',
            'Ñ' => 'N', 'ð' => 'o', 'ñ' => 'n', 'ș' => 's', 'Ș' => 'S', 'ț' => 't',
            'Ț' => 'T',
        );

        return strtr( $string, $replace );
    }

    /**
     * END - For demonstration purposes.
     */

	/**
	 * Fix and sanitize some special cases.
	 *
	 * @link https://github.com/salcode/fe-sanitize-title-js/issues/1
	 */
	public function special_replace_chars( $string  ) {

        $replace = array(
            'Ä' => 'ae',
            'ä' => 'ae',
            'Ö' => 'oe',
            'ö' => 'oe',
            'Ü' => 'ue',
            'ü' => 'ue',
            'ß' => 'ss',
            '€' => 'euro',
            '@' => 'at',
            '%20' => '-',
            '©' => 'copy',
            '&amp;' => 'and',
            '℠' => 'sm',
            '™' => 'tm',
            '№' => 'No',
        );

        return strtr( $string, $replace );

    }

	public function sanitize_file_name( $filename_sanitized, $filename_raw ) {

		// Get file parts.
		$pathinfo = pathinfo( $filename_raw );
		$ext = $pathinfo['extension'];
		$filename = $pathinfo['filename'];

        /**
         * Replace language-specific characters by ASCII-equivalents.
         * Converts all accent characters to ASCII characters.
         */
        $filename = $this->normalize_chars( $filename );

        /**
         * Remove special chars, sanitize_title does not this (or at least not all).
         * Replace "speacial" chars without remove accents.
         */
        // $filename = $this->special_replace_chars( $filename );

        $filename = str_replace( '_', '-', $filename );

        $filename = remove_accents( $filename );

		/**
		 * Sanitize filename.
		 *
		 * @link https://codex.wordpress.org/Function_Reference/sanitize_title
		 *
		 * "Despite the name of this function, the returned value is intended to be
		 * suitable for use in a URL, not as a human-readable title."
		 */
		$filename = sanitize_title( $filename );

        /**
         * Removes all non-ascii characters.
         *
         * @link https://ca.wordpress.org/plugins/wp-sanitize-accented-uploads
         */
        $filename = preg_replace("/[^(\x20-\x7F)]*/", "", $filename );

		return $filename . '.' . $ext;

	}

}
