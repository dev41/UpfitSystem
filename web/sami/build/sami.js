
window.projectVersion = 'master';

(function(root) {

    var bhIndex = null;
    var rootPath = '';
    var treeHtml = '        <ul>                <li data-name="namespace:api" class="opened">                    <div style="padding-left:0px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="api.html">api</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="namespace:api_controllers" class="opened">                    <div style="padding-left:18px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="api/controllers.html">controllers</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:api_controllers_ActivityController" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="api/controllers/ActivityController.html">ActivityController</a>                    </div>                </li>                            <li data-name="class:api_controllers_ClubController" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="api/controllers/ClubController.html">ClubController</a>                    </div>                </li>                            <li data-name="class:api_controllers_CoachingController" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="api/controllers/CoachingController.html">CoachingController</a>                    </div>                </li>                            <li data-name="class:api_controllers_EventController" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="api/controllers/EventController.html">EventController</a>                    </div>                </li>                            <li data-name="class:api_controllers_NewsController" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="api/controllers/NewsController.html">NewsController</a>                    </div>                </li>                            <li data-name="class:api_controllers_SaleController" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="api/controllers/SaleController.html">SaleController</a>                    </div>                </li>                            <li data-name="class:api_controllers_SubplaceController" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="api/controllers/SubplaceController.html">SubplaceController</a>                    </div>                </li>                            <li data-name="class:api_controllers_UserController" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="api/controllers/UserController.html">UserController</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="namespace:api_models" class="opened">                    <div style="padding-left:18px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="api/models.html">models</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:api_models_AvailableApiField" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="api/models/AvailableApiField.html">AvailableApiField</a>                    </div>                </li>                </ul></div>                </li>                </ul></div>                </li>                            <li data-name="namespace:app" class="opened">                    <div style="padding-left:0px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="app.html">app</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="namespace:app_src" class="opened">                    <div style="padding-left:18px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="app/src.html">src</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="namespace:app_src_exception" >                    <div style="padding-left:36px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="app/src/exception.html">exception</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:app_src_exception_ApiException" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="app/src/exception/ApiException.html">ApiException</a>                    </div>                </li>                </ul></div>                </li>                </ul></div>                </li>                </ul></div>                </li>                </ul>';

    var searchTypeClasses = {
        'Namespace': 'label-default',
        'Class': 'label-info',
        'Interface': 'label-primary',
        'Trait': 'label-success',
        'Method': 'label-danger',
        '_': 'label-warning'
    };

    var searchIndex = [
                    
            {"type": "Namespace", "link": "api.html", "name": "api", "doc": "Namespace api"},{"type": "Namespace", "link": "api/controllers.html", "name": "api\\controllers", "doc": "Namespace api\\controllers"},{"type": "Namespace", "link": "api/models.html", "name": "api\\models", "doc": "Namespace api\\models"},{"type": "Namespace", "link": "app.html", "name": "app", "doc": "Namespace app"},{"type": "Namespace", "link": "app/src.html", "name": "app\\src", "doc": "Namespace app\\src"},{"type": "Namespace", "link": "app/src/exception.html", "name": "app\\src\\exception", "doc": "Namespace app\\src\\exception"},
            
            {"type": "Class", "fromName": "api\\controllers", "fromLink": "api/controllers.html", "link": "api/controllers/ActivityController.html", "name": "api\\controllers\\ActivityController", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "api\\controllers\\ActivityController", "fromLink": "api/controllers/ActivityController.html", "link": "api/controllers/ActivityController.html#method_actionIndex", "name": "api\\controllers\\ActivityController::actionIndex", "doc": "&quot;Returns activity list&quot;"},
            
            {"type": "Class", "fromName": "api\\controllers", "fromLink": "api/controllers.html", "link": "api/controllers/ClubController.html", "name": "api\\controllers\\ClubController", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "api\\controllers\\ClubController", "fromLink": "api/controllers/ClubController.html", "link": "api/controllers/ClubController.html#method_actionIndex", "name": "api\\controllers\\ClubController::actionIndex", "doc": "&quot;Returns clubs list&quot;"},
                    {"type": "Method", "fromName": "api\\controllers\\ClubController", "fromLink": "api/controllers/ClubController.html", "link": "api/controllers/ClubController.html#method_actionDetails", "name": "api\\controllers\\ClubController::actionDetails", "doc": "&quot;Returns the info about club&quot;"},
            
            {"type": "Class", "fromName": "api\\controllers", "fromLink": "api/controllers.html", "link": "api/controllers/CoachingController.html", "name": "api\\controllers\\CoachingController", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "api\\controllers\\CoachingController", "fromLink": "api/controllers/CoachingController.html", "link": "api/controllers/CoachingController.html#method_actionIndex", "name": "api\\controllers\\CoachingController::actionIndex", "doc": "&quot;Returns the info about coaching&quot;"},
            
            {"type": "Class", "fromName": "api\\controllers", "fromLink": "api/controllers.html", "link": "api/controllers/EventController.html", "name": "api\\controllers\\EventController", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "api\\controllers\\EventController", "fromLink": "api/controllers/EventController.html", "link": "api/controllers/EventController.html#method_actionIndex", "name": "api\\controllers\\EventController::actionIndex", "doc": "&quot;Returns the info about coaching&quot;"},
                    {"type": "Method", "fromName": "api\\controllers\\EventController", "fromLink": "api/controllers/EventController.html", "link": "api/controllers/EventController.html#method_actionAddCustomer", "name": "api\\controllers\\EventController::actionAddCustomer", "doc": "&quot;Add user to event&quot;"},
                    {"type": "Method", "fromName": "api\\controllers\\EventController", "fromLink": "api/controllers/EventController.html", "link": "api/controllers/EventController.html#method_actionLeaveEvent", "name": "api\\controllers\\EventController::actionLeaveEvent", "doc": "&quot;Add user to event&quot;"},
            
            {"type": "Class", "fromName": "api\\controllers", "fromLink": "api/controllers.html", "link": "api/controllers/NewsController.html", "name": "api\\controllers\\NewsController", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "api\\controllers\\NewsController", "fromLink": "api/controllers/NewsController.html", "link": "api/controllers/NewsController.html#method_actionIndex", "name": "api\\controllers\\NewsController::actionIndex", "doc": "&quot;Returns news list&quot;"},
            
            {"type": "Class", "fromName": "api\\controllers", "fromLink": "api/controllers.html", "link": "api/controllers/SaleController.html", "name": "api\\controllers\\SaleController", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "api\\controllers\\SaleController", "fromLink": "api/controllers/SaleController.html", "link": "api/controllers/SaleController.html#method_actionIndex", "name": "api\\controllers\\SaleController::actionIndex", "doc": "&quot;Returns sale list&quot;"},
            
            {"type": "Class", "fromName": "api\\controllers", "fromLink": "api/controllers.html", "link": "api/controllers/SubplaceController.html", "name": "api\\controllers\\SubplaceController", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "api\\controllers\\SubplaceController", "fromLink": "api/controllers/SubplaceController.html", "link": "api/controllers/SubplaceController.html#method_actionIndex", "name": "api\\controllers\\SubplaceController::actionIndex", "doc": "&quot;Returns the info about places clubs&quot;"},
            
            {"type": "Class", "fromName": "api\\controllers", "fromLink": "api/controllers.html", "link": "api/controllers/UserController.html", "name": "api\\controllers\\UserController", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "api\\controllers\\UserController", "fromLink": "api/controllers/UserController.html", "link": "api/controllers/UserController.html#method_actionLogin", "name": "api\\controllers\\UserController::actionLogin", "doc": "&quot;Login user by identity&quot;"},
                    {"type": "Method", "fromName": "api\\controllers\\UserController", "fromLink": "api/controllers/UserController.html", "link": "api/controllers/UserController.html#method_actionLogout", "name": "api\\controllers\\UserController::actionLogout", "doc": "&quot;Logout from system&quot;"},
                    {"type": "Method", "fromName": "api\\controllers\\UserController", "fromLink": "api/controllers/UserController.html", "link": "api/controllers/UserController.html#method_actionRegister", "name": "api\\controllers\\UserController::actionRegister", "doc": "&quot;Register user by identity&quot;"},
                    {"type": "Method", "fromName": "api\\controllers\\UserController", "fromLink": "api/controllers/UserController.html", "link": "api/controllers/UserController.html#method_actionLoginFb", "name": "api\\controllers\\UserController::actionLoginFb", "doc": "&quot;Login by Facebook&quot;"},
                    {"type": "Method", "fromName": "api\\controllers\\UserController", "fromLink": "api/controllers/UserController.html", "link": "api/controllers/UserController.html#method_actionRegisterFb", "name": "api\\controllers\\UserController::actionRegisterFb", "doc": "&quot;Register user by Facebook&quot;"},
                    {"type": "Method", "fromName": "api\\controllers\\UserController", "fromLink": "api/controllers/UserController.html", "link": "api/controllers/UserController.html#method_actionUpdate", "name": "api\\controllers\\UserController::actionUpdate", "doc": "&quot;Update user by id&quot;"},
                    {"type": "Method", "fromName": "api\\controllers\\UserController", "fromLink": "api/controllers/UserController.html", "link": "api/controllers/UserController.html#method_actionRequestCustomerToClub", "name": "api\\controllers\\UserController::actionRequestCustomerToClub", "doc": "&quot;Request customer to club&quot;"},
                    {"type": "Method", "fromName": "api\\controllers\\UserController", "fromLink": "api/controllers/UserController.html", "link": "api/controllers/UserController.html#method_actionLeaveClub", "name": "api\\controllers\\UserController::actionLeaveClub", "doc": "&quot;Customer leave the club&quot;"},
                    {"type": "Method", "fromName": "api\\controllers\\UserController", "fromLink": "api/controllers/UserController.html", "link": "api/controllers/UserController.html#method_actionGetUserInfo", "name": "api\\controllers\\UserController::actionGetUserInfo", "doc": "&quot;Get user info by id&quot;"},
            
            {"type": "Class", "fromName": "api\\models", "fromLink": "api/models.html", "link": "api/models/AvailableApiField.html", "name": "api\\models\\AvailableApiField", "doc": "&quot;&quot;"},
                    
            {"type": "Class", "fromName": "app\\src\\exception", "fromLink": "app/src/exception.html", "link": "app/src/exception/ApiException.html", "name": "app\\src\\exception\\ApiException", "doc": "&quot;&quot;"},
                    
            
                                        // Fix trailing commas in the index
        {}
    ];

    /** Tokenizes strings by namespaces and functions */
    function tokenizer(term) {
        if (!term) {
            return [];
        }

        var tokens = [term];
        var meth = term.indexOf('::');

        // Split tokens into methods if "::" is found.
        if (meth > -1) {
            tokens.push(term.substr(meth + 2));
            term = term.substr(0, meth - 2);
        }

        // Split by namespace or fake namespace.
        if (term.indexOf('\\') > -1) {
            tokens = tokens.concat(term.split('\\'));
        } else if (term.indexOf('_') > 0) {
            tokens = tokens.concat(term.split('_'));
        }

        // Merge in splitting the string by case and return
        tokens = tokens.concat(term.match(/(([A-Z]?[^A-Z]*)|([a-z]?[^a-z]*))/g).slice(0,-1));

        return tokens;
    };

    root.Sami = {
        /**
         * Cleans the provided term. If no term is provided, then one is
         * grabbed from the query string "search" parameter.
         */
        cleanSearchTerm: function(term) {
            // Grab from the query string
            if (typeof term === 'undefined') {
                var name = 'search';
                var regex = new RegExp("[\\?&]" + name + "=([^&#]*)");
                var results = regex.exec(location.search);
                if (results === null) {
                    return null;
                }
                term = decodeURIComponent(results[1].replace(/\+/g, " "));
            }

            return term.replace(/<(?:.|\n)*?>/gm, '');
        },

        /** Searches through the index for a given term */
        search: function(term) {
            // Create a new search index if needed
            if (!bhIndex) {
                bhIndex = new Bloodhound({
                    limit: 500,
                    local: searchIndex,
                    datumTokenizer: function (d) {
                        return tokenizer(d.name);
                    },
                    queryTokenizer: Bloodhound.tokenizers.whitespace
                });
                bhIndex.initialize();
            }

            results = [];
            bhIndex.get(term, function(matches) {
                results = matches;
            });

            if (!rootPath) {
                return results;
            }

            // Fix the element links based on the current page depth.
            return $.map(results, function(ele) {
                if (ele.link.indexOf('..') > -1) {
                    return ele;
                }
                ele.link = rootPath + ele.link;
                if (ele.fromLink) {
                    ele.fromLink = rootPath + ele.fromLink;
                }
                return ele;
            });
        },

        /** Get a search class for a specific type */
        getSearchClass: function(type) {
            return searchTypeClasses[type] || searchTypeClasses['_'];
        },

        /** Add the left-nav tree to the site */
        injectApiTree: function(ele) {
            ele.html(treeHtml);
        }
    };

    $(function() {
        // Modify the HTML to work correctly based on the current depth
        rootPath = $('body').attr('data-root-path');
        treeHtml = treeHtml.replace(/href="/g, 'href="' + rootPath);
        Sami.injectApiTree($('#api-tree'));
    });

    return root.Sami;
})(window);

$(function() {

    // Enable the version switcher
    $('#version-switcher').change(function() {
        window.location = $(this).val()
    });

    
        // Toggle left-nav divs on click
        $('#api-tree .hd span').click(function() {
            $(this).parent().parent().toggleClass('opened');
        });

        // Expand the parent namespaces of the current page.
        var expected = $('body').attr('data-name');

        if (expected) {
            // Open the currently selected node and its parents.
            var container = $('#api-tree');
            var node = $('#api-tree li[data-name="' + expected + '"]');
            // Node might not be found when simulating namespaces
            if (node.length > 0) {
                node.addClass('active').addClass('opened');
                node.parents('li').addClass('opened');
                var scrollPos = node.offset().top - container.offset().top + container.scrollTop();
                // Position the item nearer to the top of the screen.
                scrollPos -= 200;
                container.scrollTop(scrollPos);
            }
        }

    
    
        var form = $('#search-form .typeahead');
        form.typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        }, {
            name: 'search',
            displayKey: 'name',
            source: function (q, cb) {
                cb(Sami.search(q));
            }
        });

        // The selection is direct-linked when the user selects a suggestion.
        form.on('typeahead:selected', function(e, suggestion) {
            window.location = suggestion.link;
        });

        // The form is submitted when the user hits enter.
        form.keypress(function (e) {
            if (e.which == 13) {
                $('#search-form').submit();
                return true;
            }
        });

    
});


