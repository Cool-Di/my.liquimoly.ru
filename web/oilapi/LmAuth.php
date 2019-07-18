<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 23.11.2017
 * Time: 9:37
 */

class LmAuth
{
    const
        ERROR_AUTH = 'Ошибка авторизации',
        REFRESH_CACHE_DATA_AUTH = 1,
        REFRESH_INC_COUNT = 10,
        STEP_INC_COUNT = 1,
        CACHE_KEY_NAME = 'profile';

    private static $instance = null;

    private $profile = null;
    private $authKey = null;
    private $clientId = null;
    private $error = null;
    private $ipAccess = [];

    private function __construct()
    {
        $this->ipAccess = include_once __DIR__ . DIRECTORY_SEPARATOR . 'access.php';
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function authClient($clientId)
    {
        $result = false;

        try {

            $this->clientId = $clientId;
            $this->authKey = LmUtil::getKey([self::CACHE_KEY_NAME, $clientId]);

            $this->prepareProfile($clientId);

            (!is_null($this->getProfile())) or LmException::createLmException('Профиль не определён', $clientId);

            ($this->checkAccess()) or LmException::createLmException('Ошибка доступа с хоста', LmRouter::getHost() . ' ' . LmRouter::getRealIp());

            ($this->checkLimit()) or LmException::createLmException('Ограничение по лимиту', $this->getProfile('limit'));

            $this->updateProfile();

            $this->updateCache();

            $this->updateDb();

            $result = true;

        } catch (LmException $e) {

            $this->error = $e->getMessage();

        } finally {

            return $result;

        }
    }

    private function prepareProfile($clientId)
    {
        $profile = LmMemcache::getInstance()->getMemcache($this->authKey);
        $result = ($profile !== false) ? true : false;

        if (!$result) {
            $db = LmDb::getInstance();
            $profile = $db->getClientProfile($clientId);
        }

        $this->setProfile($profile);
    }

    public function getError()
    {
        return $this->error;
    }

    public function checkAccess()
    {
        $outRequest = LmRouter::getRealIp();
        $httpOrigin = LmRouter::getHost();

        $result = (($this->profile['host'] === $httpOrigin)
            || ($this->profile['ip'] === $outRequest)
            || LmRouter::checkHostSource());

        $result = $result || $this->isRootAccess($outRequest);

        return $result;
    }

    private function checkLimit()
    {
        return ((int)$this->profile['limit'] > 0);
    }

    public function getProfile($var = null)
    {

        return (is_null($var)) ? $this->profile : ((isset($this->profile[$var])) ? $this->profile[$var] : null);

    }

    private function setProfile($profile)
    {
        $this->profile = $profile;
    }

    private function updateProfile()
    {

        $profile = $this->getProfile();

        $limit = (int)$profile['limit'] - self::STEP_INC_COUNT;
        $profile['limit'] = ($limit <= 0) ? '0' : (string)$limit;

        $this->setProfile($profile);
    }

    private function updateCache()
    {

        if (!$this->checkLimit()) {
            $this->resetCacheProfile();
            return;
        }

        $profile = $this->getProfile();

        if (!LmMemcache::getInstance()->replaceMemcache($this->authKey, $profile, LmUtil::getMinutes(LmAuth::REFRESH_CACHE_DATA_AUTH))) {
            LmMemcache::getInstance()->setMemcache($this->authKey, $profile, LmUtil::getMinutes(LmAuth::REFRESH_CACHE_DATA_AUTH));
        }

    }

    private function updateDb()
    {
        $minLimit = 0;

        if (!LmMemcache::getInstance()->getStatus()) {
            $minLimit = self::STEP_INC_COUNT;
        } elseif ($this->updateCounter()) {
            $minLimit = self::REFRESH_INC_COUNT;
        }

        if (!$this->checkLimit()) {
            $minLimit = LmMemcache::getInstance()->getMemcache($this->clientId);
            $minLimit = ($minLimit == 0) ? 1 : $minLimit;
            LmMemcache::getInstance()->replaceMemcache($this->clientId, 0);
        }

        if ($minLimit > 0) {
            LmDb::getInstance()->updateClientProfile($this->clientId, $minLimit);
            $this->resetCacheProfile();
        }

    }

    private function updateCounter()
    {
        $clientId = $this->clientId;

        if (!LmMemcache::getInstance()->getMemcache($clientId)) {
            LmMemcache::getInstance()->setMemcache($clientId, 0);
        }

        $result = (LmMemcache::getInstance()->getMc()->increment($clientId, 1) == self::REFRESH_INC_COUNT);
        LmMemcache::getInstance()->getMemcache($clientId);
        if ($result) {
            LmMemcache::getInstance()->replaceMemcache($clientId, 0);
        }

        return $result;
    }

    private function resetCacheProfile()
    {
        LmMemcache::getInstance()->deleteMemcache($this->authKey);
    }

    public function isRootAccess($ip)
    {
        $result = isset($this->ipAccess[$ip]);
        return $result;
    }

}