<?php
/**
 * Pi Engine (http://piengine.org)
 *
 * @link            http://code.piengine.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://piengine.org
 * @license         http://piengine.org/license.txt New BSD License
 */

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */

namespace Module\Shop\Route;

use Pi;
use Pi\Mvc\Router\Http\Standard;

class Shop extends Standard
{
    /**
     * Default values.
     *
     * @var array
     */
    protected $defaults
        = [
            'module'     => 'shop',
            'controller' => 'index',
            'action'     => 'index',
        ];

    protected $controllerList
        = [
            'cart', 'category', 'index', 'json', 'product', 'tag', 'compare', 'question', 'serial', 'result',
        ];

    /**
     * {@inheritDoc}
     */
    protected $structureDelimiter = '/';

    /**
     * {@inheritDoc}
     */
    protected function parse($path)
    {
        $matches = [];
        $parts   = array_filter(explode($this->structureDelimiter, $path));

        // Set controller
        $matches = array_merge($this->defaults, $matches);
        if (isset($parts[0]) && in_array($parts[0], $this->controllerList)) {
            $matches['controller'] = $this->decode($parts[0]);
            // Make Match
            if (isset($matches['controller'])) {
                switch ($matches['controller']) {

                    case 'category':
                        if (!isset($parts[1])) {
                            $matches['action'] = 'list';
                        }
                        break;

                    case 'index':
                        $matches['action'] = 'index';
                        break;

                    case 'product':
                        $matches['action'] = 'index';
                        break;

                    case 'question':
                        $matches['action'] = 'index';
                        break;

                    case 'cart':
                        if (isset($parts[1]) && $parts[1] == 'complete') {
                            $matches['action'] = 'complete';
                        } elseif (isset($parts[1]) && $parts[1] == 'add') {
                            $matches['action'] = 'add';
                        } elseif (isset($parts[1]) && $parts[1] == 'update') {
                            $matches['action'] = 'update';
                        } elseif (isset($parts[1]) && $parts[1] == 'finish') {
                            //$matches['action'] = 'finish';
                            //$matches['id'] = intval($parts[2]);
                        } elseif (isset($parts[1]) && $parts[1] == 'empty') {
                            $matches['action'] = 'empty';
                        } elseif (isset($parts[1]) && $parts[1] == 'index') {
                            $matches['action'] = 'index';
                        } elseif (isset($parts[1]) && $parts[1] == 'levelAjax') {
                            $matches['action']  = 'levelAjax';
                            $matches['process'] = $this->decode($parts[2]);
                            if (is_numeric($parts[3])) {
                                $matches['id'] = intval($parts[3]);
                            } elseif ($parts[2] == 'payment') {
                                $matches['id'] = $this->decode($parts[3]);
                            }
                        } elseif (isset($parts[1]) && $parts[1] == 'basket') {
                            $matches['action'] = 'basket';
                            if (isset($parts[2]) && in_array($parts[2], ['remove', 'number'])) {
                                $matches['process'] = $this->decode($parts[2]);
                                $matches['product'] = intval($parts[3]);
                                if (isset($parts[4]) && in_array($parts[4], [1, -1])) {
                                    $matches['number'] = $parts[4];
                                }
                            }
                        } elseif (isset($parts[1]) && $parts[1] == 'promotion') {
                            $matches['action'] = 'promotion';
                        }
                        break;

                    case 'tag':
                        if (isset($parts[1]) && !empty($parts[1])) {
                            $matches['action'] = 'index';
                            $matches['slug']   = urldecode($parts[1]);
                        } else {
                            $matches['action'] = 'list';
                        }
                        break;

                    case 'json':
                        $matches['action'] = $this->decode($parts[1]);

                        if ($parts[1] == 'filterCategory') {
                            $matches['slug'] = $this->decode($parts[2]);
                        } elseif ($parts[1] == 'filterTag') {
                            $matches['slug'] = $this->decode($parts[2]);
                        } elseif ($parts[1] == 'filterSearch') {
                            $keyword = _get('keyword');
                            if (isset($keyword) && !empty($keyword)) {
                                $matches['keyword'] = $keyword;
                            }
                        }

                        if (isset($parts[2]) && $parts[2] == 'id') {
                            $matches['id'] = intval($parts[3]);
                        }

                        if (isset($parts[2]) && $parts[2] == 'update') {
                            $matches['update'] = intval($parts[3]);
                        } elseif (isset($parts[4]) && $parts[4] == 'update') {
                            $matches['update'] = intval($parts[5]);
                        }

                        if (isset($parts[4]) && $parts[4] == 'password') {
                            $matches['password'] = $this->decode($parts[5]);
                        } elseif (isset($parts[6]) && $parts[6] == 'password') {
                            $matches['password'] = $this->decode($parts[7]);
                        }

                        break;

                    case 'compare':
                        if (isset($parts[1]) && $parts[1] == 'ajax') {
                        } else {
                            $parts              = array_unique($parts);
                            $parts              = array_values($parts);
                            $matches['product'] = [];
                            if (isset($parts[1]) && !empty($parts[1])) {
                                $matches['product'][1] = $this->decode($parts[1]);
                            }
                            if (isset($parts[2]) && !empty($parts[2])) {
                                $matches['product'][2] = $this->decode($parts[2]);
                            }
                            if (isset($parts[3]) && !empty($parts[3])) {
                                $matches['product'][3] = $this->decode($parts[3]);
                            }
                            if (isset($parts[4]) && !empty($parts[4])) {
                                $matches['product'][4] = $this->decode($parts[4]);
                            }
                            if (isset($parts[5]) && !empty($parts[5])) {
                                $matches['product'][5] = $this->decode($parts[5]);
                            }
                        }
                        break;
                }
            }
        } elseif (isset($parts[0])) {
            $parts[0]     = urldecode($parts[0]);
            $categorySlug = Pi::registry('categoryRoute', 'shop')->read();
            if (in_array($parts[0], $categorySlug)) {
                $matches['controller'] = 'category';
                $matches['action']     = 'index';
                $matches['slug']       = $this->decode($parts[0]);
            } else {
                $matches['controller'] = 'product';
                $matches['action']     = 'index';
                $matches['slug']       = $this->decode($parts[0]);
            }
        }

        /* echo '<pre>';
        print_r($matches);
        print_r($parts);
        echo '</pre>'; */

        return $matches;
    }

    /**
     * assemble(): Defined by Route interface.
     *
     * @param array $params
     * @param array $options
     *
     * @return string
     * @see    Route::assemble()
     *
     */
    public function assemble(
        array $params = [],
        array $options = []
    ) {
        $mergedParams = array_merge($this->defaults, $params);
        if (!$mergedParams) {
            return $this->prefix;
        }

        // Set module
        if (!empty($mergedParams['module'])) {
            $url['module'] = $mergedParams['module'];
        }

        // Set controller
        if (!empty($mergedParams['controller'])
            && $mergedParams['controller'] != 'index'
            && $mergedParams['controller'] != 'category'
            && $mergedParams['controller'] != 'product'
            && in_array($mergedParams['controller'], $this->controllerList)
        ) {
            $url['controller'] = $mergedParams['controller'];
        }

        // Set action
        if (!empty($mergedParams['action'])
            && $mergedParams['action'] != 'index'
        ) {
            $url['action'] = $mergedParams['action'];
        }

        // Set category list url
        if ($mergedParams['controller'] == 'category'
            && $mergedParams['action'] == 'index'
            && empty($mergedParams['slug'])
        ) {
            $url['controller'] = 'category';
        }

        // Set if controller is checkou
        if ($mergedParams['controller'] == 'cart') {
            if ($mergedParams['action'] == 'basket') {
                $url['process'] = $mergedParams['process'];
                $url['product'] = $mergedParams['product'];
                if (!empty($mergedParams['number'])) {
                    $url['number'] = $mergedParams['number'];
                }
            }
        }

        // Set slug
        if (!empty($mergedParams['slug'])) {
            $url['slug'] = $mergedParams['slug'];
        }

        // Set id
        if (!empty($mergedParams['id']) && $mergedParams['controller'] == 'json') {
            $url['id'] = 'id' . $this->paramDelimiter . $mergedParams['id'];
        } elseif (!empty($mergedParams['id'])) {
            $url['id'] = $mergedParams['id'];
        }

        // Set update
        if (!empty($mergedParams['update'])) {
            $url['update'] = 'update' . $this->paramDelimiter . $mergedParams['update'];
        }

        // Set slug
        if (!empty($mergedParams['q'])) {
            $url['q'] = $mergedParams['q'];
        }

        // Set password
        if (!empty($mergedParams['password'])) {
            $url['password'] = 'password' . $this->paramDelimiter . $mergedParams['password'];
        }

        // Make url
        $url = implode($this->paramDelimiter, $url);

        if (empty($url)) {
            return $this->prefix;
        }
        return $this->paramDelimiter . $url;
    }
}
