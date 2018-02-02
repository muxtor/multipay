<?php
namespace common\components;

use Yii;
use yii\web\Request;
use common\models\Language;
use yii\web\Cookie;

class LangRequest extends Request
{
    private $_lang_url;

    public function getLangUrl()
    {
        if ($this->_lang_url === null) {
            $this->_lang_url = $this->getUrl();
        	$url_list = explode('/', $this->_lang_url);

        	$lang_url = isset($url_list[1]) ? $url_list[1] : null;
            if (!$lang_url) {
                if (isset(Yii::$app->request->cookies['language'])) {
                    $lang_url = Yii::$app->request->cookies['language']->value;
                } elseif (isset($_SERVER["HTTP_ACCEPT_LANGUAGE"]) && strlen($_SERVER["HTTP_ACCEPT_LANGUAGE"]) > 1) {
                    //based on https://www.dyeager.org/2008/10/getting-browser-default-language-php.html
                    $lang = [];
                    # Split possible languages into array
                    $x = explode(",",$_SERVER["HTTP_ACCEPT_LANGUAGE"]);
                    foreach ($x as $val) {
                        #check for q-value and create associative array. No q-value means 1 by rule
                        if(preg_match("/(.*);q=([0-1]{0,1}.\d{0,4})/i",$val,$matches)) {
                           $lang[$matches[1]] = (float)$matches[2];
                        } else {
                           $lang[$val] = 1.0;
                        }
                    }

                    #return default language (highest q-value)
                    $qval = 0.0;
                    foreach ($lang as $key => $value) {
                       if ($value > $qval) {
                          $qval = (float)$value;
                          $deflang = $key;
                       }
                    }
                    $lang_model = Language::find()->where('lang_local = :local', [':local' => $deflang])->one();
                    if ($lang_model) {
                        $lang_url = $lang_model->lang_url;
                    }
                }
            } else {
                $cookies = Yii::$app->response->cookies;
                unset($cookies['language']);
                $languageCookie = new Cookie([
                    'name' => 'language',
                    'value' => $lang_url,
                    'expire' => time() + 60 * 60 * 24 * 30, // 30 days
                ]);
                \Yii::$app->response->cookies->add($languageCookie);
            }
            
        	Language::setCurrent($lang_url);

                if( $lang_url !== null && $lang_url === Language::getCurrent()->lang_url && 
                strpos($this->_lang_url, Language::getCurrent()->lang_url) === 1 )
                {
                     $this->_lang_url = substr($this->_lang_url, strlen(Language::getCurrent()->lang_url)+1);
                }
        }

        return $this->_lang_url;
    }

    protected function resolvePathInfo()
    {
        $pathInfo = $this->getLangUrl();

        if (($pos = strpos($pathInfo, '?')) !== false) {
            $pathInfo = substr($pathInfo, 0, $pos);
        }

        $pathInfo = urldecode($pathInfo);

        // try to encode in UTF8 if not so
        // http://w3.org/International/questions/qa-forms-utf-8.html
        if (!preg_match('%^(?:
            [\x09\x0A\x0D\x20-\x7E]              # ASCII
            | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
            | \xE0[\xA0-\xBF][\x80-\xBF]         # excluding overlongs
            | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
            | \xED[\x80-\x9F][\x80-\xBF]         # excluding surrogates
            | \xF0[\x90-\xBF][\x80-\xBF]{2}      # planes 1-3
            | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
            | \xF4[\x80-\x8F][\x80-\xBF]{2}      # plane 16
            )*$%xs', $pathInfo)
        ) {
            $pathInfo = utf8_encode($pathInfo);
        }

        $scriptUrl = $this->getScriptUrl();
        $baseUrl = $this->getBaseUrl();
        if (strpos($pathInfo, $scriptUrl) === 0) {
            $pathInfo = substr($pathInfo, strlen($scriptUrl));
        } elseif ($baseUrl === '' || strpos($pathInfo, $baseUrl) === 0) {
            $pathInfo = substr($pathInfo, strlen($baseUrl));
        } elseif (isset($_SERVER['PHP_SELF']) && strpos($_SERVER['PHP_SELF'], $scriptUrl) === 0) {
            $pathInfo = substr($_SERVER['PHP_SELF'], strlen($scriptUrl));
        } else {
            throw new InvalidConfigException('Unable to determine the path info of the current request.');
        }

        if ($pathInfo[0] === '/') {
            $pathInfo = substr($pathInfo, 1);
        }

        return (string) $pathInfo;
    }
}