$(document).ready(function () {

    const ajaxurl = '/wp-admin/admin-ajax.php';
    var current_page = 1;
    const products_per_page = 12;
    const metaKey = '_wc_average_rating';
    const order = 'ASC';
    const ajaxInput = '.ajax-input';
    var totalCount;
    var productsWrapSelect = '.catalogSection .cardsRow';


    class Filters {

        get getFilters () {

            const _filters = {};
            const filtersWrap = '.catalogSideBarWrap';

            const groups = $(filtersWrap).find('.sideBarBlock').map(function (index, item) {

                const _group = {};
                var groupData = [];

                const groupName = $(item).data('group');

                $(item).find('.sideBarBody .chbWrap')
                    .map(function (index, item) {

                        const filterInput = $(item).find('.ajax-input').map(function (index, item) {

                            return $(item).get();

                        });

                        groupData.push(filterInput);

                    });

                _group[groupName] = groupData;

                return _group;

            }).map(function (index, item) {

                Object.assign(_filters, item);

            });

            return _filters;

        }

        get checkedFilters() {

            const filtersObject = this.getFilters;
            const checkedFiltersObject = {};

            for (const groupKey in filtersObject) {

                const groupObject = filtersObject[groupKey];

                for (const groupObjectKey in groupObject) {

                    const inputsGroupObject = groupObject[groupObjectKey];

                    const checkedInputs = inputsGroupObject.filter(':checked');

                    if (checkedInputs.length !== 0) {

                        const checkedInputsObject = {};
                        checkedInputsObject[groupObjectKey] = checkedInputs;

                        checkedFiltersObject[groupKey] = Object.assign(checkedFiltersObject[groupKey] ? checkedFiltersObject[groupKey] : {}, checkedInputsObject);

                    }

                }

            }

            return checkedFiltersObject;

        }

        checkFilters() {

            $('.ajax-input:checked').prop('checked', false);

            var qs;

            if (typeof initQs == 'undefined') {
                qs = (function (a) {
                    if (a == "") return {};
                    var b = {};

                    for (var i = 0; i < a.length; ++i) {
                        var p = a[i].split('=', 2);

                        if (p.length == 1)
                            b[p[0]] = "";
                        else
                            b[p[0]] = decodeURIComponent(p[1].replace(/\+/g, " "));
                    }

                    return b;

                })(window.location.search.substr(1).split('&'));
            } else {
                qs = initQs;
            }

            Object.values(qs).map(function (value) {

                const slugArr = value.split(',');

                slugArr.map(function (slug) {

                    $('.ajax-input[data-id="'+slug+'"]').prop('checked', true).trigger('initCheckbox');

                });

            });

        }

    }

    class Query {

        constructor(filters, checkedFilters) {
            this.filters = filters;
            this.checkedFilters = checkedFilters;
        }

        get queryObject () {

            var queryObject = {};
            const filters = this.filters;

            Object.values(this.checkedFilters).map(function (checkedGroup) {

                Object.values(checkedGroup).map(function (inputGroup) {

                    inputGroup.get().map(function (input) {

                        const inputTerm = $(input).data('term');
                        const inputSlug = $(input).data('id');

                        if ($(input).parents('.filtersGroup').data('group') !== 'filter') {
                            queryObject[inputTerm] !== undefined ? queryObject[inputTerm].push(inputSlug) : queryObject[inputTerm] = [inputSlug];
                        } else {

                            if (!$(input).hasClass('generalCheckbox')) {
                                queryObject[inputTerm] !== undefined ? queryObject[inputTerm].push(inputSlug) : queryObject[inputTerm] = [inputSlug];
                            }

                        }

                    });

                });

            });

            return queryObject;

        };

        get queryString () {

            var queryString = '';
            const queryObj = this.queryObject;
            const queryKeys = Object.keys(queryObj);

            queryKeys.map(function (key) {

                const queryTerm = queryObj[key];

                queryString === '' ? queryString = key + '=' + queryTerm : queryString += '&' + key + '=' + queryTerm;

            });

            return queryString;

        };

        get prettyString() {

            var urlString = '';
            const filters = this.filters;
            const checkedFiltersObj = this.checkedFilters;
            const checkedFiltersObjKeys = Object.keys(checkedFiltersObj);

            function getPrettyString(keys, group, checkedFilters) {

                keys.map(function (filterKey) {

                    const filterInputs = filters[group][filterKey];

                    const checkedFilterInputs = checkedFilters[filterKey];

                    const generalInput = Object.values(filterInputs.get()).filter(function (input) {
                        return $(input).hasClass('ajax-input');
                    });

                    if (filterInputs.length === checkedFilterInputs.length && group !== 'filter') {

                        if ($(generalInput).data('term') === 'product_cat') {
                            urlString = 'category/' + $(generalInput).data('id') + '/';
                        } else if ($(generalInput).data('term') === 'product_tag') {
                            urlString = 'by/' + $(generalInput).data('id') + '/';
                        }

                    } else {

                        if (checkedFilterInputs.length === 1) {

                            if ($(generalInput).data('id') !== 'categories') {

                                if ($(generalInput).data('term') === 'product_cat') {
                                    urlString = 'category/' + $(generalInput).data('id') + '/';
                                } else if ($(generalInput).data('term') === 'product_tag') {
                                    urlString = 'by/' + $(generalInput).data('id') + '/';
                                } else {
                                    urlString = 'filter/' + $(generalInput).data('slug') + '/' + $(generalInput).data('id') + '/';
                                }

                            } else {
                                urlString = 'categories/' + $(generalInput).data('id') + '/';
                            }

                        } else {
                            urlString = '';
                        }

                    }

                });

            }

            if (checkedFiltersObjKeys.length === 1) {

                checkedFiltersObjKeys.map(function (group) {

                    const checkedFilters = checkedFiltersObj[group];
                    const checkedFiltersKeys = Object.keys(checkedFilters);

                    if (checkedFiltersKeys.length === 1) {

                        getPrettyString(checkedFiltersKeys, group, checkedFilters);

                    }

                });

            } else {
                urlString = '';
            }

            return urlString !== '' ? urlString : false;

        }

        pushQuery () {

            const modifyQueryString = this.queryString !== '' ? '?' + this.queryString : '';

            const url = this.prettyString !== false ? '/tours/' + this.prettyString : '/tours/' + modifyQueryString;

            history.pushState({query: url}, 'state' + url, url);

        }

    }


    function getProducts(query) {

        current_page = 1;

        var data = {
            'action': 'traveling_store_shop_filters',
            'query': query,
            'page': current_page,
            'meta_key': metaKey
        };

        if (metaKey && metaKey.length) {
            data['meta_key'] = metaKey;
            data['order'] = order;
        }

        $.ajax({
            url: ajaxurl,
            data: data,
            type: 'GET',
            success: function (data) {
                if (data) {

                    $(productsWrapSelect).empty().append(data);

                    // const filters = new Filters();
                    // const query = new Query(filters.getFilters, filters.checkedFilters);
                    //
                    // updatePagination(query.queryString);

                } else {

                    $(productsWrapSelect).empty().append('<div class="empty-product">Not found</div>');

                }
            }
        });

    }


    $(document).on('change', ajaxInput, function () {

        const filters = new Filters();
        const query = new Query(filters.getFilters, filters.checkedFilters);

        query.pushQuery();

        getProducts(query.queryString);

    });

    const filters = new Filters();
    filters.checkFilters();

});