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
            $matches['controller'] = $this->decode($parts[0]);
        }

        // Make Match
        if (isset($matches['controller'])) {
            switch ($matches['controller']) {
                case 'category':
                    if (!empty($parts[1])) {
                        $matches['action'] = $this->decode($parts[1]);
                        $matches['slug'] = $this->decode($parts[1]);
                        // Set page
                        if (isset($parts[2]) && $parts[2] == 'page') {
                            $matches['page'] = intval($parts[3]);
                            // Set sort and stock
                            if (isset($parts[4]) && $parts[4] == 'sort' 
                                && isset($parts[6]) && $parts[6] == 'stock') {
                                $matches['sort'] = $this->decode($parts[5]);
                                $matches['stock'] = $this->decode($parts[7]);
                            } elseif (isset($parts[4]) && $parts[4] == 'sort') {
                                $matches['sort'] = $this->decode($parts[5]);
                            } elseif (isset($parts[4]) && $parts[4] == 'stock') {
                                $matches['stock'] = $this->decode($parts[5]);
                            } 
                        } else {
                            // Set sort and stock
                            if (isset($parts[2]) && $parts[2] == 'sort' 
                                && isset($parts[4]) && $parts[4] == 'stock') {
                                $matches['sort'] = $this->decode($parts[3]);
                                $matches['stock'] = $this->decode($parts[5]);
                            } elseif (isset($parts[2]) && $parts[2] == 'sort') {
                                $matches['sort'] = $this->decode($parts[3]);
                            } elseif (isset($parts[2]) && $parts[2] == 'stock') {
                                $matches['stock'] = $this->decode($parts[3]);
                            } 
                        }
                    }
                    break;

                case 'checkout':
                    if (!empty($parts[1])) {
                        if ($parts[1] == 'information') {
                            $matches['action'] = 'information';
                        } elseif ($parts[1] == 'add') {
                            $matches['action'] = 'add';
                            $matches['slug'] = $this->decode($parts[2]);
                        } elseif ($parts[1] == 'finish') {    
                            $matches['action'] = 'finish';
                            $matches['id'] = intval($parts[2]);
                        } elseif ($parts[1] == 'empty') {
                            $matches['action'] = 'empty';
                        } elseif ($parts[1] == 'cart') {
                            $matches['action'] = 'cart';
                        } elseif ($parts[1] == 'levelAjax') {
                            $matches['action'] = 'levelAjax';
                            $matches['process'] = $this->decode($parts[2]);
                            if (is_numeric($parts[3])) {
                                $matches['id'] = intval($parts[3]);
                            } elseif ($parts[2] == 'payment') {
                                $matches['id'] = $this->decode($parts[3]);
                            }
                        } elseif ($parts[1] == 'cartAjax') {    
                            $matches['action'] = 'cartAjax';
                        } elseif ($parts[1] == 'basketAjax') {
                            $matches['action'] = 'basketAjax';
                            if (isset($parts[2]) &&  in_array($parts[2], array('remove', 'number'))) {
                                $matches['process'] = $this->decode($parts[2]);
                                $matches['product'] = intval($parts[3]);
                                if (isset($parts[4]) && in_array($parts[4], array(1, -1))) {
                                    $matches['number'] = $parts[4];
                                }
                            }
                        }   
                    }
                    break; 

                case 'index':
                    // Set page
                    if (isset($parts[0]) && $parts[0] == 'page') {
                        $matches['page'] = intval($parts[1]);
                        // Set sort and stock
                        if (isset($parts[2]) && $parts[2] == 'sort' 
                            && isset($parts[4]) && $parts[4] == 'stock') {
                            $matches['sort'] = $this->decode($parts[3]);
                            $matches['stock'] = $this->decode($parts[5]);
                        } elseif (isset($parts[2]) && $parts[2] == 'sort') {
                            $matches['sort'] = $this->decode($parts[3]);
                        } elseif (isset($parts[2]) && $parts[2] == 'stock') {
                            $matches['stock'] = $this->decode($parts[3]);
                        }
                    } else {
                        // Set sort and stock
                        if (isset($parts[0]) && $parts[0] == 'sort' 
                            && isset($parts[2]) && $parts[2] == 'stock') {
                            $matches['sort'] = $this->decode($parts[1]);
                            $matches['stock'] = $this->decode($parts[3]);
                        } elseif (isset($parts[0]) && $parts[0] == 'sort') {
                            $matches['sort'] = $this->decode($parts[1]);
                        } elseif (isset($parts[0]) && $parts[0] == 'stock') {
                            $matches['stock'] = $this->decode($parts[1]);
                        }
                    }
                     
                    break;

                case 'product':
                    if (!empty($parts[1])) {
                        if ($parts[1] == 'print') {
                            $matches['action'] = 'print';
                            $matches['slug'] = $this->decode($parts[2]);
                        } elseif($parts[1] == 'review') {   
                            $matches['action'] = 'review';
                            $matches['slug'] = $this->decode($parts[2]);
                        } else {
                            $matches['slug'] = $this->decode($parts[1]);
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
                                    $matches['sort'] = $this->decode($parts[5]);
                                    $matches['stock'] = $this->decode($parts[7]);
                                } elseif (isset($parts[4]) && $parts[4] == 'sort') {
                                    $matches['sort'] = $this->decode($parts[5]);
                                } elseif (isset($parts[4]) && $parts[4] == 'stock') {
                                    $matches['stock'] = $this->decode($parts[5]);
                                } 
                            } else {
                                // Set sort and stock
                                if (isset($parts[2]) && $parts[2] == 'sort' 
                                    && isset($parts[4]) && $parts[4] == 'stock') {
                                    $matches['sort'] = $this->decode($parts[3]);
                                    $matches['stock'] = $this->decode($parts[5]);
                                } elseif (isset($parts[2]) && $parts[2] == 'sort') {
                                    $matches['sort'] = $this->decode($parts[3]);
                                } elseif (isset($parts[2]) && $parts[2] == 'stock') {
                                    $matches['stock'] = $this->decode($parts[3]);
                                } 
                            }
                        }
                    }
                    break;

                case 'tag':
                    if (!empty($parts[1])) {
                        if ($parts[1] == 'term') {
                            $matches['action'] = 'term';
                            $matches['slug'] = $this->decode($parts[2]);
                            // Set page
                            if (isset($parts[3]) && $parts[3] == 'page') {
                                $matches['page'] = intval($parts[4]);
                                // Set sort and stock
                                if (isset($parts[5]) && $parts[5] == 'sort' 
                                    && isset($parts[7]) && $parts[7] == 'stock') {
                                    $matches['sort'] = $this->decode($parts[6]);
                                    $matches['stock'] = $this->decode($parts[8]);
                                } elseif (isset($parts[5]) && $parts[5] == 'sort') {
                                    $matches['sort'] = $this->decode($parts[6]);
                                } elseif (isset($parts[5]) && $parts[5] == 'stock') {
                                    $matches['stock'] = $this->decode($parts[6]);
                                } 
                            } else {
                                // Set sort and stock
                                if (isset($parts[3]) && $parts[3] == 'sort' 
                                    && isset($parts[5]) && $parts[5] == 'stock') {
                                    $matches['sort'] = $this->decode($parts[4]);
                                    $matches['stock'] = $this->decode($parts[6]);
                                } elseif (isset($parts[3]) && $parts[3] == 'sort') {
                                    $matches['sort'] = $this->decode($parts[4]);
                                } elseif (isset($parts[3]) && $parts[3] == 'stock') {
                                    $matches['stock'] = $this->decode($parts[4]);
                                } 
                            }
                        } elseif ($parts[1] == 'list') {
                            $matches['action'] = 'list';
                        }
                    }
                    break;    

                case 'user':
                    if (!empty($parts[1])) {
                        if ($parts[1] == 'order') {
                            $matches['action'] = 'order';
                            $matches['id'] = intval($parts[2]);
                        }  
                    }
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

        // Set if controller is checkou
        if ($mergedParams['controller'] == 'checkout') {
            if ($mergedParams['action'] == 'basketAjax') {    
                $url['process'] = $mergedParams['process'];
                $url['product'] = $mergedParams['product'];
                if (!empty($mergedParams['number'])) {
                    $url['number'] = $mergedParams['number'];
                }
            } elseif ($mergedParams['action'] == 'levelAjax') {
                $url['process'] = $mergedParams['process'];
            }
            $url['id'] = $mergedParams['id'];
        }

        // Set if controller is user
        if ($mergedParams['controller'] == 'user') {
            if ($mergedParams['action'] == 'order') {
                $url['id'] = $mergedParams['id'];
            }   
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
