/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/build/";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./public/assets/js/base.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/jquery/dist/jquery.js":
/*!********************************************!*\
  !*** ./node_modules/jquery/dist/jquery.js ***!
  \********************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports) {

throw new Error("Module build failed: Error: EPERM: operation not permitted, open 'D:\\Web\\P6\\node_modules\\jquery\\dist\\jquery.js'");

/***/ }),

/***/ "./public/assets/js/base.js":
/*!**********************************!*\
  !*** ./public/assets/js/base.js ***!
  \**********************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var $ = __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js");

$(document).ready(function () {

    /**
     *        AJAX FUNCTIONS
     **/

    // Adding Thumbnail Tricks
    if ($(".main-tricks").length > 0) {
        $(window).scroll(function () {
            if ($(window).scrollTop() >= $(document).height() - $(window).height() - 10) {
                $.post(window.location.href, "nb=5", addingTricks, "text");
            }
        });
    }

    function addingTricks(data) {
        $(".main-tricks").append(data);
        trickThumbnailHeight();
        arrowUp();
    }

    /**
     * Height's Thumbnail Adjusting
     */
    function trickThumbnailHeight() {
        var trickHeight = [];
        $(".main-trick-header").each(function () {
            trickHeight.push($(this).height());
        });
        var maxHeight = Math.max.apply(Math, trickHeight);
        $(".main-trick-header").height(maxHeight);
    }
    trickThumbnailHeight();

    /**
     * 		AJAX LOADING SPINNER
     **/
    var $spinner = $('.fa-spinner').hide();
    $(document).ajaxStart(function () {
        $spinner.show();
    }).ajaxStop(function () {
        $spinner.hide();
    });

    /**                                     ***
     *
     *              MAIN PAGE               ***
     *
     **                                     **/

    /**
     * 		Effects on trick hover Thumbnail
     **/
    $(document).on({
        mouseenter: function mouseenter() {
            $(".img", this).animate({
                left: '-10px',
                top: '-10px',
                marginBottom: '-20px',
                height: '+=20px',
                width: '+=20px'
            });
        },
        mouseleave: function mouseleave() {
            $(".img", this).animate({
                left: '0px',
                top: '0px',
                marginBottom: '0px',
                height: '-=20px',
                width: '-=20px'
            });
        }
    }, '.main-trick a');

    /**
     * 		ARROW UP
     **/
    // Display the fa-arrow-up element if >= 15 tricks shown
    function arrowUp() {
        if ($(".main-trick").length >= 30) {
            $(".fa-arrow-up").css("display", "block");
        }
    }
    // Scrolling animation for the fa-arrow-up element
    $(".fa-arrow-up").click(function () {
        $('html, body').animate({ scrollTop: $("#top").offset().top - 20 }, 500);
    });

    /**                                     ***
     *
     * 		       TRICK PAGE               ***
     *
     **                                     **/

    /**
     * 		Trick Hover Image Animation
     **/
    $(".media").css("padding", "15px"); // Because this does't work with CSS for really unexpected reasons
    $(".container-fluid").css("padding-left", "+=20px").css("padding-right", "+=20px");
    $(".media img").hover(function () {
        $(this).closest(".media").animate({
            padding: '0px'
        }), $("img", this).animate({
            top: '-15px',
            left: '-15px',
            height: '+=30px',
            width: '+=30px'
        });
    }, function () {
        $(this).closest(".media").animate({
            padding: '15px'
        }), $("img", this).animate({
            top: '0px',
            left: '0px',
            height: '-=30px',
            width: '-=30px'
        });
    });
});

/***/ })

/******/ });