<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\components\modules;

use Yii;
use yii\base\InvalidRouteException;

/**
 * Application is the base class for all web application classes.
 *
 * @property string $homeUrl The homepage URL.
 * @property Session $session The session component. This property is read-only.
 * @property User $user The user component. This property is read-only.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class CustomApplication extends \yii\web\Application
{
    /**
     * Creates a controller based on the given controller ID.
     *
     * The controller ID is relative to this module. The controller class
     * should be namespaced under [[controllerNamespace]].
     *
     * Note that this method does not check [[modules]] or [[controllerMap]].
     *
     * @param string $id the controller ID
     * @return Controller the newly created controller instance, or null if the controller ID is invalid.
     * @throws InvalidConfigException if the controller class and its file name do not match.
     * This exception is only thrown when in debug mode.
     */
    public function createControllerByID($id)
    {
        $pos = strrpos($id, '/');
        if ($pos === false) {
            $prefix = '';
            $className = $id;
        } else {
            $prefix = substr($id, 0, $pos + 1);
            $className = substr($id, $pos + 1);
        }

        if (!preg_match('%^[a-z][a-z0-9\\-_]*$%', $className)) {
            return null;
        }
        if ($prefix !== '' && !preg_match('%^[a-z0-9_/]+$%i', $prefix)) {
            return null;
        }

        $className = str_replace(' ', '', ucwords(str_replace('-', ' ', $className))) . 'Controller';
        $className = ltrim($this->controllerNamespace . '\\' . str_replace('/', '\\', $prefix)  . $className, '\\');
        if (strpos($className, '-') !== false || !class_exists($className)) {
            return null;
        }

        if (is_subclass_of($className, 'yii\base\Controller')) {
            $controller = Yii::createObject($className, [$id, $this]);
            // FIX      version 3.0.4: allow controller & anction camelcase EX: NameCamelCase/ NameCamelCase
            // Original version 3.0.4: return get_class($controller) === $className ? $controller : null;
            return strtolower(get_class($controller)) === strtolower($className) ? $controller : null;
        } elseif (YII_DEBUG) {
            throw new InvalidConfigException("Controller class must extend from \\yii\\base\\Controller.");
        } else {
            return null;
        }
    }
}
