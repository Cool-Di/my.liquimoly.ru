<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 16.11.2017
 * Time: 10:37
 */

class LmMemcache
{

    const
        ERROR_MEMCACHE_CONNECT = 'Сервер кэширования недоступен',
        ERROR_MEMCACHE_CLASS_NOTFOUND = 'Кэширование не поддерживается',
        ERROR_MEMCACHE_EXTENTION_NOTFOUND = 'Модуль кэширования недоступен на веб-сервере',
        NAME_MEMCACHE = 'memcache';

    private static $instance = null;

    private $cache = null;
    private $status = false;
    private $host;
    private $port;

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct($host = 'localhost', $port = '11211')
    {
        $this->host = $host;
        $this->port = $port;
        try {
            if (extension_loaded(self::NAME_MEMCACHE)) {
                $this->initMemcache();
            } else LmException::createLmException(LmMemcache::ERROR_MEMCACHE_EXTENTION_NOTFOUND);
        } catch (LmException $e) {
            $e->registerException($this, true);
        }
    }

    private function initMemcache()
    {
        try {
            class_exists(self::NAME_MEMCACHE) or LmException::createLmException(LmMemcache::ERROR_MEMCACHE_CLASS_NOTFOUND);
            $this->cache = new Memcache();

            $this->status = @$this->cache->connect($this->host, $this->port);
            ($this->status !== false) or LmException::createLmException(LmMemcache::ERROR_MEMCACHE_CONNECT);

        } catch (LmException $e) {
            $e->registerException($this, true);
        } catch (Exception $e) {
            LmException::createLmException($e->getMessage());
        }
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getMc()
    {
        return $this->cache;
    }

    public function getMemcache($key)
    {
        return $result = ($this->status) ? $this->cache->get($key) : false;
    }

    public function setMemcache($key, $data, $time = 0)
    {
        return $result = ($this->status) ? $this->cache->set($key, $data, MEMCACHE_COMPRESSED, $time) : false;
    }

    public function deleteMemcache($key)
    {
        return $result = ($this->status) ? $this->cache->delete($key) : false;
    }

    public function replaceMemcache($key, $data, $time = 0)
    {
        return $result = ($this->status) ? $this->cache->replace($key, $data, MEMCACHE_COMPRESSED, $time) : false;
    }

    public function getVarMemcache($var = [])
    {
        return $this->cache->getStats()[$var];
    }

    public function getStatusMemcache()
    {
        return $this->cache->getStats();
    }

}