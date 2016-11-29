/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};

/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {

/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId])
/******/ 			return installedModules[moduleId].exports;

/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			exports: {},
/******/ 			id: moduleId,
/******/ 			loaded: false
/******/ 		};

/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);

/******/ 		// Flag the module as loaded
/******/ 		module.loaded = true;

/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}


/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;

/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;

/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";

/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ function(module, exports) {

	eval("'use strict';\n\nvar App = Vue.component('app', {\n    ready: function ready() {\n        //Set Sugar to use Australian date formatting\n        Date.setLocale('en-AU');\n    }\n});\n\nvar router = new VueRouter({\n    hashbang: false\n});\n\nrouter.map({\n    '/': {\n        component: ItemsPage,\n        subRoutes: {\n            //default for if no id is specified\n            '/': {\n                component: Item\n            },\n            '/:id': {\n                component: Item\n            }\n        }\n    },\n    '/items': {\n        component: ItemsPage,\n        subRoutes: {\n            //default for if no id is specified\n            '/': {\n                component: Item\n            },\n            '/:id': {\n                component: Item\n            }\n        }\n    },\n    '/categories': {\n        component: Categories\n    },\n    '/trash': {\n        component: Trash\n    },\n    '/feedback': {\n        component: FeedbackPage\n    },\n    '/help': {\n        component: HelpPage\n    }\n});\n\nrouter.start(App, 'body');\n\n//new Vue({\n//    el: 'body',\n//    events: {\n//\n//    }\n//});//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIi4vcmVzb3VyY2VzL2Fzc2V0cy9qcy9hcHAuanMiXSwibmFtZXMiOlsiQXBwIiwiVnVlIiwiY29tcG9uZW50IiwicmVhZHkiLCJEYXRlIiwic2V0TG9jYWxlIiwicm91dGVyIiwiVnVlUm91dGVyIiwiaGFzaGJhbmciLCJtYXAiLCJJdGVtc1BhZ2UiLCJzdWJSb3V0ZXMiLCJJdGVtIiwiQ2F0ZWdvcmllcyIsIlRyYXNoIiwiRmVlZGJhY2tQYWdlIiwiSGVscFBhZ2UiLCJzdGFydCJdLCJtYXBwaW5ncyI6Ijs7QUFDQSxJQUFJQSxNQUFNQyxJQUFJQyxTQUFKLENBQWMsS0FBZCxFQUFxQjtBQUMzQkMsV0FBTyxpQkFBWTtBQUNmO0FBQ0FDLGFBQUtDLFNBQUwsQ0FBZSxPQUFmO0FBQ0g7QUFKMEIsQ0FBckIsQ0FBVjs7QUFPQSxJQUFJQyxTQUFTLElBQUlDLFNBQUosQ0FBYztBQUN2QkMsY0FBVTtBQURhLENBQWQsQ0FBYjs7QUFJQUYsT0FBT0csR0FBUCxDQUFXO0FBQ1AsU0FBSztBQUNEUCxtQkFBV1EsU0FEVjtBQUVEQyxtQkFBVztBQUNQO0FBQ0EsaUJBQUs7QUFDRFQsMkJBQVdVO0FBRFYsYUFGRTtBQUtQLG9CQUFRO0FBQ0pWLDJCQUFXVTtBQURQO0FBTEQ7QUFGVixLQURFO0FBYVAsY0FBVTtBQUNOVixtQkFBV1EsU0FETDtBQUVOQyxtQkFBVztBQUNQO0FBQ0EsaUJBQUs7QUFDRFQsMkJBQVdVO0FBRFYsYUFGRTtBQUtQLG9CQUFRO0FBQ0pWLDJCQUFXVTtBQURQO0FBTEQ7QUFGTCxLQWJIO0FBeUJQLG1CQUFlO0FBQ1hWLG1CQUFXVztBQURBLEtBekJSO0FBNEJQLGNBQVU7QUFDTlgsbUJBQVdZO0FBREwsS0E1Qkg7QUErQlAsaUJBQWE7QUFDVFosbUJBQVdhO0FBREYsS0EvQk47QUFrQ1AsYUFBUztBQUNMYixtQkFBV2M7QUFETjtBQWxDRixDQUFYOztBQXVDQVYsT0FBT1csS0FBUCxDQUFhakIsR0FBYixFQUFrQixNQUFsQjs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EiLCJmaWxlIjoiMC5qcyIsInNvdXJjZXNDb250ZW50IjpbIlxudmFyIEFwcCA9IFZ1ZS5jb21wb25lbnQoJ2FwcCcsIHtcbiAgICByZWFkeTogZnVuY3Rpb24gKCkge1xuICAgICAgICAvL1NldCBTdWdhciB0byB1c2UgQXVzdHJhbGlhbiBkYXRlIGZvcm1hdHRpbmdcbiAgICAgICAgRGF0ZS5zZXRMb2NhbGUoJ2VuLUFVJyk7XG4gICAgfVxufSk7XG5cbnZhciByb3V0ZXIgPSBuZXcgVnVlUm91dGVyKHtcbiAgICBoYXNoYmFuZzogZmFsc2Vcbn0pO1xuXG5yb3V0ZXIubWFwKHtcbiAgICAnLyc6IHtcbiAgICAgICAgY29tcG9uZW50OiBJdGVtc1BhZ2UsXG4gICAgICAgIHN1YlJvdXRlczoge1xuICAgICAgICAgICAgLy9kZWZhdWx0IGZvciBpZiBubyBpZCBpcyBzcGVjaWZpZWRcbiAgICAgICAgICAgICcvJzoge1xuICAgICAgICAgICAgICAgIGNvbXBvbmVudDogSXRlbVxuICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICcvOmlkJzoge1xuICAgICAgICAgICAgICAgIGNvbXBvbmVudDogSXRlbVxuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgfSxcbiAgICAnL2l0ZW1zJzoge1xuICAgICAgICBjb21wb25lbnQ6IEl0ZW1zUGFnZSxcbiAgICAgICAgc3ViUm91dGVzOiB7XG4gICAgICAgICAgICAvL2RlZmF1bHQgZm9yIGlmIG5vIGlkIGlzIHNwZWNpZmllZFxuICAgICAgICAgICAgJy8nOiB7XG4gICAgICAgICAgICAgICAgY29tcG9uZW50OiBJdGVtXG4gICAgICAgICAgICB9LFxuICAgICAgICAgICAgJy86aWQnOiB7XG4gICAgICAgICAgICAgICAgY29tcG9uZW50OiBJdGVtXG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICB9LFxuICAgICcvY2F0ZWdvcmllcyc6IHtcbiAgICAgICAgY29tcG9uZW50OiBDYXRlZ29yaWVzXG4gICAgfSxcbiAgICAnL3RyYXNoJzoge1xuICAgICAgICBjb21wb25lbnQ6IFRyYXNoXG4gICAgfSxcbiAgICAnL2ZlZWRiYWNrJzoge1xuICAgICAgICBjb21wb25lbnQ6IEZlZWRiYWNrUGFnZVxuICAgIH0sXG4gICAgJy9oZWxwJzoge1xuICAgICAgICBjb21wb25lbnQ6IEhlbHBQYWdlXG4gICAgfVxufSk7XG5cbnJvdXRlci5zdGFydChBcHAsICdib2R5Jyk7XG5cbi8vbmV3IFZ1ZSh7XG4vLyAgICBlbDogJ2JvZHknLFxuLy8gICAgZXZlbnRzOiB7XG4vL1xuLy8gICAgfVxuLy99KTtcblxuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vcmVzb3VyY2VzL2Fzc2V0cy9qcy9hcHAuanMiXSwic291cmNlUm9vdCI6IiJ9");

/***/ }
/******/ ]);