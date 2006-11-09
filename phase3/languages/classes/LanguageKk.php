<?php
/** Kazakh (Қазақша)
  * converter routines
  *
  * @package MediaWiki
  * @subpackage Language
  */

require_once( dirname(__FILE__).'/../LanguageConverter.php' );
require_once( dirname(__FILE__).'/LanguageKk_kz.php' );

class KkConverter extends LanguageConverter {
    var $mLatinToCyrillic = array(
        'YA' => 'Я', 'Ya' => 'Я', 'ya' => 'я', 'YO' => 'Ё', 'Yo' => 'Ё', 'yo' => 'ё',
        'YU' => 'Ю', 'Yu' => 'Ю', 'yu' => 'ю', 'YW' => 'Ю', 'Yw' => 'Ю', 'yw' => 'ю',
        'ŞÇ' => 'Щ', 'Şç' => 'Щ', 'şç' => 'щ',

        'bʺ' => 'бъ', 'dʺ' => 'дъ', 'fʺ' => 'фъ', 'gʺ' => 'гъ', 'kʺ' => 'къ', 'lʺ' => 'лъ',
        'mʺ' => 'мъ', 'nʺ' => 'нъ', 'pʺ' => 'пъ', 'rʺ' => 'ръ', 'sʺ' => 'съ', 'tʺ' => 'тъ',
        'vʺ' => 'въ', 'zʺ' => 'зъ',
     /* 'jʺ' => 'жъ', 'cʺ' => 'цъ', 'çʺ' => 'чъ', 'şʺ' => 'шъ', */

        'Bʺ' => 'БЪ', 'Dʺ' => 'ДЪ', 'Fʺ' => 'ФЪ', 'Gʺ' => 'ГЪ', 'Kʺ' => 'КЪ', 'Lʺ' => 'ЛЪ',
        'Mʺ' => 'МЪ', 'Nʺ' => 'НЪ', 'Pʺ' => 'ПЪ', 'Rʺ' => 'РЪ', 'Sʺ' => 'СЪ', 'Tʺ' => 'ТЪ',
        'Vʺ' => 'ВЪ', 'Zʺ' => 'ЗЪ',
     /* 'Jʺ' => 'ЖЪ', 'Cʺ' => 'ЦЪ', 'Çʺ' => 'ЧЪ', 'Şʺ' => 'ШЪ', */

        'bʹ' => 'бь', 'dʹ' => 'дь', 'fʹ' => 'фь', 'gʹ' => 'гь', 'kʹ' => 'кь', 'lʹ' => 'ль',
        'mʹ' => 'мь', 'nʹ' => 'нь', 'pʹ' => 'пь', 'rʹ' => 'рь', 'sʹ' => 'сь', 'tʹ' => 'ть',
        'vʹ' => 'вь', 'zʹ' => 'зь', 'jʹ' => 'жь', 'cʹ' => 'ць', 'çʹ' => 'чь', 'şʹ' => 'шь',

        'Bʹ' => 'БЬ', 'Dʹ' => 'ДЬ', 'Fʹ' => 'ФЬ', 'Gʹ' => 'ГЬ', 'Kʹ' => 'КЬ', 'Lʹ' => 'ЛЬ',
        'Mʹ' => 'МЬ', 'Nʹ' => 'НЬ', 'Pʹ' => 'ПЬ', 'Rʹ' => 'РЬ', 'Sʹ' => 'СЬ', 'Tʹ' => 'ТЬ',
        'Vʹ' => 'ВЬ', 'Zʹ' => 'ЗЬ', 'Jʹ' => 'ЖЬ', 'Cʹ' => 'ЦЬ', 'Çʹ' => 'ЧЬ', 'Şʹ' => 'ШЬ',
        
        'a' => 'а', 'ä' => 'ә', 'b' => 'б', 'c' => 'ц', 'ç' => 'ч', 'd' => 'д', 'e' => 'е',
        'é' => 'э', 'f' => 'ф', 'g' => 'г', 'ğ' => 'ғ', 'h' => 'һ', 'i' => 'і', 'ı' => 'ы',
        'ï' => 'и', 'j' => 'ж', 'k' => 'к', 'l' => 'л', 'm' => 'м', 'n' => 'н', 'ñ' => 'ң',
        'o' => 'о', 'ö' => 'ө', 'p' => 'п', 'q' => 'қ', 'r' => 'р', 's' => 'с', 'ş' => 'ш',
        't' => 'т', 'u' => 'ұ', 'ü' => 'ү', 'v' => 'в', 'w' => 'у', 'x' => 'х', 'ý' => 'й',
        'z' => 'з',

        'A' => 'А', 'Ä' => 'Ә', 'B' => 'Б', 'C' => 'Ц', 'Ç' => 'Ч', 'D' => 'Д', 'E' => 'Е',
        'É' => 'Э', 'F' => 'Ф', 'G' => 'Г', 'Ğ' => 'Ғ', 'H' => 'Һ', 'İ' => 'І', 'I' => 'Ы',
        'Ï' => 'И', 'J' => 'Ж', 'K' => 'К', 'L' => 'Л', 'M' => 'М', 'N' => 'Н', 'Ñ' => 'Ң',
        'O' => 'О', 'Ö' => 'Ө', 'P' => 'П', 'Q' => 'Қ', 'R' => 'Р', 'S' => 'С', 'Ş' => 'Ш',
        'T' => 'Т', 'U' => 'Ұ', 'Ü' => 'Ү', 'V' => 'В', 'W' => 'У', 'Ý' => 'Й', 'X' => 'Х',
        'Z' => 'З'
    );

    var $mCyrillicToLatin = array(
        'а' => 'a',  'ә' => 'ä',  'б' => 'b',  'в' => 'v',  'г' => 'g',  'ғ' => 'ğ',
        'д' => 'd',  'е' => 'e',  'ё' => 'yo', 'ж' => 'j',  'з' => 'z',  'и' => 'ï',
        'й' => 'ý',  'к' => 'k',  'қ' => 'q',  'л' => 'l',  'м' => 'm',  'н' => 'n',
        'ң' => 'ñ',  'о' => 'o',  'ө' => 'ö',  'п' => 'p',  'р' => 'r',  'с' => 's',
        'т' => 't',  'у' => 'w',  'ұ' => 'u',  'ү' => 'ü',  'ф' => 'f',  'х' => 'x',
        'һ' => 'h',  'ц' => 'c',  'ч' => 'ç',  'ш' => 'ş',  'щ' => 'şç', 'ъ' => 'ʺ',
        'ы' => 'ı',  'ь' => 'ʹ',  'і' => 'i',  'э' => 'é',  'ю' => 'yw', 'я' => 'ya',

        'А' => 'A',  'Ә' => 'Ä',  'Б' => 'B',  'В' => 'V',  'Г' => 'G',  'Ғ' => 'Ğ',
        'Д' => 'D',  'Е' => 'E',  'Ё' => 'Yo', 'Ж' => 'J',  'З' => 'Z',  'И' => 'Ï',
        'Й' => 'Ý',  'К' => 'K',  'Қ' => 'Q',  'Л' => 'L',  'М' => 'M',  'Н' => 'N',
        'Ң' => 'Ñ',  'О' => 'O',  'Ө' => 'Ö',  'П' => 'P',  'Р' => 'R',  'С' => 'S',
        'Т' => 'T',  'У' => 'W',  'Ұ' => 'U',  'Ү' => 'Ü',  'Ф' => 'F',  'Х' => 'X',
        'Һ' => 'H',  'Ц' => 'C',  'Ч' => 'Ç',  'Ш' => 'Ş',  'Щ' => 'Şç', 'Ъ' => 'ʺ',
        'Ы' => 'I',  'Ь' => 'ʹ',  'І' => 'İ',  'Э' => 'É',  'Ю' => 'Yw', 'Я' => 'Ya'
    );

    var $mCyrillicToArabic = array(
        'ла' => 'لا', 'ЛА' => 'لا', 'Ла' => 'لا',
        
        'а' => 'ا',  'ә' => 'ٴا',  'б' => 'ب',  'в' => 'ۆ',  'г' => 'گ',  'ғ' => 'ع',
        'д' => 'د',  'е' => 'ە',  'ё' => 'يو', 'ж' => 'ج',  'з' => 'ز',  'и' => 'ي',
        'й' => 'ي',  'к' => 'ك',  'қ' => 'ق',  'л' => 'ل',  'м' => 'م',  'н' => 'ن',
        'ң' => 'ڭ',  'о' => 'و',  'ө' => 'ٴو',  'п' => 'پ',  'р' => 'ر',  'с' => 'س',
        'т' => 'ت',  'у' => 'ۋ',  'ұ' => 'ۇ',  'ү' => 'ٴۇ',  'ф' => 'ف',  'х' => 'ح',
        'һ' => 'ھ',  'ц' => 'تس',  'ч' => 'چ',  'ш' => 'ش',  'щ' => 'شش', 'ъ' => 'ي',
        'ы' => 'ى',  'ь' => 'ي',  'і' => 'ٴى',  'э' => 'ە',  'ю' => 'يۋ', 'я' => 'يا',

        'А' => 'ا',  'Ә' => 'ٴا',  'Б' => 'ب',  'В' => 'ۆ',  'Г' => 'گ',  'Ғ' => 'ع',
        'Д' => 'د',  'Е' => 'ە',  'Ё' => 'يو', 'Ж' => 'ج',  'З' => 'ز',  'И' => 'ي',
        'Й' => 'ي',  'К' => 'ك',  'Қ' => 'ق',  'Л' => 'ل',  'М' => 'م',  'Н' => 'ن',
        'Ң' => 'ڭ',  'О' => 'و',  'Ө' => 'ٴو',  'П' => 'پ',  'Р' => 'ر',  'С' => 'س',
        'Т' => 'ت',  'У' => 'ۋ',  'Ұ' => 'ۇ',  'Ү' => 'ٴۇ',  'Ф' => 'ف',  'Х' => 'ح',
        'Һ' => 'ھ',  'Ц' => 'تس',  'Ч' => 'چ',  'Ш' => 'ش',  'Щ' => 'شش', 'Ъ' => 'ي',
        'Ы' => 'ى',  'Ь' => 'ي',  'І' => 'ٴى',  'Э' => 'ە',  'Ю' => 'يۋ', 'Я' => 'يا',

	      '0' => '۰',
	      '1' => '۱',
	      '2' => '۲',
	      '3' => '۳',
	      '4' => '۴',
	      '5' => '۵',
	      '6' => '۶',
	      '7' => '۷',
	      '8' => '۸',
	      '9' => '۹',
	      '%' => '٪',
	      ',' => '٬',
	      '.' => '٫',
    );

    function loadDefaultTables() {
        $this->mTables = array();
        $this->mTables['kk-kz'] = $this->mLatinToCyrillic;
        $this->mTables['kk-tr'] = $this->mCyrillicToLatin;
        $this->mTables['kk-cn'] = $this->mCyrillicToArabic;
        $this->mTables['kk'] = array();
    }

    /*
     * Override function from LanguageConvertor
     */
    function getPreferredVariant(){
		    global $wgUser, $wgRequest, $wgTitle;;
        // Additional checks:
        //  - There should be no conversion for Talk pages
        if($wgTitle!=NULL && $wgTitle->isTalkPage()){
            return $this->mMainLanguageCode;
        }
		    return parent::getPreferredVariant();
	   }

    /*
     * A function wrapper, if there is no selected variant,
     * leave the link names as they were
     */
    function findVariantLink( &$link, &$nt ) {
        $oldlink=$link;
        parent::findVariantLink($link,$nt);
        if($this->getPreferredVariant()==$this->mMainLanguageCode)
            $link=$oldlink;
    }

    /*
     * We want our external link captions to be converted in variants,
     * so we return the original text instead -{$text}-, except for URLs
     */
    function markNoConversion($text) {
        if(preg_match("/^https?:\/\/|ftp:\/\/|irc:\/\//",$text))
            return parent::markNoConversion($text);
        return $text;
    }

    /*
     * An ugly function wrapper for parsing Image titles
     * (to prevent image name conversion)
     */
    function autoConvert($text, $toVariant=false) {
        global $wgTitle;
        if($wgTitle->getNameSpace()==NS_IMAGE){
            $imagename = $wgTitle->getNsText();
            if(preg_match("/^$imagename:/",$text)) return $text;
        }
        return parent::autoConvert($text,$toVariant);
    }

	function convertCategoryKey( $key ) {
		return $this->autoConvert( $key, 'kk-kz' );
	}

	/**
	 *  It translates text into variant, specials:
	 *    - ommiting roman numbers
	 */
	function translate($text, $toVariant){
		$breaks = '[^\w\x80-\xff]';

		// regexp for roman numbers
		$roman = 'M{0,4}(CM|CD|D?C{0,3})(XC|XL|L?X{0,3})(IX|IV|V?I{0,3})';

		$reg = '/^'.$roman.'$|^'.$roman.$breaks.'|'.$breaks.$roman.'$|'.$breaks.$roman.$breaks.'/';

		$matches = preg_split($reg, $text, -1, PREG_SPLIT_OFFSET_CAPTURE);

		$m = array_shift($matches);
		$ret = strtr($m[0], $this->mTables[$toVariant]);
		$mstart = $m[1]+strlen($m[0]);
		foreach($matches as $m) {
			$ret .= substr($text, $mstart, $m[1]-$mstart);
			$ret .= parent::translate($m[0], $toVariant);
			$mstart = $m[1] + strlen($m[0]);
		}

		return $ret;
	}

}

class LanguageKk extends LanguageKk_kz {

  function __construct() {
    global $wgHooks;
		parent::__construct();

   $variants = array('kk', 'kk-kz', 'kk-tr', 'kk-cn');
   $variantfallbacks = array(
      'kk'    => 'kk-kz',
      'kk-kz' => 'kk-kz',
			'kk-tr' => 'kk-tr',
			'kk-cn' => 'kk-cn'
    );

    $this->mConverter = new KkConverter( $this, 'kk', $variants, $variantfallbacks );
    $wgHooks['ArticleSaveComplete'][] = $this->mConverter;
  }

	function convertGrammar( $word, $case ) {
		$fname="LanguageKk::convertGrammar";
		wfProfileIn( $fname );

    //always convert to kk-kz before convertGrammar
    $w1 = $word;
		$word = $this->mConverter->autoConvert($word, 'kk-kz');
    $w2 = $word;
		$word = parent::convertGrammar( $word, $case );
    //restore encoding
    if( $w1 != $w2 ) {
       $word = $this->mConverter->translate($word, 'kk-tr');
    }
		wfProfileOut( $fname );
		return $word;
	}

}

?>
