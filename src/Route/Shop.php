<?php
/**
 * Pi Engine (http://pialog.org)
 *
 * @link            http://code.pialog.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://pialog.org
 * @license         http://pialog.org/license.txt New BSD License
 */

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */

namespace Module\Shop\Route;

use Pi\Mvc\Router\Http\Standard;

class Shop extends Standard
{
    /**
     * Default values.
     * @var array
     */
    protected $defaults = array(
        'module'        => 'shop',
        'controller'    => 'index',
        'action'        => 'index'
    );

    protected $sortList = array(
        'create', 'update', 'price', 'stock'
    );

    protected $controllerList = array(
        'category', 'checkout', 'index', 'product', 'search', 'tag', 'user'
    );

    /**
     * {@inheritDoc}
     */
    protected $structureDelimiter = '/';

    /**
     * {@inheritDoc}
     */
    protected function parse($path)
    {
        $matches = array();
        $parts = array_filter(explode($this->structureDelimiter, $path));

        // Set controller
        $matches = array_merge($this->defaults, $matches);
        if (isset($parts[0]) && in_array($parts[0], $this->controllerList)) {
            $matches['controller'] = urldecode($parts[0]);
        }

        // Make Match
        if (isset($matches['controller'])) {
            switch ($matches['controller']) {
                case 'category':
                    if (!empty($parts[1])) {
                        $matches['action'] = urldecode($parts[1]);
                        $matches['slug'] = urldecode($parts[1]);
                        // Set page
                        if (isset($parts[2]) && $parts[2] == 'page') {
                            $matches['page'] = intval($parts[3]);
                            // Set sort and stock
                            if (isset($parts[4]) && $parts[4] == 'sort' 
                                && isset($parts[6]) && $parts[6] == 'stock') {
                                $matches['sort'] = urldecode($parts[5]);
                                $matches['stock'] = urldecode($parts[7]);
                            } elseif (isset($parts[4]) && $parts[4] == 'sort') {
                                $matches['sort'] = urldecode($parts[5]);
                            } elseif (isset($parts[4]) && $parts[4] == 'stock') {
                                $matches['stock'] = urldecode($parts[5]);
                            } 
                        } else {
                            // Set sort and stock
                            if (isset($parts[2]) && $parts[2] == 'sort' 
                                && isset($parts[4]) && $parts[4] == 'stock') {
                                $matches['sort'] = urldecode($parts[3]);
                                $matches['stock'] = urldecode($parts[5]);
                            } elseif (isset($parts[2]) && $parts[2] == 'sort') {
                                $matches['sort'] = urldecode($parts[3]);
                            } elseif (isset($parts[2]) && $parts[2] == 'stock') {
                                $matches['stock'] = urldecode($parts[3]);
                            } 
                        }
                    }
                    break;

                case 'checkout':
                
                    break; 

                case 'index':
                    // Set page
                    if (isset($parts[0]) && $parts[0] == 'page') {
                        $matches['page'] = intval($parts[1]);
                        // Set sort and stock
                        if (isset($parts[2]) && $parts[2] == 'sort' 
                            && isset($parts[4]) && $parts[4] == 'stock') {
                            $matches['sort'] = urldecode($parts[3]);
                            $matches['stock'] = urldecode($parts[5]);
                        } elseif (isset($parts[2]) && $parts[2] == 'sort') {
                            $matches['sort'] = urldecode($parts[3]);
                        } elseif (isset($parts[2]) && $parts[2] == 'stock') {
                            $matches['stock'] = urldecode($parts[3]);
                        }
                    } else {
                        // Set sort and stock
                        if (isset($parts[0]) && $parts[0] == 'sort' 
                            && isset($parts[2]) && $parts[2] == 'stock') {
                            $matches['sort'] = urldecode($parts[1]);
                            $matches['stock'] = urldecode($parts[3]);
                        } elseif (isset($parts[0]) && $parts[0] == 'sort') {
                            $matches['sort'] = urldecode($parts[1]);
                        } elseif (isset($parts[0]) && $parts[0] == 'stock') {
                            $matches['stock'] = urldecode($parts[1]);
                        }
                    }
                     
                    break;

                case 'product':
                    if (!empty($parts[1])) {
                        if ($parts[1] == 'result') {
                            $matches['action'] = 'print';
                            $matches['slug'] = urldecode($parts[2]);
                        } else {
                            $matches['slug'] = urldecode($parts[1]);
                        }
                    }
                    break; 

                case 'search':
                    if (!empty($parts[1])) {
                        if ($parts[1] == 'result') {
                            $matches['action'] = 'result';
                            // Set page
                            if (isset($parts[2]) && $parts[2] == 'page') {
                                $matches['page'] = intval($parts[3]);
                                // Set sort and stock
                                if (isset($parts[4]) && $parts[4] == 'sort' 
                                    && isset($parts[6]) && $parts[6] == 'stock') {
                                    $matches['sort'] = urldecode($parts[5]);
                                    $matches['stock'] = urldecode($parts[7]);
                                } elseif (isset($parts[4]) && $parts[4] == 'sort') {
                                    $matches['sort'] = urldecode($parts[5]);
                                } elseif (isset($parts[4]) && $parts[4] == 'stock') {
                                    $matches['stock'] = urldecode($parts[5]);
                                } 
                            } else {
                                // Set sort and stock
                                if (isset($parts[2]) && $parts[2] == 'sort' 
                                    && isset($parts[4]) && $parts[4] == 'stock') {
                                    $matches['sort'] = urldecode($parts[3]);
                                    $matches['stock'] = urldecode($parts[5]);
                                } elseif (isset($parts[2]) && $parts[2] == 'sort') {
                                    $matches['sort'] = urldecode($parts[3]);
                                } elseif (isset($parts[2]) && $parts[2] == 'stock') {
                                    $matches['stock'] = urldecode($parts[3]);
                                } 
                            }
                        }
                    }
                    break;

                case 'tag':
                
                    break;    

                case 'user':
                
                    break;
            }    
        } 

        // Check sort
        if (isset($matches['sort']) 
            && !in_array($matches['sort'], $this->sortList)) {
            unset($matches['sort']);
        }

        // Check stock
        if (isset($matches['stock']) 
            && !in_array($matches['stock'], array(0,1))) {
            unset($matches['stock']);
        }

        return $matches;
    }

    /**
     * assemble(): Defined by Route interface.
     *
     * @see    Route::assemble()
     * @param  array $params
     * @param  array $options
     * @return string
     */
    public function assemble(
        array $params = array(),
        array $options = array()
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
                && in_array($mergedParams['controller'], $this->controllerList)) 
        {
            $url['controller'] = $mergedParams['controller'];
        }

        // Set action
        if (!empty($mergedParams['action']) 
                && $mergedParams['action'] != 'index') 
        {
            $url['action'] = $mergedParams['action'];
        }
        
        // Set slug
        if (!empty($mergedParams['slug'])) {
            $url['slug'] = $mergedParams['slug'];
        }

        // Set page
        if (!empty($mergedParams['page'])) {
            $url['page'] = sprintf('page%s%s', 
                                   $this->paramDelimiter, 
                                   $mergedParams['page']);
        }

        // Set step
        if (!empty($mergedParams['step'])) {
            $url['step'] = sprintf('step%s%s', 
                                   $this->paramDelimiter, 
                                   $mergedParams['step']);
        }
        
        // Set sort
        if (!empty($mergedParams['sort']) 
            && in_array($mergedParams['sort'], $this->sortList)) 
        {
            $url['sort'] = sprintf('sort%s%s', 
                                   $this->paramDelimiter, 
                                   $mergedParams['sort']);
        }

        // Set stock
        if (!empty($mergedParams['stock']) 
            && in_array($mergedParams['stock'], array(0,1)))
        {
            $url['stock'] = sprintf('stock%s%s',                                    
                $this->paramDelimiter,                                   
                $mergedParams['stock']);
        }

        // Make url
        $url = implode($this->paramDelimiter, $url);

        if (empty($url)) {
            return $this->prefix;
        }
        return $this->paramDelimiter . $url;
    }
}
