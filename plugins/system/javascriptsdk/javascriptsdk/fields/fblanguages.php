<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldFblanguages extends JFormField {
	protected $type = 'Fblanguages';
    public function getLabel() {
        return '<span style="">' . parent::getLabel() . '</span>';
	}
    
    protected function getInput() {
        $languages = array('default'=>JText::_('DEFAULT TO JOOMLA'),
                            'af_ZA'=>JText::_('AFRIKAANS'),
                            'sq_AL'=>JText::_('ALBANIAN'),
                            'ar_AR'=>JText::_('ARABIC'),
                            'hy_AM'=>JText::_('ARMENIAN'),
                            'ay_BO'=>JText::_('AYMARA'),
                            'az_AZ'=>JText::_('AZERI'),
                            'eu_ES'=>JText::_('BASQUE'),
                            'be_BY'=>JText::_('BELARUSIAN'),
                            'bn_IN'=>JText::_('BENGALI'),
                            'bs_BA'=>JText::_('BOSNIAN'),
                            'bg_BG'=>JText::_('BULGARIAN'),
                            'ca_ES'=>JText::_('CATALAN'),
                            'ck_US'=>JText::_('CHEROKEE'),
                            'hr_HR'=>JText::_('CROATIAN'),
                            'cs_CZ'=>JText::_('CZECH'),
                            'da_DK'=>JText::_('DANISH'),
                            'nl_NL'=>JText::_('DUTCH'),
                            'nl_BE'=>JText::_('DUTCH_BELGIE'),
                            'en_PI'=>JText::_('ENGLISH_PIRATE'),
                            'en_GB'=>JText::_('ENGLISH_UK'),
                            'en_UD'=>JText::_('ENGLISH_UPSIDE_DOWN'),
                            'en_US'=>JText::_('ENGLISH_US'),
                            'eo_EO'=>JText::_('ESPERANTO'),
                            'et_EE'=>JText::_('ESTONIAN'),
                            'fo_FO'=>JText::_('FAROESE'),
                            'tl_PH'=>JText::_('FILIPINO'),
                            'fi_FI'=>JText::_('FINNISH'),
                            'fb_FI'=>JText::_('FINNISH_TEST'),
                            'fr_CA'=>JText::_('FRENCH_CANADA'),
                            'fr_FR'=>JText::_('FRENCH_FRANCE'),
                            'gl_ES'=>JText::_('GALICIAN'),
                            'ka_GE'=>JText::_('GEORGIAN'),
                            'de_DE'=>JText::_('GERMAN'),
                            'el_GR'=>JText::_('GREEK'),
                            'gn_PY'=>JText::_('GUARANI'),
                            'gu_IN'=>JText::_('GUJARATI'),
                            'he_IL'=>JText::_('HEBREW'),
                            'hi_IN'=>JText::_('HINDI'),
                            'hu_HU'=>JText::_('HUNGARIAN'),
                            'is_IS'=>JText::_('ICELANDIC'),
                            'id_ID'=>JText::_('INDONESIAN'),
                            'ga_IE'=>JText::_('IRISH'),
                            'it_IT'=>JText::_('ITALIAN'),
                            'ja_JP'=>JText::_('JAPANESE'),
                            'jv_ID'=>JText::_('JAVANESE'),
                            'kn_IN'=>JText::_('KANNADA'),
                            'kk_KZ'=>JText::_('KAZAKH'),
                            'km_KH'=>JText::_('KHMER'),
                            'tl_ST'=>JText::_('KLINGON'),
                            'ko_KR'=>JText::_('KOREAN'),
                            'ku_TR'=>JText::_('KURDISH'),
                            'la_VA'=>JText::_('LATIN'),
                            'lv_LV'=>JText::_('LATVIAN'),
                            'fb_LT'=>JText::_('LEET_SPEAK'),
                            'li_NL'=>JText::_('LIMBURGISH'),
                            'lt_LT'=>JText::_('LITHUANIAN'),
                            'mk_MK'=>JText::_('MACEDONIAN'),
                            'mg_MG'=>JText::_('MALAGASY'),
                            'ms_MY'=>JText::_('MALAY'),
                            'ml_IN'=>JText::_('MALAYALAM'),
                            'mt_MT'=>JText::_('MALTESE'),
                            'mr_IN'=>JText::_('MARATHI'),
                            'mn_MN'=>JText::_('MONGOLIAN'),
                            'ne_NP'=>JText::_('NEPALI'),
                            'se_NO'=>JText::_('NORTHERN_SAMI'),
                            'nb_NO'=>JText::_('NORWEGIAN_BOKMAL'),
                            'nn_NO'=>JText::_('NORWEGIAN_NYNORSK'),
                            'ps_AF'=>JText::_('PASHTO'),
                            'fa_IR'=>JText::_('PERSIAN'),
                            'pl_PL'=>JText::_('POLISH'),
                            'pt_BR'=>JText::_('PORTUGUESE_BRAZIL'),
                            'pt_PT'=>JText::_('PORTUGUESE_PORTUGAL'),
                            'pa_IN'=>JText::_('PUNJABI'),
                            'qu_PE'=>JText::_('QUECHUA'),
                            'ro_RO'=>JText::_('ROMANIAN'),
                            'rm_CH'=>JText::_('ROMANSH'),
                            'ru_RU'=>JText::_('RUSSIAN'),
                            'sa_IN'=>JText::_('SANSKRIT'),
                            'sr_RS'=>JText::_('SERBIAN'),
                            'zh_CN'=>JText::_('SIMPLIFIED_CHINESE_CHINA'),
                            'sk_SK'=>JText::_('SLOVAK'),
                            'sl_SI'=>JText::_('SLOVENIAN'),
                            'so_SO'=>JText::_('SOMALI'),
                            'es_LA'=>JText::_('SPANISH'),
                            'es_CL'=>JText::_('SPANISH_CHILE'),
                            'es_CO'=>JText::_('SPANISH_COLOMBIA'),
                            'es_MX'=>JText::_('SPANISH_MEXICO'),
                            'es_ES'=>JText::_('SPANISH_SPAIN'),
                            'es_VE'=>JText::_('SPANISH_VENEZUELA'),
                            'sw_KE'=>JText::_('SWAHILI'),
                            'sv_SE'=>JText::_('SWEDISH'),
                            'sy_SY'=>JText::_('SYRIAC'),
                            'tg_TJ'=>JText::_('TAJIK'),
                            'ta_IN'=>JText::_('TAMIL'),
                            'tt_RU'=>JText::_('TATAR'),
                            'te_IN'=>JText::_('TELUGU'),
                            'th_TH'=>JText::_('THAI'),
                            'zh_HK'=>JText::_('TRADITIONAL_CHINESE_HONG_KONG'),
                            'zh_TW'=>JText::_('TRADITIONAL_CHINESE_TAIWAN'),
                            'tr_TR'=>JText::_('TURKISH'),
                            'uk_UA'=>JText::_('UKRAINIAN'),
                            'ur_PK'=>JText::_('URDU'),
                            'uz_UZ'=>JText::_('UZBEK'),
                            'vi_VN'=>JText::_('VIETNAMESE'),
                            'cy_GB'=>JText::_('WELSH'),
                            'xh_ZA'=>JText::_('XHOSA'),
                            'yi_DE'=>JText::_('YIDDISH'),
                            'zu_ZA'=>JText::_('ZULU')
                            );
        $options = array(); // Empty array to add options into
        $options[] = JHTML :: _('select.option', '', JText::_('SELECT_LANGUAGE')); // Initial option
//        $options[] = JHTML::_('select.optgroup', 'Activities'); // optgroup option to add group of items
        foreach($languages as $key => $text) { // loop through items
            $options[] = JHTML::_('select.option', $key, $text);
        }
//        $options[] = JHTML::_('select.option', '</OPTGROUP>'); // Close OptGroup
        
        $select = JHTML::_(
         'select.genericlist', // Select element
         $options,             // Options that we created above
         $this->name,          // The name select element in HTML
         'size="1" ',          // Extra parameters to add for select element
         'value',              // The name of the object variable for the option value
         'text',               // The name of the object variable for the option text
         $this->value,         // The key that is selected (accepts an array or a string)
         true                  // Flag to translate the option results
        );

        return $select;
	}
}

?>

