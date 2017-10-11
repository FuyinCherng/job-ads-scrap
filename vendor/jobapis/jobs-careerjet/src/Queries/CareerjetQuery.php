<?php namespace JobApis\Jobs\Client\Queries;

class CareerjetQuery extends AbstractQuery
{
    /**
     * affid
     *
     * @var string
     */
    protected $affid;

    /**
     * locale_code
     *
     * Available locales:
     *
     *   LOCALE     LANGUAGE         DEFAULT LOCATION     CAREERJET SITE
     *   cs_CZ      Czech            Czech Republic       http://www.careerjet.cz
     *   da_DK      Danish           Denmark              http://www.careerjet.dk
     *   de_AT      German           Austria              http://www.careerjet.at
     *   de_CH      German           Switzerland          http://www.careerjet.ch
     *   de_DE      German           Germany              http://www.careerjet.de
     *   en_AE      English          United Arab Emirates http://www.careerjet.ae
     *   en_AU      English          Australia            http://www.careerjet.com.au
     *   en_CA      English          Canada               http://www.careerjet.ca
     *   en_CN      English          China                http://www.career-jet.cn
     *   en_HK      English          Hong Kong            http://www.careerjet.hk
     *   en_IE      English          Ireland              http://www.careerjet.ie
     *   en_IN      English          India                http://www.careerjet.co.in
     *   en_MY      English          Malaysia             http://www.careerjet.com.my
     *   en_NZ      English          New Zealand          http://www.careerjet.co.nz
     *   en_OM      English          Oman                 http://www.careerjet.com.om
     *   en_PH      English          Philippines          http://www.careerjet.ph
     *   en_PK      English          Pakistan             http://www.careerjet.com.pk
     *   en_QA      English          Qatar                http://www.careerjet.com.qa
     *   en_SG      English          Singapore            http://www.careerjet.sg
     *   en_GB      English          United Kingdom       http://www.careerjet.com
     *   en_US      English          United States        http://www.careerjet.com
     *   en_ZA      English          South Africa         http://www.careerjet.co.za
     *   en_TW      English          Taiwan               http://www.careerjet.com.tw
     *   en_VN      English          Vietnam              http://www.careerjet.vn
     *   es_AR      Spanish          Argentina            http://www.opcionempleo.com.ar
     *   es_BO      Spanish          Bolivia              http://www.opcionempleo.com.bo
     *   es_CL      Spanish          Chile                http://www.opcionempleo.cl
     *   es_CR      Spanish          Costa Rica           http://www.opcionempleo.co.cr
     *   es_DO      Spanish          Dominican Republic   http://www.opcionempleo.com.do
     *   es_EC      Spanish          Ecuador              http://www.opcionempleo.ec
     *   es_ES      Spanish          Spain                http://www.opcionempleo.com
     *   es_GT      Spanish          Guatemala            http://www.opcionempleo.com.gt
     *   es_MX      Spanish          Mexico               http://www.opcionempleo.com.mx
     *   es_PA      Spanish          Panama               http://www.opcionempleo.com.pa
     *   es_PE      Spanish          Peru                 http://www.opcionempleo.com.pe
     *   es_PR      Spanish          Puerto Rico          http://www.opcionempleo.com.pr
     *   es_PY      Spanish          Paraguay             http://www.opcionempleo.com.py
     *   es_UY      Spanish          Uruguay              http://www.opcionempleo.com.uy
     *   es_VE      Spanish          Venezuela            http://www.opcionempleo.com.ve
     *   fi_FI      Finnish          Finland              http://www.careerjet.fi
     *   fr_CA      French           Canada               http://www.option-carriere.ca
     *   fr_BE      French           Belgium              http://www.optioncarriere.be
     *   fr_CH      French           Switzerland          http://www.optioncarriere.ch
     *   fr_FR      French           France               http://www.optioncarriere.com
     *   fr_LU      French           Luxembourg           http://www.optioncarriere.lu
     *   fr_MA      French           Morocco              http://www.optioncarriere.ma
     *   hu_HU      Hungarian        Hungary              http://www.careerjet.hu
     *   it_IT      Italian          Italy                http://www.careerjet.it
     *   ja_JP      Japanese         Japan                http://www.careerjet.jp
     *   ko_KR      Korean           Korea                http://www.careerjet.co.kr
     *   nl_BE      Dutch            Belgium              http://www.careerjet.be
     *   nl_NL      Dutch            Netherlands          http://www.careerjet.nl
     *   no_NO      Norwegian        Norway               http://www.careerjet.no
     *   pl_PL      Polish           Poland               http://www.careerjet.pl
     *   pt_PT      Portuguese       Portugal             http://www.careerjet.pt
     *   pt_BR      Portuguese       Brazil               http://www.careerjet.com.br
     *   ru_RU      Russian          Russia               http://www.careerjet.ru
     *   ru_UA      Russian          Ukraine              http://www.careerjet.com.ua
     *   sv_SE      Swedish          Sweden               http://www.careerjet.se
     *   sk_SK      Slovak           Slovakia             http://www.careerjet.sk
     *   tr_TR      Turkish          Turkey               http://www.careerjet.com.tr
     *   uk_UA      Ukrainian        Ukraine              http://www.careerjet.ua
     *   vi_VN      Vietnamese       Vietnam              http://www.careerjet.com.vn
     *   zh_CN      Chinese          China                http://www.careerjet.cn
     *
     * @var string
     */
    protected $locale_code;

    /**
     * keywords
     *
     * The search query.
     *
     * @var string
     */
    protected $keywords;

    /**
     * location
     *
     * Examples: 'London', 'Paris'
     *
     * @var string
     */
    protected $location;

    /**
     * sort
     *
     * Available values are 'relevance' (default), 'date', and 'salary'.
     *
     * @var string
     */
    protected $sort;

    /**
     * start_num
     *
     * Num of first offer returned in entire result space should be >= 1
     * and <= Number of hits
     *
     * @var integer
     */
    protected $start_num;

    /**
     * pagesize
     *
     * Number of offers returned in one call, default: 20
     *
     * @var integer
     */
    protected $pagesize;

    /**
     * page
     *
     * Current page number (should be >=1). If set, will override start_num.
     *
     * @var integer
     */
    protected $page;

    /**
     * contracttype
     *
     * Character code for contract types:
     *    'p'    - permanent job
     *    'c'    - contract
     *    't'    - temporary
     *    'i'    - training
     *    'v'    - voluntary
     *
     * @var string
     */
    protected $contracttype;

    /**
     * contractperiod
     *
     * Character code for contract contract periods:
     *    'f'     - Full time
     *    'p'     - Part time
     *
     * @var string
     */
    protected $contractperiod;

    /**
     * Get baseUrl
     *
     * @return  string Value of the base url to this api
     */
    public function getBaseUrl()
    {
        return 'http://public.api.careerjet.net/search';
    }

    /**
     * Get keyword
     *
     * @return  string Attribute being used as the search keyword
     */
    public function getKeyword()
    {
        return $this->keywords;
    }

    /**
     * Required parameters
     *
     * @return array
     */
    protected function requiredAttributes()
    {
        return [
            'affid'
        ];
    }

    protected function defaultAttributes()
    {
        return [
            'locale_code' => 'en_US',
        ];
    }
}
