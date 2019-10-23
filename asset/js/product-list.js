angular.module('shop')
    .config(['$routeProvider', 'piProvider', 'config',
        function ($routeProvider, piProvider, config) {

            //Get template url
            function tpl(name) {
                return config.assetRoot + name + '.html';
            }

            function resolve(action) {
                return {
                    data: ['$q', '$route', '$rootScope', '$location', 'server',
                        function ($q, $route, $rootScope, $location, server) {
                            var deferred = $q.defer();
                            var params = $route.current.params;
                            $('.ajax-spinner').show();

                            if (config.pageType == 'category') {
                                $location.search('category', config.categorySlug);
                            } else if (config.pageType == 'tag') {
                                $location.search('tag', config.tagTerm);
                            }

                            $rootScope.alert = 2;
                            server.get(action, params).success(function (data) {
                                data.filter = params;
                                deferred.resolve(data);
                                $rootScope.alert = '';
                            });
                            return deferred.promise;
                        }
                    ]
                };
            }

            $routeProvider.when('/search', {
                templateUrl: tpl('product-list'),
                controller: 'ListCtrl',
                resolve: resolve('search')
            }).otherwise({
                redirectTo: '/search'
            });

            piProvider.setHashPrefix();
            piProvider.addTranslations(config.t);
            piProvider.addAjaxInterceptors();
        }
    ])
    .service('server', ['$http', '$cacheFactory', 'config',
        function ($http, $cacheFactory, config) {

            var urlRoot = config.urlRoot;

            this.get = function (action, params) {
                return $http.get(urlRoot + action, {
                    params: params
                });
            }

            this.filterEmpty = function (obj) {
                var search = {};
                for (var i in obj) {
                    if (obj[i]) {
                        search[i] = obj[i];
                    }
                }
                return search;
            }

        }
    ])
    .controller('ListCtrl', ['$scope', '$location', '$timeout', 'data', 'config', 'server',
        function ($scope, $location, $timeout, data, config, server) {

            if (config.priceFilter) {
                $scope.slider = {
                    minValue: data.price.minSelect,
                    maxValue: data.price.maxSelect,
                    options: {
                        floor: data.price.minValue,
                        ceil: data.price.maxValue,
                        step: data.price.step,
                        rightToLeft: data.price.rightToLeft,
                        translate: function (value, sliderId, label) {
                            switch (label) {
                                case 'model':
                                    return '<b>' + config.t.MIN_PRICE + ':</b> ' + config.t.PRICE_SYMBOL + value;
                                case 'high':
                                    return '<b>' + config.t.MAX_PRICE + ':</b> ' + config.t.PRICE_SYMBOL + value;
                                default:
                                    return config.t.PRICE_SYMBOL + value
                            }
                        }
                    }
                };

                $scope.$watch('slider.minValue', function (newValue, oldValue) {
                    if (newValue === oldValue) {
                        return
                    } else {
                        $timeout(function () {
                            if (newValue === data.price.minValue) {
                                $location.search('minPrice', null);
                            } else {
                                $location.search('minPrice', $scope.slider.minValue);
                            }
                        }, 1500);
                    }
                    ;
                });

                $scope.$watch('slider.maxValue', function (newValue, oldValue) {
                    if (newValue === oldValue) {
                        return
                    } else {
                        $timeout(function () {
                            if (newValue === data.price.maxValue) {
                                $location.search('maxPrice', null);
                            } else {
                                $location.search('maxPrice', $scope.slider.maxValue);
                            }
                        }, 1500);
                    }
                    ;
                });
            }

            angular.extend($scope, data);

            $scope.$watch('paginator.page', function (newValue, oldValue) {
                if (newValue === oldValue) return;
                $location.search('page', newValue);
            });

            $scope.filterAction = function () {
                $location.search(server.filterEmpty($scope.filter));
                $location.search('page', null);
            }

            // compare products
            var compareCount = 0;
            var compareList = [];
            $(document).on("click", ".pi-item-compare-add", function () {
                if (compareCount < 5) {
                    if (jQuery.inArray($(this).attr("data-slug"), compareList) !== -1) {
                        $('#compareModal .modal-body').html(config.t.COMPARE_MESSAGE_2);
                        $('#compareModal').modal('show');
                    } else {
                        compareList.push($(this).attr("data-slug"));
                        compareCount = compareCount + 1;

                        var url = $('.pi-item-compare-button a').attr('href');
                        url = url + '/' + $(this).attr("data-slug");

                        $('.pi-item-compare-button a').attr("href", url);
                        $(".pi-item-compare-list").removeClass("invisible");
                        $(".pi-item-compare-list .clearfix").append("<div class='col-lg-2 col-md-2'><div class='card'><img class='card-img-top' src='" + $(this).attr("data-image") + "' alt='" + $(this).attr("data-title") + "'><div class='card-body'><h4 class='card-title'>" + $(this).attr("data-title") + "</h4></div></div></div>");
                    }
                } else {
                    $('#compareModal .modal-body').html(config.t.COMPARE_MESSAGE_1);
                    $('#compareModal').modal('show');
                }
            });

            // category list
            $(function () {
                $('.pi-item-category').treeview({
                    levels: 1,
                    data: config.categoryJson,
                    enableLinks: true,
                    expandIcon: 'fas fa-plus',
                    collapseIcon: 'fas fa-minus',
                    emptyIcon: 'fas',
                    checkedIcon: 'far fa-check-square',
                    uncheckedIcon: 'far fa-square',
                });
            });

            $('.ajax-spinner').hide();
        }
    ]);